<?php

class DLS_Blog_Adminhtml_Blog_TaxonomyController extends DLS_Blog_Controller_Adminhtml_Blog {

    protected function _initTaxonomy() {
        $taxonomyId = (int) $this->getRequest()->getParam('id', false);
        $taxonomy = Mage::getModel('dls_blog/taxonomy');
        if ($taxonomyId) {
            $taxonomy->load($taxonomyId);
        } else {
            $taxonomy->setData($taxonomy->getDefaultValues());
        }
        if ($activeTabId = (string) $this->getRequest()->getParam('active_tab_id')) {
            Mage::getSingleton('admin/session')->setTaxonomyActiveTabId($activeTabId);
        }
        Mage::register('taxonomy', $taxonomy);
        Mage::register('current_taxonomy', $taxonomy);
        return $taxonomy;
    }

    public function indexAction() {
        $this->_forward('edit');
    }

    public function addAction() {
        Mage::getSingleton('admin/session')->unsTaxonomyActiveTabId();
        $this->_forward('edit');
    }

    public function editAction() {
        $params['_current'] = true;
        $redirect = false;
        $parentId = (int) $this->getRequest()->getParam('parent');
        $taxonomyId = (int) $this->getRequest()->getParam('id');
        $_prevTaxonomyId = Mage::getSingleton('admin/session')->getLastEditedTaxonomy(true);
        if ($_prevTaxonomyId &&
                !$this->getRequest()->getQuery('isAjax') &&
                !$this->getRequest()->getParam('clear')) {
            $this->getRequest()->setParam('id', $_prevTaxonomyId);
        }
        if ($redirect) {
            $this->_redirect('*/*/edit', $params);
            return;
        }
        if (!($taxonomy = $this->_initTaxonomy())) {
            return;
        }
        $this->_title($taxonomyId ? $taxonomy->getName() : $this->__('New Blog category'));
        $data = Mage::getSingleton('adminhtml/session')->getTaxonomyData(true);
        if (isset($data['taxonomy'])) {
            $taxonomy->addData($data['taxonomy']);
        }
        if ($this->getRequest()->getQuery('isAjax')) {
            $breadcrumbsPath = $taxonomy->getPath();
            if (empty($breadcrumbsPath)) {
                $breadcrumbsPath = Mage::getSingleton('admin/session')->getTaxonomyDeletedPath(true);
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
            Mage::getSingleton('admin/session')->setLastEditedTaxonomy($taxonomy->getId());
            $this->loadLayout();
            $eventResponse = new Varien_Object(
                    array(
                'content' => $this->getLayout()->getBlock('taxonomy.edit')->getFormHtml() .
                $this->getLayout()->getBlock('taxonomy.tree')->getBreadcrumbsJavascript(
                        $breadcrumbsPath, 'editingTaxonomyBreadcrumbs'
                ),
                'messages' => $this->getLayout()->getMessagesBlock()->getGroupedHtml(),
                    )
            );
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($eventResponse->getData()));
            return;
        }
        $this->loadLayout();
        $this->_title(Mage::helper('dls_blog')->__('Blog'))
                ->_title(Mage::helper('dls_blog')->__('Taxonomies'));
        $this->_setActiveMenu('dls_blog/taxonomy');
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true)
                ->setContainerCssClass('taxonomy');

        $this->_addBreadcrumb(
                Mage::helper('dls_blog')->__('Manage Taxonomies'), Mage::helper('dls_blog')->__('Manage Taxonomies')
        );
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    public function taxonomiesJsonAction() {
        if ($this->getRequest()->getParam('expand_all')) {
            Mage::getSingleton('admin/session')->setTaxonomyIsTreeWasExpanded(true);
        } else {
            Mage::getSingleton('admin/session')->setTaxonomyIsTreeWasExpanded(false);
        }
        if ($taxonomyId = (int) $this->getRequest()->getPost('id')) {
            $this->getRequest()->setParam('id', $taxonomyId);
            if (!$taxonomy = $this->_initTaxonomy()) {
                return;
            }
            $this->getResponse()->setBody(
                    $this->getLayout()->createBlock('dls_blog/adminhtml_taxonomy_tree')
                            ->getTreeJson($taxonomy)
            );
        }
    }

    public function moveAction() {
        $taxonomy = $this->_initTaxonomy();
        if (!$taxonomy) {
            $this->getResponse()->setBody(
                    Mage::helper('dls_blog')->__('Blog category move error')
            );
            return;
        }
        $parentNodeId = $this->getRequest()->getPost('pid', false);
        $prevNodeId = $this->getRequest()->getPost('aid', false);
        try {
            $taxonomy->move($parentNodeId, $prevNodeId);
            $this->getResponse()->setBody("SUCCESS");
        } catch (Mage_Core_Exception $e) {
            $this->getResponse()->setBody($e->getMessage());
        } catch (Exception $e) {
            $this->getResponse()->setBody(
                    Mage::helper('dls_blog')->__('Blog category move error')
            );
            Mage::logException($e);
        }
    }

    public function treeAction() {
        $taxonomyId = (int) $this->getRequest()->getParam('id');
        $taxonomy = $this->_initTaxonomy();
        $block = $this->getLayout()->createBlock('dls_blog/adminhtml_taxonomy_tree');
        $root = $block->getRoot();
        $this->getResponse()->setBody(
                Mage::helper('core')->jsonEncode(
                        array(
                            'data' => $block->getTree(),
                            'parameters' => array(
                                'text' => $block->buildNodeName($root),
                                'draggable' => false,
                                'allowDrop' => ($root->getIsVisible()) ? true : false,
                                'id' => (int) $root->getId(),
                                'expanded' => (int) $block->getIsWasExpanded(),
                                'taxonomy_id' => (int) $taxonomy->getId(),
                                'root_visible' => (int) $root->getIsVisible()
                            )
                        )
                )
        );
    }

    public function refreshPathAction() {
        if ($id = (int) $this->getRequest()->getParam('id')) {
            $taxonomy = Mage::getModel('dls_blog/taxonomy')->load($id);
            $this->getResponse()->setBody(
                    Mage::helper('core')->jsonEncode(
                            array(
                                'id' => $id,
                                'path' => $taxonomy->getPath(),
                            )
                    )
            );
        }
    }

    public function deleteAction() {
        if ($id = (int) $this->getRequest()->getParam('id')) {
            try {
                $taxonomy = Mage::getModel('dls_blog/taxonomy')->load($id);
                Mage::getSingleton('admin/session')->setTaxonomyDeletedPath($taxonomy->getPath());

                $taxonomy->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('dls_blog')->__('The taxonomy has been deleted.')
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->getResponse()->setRedirect($this->getUrl('*/*/edit', array('_current' => true)));
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('dls_blog')->__('An error occurred while trying to delete the taxonomy.')
                );
                $this->getResponse()->setRedirect($this->getUrl('*/*/edit', array('_current' => true)));
                Mage::logException($e);
                return;
            }
        }
        $this->getResponse()->setRedirect($this->getUrl('*/*/', array('_current' => true, 'id' => null)));
    }

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('dls_blog/taxonomy');
    }

    public function saveAction() {
        if (!$taxonomy = $this->_initTaxonomy()) {
            return;
        }
        $refreshTree = 'false';
        if ($data = $this->getRequest()->getPost('taxonomy')) {
            $taxonomy->addData($data);
            $smallImageName = $this->_uploadAndGetName(
                    'small_image', Mage::helper('dls_blog/taxonomy_image')->getImageBaseDir(), $data
            );
            $taxonomy->setData('small_image', $smallImageName);
            $largeImageName = $this->_uploadAndGetName(
                    'large_image', Mage::helper('dls_blog/taxonomy_image')->getImageBaseDir(), $data
            );
            $taxonomy->setData('large_image', $largeImageName);
            if (!$taxonomy->getId()) {
                $parentId = $this->getRequest()->getParam('parent');
                if (!$parentId) {
                    $parentId = Mage::helper('dls_blog/taxonomy')->getRootTaxonomyId();
                }
                $parentTaxonomy = Mage::getModel('dls_blog/taxonomy')->load($parentId);
                $taxonomy->setPath($parentTaxonomy->getPath());
            }
            try {
                $blogsets = $this->getRequest()->getPost('taxonomy_blogsets', -1);
                if ($blogsets != -1) {
                    $blogsetData = array();
                    parse_str($blogsets, $blogsetData);
                    foreach ($blogsetData as $id => $position) {
                        $blogset[$id]['position'] = $position;
                    }
                    $taxonomy->setBlogsetsData($blogsetData);
                }
                $filters = $this->getRequest()->getPost('taxonomy_filters', -1);
                if ($filters != -1) {
                    $filterData = array();
                    parse_str($filters, $filterData);
                    foreach ($filterData as $id => $position) {
                        $filter[$id]['position'] = $position;
                    }
                    $taxonomy->setFiltersData($filterData);
                }
                $posts = $this->getRequest()->getPost('taxonomy_posts', -1);
                if ($posts != -1) {
                    $postData = array();
                    parse_str($posts, $postData);
                    foreach ($postData as $id => $position) {
                        $post[$id]['position'] = $position;
                    }
                    $taxonomy->setPostsData($postData);
                }
                $taxonomy->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('dls_blog')->__('The taxonomy has been saved.')
                );
                $refreshTree = 'true';
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage())->setTaxonomyData($data);
                Mage::logException($e);
                $refreshTree = 'false';
            }
        }
        $url = $this->getUrl('*/*/edit', array('_current' => true, 'id' => $taxonomy->getId()));
        $this->getResponse()->setBody(
                '<script type="text/javascript">parent.updateContent("' . $url . '", {}, ' . $refreshTree . ');</script>'
        );
    }

    public function blogsetsgridAction() {
        if (!$taxonomy = $this->_initTaxonomy()) {
            return;
        }
        $this->getResponse()->setBody(
                $this->getLayout()->createBlock(
                                'dls_blog/adminhtml_taxonomy_edit_tab_blogset', 'taxonomy.blogset.grid'
                        )
                        ->toHtml()
        );
    }

    public function filtersgridAction() {
        if (!$taxonomy = $this->_initTaxonomy()) {
            return;
        }
        $this->getResponse()->setBody(
                $this->getLayout()->createBlock(
                                'dls_blog/adminhtml_taxonomy_edit_tab_filter', 'taxonomy.filter.grid'
                        )
                        ->toHtml()
        );
    }

    public function postsgridAction() {
        if (!$taxonomy = $this->_initTaxonomy()) {
            return;
        }
        $this->getResponse()->setBody(
                $this->getLayout()->createBlock(
                                'dls_blog/adminhtml_taxonomy_edit_tab_post', 'taxonomy.post.grid'
                        )
                        ->toHtml()
        );
    }

}
