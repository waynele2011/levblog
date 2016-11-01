<?php

class DLS_Blog_Adminhtml_Blog_Post_CommentController extends Mage_Adminhtml_Controller_Action {

    protected function _initComment() {
        $commentId = (int) $this->getRequest()->getParam('id');
        $comment = Mage::getModel('dls_blog/post_comment');
        if ($commentId) {
            $comment->load($commentId);
        }
        Mage::register('current_comment', $comment);
        return $comment;
    }

    public function indexAction() {
        $this->loadLayout();
        $this->_title(Mage::helper('dls_blog')->__('Blog'))
                ->_title(Mage::helper('dls_blog')->__('Posts'))
                ->_title(Mage::helper('dls_blog')->__('Comments'));
        $this->renderLayout();
    }

    public function gridAction() {
        $this->loadLayout()->renderLayout();
    }

    public function editAction() {
        $commentId = $this->getRequest()->getParam('id');
        $comment = $this->_initComment();
        if (!$comment->getId()) {
            $this->_getSession()->addError(
                    Mage::helper('dls_blog')->__('This comment no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $comment->setData($data);
        }
        Mage::register('comment_data', $comment);
        $post = Mage::getModel('dls_blog/post')->load($comment->getPostId());
        Mage::register('current_post', $post);
        $this->loadLayout();
        $this->_title(Mage::helper('dls_blog')->__('Blog'))
                ->_title(Mage::helper('dls_blog')->__('Posts'))
                ->_title(Mage::helper('dls_blog')->__('Comments'))
                ->_title($comment->getTitle());
        $this->renderLayout();
    }

    public function saveAction() {
        if ($data = $this->getRequest()->getPost('comment')) {
            try {
                $comment = $this->_initComment();
                $comment->addData($data);
                if (!$comment->getCustomerId()) {
                    $comment->unsCustomerId();
                }
                $comment->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('dls_blog')->__('Comment was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $comment->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('dls_blog')->__('There was a problem saving the comment.')
                );
                Mage::getSingleton('adminhtml/session')->setFormData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('dls_blog')->__('Unable to find comment to save.')
        );
        $this->_redirect('*/*/');
    }

    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $comment = Mage::getModel('dls_blog/post_comment');
                $comment->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('dls_blog')->__('Comment was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('dls_blog')->__('There was an error deleting the comment.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('dls_blog')->__('Could not find comment to delete.')
        );
        $this->_redirect('*/*/');
    }

    public function massDeleteAction() {
        $commentIds = $this->getRequest()->getParam('comment');
        if (!is_array($commentIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('dls_blog')->__('Please select comments to delete.')
            );
        } else {
            try {
                foreach ($commentIds as $commentId) {
                    $comment = Mage::getModel('dls_blog/post_comment');
                    $comment->setId($commentId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                        Mage::helper('dls_blog')->__(
                                'Total of %d comments were successfully deleted.', count($commentIds)
                        )
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('dls_blog')->__('There was an error deleting comments.')
                );
            }
        }
        $this->_redirect('*/*/index');
    }

    public function massStatusAction() {
        $commentIds = $this->getRequest()->getParam('comment');
        if (!is_array($commentIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('dls_blog')->__('Please select comments.')
            );
        } else {
            try {
                foreach ($commentIds as $commentId) {
                    $comment = Mage::getSingleton('dls_blog/post_comment')->load($commentId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                        $this->__('Total of %d comments were successfully updated.', count($commentIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('dls_blog')->__('There was an error updating comments.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    public function exportCsvAction() {
        $fileName = 'post_comments.csv';
        $content = $this->getLayout()->createBlock(
                        'dls_blog/adminhtml_post_comment_grid'
                )
                ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function exportExcelAction() {
        $fileName = 'post_comments.xls';
        $content = $this->getLayout()->createBlock(
                        'dls_blog/adminhtml_post_comment_grid'
                )
                ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    public function exportXmlAction() {
        $fileName = 'post_comments.xml';
        $content = $this->getLayout()->createBlock(
                        'dls_blog/adminhtml_post_comment_grid'
                )
                ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('dls_blog/post_comments');
    }

}
