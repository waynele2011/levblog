<?php


class DLS_Base_Helper_Data extends Mage_Core_Helper_Data
{
    const DLS_WEBSITE_URL_CONFIG_PATH = 'dls_base/website_url';

    protected $_dls_website_url = null;

    public function getDLSWebsiteUrl()
    {
        if (is_null($this->_dls_website_url))
        {
            $dls_website_url = Mage::getStoreConfig(self::DLS_WEBSITE_URL_CONFIG_PATH);
            $this->_dls_website_url = $dls_website_url;
        }

        return $this->_dls_website_url;
    }
}
