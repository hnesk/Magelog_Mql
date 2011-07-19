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
class Magelog_Mql_Block_Modelchooser extends Mage_Adminhtml_Block_Abstract {
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

    public function hasError() {
        return $this->getQuery()->hasError();
    }

    public function getMessage() {
        return $this->getQuery()->getMessage();
    }

    public function getModelname() {
        return $this->getQuery()->getModelname();
    }

    public function getModelClass() {
        return $this->getQuery()->getModelClass();
    }


    public function getCollectionname() {
        return $this->getQuery()->getCollection();
    }


    /**
     * @return Magelog_Mql_Model_Mysql4_Model_Collection 
     */
    public function getModelCollection() {
        $modelCollection = Mage::getModel('mql/model')->getCollection();
        /* @var $modelCollection Magelog_Mql_Model_Mysql4_Model_Collection */
        $modelCollection->addInstantiableFilter()->addCollectionFilter()->orderByName();
        return $modelCollection;
    }

    public function getModelHints() {
        $data = array();
        foreach ($this->getModelCollection() as $model) {
            /* @var $model Magelog_Mql_Model_Model */
            $data[] = $model->getClass();
        }
        return $data;
    }


}
?>
