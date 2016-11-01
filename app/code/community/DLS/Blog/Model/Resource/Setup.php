<?php

class DLS_Blog_Model_Resource_Setup extends Mage_Catalog_Model_Resource_Setup {

    public function getDefaultEntities() {
        $entities = array();
        $entities['dls_blog_post'] = array(
            'entity_model' => 'dls_blog/post',
            'attribute_model' => 'dls_blog/resource_eav_attribute',
            'table' => 'dls_blog/post',
            'additional_attribute_table' => 'dls_blog/eav_attribute',
            'entity_attribute_collection' => 'dls_blog/post_attribute_collection',
            'attributes' => array(
                'title' => array(
                    'group' => 'General',
                    'type' => 'varchar',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Title',
                    'input' => 'text',
                    'source' => '',
                    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                    'required' => '1',
                    'user_defined' => false,
                    'default' => '',
                    'unique' => false,
                    'position' => '10',
                    'note' => '',
                    'visible' => '1',
                    'wysiwyg_enabled' => '0',
                ),
                'main_content' => array(
                    'group' => 'General',
                    'type' => 'text',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Main content',
                    'input' => 'textarea',
                    'source' => '',
                    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                    'required' => '1',
                    'user_defined' => true,
                    'default' => '',
                    'unique' => false,
                    'position' => '20',
                    'note' => '',
                    'visible' => '1',
                    'wysiwyg_enabled' => '1',
                ),
                'short_content' => array(
                    'group' => 'General',
                    'type' => 'text',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Short content',
                    'input' => 'textarea',
                    'source' => '',
                    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                    'required' => '1',
                    'user_defined' => true,
                    'default' => '',
                    'unique' => false,
                    'position' => '30',
                    'note' => '',
                    'visible' => '1',
                    'wysiwyg_enabled' => '1',
                ),
                'publish_date' => array(
                    'group' => 'General',
                    'type' => 'datetime',
                    'backend' => 'eav/entity_attribute_backend_datetime',
                    'frontend' => '',
                    'label' => 'Publish date',
                    'input' => 'date',
                    'source' => '',
                    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                    'required' => '1',
                    'user_defined' => true,
                    'default' => '',
                    'unique' => false,
                    'position' => '40',
                    'note' => '',
                    'visible' => '1',
                    'wysiwyg_enabled' => '0',
                ),
                'publish_status' => array(
                    'group' => 'General',
                    'type' => 'int',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Publishing status',
                    'input' => 'select',
                    'source' => 'eav/entity_attribute_source_table',
                    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                    'required' => '1',
                    'user_defined' => true,
                    'default' => '',
                    'unique' => false,
                    'position' => '50',
                    'note' => '',
                    'visible' => '1',
                    'wysiwyg_enabled' => '0',
                    'option' =>
                    array(
                        'values' =>
                        array(
                            'draft',
                            'pending',
                            'approved',
                            'ignored',
                        ),
                    ),
                ),
                'small_image' => array(
                    'group' => 'General',
                    'type' => 'varchar',
                    'backend' => 'dls_blog/post_attribute_backend_image',
                    'frontend' => '',
                    'label' => 'Small Image',
                    'input' => 'image',
                    'source' => '',
                    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                    'required' => '',
                    'user_defined' => true,
                    'default' => '',
                    'unique' => false,
                    'position' => '60',
                    'note' => '',
                    'visible' => '1',
                    'wysiwyg_enabled' => '0',
                ),
                'large_image' => array(
                    'group' => 'General',
                    'type' => 'varchar',
                    'backend' => 'dls_blog/post_attribute_backend_image',
                    'frontend' => '',
                    'label' => 'Large Image',
                    'input' => 'image',
                    'source' => '',
                    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                    'required' => '',
                    'user_defined' => true,
                    'default' => '',
                    'unique' => false,
                    'position' => '70',
                    'note' => '',
                    'visible' => '1',
                    'wysiwyg_enabled' => '0',
                ),
                'blogset_id' => array(
                    'group' => 'General',
                    'type' => 'int',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Blog',
                    'input' => 'select',
                    'source' => 'dls_blog/blogset_source',
                    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
                    'required' => '',
                    'user_defined' => true,
                    'default' => '',
                    'unique' => false,
                    'position' => '80',
                    'note' => '',
                    'visible' => '1',
                    'wysiwyg_enabled' => '0',
                ),
                'layoutdesign_id' => array(
                    'group' => 'General',
                    'type' => 'int',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Layout Design',
                    'input' => 'select',
                    'source' => 'dls_blog/layoutdesign_source',
                    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
                    'required' => '',
                    'user_defined' => true,
                    'default' => '',
                    'unique' => false,
                    'position' => '90',
                    'note' => '',
                    'visible' => '1',
                    'wysiwyg_enabled' => '0',
                ),
                'status' => array(
                    'group' => 'General',
                    'type' => 'int',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Enabled',
                    'input' => 'select',
                    'source' => 'eav/entity_attribute_source_boolean',
                    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                    'required' => '',
                    'user_defined' => false,
                    'default' => '1',
                    'unique' => false,
                    'position' => '100',
                    'note' => '',
                    'visible' => '1',
                    'wysiwyg_enabled' => '0',
                ),
                'url_key' => array(
                    'group' => 'General',
                    'type' => 'varchar',
                    'backend' => 'dls_blog/post_attribute_backend_urlkey',
                    'frontend' => '',
                    'label' => 'URL key',
                    'input' => 'text',
                    'source' => '',
                    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                    'required' => '',
                    'user_defined' => false,
                    'default' => '',
                    'unique' => false,
                    'position' => '110',
                    'note' => '',
                    'visible' => '1',
                    'wysiwyg_enabled' => '0',
                ),
                'meta_title' => array(
                    'group' => 'General',
                    'type' => 'varchar',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Meta title',
                    'input' => 'text',
                    'source' => '',
                    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                    'required' => '',
                    'user_defined' => false,
                    'default' => '',
                    'unique' => false,
                    'position' => '120',
                    'note' => '',
                    'visible' => '1',
                    'wysiwyg_enabled' => '0',
                ),
                'meta_keywords' => array(
                    'group' => 'General',
                    'type' => 'text',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Meta keywords',
                    'input' => 'textarea',
                    'source' => '',
                    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                    'required' => '',
                    'user_defined' => false,
                    'default' => '',
                    'unique' => false,
                    'position' => '130',
                    'note' => '',
                    'visible' => '1',
                    'wysiwyg_enabled' => '0',
                ),
                'meta_description' => array(
                    'group' => 'General',
                    'type' => 'text',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Meta description',
                    'input' => 'textarea',
                    'source' => '',
                    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                    'required' => '',
                    'user_defined' => false,
                    'default' => '',
                    'unique' => false,
                    'position' => '140',
                    'note' => '',
                    'visible' => '1',
                    'wysiwyg_enabled' => '0',
                ),
                'allow_comment' => array(
                    'group' => 'General',
                    'type' => 'int',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'Allow Comment',
                    'input' => 'select',
                    'source' => 'dls_blog/adminhtml_source_yesnodefault',
                    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                    'required' => '',
                    'user_defined' => false,
                    'default' => '2',
                    'unique' => false,
                    'position' => '150',
                    'note' => '',
                    'visible' => '1',
                    'wysiwyg_enabled' => '0',
                ),
            )
        );
        return $entities;
    }

}
