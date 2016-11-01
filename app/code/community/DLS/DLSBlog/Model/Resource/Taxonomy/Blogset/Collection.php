<?php

/**
 * Taxonomy - Blog relation resource model collection
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Model_Resource_Taxonomy_Blogset_Collection extends DLS_DLSBlog_Model_Resource_Blogset_Collection
{
    /**
     * remember if fields have been joined
     * @var bool
     */
    protected $_joinedFields = false;

    /**
     * join the link table
     *
     * @access public
     * @return DLS_DLSBlog_Model_Resource_Taxonomy_Blogset_Collection
     * @author Ultimate Module Creator
     */
    public function joinFields()
    {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                array('related' => $this->getTable('dls_dlsblog/taxonomy_blogset')),
                'related.blogset_id = main_table.entity_id',
                array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    /**
     * add taxonomy filter
     *
     * @access public
     * @param DLS_DLSBlog_Model_Taxonomy | int $taxonomy
     * @return DLS_DLSBlog_Model_Resource_Taxonomy_Blogset_Collection
     * @author Ultimate Module Creator
     */
    public function addTaxonomyFilter($taxonomy)
    {
        if ($taxonomy instanceof DLS_DLSBlog_Model_Taxonomy) {
            $taxonomy = $taxonomy->getId();
        }
        if (!$this->_joinedFields) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.taxonomy_id = ?', $taxonomy);
        return $this;
    }
}
