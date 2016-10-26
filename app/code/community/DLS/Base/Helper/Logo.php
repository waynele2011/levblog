<?php

class DLS_Base_Helper_Logo
{
    const DLS_LOGO_SKIN_DIRECTORY = 'dls_base/logo/skin_directory';
    const DLS_LIGHT_LOGO_IMAGE_NAME = 'dls_base/logo/logo_light_image_name';
    const DLS_DARK_LOGO_IMAGE_NAME = 'dls_base/logo/logo_dark_image_name';
    const DLS_LIGHT_LOGO_DOTS_IMAGE_NAME = 'dls_base/logo/logo_dots_light_image_name';
    const DLS_DARK_LOGO_DOTS_IMAGE_NAME = 'dls_base/logo/logo_dots_dark_image_name';

    protected $_dls_light_logo_image_url = null;
    protected $_dls_dark_logo_image_url = null;
    protected $_dls_light_logo_dots_image_url = null;
    protected $_dls_dark_logo_dots_image_url = null;

    public function getDLSLightLogoImageURL()
    {
        if (is_null($this->_dls_light_logo_image_url))
        {
            $dls_logo_skin_directory = Mage::getStoreConfig(self::DLS_LOGO_SKIN_DIRECTORY);
            $dls_light_logo_image_name = Mage::getStoreConfig(self::DLS_LIGHT_LOGO_IMAGE_NAME);
            $this->_dls_light_logo_image_url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) . DS . $dls_logo_skin_directory . DS . $dls_light_logo_image_name;
        }

        return $this->_dls_light_logo_image_url;
    }

    public function getDLSDarkLogoImageURL()
    {
        if (is_null($this->_dls_dark_logo_image_url))
        {
            $dls_logo_skin_directory = Mage::getStoreConfig(self::DLS_LOGO_SKIN_DIRECTORY);
            $dls_dark_logo_image_name = Mage::getStoreConfig(self::DLS_DARK_LOGO_IMAGE_NAME);
            $this->_dls_dark_logo_image_url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) . DS . $dls_logo_skin_directory . DS . $dls_dark_logo_image_name;
        }

        return $this->_dls_dark_logo_image_url;
    }

    public function getDLSLogoURL($light = true)
    {
        if ($light)
        {
            return $this->getDLSLightLogoImageURL();
        }

        return $this->getDLSDarkLogoImageURL();
    }

    public function getDLSDotsLightLogoImageURL()
    {
        if (is_null($this->_dls_light_logo_dots_image_url))
        {
            $dls_logo_skin_directory = Mage::getStoreConfig(self::DLS_LOGO_SKIN_DIRECTORY);
            $dls_light_logo_image_name = Mage::getStoreConfig(self::DLS_LIGHT_LOGO_DOTS_IMAGE_NAME);
            $this->_dls_light_logo_dots_image_url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) . DS . $dls_logo_skin_directory . DS . $dls_light_logo_image_name;
        }

        return $this->_dls_light_logo_dots_image_url;
    }

    public function getDLSDotsDarkLogoImageURL()
    {
        if (is_null($this->_dls_dark_logo_dots_image_url))
        {
            $dls_logo_skin_directory = Mage::getStoreConfig(self::DLS_LOGO_SKIN_DIRECTORY);
            $dls_dark_logo_image_name = Mage::getStoreConfig(self::DLS_DARK_LOGO_DOTS_IMAGE_NAME);
            $this->_dls_dark_logo_image_url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN) . DS . $dls_logo_skin_directory . DS . $dls_dark_logo_image_name;
        }

        return $this->_dls_dark_logo_dots_image_url;
    }

    public function getDLSDotsLogoURL($light = true)
    {
        if ($light)
        {
            return $this->getDLSDotsLightLogoImageURL();
        }

        return $this->getDLSDotsDarkLogoImageURL();
    }
}
