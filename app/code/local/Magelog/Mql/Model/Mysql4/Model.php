<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Model
 *
 * @author jk
 */
class Magelog_Mql_Model_Mysql4_Model extends Mage_Core_Model_Mysql4_Abstract  {
    /**
     * Initialize resource model
     */
    protected function _construct() {
        $this->_init('mql/model', 'model_id');
    }

    public function getIdByName($modelName) {
        list($prefix,$shortName) = explode('/',$modelName);
        return $this->getReadConnection()->fetchOne('SELECT model_id FROM '.$this->getMainTable().' WHERE prefix = ? AND shortname = ?', array($prefix, $shortName));
    }

    public function getIdByClass($class) {
        return $this->getReadConnection()->fetchOne('SELECT model_id FROM '.$this->getMainTable().' WHERE class = ? ', array($class));
    }

}
?>
