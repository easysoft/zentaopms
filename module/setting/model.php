<?php
declare(strict_types=1);
/**
 * The model file of setting module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     setting
 * @version     $Id: model.php 4976 2013-07-02 08:15:31Z wyd621@gmail.com $
 * @link        https://www.zentao.net
 */
class settingModel extends model
{
    //-------------------------------- methods for get, set and delete setting items. ----------------------------//

    /**
     * 获取配置。
     * Get value of an item.
     *
     * @param  string   $paramString    see parseItemParam();
     * @access public
     * @return string
     */
    public function getItem(string $paramString): string
    {
        return $this->createDAO($this->parseItemParam($paramString), 'select')->fetch('value');
    }

    /**
     * Get some items.
     *
     * @param  string   $paramString    see parseItemParam();
     * @access public
     * @return array
     */
    public function getItems(string $paramString): array
    {
        return $this->createDAO($this->parseItemParam($paramString), 'select')->fetchAll('id');
    }

    /**
     * 保存配置。
     * Set value of an item.
     *
     * @param  string  $path     system.common.global.sn | system.common.sn | system.common.global.sn@rnd
     * @param  mixed   $value
     * @access public
     * @return bool
     */
    public function setItem(string $path, mixed $value = ''): bool
    {
        $item = $this->parseItemPath($path);
        if(empty($item)) return false;

        $item->value = strval($value);
        $this->dao->replace(TABLE_CONFIG)->data($item)->exec();

        return !dao::isError();
    }

    /**
     * 批量保存配置。
     * Batch set items, the example:
     *
     * $path = 'system.mail';
     * $items->turnon = true;
     * $items->smtp->host = 'localhost';
     *
     * @param  string         $path   like system.mail
     * @param  array|object   $items  the items array or object, can be mixed by one level or two levels.
     * @access public
     * @return bool
     */
    public function setItems(string $path, array|object $items): bool
    {
        /* Determine vision of config item. */
        $pathVision = explode('@', $path);
        $vision = isset($pathVision[1]) ? $pathVision[1] : '';
        $path   = $pathVision[0];

        foreach($items as $key => $item)
        {
            if(is_array($item) or is_object($item))
            {
                $section = $key;
                foreach($item as $subKey => $subItem)
                {
                    $this->setItem($path . '.' . $section . '.' . $subKey . "@$vision", $subItem);
                }
            }
            else
            {
                $this->setItem($path . '.' . $key . "@$vision", $item);
            }
        }

        if(!dao::isError()) return true;
        return false;
    }

    /**
     * 如果配置项存在则更新，不存在则插入。
     * When exists this item then update it. No exists then insert this item.
     *
     * @param  string     $path
     * @param  string|int $value
     * @access public
     * @return bool
     */
    public function updateItem(string $path, string|int $value = ''):bool
    {
        $item = $this->parseItemPath($path);
        if(empty($item)) return false;
        if(!isset($item->vision)) $item->vision = '';

        $updateID = $this->dao->select('id')->from(TABLE_CONFIG)
            ->where('owner')->eq($item->owner)
            ->andWhere('vision')->eq($item->vision)
            ->andWhere('module')->eq($item->module)
            ->andWhere('section')->eq($item->section)
            ->andWhere('`key`')->eq($item->key)
            ->fetch('id');

        if($updateID)
        {
            $this->dao->update(TABLE_CONFIG)->set('value')->eq($value)->where('id')->eq($updateID)->exec();
            return true;
        }

        $item->value = $value;
        $this->dao->insert(TABLE_CONFIG)->data($item)->exec();
        return true;
    }

    /**
     * 删除配置项。
     * Delete items.
     *
     * @param  string   $paramString    see parseItemParam();
     * @access public
     * @return void
     */
    public function deleteItems(string $paramString): void
    {
        $this->createDAO($this->parseItemParam($paramString), 'delete')->exec();
    }

    /**
     * 处理传入的配置项，返回标准的配置项对象。
     * Parse item path
     *
     * @param  string      $path     system.common.global.sn | system.common.sn | system.common.global.sn@rnd
     * @access public
     * @return object|bool
     */
    public function parseItemPath(string $path): object|bool
    {
        /* Determine vision of config item. */
        $pathVision = explode('@', $path);
        $vision     = isset($pathVision[1]) ? $pathVision[1] : '';
        $path       = $pathVision[0];

        /* fix bug when account has dot. */
        $account = isset($this->app->user->account) ? $this->app->user->account : '';
        $replace = false;
        if($account and strpos($path, $account) === 0)
        {
            $replace = true;
            $path    = preg_replace("/^{$account}/", 'account', $path);
        }

        $level   = substr_count($path, '.');
        $section = '';

        if($level <= 1) return false;
        if($level == 2) list($owner, $module, $key) = explode('.', $path);
        if($level == 3) list($owner, $module, $section, $key) = explode('.', $path);
        if($replace) $owner = $account;

        $item = new stdclass();
        $item->owner   = $owner;
        $item->module  = $module;
        $item->section = $section;
        $item->key     = $key;
        if(!empty($vision)) $item->vision = $vision;

        return $item;
    }

    /**
     * 解析配置项参数。
     * Parse the param string for select or delete items.
     *
     * @param  string    $paramString     owner=xxx&key=sn and so on.
     * @access public
     * @return array
     */
    public function parseItemParam(string $paramString): array
    {
        /* Parse the param string into array. */
        parse_str($paramString, $params);

        /* Init fields not set in the param string. */
        $fields = 'vision,owner,module,section,key';
        $fields = explode(',', $fields);
        foreach($fields as $field) if(!isset($params[$field])) $params[$field] = '';

        return $params;
    }

    /**
     * 创建DAO对象，用于查询或删除多条记录。
     * Create a DAO object to select or delete one or more records.
     *
     * @param  array  $params     the params parsed by parseItemParam() method.
     * @param  string $method     select|delete.
     * @access public
     * @return object
     */
    public function createDAO(array $params, string $method = 'select'): object
    {
        $params['vision']  = isset($params['vision'])  ? $params['vision']  : '';
        $params['owner']   = isset($params['owner'])   ? $params['owner']   : '';
        $params['module']  = isset($params['module'])  ? $params['module']  : '';
        $params['section'] = isset($params['section']) ? $params['section'] : '';
        $params['key']     = isset($params['key'])     ? $params['key']     : '';

        return $this->dao->$method('*')->from(TABLE_CONFIG)->where('1 = 1')
            ->beginIF($params['vision'])->andWhere('vision')->in($params['vision'])->fi()
            ->beginIF($params['owner'])->andWhere('owner')->in($params['owner'])->fi()
            ->beginIF($params['module'])->andWhere('module')->in($params['module'])->fi()
            ->beginIF($params['section'])->andWhere('section')->in($params['section'])->fi()
            ->beginIF($params['key'])->andWhere('`key`')->in($params['key'])->fi();
    }

    /**
     * 获取系统和用户的配置。
     * Get config of system and one user.
     *
     * @param  string $account
     * @access public
     * @return array
     */
    public function getSysAndPersonalConfig(string $account = ''): array
    {
        $owner   = 'system,' . ($account ? $account : '');
        $records = $this->dao->select('*')->from(TABLE_CONFIG)
            ->where('owner')->in($owner)
            ->beginIF(!$this->app->upgrading)->andWhere('vision')->in(array('', $this->config->vision))->fi()
            ->orderBy('id')
            ->fetchAll('id');
        if(!$records) return array();

        $vision = $this->config->vision;

        /* Group records by owner and module. */
        $config = array();
        foreach($records as $record)
        {
            if(!isset($config[$record->owner])) $config[$record->owner] = new stdclass();
            if(!isset($record->module)) return array();    // If no module field, return directly. Since 3.2 version, there's the module field.
            if(empty($record->module)) continue;

            /* If it`s lite vision unset config requiredFields */
            if($vision == 'lite' and $record->key == 'requiredFields' and $record->vision == '') continue;

            $config[$record->owner]->{$record->module}[] = $record;
        }
        return $config;
    }

    //-------------------------------- methods for version and sn. ----------------------------//

    /**
     * 获取禅道版本号。
     * Get the version of current zentaopms.
     *
     * Since the version field not saved in db. So if empty, return 0.3 beta.
     *
     * @access public
     * @return void
     */
    public function getVersion()
    {
        $version = isset($this->config->global->version) ? $this->config->global->version : '0.3.beta';    // No version, set as 0.3.beta.
        if($version == '3.0.stable') $version = '3.0';    // convert 3.0.stable to 3.0.
        return $version;
    }

    /**
     * 获取用需、软需的配置。
     * Get URSR.
     *
     * @access public
     * @return int
     */
    public function getURSR()
    {
        if(isset($this->config->URSR)) return $this->config->URSR;
        return $this->getItem('owner=system&module=custom&key=URSR');
    }

    /**
     * 更新禅道版本号。
     * Update version
     *
     * @param  string  $version
     * @access public
     * @return void
     */
    public function updateVersion($version)
    {
        return $this->setItem('system.common.global.version', $version);
    }

    /**
     * 设置sn。
     * Set the sn of current zentaopms.
     *
     * @access public
     * @return void
     */
    public function setSN()
    {
        $sn = $this->getItem('owner=system&module=common&section=global&key=sn');
        if($this->snNeededUpdate($sn)) $this->setItem('system.common.global.sn', $this->computeSN());
    }

    /**
     * Compute a SN. Use the server ip, and server software string as seed, and an rand number, two micro time
     *
     * Note: this sn just to unique this zentaopms. No any private info.
     *
     * @access public
     * @return string
     */
    public function computeSN()
    {
        $seed = $this->server->SERVER_ADDR . $this->server->SERVER_SOFTWARE;
        $sn   = md5(str_shuffle(md5($seed . mt_rand(0, 99999999) . microtime())) . microtime());
        return $sn;
    }

    /**
     * 判断是否需要更新sn。
     * Judge a sn needed update or not.
     *
     * @param  string    $sn
     * @access public
     * @return bool
     */
    public function snNeededUpdate(string $sn): bool
    {
        if($sn == '') return true;
        if($sn == '281602d8ff5ee7533eeafd26eda4e776') return true;
        if($sn == '9bed3108092c94a0db2b934a46268b4a') return true;
        if($sn == '8522dd4d76762a49d02261ddbe4ad432') return true;
        if($sn == '13593e340ee2bdffed640d0c4eed8bec') return true;

        return false;
    }
}
