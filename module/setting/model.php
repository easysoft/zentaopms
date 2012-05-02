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
     * Get the version of current zentaopms.
     * 
     * Since the version field not saved in db. So if empty, return 0.3 beta.
     * @access public
     * @return void
     */
    public function getVersion()
    {
        $version = $this->getItem('system', 'global', 'version');
        if($version == '3.0.stable') $version = '3.0';   // convert 3.0.stable to 3.0.
        if($version) return $version;
        return '0.3 beta';
    }

    /**
     * Get value of an item.
     * 
     * @param  string    $owner 
     * @param  string    $section 
     * @param  string    $key 
     * @access public
     * @return misc
     */
    public function getItem($owner, $section, $key)
    {
        return $this->dao->select('`value`')->from(TABLE_CONFIG)
            ->where('company')->eq(0)
            ->andWhere('owner')->eq($owner)
            ->andWhere('section')->eq($section)
            ->andWhere('`key`')->eq($key)
            ->fetch('value', $autoCompany = false);
    }

    /**
     * Set value of an item. 
     * 
     * @param  string $owner 
     * @param  string $section 
     * @param  string $key 
     * @param  string $value 
     * @access public
     * @return void
     */
    public function setItem($owner, $section, $key, $value = '')
    {
        $item->company = 0;
        $item->owner   = $owner;
        $item->section = $section;
        $item->key     = $key;
        $item->value   = $value;

        $config = $this->dao->select('`value`')->from(TABLE_CONFIG)
            ->where('company')->eq(0)
            ->andWhere('owner')->eq($owner)
            ->andWhere('section')->eq($section)
            ->andWhere('`key`')->eq($key)
            ->fetch('', $autoComapny = false);
        if(!$config)
        {
            $this->dao->insert(TABLE_CONFIG)->data($item)->exec($autoCompany = false);
        }
        else
        {
            $this->dao->update(TABLE_CONFIG)->data($item)->where('id')->eq($config->id)->exec($autoCompany = false);
        }
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
        $item->company = 0;
        $item->owner   = 'system';
        $item->section = 'global';
        $item->key     = 'sn';
        $item->value   =  $this->computeSN();

        $config = $this->dao->select('id, value')->from(TABLE_CONFIG)
            ->where('company')->eq(0)
            ->andWhere('owner')->eq('system')
            ->andWhere('section')->eq('global')
            ->andWhere('`key`')->eq('sn')
            ->fetch('', $autoComapny = false);
        if(!$config)
        {
            $this->dao->insert(TABLE_CONFIG)->data($item)->exec($autoCompany = false);
        }
        elseif($config->value == '281602d8ff5ee7533eeafd26eda4e776' or 
               $config->value == '9bed3108092c94a0db2b934a46268b4a' or
               $config->value == '8522dd4d76762a49d02261ddbe4ad432' or
               $config->value == '13593e340ee2bdffed640d0c4eed8bec')
        {
            $this->dao->update(TABLE_CONFIG)->data($item)->where('id')->eq($config->id)->exec($autoCompany = false);
        }
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
        $item->company = 0;
        $item->owner   = 'system';
        $item->section = 'global';
        $item->key     = 'version';
        $item->value   =  $version;

        $configID = $this->dao->select('id')->from(TABLE_CONFIG)
            ->where('company')->eq(0)
            ->andWhere('owner')->eq('system')
            ->andWhere('section')->eq('global')
            ->andWhere('`key`')->eq('version')
            ->fetch('id', $autoComapny = false);
        if($configID > 0)
        {
            $this->dao->update(TABLE_CONFIG)->data($item)->where('id')->eq($configID)->exec($autoCompany = false);
        }
        else
        {
            $this->dao->insert(TABLE_CONFIG)->data($item)->exec($autoCompany = false);
        }
    }
}
