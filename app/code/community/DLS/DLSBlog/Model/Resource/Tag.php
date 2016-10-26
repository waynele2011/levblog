<?php

class DLS_DLSBlog_Model_Resource_Tag extends Mage_Core_Model_Resource_Db_Abstract {

    public function _construct() {
        $this->_init('dls_dlsblog/tag', 'entity_id');
    }

}
