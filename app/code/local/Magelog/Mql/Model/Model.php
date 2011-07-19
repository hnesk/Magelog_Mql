<?php
/**
 * A representation of a Magento Model Class
 * 
 *
 * @category    Magelog
 * @package     Magelog_Mql
 * @author      Johannes Künsebeck <jk@hdnet.de>
 */
class Magelog_Mql_Model_Model extends Mage_Core_Model_Abstract {
    /**
     * Initialize resource model
     *
     */
    protected function _construct()
    {
        $this->_init('mql/model');
    }

    public function isResource() {
        return $this->_data['is_resource']  ? true : false;
    }

    public function isCollection() {
        return $this->_data['is_collection']  ? true : false;
    }

    public function isDbCollection() {
        return $this->_data['is_db_collection']  ? true : false;
    }

    public function isGetModelInstantiable() {
        return $this->_data['is_get_model_instantiable'] ? true : false;
    }

    public function isInstantiable() {
        return $this->_data['is_instantiable'];
    }

    public function getClass() {
        return $this->_data['class'];
    }

    public function getPrefix() {
        return $this->_data['prefix'];
    }

    public function getShortname() {
        return $this->_data['shortname'];
    }

    
    public function getName() {
        return $this->getPrefix().'/'.$this->getShortname();
    }

    public function getInstance() {
        if ($this->getIsGetModelInstantiable()) {
            return Mage::getModel($this->getName());
        } else if ($this->getIsInstantiable()) {
            $class = $this->getClass();
            return new $class();
        } else {
            return null;
        }
    }

    /**
     * @return Magelog_Mql_Model_Model
     */
    public function loadByName($modelName) {
        $modelId = $this->getResource()->getIdByName($modelName);
        if ($modelId) {
            $this->load($modelId);
        }
        return $this;

    }

    /**
     * @return Magelog_Mql_Model_Model
     */
    public function loadByClass($class) {
        $modelId = $this->getResource()->getIdByClass($class);
        if ($modelId) {
            $this->load($modelId);
        }
        return $this;

    }


}
?>