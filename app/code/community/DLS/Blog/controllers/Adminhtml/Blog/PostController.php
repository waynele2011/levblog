<?php

class DLS_Blog_Adminhtml_Blog_PostController extends Mage_Adminhtml_Controller_Action {

    protected function _construct() {
        $this->setUsedModuleName('DLS_Blog');
    }

    protected function _initPost() {
        $this->_title($this->__('Blog'))
                ->_title($this->__('Manage Posts'));

        $postId = (int) $this->getRequest()->getParam('id');
        $post = Mage::getModel('dls_blog/post')
                ->setStoreId($this->getRequest()->getParam('store', 0));

        if ($postId) {
            $post->load($postId);
        }
        Mage::register('current_post', $post);
        return $post;
    }

    public function indexAction() {
        $this->_title($this->__('Blog'))
                ->_title($this->__('Manage Posts'));
        $this->loadLayout();
        $this->renderLayout();
    }

    public function newAction() {
        $this->_forward('edit');
    }

    public function editAction() {
        $postId = (int) $this->getRequest()->getParam('id');
        $post = $this->_initPost();
        if ($postId && !$post->getId()) {
            $this->_getSession()->addError(
                    Mage::helper('dls_blog')->__('This post no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        if ($data = Mage::getSingleton('adminhtml/session')->getPostData(true)) {
            $post->setData($data);
        }
        $this->_title($post->getTitle());
        Mage::dispatchEvent(
                'dls_blog_post_edit_action', array('post' => $post)
        );
        $this->loadLayout();
        if ($post->getId()) {
            if (!Mage::app()->isSingleStoreMode() && ($switchBlock = $this->getLayout()->getBlock('store_switcher'))) {
                $switchBlock->setDefaultStoreName(Mage::helper('dls_blog')->__('Default Values'))
                        ->setWebsiteIds($post->getWebsiteIds())
                        ->setSwitchUrl(
                                $this->getUrl(
                                        '*/*/*', array(
                                    '_current' => true,
                                    'active_tab' => null,
                                    'tab' => null,
                                    'store' => null
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

    public function saveAction() {
        $storeId = $this->getRequest()->getParam('store');
        $redirectBack = $this->getRequest()->getParam('back', false);
        $postId = $this->getRequest()->getParam('id');
        $isEdit = (int) ($this->getRequest()->getParam('id') != null);
        $data = $this->getRequest()->getPost();
        if ($data) {
            $post = $this->_initPost();
            $postData = $this->getRequest()->getPost('post', array());
            //
            $pubDate = $postData['publish_date'];
            $dtime = DateTime::createFromFormat("m/d/Y H:i A", $pubDate);
            $postData['publish_date'] = $dtime->format('Y-m-d H:i:s');
            //
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
                foreach ($tagsNameArray as $tagName) {
                    $tagsCollection = Mage::getModel('dls_blog/tag')->getCollection();
                    if (empty($tagName))
                        continue;
                    $tagsCollection->addFieldToFilter('name', $tagName);
                    if ($tagsCollection->count() < 1)
                        continue;
                    $tagInstance = $tagsCollection->getFirstItem();
                    $tagsData[$tagInstance->getId()] = array('position' => '');
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
                        Mage::helper('dls_blog')->__('Post was saved')
                );
            } catch (Mage_Core_Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage())
                        ->setPostData($postData);
                $redirectBack = true;
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError(
                                Mage::helper('dls_blog')->__('Error saving post')
                        )
                        ->setPostData($postData);
                $redirectBack = true;
            }
        }
        if ($redirectBack) {
            $this->_redirect(
                    '*/*/edit', array(
                'id' => $postId,
                '_current' => true
                    )
            );
        } else {
            $this->_redirect('*/*/', array('store' => $storeId));
        }
    }

    public function deleteAction() {
        if ($id = $this->getRequest()->getParam('id')) {
            $post = Mage::getModel('dls_blog/post')->load($id);
            try {
                $post->delete();
                $this->_getSession()->addSuccess(
                        Mage::helper('dls_blog')->__('The posts has been deleted.')
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->getResponse()->setRedirect(
                $this->getUrl('*/*/', array('store' => $this->getRequest()->getParam('store')))
        );
    }

    public function massDeleteAction() {
        $postIds = $this->getRequest()->getParam('post');
        if (!is_array($postIds)) {
            $this->_getSession()->addError($this->__('Please select posts.'));
        } else {
            try {
                foreach ($postIds as $postId) {
                    $post = Mage::getSingleton('dls_blog/post')->load($postId);
                    Mage::dispatchEvent(
                            'dls_blog_controller_post_delete', array('post' => $post)
                    );
                    $post->delete();
                }
                $this->_getSession()->addSuccess(
                        Mage::helper('dls_blog')->__('Total of %d record(s) have been deleted.', count($postIds))
                );
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction() {
        $postIds = $this->getRequest()->getParam('post');
        if (!is_array($postIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('dls_blog')->__('Please select posts.')
            );
        } else {
            try {
                foreach ($postIds as $postId) {
                    $post = Mage::getSingleton('dls_blog/post')->load($postId)
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
                        Mage::helper('dls_blog')->__('There was an error updating posts.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function gridAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('dls_blog/post');
    }

    public function exportCsvAction() {
        $fileName = 'posts.csv';
        $content = $this->getLayout()->createBlock('dls_blog/adminhtml_post_grid')
                ->getCsvFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function exportExcelAction() {
        $fileName = 'post.xls';
        $content = $this->getLayout()->createBlock('dls_blog/adminhtml_post_grid')
                ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function exportXmlAction() {
        $fileName = 'post.xml';
        $content = $this->getLayout()->createBlock('dls_blog/adminhtml_post_grid')
                ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function wysiwygAction() {
        $elementId = $this->getRequest()->getParam('element_id', md5(microtime()));
        $storeId = $this->getRequest()->getParam('store_id', 0);
        $storeMediaUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);

        $content = $this->getLayout()->createBlock(
                'dls_blog/adminhtml_blog_helper_form_wysiwyg_content', '', array(
            'editor_element_id' => $elementId,
            'store_id' => $storeId,
            'store_media_url' => $storeMediaUrl,
                )
        );
        $this->getResponse()->setBody($content->toHtml());
    }

    public function massPublishStatusAction() {
        $postIds = (array) $this->getRequest()->getParam('post');
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        $flag = (int) $this->getRequest()->getParam('flag_publish_status');
        if ($flag == 2) {
            $flag = 0;
        }
        try {
            foreach ($postIds as $postId) {
                $post = Mage::getSingleton('dls_blog/post')
                        ->setStoreId($storeId)
                        ->load($postId);
                $post->setPublishStatus($flag)->save();
            }
            $this->_getSession()->addSuccess(
                    Mage::helper('dls_blog')->__('Total of %d record(s) have been updated.', count($postIds))
            );
        } catch (Mage_Core_Model_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        } catch (Exception $e) {
            $this->_getSession()->addException(
                    $e, Mage::helper('dls_blog')->__('An error occurred while updating the posts.')
            );
        }
        $this->_redirect('*/*/', array('store' => $storeId));
    }

    public function massBlogsetIdAction() {
        $postIds = $this->getRequest()->getParam('post');
        if (!is_array($postIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('dls_blog')->__('Please select posts.')
            );
        } else {
            try {
                foreach ($postIds as $postId) {
                    $post = Mage::getSingleton('dls_blog/post')->load($postId)
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
                        Mage::helper('dls_blog')->__('There was an error updating posts.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massLayoutdesignIdAction() {
        $postIds = $this->getRequest()->getParam('post');
        if (!is_array($postIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('dls_blog')->__('Please select posts.')
            );
        } else {
            try {
                foreach ($postIds as $postId) {
                    $post = Mage::getSingleton('dls_blog/post')->load($postId)
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
                        Mage::helper('dls_blog')->__('There was an error updating posts.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function productsAction() {
        $this->_initPost();
        $this->loadLayout();
        $this->getLayout()->getBlock('post.edit.tab.product')
                ->setPostProducts($this->getRequest()->getPost('post_products', null));
        $this->renderLayout();
    }

    public function productsgridAction() {
        $this->_initPost();
        $this->loadLayout();
        $this->getLayout()->getBlock('post.edit.tab.product')
                ->setPostProducts($this->getRequest()->getPost('post_products', null));
        $this->renderLayout();
    }

    public function tagsAction() {
        $this->_initPost();
        $this->loadLayout();
        $this->getLayout()->getBlock('dls_blog.post.edit.tab.tag')
                ->setPostTags($this->getRequest()->getPost('tags', null));
        $this->renderLayout();
    }

    public function tagsGridAction() {
        $this->_initPost();
        $this->loadLayout();
        $this->getLayout()->getBlock('dls_blog.post.edit.tab.tag')
                ->setPostTags($this->getRequest()->getPost('tags', null));
        $this->renderLayout();
    }

    public function taxonomiesAction() {
        $this->_initPost();
        $this->loadLayout();
        $this->renderLayout();
    }

    public function taxonomiesJsonAction() {
        $this->_initPost();
        $this->getResponse()->setBody(
                $this->getLayout()->createBlock('dls_blog/adminhtml_post_edit_tab_taxonomy')
                        ->getTaxonomyChildrenJson($this->getRequest()->getParam('taxonomy'))
        );
    }

}
