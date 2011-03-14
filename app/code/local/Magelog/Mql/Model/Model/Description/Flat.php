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
class Magelog_Mql_Model_Model_Description_Flat extends Magelog_Mql_Model_Model_Description {

    /**
     *
     * @return Mage_Core_Model_Mysql4_Abstract
     */
    public function getResource() {
        return $this->getModel()->getResource();
    }


    /**
     *  ["SCHEMA_NAME"]=>NULL
        ["TABLE_NAME"]=>string(10) "admin_user"
        ["COLUMN_NAME"]=>string(8) "modified"
        ["COLUMN_POSITION"]=>int(8)
        ["DATA_TYPE"]=>string(8) "datetime"
        ["DEFAULT"]=>NULL
        ["NULLABLE"]=>bool(true)
        ["LENGTH"]=>NULL
        ["SCALE"]=>NULL
        ["PRECISION"]=>NULL
        ["UNSIGNED"]=>NULL
        ["PRIMARY"]=> bool(false)
        ["PRIMARY_POSITION"]=> NULL
        ["IDENTITY"]=> bool(false)

     * @return array
     */
    public function getAttributes() {
        $attributes = array();
        $attributeDescriptions = $this->getResource()->getReadConnection()->describeTable($this->getResource()->getMainTable());

        foreach ($attributeDescriptions as $attribute) {
            $attributes[$attribute['COLUMN_NAME']] = (object)array(
                'name' => $attribute['COLUMN_NAME'],
                'label' => $attribute['COLUMN_NAME'],
                'table' => $attribute['TABLE_NAME'],
                'type' => $attribute['DATA_TYPE'],
            );
        }
        return $attributes;
    }

    
}
?>
