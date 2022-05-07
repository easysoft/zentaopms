<?php
class settingTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('setting');
    }

    /**
     * Get value of an item.
     *
     * @param  string   $paramString    see parseItemParam();
     * @access public
     * @return bool
     */
    public function getItemTest($paramString)
    {
        $objects = $this->objectModel->getItem($paramString);

        if(dao::isError()) return dao::getError();

        if($objects or $objects === 0 or $objects === '0') return true;
        return false;
    }

    /**
     * Get some items.
     *
     * @param  string   $paramString    see parseItemParam();
     * @access public
     * @return array|string
     */
    public function getItemsTest($paramString)
    {
        $objects = $this->objectModel->getItems($paramString);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Set value of an item.
     *
     * @param  string      $path     system.common.global.sn | system.common.sn | system.common.global.sn@rnd
     * @param  string      $value
     * @access public
     * @return misc
     */
    public function setItemTest($path, $value = '')
    {
        $this->objectModel->setItem($path, $value);

        if(dao::isError()) return dao::getError();

        /* Determine vision of config item. */
        $pathVision = explode('@', $path);
        $vision     = isset($pathVision[1]) ? $pathVision[1] : '';
        $path       = $pathVision[0];
        $level      = substr_count($path, '.');
        $section    = '';

        if($level <= 1) return false;
        if($level == 2) list($owner, $module, $key) = explode('.', $path);
        if($level == 3) list($owner, $module, $section, $key) = explode('.', $path);
        $paramString = "vision=$vision&owner=$owner&module=$module&section=$section&key=$key";
        $objects     = $this->objectModel->getItem($paramString);

        return $objects;
    }

    /**
     * Batch set items, the example:
     *
     * $path = 'system.mail';
     * $items->turnon = true;
     * $items->smtp->host = 'localhost';
     *
     * @param  string         $path   like system.mail
     * @param  array|object   $items  the items array or object, can be mixed by one level or two levels.
     * @access public
     * @return bool|string
     */
    public function setItemsTest($path, $items)
    {
        $result = $this->objectModel->setItems($path, $items);

        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Delete items.
     *
     * @param  string   $paramString    see parseItemParam();
     * @access public
     * @return misc
     */
    public function deleteItemsTest($paramString)
    {
        $this->objectModel->deleteItems($paramString);

        if(dao::isError()) return dao::getError();

        $object = $this->getItemTest($paramString);
        return $object;
    }

    /**
     * Parse the param string for select or delete items.
     *
     * @param  string    $paramString     owner=xxx&key=sn and so on.
     * @access public
     * @return array
     */
    public function parseItemParamTest($paramString)
    {
        $objects = $this->objectModel->parseItemParam($paramString);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function createDAOTest($params, $method = 'select')
    {
        $objects = $this->objectModel->createDAO($params, $method = 'select');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getSysAndPersonalConfigTest($account = '')
    {
        $objects = $this->objectModel->getSysAndPersonalConfig($account = '');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getVersionTest()
    {
        $objects = $this->objectModel->getVersion();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function getURSRTest()
    {
        $objects = $this->objectModel->getURSR();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function updateVersionTest($version)
    {
        $objects = $this->objectModel->updateVersion($version);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function setSNTest()
    {
        $objects = $this->objectModel->setSN();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    public function snNeededUpdateTest($sn)
    {
        $objects = $this->objectModel->snNeededUpdate($sn);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
