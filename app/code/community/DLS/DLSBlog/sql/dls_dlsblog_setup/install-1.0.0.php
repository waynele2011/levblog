<?php

/**
 * DLSBlog module install script
 *
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('dls_dlsblog/blogset'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Blog setting ID'
    )
    ->addColumn(
        'layoutdesign_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Layout design ID'
    )
    ->addColumn(
        'name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Name'
    )
    ->addColumn(
        'description',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Blog description'
    )
    ->addColumn(
        'custom_default_filter',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
        ),
        'Default filter'
    )
    ->addColumn(
        'logo',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Logo image'
    )
    ->addColumn(
        'status',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Enabled'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Blog setting Modification Time'
    )
    ->addColumn(
        'url_key',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'URL key'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Blog setting Creation Time'
    ) 
    ->addIndex($this->getIdxName('dls_dlsblog/layoutdesign', array('layoutdesign_id')), array('layoutdesign_id'))
    ->setComment('Blog setting Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('dls_dlsblog/taxonomy'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Taxonomy ID'
    )
    ->addColumn(
        'layoutdesign_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Layout design ID'
    )
    ->addColumn(
        'name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Name'
    )
    ->addColumn(
        'description',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Description'
    )
    ->addColumn(
        'small_image',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Small Image'
    )
    ->addColumn(
        'large_image',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Large image'
    )
    ->addColumn(
        'status',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Enabled'
    )
    ->addColumn(
        'url_key',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'URL key'
    )
    ->addColumn(
        'parent_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Parent id'
    )
    ->addColumn(
        'path',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Path'
    )
    ->addColumn(
        'position',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Position'
    )
    ->addColumn(
        'level',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Level'
    )
    ->addColumn(
        'children_count',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Children count'
    )
    ->addColumn(
        'meta_title',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Meta title'
    )
    ->addColumn(
        'meta_keywords',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Meta keywords'
    )
    ->addColumn(
        'meta_description',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Meta description'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Taxonomy Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Taxonomy Creation Time'
    ) 
    ->addIndex($this->getIdxName('dls_dlsblog/layoutdesign', array('layoutdesign_id')), array('layoutdesign_id'))
    ->setComment('Taxonomy Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('dls_dlsblog/filter'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Filter ID'
    )
    ->addColumn(
        'blogset_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Blog setting ID'
    )
    ->addColumn(
        'layoutdesign_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Layout design ID'
    )
    ->addColumn(
        'name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Name'
    )
    ->addColumn(
        'description',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Description'
    )
    ->addColumn(
        'exposed',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(
            'nullable'  => false,
        ),
        'Exposed filter'
    )
    ->addColumn(
        'type',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
        ),
        'Filter type'
    )
    ->addColumn(
        'condition_code',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Condition'
    )
    ->addColumn(
        'sort_code',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Sorts'
    )
    ->addColumn(
        'paging_code',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Paging'
    )
    ->addColumn(
        'status',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Enabled'
    )
    ->addColumn(
        'url_key',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'URL key'
    )
    ->addColumn(
        'meta_title',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Meta title'
    )
    ->addColumn(
        'meta_keywords',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Meta keywords'
    )
    ->addColumn(
        'meta_description',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Meta description'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Filter Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Filter Creation Time'
    ) 
    ->addIndex($this->getIdxName('dls_dlsblog/blogset', array('blogset_id')), array('blogset_id'))
    ->addIndex($this->getIdxName('dls_dlsblog/layoutdesign', array('layoutdesign_id')), array('layoutdesign_id'))
    ->setComment('Filter Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('dls_dlsblog/layoutdesign'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Layout design ID'
    )
    ->addColumn(
        'name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Name'
    )
    ->addColumn(
        'description',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Description'
    )
    ->addColumn(
        'basic_layout',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
        ),
        'Basic layout'
    )
    ->addColumn(
        'design_code',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Design frame'
    )
    ->addColumn(
        'status',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Enabled'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Layout design Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Layout design Creation Time'
    ) 
    ->setComment('Layout design Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('dls_dlsblog/tag'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Tag ID'
    )
    ->addColumn(
        'blogset_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'unsigned'  => true,
        ),
        'Blog setting ID'
    )
    ->addColumn(
        'name',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Name'
    )
    ->addColumn(
        'slug',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Slug'
    )
    ->addColumn(
        'status',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Enabled'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Tag Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Tag Creation Time'
    ) 
    ->addIndex($this->getIdxName('dls_dlsblog/blogset', array('blogset_id')), array('blogset_id'))
    ->setComment('Tag Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('dls_dlsblog/post'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Entity ID'
    )
    ->addColumn(
        'entity_type_id',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
        ),
        'Entity Type ID'
    )
    ->addColumn(
        'attribute_set_id',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
        ),
        'Attribute Set ID'
    )

    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null, array(),
        'Creation Time'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Update Time'
    )
    ->addIndex(
        $this->getIdxName(
            'dls_dlsblog/post',
            array('entity_type_id')
        ),
        array('entity_type_id')
    )
    ->addIndex(
        $this->getIdxName(
            'dls_dlsblog/post',
            array('attribute_set_id')
        ),
        array('attribute_set_id')
    )
    ->addForeignKey(
        $this->getFkName(
            'dls_dlsblog/post',
            'attribute_set_id',
            'eav/attribute_set',
            'attribute_set_id'
        ),
        'attribute_set_id',
        $this->getTable('eav/attribute_set'),
        'attribute_set_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'dls_dlsblog/post',
            'entity_type_id',
            'eav/entity_type',
            'entity_type_id'
        ),
        'entity_type_id',
        $this->getTable('eav/entity_type'),
        'entity_type_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Post Table');
$this->getConnection()->createTable($table);

$postEav = array();
$postEav['int'] = array(
    'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'length'    => null,
    'comment'   => 'Post Datetime Attribute Backend Table'
);

$postEav['varchar'] = array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'    => 255,
    'comment'   => 'Post Varchar Attribute Backend Table'
);

$postEav['text'] = array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'    => '64k',
    'comment'   => 'Post Text Attribute Backend Table'
);

$postEav['datetime'] = array(
    'type'      => Varien_Db_Ddl_Table::TYPE_DATETIME,
    'length'    => null,
    'comment'   => 'Post Datetime Attribute Backend Table'
);

$postEav['decimal'] = array(
    'type'      => Varien_Db_Ddl_Table::TYPE_DECIMAL,
    'length'    => '12,4',
    'comment'   => 'Post Datetime Attribute Backend Table'
);

foreach ($postEav as $type => $options) {
    $table = $this->getConnection()
        ->newTable($this->getTable(array('dls_dlsblog/post', $type)))
        ->addColumn(
            'value_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'identity'  => true,
                'nullable'  => false,
                'primary'   => true,
            ),
            'Value ID'
        )
        ->addColumn(
            'entity_type_id',
            Varien_Db_Ddl_Table::TYPE_SMALLINT,
            null,
            array(
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
            ),
            'Entity Type ID'
        )
        ->addColumn(
            'attribute_id',
            Varien_Db_Ddl_Table::TYPE_SMALLINT,
            null,
            array(
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
            ),
            'Attribute ID'
        )
        ->addColumn(
            'store_id',
            Varien_Db_Ddl_Table::TYPE_SMALLINT,
            null,
            array(
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
            ),
            'Store ID'
        )
        ->addColumn(
            'entity_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'unsigned'  => true,
                'nullable'  => false,
                'default'   => '0',
            ),
            'Entity ID'
        )
        ->addColumn(
            'value',
            $options['type'],
            $options['length'], array(),
            'Value'
        )
        ->addIndex(
            $this->getIdxName(
                array('dls_dlsblog/post', $type),
                array('entity_id', 'attribute_id', 'store_id'),
                Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
            ),
            array('entity_id', 'attribute_id', 'store_id'),
            array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
        )
        ->addIndex(
            $this->getIdxName(
                array('dls_dlsblog/post', $type),
                array('store_id')
            ),
            array('store_id')
        )
        ->addIndex(
            $this->getIdxName(
                array('dls_dlsblog/post', $type),
                array('entity_id')
            ),
            array('entity_id')
        )
        ->addIndex(
            $this->getIdxName(
                array('dls_dlsblog/post', $type),
                array('attribute_id')
            ),
            array('attribute_id')
        )
        ->addForeignKey(
            $this->getFkName(
                array('dls_dlsblog/post', $type),
                'attribute_id',
                'eav/attribute',
                'attribute_id'
            ),
            'attribute_id',
            $this->getTable('eav/attribute'),
            'attribute_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE
        )
        ->addForeignKey(
            $this->getFkName(
                array('dls_dlsblog/post', $type),
                'entity_id',
                'dls_dlsblog/post',
                'entity_id'
            ),
            'entity_id',
            $this->getTable('dls_dlsblog/post'),
            'entity_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE
        )
        ->addForeignKey(
            $this->getFkName(
                array('dls_dlsblog/post', $type),
                'store_id',
                'core/store',
                'store_id'
            ),
            'store_id',
            $this->getTable('core/store'),
            'store_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE,
            Varien_Db_Ddl_Table::ACTION_CASCADE
        )
        ->setComment($options['comment']);
    $this->getConnection()->createTable($table);
}
$table = $this->getConnection()
    ->newTable($this->getTable('dls_dlsblog/post_product'))
    ->addColumn(
        'rel_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Relation ID'
    )
    ->addColumn(
        'post_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
        ),
        'Post ID'
    )
    ->addColumn(
        'product_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
        ),
        'Product ID'
    )
    ->addColumn(
        'position',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'nullable'  => false,
            'default'   => '0',
        ),
        'Position'
    )
    ->addIndex(
        $this->getIdxName(
            'dls_dlsblog/post_product',
            array('product_id')
        ),
        array('product_id')
    )
    ->addForeignKey(
        $this->getFkName(
            'dls_dlsblog/post_product',
            'post_id',
            'dls_dlsblog/post',
            'entity_id'
        ),
        'post_id',
        $this->getTable('dls_dlsblog/post'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'dls_dlsblog/post_product',
            'product_id',
            'catalog/product',
            'entity_id'
        ),
        'product_id',
        $this->getTable('catalog/product'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addIndex(
        $this->getIdxName(
            'dls_dlsblog/post_product',
            array('post_id', 'product_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('post_id', 'product_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    )
    ->setComment('Post to Product Linkage Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('dls_dlsblog/post_comment'))
    ->addColumn(
        'comment_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Post Comment ID'
    )
    ->addColumn(
        'post_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array('nullable' => false),
        'Post ID'
    )
    ->addColumn(
        'title',
        Varien_Db_Ddl_Table::TYPE_TEXT,
        255,
        array('nullable' => false),
        'Comment Title'
    )
    ->addColumn(
        'comment',
        Varien_Db_Ddl_Table::TYPE_TEXT,
        '64k',
        array('nullable' => false),
        'Comment'
    )
    ->addColumn(
        'status',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array('nullable' => false),
        'Comment status'
    )
    ->addColumn(
        'customer_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array('nullable' => true),
        'Customer id'
    )
    ->addColumn(
        'name',
        Varien_Db_Ddl_Table::TYPE_TEXT,
        255,
        array('nullable' => false),
        'Customer name'
    )
    ->addColumn(
        'email',
        Varien_Db_Ddl_Table::TYPE_TEXT,
        255,
        array('nullable' => false),
        'Customer email'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Post Comment Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Post Comment Creation Time'
    )
    ->addForeignKey(
        $this->getFkName(
            'dls_dlsblog/post_comment',
            'post_id',
            'dls_dlsblog/post',
            'entity_id'
        ),
        'post_id',
        $this->getTable('dls_dlsblog/post'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'dls_dlsblog/post_comment',
            'customer_id',
            'customer/entity',
            'entity_id'
        ),
        'customer_id',
        $this->getTable('customer/entity'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_SET_NULL,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Post Comments Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('dls_dlsblog/post_comment_store'))
    ->addColumn(
        'comment_id',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'nullable'  => false,
            'primary'   => true,
        ),
        'Comment ID'
    )
    ->addColumn(
        'store_id',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Store ID'
    )
    ->addIndex(
        $this->getIdxName(
            'dls_dlsblog/post_comment_store',
            array('store_id')
        ),
        array('store_id')
    )
    ->addForeignKey(
        $this->getFkName(
            'dls_dlsblog/post_comment_store',
            'comment_id',
            'dls_dlsblog/post_comment',
            'comment_id'
        ),
        'comment_id',
        $this->getTable('dls_dlsblog/post_comment'),
        'comment_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'dls_dlsblog/post_comment_store',
            'store_id',
            'core/store',
            'store_id'
        ),
        'store_id',
        $this->getTable('core/store'),
        'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Posts Comments To Store Linkage Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('dls_dlsblog/blogset_taxonomy'))
    ->addColumn(
        'rel_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Relation ID'
    )
    ->addColumn(
        'blogset_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
        ),
        'Blog setting ID'
    )
    ->addColumn(
        'taxonomy_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
        ),
        'Taxonomy ID'
    )
    ->addColumn(
        'position',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'nullable'  => false,
            'default'   => '0',
        ),
        'Position'
    )
    ->addForeignKey(
        $this->getFkName(
            'dls_dlsblog/blogset_taxonomy',
            'blogset_id',
            'dls_dlsblog/blogset',
            'entity_id'
        ),
        'blogset_id',
        $this->getTable('dls_dlsblog/blogset'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'dls_dlsblog/blogset_taxonomy',
            'taxonomy_id',
            'dls_dlsblog/blogset',
            'entity_id'
        ),
        'taxonomy_id',
        $this->getTable('dls_dlsblog/taxonomy'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addIndex(
        $this->getIdxName(
            'dls_dlsblog/blogset_taxonomy',
            array('blogset_id', 'taxonomy_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('blogset_id', 'taxonomy_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    )
    ->setComment('Blog setting to Taxonomy Linkage Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('dls_dlsblog/taxonomy_filter'))
    ->addColumn(
        'rel_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Relation ID'
    )
    ->addColumn(
        'taxonomy_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
        ),
        'Taxonomy ID'
    )
    ->addColumn(
        'filter_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
        ),
        'Filter ID'
    )
    ->addColumn(
        'position',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'nullable'  => false,
            'default'   => '0',
        ),
        'Position'
    )
    ->addForeignKey(
        $this->getFkName(
            'dls_dlsblog/taxonomy_filter',
            'taxonomy_id',
            'dls_dlsblog/taxonomy',
            'entity_id'
        ),
        'taxonomy_id',
        $this->getTable('dls_dlsblog/taxonomy'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'dls_dlsblog/taxonomy_filter',
            'filter_id',
            'dls_dlsblog/taxonomy',
            'entity_id'
        ),
        'filter_id',
        $this->getTable('dls_dlsblog/filter'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addIndex(
        $this->getIdxName(
            'dls_dlsblog/taxonomy_filter',
            array('taxonomy_id', 'filter_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('taxonomy_id', 'filter_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    )
    ->setComment('Taxonomy to Filter Linkage Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('dls_dlsblog/taxonomy_post'))
    ->addColumn(
        'rel_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Relation ID'
    )
    ->addColumn(
        'taxonomy_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
        ),
        'Taxonomy ID'
    )
    ->addColumn(
        'post_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
        ),
        'Post ID'
    )
    ->addColumn(
        'position',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'nullable'  => false,
            'default'   => '0',
        ),
        'Position'
    )
    ->addForeignKey(
        $this->getFkName(
            'dls_dlsblog/taxonomy_post',
            'taxonomy_id',
            'dls_dlsblog/taxonomy',
            'entity_id'
        ),
        'taxonomy_id',
        $this->getTable('dls_dlsblog/taxonomy'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'dls_dlsblog/taxonomy_post',
            'post_id',
            'dls_dlsblog/taxonomy',
            'entity_id'
        ),
        'post_id',
        $this->getTable('dls_dlsblog/post'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addIndex(
        $this->getIdxName(
            'dls_dlsblog/taxonomy_post',
            array('taxonomy_id', 'post_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('taxonomy_id', 'post_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    )
    ->setComment('Taxonomy to Post Linkage Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('dls_dlsblog/tag_post'))
    ->addColumn(
        'rel_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Relation ID'
    )
    ->addColumn(
        'tag_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
        ),
        'Tag ID'
    )
    ->addColumn(
        'post_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
        ),
        'Post ID'
    )
    ->addColumn(
        'position',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'nullable'  => false,
            'default'   => '0',
        ),
        'Position'
    )
    ->addForeignKey(
        $this->getFkName(
            'dls_dlsblog/tag_post',
            'tag_id',
            'dls_dlsblog/tag',
            'entity_id'
        ),
        'tag_id',
        $this->getTable('dls_dlsblog/tag'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'dls_dlsblog/tag_post',
            'post_id',
            'dls_dlsblog/tag',
            'entity_id'
        ),
        'post_id',
        $this->getTable('dls_dlsblog/post'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addIndex(
        $this->getIdxName(
            'dls_dlsblog/tag_post',
            array('tag_id', 'post_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('tag_id', 'post_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    )
    ->setComment('Tag to Post Linkage Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('dls_dlsblog/eav_attribute'))
    ->addColumn(
        'attribute_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Attribute ID'
    )
    ->addColumn(
        'is_global',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(),
        'Attribute scope'
    )
    ->addColumn(
        'position',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(),
        'Attribute position'
    )
    ->addColumn(
        'is_wysiwyg_enabled',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(),
        'Attribute uses WYSIWYG'
    )
    ->addColumn(
        'is_visible',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(),
        'Attribute is visible'
    )
    ->setComment('DLSBlog attribute table');
$this->getConnection()->createTable($table);

$this->installEntities();
$attribute = Mage::getSingleton('eav/config')->getAttribute('dls_dlsblog_post', 'publish_status');
$options = $attribute->getSource()->getAllOptions(false);
foreach ($options as $option) {
    if ($option['label'] == 'draft') {
        $this->updateAttribute('dls_dlsblog_post', 'publish_status', 'default_value', $option['value']);
    }
}

$this->endSetup();
