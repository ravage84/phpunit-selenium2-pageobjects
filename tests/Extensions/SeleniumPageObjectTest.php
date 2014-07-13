<?php
/**
 * Part of the PHPUnit Selenium2 PageObjects library
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Marc Würth <ravage@bluewin.ch>
 * @link https://github.com/ravage84/phpunit-selenium2-pageobjects
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @package PHPUnit_Selenium2_PageObjects\Tests
 */

/**
 * Tests for Selenium2PageObject
 *
 * @coversDefaultClass PHPUnit_Extensions_Selenium2PageObject
 */
class Selenium2PageObjectTest extends PHPUnit_Framework_TestCase
{
	/**
	 * The mocked Selenium2TestCase
	 *
	 * @var PHPUnit_Extensions_Selenium2TestCase
	 */
	protected $test;

	/**
	 * The page object under test
	 *
	 * @var PHPUnit_Extensions_Selenium2PageObject
	 */
	protected $page;

	/**
	 * Prepare a Selenium2TestCase and Selenium2PageObjectmock
	 *
	 * @return void
	 */
	protected function setUp() {
		parent::setUp();

		$this->test = $this->getMock(
			'PHPUnit_Extensions_Selenium2TestCase',
			array('url', 'title', 'byCssSelector')
		);
	}

	/**
	 * Tests whether load calls the callbacks
	 *
	 * @covers ::load
	 * @return void
	 */
	public function testLoadCallsCallbacks()
	{
		$this->page = $this->getMock(
			'MockPage',
			array('_assertPreConditions', 'assertPageTitle', '_assertMapConditions'),
			array($this->test)
		);

		$this->page->expects($this->once())
			->method('_assertPreConditions');
		$this->page->expects($this->once())
			->method('assertPageTitle');
		$this->page->expects($this->once())
			->method('_assertMapConditions');

		$this->page->load();
	}

	/**
	 * Tests whether load calls url with the default URL
	 *
	 * @covers ::load
	 * @return void
	 */
	public function testLoadCallsUrlWithDefault()
	{
		$expectedUrl = 'foo123.html';

		$this->page = $this->getMock(
			'MockPage',
			array('_assertPreConditions', 'assertPageTitle', '_assertMapConditions'),
			array($this->test)
		);

		$this->test->expects($this->once())
			->method('url')
			->with($this->equalTo($expectedUrl));

		$this->page->load();
	}

	/**
	 * Tests whether load calls url with the URL given
	 *
	 * @covers ::load
	 * @return void
	 */
	public function testLoadCallsUrlWithUrlGiven()
	{
		$url = 'foo.html';

		$this->page = $this->getMock(
			'MockPage',
			array('_assertPreConditions', 'assertPageTitle', '_assertMapConditions'),
			array($this->test)
		);

		$this->test->expects($this->once())
			->method('url')
			->with($this->equalTo($url));

		$this->page->load($url);
	}

	/**
	 * Tests whether load return value equals the page object
	 *
	 * @covers ::load
	 * @return void
	 */
	public function testLoadReturnsThis()
	{
		$this->page = $this->getMock(
			'MockPage',
			array('_assertPreConditions', 'assertPageTitle', '_assertMapConditions'),
			array($this->test)
		);

		$returned = $this->page->load();
		$this->assertInstanceOf('MockPage', $returned);
	}

	/**
	 * Tests the method assertPageTitle
	 *
	 * @covers ::assertPageTitle
	 * @return void
	 */
	public function testAssertPageTitle()
	{
		$this->page = $this->getMock(
			'MockPage',
			null,
			array($this->test)
		);

		$this->test->expects($this->any())
			->method('title')
			->will($this->returnValue('Foo 123'));

		$this->page->assertPageTitle();
	}

	/**
	 * Tests whether assertPageTitle return value equals the page object
	 *
	 * @covers ::assertPageTitle
	 * @return void
	 */
	public function testAssertPageTitleReturnsThis()
	{
		$this->page = $this->getMock(
			'MockPage',
			null,
			array($this->test)
		);

		$this->test->expects($this->any())
			->method('title')
			->will($this->returnValue('Foo 123'));

		$returned =	$this->page->assertPageTitle();
		$this->assertInstanceOf('MockPage', $returned);
	}

	/**
	 * Tests the _assertMapConditions method
	 *
	 * @covers ::_assertMapConditions
	 * @return void
	 */
	public function test_assertMapConditions()
	{
		$this->page = $this->getMock(
			'MockPage',
			array('_assertPreConditions', 'assertPageTitle'),
			array($this->test)
		);

		$this->test->expects($this->exactly(3))
			->method('byCssSelector')
			->will($this->onConsecutiveCalls('not_null', 'not_null', 'not_null'));

		$this->page->load();
	}

	/**
	 * Tests the _assertMapConditions method with a locator missing
	 *
	 * @e3xpectedExeption
	 * @covers ::_assertMapConditions
	 * @return void
	 */
	public function test_assertMapConditionsMissingLocator()
	{
		$this->page = $this->getMock(
			'MockPage',
			array('_assertPreConditions', 'assertPageTitle'),
			array($this->test)
		);

		$this->markTestIncomplete(
			'This test has not been implemented yet.'
		);

		$this->test->expects($this->exactly(3))
			->method('byCssSelector')
			->will($this->onConsecutiveCalls('not_null', 'not_null', null));

		$this->page->load();
	}

	/**
	 * Tests the getLocator method
	 *
	 * @covers ::getLocator
	 * @return void
	 */
	public function testGetLocatorReturnsMapValue()
	{
		$this->page = $this->getMock(
			'MockPage',
			null,
			array($this->test)
		);

		$result = $this->page->getLocator('fieldTwo');
		$expected = 'field_2';

		$this->assertEquals(
			$expected,
			$result,
			'Returned map key should match.'
		);
	}

	/**
	 * Tests the getLocator method with a missing locator
	 *
	 * @expectedException InvalidArgumentException
	 * @covers ::getLocator
	 * @return void
	 */
	public function testGetLocatorFailsIfMissing()
	{
		$this->page = $this->getMock(
			'MockPage',
			null,
			array($this->test)
		);

		$this->page->getLocator('this-key-does-not-exist');
	}

}

/**
 * Class MockPage
 */
class MockPage extends PHPUnit_Extensions_Selenium2PageObject {

	protected $url = 'foo123.html';

	protected $pageTitle = 'Foo 123';

	protected $map = array(
		'fieldOne' => 'field_1',
		'fieldTwo' => 'field_2',
		'fieldThree' => 'field_3',
	);

}
