<?php
/**
 * The model file of upgrade module of ZenTaoMS.
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
 * @package     upgrade
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php
class upgradeModel extends model
{
    static $errors = array();

    /* 统一的升级入口，根据升级版本的不同，调用不同的升级步骤程序。*/
    public function execute($fromVersion)
    {
        if($fromVersion == '0_3')
        {
            $this->upgradeFrom0_3To0_4();
            $this->upgradeFrom0_4To0_5();
        }
        elseif($fromVersion == '0_4')
        {
            $this->upgradeFrom0_4To0_5();
        }
    }

    /* 确认。*/
    public function confirm($fromVersion)
    {
        $confirmContent = '';
        if($fromVersion == '0_3')
        {
            $confirmContent .= file_get_contents($this->getUpgradeFile('0.3'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('0.4'));
        }
        elseif($fromVersion == '0_4')
        {
            $confirmContent .= file_get_contents($this->getUpgradeFile('0.4'));
        }
        return str_replace('zt_', $this->config->db->prefix, $confirmContent);
    }


    /* 从0.3版本升级到0.4版本。*/
    private function upgradeFrom0_3To0_4()
    {
        $this->execSQL($this->getUpgradeFile('0.3'));
        if(!$this->isError()) $this->updateVersion('0.4 beta');
    }

    /* 从0.4版本升级到0.5版本。*/
    private function upgradeFrom0_4To0_5()
    {
        $this->execSQL($this->getUpgradeFile('0.4'));
        if(!$this->isError()) $this->updateVersion('0.5 beta');
    }

    /* 更新PMS的版本设置。*/
    public function updateVersion($version)
    {
        $item->owner   = 'system';
        $item->section = 'global';
        $item->key     = 'version';
        $item->value   =  $version;

        $configID = $this->dao->select('id')->from(TABLE_CONFIG)
            ->where('owner')->eq('system')
            ->andWhere('section')->eq('global')
            ->andWhere('`key`')->eq('version')
            ->fetch('id');
        if($configID > 0)
        {
            $this->dao->update(TABLE_CONFIG)->data($item)->where('id')->eq($configID)->exec();
        }
        else
        {
            $this->dao->insert(TABLE_CONFIG)->data($item)->exec();
        }
    }

    /* 获得要更新的sql文件。*/
    private function getUpgradeFile($version)
    {
        return $this->app->getAppRoot() . 'db' . $this->app->getPathFix() . 'update' . $version . '.sql';
    }

    /* 执行SQL。*/
    private function execSQL($sqlFile)
    {
        $mysqlVersion = $this->loadModel('install')->getMysqlVersion();
        $sqls = explode(';', file_get_contents($sqlFile));
        foreach($sqls as $sql)
        {
            $sql = trim($sql);
            if(empty($sql)) continue;

            if(strpos($sql, 'CREATE') !== false and $mysqlVersion <= 4.1)
            {
                $sql = str_replace('DEFAULT CHARSET=utf8', '', $sql);
            }
            $sql = str_replace('zt_', $this->config->db->prefix, $sql);
            try
            {
                $this->dbh->exec($sql);
            }
            catch (PDOException $e) 
            {
                self::$errors[] = $e->getMessage() . "<p>The sql is: $sql</p>";
            }
        }
    }

    /* 判断是否有误。*/
    public function isError()
    {
        return !empty(self::$errors);
    }

    /* 获得升级过程中的错误。*/
    public function getError()
    {
        $errors = self::$errors;
        self::$errors = array();
        return $errors;
    }
}
