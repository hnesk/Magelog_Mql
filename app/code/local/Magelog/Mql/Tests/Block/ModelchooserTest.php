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
class Magelog_Mql_Tests_Block_ModelchooserTest extends PHPUnit_Framework_TestCase {

    protected $blockClass = 'mql/modelchooser';

    public static function setUpBeforeClass() {
        Mage::app();
    }


    public function testModelHints() {
        $modelHints = $this->getBlock()->getModelHints();
        $this->assertGreaterThan(100, count($modelHints));
    }


    /**
     *
     * @return Magelog_Mql_Block_Modelchooser
     */
    public function getBlock() {
        return Mage::getSingleton('core/layout')->createBlock($this->blockClass);
    }



}
?>
