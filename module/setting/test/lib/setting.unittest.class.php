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

        return $this->objectModel->dao->select('COUNT(1) AS count')->from(TABLE_CONFIG)->fetch('count');
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
     * @return array|object
     */
    public function getSysAndPersonalConfigTest($account = '')
    {
        $objects = $this->objectModel->getSysAndPersonalConfig($account);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get the version of current zentaopms.
     *
     * Since the version field not saved in db. So if empty, return 0.3 beta.
     *
     * @param  string $version 要设置的版本号，为null时不设置（测试默认值）
     * @access public
     * @return void
     */
    public function getVersionTest($version = null)
    {
        // 备份原始配置
        $originalVersion = isset($this->objectModel->config->global->version) ? $this->objectModel->config->global->version : null;

        // 根据参数设置或清除配置
        if($version === null)
        {
            // 测试默认情况，清除版本配置
            if(isset($this->objectModel->config->global->version)) unset($this->objectModel->config->global->version);
        }
        else
        {
            // 设置指定版本
            if(!isset($this->objectModel->config->global)) $this->objectModel->config->global = new stdClass();
            $this->objectModel->config->global->version = $version;
        }

        $objects = $this->objectModel->getVersion();

        // 恢复原始配置
        if($originalVersion !== null)
        {
            if(!isset($this->objectModel->config->global)) $this->objectModel->config->global = new stdClass();
            $this->objectModel->config->global->version = $originalVersion;
        }
        elseif(isset($this->objectModel->config->global->version))
        {
            unset($this->objectModel->config->global->version);
        }

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Test get URSR.
     *
     * @param  bool $clearConfig 是否清空配置中的URSR
     * @access public
     * @return mixed
     */
    public function getURSRTest($clearConfig = false)
    {
        // 如果需要清空配置中的URSR，模拟配置不存在的情况
        if($clearConfig)
        {
            $originalURSR = isset($this->objectModel->config->URSR) ? $this->objectModel->config->URSR : null;
            unset($this->objectModel->config->URSR);
            $objects = $this->objectModel->getURSR();
            // 恢复原始配置
            if($originalURSR !== null) $this->objectModel->config->URSR = $originalURSR;
        }
        else
        {
            $objects = $this->objectModel->getURSR();
        }

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

        $objects = $this->objectModel->createDAO($params)->fetchAll('', false);

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
     * Test setSN for installed system without cookie.
     *
     * @access public
     * @return array
     */
    public function setSNTestForInstalled()
    {
        // 备份原始状态
        $originalInstalled = isset($this->objectModel->config->installed) ? $this->objectModel->config->installed : null;
        $originalCookie = isset($this->objectModel->cookie->sn) ? $this->objectModel->cookie->sn : null;

        // 模拟已安装状态且无cookie
        $this->objectModel->config->installed = true;
        if(isset($this->objectModel->cookie->sn)) unset($this->objectModel->cookie->sn);

        $sn = $this->objectModel->setSN();

        // 恢复原始状态
        if($originalInstalled !== null) $this->objectModel->config->installed = $originalInstalled;
        if($originalCookie !== null) $this->objectModel->cookie->sn = $originalCookie;

        if(dao::isError()) return dao::getError();

        return array('length' => strlen($sn), 'sn' => $sn);
    }

    /**
     * Test setSN with valid cookie.
     *
     * @access public
     * @return array
     */
    public function setSNTestWithValidCookie()
    {
        // 备份原始状态
        $originalInstalled = isset($this->objectModel->config->installed) ? $this->objectModel->config->installed : null;
        $originalCookie = isset($this->objectModel->cookie->sn) ? $this->objectModel->cookie->sn : null;

        // 模拟已安装状态且有有效cookie
        $this->objectModel->config->installed = true;
        $validSN = 'abcdef1234567890abcdef1234567890';
        $this->objectModel->cookie->sn = $validSN;

        $sn = $this->objectModel->setSN();

        // 恢复原始状态
        if($originalInstalled !== null) $this->objectModel->config->installed = $originalInstalled;
        if($originalCookie !== null) $this->objectModel->cookie->sn = $originalCookie;

        if(dao::isError()) return dao::getError();

        return array('length' => strlen($sn), 'sn' => $sn);
    }

    /**
     * Test setSN with invalid cookie that needs update.
     *
     * @access public
     * @return array
     */
    public function setSNTestWithInvalidCookie()
    {
        // 备份原始状态
        $originalInstalled = isset($this->objectModel->config->installed) ? $this->objectModel->config->installed : null;
        $originalCookie = isset($this->objectModel->cookie->sn) ? $this->objectModel->cookie->sn : null;

        // 模拟已安装状态且有需要更新的cookie
        $this->objectModel->config->installed = true;
        $this->objectModel->cookie->sn = '281602d8ff5ee7533eeafd26eda4e776'; // 需要更新的SN

        $sn = $this->objectModel->setSN();

        // 恢复原始状态
        if($originalInstalled !== null) $this->objectModel->config->installed = $originalInstalled;
        if($originalCookie !== null) $this->objectModel->cookie->sn = $originalCookie;

        if(dao::isError()) return dao::getError();

        return array('length' => strlen($sn), 'sn' => $sn);
    }

    /**
     * Test setSN for not installed system.
     *
     * @access public
     * @return array
     */
    public function setSNTestForNotInstalled()
    {
        // 备份原始状态
        $originalInstalled = isset($this->objectModel->config->installed) ? $this->objectModel->config->installed : null;
        $originalCookie = isset($this->objectModel->cookie->sn) ? $this->objectModel->cookie->sn : null;

        // 模拟未安装状态
        $this->objectModel->config->installed = false;
        if(isset($this->objectModel->cookie->sn)) unset($this->objectModel->cookie->sn);

        $sn = $this->objectModel->setSN();

        // 恢复原始状态
        if($originalInstalled !== null) $this->objectModel->config->installed = $originalInstalled;
        if($originalCookie !== null) $this->objectModel->cookie->sn = $originalCookie;

        if(dao::isError()) return dao::getError();

        return array('length' => strlen($sn), 'sn' => $sn);
    }

    /**
     * Test setSN return value format validation.
     *
     * @access public
     * @return array
     */
    public function setSNTestFormatValidation()
    {
        $sn = $this->objectModel->setSN();

        if(dao::isError()) return dao::getError();

        $isValidMD5 = (strlen($sn) == 32 && ctype_xdigit($sn)) ? 1 : 0;

        return array('isValidMD5' => $isValidMD5, 'sn' => $sn, 'length' => strlen($sn));
    }

    /**
     * Test setSN with multiple invalid SN values.
     *
     * @access public
     * @return array
     */
    public function setSNTestWithMultipleInvalidSNs()
    {
        $invalidSNs = array(
            '',
            '281602d8ff5ee7533eeafd26eda4e776',
            '9bed3108092c94a0db2b934a46268b4a',
            '8522dd4d76762a49d02261ddbe4ad432',
            '13593e340ee2bdffed640d0c4eed8bec'
        );

        $results = array();
        foreach($invalidSNs as $invalidSN)
        {
            // 备份原始状态
            $originalInstalled = isset($this->objectModel->config->installed) ? $this->objectModel->config->installed : null;
            $originalCookie = isset($this->objectModel->cookie->sn) ? $this->objectModel->cookie->sn : null;

            // 设置无效SN并测试
            $this->objectModel->config->installed = true;
            $this->objectModel->cookie->sn = $invalidSN;

            $newSN = $this->objectModel->setSN();

            // 恢复原始状态
            if($originalInstalled !== null) $this->objectModel->config->installed = $originalInstalled;
            if($originalCookie !== null) $this->objectModel->cookie->sn = $originalCookie;

            $results[] = array(
                'original' => $invalidSN,
                'new' => $newSN,
                'changed' => ($invalidSN !== $newSN) ? 1 : 0
            );
        }

        if(dao::isError()) return dao::getError();

        return $results;
    }

    /**
     * Test setSN configuration persistence.
     *
     * @access public
     * @return array
     */
    public function setSNTestConfigPersistence()
    {
        // 备份原始状态
        $originalInstalled = isset($this->objectModel->config->installed) ? $this->objectModel->config->installed : null;
        $originalCookie = isset($this->objectModel->cookie->sn) ? $this->objectModel->cookie->sn : null;

        // 模拟已安装状态
        $this->objectModel->config->installed = true;
        if(isset($this->objectModel->cookie->sn)) unset($this->objectModel->cookie->sn);

        $sn = $this->objectModel->setSN();

        // 检查配置是否正确保存
        $params = array(
            'owner' => 'system',
            'module' => 'common',
            'section' => 'global',
            'key' => 'sn'
        );
        $savedConfig = $this->objectModel->createDAO($params)->fetch();

        // 恢复原始状态
        if($originalInstalled !== null) $this->objectModel->config->installed = $originalInstalled;
        if($originalCookie !== null) $this->objectModel->cookie->sn = $originalCookie;

        if(dao::isError()) return dao::getError();

        return array(
            'generatedSN' => $sn,
            'savedSN' => $savedConfig ? $savedConfig->value : '',
            'configExists' => $savedConfig ? 1 : 0,
            'snMatch' => ($savedConfig && $savedConfig->value === $sn) ? 1 : 0
        );
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

    /**
     * Test compute SN method.
     *
     * @access public
     * @return string
     */
    public function computeSNTest()
    {
        $result = $this->objectModel->computeSN();
        if(dao::isError()) return dao::getError();

        return $result;
    }
}
