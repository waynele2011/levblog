<?php

/**
 * Layout design admin controller
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Adminhtml_Dlsblog_LayoutdesignController extends DLS_DLSBlog_Controller_Adminhtml_DLSBlog
{
    /**
     * init the layout design
     *
     * @access protected
     * @return DLS_DLSBlog_Model_Layoutdesign
     */
    protected function _initLayoutdesign()
    {
        $layoutdesignId  = (int) $this->getRequest()->getParam('id');
        $layoutdesign    = Mage::getModel('dls_dlsblog/layoutdesign');
        if ($layoutdesignId) {
            $layoutdesign->load($layoutdesignId);
        }
        Mage::register('current_layoutdesign', $layoutdesign);
        return $layoutdesign;
    }

    /**
     * default action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_title(Mage::helper('dls_dlsblog')->__('DLS Blog'))
             ->_title(Mage::helper('dls_dlsblog')->__('Layout designs'));
        $this->renderLayout();
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }

    /**
     * edit layout design - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function editAction()
    {
        $layoutdesignId    = $this->getRequest()->getParam('id');
        $layoutdesign      = $this->_initLayoutdesign();
        if ($layoutdesignId && !$layoutdesign->getId()) {
            $this->_getSession()->addError(
                Mage::helper('dls_dlsblog')->__('This layout design no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getLayoutdesignData(true);
        if (!empty($data)) {
            $layoutdesign->setData($data);
        }
        Mage::register('layoutdesign_data', $layoutdesign);
        $this->loadLayout();
        $this->_title(Mage::helper('dls_dlsblog')->__('DLS Blog'))
             ->_title(Mage::helper('dls_dlsblog')->__('Layout designs'));
        if ($layoutdesign->getId()) {
            $this->_title($layoutdesign->getName());
        } else {
            $this->_title(Mage::helper('dls_dlsblog')->__('Add layout design'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new layout design action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * save layout design - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('layoutdesign')) {
            try {
                $layoutdesign = $this->_initLayoutdesign();
                $data['design_code'] = Mage::helper('core')->jsonEncode($data['design_frame']);
                $layoutdesign->addData($data);
                $layoutdesign->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('dls_dlsblog')->__('Layout design was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $layoutdesign->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setLayoutdesignData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('dls_dlsblog')->__('There was a problem saving the layout design.')
                );
                Mage::getSingleton('adminhtml/session')->setLayoutdesignData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('dls_dlsblog')->__('Unable to find layout design to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete layout design - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $layoutdesign = Mage::getModel('dls_dlsblog/layoutdesign');
                $layoutdesign->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('dls_dlsblog')->__('Layout design was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('dls_dlsblog')->__('There was an error deleting layout design.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('dls_dlsblog')->__('Could not find layout design to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete layout design - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massDeleteAction()
    {
        $layoutdesignIds = $this->getRequest()->getParam('layoutdesign');
        if (!is_array($layoutdesignIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('dls_dlsblog')->__('Please select layout designs to delete.')
            );
        } else {
            try {
                foreach ($layoutdesignIds as $layoutdesignId) {
                    $layoutdesign = Mage::getModel('dls_dlsblog/layoutdesign');
                    $layoutdesign->setId($layoutdesignId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('dls_dlsblog')->__('Total of %d layout designs were successfully deleted.', count($layoutdesignIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('dls_dlsblog')->__('There was an error deleting layout designs.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massStatusAction()
    {
        $layoutdesignIds = $this->getRequest()->getParam('layoutdesign');
        if (!is_array($layoutdesignIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('dls_dlsblog')->__('Please select layout designs.')
            );
        } else {
            try {
                foreach ($layoutdesignIds as $layoutdesignId) {
                $layoutdesign = Mage::getSingleton('dls_dlsblog/layoutdesign')->load($layoutdesignId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d layout designs were successfully updated.', count($layoutdesignIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('dls_dlsblog')->__('There was an error updating layout designs.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Basic layout change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massBasicLayoutAction()
    {
        $layoutdesignIds = $this->getRequest()->getParam('layoutdesign');
        if (!is_array($layoutdesignIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('dls_dlsblog')->__('Please select layout designs.')
            );
        } else {
            try {
                foreach ($layoutdesignIds as $layoutdesignId) {
                $layoutdesign = Mage::getSingleton('dls_dlsblog/layoutdesign')->load($layoutdesignId)
                    ->setBasicLayout($this->getRequest()->getParam('flag_basic_layout'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d layout designs were successfully updated.', count($layoutdesignIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('dls_dlsblog')->__('There was an error updating layout designs.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * export as csv - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportCsvAction()
    {
        $fileName   = 'layoutdesign.csv';
        $content    = $this->getLayout()->createBlock('dls_dlsblog/adminhtml_layoutdesign_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as MsExcel - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportExcelAction()
    {
        $fileName   = 'layoutdesign.xls';
        $content    = $this->getLayout()->createBlock('dls_dlsblog/adminhtml_layoutdesign_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as xml - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportXmlAction()
    {
        $fileName   = 'layoutdesign.xml';
        $content    = $this->getLayout()->createBlock('dls_dlsblog/adminhtml_layoutdesign_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean
     * @author Ultimate Module Creator
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('dls_dlsblog/layoutdesign');
    }
}
