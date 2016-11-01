<?php

/**
 * Layout design admin grid block
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
class DLS_DLSBlog_Block_Adminhtml_Layoutdesign_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('layoutdesignGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Adminhtml_Layoutdesign_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('dls_dlsblog/layoutdesign')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return DLS_DLSBlog_Block_Adminhtml_Layoutdesign_Grid
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
            'basic_layout',
            array(
                'header' => Mage::helper('dls_dlsblog')->__('Basic Layout'),
                'index'  => 'basic_layout',
                'type'  => 'options',
                'options' => Mage::helper('dls_dlsblog')->convertOptions(
                    Mage::getModel('dls_dlsblog/layoutdesign_attribute_source_basiclayout')->getAllOptions(false)
                )

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
     * @return DLS_DLSBlog_Block_Adminhtml_Layoutdesign_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('layoutdesign');
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
            'basic_layout',
            array(
                'label'      => Mage::helper('dls_dlsblog')->__('Change Basic Layout'),
                'url'        => $this->getUrl('*/*/massBasicLayout', array('_current'=>true)),
                'additional' => array(
                    'flag_basic_layout' => array(
                        'name'   => 'flag_basic_layout',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('dls_dlsblog')->__('Basic Layout'),
                        'values' => Mage::getModel('dls_dlsblog/layoutdesign_attribute_source_basiclayout')
                            ->getAllOptions(true),

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
     * @param DLS_DLSBlog_Model_Layoutdesign
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
     * @return DLS_DLSBlog_Block_Adminhtml_Layoutdesign_Grid
     * @author Ultimate Module Creator
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
