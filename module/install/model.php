<?php
/**
 * The model file of install module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     install
 * @version     $Id: model.php 5006 2013-07-03 08:52:21Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class installModel extends model
{
    /**
     * Get license according the client lang.
     *
     * @access public
     * @return string
     */
    public function getLicense()
    {
        $clientLang = $this->app->getClientLang();

        $licenseCN = file_get_contents($this->app->getBasePath() . 'doc/LICENSE.CN');
        $licenseEN = file_get_contents($this->app->getBasePath() . 'doc/LICENSE.EN');

        if($clientLang == 'zh-cn' or $clientLang == 'zh-tw') return $licenseCN . $licenseEN;
        return $licenseEN . $licenseCN;
    }

    /**
     * Check version of zentao.
     *
     * @access public
     * @return void
     */
    public function checkZenTaoVersion()
    {
    }

    /**
     * get php version.
     *
     * @access public
     * @return string
     */
    public function getPhpVersion()
    {
        return PHP_VERSION;
    }

    /**
     * Check php version.
     *
     * @access public
     * @return string   ok|fail
     */
    public function checkPHP()
    {
        return $result = version_compare(PHP_VERSION, '5.2.0') >= 0 ? 'ok' : 'fail';
    }

    /**
     * Check PDO.
     *
     * @access public
     * @return string   ok|fail
     */
    public function checkPDO()
    {
        return $result = extension_loaded('pdo') ? 'ok' : 'fail';
    }

    /**
     * Check PDO::MySQL
     *
     * @access public
     * @return string   ok|fail
     */
    public function checkPDOMySQL()
    {
        return $result = extension_loaded('pdo_mysql') ? 'ok' : 'fail';
    }

    /**
     * Check json extension.
     *
     * @access public
     * @return string   ok|fail
     */
    public function checkJSON()
    {
        return $result = extension_loaded('json') ? 'ok' : 'fail';
    }

    /**
     * Check openssl extension.
     *
     * @access public
     * @return string   ok|fail
     */
    public function checkOpenssl()
    {
        return $result = extension_loaded('openssl') ? 'ok' : 'fail';
    }

    /**
     * Check mbstring extension.
     *
     * @access public
     * @return string   ok|fail
     */
    public function checkMbstring()
    {
        return $result = extension_loaded('mbstring') ? 'ok' : 'fail';
    }

    /**
     * Check zlib extension.
     *
     * @access public
     * @return string   ok|fail
     */
    public function checkZlib()
    {
        return $result = extension_loaded('zlib') ? 'ok' : 'fail';
    }

    /**
     * Check curl extension.
     *
     * @access public
     * @return string   ok|fail
     */
    public function checkCurl()
    {
        return $result = extension_loaded('curl') ? 'ok' : 'fail';
    }

    /**
     * Check filter extension.
     *
     * @access public
     * @return string   ok|fail
     */
    public function checkFilter()
    {
        return $result = extension_loaded('filter') ? 'ok' : 'fail';
    }

    /**
     * Check iconv extension.
     *
     * @access public
     * @return string   ok|fail
     */
    public function checkIconv()
    {
        return $result = extension_loaded('iconv') ? 'ok' : 'fail';
    }

    /**
     * Get tempRoot info.
     *
     * @access public
     * @return array
     */
    public function getTmpRoot()
    {
        $result['path']     = $this->app->getTmpRoot();
        $result['exists']   = is_dir($result['path']);
        $result['writable'] = is_writable($result['path']);
        return $result;
    }

    /**
     * Check tmpRoot.
     *
     * @access public
     * @return string   ok|fail
     */
    public function checkTmpRoot()
    {
        $tmpRoot = $this->app->getTmpRoot();
        return $result = (is_dir($tmpRoot) and is_writable($tmpRoot)) ? 'ok' : 'fail';
    }

    /**
     * Get session save path.
     *
     * @access public
     * @return array
     */
    public function getSessionSavePath()
    {
        $result['path']     = preg_replace("/\d;/", '', session_save_path());
        $result['exists']   = is_dir($result['path']);
        $result['writable'] = is_writable($result['path']);
        return $result;
    }

    /**
     * Check session save path.
     *
     * @access public
     * @return string
     */
    public function checkSessionSavePath()
    {
        $sessionSavePath = preg_replace("/\d;/", '', session_save_path());
        $result = (is_dir($sessionSavePath) and is_writable($sessionSavePath)) ? 'ok' : 'fail';
        if($result == 'fail') return $result;

        /* Test session path again. Fix bug #1527. */
        file_put_contents($sessionSavePath . '/zentaotest', 'zentao');
        $sessionContent = file_get_contents($sessionSavePath . '/zentaotest');
        if($sessionContent == 'zentao')
        {
            unlink($sessionSavePath . '/zentaotest');
            return 'ok';
        }
        return 'fail';
    }

    /**
     * Get data root
     *
     * @access public
     * @return array
     */
    public function getDataRoot()
    {
        $result['path']    = $this->app->getAppRoot() . 'www' . DS . 'data';
        $result['exists']  = is_dir($result['path']);
        $result['writable']= is_writable($result['path']);
        return $result;
    }

    /**
     * Check the data root.
     *
     * @access public
     * @return string ok|fail
     */
    public function checkDataRoot()
    {
        $dataRoot = $this->app->getAppRoot() . 'www' . DS . 'data';
        return $result = (is_dir($dataRoot) and is_writable($dataRoot)) ? 'ok' : 'fail';
    }

    /**
     * Get the php.ini info.
     *
     * @access public
     * @return string
     */
    public function getIniInfo()
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
     * Check config ok or not.
     *
     * @access public
     * @return array
     */
    public function checkConfig()
    {
        $return = new stdclass();
        $return->result = 'ok';

        /* Connect to database. */
        $this->setDBParam();
        $this->dbh = $this->connectDB();
        if(strpos($this->post->dbName, '.') !== false)
        {
            $return->result = 'fail';
            $return->error  = $this->lang->install->errorDBName;
            return $return;
        }
        if(!is_object($this->dbh))
        {
            $return->result = 'fail';
            $return->error  = $this->lang->install->errorConnectDB . $this->dbh;
            return $return;
        }

        /* Get database version. */
        $version = $this->getDatabaseVersion();

        /* If database no exits, try create it. */
        if(!$this->dbh->dbExists())
        {
            if(!$this->dbh->createDB($version))
            {
                $return->result = 'fail';
                $return->error  = $this->lang->install->errorCreateDB;
                return $return;
            }
        }
        elseif($this->dbh->tableExits(TABLE_CONFIG) and $this->post->clearDB == false)
        {
            $return->result = 'fail';
            $return->error  = $this->lang->install->errorTableExists;
            return $return;
        }

        return $return;
    }

    /**
     * Set database params.
     *
     * @access public
     * @return void
     */
    public function setDBParam()
    {
        $this->config->db->driver   = $this->post->dbDriver;
        if($this->config->inQuickon)
        {
            $this->config->db->host     = getenv('ZT_MYSQL_HOST');
            $this->config->db->user     = getenv('ZT_MYSQL_USER');
            $this->config->db->encoding = 'UTF8';
            $this->config->db->password = getenv('ZT_MYSQL_PASSWORD');
            $this->config->db->port     = getenv('ZT_MYSQL_PORT');
        }
        else
        {
            $this->config->db->host     = $this->post->dbHost;
            $this->config->db->user     = $this->post->dbUser;
            $this->config->db->encoding = $this->post->dbEncoding;
            $this->config->db->password = $this->post->dbPassword;
            $this->config->db->port     = $this->post->dbPort;
        }
        $this->config->db->name     = $this->post->dbName;
        $this->config->db->prefix   = $this->post->dbPrefix;

        file_put_contents($this->buildDBLogFile('config'), json_encode(array('db' => $this->config->db, 'post' => $_POST)));
    }

    /**
     * Connect to database.
     *
     * @access public
     * @return object
     */
    public function connectDB()
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
     * Get database version.
     *
     * @access public
     * @return string
     */
    public function getDatabaseVersion()
    {
        if($this->config->db->driver != 'mysql') return 8;
        if(empty($this->dbh)) $this->dbh = $this->connectDB();

        $sql = "SELECT VERSION() AS version";
        $result = $this->dbh->query($sql)->fetch();
        return substr($result->version, 0, 3);
    }

    /**
     * Create tables.
     *
     * @param  string    $version
     * @param  bool      $saveLog
     * @access public
     * @return bool
     */
    public function createTable($version, $saveLog = false)
    {
        /* Add exception handling to ensure that all SQL is executed successfully. */
        try
        {
            $this->dbh->useDB($this->config->db->name);

            $dbFile = $this->app->getAppRoot() . 'db' . DS . 'zentao.sql';
            $tables = explode(';', file_get_contents($dbFile));

            foreach($tables as $table)
            {
                $table = trim($table);
                if(empty($table)) continue;

                if(strpos($table, 'CREATE') !== false and $version <= 4.1)
                {
                    $table = str_replace('DEFAULT CHARSET=utf8', '', $table);
                }
                elseif(strpos($table, 'DROP') !== false and $this->post->clearDB != false)
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

                $table = str_replace('`zt_', $this->config->db->name . '.`zt_', $table);
                $table = str_replace('`ztv_', $this->config->db->name . '.`ztv_', $table);
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
     * Exec dm.sql.
     *
     * @access public
     * @return bool
     */
    public function execDMSQL()
    {
        $dbFile = $this->app->getAppRoot() . 'db' . DS . 'dm.sql';
        $tables = explode(';', file_get_contents($dbFile));

        foreach($tables as $table) $this->dbh->exec($table);
    }

    /**
     * Build DB log file.
     *
     * @param  string    $type config|error|success|progress
     * @access public
     * @return string
     */
    public function buildDBLogFile($type)
    {
        if($type == 'config')   return $this->app->getCacheRoot() . 'db.cnf';
        if($type == 'error')    return $this->app->getCacheRoot() . 'dberror.log';
        if($type == 'success')  return $this->app->getCacheRoot() . 'dbsuccess.log';
        if($type == 'progress') return $this->app->getCacheRoot() . 'dbprogress.log';
    }

    /**
     * Create a comapny, set admin.
     *
     * @access public
     * @return void
     */
    public function grantPriv()
    {
        $data = fixer::input('post')
            ->stripTags('company')
            ->get();

        $requiredFields = explode(',', $this->config->install->step5RequiredFields);
        foreach($requiredFields as $field)
        {
            if(empty($data->{$field}))
            {
                dao::$errors[$field][] = $this->lang->install->errorEmpty[$field];
            }
        }
        if(dao::isError()) return false;

        $this->loadModel('user');
        $this->app->loadConfig('admin');
        /* Check password. */
        if(!validater::checkReg($this->post->password, '|(.){6,}|')) dao::$errors['password'][] = $this->lang->error->passwordrule;
        if($this->user->computePasswordStrength($this->post->password) < 1) dao::$errors['password'][] = $this->lang->user->placeholder->passwordStrengthCheck[1];
        if(!isset($this->config->safe->weak)) $this->app->loadConfig('admin');
        if(strpos(",{$this->config->safe->weak},", ",{$this->post->password},") !== false) dao::$errors['password'] = sprintf($this->lang->user->errorWeak, $this->config->safe->weak);
        if(dao::isError()) return false;

        /* Insert a company. */
        $company = new stdclass();
        $company->name   = $data->company;
        $company->admins = ",{$this->post->account},";
        $this->dao->insert(TABLE_COMPANY)->data($company)->autoCheck()->exec();
        if(!dao::isError())
        {
            $visions = $this->config->edition == 'ipd' ? 'or,rnd,lite' : 'rnd,lite';

            /* Set admin. */
            $admin = new stdclass();
            $admin->account  = $this->post->account;
            $admin->realname = $this->post->account;
            $admin->password = md5($this->post->password);
            $admin->gender   = 'f';
            $admin->visions  = $visions;
            $this->dao->replace(TABLE_USER)->data($admin)->exec();
        }
    }

    /**
     * Update language for group and cron.
     *
     * @access public
     * @return void
     */
    public function updateLang()
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

        if($this->config->edition != 'open')
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
        }

        if($this->config->edition == 'max' or $this->config->edition == 'ipd')
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
        }
    }

    /**
     * Import demo data.
     *
     * @access public
     * @return void
     */
    public function importDemoData()
    {
        $demoDataFile = $this->app->clientLang == 'en' ? 'endemo.sql' : 'demo.sql';
        $demoDataFile = $this->app->getAppRoot() . 'db' . DS . $demoDataFile;
        $insertTables = explode(";\n", file_get_contents($demoDataFile));
        foreach($insertTables as $table)
        {
            $table = trim($table);
            if(empty($table)) continue;

            $table = str_replace('`zt_', $this->config->db->name . '.`zt_', $table);
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
        $this->dao->replace(TABLE_CONFIG)->data($config)->exec();
        return true;
    }

    /**
     * DevOps平台版将配置信息写入my.php。
     * Save config file when inQuickon is true.
     *
     * @access public
     * @return void
     */
    public function saveConfigFile()
    {
        $configRoot   = $this->app->getConfigRoot();
        $myConfigFile = $configRoot . 'my.php';
        if(file_exists($myConfigFile) && trim(file_get_contents($myConfigFile))) return;

        /* Set the session save path when the session save path is null. */
        $customSession = $this->setSessionPath();

        $dbHost      = getenv('MYSQL_HOST');
        $dbPort      = getenv('MYSQL_PORT');
        $dbName      = getenv('MYSQL_DB');
        $dbUser      = getenv('MYSQL_USER');
        $dbPassword  = getenv('MYSQL_PASSWORD');
        $timezone    = getenv('ZT_TZ');
        $defaultLang = getenv('ZT_LANG');
        if(empty($timezone))    $timezone    = $this->config->timezone;
        if(empty($defaultLang)) $defaultLang = $this->config->default->lang;
        $configContent = <<<EOT
        <?php
        \$config->installed       = true;
        \$config->debug           = false;
        \$config->requestType     = '{$this->config->requestType}';
        \$config->timezone        = '$timezone';
        \$config->db->driver      = '{$this->config->db->driver}';
        \$config->db->host        = '$dbHost';
        \$config->db->port        = '$dbPort';
        \$config->db->name        = '$dbName';
        \$config->db->user        = '$dbUser';
        \$config->db->encoding    = '{$this->config->db->encoding}';
        \$config->db->password    = '$dbPassword';
        \$config->db->prefix      = '{$this->config->db->prefix}';
        \$config->webRoot         = getWebRoot();
        \$config->default->lang   = '$defaultLang';
        EOT;

        if($customSession) $configContent .= "\n\$config->customSession = true;";

        if(is_writable($configRoot)) @file_put_contents($myConfigFile, $configContent);
        $this->config->installed = true;
    }

    /**
     * DevOps平台版设置session path。
     * Set session save path.
     *
     * @access public
     * @return bool
     */
    public function setSessionPath()
    {
        $customSession = false;
        $checkSession  = ini_get('session.save_handler') == 'files';
        if($checkSession)
        {
            if(!session_save_path())
            {
                /* Restart the session because the session save path is null when start the session last time. */
                session_write_close();

                $tmpRootInfo     = $this->getTmpRoot();
                $sessionSavePath = $tmpRootInfo['path'] . 'session';
                if(!is_dir($sessionSavePath)) mkdir($sessionSavePath, 0777, true);

                session_save_path($sessionSavePath);
                $customSession = true;

                $sessionResult = $this->checkSessionSavePath();
                if($sessionResult == 'fail') chmod($sessionSavePath, 0777);

                session_start();
                $this->session->set('installing', true);
            }
        }

        return $customSession;
    }
}
