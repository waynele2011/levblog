<?php
$installer = $this;
$installer->startSetup();

$installer->updateAttribute('dls_blog_post','publish_date','is_required',0);


$installer->endSetup();