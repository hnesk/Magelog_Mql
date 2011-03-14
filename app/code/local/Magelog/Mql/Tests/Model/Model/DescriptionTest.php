<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EavTest
 *
 * @author jk
 */
class Magelog_Mql_Tests_Model_Model_DescriptionTest extends PHPUnit_Framework_TestCase {
    public static function  setUpBeforeClass() {
        Mage::app();
    }

    public function testFactoryForEavModel() {
        $description = Magelog_Mql_Model_Model_Description::factory(Mage::getModel('catalog/product'));
        $this->assertInstanceOf('Magelog_Mql_Model_Model_Description_Eav', $description);
    }

    public function testFactoryForFlatModel() {
        $description = Magelog_Mql_Model_Model_Description::factory(Mage::getModel('sales/order_item'));
        $this->assertInstanceOf('Magelog_Mql_Model_Model_Description_Flat', $description);
    }

    public function testGetNames() {
        $description = Magelog_Mql_Model_Model_Description::factory(Mage::getModel('sales/order_item'));
        $this->assertEquals('sales', $description->getModule());
        $this->assertEquals('order_item', $description->getShortname());

        $description = Magelog_Mql_Model_Model_Description::factory(Mage::getModel('catalog/product'));
        $this->assertEquals('catalog', $description->getModule());
        $this->assertEquals('product', $description->getShortname());
    }
}
?>
