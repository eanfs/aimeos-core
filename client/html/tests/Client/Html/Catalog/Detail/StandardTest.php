<?php

namespace Aimeos\Client\Html\Catalog\Detail;


/**
 * @copyright Copyright (c) Metaways Infosystems GmbH, 2012
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 */
class StandardTest extends \PHPUnit_Framework_TestCase
{
	private $object;
	private $context;


	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 */
	protected function setUp()
	{
		$this->context = \TestHelper::getContext();
		$paths = \TestHelper::getHtmlTemplatePaths();

		$this->object = new \Aimeos\Client\Html\Catalog\Detail\Standard( $this->context, $paths );
		$this->object->setView( \TestHelper::getView() );
	}


	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 */
	protected function tearDown()
	{
		unset( $this->object );
	}


	public function testGetHeader()
	{
		$view = $this->object->getView();
		$helper = new \Aimeos\MW\View\Helper\Parameter\Standard( $view, array( 'd_prodid' => $this->getProductItem()->getId() ) );
		$view->addHelper( 'param', $helper );

		$tags = array();
		$expire = null;
		$output = $this->object->getHeader( 1, $tags, $expire );

		$this->assertStringStartsWith( '	<title>Cafe Noire Cappuccino</title>', $output );
		$this->assertEquals( '2022-01-01 00:00:00', $expire );
		$this->assertEquals( 5, count( $tags ) );
	}


	public function testGetBody()
	{
		$view = $this->object->getView();
		$helper = new \Aimeos\MW\View\Helper\Parameter\Standard( $view, array( 'd_prodid' => $this->getProductItem()->getId() ) );
		$view->addHelper( 'param', $helper );

		$tags = array();
		$expire = null;
		$output = $this->object->getBody( 1, $tags, $expire );

		$this->assertStringStartsWith( '<section class="aimeos catalog-detail">', $output );
		$this->assertEquals( '2022-01-01 00:00:00', $expire );
		$this->assertEquals( 5, count( $tags ) );
	}


	public function testGetSubClient()
	{
		$client = $this->object->getSubClient( 'basic', 'Standard' );
		$this->assertInstanceOf( '\\Aimeos\\Client\\HTML\\Iface', $client );
	}


	public function testGetSubClientInvalid()
	{
		$this->setExpectedException( '\\Aimeos\\Client\\Html\\Exception' );
		$this->object->getSubClient( 'invalid', 'invalid' );
	}


	public function testGetSubClientInvalidName()
	{
		$this->setExpectedException( '\\Aimeos\\Client\\Html\\Exception' );
		$this->object->getSubClient( '$$$', '$$$' );
	}


	public function testProcess()
	{
		$this->object->process();
	}


	protected function getProductItem()
	{
		$manager = \Aimeos\MShop\Product\Manager\Factory::createManager( $this->context );
		$search = $manager->createSearch();
		$search->setConditions( $search->compare( '==', 'product.code', 'CNC' ) );
		$items = $manager->searchItems( $search );

		if( ( $item = reset( $items ) ) === false ) {
			throw new \Exception( 'No product item with code "CNC" found' );
		}

		return $item;
	}
}