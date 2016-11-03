<?php

class DLS_Blog_Helper_Data extends Mage_Core_Helper_Abstract {

    const XML_PATH_CONFIRM_EMAIL_ENABLED = 'dls_blog/confirmed_comment/enabled';
    const XML_PATH_EMAIL_TEMPLATE = 'dls_blog/confirmed_comment/template';
    const XML_PATH_CONFIRM_EMAILS = 'dls_blog/confirmed_comment/emails';
    const XML_PATH_SCHEDULE_ENABLED = 'dls_blog/confirmed_comment/enabled_schedule';
    const EVENT_TYPE = 'new_comment';
    const ENTITY = 'comment';

    public function convertOptions($options) {
        $converted = array();
        foreach ($options as $option) {
            if (isset($option['value']) && !is_array($option['value']) &&
                    isset($option['label']) && !is_array($option['label'])) {
                $converted[$option['value']] = $option['label'];
            }
        }
        return $converted;
    }

    public function canSendConfirmedCommentEmail($storeId = null) {
        return Mage::getStoreConfig(self::XML_PATH_CONFIRM_EMAIL_ENABLED);
    }

    public function getConfirmedEmails($storeId = null) {
        return explode(",", Mage::getStoreConfig(self::XML_PATH_CONFIRM_EMAILS, $storeId));
    }

    public function canSendBySchedule($storeId = null) {
        return Mage::getStoreConfig(self::XML_PATH_SCHEDULE_ENABLED, $storeId);
    }

    public function sendConfirmedCommentEmail($comment) {

        if (!$this->canSendConfirmedCommentEmail()) {
            return $this;
        }

        $templateId = Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE);

        $mailer = Mage::getModel('core/email_template_mailer');
        $emailInfo = Mage::getModel('core/email_info');

        $confirmedEmails = $this->getConfirmedEmails();

        if (count($confirmedEmails) == 0) {
            return false;
        }

        foreach ($confirmedEmails as $email) {
            $emailInfo->addTo($email, 'General');
        }

        $mailer->addEmailInfo($emailInfo);

        $post = Mage::getModel('dls_blog/post')->load($comment->getPostId());
        $mailer->setSender(Mage::getStoreConfig(Mage_Sales_Model_Order::XML_PATH_EMAIL_IDENTITY));
        $mailer->setTemplateId($templateId);
        $mailer->setTemplateParams(array(
            'remote_ip' => $this,
            'comment' => $comment->getComment(),
            'customer_name' => $comment->getName(),
            'customer_email' => $comment->getEmail(),
            'created_at' => $comment->getCreatedAt(),
            'post_url' => $post->getPostUrl(),
            'post_title' => $post->getTitle(),
        ));

        $emailQueue = Mage::getModel('core/email_queue');
        $emailQueue->setEntityId($comment->getCommentId())
                ->setEntityType(self::ENTITY)
                ->setEventType(self::EVENT_TYPE)
                ->setIsForceCheck(!$forceMode);

        $mailer->setQueue($emailQueue)->send();

        $comment->setNotified(1);
        $comment->save();
        return true;
    }

}
