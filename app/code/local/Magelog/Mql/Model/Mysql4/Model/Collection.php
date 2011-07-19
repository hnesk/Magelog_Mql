<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Collection
 *
 * @author jk
 */
class Magelog_Mql_Model_Mysql4_Model_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract {
    protected function _construct() {
        $this->_init('mql/model');
    }

    /**
     * @return Magelog_Mql_Model_Mysql4_Model_Collection
     */
    public function recreate() {
        $this->delete();
        Mage::getModel('mql/model_indexer')->createModelCollection();
        return $this;
    }

    /**
     * @return Magelog_Mql_Model_Mysql4_Model_Collection
     */
    public function delete($where = '') {
        $this->getConnection()->delete($this->getMainTable(), $where);
        return $this;
    }

    /**
     * @return Magelog_Mql_Model_Mysql4_Model_Collection
     */
    public function orderByName($dir = self::SORT_ORDER_ASC) {
        $this->addOrder('class', $dir);
        return $this;
    }

    /**
     * @return Magelog_Mql_Model_Mysql4_Model_Collection
     */
    public function addPrefixFilter($prefix) {
        $this->addFieldToFilter('prefix',$prefix);
        return $this;
    }

    /**
     * @return Magelog_Mql_Model_Mysql4_Model_Collection
     */
    public function addInstantiableFilter() {
        $this->addFieldToFilter('is_instantiable',1);
        return $this;
    }

    /**
     * @return Magelog_Mql_Model_Mysql4_Model_Collection
     */
    public function addCollectionFilter() {
        $this->addFieldToFilter('is_collection',1);
        return $this;
    }

}
?>
