<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Description
 *
 * @author jk
 */
abstract class Magelog_Mql_Model_Model_Description {

    /**
     *
     * @var Mage_Core_Model_Abstract 
     */
    protected $model;

    public function setModel(Mage_Core_Model_Abstract $model) {
        $this->model = $model;
        return $this;
    }

    /**
     *
     * @return Mage_Eav_Model_Entity_Abstract
     */
    public function getModel() {
        return $this->model;
    }

    public function getModule() {
        return current(explode('/',$this->getModel()->getResourceName()));
    }

    public function getShortname() {
        return current(array_reverse(explode('/',$this->getModel()->getResourceName())));
    }

    public function hasAttribute($code) {
        $attributes = $this->getAttributes();
        return isset($attributes[$code]);
    }

    public function getAttribute($code) {
        $attributes = $this->getAttributes();
        return isset($attributes[$code]) ? $attributes[$code] : null;
    }


    abstract public function getAttributes();

    /**
     *
     * @param Mage_Core_Model_Abstract $model
     * @return Magelog_Mql_Model_Model_Description
     */
    public static function factory(Mage_Core_Model_Abstract $model) {
        $resource = $model->getResource();
        if ($resource instanceof Mage_Eav_Model_Entity_Abstract) {
            $description = Mage::getModel('mql/model_description_eav');
        } else if ($resource instanceof Mage_Core_Model_Resource_Abstract) {
            $description = Mage::getModel('mql/model_description_flat');
        } else {
            throw new InvalidArgumentException(get_class($model).' is not a resource model');
        }
        return $description->setModel($model);
    }
}
?>
