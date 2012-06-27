<?php
/**
 * The model file of setting module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     setting
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class settingModel extends model
{
    /**
     * Get config of system and one user.
     *
     * @param  string $account 
     * @access public
     * @return array
     */
    public function getSysAndPersonalConfig($account = '')
    {
        $owner   = 'system,' . ($account ? $account : $this->app->user->account);
        $records = $this->dao->select('owner, module, section, `key`, value')
            ->from(TABLE_CONFIG)
            ->where('owner')->in($owner)
            ->fetchAll();
        if(!$records) return array();

        /* Group records by owner and module. */
        $config = array();
        foreach($records as $record)
        {
            if($record->section)  $config[$record->owner]->{$record->module}[] = $record;
            if(!$record->section) $config[$record->owner]->{$record->module}[] = $record;
        }
        return $config;
    }

    /**
     * Get the version of current zentaopms.
     * 
     * Since the version field not saved in db. So if empty, return 0.3 beta.
     * @access public
     * @return void
     */
    public function getVersion()
    {
        $version = $this->getItem('system', 'common', 'global', 'version', 0);
        if($version == '3.0.stable') $version = '3.0';   // convert 3.0.stable to 3.0.
        if($version) return $version;
        return '0.3 beta';
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
        $this->setItem('system', 'common', 'global', 'version', $version, 0);
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
     * Set the sn of current zentaopms.
     * 
     * @access public
     * @return void
     */
    public function setSN()
    {
        $sn = $this->getItem('system', 'common', 'global', 'sn', 0);
        if(!$sn) return false;
        if($sn->value == '281602d8ff5ee7533eeafd26eda4e776' or 
           $sn->value == '9bed3108092c94a0db2b934a46268b4a' or
           $sn->value == '8522dd4d76762a49d02261ddbe4ad432' or
           $sn->value == '13593e340ee2bdffed640d0c4eed8bec')
        {
            $sn->value = $this->computeSN();
            $this->dao->replace(TABLE_CONFIG)->data($sn)->exec($autoCompany = false);
        }
    }

    /**
     * Get value of an item.
     * 
     * @param  string     $owner 
     * @param  string     $module 
     * @param  string     $section 
     * @param  string     $key 
     * @param  string|int $company 
     * @access public
     * @return misc
     */
    public function getItem($owner, $module, $section, $key, $company = 'current')
    {
        if($company === 'current') $company = $this->app->company->id;
        return $this->dao->select('`value`')->from(TABLE_CONFIG)
            ->where('company')->eq($company)
            ->andWhere('owner')->eq($owner)
            ->andWhere('module')->eq($module)
            ->andWhere('section')->eq($section)
            ->andWhere('`key`')->eq($key)
            ->fetch('value', $autoCompany = false);
    }

    /**
     * Set value of an item. 
     * 
     * @param  string      $owner 
     * @param  string      $module 
     * @param  string      $section 
     * @param  string      $key 
     * @param  string      $value 
     * @param  string|int  $company 
     * @access public
     * @return void
     */
    public function setItem($owner, $module, $section, $key, $value = '', $company = 'current')
    {
        $item->company = $company === 'current' ? $this->app->company->id : $company;
        $item->owner   = $owner;
        $item->module  = $module;
        $item->section = $section;
        $item->key     = $key;
        $item->value   = $value;

        $this->dao->replace(TABLE_CONFIG)->data($item)->exec($autoCompany = false);
    }
}
