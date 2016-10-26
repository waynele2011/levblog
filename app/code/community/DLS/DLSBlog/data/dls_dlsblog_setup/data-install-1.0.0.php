<?php

/*
 * @category    DLS
 * @package     DLS_DLSBlog
 * @author      Ultimate Module Creator
 */
Mage::getModel('dls_dlsblog/taxonomy')
        ->load(1)
        ->setParentId(0)
        ->setPath(1)
        ->setLevel(0)
        ->setPosition(0)
        ->setChildrenCount(0)
        ->setName('ROOT')
        ->setInitialSetupFlag(true)
        ->save();
