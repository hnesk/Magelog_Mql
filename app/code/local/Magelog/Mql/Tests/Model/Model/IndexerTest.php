<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IndexerTest
 *
 * @author jk
 */
class Magelog_Mql_Tests_Model_Model_IndexerTest extends PHPUnit_Framework_TestCase {
    public static function setUpBeforeClass() {
        Mage::app();
    }

    /**
     * Hmmm, let's simply assert that there are models and no exception gets thrown
     */
    public function testCreateModelCollection() {
        $this->assertGreaterThan(100, $this->getModel()->createModelCollection()->count());
    }


    /**
     * @group slow
     * Assert that some namespaces are registered
     */
    public function testGetNamespacesFindsSomeNamespaces() {
        $namespaces = $this->getModel()->getNamespaces();
        $this->assertArrayHasKey('catalog', $namespaces);
        $this->assertArrayHasKey('catalog_resource_eav_mysql4', $namespaces);
        $this->assertArrayHasKey('cms', $namespaces);
        $this->assertArrayHasKey('cms_mysql4', $namespaces);
    }

    /**
     * @group slow
     * Assert that all namespace prefixes resolve to a model namespace
     */
    public function testGetNamespacesHasNoEmptyNamespaces() {
        foreach ($this->getModel()->getNamespaces() as $prefixInfo) {
            $this->assertNotEmpty($prefixInfo->modelNamespace);
        }
    }

    /**
     * @group slow
     * Assert the isResourceFlag is correctly set
     */
    public function testGetNamespacesCanDistinguishResourceModels() {
        $namespaces = $this->getModel()->getNamespaces();

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
     * @group slow
     * Assert that all models have a corresponding resource model
     */
    public function testGetNamespacesFindsCorrespondingResourceModels() {
        $namespaces = $this->getModel()->getNamespaces();
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
        $this->assertInstanceOf('Magelog_Mql_Model_Model_Indexer', $this->getModel());
    }


    /**
     * @return Magelog_Mql_Model_Model_Indexer
     */
    public function getModel() {
        return Mage::getModel('mql/model_indexer');
    }
}
?>
