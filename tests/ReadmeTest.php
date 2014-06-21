<?php
/**
 * @author Todd Burry <todd@vanillaforums.com>
 * @copyright 2009-2014 Vanilla Forums Inc.
 * @license MIT
 */

use Garden\Cli\Cli;

/**
 * Provides tests for the sample code in README.md.
 */
class ReadmeTest extends PHPUnit_Framework_TestCase {

    /**
     * Test the basic example help.
     *
     * @expectedException \Exception
     */
    public function testBasicHelp() {
        $cli = $this->getBasicCli();
        $argv = ['dbdump.php', '--help'];

        // Parse and return cli args.
        $args = $cli->parse($argv, false);
    }

    /**
     * Test the basic example error output.
     *
     * @expectedException \Exception
     */
    public function testBasicError() {
        $cli = $this->getBasicCli();
        $argv = ['dbdump.php', '-P', 'foo'];

        $args = $cli->parse($argv, false);
    }

    /**
     * Test the basic example args.
     */
    public function testBasicArgs() {
        $cli = $this->getBasicCli();
        $argv = ['dbdump.php', '-hlocalhost', '-uroot', '--database=testdb'];

        $args = $cli->parse($argv, false);

        $host = $args->getOpt('host', '127.0.0.1'); // get host with default 127.0.0.1
        $user = $args->getOpt('user'); // get user
        $database = $args['database']; // use the args like an array too
        $port = $args->getOpt('port', 123); // get port with default 123

        $this->assertEquals('localhost', $host);
        $this->assertEquals('root', $user);
        $this->assertEquals('testdb', $database);
        $this->assertEquals(123, $port);
    }

    /**
     * Test the command usage output.
     *
     * @expectedException \Exception
     */
    public function testCommandsUsage() {
        $cli = $this->getCommandCli();

        $args = $cli->parse(['nit.php', '--help'], false);
    }

    /**
     * Test the help output for a multiple command argument.
     *
     * @expectedException \Exception
     */
    public function testCommandsHelp() {
        $cli = $this->getCommandCli();

        $args = $cli->parse(['nit.php', 'push', '--help'], false);
    }

    /**
     * Get the basic cli example.
     *
     * @return Cli
     */
    public function getBasicCli() {
        // Define the cli options.
        $cli = new Cli();

        $cli->description('Dump some information from your database.')
            ->opt('host', 'Connect to host.', false, 'string', 'h')
            ->opt('port', 'Port number to use.', false, 'integer', 'P')
            ->opt('user', 'User for login if not current user.', true, 'string', 'u')
            ->opt('password', 'Password to use when connecting to server.', false, 'string', 'p')
            ->opt('database', 'The name of the database to dump.', true, 'string', 'd');

        return $cli;
    }

    /**
     * Get the multiple command cli example.
     *
     * @return Cli
     */
    public function getCommandCli() {
        // Define a cli with commands.
        $cli = Cli::create()
            // Define the first command: push.
            ->command('push')
            ->description('Push data to a remote server.')
            ->opt('force', 'Force an overwrite.', false, 'boolean', 'f')
            ->opt('set-upstream', 'Add a reference to the upstream repo.', false, 'boolean', 'u')
            // Define the second command: pull.
            ->command('pull')
            ->description('Pull data from a remote server.')
            ->opt('commit', 'Perform the merge and commit the result.', false, 'boolean')
            // Set some global options.
            ->command('*')
            ->opt('verbose', 'Output verbose information.', false, 'boolean', 'v')
            ->arg('repo', 'The repository to sync with.', true);

        return $cli;
    }
}
