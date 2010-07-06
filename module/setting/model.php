<?php
/**
 * The model file of setting module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     setting
 * @version     $Id$
 * @link        http://www.zentaoms.com
 */
?>
<?php
class settingModel extends model
{
    /* 获得已经安装的PMS的版本。0.3版本没有在数据库里面登记，所以当数据库中没有查到记录的时候，认为该版本为0.3。*/
    public function getVersion()
    {
        $version = $this->getItem('system', 'global', 'version');
        if($version) return $version;
        return '0.3 beta';
    }

    /* 获得某一个配置项的值。*/
    public function getItem($owner, $section, $key)
    {
        return $this->dao->select('`value`')->from(TABLE_CONFIG)
            ->where('company')->eq(0)
            ->andWhere('owner')->eq($owner)
            ->andWhere('section')->eq($section)
            ->andWhere('`key`')->eq($key)
            ->fetch('value', $autoCompany = false);
    }

    /* 计算当前系统的序列号。*/
    public function computeSN()
    {
        $seed = $this->server->SERVER_ADDR . $this->server->SERVER_SOFTWARE;
        $sn   = md5(str_shuffle(md5($seed . mt_rand(0, 99999999) . microtime())) . microtime());
        return $sn;
    }

    /* 设置当前系统的序列号。*/
    public function setSN()
    {
        $item->company = 0;
        $item->owner   = 'system';
        $item->section = 'global';
        $item->key     = 'sn';
        $item->value   =  $this->computeSN();

        $configID = $this->dao->select('id')->from(TABLE_CONFIG)
            ->where('company')->eq(0)
            ->andWhere('owner')->eq('system')
            ->andWhere('section')->eq('global')
            ->andWhere('`key`')->eq('sn')
            ->fetch('id', $autoComapny = false);
        if(!$configID) $this->dao->insert(TABLE_CONFIG)->data($item)->exec($autoCompany = false);
    }

    /* 更新PMS的版本设置。*/
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
