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
class Magelog_Mql_Block_Info extends Mage_Adminhtml_Block_Abstract {
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


    public function getAttributeDescriptions() {
        if ($this->getQuery()) {
            return $this->getQuery()->getAttributeDescriptions();
        } else {
            return array();
        }
    }

}
?>
