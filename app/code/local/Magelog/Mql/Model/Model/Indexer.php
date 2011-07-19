<?php

/**
 * Description of Indexer
 *
 * @author jk
 */
class Magelog_Mql_Model_Model_Indexer {

    protected static $blacklist = array(
        'Varien_Convert_Validator_Column'=>true,
        'Mage_Admin_Model_Acl_Assert_Ip' => true,
        'Mage_Admin_Model_Acl_Assert_Time' => true,
        'Mage_Api_Model_Acl_Assert_Ip' => true,
        'Mage_Api_Model_Acl_Assert_Time' => true,
        'Mage_Dataflow_Model_Session_Adapter_Iterator' => true,
        'Mage_Dataflow_Model_Convert_Iterator_Http' => true,
        'Mage_Dataflow_Model_Convert_Parser_Abstract' => true,
        'Mage_Dataflow_Model_Convert_Action_Abstract' => true,
        'Mage_Dataflow_Model_Convert_Iterator_File_Csv' => true,
        'Mage_Dataflow_Model_Convert_Validator_Column' => true,
        'Mage_Dataflow_Model_Session_Adapter_Http' => true,
        'Mage_Dataflow_Model_Mysql4_Import_Collection' => true,
    );


    /**
     *
     * @var array
     */
    protected $_filesProcessed = array();


    /**
     * @return Magelog_Mql_Model_Mysql4_Model_Collection
     */
    public function createModelCollection()  {
        $modelCollection = Mage::getModel('mql/model')->getCollection();
        /* @var $modelCollection Magelog_Mql_Model_Mysql4_Model_Collection */
        if ($modelCollection->count() == 0) {
            $this->getModels()->save();
            $modelCollection = Mage::getModel('mql/model')->getCollection();
        }
        return $modelCollection;
    }


    /**
     *
     * @return Magelog_Mql_Model_Mysql4_Model_Collection
     */
    public function getModels() {
        $oldErrorHandler = set_error_handler(function($errno , $errstr , $errfile , $errline , $errcontext) {
            throw new Magelog_Mql_Model_Model_Exception("Reflection error ($errno: '$errstr' in $errfile:$errline ".print_r($errcontext,1));
        });

        $models = Mage::getModel('mql/model')->getCollection();
        /* @var $models Magelog_Mql_Model_Mysql4_Model_Collection */
        $this->_filesProcessed = array();
        $namespaces = $this->getNamespaces();
        foreach ($namespaces as $prefix => $prefixInfo) {
            $namespace = $prefixInfo->modelNamespace;
            $path = str_replace(' ', DIRECTORY_SEPARATOR, ucwords(str_replace('_', ' ', $namespace)));
            foreach (explode(":", get_include_path()) as $includePath) {
                $prefixedPath = $includePath.DS.$path;
                if (file_exists($prefixedPath) && is_dir($prefixedPath)) {
                    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($prefixedPath, RecursiveDirectoryIterator::FOLLOW_SYMLINKS | RecursiveDirectoryIterator::SKIP_DOTS));
                    foreach($it as $file) {
                        if ($model = $this->getModelInfo($file, $prefixInfo, $prefixedPath)) {
                            $models->addItem($model);
                        }
                    }
                }
            }
        }
        set_error_handler($oldErrorHandler);
        return $models;
    }

    /**
     *
     * @param SplFileInfo $file
     * @param stdClass $prefix
     * @param string $prefixedPath
     * @return Magelog_Mql_Model_Model
     */
    public function getModelInfo(SplFileInfo $file, $prefixInfo, $prefixedPath) {
        $fileName = $file->getFilename();
        if (!fnmatch('*.php', $fileName)) {
            return false;
        }

        $pathName = $file->getPathname();

        // skip resource models that are already processed by the preceding resource namespace
        if (isset($this->_filesProcessed[$pathName])) {
            return false;
        }
        $this->_filesProcessed[$pathName] = 1;

        $prefix = $prefixInfo->prefix;

        $basename = substr(substr(trim($file->getPathname()), 1+strlen($prefixedPath)),0,-4);
        $shortName = implode('_',array_map('lcfirst', explode('/', $basename)));
        $className = Mage::getConfig()->getModelClassName($prefix.'/'.$shortName);

        $model = Mage::getModel('mql/model')
            ->setShortname($shortName)
            ->setClass($className)
            ->setPrefix($prefixInfo->prefix)
            ->setIsResource($prefixInfo->isResource)
            ->setResourcePrefix(isset($prefixInfo->resourcePrefix) ? $prefixInfo->resourcePrefix :  '')
            ->setFilename(str_replace(Mage::getBaseDir().'/', '', $pathName))
            ->setError('')
            ->setIsBlacklisted(isset(self::$blacklist[$className]))
        ;
       
        if (stripos(file_get_contents($pathName) , $className) === false) {
            echo $className.PHP_EOL;
            $model->setError('File does not contain class')->setIsBlacklisted(true);
        }


        if (!$model->getIsBlacklisted()) {
            $model->setIsUsable(true);
            try {
                $parentClasses = class_parents($className,true);
                $reflectionClass = new ReflectionClass($className);

                $model->setIsException(in_array('Exception',$parentClasses));
                $model->setIsDbCollection(in_array('Varien_Data_Collection_Db',$parentClasses));
                $model->setIsCollection(in_array('Varien_Data_Collection',$parentClasses));
                $model->setIsInstantiable(
                        $reflectionClass->isInstantiable() &&
                        !$reflectionClass->isAbstract() &&
                        substr( $shortName, strlen( $shortName ) - strlen( 'abstract') ) != 'abstract'
                );

                #echo $model->getPrefix().'/'.$model->getShortname().' => '.$pathName.PHP_EOL;
                $model->setIsGetModelInstantiable(false);
                if ($model->getPrefix()!='varien' && !$model->getIsException() && $model->getIsInstantiable()) {
                    try {
                        $testModel = Mage::getModel($model->getPrefix().'/'.$model->getShortname());
                        unset($testModel);
                        $model->setIsGetModelInstantiable(true);
                    } catch (Exception $e) {
                        #echo $e->getMessage().PHP_EOL;
                        
                    }
                }

            } catch (Magelog_Mql_Model_Model_Exception $e) {
                $model->setError($e->getMessage());
                $model->setIsUsable(false);
            }
        }

        return $model;
    }

    public function getNamespaces() {
        $out = array();
        $resourceModelMarker = array();
        $modelNodes = Mage::getConfig()->getNode('global/models')->children();
        foreach ($modelNodes as $name=>$modelNode) {
            $prefix = trim($modelNode->getName());
            $prefixInfo =  array(
                'prefix' => $prefix,
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
        // this guarantees that resource models (whose names are longer) get processed first
        uksort($out, function ($a, $b) { return strlen($b) - strlen($a);});
        return $out;

    }
} 
?>
