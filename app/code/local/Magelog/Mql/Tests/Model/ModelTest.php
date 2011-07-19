<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModellisterTest
 *
 * @author jk
 */
class Magelog_Mql_Tests_Model_ModelTest extends PHPUnit_Framework_TestCase {

    protected $modelClass = 'mql/model';

    public static function setUpBeforeClass() {
        Mage::app();
    }


    public function testGetInstance() {
        $this->assertInstanceOf('Mage_Catalog_Model_Product', $this->getModel()->loadByName('catalog/product')->getInstance());
    }


    public function testModelCanGetLoadedByName() {
        $model = $this->getModel()->loadByName('catalog/product');
        $this->assertEquals('catalog', $model->getPrefix());
        $this->assertEquals('product', $model->getShortname());
        $this->assertEquals(1, $model->getIsGetModelInstantiable());
    }


    /**
     * Asserts the succesful registratioin of the model
     */
    public function testModelIsRegistered() {        
        $this->assertInstanceOf('Magelog_Mql_Model_Model', $this->getModel());
    }

    /**
     * Asserts the succesful registratioin of the collection
     */
    public function _testCollectionIsRegistered() {
        $this->assertInstanceOf('Magelog_Mql_Model_Mysql4_Collection', $this->getCollection());
    }

    /**
     * Asserts the succesful registratioin of the resource
     */
    public function _testResourceIsRegistered() {
        $this->assertInstanceOf('Magelog_Mql_Model_Mysql4_Model', $this->getCollection());
    }



    /**
     *
     * @return Magelog_Mql_Model_Model
     */
    public function getModel() {
        return Mage::getModel($this->modelClass);
    }

    /**
     *
     * @return Magelog_Mql_Model_Mysql4_Model_Collection
     */
    public function getCollection() {
        return $this->getModel()->getCollection();
    }

    /**
     *
     * @return Magelog_Mql_Model_Mysql4_Model
     */
    public function getResource() {
        return $this->getModel()->getResource();
    }


}
?>
