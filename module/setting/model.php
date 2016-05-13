<?php
/**
 * The model file of setting module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     setting
 * @version     $Id: model.php 4976 2013-07-02 08:15:31Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class settingModel extends model
{
    //-------------------------------- methods for get, set and delete setting items. ----------------------------//
    
    /**
     * Get value of an item.
     * 
     * @param  string   $paramString    see parseItemParam();
     * @access public
     * @return misc
     */
    public function getItem($paramString)
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
    public function getItems($paramString)
    {
        return $this->createDAO($this->parseItemParam($paramString), 'select')->fetchAll('id');
    }

    /**
     * Set value of an item. 
     * 
     * @param  string      $path     system.common.global.sn or system.common.sn 
     * @param  string      $value 
     * @access public
     * @return void
     */
    public function setItem($path, $value = '')
    {
        /* fix bug when account has dot. */
        $account = isset($this->app->user->account) ? $this->app->user->account : '';
        $replace = false;
        if($account and strpos($path, $account) === 0)
        {
            $replace = true;
            $path    = preg_replace("/^{$account}/", 'account', $path);
        }

        $level    = substr_count($path, '.');
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
        $item->value   = $value;

        $this->dao->replace(TABLE_CONFIG)->data($item)->exec();
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
     * @return bool
     */
    public function setItems($path, $items)
    {
        foreach($items as $key => $item)
        {
            if(is_array($item) or is_object($item))
            {
                $section = $key;
                foreach($item as $subKey => $subItem)
                {
                    $this->setItem($path . '.' . $section . '.' . $subKey, $subItem);
                }
            }
            else
            {
                $this->setItem($path . '.' . $key, $item);
            }
        }

        if(!dao::isError()) return true;
        return false;
    }

    /**
     * Delete items.
     * 
     * @param  string   $paramString    see parseItemParam();
     * @access public
     * @return void
     */
    public function deleteItems($paramString)
    {
        $this->createDAO($this->parseItemParam($paramString), 'delete')->exec();
    }

    /**
     * Parse the param string for select or delete items.
     * 
     * @param  string    $paramString     owner=xxx&key=sn and so on.
     * @access public
     * @return array
     */
    public function parseItemParam($paramString)
    {
        /* Parse the param string into array. */
        parse_str($paramString, $params); 

        /* Init fields not set in the param string. */
        $fields = 'owner,module,section,key';
        $fields = explode(',', $fields);
        foreach($fields as $field) if(!isset($params[$field])) $params[$field] = '';

        return $params;
    }

    /**
     * Create a DAO object to select or delete one or more records.
     * 
     * @param  array  $params     the params parsed by parseItemParam() method.
     * @param  string $method     select|delete.
     * @access public
     * @return object
     */
    public function createDAO($params, $method = 'select')
    {
        return $this->dao->$method('*')->from(TABLE_CONFIG)->where('1 = 1')
            ->beginIF($params['owner'])->andWhere('owner')->in($params['owner'])->fi()
            ->beginIF($params['module'])->andWhere('module')->in($params['module'])->fi()
            ->beginIF($params['section'])->andWhere('section')->in($params['section'])->fi()
            ->beginIF($params['key'])->andWhere('`key`')->in($params['key'])->fi();
    }

    /**
     * Get config of system and one user.
     *
     * @param  string $account 
     * @access public
     * @return array
     */
    public function getSysAndPersonalConfig($account = '')
    {
        $owner   = 'system,' . ($account ? $account : '');
        $records = $this->dao->select('*')->from(TABLE_CONFIG)
            ->where('owner')->in($owner)
            ->orderBy('id')
            ->fetchAll('id');
        if(!$records) return array();

        /* Group records by owner and module. */
        $config = array();
        foreach($records as $record)
        {
            if(!isset($record->module)) return array();    // If no module field, return directly. Since 3.2 version, there's the module field.
            if(empty($record->module)) continue;

            $config[$record->owner]->{$record->module}[] = $record;
        }
        return $config;
    }

    //-------------------------------- methods for version and sn. ----------------------------//
   
    /**
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
     * Update version 
     * 
     * @param  string    $version 
     * @access public
     * @return void
     */
    public function updateVersion($version)
    {
        return $this->setItem('system.common.global.version', $version);
    }

    /**
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
     * Judge a sn needed update or not.
     * 
     * @param  string    $sn 
     * @access public
     * @return bool
     */
    public function snNeededUpdate($sn)
    {
        if($sn == '') return true;
        if($sn == '281602d8ff5ee7533eeafd26eda4e776') return true;
        if($sn == '9bed3108092c94a0db2b934a46268b4a') return true;
        if($sn == '8522dd4d76762a49d02261ddbe4ad432') return true;
        if($sn == '13593e340ee2bdffed640d0c4eed8bec') return true;

        return false;
    }
}
