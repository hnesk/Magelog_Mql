<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IndexController
 *
 * @author jk
 */
class Magelog_Mql_IndexController extends Mage_Adminhtml_Controller_Action{

    protected function _initAction() {
        $this->_title($this->__('System'))->_title($this->__('MQL'));


        $this->loadLayout()
            ->_setActiveMenu('system/mql/index')
            ->_addBreadcrumb($this->__('System'), $this->__('System'))
            ->_addBreadcrumb($this->__('MQL'), $this->__('MQL'))
        ;
        return $this;
    }


    protected function _initQuery() {
        $session = Mage::getSingleton('core/session');
        /* @var $session Mage_Core_Model_Session */
        $query = $session->hasMqlQuery() ? $session->getMqlQuery() : Mage::getModel('mql/query');
        /* @var $query Magelog_Mql_Model_Query */
        
        if ($this->getRequest()->getParam('model')) {
            try {
                $query->setModelname($this->getRequest()->getParam('model'));
            } catch (Magelog_Mql_Model_Query_Exception $e) {
                $session->addError($e->getMessage());
            }
        }

        if ($this->getRequest()->getParam('attributes')) {
            $query->setAttributes($this->getRequest()->getParam('attributes'));
        }

        if ($this->getRequest()->getParam('sort')) {
            $query->setOrder($this->getRequest()->getParam('sort'),$this->getRequest()->getParam('dir'));
        }

        $session->setMqlQuery($query);
        Mage::register('current_mql_query', $query);
        return $query;

    }

    public function indexAction() {
        $this->_initQuery();
        $this->_initAction();
        $this->renderLayout();
    }
}
?>
