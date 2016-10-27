<?php

/**
 * Post attribute collection model
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Model_Resource_Post_Attribute_Collection extends Mage_Eav_Model_Resource_Entity_Attribute_Collection
{
    /**
     * init attribute select
     *
     * @access protected
     * @return DLS_DLSBlog_Model_Resource_Post_Attribute_Collection
     * @author Ultimate Module Creator
     */
    protected function _initSelect()
    {
        $this->getSelect()->from(array('main_table' => $this->getResource()->getMainTable()))
            ->where(
                'main_table.entity_type_id=?',
                Mage::getModel('eav/entity')->setType('dls_dlsblog_post')->getTypeId()
            )
            ->join(
                array('additional_table' => $this->getTable('dls_dlsblog/eav_attribute')),
                'additional_table.attribute_id=main_table.attribute_id'
            );
        return $this;
    }

    /**
     * set entity type filter
     *
     * @access public
     * @param string $typeId
     * @return DLS_DLSBlog_Model_Resource_Post_Attribute_Collection
     * @author Ultimate Module Creator
     */
    public function setEntityTypeFilter($typeId)
    {
        return $this;
    }

    /**
     * Specify filter by "is_visible" field
     *
     * @access public
     * @return DLS_DLSBlog_Model_Resource_Post_Attribute_Collection
     * @author Ultimate Module Creator
     */
    public function addVisibleFilter()
    {
        return $this->addFieldToFilter('additional_table.is_visible', 1);
    }

    /**
     * Specify filter by "is_editable" field
     *
     * @access public
     * @return DLS_DLSBlog_Model_Resource_Post_Attribute_Collection
     * @author Ultimate Module Creator
     */
    public function addEditableFilter()
    {
        return $this->addFieldToFilter('additional_table.is_editable', 1);
    }
}
