<?php

class DLS_Blog_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract {

    public function initControllerRouters($observer) {
        $front = $observer->getEvent()->getFront();
        $front->addRouter('dls_blog', $this);
        return $this;
    }

    public function match(Zend_Controller_Request_Http $request) {
        if (!Mage::isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                    ->setRedirect(Mage::getUrl('install'))
                    ->sendResponse();
            exit;
        }
        $urlKey = trim($request->getPathInfo(), '/');
        $check = array();
        $check['blogset'] = new Varien_Object(
                array(
            'prefix' => Mage::getStoreConfig('dls_blog/blogset/url_prefix'),
            'suffix' => Mage::getStoreConfig('dls_blog/blogset/url_suffix'),
            'list_key' => Mage::getStoreConfig('dls_blog/blogset/url_rewrite_list'),
            'list_action' => 'index',
            'model' => 'dls_blog/blogset',
            'controller' => 'blogset',
            'action' => 'view',
            'param' => 'id',
            'check_path' => 0
                )
        );
        $check['taxonomy'] = new Varien_Object(
                array(
            'prefix' => Mage::getStoreConfig('dls_blog/taxonomy/url_prefix'),
            'suffix' => Mage::getStoreConfig('dls_blog/taxonomy/url_suffix'),
            'list_key' => Mage::getStoreConfig('dls_blog/taxonomy/url_rewrite_list'),
            'list_action' => 'index',
            'model' => 'dls_blog/taxonomy',
            'controller' => 'taxonomy',
            'action' => 'view',
            'param' => 'id',
            'check_path' => 1
                )
        );
        $check['filter'] = new Varien_Object(
                array(
            'prefix' => Mage::getStoreConfig('dls_blog/filter/url_prefix'),
            'suffix' => Mage::getStoreConfig('dls_blog/filter/url_suffix'),
            'list_key' => Mage::getStoreConfig('dls_blog/filter/url_rewrite_list'),
            'list_action' => 'index',
            'model' => 'dls_blog/filter',
            'controller' => 'filter',
            'action' => 'view',
            'param' => 'id',
            'check_path' => 0
                )
        );
        $check['post'] = new Varien_Object(
                array(
            'prefix' => Mage::getStoreConfig('dls_blog/post/url_prefix'),
            'suffix' => Mage::getStoreConfig('dls_blog/post/url_suffix'),
            'list_key' => Mage::getStoreConfig('dls_blog/post/url_rewrite_list'),
            'list_action' => 'index',
            'model' => 'dls_blog/post',
            'controller' => 'post',
            'action' => 'view',
            'param' => 'id',
            'check_path' => 0
                )
        );
        foreach ($check as $key => $settings) {
            if ($settings->getListKey()) {
                if ($urlKey == $settings->getListKey()) {
                    $request->setModuleName('blog')
                            ->setControllerName($settings->getController())
                            ->setActionName($settings->getListAction());
                    $request->setAlias(
                            Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS, $urlKey
                    );
                    return true;
                }
            }
            if ($settings['prefix']) {
                $parts = explode('/', $urlKey);
                if ($parts[0] != $settings['prefix'] || count($parts) != 2) {
                    continue;
                }
                $urlKey = $parts[1];
            }
            if ($settings['suffix']) {
                $urlKey = substr($urlKey, 0, -strlen($settings['suffix']) - 1);
            }
            $model = Mage::getModel($settings->getModel());
            $id = $model->checkUrlKey($urlKey, Mage::app()->getStore()->getId());
            if ($id) {
                if ($settings->getCheckPath() && !$model->load($id)->getStatusPath()) {
                    continue;
                }
                $request->setModuleName('blog')
                        ->setControllerName($settings->getController())
                        ->setActionName($settings->getAction())
                        ->setParam($settings->getParam(), $id);
                $request->setAlias(
                        Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS, $urlKey
                );
                return true;
            }
        }
        return false;
    }

}
