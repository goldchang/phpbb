<?php
/**
*
* @package testing
* @version $Id$
* @copyright (c) 2008 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

define('IN_PHPBB', true);

if (!defined('PHPUnit_MAIN_METHOD'))
{
	define('PHPUnit_MAIN_METHOD', 'phpbb_security_all_tests::main');
}

require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

require_once 'security/extract_current_page.php';
require_once 'security/redirect.php';

class phpbb_security_all_tests extends PHPUnit_Framework_TestSuite
{
	/**
	* Set up the required user object and server variables for the suites
	*/
	protected function setUp()
	{
		global $user;

		// Put this into a global function being run by every test to init a proper user session
		$_SERVER['HTTP_HOST']		= 'localhost';
		$_SERVER['SERVER_NAME']		= 'localhost';
		$_SERVER['SERVER_ADDR']		= '127.0.0.1';
		$_SERVER['SERVER_PORT']		= 80;
		$_SERVER['REMOTE_ADDR']		= '127.0.0.1';
		$_SERVER['QUERY_STRING']	= '';
		$_SERVER['REQUEST_URI']		= '/tests/';
		$_SERVER['SCRIPT_NAME']		= '/tests/index.php';
		$_SERVER['PHP_SELF']		= '/tests/index.php';
		$_SERVER['HTTP_USER_AGENT']	= 'Mozilla/5.0 (Windows; U; Windows NT 6.0; de; rv:1.8.1.14) Gecko/20080404 Firefox/2.0.0.14';
		$_SERVER['HTTP_ACCEPT_LANGUAGE']	= 'de-de,de;q=0.8,en-us;q=0.5,en;q=0.3';

/*
		[HTTP_ACCEPT_ENCODING] => gzip,deflate
		[HTTP_ACCEPT_CHARSET] => ISO-8859-1,utf-8;q=0.7,*;q=0.7
		DOCUMENT_ROOT] => /var/www/
		[SCRIPT_FILENAME] => /var/www/tests/index.php
*/

		// Set no user and trick a bit to circumvent errors
		$user = new user();
		$user->lang = true;
		$user->browser				= (!empty($_SERVER['HTTP_USER_AGENT'])) ? htmlspecialchars((string) $_SERVER['HTTP_USER_AGENT']) : '';
		$user->referer				= (!empty($_SERVER['HTTP_REFERER'])) ? htmlspecialchars((string) $_SERVER['HTTP_REFERER']) : '';
		$user->forwarded_for		= (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) ? (string) $_SERVER['HTTP_X_FORWARDED_FOR'] : '';
		$user->host					= (!empty($_SERVER['HTTP_HOST'])) ? (string) strtolower($_SERVER['HTTP_HOST']) : ((!empty($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : getenv('SERVER_NAME'));
		$user->page = session::extract_current_page(PHPBB_ROOT_PATH);
	}

	protected function tearDown()
	{
		global $user;
		$user = NULL;
	}

	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run(self::suite());
	}

	public static function suite()
	{
		// I bet there is a better method calling this... :)
		$suite = new phpbb_security_all_tests('phpBB Security Fixes');

		$suite->addTestSuite('phpbb_security_extract_current_page_test');
		$suite->addTestSuite('phpbb_security_redirect_test');

		return $suite;
	}
}

if (PHPUnit_MAIN_METHOD == 'phpbb_security_all_tests::main')
{
	phpbb_security_all_tests::main();
}
?>