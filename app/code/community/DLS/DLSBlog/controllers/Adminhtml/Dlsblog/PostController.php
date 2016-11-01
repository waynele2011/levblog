<?php

/**
 * Post admin controller
 *
 * @category    DLS
 * @package     DLS_Blog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Adminhtml_Dlsblog_PostController extends Mage_Adminhtml_Controller_Action
{
    /**
     * constructor - set the used module name
     *
     * @access protected
     * @return void
     * @see Mage_Core_Controller_Varien_Action::_construct()
     * @author Ultimate Module Creator
     */
    protected function _construct()
    {
        $this->setUsedModuleName('DLS_DLSBlog');
    }

    /**
     * init the post
     *
     * @access protected 
     * @return DLS_Blog_Model_Post
     * @author Ultimate Module Creator
     */
    protected function _initPost()
    {
        $this->_title($this->__('Blog'))
             ->_title($this->__('Manage Posts'));

        $postId  = (int) $this->getRequest()->getParam('id');
        $post    = Mage::getModel('dls_dlsblog/post')
            ->setStoreId($this->getRequest()->getParam('store', 0));

        if ($postId) {
            $post->load($postId);
        }
        Mage::register('current_post', $post);
        return $post;
    }

    /**
     * default action for post controller
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function indexAction()
    {
        $this->_title($this->__('Blog'))
             ->_title($this->__('Manage Posts'));
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * new post action
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
     * edit post action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function editAction()
    {
        $postId  = (int) $this->getRequest()->getParam('id');
        $post    = $this->_initPost();
        if ($postId && !$post->getId()) {
            $this->_getSession()->addError(
                Mage::helper('dls_dlsblog')->__('This post no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        if ($data = Mage::getSingleton('adminhtml/session')->getPostData(true)) {
            $post->setData($data);
        }
        $this->_title($post->getTitle());
        Mage::dispatchEvent(
            'dls_dlsblog_post_edit_action',
            array('post' => $post)
        );
        $this->loadLayout();
        if ($post->getId()) {
            if (!Mage::app()->isSingleStoreMode() && ($switchBlock = $this->getLayout()->getBlock('store_switcher'))) {
                $switchBlock->setDefaultStoreName(Mage::helper('dls_dlsblog')->__('Default Values'))
                    ->setWebsiteIds($post->getWebsiteIds())
                    ->setSwitchUrl(
                        $this->getUrl(
                            '*/*/*',
                            array(
                                '_current'=>true,
                                'active_tab'=>null,
                                'tab' => null,
                                'store'=>null
                            )
                        )
                    );
            }
        } else {
            $this->getLayout()->getBlock('left')->unsetChild('store_switcher');
        }
        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
        $this->renderLayout();
    }

    /**
     * save post action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function saveAction()
    {
        $storeId        = $this->getRequest()->getParam('store');
        $redirectBack   = $this->getRequest()->getParam('back', false);
        $postId   = $this->getRequest()->getParam('id');
        $isEdit         = (int)($this->getRequest()->getParam('id') != null);
        $data = $this->getRequest()->getPost();
        if ($data) {
            $post     = $this->_initPost();
            $postData = $this->getRequest()->getPost('post', array());
            $post->addData($postData);
            $post->setAttributeSetId($post->getDefaultAttributeSetId());
                $products = $this->getRequest()->getPost('products', -1);
                if ($products != -1) {
                    $post->setProductsData(
                        Mage::helper('adminhtml/js')->decodeGridSerializedInput($products)
                    );
                }
            if (isset($data['tags'])) {
                $post->setTagsData(
                    Mage::helper('adminhtml/js')->decodeGridSerializedInput($data['tags'])
                );
            } elseif (isset($data['post']['tags_custom_hidden'])) {
                $tagsNameArray = array_map('trim', explode(',', $data['post']['tags_custom_hidden']));
                $tagsData = array();
                foreach($tagsNameArray as $tagName ) {
                    $tagsCollection = Mage::getModel('dls_dlsblog/tag')->getCollection();
                    if(empty($tagName)) continue;
                    $tagsCollection->addFieldToFilter('name', $tagName);
                    if($tagsCollection->count() < 1) continue;
                    $tagInstance = $tagsCollection->getFirstItem();
                    $tagsData[$tagInstance->getId()] = array('position'=>'');
                }
                $post->setTagsData($tagsData);
            }
                $taxonomies = $this->getRequest()->getPost('taxonomy_ids', -1);
                if ($taxonomies != -1) {
                    $taxonomies = explode(',', $taxonomies);
                    $taxonomies = array_unique($taxonomies);
                    $post->setTaxonomiesData($taxonomies);
                }
            if ($useDefaults = $this->getRequest()->getPost('use_default')) {
                foreach ($useDefaults as $attributeCode) {
                    $post->setData($attributeCode, false);
                }
            }
            try {
                $post->save();
                $postId = $post->getId();
                $this->_getSession()->addSuccess(
                    Mage::helper('dls_dlsblog')->__('Post was saved')
                );
            } catch (Mage_Core_Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage())
                    ->setPostData($postData);
                $redirectBack = true;
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError(
                    Mage::helper('dls_dlsblog')->__('Error saving post')
                )
                ->setPostData($postData);
                $redirectBack = true;
            }
        }
        if ($redirectBack) {
            $this->_redirect(
                '*/*/edit',
                array(
                    'id'    => $postId,
                    '_current'=>true
                )
            );
        } else {
            $this->_redirect('*/*/', array('store'=>$storeId));
        }
    }

    /**
     * delete post
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $post = Mage::getModel('dls_dlsblog/post')->load($id);
            try {
                $post->delete();
                $this->_getSession()->addSuccess(
                    Mage::helper('dls_dlsblog')->__('The posts has been deleted.')
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->getResponse()->setRedirect(
            $this->getUrl('*/*/', array('store'=>$this->getRequest()->getParam('store')))
        );
    }

    /**
     * mass delete posts
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massDeleteAction()
    {
        $postIds = $this->getRequest()->getParam('post');
        if (!is_array($postIds)) {
            $this->_getSession()->addError($this->__('Please select posts.'));
        } else {
            try {
                foreach ($postIds as $postId) {
                    $post = Mage::getSingleton('dls_dlsblog/post')->load($postId);
                    Mage::dispatchEvent(
                        'dls_dlsblog_controller_post_delete',
                        array('post' => $post)
                    );
                    $post->delete();
                }
                $this->_getSession()->addSuccess(
                    Mage::helper('dls_dlsblog')->__('Total of %d record(s) have been deleted.', count($postIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
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
        $postIds = $this->getRequest()->getParam('post');
        if (!is_array($postIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('dls_dlsblog')->__('Please select posts.')
            );
        } else {
            try {
                foreach ($postIds as $postId) {
                $post = Mage::getSingleton('dls_dlsblog/post')->load($postId)
                    ->setStatus($this->getRequest()->getParam('status'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d posts were successfully updated.', count($postIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('dls_dlsblog')->__('There was an error updating posts.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
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
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * restrict access
     *
     * @access protected
     * @return bool
     * @see Mage_Adminhtml_Controller_Action::_isAllowed()
     * @author Ultimate Module Creator
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('dls_dlsblog/post');
    }

    /**
     * Export posts in CSV format
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportCsvAction()
    {
        $fileName   = 'posts.csv';
        $content    = $this->getLayout()->createBlock('dls_dlsblog/adminhtml_post_grid')
            ->getCsvFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export posts in Excel format
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportExcelAction()
    {
        $fileName   = 'post.xls';
        $content    = $this->getLayout()->createBlock('dls_dlsblog/adminhtml_post_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Export posts in XML format
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportXmlAction()
    {
        $fileName   = 'post.xml';
        $content    = $this->getLayout()->createBlock('dls_dlsblog/adminhtml_post_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * wysiwyg editor action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function wysiwygAction()
    {
        $elementId     = $this->getRequest()->getParam('element_id', md5(microtime()));
        $storeId       = $this->getRequest()->getParam('store_id', 0);
        $storeMediaUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);

        $content = $this->getLayout()->createBlock(
            'dls_dlsblog/adminhtml_dlsblog_helper_form_wysiwyg_content',
            '',
            array(
                'editor_element_id' => $elementId,
                'store_id'          => $storeId,
                'store_media_url'   => $storeMediaUrl,
            )
        );
        $this->getResponse()->setBody($content->toHtml());
    }

    /**
     * mass Publishing status change
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massPublishStatusAction()
    {
        $postIds = (array)$this->getRequest()->getParam('post');
        $storeId       = (int)$this->getRequest()->getParam('store', 0);
        $flag          = (int)$this->getRequest()->getParam('flag_publish_status');
        if ($flag == 2) {
            $flag = 0;
        }
        try {
            foreach ($postIds as $postId) {
                $post = Mage::getSingleton('dls_dlsblog/post')
                    ->setStoreId($storeId)
                    ->load($postId);
                $post->setPublishStatus($flag)->save();
            }
            $this->_getSession()->addSuccess(
                Mage::helper('dls_dlsblog')->__('Total of %d record(s) have been updated.', count($postIds))
            );
        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('dls_dlsblog')->__('An error occurred while updating the posts.')
            );
        }
        $this->_redirect('*/*/', array('store'=> $storeId));
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
        $postIds = $this->getRequest()->getParam('post');
        if (!is_array($postIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('dls_dlsblog')->__('Please select posts.')
            );
        } else {
            try {
                foreach ($postIds as $postId) {
                $post = Mage::getSingleton('dls_dlsblog/post')->load($postId)
                    ->setBlogsetId($this->getRequest()->getParam('flag_blogset_id'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d posts were successfully updated.', count($postIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('dls_dlsblog')->__('There was an error updating posts.')
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
        $postIds = $this->getRequest()->getParam('post');
        if (!is_array($postIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('dls_dlsblog')->__('Please select posts.')
            );
        } else {
            try {
                foreach ($postIds as $postId) {
                $post = Mage::getSingleton('dls_dlsblog/post')->load($postId)
                    ->setLayoutdesignId($this->getRequest()->getParam('flag_layoutdesign_id'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d posts were successfully updated.', count($postIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('dls_dlsblog')->__('There was an error updating posts.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * get grid of products action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function productsAction()
    {
        $this->_initPost();
        $this->loadLayout();
        $this->getLayout()->getBlock('post.edit.tab.product')
            ->setPostProducts($this->getRequest()->getPost('post_products', null));
        $this->renderLayout();
    }

    /**
     * get grid of products action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function productsgridAction()
    {
        $this->_initPost();
        $this->loadLayout();
        $this->getLayout()->getBlock('post.edit.tab.product')
            ->setPostProducts($this->getRequest()->getPost('post_products', null));
        $this->renderLayout();
    }

    /**
     *  on the current post
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function tagsAction()
    {
        $this->_initPost();
        $this->loadLayout();
        $this->getLayout()->getBlock('dls_dlsblog.post.edit.tab.tag')
            ->setPostTags($this->getRequest()->getPost('tags', null));
        $this->renderLayout();
    }

    /**
     *  on the current post
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function tagsGridAction()
    {
        $this->_initPost();
        $this->loadLayout();
        $this->getLayout()->getBlock('dls_dlsblog.post.edit.tab.tag')
            ->setPostTags($this->getRequest()->getPost('tags', null));
        $this->renderLayout();
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
        $this->_initPost();
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
        $this->_initPost();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('dls_dlsblog/adminhtml_post_edit_tab_taxonomy')
                ->getTaxonomyChildrenJson($this->getRequest()->getParam('taxonomy'))
        );
    }
}
