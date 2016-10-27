<?php

/**
 * Taxonomy collection resource model
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Model_Resource_Taxonomy_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected $_joinedFields = array();

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    protected function _construct()
    {
        parent::_construct();
        $this->_init('dls_dlsblog/taxonomy');
    }

    /**
     * Add Id filter
     *
     * @access public
     * @param array $taxonomyIds
     * @return DLS_DLSBlog_Model_Resource_Taxonomy_Collection
     * @author Ultimate Module Creator
     */
    public function addIdFilter($taxonomyIds)
    {
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

    /**
     * Add taxonomy path filter
     *
     * @access public
     * @param string $regexp
     * @return DLS_DLSBlog_Model_Resource_Taxonomy_Collection
     * @author Ultimate Module Creator
     */
    public function addPathFilter($regexp)
    {
        $this->addFieldToFilter('path', array('regexp' => $regexp));
        return $this;
    }

    /**
     * Add taxonomy path filter
     *
     * @access public
     * @param array|string $paths
     * @return DLS_DLSBlog_Model_Resource_Taxonomy_Collection
     * @author Ultimate Module Creator
     */
    public function addPathsFilter($paths)
    {
        if (!is_array($paths)) {
            $paths = array($paths);
        }
        $write  = $this->getResource()->getWriteConnection();
        $cond   = array();
        foreach ($paths as $path) {
            $cond[] = $write->quoteInto('e.path LIKE ?', "$path%");
        }
        if ($cond) {
            $this->getSelect()->where(join(' OR ', $cond));
        }
        return $this;
    }

    /**
     * Add taxonomy level filter
     *
     * @access public
     * @param int|string $level
     * @return DLS_DLSBlog_Model_Resource_Taxonomy_Collection
     * @author Ultimate Module Creator
     */
    public function addLevelFilter($level)
    {
        $this->addFieldToFilter('level', array('lteq' => $level));
        return $this;
    }

    /**
     * Add root taxonomy filter
     *
     * @access public
     * @return DLS_DLSBlog_Model_Resource_Taxonomy_Collection
     */
    public function addRootLevelFilter()
    {
        $this->addFieldToFilter('path', array('neq' => '1'));
        $this->addLevelFilter(1);
        return $this;
    }

    /**
     * Add order field
     *
     * @access public
     * @param string $field
     * @return DLS_DLSBlog_Model_Resource_Taxonomy_Collection
     */
    public function addOrderField($field)
    {
        $this->setOrder($field, self::SORT_ORDER_ASC);
        return $this;
    }

    /**
     * Add active taxonomy filter
     *
     * @access public
     * @return DLS_DLSBlog_Model_Resource_Taxonomy_Collection
     */
    public function addStatusFilter($status = 1)
    {
        $this->addFieldToFilter('status', $status);
        return $this;
    }

    /**
     * get taxonomies as array
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @param array $additional
     * @return array
     * @author Ultimate Module Creator
     */
    protected function _toOptionArray($valueField='entity_id', $labelField='name', $additional=array())
    {
        $res = array();
        $additional['value'] = $valueField;
        $additional['label'] = $labelField;

        foreach ($this as $item) {
            if ($item->getId() == Mage::helper('dls_dlsblog/taxonomy')->getRootTaxonomyId()) {
                continue;
            }
            foreach ($additional as $code => $field) {
                $data[$code] = $item->getData($field);
            }
            $res[] = $data;
        }
        return $res;
    }

    /**
     * get options hash
     *
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @return array
     * @author Ultimate Module Creator
     */
    protected function _toOptionHash($valueField='entity_id', $labelField='name')
    {
        $res = array();
        foreach ($this as $item) {
            if ($item->getId() == Mage::helper('dls_dlsblog/taxonomy')->getRootTaxonomyId()) {
                continue;
            }
            $res[$item->getData($valueField)] = $item->getData($labelField);
        }
        return $res;
    }

    /**
     * add the blogset filter to collection
     *
     * @access public
     * @param mixed (DLS_DLSBlog_Model_Blogset|int) $blogset
     * @return DLS_DLSBlog_Model_Resource_Taxonomy_Collection
     * @author Ultimate Module Creator
     */
    public function addBlogsetFilter($blogset)
    {
        if ($blogset instanceof DLS_DLSBlog_Model_Blogset) {
            $blogset = $blogset->getId();
        }
        if (!isset($this->_joinedFields['blogset'])) {
            $this->getSelect()->join(
                array('related_blogset' => $this->getTable('dls_dlsblog/taxonomy_blogset')),
                'related_blogset.taxonomy_id = main_table.entity_id',
                array('position')
            );
            $this->getSelect()->where('related_blogset.blogset_id = ?', $blogset);
            $this->_joinedFields['blogset'] = true;
        }
        return $this;
    }

    /**
     * add the filter filter to collection
     *
     * @access public
     * @param mixed (DLS_DLSBlog_Model_Filter|int) $filter
     * @return DLS_DLSBlog_Model_Resource_Taxonomy_Collection
     * @author Ultimate Module Creator
     */
    public function addFilterFilter($filter)
    {
        if ($filter instanceof DLS_DLSBlog_Model_Filter) {
            $filter = $filter->getId();
        }
        if (!isset($this->_joinedFields['filter'])) {
            $this->getSelect()->join(
                array('related_filter' => $this->getTable('dls_dlsblog/taxonomy_filter')),
                'related_filter.taxonomy_id = main_table.entity_id',
                array('position')
            );
            $this->getSelect()->where('related_filter.filter_id = ?', $filter);
            $this->_joinedFields['filter'] = true;
        }
        return $this;
    }

    /**
     * add the post filter to collection
     *
     * @access public
     * @param mixed (DLS_DLSBlog_Model_Post|int) $post
     * @return DLS_DLSBlog_Model_Resource_Taxonomy_Collection
     * @author Ultimate Module Creator
     */
    public function addPostFilter($post)
    {
        if ($post instanceof DLS_DLSBlog_Model_Post) {
            $post = $post->getId();
        }
        if (!isset($this->_joinedFields['post'])) {
            $this->getSelect()->join(
                array('related_post' => $this->getTable('dls_dlsblog/taxonomy_post')),
                'related_post.taxonomy_id = main_table.entity_id',
                array('position')
            );
            $this->getSelect()->where('related_post.post_id = ?', $post);
            $this->_joinedFields['post'] = true;
        }
        return $this;
    }

    /**
     * Get SQL for get record count.
     * Extra GROUP BY strip added.
     *
     * @access public
     * @return Varien_Db_Select
     * @author Ultimate Module Creator
     */
    public function getSelectCountSql()
    {
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(Zend_Db_Select::GROUP);
        return $countSelect;
    }
}
