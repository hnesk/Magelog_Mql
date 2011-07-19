<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Adminhtml
 * @copyright   Copyright (c) 2010 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Users grid
 *
 * @category   Mage
 * @package    Mage_Adminhtml
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class Magelog_Mql_Block_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('customerGrid');
        $this->setSaveParametersInSession(true);
    }

    /**
     * @return Magelog_Mql_Model_Query
     */
    protected function getQuery() {
        $query = Mage::registry('current_mql_query');
        return $query->hasError() ? null : $query;
    }

    protected function _prepareCollection()
    {
        
        if ($this->getQuery()) {
            $this->setCollection($this->getQuery()->getCollection());
        }

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        if ($query = $this->getQuery()) {
            foreach ($query->getAttributeDescriptions() as $code => $attribute) {
                $this->addColumn($code, array(
                    'header'    => $attribute->label,
                    'sortable'  => true,
                    'index'     => $code
                ));
            }
        }
        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('adminhtml/catalog_product/edit', array('id' => $row->getId()));
    }

}

