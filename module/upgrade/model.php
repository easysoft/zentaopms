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
        if($fromVersion == '0_3beta')
        {
            $this->upgradeFrom0_3To0_4();
            $this->upgradeFrom0_4To0_5();
            $this->upgradeFrom0_5To0_6();
            $this->upgradeFrom0_6To1_0_B();
            $this->upgradeFrom1_0betaTo1_0rc1();
            $this->upgradeFrom1_0rc1To1_0rc2();
        }
        elseif($fromVersion == '0_4beta')
        {
            $this->upgradeFrom0_4To0_5();
            $this->upgradeFrom0_5To0_6();
            $this->upgradeFrom0_6To1_0_B();
            $this->upgradeFrom1_0betaTo1_0rc1();
            $this->upgradeFrom1_0rc1To1_0rc2();
        }
        elseif($fromVersion == '0_5beta')
        {
            $this->upgradeFrom0_5To0_6();
            $this->upgradeFrom0_6To1_0_B();
            $this->upgradeFrom1_0betaTo1_0rc1();
            $this->upgradeFrom1_0rc1To1_0rc2();
        }
        elseif($fromVersion == '0_6beta')
        {
            $this->upgradeFrom0_6To1_0_B();
            $this->upgradeFrom1_0betaTo1_0rc1();
            $this->upgradeFrom1_0rc1To1_0rc2();
        }
        elseif($fromVersion == '1_0beta')
        {
            $this->upgradeFrom1_0betaTo1_0rc1();
            $this->upgradeFrom1_0rc1To1_0rc2();
        }
        elseif($fromVersion == '1_0rc1')
        {
            $this->upgradeFrom1_0rc1To1_0rc2();
        }
    }

    /* 确认。*/
    public function confirm($fromVersion)
    {
        $confirmContent = '';
        if($fromVersion == '0_3beta')
        {
            $confirmContent .= file_get_contents($this->getUpgradeFile('0.3'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('0.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('0.5'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('0.6'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.0.beta'));
        }
        elseif($fromVersion == '0_4beta')
        {
            $confirmContent .= file_get_contents($this->getUpgradeFile('0.4'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('0.5'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('0.6'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.0.beta'));
        }
        elseif($fromVersion == '0_5beta')
        {
            $confirmContent .= file_get_contents($this->getUpgradeFile('0.5'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('0.6'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.0.beta'));
        }
        elseif($fromVersion == '0_6beta')
        {
            $confirmContent .= file_get_contents($this->getUpgradeFile('0.6'));
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.0.beta'));
        }
        elseif($fromVersion == '1_0beta')
        {
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.0.beta'));
        }
        elseif($fromVersion == '1_0rc1')
        {
            $confirmContent .= file_get_contents($this->getUpgradeFile('1.0.rc1'));
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

    /* 从0.5版本升级到0.6版本。*/
    private function upgradeFrom0_5To0_6()
    {
        $this->execSQL($this->getUpgradeFile('0.5'));
        if(!$this->isError()) $this->updateVersion('0.6 beta');
    }

    /* 从0.6版本升级到1.0 beta版本。*/
    private function upgradeFrom0_6To1_0_B()
    {
        $this->execSQL($this->getUpgradeFile('0.6'));
        if(!$this->isError()) $this->updateVersion('1.0beta');
    }

    /* 从1.0beta版本升级到1.0rc1版本。*/
    private function upgradeFrom1_0betaTo1_0rc1()
    {
        $this->execSQL($this->getUpgradeFile('1.0.beta'));
        $this->updateCompany();
        if(!$this->isError()) $this->updateVersion('1.0rc1');
    }

    /* 从1.0rc1版本升级到1.0rc2版本。*/
    private function upgradeFrom1_0betaTo1_0rc2()
    {
        $this->execSQL($this->getUpgradeFile('1.0.rc1'));
        if(!$this->isError()) $this->updateVersion('1.0rc2');
    }

    /* 更新每个表的company字段。*/
    private function updateCompany()
    {
        $constants     = get_defined_constants(true);
        $userConstants = $constants['user'];

        /* 查找每个表的id字段的最大值。*/
        foreach($userConstants as $key => $value)
        {
            if(strpos($key, 'TABLE') === false) continue;
            if($key == 'TABLE_COMPANY' or $key == 'TABLE_CONFIG') continue;
            $this->dbh->query("UPDATE $value SET company = '{$this->app->company->id}'");
        }
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

    /* 获得要更新的sql文件。*/
    private function getUpgradeFile($version)
    {
        return $this->app->getAppRoot() . 'db' . $this->app->getPathFix() . 'update' . $version . '.sql';
    }

    /* 执行SQL。*/
    private function execSQL($sqlFile)
    {
        $mysqlVersion = $this->loadModel('install')->getMysqlVersion();

        /* 去掉注释之后，再用;隔开。*/
        $sqls = explode("\n", file_get_contents($sqlFile));
        foreach($sqls as $key => $line) 
        {
            $line       = trim($line);
            $sqls[$key] = $line;
            if(strpos($line, '--') !== false or empty($line)) unset($sqls[$key]);
        }
        $sqls = explode(';', join("\n", $sqls));

        foreach($sqls as $sql)
        {
            $sql = trim($sql);
            if(empty($sql)) continue;

            if($mysqlVersion <= 4.1)
            {
                $sql = str_replace('DEFAULT CHARSET=utf8', '', $sql);
                $sql = str_replace('CHARACTER SET utf8 COLLATE utf8_general_ci', '', $sql);
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
