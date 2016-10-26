<?php

class DLS_DLSBlog_Adminhtml_Dlsblog_BlogsetController extends DLS_DLSBlog_Controller_Adminhtml_DLSBlog {

    protected function _initBlogset() {
        $blogsetId = (int) $this->getRequest()->getParam('id');
        $blogset = Mage::getModel('dls_dlsblog/blogset');
        if ($blogsetId) {
            $blogset->load($blogsetId);
        }
        Mage::register('current_blogset', $blogset);
        return $blogset;
    }

    public function indexAction() {
        $this->loadLayout();
        $this->_title(Mage::helper('dls_dlsblog')->__('DLS Blog'))
                ->_title(Mage::helper('dls_dlsblog')->__('Blogs setting'));
        $this->renderLayout();
    }

    public function gridAction() {
        $this->loadLayout()->renderLayout();
    }

    public function editAction() {
        $blogsetId = $this->getRequest()->getParam('id');
        $blogset = $this->_initBlogset();
        if ($blogsetId && !$blogset->getId()) {
            $this->_getSession()->addError(
                    Mage::helper('dls_dlsblog')->__('This blog setting no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getBlogsetData(true);
        if (!empty($data)) {
            $blogset->setData($data);
        }
        Mage::register('blogset_data', $blogset);
        $this->loadLayout();
        $this->_title(Mage::helper('dls_dlsblog')->__('DLS Blog'))
                ->_title(Mage::helper('dls_dlsblog')->__('Blogs setting'));
        if ($blogset->getId()) {
            $this->_title($blogset->getName());
        } else {
            $this->_title(Mage::helper('dls_dlsblog')->__('Add blog setting'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost('blogset')) {
            try {
                $blogset = $this->_initBlogset();
                $blogset->addData($data);
                $taxonomies = $this->getRequest()->getPost('taxonomy_ids', -1);
                if ($taxonomies != -1) {
                    $taxonomies = explode(',', $taxonomies);
                    $taxonomies = array_unique($taxonomies);
                    $blogset->setTaxonomiesData($taxonomies);
                }
                $blogset->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('dls_dlsblog')->__('Blog setting was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $blogset->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setBlogsetData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('dls_dlsblog')->__('There was a problem saving the blog setting.')
                );
                Mage::getSingleton('adminhtml/session')->setBlogsetData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('dls_dlsblog')->__('Unable to find blog setting to save.')
        );
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $blogset = Mage::getModel('dls_dlsblog/blogset');
                $blogset->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('dls_dlsblog')->__('Blog setting was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('dls_dlsblog')->__('There was an error deleting blog setting.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('dls_dlsblog')->__('Could not find blog setting to delete.')
        );
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $blogsetIds = $this->getRequest()->getParam('blogset');
        if (!is_array($blogsetIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('dls_dlsblog')->__('Please select blogs setting to delete.')
            );
        } else {
            try {
                foreach ($blogsetIds as $blogsetId) {
                    $blogset = Mage::getModel('dls_dlsblog/blogset');
                    $blogset->setId($blogsetId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('dls_dlsblog')->__('Total of %d blogs setting were successfully deleted.', count($blogsetIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('dls_dlsblog')->__('There was an error deleting blogs setting.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction() {
        $blogsetIds = $this->getRequest()->getParam('blogset');
        if (!is_array($blogsetIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('dls_dlsblog')->__('Please select blogs setting.')
            );
        } else {
            try {
                foreach ($blogsetIds as $blogsetId) {
                    $blogset = Mage::getSingleton('dls_dlsblog/blogset')->load($blogsetId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d blogs setting were successfully updated.', count($blogsetIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('dls_dlsblog')->__('There was an error updating blogs setting.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massLayoutdesignIdAction() {
        $blogsetIds = $this->getRequest()->getParam('blogset');
        if (!is_array($blogsetIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('dls_dlsblog')->__('Please select blogs setting.')
            );
        } else {
            try {
                foreach ($blogsetIds as $blogsetId) {
                    $blogset = Mage::getSingleton('dls_dlsblog/blogset')->load($blogsetId)
                            ->setLayoutdesignId($this->getRequest()->getParam('flag_layoutdesign_id'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d blogs setting were successfully updated.', count($blogsetIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('dls_dlsblog')->__('There was an error updating blogs setting.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function taxonomiesAction() {
        $this->_initBlogset();
        $this->loadLayout();
        $this->renderLayout();
    }

    public function taxonomiesJsonAction() {
        $this->_initBlogset();
        $this->getResponse()->setBody(
                $this->getLayout()->createBlock('dls_dlsblog/adminhtml_blogset_edit_tab_taxonomy')
                        ->getTaxonomyChildrenJson($this->getRequest()->getParam('taxonomy'))
        );
    }

    public function exportCsvAction() {
        $fileName = 'blogset.csv';
        $content = $this->getLayout()->createBlock('dls_dlsblog/adminhtml_blogset_grid')
                ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function exportExcelAction() {
        $fileName = 'blogset.xls';
        $content = $this->getLayout()->createBlock('dls_dlsblog/adminhtml_blogset_grid')
                ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function exportXmlAction() {
        $fileName = 'blogset.xml';
        $content = $this->getLayout()->createBlock('dls_dlsblog/adminhtml_blogset_grid')
                ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('dls_dlsblog/blogset');
    }

}
