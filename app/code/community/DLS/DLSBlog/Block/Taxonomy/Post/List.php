<?php

/**
 * Taxonomy Posts list block
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Taxonomy_Post_List extends DLS_DLSBlog_Block_Post_List
{
    /**
     * initialize
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $taxonomy = $this->getTaxonomy();
         if ($taxonomy) {
             $collection = $this->getPosts()->addTaxonomyFilter($taxonomy->getId());
             $this->setCollection($collection);
         }
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Taxonomy_Post_List
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        $pager = $this->getLayout()->createBlock('page/html_pager', 'list.pager');
        $pager->setAvailableLimit(array(5 => 5, 10 => 10, 20 => 20, 'all' => 'all'));
        $pager->setCollection($this->getCollection());
        $this->setChild('pager', $pager);
        $this->getCollection()->load();
        return $this;
    }

    /**
     * get the current taxonomy
     *
     * @access public
     * @return DLS_DLSBlog_Model_Taxonomy
     * @author Ultimate Module Creator
     */
    public function getTaxonomy()
    {
        return Mage::registry('current_taxonomy');
    }
    
    public function getPagerHtml() {
        return $this->getChildHtml('pager');
    }
}
