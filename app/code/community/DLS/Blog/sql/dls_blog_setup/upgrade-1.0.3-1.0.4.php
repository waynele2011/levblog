<?php

$installer = $this;
$installer->startSetup();

$installer->updateAttribute('dls_blog_post', 'main_content', 'is_wysiwyg_enabled', 0);
$installer->updateAttribute('dls_blog_post', 'short_content', 'is_wysiwyg_enabled', 0);


$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$setup->removeAttribute('dls_blog_post', 'publish_date');
$setup = Mage::getResourceModel('dls_blog/setup','dls_blog_setup');
$setup->addAttribute('dls_blog_post', 'publish_date', array(
    'group' => 'General',
    'input' => 'datetime',
    'type' => 'datetime',
    'time' => true,
    'label' => 'Publish Date',
    'backend' => "eav/entity_attribute_backend_datetime",
    'required' => '1',
    'user_defined' => true,
    'default' => '',
    'unique' => false,
    'position' => '40',
    'note' => '',
    'visible' => '1',
    'wysiwyg_enabled' => '0',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));

$installer->endSetup();
