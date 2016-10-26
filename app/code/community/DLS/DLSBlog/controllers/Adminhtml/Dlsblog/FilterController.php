<?php

class DLS_DLSBlog_Adminhtml_Dlsblog_FilterController extends DLS_DLSBlog_Controller_Adminhtml_DLSBlog {

    protected function _initFilter() {
        $filterId = (int) $this->getRequest()->getParam('id');
        $filter = Mage::getModel('dls_dlsblog/filter');
        if ($filterId) {
            $filter->load($filterId);
        }
        Mage::register('current_filter', $filter);
        return $filter;
    }

    public function indexAction() {
        $this->loadLayout();
        $this->_title(Mage::helper('dls_dlsblog')->__('DLS Blog'))
                ->_title(Mage::helper('dls_dlsblog')->__('Filters'));
        $this->renderLayout();
    }

    public function gridAction() {
        $this->loadLayout()->renderLayout();
    }

    public function editAction() {
        // temporary load rule model
//@TODO
        $model = Mage::getModel('salesrule/rule');
        $model->getConditions()->setJsFormObject('rule_conditions_fieldset');
        $model->getActions()->setJsFormObject('rule_actions_fieldset');
        Mage::register('current_promo_quote_rule', $model);

        $filterId = $this->getRequest()->getParam('id');
        $filter = $this->_initFilter();
        if ($filterId && !$filter->getId()) {
            $this->_getSession()->addError(
                    Mage::helper('dls_dlsblog')->__('This filter no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getFilterData(true);
        if (!empty($data)) {
            $filter->setData($data);
        }
        Mage::register('filter_data', $filter);
        $this->loadLayout();
        $this->_title(Mage::helper('dls_dlsblog')->__('DLS Blog'))
                ->_title(Mage::helper('dls_dlsblog')->__('Filters'));
        if ($filter->getId()) {
            $this->_title($filter->getName());
        } else {
            $this->_title(Mage::helper('dls_dlsblog')->__('Add filter'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
//@TODO
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->getLayout()->getBlock('head')->setCanLoadRulesJs(true);
        $this->renderLayout();
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost('filter')) {
            try {
                $filter = $this->_initFilter();
                $filter->addData($data);
                $posts = $this->getRequest()->getPost('posts', -1);
                if ($posts != -1) {
                    $filter->setPostsData(
                            Mage::helper('adminhtml/js')->decodeGridSerializedInput($posts)
                    );
                }
                $filter->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('dls_dlsblog')->__('Filter was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $filter->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFilterData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('dls_dlsblog')->__('There was a problem saving the filter.')
                );
                Mage::getSingleton('adminhtml/session')->setFilterData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('dls_dlsblog')->__('Unable to find filter to save.')
        );
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $filter = Mage::getModel('dls_dlsblog/filter');
                $filter->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('dls_dlsblog')->__('Filter was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('dls_dlsblog')->__('There was an error deleting filter.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('dls_dlsblog')->__('Could not find filter to delete.')
        );
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $filterIds = $this->getRequest()->getParam('filter');
        if (!is_array($filterIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('dls_dlsblog')->__('Please select filters to delete.')
            );
        } else {
            try {
                foreach ($filterIds as $filterId) {
                    $filter = Mage::getModel('dls_dlsblog/filter');
                    $filter->setId($filterId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('dls_dlsblog')->__('Total of %d filters were successfully deleted.', count($filterIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('dls_dlsblog')->__('There was an error deleting filters.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction() {
        $filterIds = $this->getRequest()->getParam('filter');
        if (!is_array($filterIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('dls_dlsblog')->__('Please select filters.')
            );
        } else {
            try {
                foreach ($filterIds as $filterId) {
                    $filter = Mage::getSingleton('dls_dlsblog/filter')->load($filterId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d filters were successfully updated.', count($filterIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('dls_dlsblog')->__('There was an error updating filters.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massExposedAction() {
        $filterIds = $this->getRequest()->getParam('filter');
        if (!is_array($filterIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('dls_dlsblog')->__('Please select filters.')
            );
        } else {
            try {
                foreach ($filterIds as $filterId) {
                    $filter = Mage::getSingleton('dls_dlsblog/filter')->load($filterId)
                            ->setExposed($this->getRequest()->getParam('flag_exposed'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d filters were successfully updated.', count($filterIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('dls_dlsblog')->__('There was an error updating filters.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massTypeAction() {
        $filterIds = $this->getRequest()->getParam('filter');
        if (!is_array($filterIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('dls_dlsblog')->__('Please select filters.')
            );
        } else {
            try {
                foreach ($filterIds as $filterId) {
                    $filter = Mage::getSingleton('dls_dlsblog/filter')->load($filterId)
                            ->setType($this->getRequest()->getParam('flag_type'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d filters were successfully updated.', count($filterIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('dls_dlsblog')->__('There was an error updating filters.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massBlogsetIdAction() {
        $filterIds = $this->getRequest()->getParam('filter');
        if (!is_array($filterIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('dls_dlsblog')->__('Please select filters.')
            );
        } else {
            try {
                foreach ($filterIds as $filterId) {
                    $filter = Mage::getSingleton('dls_dlsblog/filter')->load($filterId)
                            ->setBlogsetId($this->getRequest()->getParam('flag_blogset_id'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d filters were successfully updated.', count($filterIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('dls_dlsblog')->__('There was an error updating filters.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massTaxonomyIdAction() {
        $filterIds = $this->getRequest()->getParam('filter');
        if (!is_array($filterIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('dls_dlsblog')->__('Please select filters.')
            );
        } else {
            try {
                foreach ($filterIds as $filterId) {
                    $filter = Mage::getSingleton('dls_dlsblog/filter')->load($filterId)
                            ->setTaxonomyId($this->getRequest()->getParam('flag_taxonomy_id'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d filters were successfully updated.', count($filterIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('dls_dlsblog')->__('There was an error updating filters.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massLayoutdesignIdAction() {
        $filterIds = $this->getRequest()->getParam('filter');
        if (!is_array($filterIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('dls_dlsblog')->__('Please select filters.')
            );
        } else {
            try {
                foreach ($filterIds as $filterId) {
                    $filter = Mage::getSingleton('dls_dlsblog/filter')->load($filterId)
                            ->setLayoutdesignId($this->getRequest()->getParam('flag_layoutdesign_id'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d filters were successfully updated.', count($filterIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('dls_dlsblog')->__('There was an error updating filters.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function postsAction() {
        $this->_initFilter();
        $this->loadLayout();
        $this->getLayout()->getBlock('dls_dlsblog.filter.edit.tab.post')
                ->setFilterPosts($this->getRequest()->getPost('filter_posts', null));
        $this->renderLayout();
    }

    public function postsgridAction() {
        $this->_initFilter();
        $this->loadLayout();
        $this->getLayout()->getBlock('dls_dlsblog.filter.edit.tab.post')
                ->setFilterPosts($this->getRequest()->getPost('filter_posts', null));
        $this->renderLayout();
    }

    public function exportCsvAction() {
        $fileName = 'filter.csv';
        $content = $this->getLayout()->createBlock('dls_dlsblog/adminhtml_filter_grid')
                ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function exportExcelAction() {
        $fileName = 'filter.xls';
        $content = $this->getLayout()->createBlock('dls_dlsblog/adminhtml_filter_grid')
                ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function exportXmlAction() {
        $fileName = 'filter.xml';
        $content = $this->getLayout()->createBlock('dls_dlsblog/adminhtml_filter_grid')
                ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('dls_dlsblog/filter');
    }

}
