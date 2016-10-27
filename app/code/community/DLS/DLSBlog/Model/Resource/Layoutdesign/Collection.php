<?php

/**
 * Layout design collection resource model
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Model_Resource_Layoutdesign_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
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
        $this->_init('dls_dlsblog/layoutdesign');
    }

    /**
     * Add Id filter
     *
     * @access public
     * @param array $layoutdesignIds
     * @return DLS_DLSBlog_Model_Resource_Layoutdesign_Collection
     * @author Ultimate Module Creator
     */
    public function addIdFilter($layoutdesignIds)
    {
        if (is_array($layoutdesignIds)) {
            if (empty($layoutdesignIds)) {
                $condition = '';
            } else {
                $condition = array('in' => $layoutdesignIds);
            }
        } elseif (is_numeric($layoutdesignIds)) {
            $condition = $layoutdesignIds;
        } elseif (is_string($layoutdesignIds)) {
            $ids = explode(',', $layoutdesignIds);
            if (empty($ids)) {
                $condition = $layoutdesignIds;
            } else {
                $condition = array('in' => $ids);
            }
        }
        $this->addFieldToFilter('entity_id', $condition);
        return $this;
    }

    /**
     * Add layout design path filter
     *
     * @access public
     * @param string $regexp
     * @return DLS_DLSBlog_Model_Resource_Layoutdesign_Collection
     * @author Ultimate Module Creator
     */
    public function addPathFilter($regexp)
    {
        $this->addFieldToFilter('path', array('regexp' => $regexp));
        return $this;
    }

    /**
     * Add layout design path filter
     *
     * @access public
     * @param array|string $paths
     * @return DLS_DLSBlog_Model_Resource_Layoutdesign_Collection
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
     * Add layout design level filter
     *
     * @access public
     * @param int|string $level
     * @return DLS_DLSBlog_Model_Resource_Layoutdesign_Collection
     * @author Ultimate Module Creator
     */
    public function addLevelFilter($level)
    {
        $this->addFieldToFilter('level', array('lteq' => $level));
        return $this;
    }

    /**
     * Add root layout design filter
     *
     * @access public
     * @return DLS_DLSBlog_Model_Resource_Layoutdesign_Collection
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
     * @return DLS_DLSBlog_Model_Resource_Layoutdesign_Collection
     */
    public function addOrderField($field)
    {
        $this->setOrder($field, self::SORT_ORDER_ASC);
        return $this;
    }

    /**
     * Add active layout design filter
     *
     * @access public
     * @return DLS_DLSBlog_Model_Resource_Layoutdesign_Collection
     */
    public function addStatusFilter($status = 1)
    {
        $this->addFieldToFilter('status', $status);
        return $this;
    }

    /**
     * get layout designs as array
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
            if ($item->getId() == Mage::helper('dls_dlsblog/layoutdesign')->getRootLayoutdesignId()) {
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
            if ($item->getId() == Mage::helper('dls_dlsblog/layoutdesign')->getRootLayoutdesignId()) {
                continue;
            }
            $res[$item->getData($valueField)] = $item->getData($labelField);
        }
        return $res;
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
