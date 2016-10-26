<?php

class DLS_DLSBlog_Adminhtml_Dlsblog_TagController extends DLS_DLSBlog_Controller_Adminhtml_DLSBlog {

    protected function _initTag() {
        $tagId = (int) $this->getRequest()->getParam('id');
        $tag = Mage::getModel('dls_dlsblog/tag');
        if ($tagId) {
            $tag->load($tagId);
        }
        Mage::register('current_tag', $tag);
        return $tag;
    }

    public function indexAction() {
        $this->loadLayout();
        $this->_title(Mage::helper('dls_dlsblog')->__('DLS Blog'))
                ->_title(Mage::helper('dls_dlsblog')->__('Tags'));
        $this->renderLayout();
    }

    public function gridAction() {
        $this->loadLayout()->renderLayout();
    }

    public function editAction() {
        $tagId = $this->getRequest()->getParam('id');
        $tag = $this->_initTag();
        if ($tagId && !$tag->getId()) {
            $this->_getSession()->addError(
                    Mage::helper('dls_dlsblog')->__('This tag no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getTagData(true);
        if (!empty($data)) {
            $tag->setData($data);
        }
        Mage::register('tag_data', $tag);
        $this->loadLayout();
        $this->_title(Mage::helper('dls_dlsblog')->__('DLS Blog'))
                ->_title(Mage::helper('dls_dlsblog')->__('Tags'));
        if ($tag->getId()) {
            $this->_title($tag->getName());
        } else {
            $this->_title(Mage::helper('dls_dlsblog')->__('Add tag'));
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
        if ($data = $this->getRequest()->getPost('tag')) {
            try {
                $tag = $this->_initTag();
                $tag->addData($data);
                $posts = $this->getRequest()->getPost('posts', -1);
                if ($posts != -1) {
                    $tag->setPostsData(
                            Mage::helper('adminhtml/js')->decodeGridSerializedInput($posts)
                    );
                }
                $tag->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('dls_dlsblog')->__('Tag was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $tag->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setTagData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('dls_dlsblog')->__('There was a problem saving the tag.')
                );
                Mage::getSingleton('adminhtml/session')->setTagData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('dls_dlsblog')->__('Unable to find tag to save.')
        );
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $tag = Mage::getModel('dls_dlsblog/tag');
                $tag->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('dls_dlsblog')->__('Tag was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('dls_dlsblog')->__('There was an error deleting tag.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('dls_dlsblog')->__('Could not find tag to delete.')
        );
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $tagIds = $this->getRequest()->getParam('tag');
        if (!is_array($tagIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('dls_dlsblog')->__('Please select tags to delete.')
            );
        } else {
            try {
                foreach ($tagIds as $tagId) {
                    $tag = Mage::getModel('dls_dlsblog/tag');
                    $tag->setId($tagId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('dls_dlsblog')->__('Total of %d tags were successfully deleted.', count($tagIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('dls_dlsblog')->__('There was an error deleting tags.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction() {
        $tagIds = $this->getRequest()->getParam('tag');
        if (!is_array($tagIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('dls_dlsblog')->__('Please select tags.')
            );
        } else {
            try {
                foreach ($tagIds as $tagId) {
                    $tag = Mage::getSingleton('dls_dlsblog/tag')->load($tagId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d tags were successfully updated.', count($tagIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('dls_dlsblog')->__('There was an error updating tags.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function postsAction() {
        $this->_initTag();
        $this->loadLayout();
        $this->getLayout()->getBlock('dls_dlsblog.tag.edit.tab.post')
                ->setTagPosts($this->getRequest()->getPost('tag_posts', null));
        $this->renderLayout();
    }

    public function postsgridAction() {
        $this->_initTag();
        $this->loadLayout();
        $this->getLayout()->getBlock('dls_dlsblog.tag.edit.tab.post')
                ->setTagPosts($this->getRequest()->getPost('tag_posts', null));
        $this->renderLayout();
    }

    public function exportCsvAction() {
        $fileName = 'tag.csv';
        $content = $this->getLayout()->createBlock('dls_dlsblog/adminhtml_tag_grid')
                ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function exportExcelAction() {
        $fileName = 'tag.xls';
        $content = $this->getLayout()->createBlock('dls_dlsblog/adminhtml_tag_grid')
                ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function exportXmlAction() {
        $fileName = 'tag.xml';
        $content = $this->getLayout()->createBlock('dls_dlsblog/adminhtml_tag_grid')
                ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('dls_dlsblog/tag');
    }

}
