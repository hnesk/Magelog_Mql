<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Modellister
 *
 * @author jk
 */
class Magelog_Mql_Model_Modellister extends Varien_Object {

    protected $blacklist = array(
        'Varien_Convert_Validator_Column'=>true,
        'Varien_Directory_IFactory' => true,
        'Varien_File_Uploader_Image' => true,
        'Varien_File_CsvMulty' => true,
        'Varien_Pear' => true,
        'Varien_Pear_Package' => true,
        'Varien_Pear_Registry' => true,
        'Varien_Pear_Frontend' => true,
        'Mage_Core_Model_Mysql4_Design_Theme_Collection' => true,
        'Mage_Install_Model_Installer_Pear' => true,
        'Mage_Admin_Model_Acl_Assert_Ip' => true,
        'Mage_Admin_Model_Acl_Assert_Time' => true,
        'Mage_Api_Model_Acl_Assert_Ip' => true,
        'Mage_Api_Model_Acl_Assert_Time' => true,
        'Mage_Adminhtml_Model_Extension' => true,
        'Mage_Adminhtml_Model_System_Config_Source_Design_Package' => true,
        'Mage_Dataflow_Model_Session_Adapter_Iterator' => true,
        'Mage_Dataflow_Model_Convert_Iterator' => true,
        'Mage_Dataflow_Model_Convert_Iterator_Http' => true,
        'Mage_Dataflow_Model_Convert_Parser_Xml_Excel' => true,
        'Mage_Dataflow_Model_Convert_Parser_Abstract' => true,
        'Mage_Dataflow_Model_Convert_Parser_Csv' => true,
        'Mage_Dataflow_Model_Convert_Parser_Serialize' => true,
        'Mage_Dataflow_Model_Convert_Action_Abstract' => true,
        'Mage_Dataflow_Model_Convert_Iterator_File_Csv' => true,
        'Mage_Dataflow_Model_Convert_Validator_Column' => true,
        'Mage_Dataflow_Model_Mysql4_Import_Collection' => true,
        'Mage_Dataflow_Model_Mysql4_Catalogold' => true,
        'Mage_Dataflow_Model_Session_Adapter_Http' => true,
        'Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Attribute_Backend_Gallery' => true,
        'Mage_CatalogIndex_Model_Mysql4_Abstract' => true,
        'Mage_Payment_Model_Paygate_Request' => true,
        'Mage_Sales_Model_Mysql4_Report_Collection_Abstract' => true,
        'Mage_Shipping_Model_Rule_Action_Abstract' => true,
        'Mage_Shipping_Model_Rule_Action_Method' => true,
        'Mage_Shipping_Model_Rule_Action_Carrier' => true,
        'Mage_Shipping_Model_Rule_Abstract' => true,
        'Mage_Shipping_Model_Rule_Condition_Order_Totalqty' => true,
        'Mage_Shipping_Model_Rule_Condition_Order_Subtotal' => true,
        'Mage_Shipping_Model_Rule_Condition_Dest_Country' => true,
        'Mage_Shipping_Model_Rule_Condition_Dest_Region' => true,
        'Mage_Shipping_Model_Rule_Condition_Dest_Zip' => true,
        'Mage_Shipping_Model_Rule_Condition_Abstract' => true,
        'Mage_Shipping_Model_Rule_Condition_Package_Weight' => true,
        'Mage_Usa_Model_Tax_Uszip' => true,
        'Mage_ImportExport_Model_Import_Entity_Customer_Address' => true,
        'Mage_Api_Model_Server_Handler' => true,
        'Mage_Api_Model_Server_V2_Handler' => true,
    );


    public function getModelsWithCollections() {
        $cacheKey = 'meta_data_model_with_collection';
        if(Mage::app()->loadCache($cacheKey) === false) {
            $out = array();
            foreach ($this->getInstantiableModels() as $qualifiedName => $className) {
                $class = Mage::getModel($qualifiedName);
                if (Mage::getModel($qualifiedName) instanceof Mage_Core_Model_Abstract) {
                    try {
                        $collectionName = get_class($class->getCollection());
                        if ($collectionName) {
                            $out[$qualifiedName] = $collectionName;
                        }
                    } catch(Exception $e) {
                        #echo $qualifiedName.' '.$e->getMessage().PHP_EOL;
                    }

                }
            }
            Mage::app()->saveCache(serialize($out), $cacheKey);
        }
        return unserialize(Mage::app()->loadCache($cacheKey));
        
    }

    public function getInstantiableModels() {
        $out = array();
        $models = $this->getModels();
        $namespaces = $this->getModelNamespaces();
        foreach ($models as $prefix => $namespace) {
            foreach ($namespace as $qualifiedName => $className) {
                if ($namespaces[$prefix]->isResource) {
                    continue;
                }
                if (isset($this->blacklist[$className])) {
                    #echo '#  '.$className.PHP_EOL;
                    continue;
                }
                $parentClasses = class_parents($className,true);
                if (in_array('Exception',$parentClasses)) {
                    #echo 'E  '.$className.PHP_EOL;
                    continue;
                }
                $reflectionClass = new ReflectionClass($className);
                if (!($reflectionClass->isInstantiable() && !$reflectionClass->isAbstract()))  {
                    #echo 'C  '.$className.PHP_EOL;
                    continue;
                }

                #echo 'N'.($namespaces[$prefix]->isResource?'R':' ').' '.$className.' '.@$namespaces[$prefix]->resourcePrefix.PHP_EOL;

                if (!$namespaces[$prefix]->isResource) {
                    try {
                        $model = Mage::getModel($prefix.'/'.$qualifiedName);
                        $out[$prefix.'/'.$qualifiedName] = $className;
                    } catch (Exception $e) {
                        #echo 'EM '.$className.' '.substr($e->getMessage(),0,50).PHP_EOL;
                    }
                }
            }
        }
        return $out;
    }

    public function getModels() {
        $out = array();
        $namespaces = $this->getModelNamespaces();
        foreach ($namespaces as $prefix => $prefixInfo) {
            $namespace = $prefixInfo->modelNamespace;
            $path = str_replace(' ', DIRECTORY_SEPARATOR, ucwords(str_replace('_', ' ', $namespace)));
            foreach (explode(":", get_include_path()) as $includePath) {
                $prefixedPath = $includePath.DS.$path;
                if (file_exists($prefixedPath) && is_dir($prefixedPath)) {
                    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($prefixedPath, RecursiveDirectoryIterator::FOLLOW_SYMLINKS | RecursiveDirectoryIterator::SKIP_DOTS));
                    foreach($it as $file) {
                        $fileName = $file->getFilename();
                        if (fnmatch('*.php', $fileName)) {
                            $basename = substr(substr(trim($file->getPathname()), 1+strlen($prefixedPath)),0,-4);
                            $qualifiedName = implode('_',array_map('lcfirst', explode('/', $basename)));
                            $className = Mage::getConfig()->getModelClassName($prefix.'/'.$qualifiedName);
                            $out[$prefix][$qualifiedName] = $className;
                        }
                    }
                }
            }
        }
        return $out;
    }


    public function getModelNamespaces() {
        $out = array();
        $resourceModelMarker = array();
        $modelNodes = Mage::getConfig()->getNode('global/models')->children();
        foreach ($modelNodes as $name=>$modelNode) {
            $prefix = trim($modelNode->getName());
            $prefixInfo =  array(
                'modelNamespace' => (string)$modelNode->class,
                'isResource' => false
            );
            if ($modelNode->resourceModel) {
                $resoureModelPrefix = (string)$modelNode->resourceModel;
                $resourceModelMarker[$resoureModelPrefix] = true;
                #$resourceModelNode = $modelNodes->$resoureModelPrefix;
                $prefixInfo['resourcePrefix'] = $resoureModelPrefix;
            }

            $out[$prefix] = (object)$prefixInfo;
        }

        foreach ($resourceModelMarker as $resoureModelPrefix => $true) {
            $out[$resoureModelPrefix]->isResource = true;
        }
        return $out;

    }


}
?>
