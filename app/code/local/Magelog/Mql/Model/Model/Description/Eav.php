<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Eav
 *
 * @author jk
 */
class Magelog_Mql_Model_Model_Description_Eav extends Magelog_Mql_Model_Model_Description {
    protected $_attributes;

    /**
     * @return Mage_Eav_Model_Entity_Type
     */
    public function getEntityType() {
        return $this->getResource()->getEntityType();
    }

    /**
     *
     * @return Mage_Eav_Model_Entity_Abstract
     */
    public function getResource() {
        return $this->getModel()->getResource();
    }


    /**
     *
     * @return array
     */
    public function getAttributes() {
        if (!is_array($this->_attributes)) {

            $this->_attributes = array();
            foreach ($this->getEntityType()->getAttributeCollection() as $attribute) {
                /* @var $attribute Mage_Eav_Model_Entity_Attribute */
                $this->_attributes[$attribute->getAttributeCode()] = (object)array(
                    'name' => $attribute->getName(),
                    'label' => $attribute->getFrontend()->getLabel(),
                    'table' => $attribute->getBackendTable(),
                    'type' => $attribute->getBackendType(),
                    'input' => $attribute->getFrontend()->getInputType(),
                );
            }
            $entityTableDescriptions = $this->getResource()->getReadConnection()->describeTable($this->getResource()->getEntityTable());
            $entityTableKeys = $this->getResource()->getReadConnection()->getIndexList($this->getResource()->getEntityTable());

            foreach ($this->getResource()->getDefaultAttributes() as $attributeCode) {
                $attribute = $this->getResource()->getAttribute($attributeCode);
                $this->_attributes[$attribute->getAttributeCode()] = (object)array(
                    'name' => $attribute->getName(),
                    'label' => $attribute->getFrontend()->getLabel(),
                    'table' => $attribute->getBackendTable(),
                    'type' => $entityTableDescriptions[$attribute->getAttributeCode()]['DATA_TYPE'],
                    'input' => $attribute->getFrontend()->getInputType(),
                );
            }

        }
        return $this->_attributes;
    }

    
}
?>
