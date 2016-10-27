<?php

/**
 * Filter admin grid block
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Adminhtml_Filter_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('filterGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Adminhtml_Filter_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('dls_dlsblog/filter')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Adminhtml_Filter_Grid
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
                'header'    => Mage::helper('dls_dlsblog')->__('Blog setting'),
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
                'header'    => Mage::helper('dls_dlsblog')->__('Layout design'),
                'index'     => 'layoutdesign_id',
                'type'      => 'options',
                'options'   => Mage::getResourceModel('dls_dlsblog/layoutdesign_collection')
                    ->toOptionHash(),
                'renderer'  => 'dls_dlsblog/adminhtml_helper_column_renderer_parent',
                'params'    => array(
                    'id'    => 'getLayoutdesignId'
                ),
                'static' => array(
                    'clear' => 1
                ),
                'base_link' => 'adminhtml/dlsblog_layoutdesign/edit'
            )
        );
        $this->addColumn(
            'name',
            array(
                'header'    => Mage::helper('dls_dlsblog')->__('Name'),
                'align'     => 'left',
                'index'     => 'name',
            )
        );
        
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
            'url_key',
            array(
                'header' => Mage::helper('dls_dlsblog')->__('URL key'),
                'index'  => 'url_key',
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
     * prepare mass action
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Adminhtml_Filter_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('filter');
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
                'label'      => Mage::helper('dls_dlsblog')->__('Change status'),
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
            'exposed',
            array(
                'label'      => Mage::helper('dls_dlsblog')->__('Change Exposed filter'),
                'url'        => $this->getUrl('*/*/massExposed', array('_current'=>true)),
                'additional' => array(
                    'flag_exposed' => array(
                        'name'   => 'flag_exposed',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('dls_dlsblog')->__('Exposed filter'),
                        'values' => array(
                                '1' => Mage::helper('dls_dlsblog')->__('Yes'),
                                '0' => Mage::helper('dls_dlsblog')->__('No'),
                            )

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'type',
            array(
                'label'      => Mage::helper('dls_dlsblog')->__('Change Filter type'),
                'url'        => $this->getUrl('*/*/massType', array('_current'=>true)),
                'additional' => array(
                    'flag_type' => array(
                        'name'   => 'flag_type',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('dls_dlsblog')->__('Filter type'),
                        'values' => Mage::getModel('dls_dlsblog/filter_attribute_source_type')
                            ->getAllOptions(true),

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
                'label'      => Mage::helper('dls_dlsblog')->__('Change Blog setting'),
                'url'        => $this->getUrl('*/*/massBlogsetId', array('_current'=>true)),
                'additional' => array(
                    'flag_blogset_id' => array(
                        'name'   => 'flag_blogset_id',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('dls_dlsblog')->__('Blog setting'),
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
                'label'      => Mage::helper('dls_dlsblog')->__('Change Layout design'),
                'url'        => $this->getUrl('*/*/massLayoutdesignId', array('_current'=>true)),
                'additional' => array(
                    'flag_layoutdesign_id' => array(
                        'name'   => 'flag_layoutdesign_id',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('dls_dlsblog')->__('Layout design'),
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
     * @param DLS_DLSBlog_Model_Filter
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

    /**
     * after collection load
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Adminhtml_Filter_Grid
     * @author Ultimate Module Creator
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
