<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ModellisterTest
 *
 * @author jk
 */
class Magelog_Mql_Tests_Model_ModellisterTest extends PHPUnit_Framework_TestCase {

    /**
     *
     * @var Magelog_Mql_Model_Modellister
     */
    protected $lister;

    public static function setUpBeforeClass() {
        Mage::app();
    }

    protected function setUp() {        
        $this->lister = Mage::getModel('mql/modellister');
    }

    public function testGetModelsWithCollections() {
        $this->assertInternalType('array',$this->lister->getModelsWithCollections());
        $this->assertGreaterThan(100, count($this->lister->getModelsWithCollections()));
    }

    public function _testGetModels() {
        $models = $this->lister->getInstantiableModels();
        foreach ($models as $qualifiedName => $className) {
            try {
                Mage::getModel($qualifiedName);
            } catch (Exception $e) {
                $this->fail('Exception thrown for: '.$className.': '.$e->getMessage());
            }
        }
        $this->assertTrue(true);
    }

    /**
     * Assert that some namespaces are registered
     */
    public function _testGetModelNamespacesFindsSomeNamespaces() {
        $namespaces = $this->lister->getModelNamespaces();
        $this->assertArrayHasKey('catalog', $namespaces);
        $this->assertArrayHasKey('catalog_resource_eav_mysql4', $namespaces);
        $this->assertArrayHasKey('cms', $namespaces);
        $this->assertArrayHasKey('cms_mysql4', $namespaces);
    }

    /**
     * Assert that all namespace prefixes resolve to a model namespace
     */
    public function _testGetModelNamespacesHasNoEmptyNamespaces() {
        foreach ($this->lister->getModelNamespaces() as $prefixInfo) {
            $this->assertNotEmpty($prefixInfo->modelNamespace);
        }
    }


    /**
     * Assert the isResourceFlag is correctly set
     */
    public function _testGetModelNamespacesCanDistinguishResourceModels() {
        $namespaces = $this->lister->getModelNamespaces();

        $this->assertFalse($namespaces['catalog']->isResource);
        $this->assertObjectHasAttribute('resourcePrefix', $namespaces['catalog']);

        $this->assertTrue($namespaces['catalog_resource_eav_mysql4']->isResource);
        $this->assertObjectNotHasAttribute('resourcePrefix',$namespaces['catalog_resource_eav_mysql4']);


        $this->assertFalse($namespaces['cms']->isResource);
        $this->assertObjectHasAttribute('resourcePrefix', $namespaces['cms']);

        $this->assertTrue($namespaces['cms_mysql4']->isResource);
        $this->assertObjectNotHasAttribute('resourcePrefix',$namespaces['cms_mysql4']);
        
    }

    /**
     * Assert that all models have a corresponding resource model
     */
    public function _testGetModelNamespacesFindsCorrespondingResourceModels() {
        $namespaces = $this->lister->getModelNamespaces();
        $cnt = 0;
        foreach ($namespaces as $prefix => $prefixInfo) {
            #var_dump($prefixInfo);
            if (!$prefixInfo->isResource && isset($prefixInfo->resourcePrefix)) {
                $this->assertArrayHasKey($prefixInfo->resourcePrefix, $namespaces);
                $this->assertTrue($namespaces[$prefixInfo->resourcePrefix]->isResource);
                $cnt++;
            }
        }
        $this->assertGreaterThan(0, $cnt);

    }


    /**
     * Asserts the succesful registratioin of the model
     */
    public function testModelIsRegistered() {
        $this->assertInstanceOf('Magelog_Mql_Model_Modellister', $this->lister);
    }


    protected function tearDown() {
        $this->lister = null;
    }
}
?>
