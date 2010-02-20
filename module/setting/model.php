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
 * @copyright   Copyright 2009-2010 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     setting
 * @version     $Id$
 * @link        http://www.zentao.cn
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
            ->where('company')->eq($this->app->company->id)
            ->andWhere('owner')->eq($owner)
            ->andWhere('section')->eq($section)
            ->andWhere('`key`')->eq($key)
            ->fetch('value');
    }
}
