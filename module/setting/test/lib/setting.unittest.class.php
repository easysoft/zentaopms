<?php
class settingTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('setting');
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
     * Test updateItem method.
     *
     * @param  string $path
     * @param  string $value
     * @access public
     * @return object
     */
    public function updateItemTest($path, $value = '')
    {
        $this->objectModel->updateItem($path, $value);

        if(dao::isError()) return dao::getError();

        /* Determine vision of config item. */
        $item = $this->objectModel->parseItemPath($path);
        if(empty($item)) return false;

        $paramString = array();
        foreach($item as $key => $value) $paramString[] = "{$key}={$value}";

        $objects = $this->objectModel->getItems(implode('&', $paramString));
        return array_pop($objects);
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

        return $this->objectModel->dao->select('count(*) as count')->from(TABLE_CONFIG)->fetch('count');
    }

    /**
     * Parse item path test.
     *
     * @param  string    $path
     * @access public
     * @return object
     */
    public function parseItemPathTest($path)
    {
        $object = $this->objectModel->parseItemPath($path);
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

    /**
     * Test create a DAO object to select or delete one or more records.
     *
     * @param  string $paramString
     * @param  string $method     select|delete.
     * @access public
     * @return array|int
     */
    public function createDAOTest($paramString, $method = 'select')
    {
        $params  = $this->objectModel->parseItemParam($paramString);
        if($method == 'delete')
        {
            $objects = $this->objectModel->createDAO($params, $method)->exec();
        }
        else
        {
            $objects = $this->objectModel->createDAO($params, $method)->orderBy('key')->fetch();
        }

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Get config of system and one user.
     *
     * @param  string $account
     * @access public
     * @return bool
     */
    public function getSysAndPersonalConfigTest($account = '')
    {
        $objects = $this->objectModel->getSysAndPersonalConfig($account);

        if(dao::isError()) return dao::getError();

        return !empty($objects) ? true : false;
    }

    /**
     * Test get the version of current zentaopms.
     *
     * Since the version field not saved in db. So if empty, return 0.3 beta.
     *
     * @access public
     * @return void
     */
    public function getVersionTest()
    {
        $objects = $this->objectModel->getVersion();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get URSR.
     *
     * @access public
     * @return int
     */
    public function getURSRTest()
    {
        $objects = $this->objectModel->getURSR();

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test update version
     *
     * @param  string $version
     * @access public
     * @return array
     */
    public function updateVersionTest($version)
    {
        $this->objectModel->updateVersion($version);

        if(dao::isError()) return dao::getError();

        $params['owner']   = 'system';
        $params['module']  = 'common';
        $params['section'] = 'global';
        $params['key']     = 'version';

        $objects = $this->objectModel->createDAO($params)->fetchAll();

        return $objects;
    }

    /**
     * Test set the sn of current zentaopms.
     *
     * @access public
     * @return bool
     */
    public function setSNTest()
    {
        $this->objectModel->setSN();

        if(dao::isError()) return dao::getError();

        $params['owner']   = 'system';
        $params['module']  = 'common';
        $params['section'] = 'global';
        $params['key']     = 'sn';

        $objects = $this->objectModel->createDAO($params)->fetchAll();

        return !empty($objects) ? true : false;
    }

    /**
     * Test judge a sn needed update or not.
     *
     * @param  string $sn
     * @access public
     * @return bool
     */
    public function snNeededUpdateTest($sn)
    {
        $objects = $this->objectModel->snNeededUpdate($sn);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
