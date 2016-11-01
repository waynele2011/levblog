<?php

class DLS_Blog_Model_Resource_Taxonomy_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {

    protected $_joinedFields = array();

    protected function _construct() {
        parent::_construct();
        $this->_init('dls_blog/taxonomy');
    }

    public function addIdFilter($taxonomyIds) {
        if (is_array($taxonomyIds)) {
            if (empty($taxonomyIds)) {
                $condition = '';
            } else {
                $condition = array('in' => $taxonomyIds);
            }
        } elseif (is_numeric($taxonomyIds)) {
            $condition = $taxonomyIds;
        } elseif (is_string($taxonomyIds)) {
            $ids = explode(',', $taxonomyIds);
            if (empty($ids)) {
                $condition = $taxonomyIds;
            } else {
                $condition = array('in' => $ids);
            }
        }
        $this->addFieldToFilter('entity_id', $condition);
        return $this;
    }

    public function addPathFilter($regexp) {
        $this->addFieldToFilter('path', array('regexp' => $regexp));
        return $this;
    }

    public function addPathsFilter($paths) {
        if (!is_array($paths)) {
            $paths = array($paths);
        }
        $write = $this->getResource()->getWriteConnection();
        $cond = array();
        foreach ($paths as $path) {
            $cond[] = $write->quoteInto('e.path LIKE ?', "$path%");
        }
        if ($cond) {
            $this->getSelect()->where(join(' OR ', $cond));
        }
        return $this;
    }

    public function addLevelFilter($level) {
        $this->addFieldToFilter('level', array('lteq' => $level));
        return $this;
    }

    public function addRootLevelFilter() {
        $this->addFieldToFilter('path', array('neq' => '1'));
        $this->addLevelFilter(1);
        return $this;
    }

    public function addOrderField($field) {
        $this->setOrder($field, self::SORT_ORDER_ASC);
        return $this;
    }

    public function addStatusFilter($status = 1) {
        $this->addFieldToFilter('status', $status);
        return $this;
    }

    protected function _toOptionArray($valueField = 'entity_id', $labelField = 'name', $additional = array()) {
        $res = array();
        $additional['value'] = $valueField;
        $additional['label'] = $labelField;

        foreach ($this as $item) {
            if ($item->getId() == Mage::helper('dls_blog/taxonomy')->getRootTaxonomyId()) {
                continue;
            }
            foreach ($additional as $code => $field) {
                $data[$code] = $item->getData($field);
            }
            $res[] = $data;
        }
        return $res;
    }

    protected function _toOptionHash($valueField = 'entity_id', $labelField = 'name') {
        $res = array();
        foreach ($this as $item) {
            if ($item->getId() == Mage::helper('dls_blog/taxonomy')->getRootTaxonomyId()) {
                continue;
            }
            $res[$item->getData($valueField)] = $item->getData($labelField);
        }
        return $res;
    }

    public function addBlogsetFilter($blogset) {
        if ($blogset instanceof DLS_Blog_Model_Blogset) {
            $blogset = $blogset->getId();
        }
        if (!isset($this->_joinedFields['blogset'])) {
            $this->getSelect()->join(
                    array('related_blogset' => $this->getTable('dls_blog/taxonomy_blogset')), 'related_blogset.taxonomy_id = main_table.entity_id', array('position')
            );
            $this->getSelect()->where('related_blogset.blogset_id = ?', $blogset);
            $this->_joinedFields['blogset'] = true;
        }
        return $this;
    }

    public function addFilterFilter($filter) {
        if ($filter instanceof DLS_Blog_Model_Filter) {
            $filter = $filter->getId();
        }
        if (!isset($this->_joinedFields['filter'])) {
            $this->getSelect()->join(
                    array('related_filter' => $this->getTable('dls_blog/taxonomy_filter')), 'related_filter.taxonomy_id = main_table.entity_id', array('position')
            );
            $this->getSelect()->where('related_filter.filter_id = ?', $filter);
            $this->_joinedFields['filter'] = true;
        }
        return $this;
    }

    public function addPostFilter($post) {
        if ($post instanceof DLS_Blog_Model_Post) {
            $post = $post->getId();
        }
        if (!isset($this->_joinedFields['post'])) {
            $this->getSelect()->join(
                    array('related_post' => $this->getTable('dls_blog/taxonomy_post')), 'related_post.taxonomy_id = main_table.entity_id', array('position')
            );
            $this->getSelect()->where('related_post.post_id = ?', $post);
            $this->_joinedFields['post'] = true;
        }
        return $this;
    }

    public function getSelectCountSql() {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(Zend_Db_Select::GROUP);
        return $countSelect;
    }

}
