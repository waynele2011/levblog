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
     * init layout design
     *
     * @access protected
     * @return DLS_DLSBlog_Model_Layoutdesign
     * @author Ultimate Module Creator
     */
    protected function _initLayoutdesign()
    {
        $layoutdesignId = (int) $this->getRequest()->getParam('id', false);
        $layoutdesign = Mage::getModel('dls_dlsblog/layoutdesign');
        if ($layoutdesignId) {
            $layoutdesign->load($layoutdesignId);
        } else {
            $layoutdesign->setData($layoutdesign->getDefaultValues());
        }
        if ($activeTabId = (string) $this->getRequest()->getParam('active_tab_id')) {
            Mage::getSingleton('admin/session')->setLayoutdesignActiveTabId($activeTabId);
        }
        Mage::register('layoutdesign', $layoutdesign);
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
        $this->_forward('edit');
    }

    /**
     * Add new layout design form
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function addAction()
    {
        Mage::getSingleton('admin/session')->unsLayoutdesignActiveTabId();
        $this->_forward('edit');
    }

    /**
     * Edit layout design page
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function editAction()
    {
        $params['_current'] = true;
        $redirect = false;
        $parentId = (int) $this->getRequest()->getParam('parent');
        $layoutdesignId = (int) $this->getRequest()->getParam('id');
        $_prevLayoutdesignId = Mage::getSingleton('admin/session')->getLastEditedLayoutdesign(true);
        if ($_prevLayoutdesignId &&
            !$this->getRequest()->getQuery('isAjax') &&
            !$this->getRequest()->getParam('clear')) {
            $this->getRequest()->setParam('id', $_prevLayoutdesignId);
        }
        if ($redirect) {
            $this->_redirect('*/*/edit', $params);
            return;
        }
        if (!($layoutdesign = $this->_initLayoutdesign())) {
            return;
        }
        $this->_title($layoutdesignId ? $layoutdesign->getName() : $this->__('New Layout design'));
        $data = Mage::getSingleton('adminhtml/session')->getLayoutdesignData(true);
        if (isset($data['layoutdesign'])) {
            $layoutdesign->addData($data['layoutdesign']);
        }
        if ($this->getRequest()->getQuery('isAjax')) {
            $breadcrumbsPath = $layoutdesign->getPath();
            if (empty($breadcrumbsPath)) {
                $breadcrumbsPath = Mage::getSingleton('admin/session')->getLayoutdesignDeletedPath(true);
                if (!empty($breadcrumbsPath)) {
                    $breadcrumbsPath = explode('/', $breadcrumbsPath);
                    if (count($breadcrumbsPath) <= 1) {
                        $breadcrumbsPath = '';
                    } else {
                        array_pop($breadcrumbsPath);
                        $breadcrumbsPath = implode('/', $breadcrumbsPath);
                    }
                }
            }
            Mage::getSingleton('admin/session')->setLastEditedLayoutdesign($layoutdesign->getId());
            $this->loadLayout();
            $eventResponse = new Varien_Object(
                array(
                    'content' => $this->getLayout()->getBlock('layoutdesign.edit')->getFormHtml().
                        $this->getLayout()->getBlock('layoutdesign.tree')->getBreadcrumbsJavascript(
                            $breadcrumbsPath,
                            'editingLayoutdesignBreadcrumbs'
                        ),
                    'messages' => $this->getLayout()->getMessagesBlock()->getGroupedHtml(),
                )
            );
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($eventResponse->getData()));
            return;
        }
        $this->loadLayout();
        $this->_title(Mage::helper('dls_dlsblog')->__('DLS Blog'))
             ->_title(Mage::helper('dls_dlsblog')->__('Layout designs'));
        $this->_setActiveMenu('dls_dlsblog/layoutdesign');
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true)
            ->setContainerCssClass('layoutdesign');

        $this->_addBreadcrumb(
            Mage::helper('dls_dlsblog')->__('Manage Layout designs'),
            Mage::helper('dls_dlsblog')->__('Manage Layout designs')
        );
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * Get tree node (Ajax version)
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function layoutdesignsJsonAction()
    {
        if ($this->getRequest()->getParam('expand_all')) {
            Mage::getSingleton('admin/session')->setLayoutdesignIsTreeWasExpanded(true);
        } else {
            Mage::getSingleton('admin/session')->setLayoutdesignIsTreeWasExpanded(false);
        }
        if ($layoutdesignId = (int) $this->getRequest()->getPost('id')) {
            $this->getRequest()->setParam('id', $layoutdesignId);
            if (!$layoutdesign = $this->_initLayoutdesign()) {
                return;
            }
            $this->getResponse()->setBody(
                $this->getLayout()->createBlock('dls_dlsblog/adminhtml_layoutdesign_tree')
                    ->getTreeJson($layoutdesign)
            );
        }
    }

    /**
     * Move layout design action
     * @access public
     * @author Ultimate Module Creator
     */
    public function moveAction()
    {
        $layoutdesign = $this->_initLayoutdesign();
        if (!$layoutdesign) {
            $this->getResponse()->setBody(
                Mage::helper('dls_dlsblog')->__('Layout design move error')
            );
            return;
        }
        $parentNodeId   = $this->getRequest()->getPost('pid', false);
        $prevNodeId = $this->getRequest()->getPost('aid', false);
        try {
            $layoutdesign->move($parentNodeId, $prevNodeId);
            $this->getResponse()->setBody("SUCCESS");
        } catch (Mage_Core_Exception $e) {
            $this->getResponse()->setBody($e->getMessage());
        } catch (Exception $e) {
            $this->getResponse()->setBody(
                Mage::helper('dls_dlsblog')->__('Layout design move error')
            );
            Mage::logException($e);
        }
    }

    /**
     * Tree Action
     * Retrieve layout design tree
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function treeAction()
    {
        $layoutdesignId = (int) $this->getRequest()->getParam('id');
        $layoutdesign = $this->_initLayoutdesign();
        $block = $this->getLayout()->createBlock('dls_dlsblog/adminhtml_layoutdesign_tree');
        $root  = $block->getRoot();
        $this->getResponse()->setBody(
            Mage::helper('core')->jsonEncode(
                array(
                    'data' => $block->getTree(),
                    'parameters' => array(
                        'text'          => $block->buildNodeName($root),
                        'draggable'     => false,
                        'allowDrop'     => ($root->getIsVisible()) ? true : false,
                        'id'            => (int) $root->getId(),
                        'expanded'      => (int) $block->getIsWasExpanded(),
                        'layoutdesign_id' => (int) $layoutdesign->getId(),
                        'root_visible'  => (int) $root->getIsVisible()
                    )
                )
            )
        );
    }

    /**
     * Build response for refresh input element 'path' in form
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function refreshPathAction()
    {
        if ($id = (int) $this->getRequest()->getParam('id')) {
            $layoutdesign = Mage::getModel('dls_dlsblog/layoutdesign')->load($id);
            $this->getResponse()->setBody(
                Mage::helper('core')->jsonEncode(
                    array(
                       'id' => $id,
                       'path' => $layoutdesign->getPath(),
                    )
                )
            );
        }
    }

    /**
     * Delete layout design action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function deleteAction()
    {
        if ($id = (int) $this->getRequest()->getParam('id')) {
            try {
                $layoutdesign = Mage::getModel('dls_dlsblog/layoutdesign')->load($id);
                Mage::getSingleton('admin/session')->setLayoutdesignDeletedPath($layoutdesign->getPath());

                $layoutdesign->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('dls_dlsblog')->__('The layout design has been deleted.')
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->getResponse()->setRedirect($this->getUrl('*/*/edit', array('_current'=>true)));
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('dls_dlsblog')->__('An error occurred while trying to delete the layout design.')
                );
                $this->getResponse()->setRedirect($this->getUrl('*/*/edit', array('_current'=>true)));
                Mage::logException($e);
                return;
            }
        }
        $this->getResponse()->setRedirect($this->getUrl('*/*/', array('_current'=>true, 'id'=>null)));
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

    /**
     * Layout design save action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function saveAction()
    {
        if (!$layoutdesign = $this->_initLayoutdesign()) {
            return;
        }
        $refreshTree = 'false';
        if ($data = $this->getRequest()->getPost('layoutdesign')) {
            $layoutdesign->addData($data);
            if (!$layoutdesign->getId()) {
                $parentId = $this->getRequest()->getParam('parent');
                if (!$parentId) {
                    $parentId = Mage::helper('dls_dlsblog/layoutdesign')->getRootLayoutdesignId();
                }
                $parentLayoutdesign = Mage::getModel('dls_dlsblog/layoutdesign')->load($parentId);
                $layoutdesign->setPath($parentLayoutdesign->getPath());
            }
            try {
                $layoutdesign->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('dls_dlsblog')->__('The layout design has been saved.')
                );
                $refreshTree = 'true';
            }
            catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage())->setLayoutdesignData($data);
                Mage::logException($e);
                $refreshTree = 'false';
            }
        }
        $url = $this->getUrl('*/*/edit', array('_current' => true, 'id' => $layoutdesign->getId()));
        $this->getResponse()->setBody(
            '<script type="text/javascript">parent.updateContent("' . $url . '", {}, '.$refreshTree.');</script>'
        );
    }
}
