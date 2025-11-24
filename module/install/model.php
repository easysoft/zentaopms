<?php
/**
 * The model file of install module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     install
 * @version     $Id: model.php 5006 2013-07-03 08:52:21Z wyd621@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php
class installModel extends model
{
    /**
     * 获取对应语言项的禅道授权信息。
     * Get license according the client lang.
     *
     * @access public
     * @return string
     */
    public function getLicense(): string
    {
        $clientLang = $this->app->getClientLang();

        $licenseCN = file_get_contents($this->app->getBasePath() . 'LICENSE.CN');
        $licenseEN = file_get_contents($this->app->getBasePath() . 'LICENSE.EN');

        if($clientLang == 'zh-cn' || $clientLang == 'zh-tw') return $licenseCN . $licenseEN;
        return $licenseEN . $licenseCN;
    }

    /**
     * 获取数据库版本。
     * Get database version.
     *
     * @access public
     * @return string
     */
    public function getDatabaseVersion()
    {
        if(empty($this->dbh)) $this->dbh = $this->connectDB();
        if($this->config->db->driver == 'dm') return 8;

        $sql = "SELECT VERSION() AS version";
        $result = $this->dbh->query($sql)->fetch();
        return substr($result->version, 0, 3);
    }

    /**
     * 获取php.ini里的配置信息。
     * Get the php.ini info.
     *
     * @access public
     * @return string
     */
    public function getIniInfo(): string
    {
        $iniInfo = '';

        ob_start();
        phpinfo(1);
        $lines = explode("\n", strip_tags(ob_get_contents()));
        ob_end_clean();

        foreach($lines as $line) if(strpos($line, 'ini') !== false) $iniInfo .= $line . "\n";
        return $iniInfo;
    }

    /**
     * 连接数据库。
     * Connect to database.
     *
     * @access public
     * @return object|string
     */
    public function connectDB(): object|string
    {
        try
        {
            return new dbh($this->config->db, false);
        }
        catch (PDOException $exception)
        {
             return $exception->getMessage();
        }
    }

    /**
     * 创建数据库表。
     * Create tables.
     *
     * @param  string $version
     * @param  bool   $saveLog
     * @param  int    $isClearDB
     * @access public
     * @return bool
     */
    public function createTable(string $version, bool $saveLog = false, int $isClearDB = 0): bool
    {
        /* Add exception handling to ensure that all SQL is executed successfully. */
        try
        {
            $this->dbh->useDB($this->config->db->name);

            $dbCharset = $this->dbh->getDatabaseCharsetAndCollation($this->config->db->name);
            $dbFile    = $this->app->getAppRoot() . 'db' . DS . 'zentao.sql';
            $tables    = explode(';', file_get_contents($dbFile));

            foreach($tables as $table)
            {
                $table = trim($table);
                if(empty($table)) continue;

                if(strpos($table, 'CREATE TABLE') !== false)
                {
                    $table = substr($table, 0, strrpos($table, ')') + 1);
                    if($this->config->db->driver == 'mysql')
                    {
                        $table .= " ENGINE=InnoDB DEFAULT CHARSET={$dbCharset['charset']} COLLATE={$dbCharset['collation']}";
                    }
                }
                elseif(strpos($table, 'DROP TABLE') !== false && $isClearDB)
                {
                    $table = str_replace('--', '', $table);
                }

                $tableToLower = strtolower($table);
                if(strpos($tableToLower, 'fulltext') !== false and strpos($tableToLower, 'innodb') !== false and $version < 5.6)
                {
                    $table = str_replace('ENGINE=InnoDB', 'ENGINE=MyISAM', $table);
                }

                $table = str_replace('__DELIMITER__', ';', $table);
                $table = str_replace('__TABLE__', $this->config->db->name, $table);

                /* Skip sql that is note. */
                if(strpos($table, '--') === 0) continue;

                $prefix = in_array($this->config->db->driver, $this->config->pgsqlDriverList) ? 'public' : $this->config->db->name;

                $table = str_replace('`zt_', $prefix . '.`zt_', $table);
                $table = str_replace('`ztv_', $prefix . '.`ztv_', $table);
                $table = str_replace('zt_', $this->config->db->prefix, $table);

                if($saveLog) file_put_contents($this->buildDBLogFile('progress'), $table . "\n", FILE_APPEND);
                $this->dbh->exec($table);
            }
        }
        catch (PDOException $exception)
        {
            $message = $exception->getMessage();
            if($saveLog) file_put_contents($this->buildDBLogFile('error'), $message);
            echo nl2br($message);
            helper::end();
        }
        return true;
    }

    /**
     * 执行安装前的SQL语句。
     * Exec pre install SQL.
     *
     * @access public
     * @return bool
     */
    public function execPreInstallSQL(): bool
    {
        $this->dbh->useDB($this->config->db->name);

        $tables = array();
        $dbPath = $this->app->getAppRoot() . 'db' . DS;

        if(in_array($this->config->db->driver, $this->config->pgsqlDriverList))
        {
            $dbFile = $dbPath . 'pgsql_function.sql';
            $tables = explode('--', file_get_contents($dbFile));
        }

        foreach($tables as $table)
        {
            $prefix = in_array($this->config->db->driver, $this->config->pgsqlDriverList) ? 'public' : $this->config->db->name;

            $table = trim($table);
            $table = str_replace('`zt_', $prefix . '.`zt_', $table);
            $table = str_replace('`ztv_', $prefix . '.`ztv_', $table);
            if($table) $this->dbh->exec($table);
        }

        return true;
    }

    /**
     * 执行安装后的SQL语句。
     * Exec post install SQL.
     *
     * @access public
     * @return bool
     */
    public function execPostInstallSQL(): bool
    {
        $tables = array();
        $dbPath = $this->app->getAppRoot() . 'db' . DS;

        if($this->config->db->driver == 'dm')
        {
            $dbFile     = $dbPath . 'dm.sql';
            $dbFuncFile = $dbPath . 'dm_function.sql';

            $tables   = explode(';', file_get_contents($dbFile));
            $tables[] = file_get_contents($dbFuncFile);
        }
        elseif(in_array($this->config->db->driver, $this->config->pgsqlDriverList))
        {
            $dbFile = $dbPath . 'pgsql.sql';
            $tables = explode('--', file_get_contents($dbFile));
        }

        foreach($tables as $table)
        {
            $prefix = in_array($this->config->db->driver, $this->config->pgsqlDriverList) ? 'public' : $this->config->db->name;

            $table = trim($table);
            $table = str_replace('`zt_', $prefix . '.`zt_', $table);
            $table = str_replace('`ztv_', $prefix . '.`ztv_', $table);
            if($table) $this->dbh->exec($table);
        }

        return true;
    }

    /**
     * 获取数据库日志存储路径。
     * Build DB log file.
     *
     * @param  string $type config|error|success|progress
     * @access public
     * @return string
     */
    public function buildDBLogFile($type): string
    {
        $cacheRoot = $this->app->getCacheRoot();
        if(!file_exists($cacheRoot)) mkdir($cacheRoot, 0777, true);

        if($type == 'config')   return $cacheRoot . 'db.cnf';
        if($type == 'error')    return $cacheRoot . 'dberror.log';
        if($type == 'success')  return $cacheRoot . 'dbsuccess.log';
        if($type == 'progress') return $cacheRoot . 'dbprogress.log';
    }

    /**
     * 创建公司并设置管理员。
     * Create a comapny, set admin.
     *
     * @param  object $data
     * @access public
     * @return bool
     */
    public function grantPriv(object $data): bool
    {
        /* Check required. */
        if(empty($data->company))  dao::$errors['company'][]  = sprintf($this->lang->error->notempty, $this->lang->install->company);
        if(empty($data->account))  dao::$errors['account'][]  = sprintf($this->lang->error->notempty, $this->lang->install->account);
        if(empty($data->password)) dao::$errors['password'][] = sprintf($this->lang->error->notempty, $this->lang->install->password);
        if(!validater::checkAccount($data->account)) dao::$errors['account'][] = sprintf($this->lang->error->account, $this->lang->user->account);
        if(dao::isError()) return false;

        $this->loadModel('user');
        $this->app->loadConfig('admin');

        /* Check password. */
        if(!validater::checkReg($data->password, '|(.){6,}|'))                       dao::$errors['password'][] = $this->lang->error->passwordrule;
        if($this->user->computePasswordStrength($data->password) < 1)                dao::$errors['password'][] = $this->lang->user->placeholder->passwordStrengthCheck[1];
        if(strpos(",{$this->config->safe->weak},", ",{$data->password},") !== false) dao::$errors['password'][] = sprintf($this->lang->user->errorWeak, $this->config->safe->weak);
        if(dao::isError()) return false;

        /* Insert a company. */
        $company = new stdclass();
        $company->name   = $data->company;
        $company->admins = ",{$data->account},";
        $this->dao->insert(TABLE_COMPANY)->data($company)->autoCheck()->exec();
        if(dao::isError()) return false;

        /* Set admin. */
        $visions = $this->config->edition == 'ipd' ? 'or,rnd,lite' : 'rnd,lite';
        $admin   = new stdclass();
        $admin->account  = $data->account;
        $admin->realname = $data->account;
        $admin->password = md5($data->password);
        $admin->gender   = 'f';
        $admin->visions  = $visions;
        $this->dao->insert(TABLE_USER)->data($admin)->exec();

        return !dao::isError();
    }

    /**
     * 根据当前语言更新数据库中部分数据。
     * Update language for group and cron.
     *
     * @access public
     * @return bool
     */
    public function updateLang(): bool
    {
        /* Update group name and desc on dafault lang. */
        $groups = $this->dao->select('*')->from(TABLE_GROUP)->orderBy('id')->fetchAll();
        foreach($groups as $group)
        {
            $data = zget($this->lang->install->groupList, $group->name, '');
            if($data) $this->dao->update(TABLE_GROUP)->data($data)->where('id')->eq($group->id)->exec();
        }

        /* Update cron remark by lang. */
        foreach($this->lang->install->cronList as $command => $remark)
        {
            $this->dao->update(TABLE_CRON)->set('remark')->eq($remark)->where('command')->eq($command)->exec();
        }

        foreach($this->lang->install->langList as $langInfo)
        {
            $this->dao->update(TABLE_LANG)->set('value')->eq($langInfo['value'])->where('module')->eq($langInfo['module'])->andWhere('`key`')->eq($langInfo['key'])->exec();
        }

        /* Update lang,stage by lang. */
        $this->app->loadLang('stage');
        foreach($this->lang->stage->typeList as $key => $value)
        {
            $this->dao->update(TABLE_LANG)->set('value')->eq($value)->where('`key`')->eq($key)->exec();
            $this->dao->update(TABLE_STAGE)->set('name')->eq($value)->where('`type`')->eq($key)->exec();
        }

        if($this->config->edition != 'open') $this->updateWorkflowLang();
        if($this->config->edition == 'max' || $this->config->edition == 'ipd') $this->updatePaidVersionLang();

        return true;
    }

    /**
     * 根据当前语言更新工作流表的数据。
     * Update language for workflow.
     *
     * @access private
     * @return bool
     */
    private function updateWorkflowLang(): bool
    {
        /* Update flowdatasource by lang. */
        foreach($this->lang->install->workflowdatasource as $id => $name)
        {
            $this->dao->update(TABLE_WORKFLOWDATASOURCE)->set('name')->eq($name)->where('id')->eq($id)->exec();
        }

        /* Update workflowrule by lang. */
        foreach($this->lang->install->workflowrule as $id => $name)
        {
            $this->dao->update(TABLE_WORKFLOWRULE)->set('name')->eq($name)->where('id')->eq($id)->exec();
        }

        return true;
    }

    /**
     * 根据当前语言更新付费版本表的数据。
     * Update language for paid table.
     *
     * @access private
     * @return true
     */
    private function updatePaidVersionLang()
    {
        /* Update process by lang. */
        foreach($this->lang->install->processList as $id => $name)
        {
            $this->dao->update(TABLE_PROCESS)->set('name')->eq($name)->where('id')->eq($id)->exec();
        }

        foreach($this->lang->install->activity as $id => $name)
        {
            $this->dao->update(TABLE_ACTIVITY)->set('name')->eq($name)->where('id')->eq($id)->exec();
        }

        foreach($this->lang->install->zoutput as $id => $name)
        {
            $this->dao->update(TABLE_ZOUTPUT)->set('name')->eq($name)->where('id')->eq($id)->exec();
        }

        /* Update basicmeas by lang. */
        foreach($this->lang->install->basicmeasList as $id => $basic)
        {
            $this->dao->update(TABLE_BASICMEAS)->set('name')->eq($basic['name'])->set('unit')->eq($basic['unit'])->set('definition')->eq($basic['definition'])->where('id')->eq($id)->exec();
        }

        return true;
    }

    /**
     * 导入测试数据。
     * Import demo data.
     *
     * @access public
     * @return bool
     */
    public function importDemoData(): bool
    {
        $demoDataFile = $this->app->clientLang == 'en' ? 'endemo.sql' : 'demo.sql';
        $demoDataFile = $this->app->getAppRoot() . 'db' . DS . $demoDataFile;
        $insertTables = explode(";\n", file_get_contents($demoDataFile));
        foreach($insertTables as $table)
        {
            $table = trim($table);
            if(empty($table)) continue;

            $prefix = in_array($this->config->db->driver, $this->config->pgsqlDriverList) ? 'public' : $this->config->db->name;

            $table = str_replace('`zt_', $prefix . '.`zt_', $table);
            $table = str_replace('zt_', $this->config->db->prefix, $table);
            if(!$this->dbh->query($table)) return false;

            /* Make the deleted user of demo data undeleted.*/
            if($this->config->edition == 'open') $this->dao->update(TABLE_USER)->set('deleted')->eq('0')->where('deleted')->eq('1')->exec();
        }

        $config = new stdclass();
        $config->module  = 'common';
        $config->owner   = 'system';
        $config->section = 'global';
        $config->key     = 'showDemoUsers';
        $config->value   = '1';
        $config->vision  = '';
        $this->dao->replace(TABLE_CONFIG)->data($config)->exec();

        return true;
    }

    /**
     * 导入BI内置数据。
     * Import BI data.
     *
     * @access public
     * @return bool
     */
    public function importBIData()
    {
        $this->loadModel('bi');

        /* Prepare built-in sqls of bi. */

        $insertTables = array();
        if(in_array($this->config->db->driver, $this->config->mysqlDriverList) || in_array($this->config->db->driver, $this->config->pgsqlDriverList))
        {
            $chartSQLs    = $this->bi->prepareBuiltinChartSQL();
            $pivotSQLs    = $this->bi->prepareBuiltinPivotSQL();
            $insertTables = array_merge($insertTables, $chartSQLs, $pivotSQLs);
        }

        $metricSQLs   = $this->bi->prepareBuiltinMetricSQL();
        $screenSQLs   = $this->bi->prepareBuiltinScreenSQL();
        $insertTables = array_merge($insertTables, $metricSQLs, $screenSQLs);

        try
        {
            foreach($insertTables as $table)
            {
                $table = trim($table);
                if(empty($table)) continue;

                $table = str_replace('zt_', $this->config->db->prefix, $table);
                if(!$this->dbh->query($table)) return false;
            }
        }
        catch(Error $e)
        {
            a($e->getMessage());
            die;
        }

        return true;
    }

    /**
     * 开启缓存。
     * Enable cache.
     *
     * @access public
     * @return bool
     */
    public function enableCache(): bool
    {
        if(!helper::isAPCuEnabled()) return false;

        $cache = new stdclass();
        $cache->enable    = true;
        $cache->driver    = 'apcu';
        $cache->scope     = 'shared';
        $cache->namespace = $this->config->db->name;

        $this->loadModel('setting')->setItems('system.common.cache', $cache);
        if(dao::isError()) return false;

        $this->mao->clearCache();

        return true;
    }
}
