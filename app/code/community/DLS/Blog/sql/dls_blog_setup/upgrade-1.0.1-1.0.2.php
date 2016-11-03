<?php


$installer = $this;
$installer->startSetup();

$installer->getConnection()
->addColumn($installer->getTable('dls_blog/post_comment'),'remote_ip', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    'nullable'  => true,
    'length'    => 255,
    'after'     => null, // column name to insert new column after
    'comment'   => 'Remote Ip'
    ));
$installer->getConnection()->addColumn($installer->getTable('dls_blog/post_comment'),'notified', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'nullable'  => false,
    'after'     => null, // column name to insert new column after
    'comment'   => 'Notified',
    'default'	=> 0
    ))
;   
$installer->endSetup();