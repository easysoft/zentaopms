<?php
declare(strict_types=1);
/**
 * The zen file of install module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang<wangyuting@easycorp.ltd>
 * @package     install
 * @link        https://www.zentao.net
 */
class installZen extends install
{
    /**
     * 获取当前PHP版本。
     * get php version.
     *
     * @access protected
     * @return string
     */
    protected function getPHPVersion(): string
    {
        return PHP_VERSION;
    }

    /**
     * 获取tmp的目录信息。
     * Get tempRoot info.
     *
     * @access protected
     * @return array
     */
    protected function getTmpRoot(): array
    {
        $result['path']     = $this->app->getTmpRoot();
        $result['exists']   = is_dir($result['path']);
        $result['writable'] = is_writable($result['path']);
        return $result;
    }

    /**
     * 获取session的存储目录信息。
     * Get session save path.
     *
     * @access protected
     * @return array
     */
    protected function getSessionSavePath(): array
    {
        $result['path']     = preg_replace("/\d;/", '', session_save_path());
        $result['exists']   = is_dir($result['path']);
        $result['writable'] = is_writable($result['path']);
        return $result;
    }

    /**
     * 获取附件存储的目录信息。
     * Get data root.
     *
     * @access protected
     * @return array
     */
    protected function getDataRoot(): array
    {
        $result['path']    = $this->app->getAppRoot() . 'www' . DS . 'data';
        $result['exists']  = is_dir($result['path']);
        $result['writable']= is_writable($result['path']);
        return $result;
    }

    /**
     * 检查PHP版本是否大于5.2.0。
     * Check php version.
     *
     * @access protected
     * @return string    ok|fail
     */
    protected function checkPHPVersion(): string
    {
        return version_compare(PHP_VERSION, '5.2.0') >= 0 ? 'ok' : 'fail';
    }

    /**
     * 检查是否安装pdo扩展。
     * Check PDO.
     *
     * @access protected
     * @return string    ok|fail
     */
    protected function checkPDO(): string
    {
        return extension_loaded('pdo') ? 'ok' : 'fail';
    }

    /**
     * 检查是否安装pdo_mysql扩展。
     * Check PDO::MySQL
     *
     * @access protected
     * @return string    ok|fail
     */
    protected function checkPDOMySQL(): string
    {
        return extension_loaded('pdo_mysql') ? 'ok' : 'fail';
    }

    /**
     * 检查是否安装了json扩展。
     * Check json extension.
     *
     * @access protected
     * @return string    ok|fail
     */
    protected function checkJSON(): string
    {
        return extension_loaded('json') ? 'ok' : 'fail';
    }

    /**
     * 检查是否安装了openssl扩展。
     * Check openssl extension.
     *
     * @access protected
     * @return string    ok|fail
     */
    protected function checkOpenssl(): string
    {
        return extension_loaded('openssl') ? 'ok' : 'fail';
    }

    /**
     * 检查是否安装了mbstring扩展。
     * Check mbstring extension.
     *
     * @access protected
     * @return string    ok|fail
     */
    protected function checkMBstring(): string
    {
        return extension_loaded('mbstring') ? 'ok' : 'fail';
    }

    /**
     * 检查是否安装了zlib扩展。
     * Check zlib extension.
     *
     * @access protected
     * @return string    ok|fail
     */
    protected function checkZlib(): string
    {
        return extension_loaded('zlib') ? 'ok' : 'fail';
    }

    /**
     * 检查是否安装了curl扩展。
     * Check curl extension.
     *
     * @access protected
     * @return string    ok|fail
     */
    protected function checkCURL(): string
    {
        return extension_loaded('curl') ? 'ok' : 'fail';
    }

    /**
     * 检查是否安装了filter扩展。
     * Check filter extension.
     *
     * @access protected
     * @return string    ok|fail
     */
    protected function checkFilter(): string
    {
        return extension_loaded('filter') ? 'ok' : 'fail';
    }

    /**
     * 检查是否安装了iconv扩展。
     * Check iconv extension.
     *
     * @access protected
     * @return string    ok|fail
     */
    protected function checkIconv(): string
    {
        return extension_loaded('iconv') ? 'ok' : 'fail';
    }

    /**
     * 检查tmp目录的完整性和可写性。
     * Check tmpRoot.
     *
     * @access protected
     * @return string    ok|fail
     */
    protected function checkTmpRoot(): string
    {
        $tmpRoot = $this->app->getTmpRoot();
        return is_dir($tmpRoot) && is_writable($tmpRoot) ? 'ok' : 'fail';
    }

    /**
     * 检查session存储目录的完整性和可写性质。
     * Check session save path.
     *
     * @access protected
     * @return string    ok|fail
     */
    protected function checkSessionSavePath(): string
    {
        $sessionSavePath = preg_replace("/\d;/", '', session_save_path());
        if(!is_dir($sessionSavePath) || !is_writable($sessionSavePath)) return 'fail';

        /* Test session path again. */
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
     * 检查附件存储目录的完整性和可写性质。
     * Check the data root.
     *
     * @access protected
     * @return string    ok|fail
     */
    protected function checkDataRoot(): string
    {
        $dataRoot = $this->app->getAppRoot() . 'www' . DS . 'data';
        return is_dir($dataRoot) && is_writable($dataRoot) ? 'ok' : 'fail';
    }

    /**
     * 检查数据库配置信息的正确性。
     * Check config ok or not.
     *
     * @param  object $data
     * @access protected
     * @return object
     */
    protected function checkConfig(object $data): object
    {
        $return = new stdclass();
        $return->result = 'ok';

        /* Connect to database. */
        $this->setDBParam($data);
        $this->install->dbh = $this->install->connectDB();
        if(strpos($data->dbName, '.') !== false)
        {
            /* 如果数据库名字带有.字符的话，则提示错误信息。 */
            $return->result = 'fail';
            $return->error  = $this->lang->install->errorDBName;
            return $return;
        }
        if(!is_object($this->install->dbh))
        {
            /* 没有成功连接数据库的话，则提示错误信息。 */
            $return->result = 'fail';
            $return->error  = $this->lang->install->errorConnectDB . $this->install->dbh;
            return $return;
        }

        /* Get database version. */
        $version = $this->install->getDatabaseVersion();

        /* If database no exits, try create it. */
        if(!$this->install->dbh->dbExists())
        {
            if(!$this->install->dbh->createDB($version))
            {
                /* 如果创建数据库失败的话，则提示错误信息。 */
                $return->result = 'fail';
                $return->error  = $this->lang->install->errorCreateDB;
                return $return;
            }
        }
        elseif($this->install->dbh->tableExits(TABLE_CONFIG) && empty($data->clearDB))
        {
            /* 如果已经存在config表，并且用户没有勾选清空旧数据库选项的话，则提示错误信息。 */
            $return->result = 'fail';
            $return->error  = $this->lang->install->errorTableExists;
            return $return;
        }

        return $return;
    }

    /**
     * DevOps平台版将配置信息写入my.php。
     * Save config file when inQuickon is true.
     *
     * @access protected
     * @return bool
     */
    protected function saveConfigFile(): bool
    {
        $configRoot   = $this->app->getConfigRoot();
        $myConfigFile = $configRoot . 'my.php';
        if(file_exists($myConfigFile) && trim(file_get_contents($myConfigFile))) return false;

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
        \$config->requestType     = 'PATH_INFO';
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

        return true;
    }

    /**
     * 写入数据库配置信息。
     * Set database params.
     *
     * @param  object $data
     * @access private
     * @return bool
     */
    private function setDBParam(object $data): bool
    {
        $this->config->db->driver = $data->dbDriver;
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
            $this->config->db->host     = $data->dbHost;
            $this->config->db->user     = $data->dbUser;
            $this->config->db->encoding = $data->dbEncoding;
            $this->config->db->password = $data->dbPassword;
            $this->config->db->port     = $data->dbPort;
        }
        $this->config->db->name   = $data->dbName;
        $this->config->db->prefix = $data->dbPrefix;

        file_put_contents($this->install->buildDBLogFile('config'), json_encode(array('db' => $this->config->db, 'post' => $data)));

        return true;
    }

    /**
     * DevOps平台版设置session path。
     * Set session save path.
     *
     * @access private
     * @return bool
     */
    private function setSessionPath(): bool
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

    /**
     * 处理安装应用下拉选择的数据。
     * Process application options.
     *
     * @param  object    $components
     * @param  object    $cloudSolution
     * @access protected
     * @return object
     */
    protected function processComponents(object $components, object $cloudSolution): object
    {
        foreach($components->category as $key => &$item)
        {
            if($item->name === 'pms')
            {
                unset($components->category[$key]);
                continue;
            }

            if(in_array($item->name, array('analysis', 'artifact'))) array_unshift($item->choices, (object)array('name' => $this->lang->install->solution->skipInstall, 'version' => ''));

            $item->schemaChoices = array();
            foreach($item->choices as $cloudApp)
            {
                $appInfo = zget($cloudSolution->apps, $cloudApp->name, array());
                $item->schemaChoices[$cloudApp->name] = zget($appInfo, 'alias', $cloudApp->name);
            }
        }

        return $components;
    }
}
