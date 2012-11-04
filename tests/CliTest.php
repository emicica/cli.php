<?php
/**
 * CLI test
 */
require_once "PHPUnit/Autoload.php";
require_once "lib/Cli.php";

class CliTest extends PHPUnit_Framework_TestCase {

  /**
   * Test adding options
   */
  function testOption() {
    $cli = new Cli(array('cli.php'));
    $cli->option('-p, --peppers', 'Add peppers');
    $cli->option('-c, --cheese [type]', 'Add a cheese');

    $options = $cli->getOptions();

    $this->assertCount(4, $options);

    $this->assertArrayHasKey('p', $options);
    $this->assertArrayHasKey('c', $options);

    $this->assertArrayHasKey('peppers', $options);
    $this->assertArrayHasKey('cheese', $options);

    $this->assertTrue($options['cheese']['input']);
  }

  /**
   * Test get name
   */
  function testGetName() {
    $cli = new Cli(array(
      'cli.php',
      '-p'
    ));

    $this->assertEquals('cli.php', $cli->getName());

    $inputs = $cli->getInputs();
    $this->assertEquals('-p', $inputs[0]);
  }

  /**
   * Test parse
   */
  function testParse() {
    // php cli.php -p --cheese cheddar
    $cli = new Cli(array(
      'cli.php',
      '-p',
      '--cheese',
      'cheddar'
    ));
    $cli->option('-p, --peppers', 'Add peppers');
    $cli->option('-c, --cheese [type]', 'Add a cheese');

    $cli->parse();

    $this->assertTrue($cli->get('p'));
    $this->assertTrue($cli->get('peppers'));

    $this->assertEquals('cheddar', $cli->get('c'));
    $this->assertEquals('cheddar', $cli->get('cheese'));
  }

  /**
   * Test parsing non options
   */
  function testParsingNonOptions() {
    $cli = new Cli(array(
      'cli.php',
      'extra',
      '-p',
      '-b',
      '--cheese',
      'cheddar',
      'info'
    ));
    $cli->option('-p, --peppers', 'Add peppers');
    $cli->option('-c, --cheese [type]', 'Add a cheese');

    $cli->parse();

    $this->assertTrue($cli->get('p'));
    $this->assertTrue($cli->get('peppers'));

    $this->assertEquals('cheddar', $cli->get('c'));
    $this->assertEquals('cheddar', $cli->get('cheese'));

    $this->assertEquals('extra', $cli->get(0));
    $this->assertEquals('-b', $cli->get(1));
    $this->assertEquals('info', $cli->get(2));
  }

  /**
   * Test required
   */
  function testRequired() {
    $this->assertTrue(false);
  }
}