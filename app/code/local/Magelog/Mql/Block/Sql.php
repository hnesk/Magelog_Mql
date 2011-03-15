<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Description of Console
 *
 * @author jk
 */
class Magelog_Mql_Block_Sql extends Mage_Adminhtml_Block_Abstract {
    /**
     *
     * @return Magelog_Mql_Model_Query
     */
    public function getQuery() {
        if (!isset($this->_data['query'])) {
            $this->_data['query'] = Mage::registry('current_mql_query');
        }
        return $this->_data['query'];
    }


    /**
     * @return Mage_Core_Model_Mysql4_Collection_Abstract
     */
    public function getCollection() {
        return $this->getQuery()->getCollection();
    }

}
?>
