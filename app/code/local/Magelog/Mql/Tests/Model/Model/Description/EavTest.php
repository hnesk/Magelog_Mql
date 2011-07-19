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
class Magelog_Mql_Tests_Model_Model_Description_EavTest extends PHPUnit_Framework_TestCase  {

    /**
     *
     * @var Magelog_Mql_Model_Model_Description_Eav
     */
    protected $description;

    public static function setUpBeforeClass() {
        Mage::app();
    }

    protected function setUp() {
        $this->description = Mage::getModel('mql/model_description_eav')->setModel(Mage::getModel('customer/customer'));
    }

    public function testGetResource() {
        $this->assertInstanceOf('Mage_Eav_Model_Entity_Abstract',$this->description->getResource());
        #$this->assertInstanceOf('Mage_Customer_Model_Entity_Customer',$this->description->getResource());

    }

    public function testGetEntityType() {
        $entityType = $this->description->getEntityType();
        $this->assertInstanceOf('Mage_Eav_Model_Entity_Type',$entityType);
        $this->assertEquals('customer', $entityType->getEntityTypeCode());
    }

    public function testGetAttributes() {
        $attributes = $this->description->getAttributes();
        $this->assertArrayHasKey('email', $attributes);
        $this->assertArrayHasKey('lastname', $attributes);
        $this->assertArrayHasKey('gender', $attributes);
    }

    public function testAttributeDescriptions() {
        $attributes = $this->description->getAttributes();
        #print_r($attributes);
    }

    
}
?>
