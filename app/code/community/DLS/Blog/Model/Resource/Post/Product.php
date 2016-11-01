<?php

class DLS_Blog_Model_Resource_Post_Product extends Mage_Core_Model_Resource_Db_Abstract {

    protected function _construct() {
        $this->_init('dls_blog/post_product', 'rel_id');
    }

    public function savePostRelation($post, $data) {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('post_id=?', $post->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $productId => $info) {
            $this->_getWriteAdapter()->insert(
                    $this->getMainTable(), array(
                'post_id' => $post->getId(),
                'product_id' => $productId,
                'position' => @$info['position']
                    )
            );
        }
        return $this;
    }

    public function saveProductRelation($product, $data) {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('product_id=?', $product->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $postId => $info) {
            $this->_getWriteAdapter()->insert(
                    $this->getMainTable(), array(
                'post_id' => $postId,
                'product_id' => $product->getId(),
                'position' => @$info['position']
                    )
            );
        }
        return $this;
    }

}
