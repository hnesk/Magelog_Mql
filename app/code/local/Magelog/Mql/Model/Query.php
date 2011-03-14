<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Query
 *
 * @author jk
 */
class Magelog_Mql_Model_Query extends Varien_Object /* Mage_Core_Model_Abstract*/ {

    const STATE_OK = 2;
    const STATE_NO_MODEL = 1;
    const STATE_NO_MODEL_COLLECTION = 2;

    const STATE_INVALID_ATTRIBUTES = 3;
    const STATE_INVALID_DIRECTION = 4;


    /**
     * @var int
     */
    protected $state;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var string
     */
    protected $modelName;

    /**
     *
     * @var Mage_Core_Model_Abstract
     */
    protected $model;

    /**
     *
     * @var array
     */
    protected $attributes;

    /**
     *
     * @var Magelog_Mql_Model_Modellister
     */
    protected $lister;

    protected function _construct() {
        $this->lister = Mage::getModel('mql/modellister');
        $this->state = self::STATE_NO_MODEL;
        $this->attributes = array();
    }

    public function setModelname($modelName) {
        $allowedModels = $this->lister->getModelsWithCollections();
        $this->modelName = $modelName;
        $this->model = Mage::getModel($modelName);
        if (!$this->model) {
            $this->error(self::STATE_NO_MODEL,'Model "'.$modelName.'" not found');
        }
        if (!isset($allowedModels[$modelName])) {
            $this->model = null;
            $this->error(self::STATE_NO_MODEL,'Model "'.$modelName.'" has no collection');
        }
        $this->state = self::STATE_OK;
    }

    
    /**
     *
     * @return Mage_Core_Model_Abstract
     */
    public function getModel() {
        return $this->model;
    }

    /**
     *
     * @return string
     */
    public function getModelname() {
        return $this->modelName;
    }
    
    
    public function setAttributes($attributes) {
        if (!is_array($attributes)) {
            $attributes = array_map('trim',  explode(',', $attributes));
        }
        $this->attributes = $attributes;        
    }

    public function getAttributes() {
        return $this->attributes;
    }

    public function setOrder($attribute, $direction='asc') {
        if (!$this->getModelDescription()->hasAttribute($column)) {
            $this->error(self::STATE_INVALID_ATTRIBUTES,'Unknown attribute "'.$this->getModelname().'.'.$attribute.'"');
        }
        if (!in_array($direction, array('desc','asc'))) {
            $this->error(self::STATE_INVALID_DIRECTION,'"'.$direction.'" is not a valid value for the sort direction');
        }
        
    }


    public function getAttributeDescriptions() {
        $attributes = array();
        $attributeDescriptions = $this->getModelDescription()->getAttributes();
        foreach ($this->attributes as $attribute) {
            if (isset($attributeDescriptions[$attribute])) {
                $attributes[$attribute] = $attributeDescriptions[$attribute];                
            }
        }
        return $attributes;
    }



    /**
     *
     * @return Mage_Core_Model_Mysql4_Collection_Abstract
     */
    public function getCollection() {
        $collection = $this->getModel()->getCollection();
        if ($collection instanceof Mage_Eav_Model_Entity_Collection_Abstract) {
            $collection->addAttributeToSelect($this->getAttributes());
        }
        return $collection;
    }

    /**
     * @var Magelog_Mql_Model_Model_Description
     */
    public function getModelDescription() {
        return $this->getModel() ? Magelog_Mql_Model_Model_Description::factory($this->getModel()) : null;
    }


    public function hasError() {
        return $this->state != self::STATE_OK;
    }

    public function getState() {
        return $this->state;
    }

    public function getMessage() {
        return $this->message;
    }


    protected function error($code,$message) {
        $this->state = $code;
        $this->message = $message;
        throw new Magelog_Mql_Model_Query_Exception($message);
    }

    
}
?>
