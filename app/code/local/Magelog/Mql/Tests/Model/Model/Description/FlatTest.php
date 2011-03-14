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
class Magelog_Mql_Tests_Model_Model_Description_FlatTest extends PHPUnit_Framework_TestCase  {

    /**
     *
     * @var Magelog_Mql_Model_Model_Description_Eav
     */
    protected $description;

    public static function setUpBeforeClass() {
        Mage::app();
    }

    protected function setUp() {
        $this->description = Mage::getModel('mql/model_description_flat')->setModel(Mage::getModel('admin/user'));
    }

    public function testGetResource() {
        $this->assertInstanceOf('Mage_Core_Model_Mysql4_Abstract',$this->description->getResource());
        $this->assertInstanceOf('Mage_Admin_Model_Mysql4_User',$this->description->getResource());

    }

    public function testGetAttributes() {
        $attributes = $this->description->getAttributes();
        $this->assertArrayHasKey('email', $attributes);
        $this->assertArrayHasKey('username', $attributes);
        $this->assertArrayHasKey('created', $attributes);
        
    }
    
}
?>
