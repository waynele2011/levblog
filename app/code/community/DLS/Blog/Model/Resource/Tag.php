<?php

class DLS_Blog_Model_Resource_Tag extends Mage_Core_Model_Resource_Db_Abstract {

    public function _construct() {
        $this->_init('dls_blog/tag', 'entity_id');
    }

}
