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
class Magelog_Mql_Block_Attributechooser extends Mage_Adminhtml_Block_Abstract {
    /**
     *
     * @return Magelog_Mql_Model_Query
     */
    public function getQuery() {
        if (!isset($this->_data['query'])) {
            $this->_data['query'] = Mage::registry('current_mql_query') ? Mage::registry('current_mql_query') : Mage::getModel('mql/query');
        }
        return $this->_data['query'];
    }

    /**
     *
     * @return Mage_Core_Model_Abstract
     */
    public function getModel() {
        return $this->getQuery()->getModel();
    }

    public function getSelectedAttributes() {
        return $this->getQuery()->getAttributes();
    }

    /**
     *
     * @return boolean
     */
    public function hasError() {
        return !$this->getModel();
    }

    public function getMessage() {
        return !$this->getModel() ? $this->__('No valid model selected') : '';
    }



    /**
     *
     * @return Magelog_Mql_Model_Model_Description
     */
    public function getDescription() {
        return Magelog_Mql_Model_Model_Description::factory($this->getModel());
    }

    

    public function getAttributes() {
        if ($this->getModel()) {
            return $this->getDescription()->getAttributes();
        } else {
            return array();
        }
    }

    public function getAttributehints() {
        return array_keys($this->getAttributes());
    }
}
?>
