<?php

/**
 * Filter admin controller
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Adminhtml_Dlsblog_FilterController extends DLS_DLSBlog_Controller_Adminhtml_DLSBlog
{
    /**
     * init the filter
     *
     * @access protected
     * @return DLS_DLSBlog_Model_Filter
     */
    protected function _initFilter()
    {
        $filterId  = (int) $this->getRequest()->getParam('id');
        $filter    = Mage::getModel('dls_dlsblog/filter');
        if ($filterId) {
            $filter->load($filterId);
        }
        Mage::register('current_filter', $filter);
        return $filter;
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
             ->_title(Mage::helper('dls_dlsblog')->__('Filters'));
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
     * edit filter - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function editAction()
    {
        $filterId    = $this->getRequest()->getParam('id');
        $filter      = $this->_initFilter();
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
        $this->renderLayout();
    }

    /**
     * new filter action
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
     * save filter - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('filter')) {
            try {
                $filter = $this->_initFilter();
                $filter->addData($data);
                $taxonomies = $this->getRequest()->getPost('taxonomy_ids', -1);
                if ($taxonomies != -1) {
                    $taxonomies = explode(',', $taxonomies);
                    $taxonomies = array_unique($taxonomies);
                    $filter->setTaxonomiesData($taxonomies);
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

    /**
     * delete filter - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
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

    /**
     * mass delete filter - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massDeleteAction()
    {
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

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massStatusAction()
    {
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

    /**
     * mass Exposed filter change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massExposedAction()
    {
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

    /**
     * mass Filter type change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massTypeAction()
    {
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

    /**
     * mass blog setting change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massBlogsetIdAction()
    {
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

    /**
     * mass layout design change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massLayoutdesignIdAction()
    {
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

    /**
     * get  action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function taxonomiesAction()
    {
        $this->_initFilter();
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * get child   action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function taxonomiesJsonAction()
    {
        $this->_initFilter();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('dls_dlsblog/adminhtml_filter_edit_tab_taxonomy')
                ->getTaxonomyChildrenJson($this->getRequest()->getParam('taxonomy'))
        );
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
        $fileName   = 'filter.csv';
        $content    = $this->getLayout()->createBlock('dls_dlsblog/adminhtml_filter_grid')
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
        $fileName   = 'filter.xls';
        $content    = $this->getLayout()->createBlock('dls_dlsblog/adminhtml_filter_grid')
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
        $fileName   = 'filter.xml';
        $content    = $this->getLayout()->createBlock('dls_dlsblog/adminhtml_filter_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('dls_dlsblog/filter');
    }
}
