<?php

/**
 * Post admin grid block
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Adminhtml_Post_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('postGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Adminhtml_Post_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('dls_dlsblog/post')
            ->getCollection()
            ->addAttributeToSelect('blogset_id')
            ->addAttributeToSelect('layoutdesign_id')
            ->addAttributeToSelect('publish_status')
            ->addAttributeToSelect('publish_date')
            ->addAttributeToSelect('status')
            ->addAttributeToSelect('url_key');
        
        $adminStore = Mage_Core_Model_App::ADMIN_STORE_ID;
        $store = $this->_getStore();
        $collection->joinAttribute(
            'title', 
            'dls_dlsblog_post/title', 
            'entity_id', 
            null, 
            'inner', 
            $adminStore
        );
        if ($store->getId()) {
            $collection->joinAttribute(
                'dls_dlsblog_post_title', 
                'dls_dlsblog_post/title', 
                'entity_id', 
                null, 
                'inner', 
                $store->getId()
            );
        }

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Adminhtml_Post_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('dls_dlsblog')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'blogset_id',
            array(
                'header'    => Mage::helper('dls_dlsblog')->__('Blog'),
                'index'     => 'blogset_id',
                'type'      => 'options',
                'options'   => Mage::getResourceModel('dls_dlsblog/blogset_collection')
                    ->toOptionHash(),
                'renderer'  => 'dls_dlsblog/adminhtml_helper_column_renderer_parent',
                'params'    => array(
                    'id'    => 'getBlogsetId'
                ),
                'base_link' => 'adminhtml/dlsblog_blogset/edit'
            )
        );
        $this->addColumn(
            'layoutdesign_id',
            array(
                'header'    => Mage::helper('dls_dlsblog')->__('Layout Design'),
                'index'     => 'layoutdesign_id',
                'type'      => 'options',
                'options'   => Mage::getResourceModel('dls_dlsblog/layoutdesign_collection')
                    ->toOptionHash(),
                'renderer'  => 'dls_dlsblog/adminhtml_helper_column_renderer_parent',
                'params'    => array(
                    'id'    => 'getLayoutdesignId'
                ),
                'base_link' => 'adminhtml/dlsblog_layoutdesign/edit'
            )
        );
        $this->addColumn(
            'title',
            array(
                'header'    => Mage::helper('dls_dlsblog')->__('Title'),
                'align'     => 'left',
                'index'     => 'title',
            )
        );
        
        if ($this->_getStore()->getId()) {
            $this->addColumn(
                'dls_dlsblog_post_title', 
                array(
                    'header'    => Mage::helper('dls_dlsblog')->__('Title in %s', $this->_getStore()->getName()),
                    'align'     => 'left',
                    'index'     => 'dls_dlsblog_post_title',
                )
            );
        }

        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('dls_dlsblog')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('dls_dlsblog')->__('Enabled'),
                    '0' => Mage::helper('dls_dlsblog')->__('Disabled'),
                )
            )
        );
        $this->addColumn(
            'publish_status',
            array(
                'header' => Mage::helper('dls_dlsblog')->__('Publishing Status'),
                'index'  => 'publish_status',
                'type'  => 'options',
                'options' => Mage::helper('dls_dlsblog')->convertOptions(
                    Mage::getModel('eav/config')->getAttribute('dls_dlsblog_post', 'publish_status')->getSource()->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'publish_date',
            array(
                'header' => Mage::helper('dls_dlsblog')->__('Publish Date'),
                'index'  => 'publish_date',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'url_key',
            array(
                'header' => Mage::helper('dls_dlsblog')->__('URL Key'),
                'index'  => 'url_key',
            )
        );
        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('dls_dlsblog')->__('Created At'),
                'index'  => 'created_at',
                'width'  => '120px',
                'type'   => 'datetime',
            )
        );
        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('dls_dlsblog')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('dls_dlsblog')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('dls_dlsblog')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('dls_dlsblog')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('dls_dlsblog')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * get the selected store
     *
     * @access protected
     * @return Mage_Core_Model_Store
     * @author Ultimate Module Creator
     */
    protected function _getStore()
    {
        $storeId = (int) $this->getRequest()->getParam('store', 0);
        return Mage::app()->getStore($storeId);
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Adminhtml_Post_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('post');
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'=> Mage::helper('dls_dlsblog')->__('Delete'),
                'url'  => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('dls_dlsblog')->__('Are you sure?')
            )
        );
        $this->getMassactionBlock()->addItem(
            'status',
            array(
                'label'      => Mage::helper('dls_dlsblog')->__('Change Status'),
                'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                    'status' => array(
                        'name'   => 'status',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('dls_dlsblog')->__('Status'),
                        'values' => array(
                            '1' => Mage::helper('dls_dlsblog')->__('Enabled'),
                            '0' => Mage::helper('dls_dlsblog')->__('Disabled'),
                        )
                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'publish_status',
            array(
                'label'      => Mage::helper('dls_dlsblog')->__('Change Publishing Status'),
                'url'        => $this->getUrl('*/*/massPublishStatus', array('_current'=>true)),
                'additional' => array(
                    'flag_publish_status' => array(
                        'name'   => 'flag_publish_status',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('dls_dlsblog')->__('Publishing Status'),
                        'values' => Mage::getModel('eav/config')->getAttribute('dls_dlsblog_post', 'publish_status')
                            ->getSource()->getAllOptions(true),

                    )
                )
            )
        );
        $values = Mage::getResourceModel('dls_dlsblog/blogset_collection')->toOptionHash();
        $values = array_reverse($values, true);
        $values[''] = '';
        $values = array_reverse($values, true);
        $this->getMassactionBlock()->addItem(
            'blogset_id',
            array(
                'label'      => Mage::helper('dls_dlsblog')->__('Change Blog'),
                'url'        => $this->getUrl('*/*/massBlogsetId', array('_current'=>true)),
                'additional' => array(
                    'flag_blogset_id' => array(
                        'name'   => 'flag_blogset_id',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('dls_dlsblog')->__('Blog'),
                        'values' => $values
                    )
                )
            )
        );
        $values = Mage::getResourceModel('dls_dlsblog/layoutdesign_collection')->toOptionHash();
        $values = array_reverse($values, true);
        $values[''] = '';
        $values = array_reverse($values, true);
        $this->getMassactionBlock()->addItem(
            'layoutdesign_id',
            array(
                'label'      => Mage::helper('dls_dlsblog')->__('Change Layout Design'),
                'url'        => $this->getUrl('*/*/massLayoutdesignId', array('_current'=>true)),
                'additional' => array(
                    'flag_layoutdesign_id' => array(
                        'name'   => 'flag_layoutdesign_id',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('dls_dlsblog')->__('Layout Design'),
                        'values' => $values
                    )
                )
            )
        );
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param DLS_DLSBlog_Model_Post
     * @return string
     * @author Ultimate Module Creator
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * get the grid url
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
}
