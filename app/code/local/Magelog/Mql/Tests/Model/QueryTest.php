<?php
class Magelog_Mql_Tests_Model_QueryTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Magelog_Mql_Model_Query
     */
    protected $query;

    public static function setUpBeforeClass() {
        Mage::app();
    }

    protected function setUp() {
        $this->query = Mage::getModel('mql/query');
    }

    /**
     * @expectedException Magelog_Mql_Model_Query_Exception
     */
    public function testContructionWithUselessModelThrowsException() {
        $this->query->setModelname('catalog/undefined');
    }

    public function testContructionWithEavModel() {
        $this->query->setModelname('catalog/product');
        $this->assertInstanceOf('Mage_Catalog_Model_Product', $this->query->getModel());
    }

    public function testContructionWithFlatModel() {
        $this->query->setModelname('sales/quote_item');
        $this->assertInstanceOf('Mage_Sales_Model_Quote_Item', $this->query->getModel());
    }


}