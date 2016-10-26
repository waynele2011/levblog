<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

$installer->removeAttribute('dls_dlsblog_post', 'post_tag');
$installer->addAttribute(DLS_DLSBlog_Model_Post::ENTITY, 'post_tag', array(
    'group'             => 'General',
    'type'              => 'text',
    'backend'           => '',
    'frontend'          => '',
    'input_renderer'    => 'dls_dlsblog/adminhtml_helper_form_tag',//definition of renderer
    'label'             => 'Tag',
    'class'             => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'unique'            => false,
    'position' => '40'
));

$installer->endSetup();