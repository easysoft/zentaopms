<?php
/**
 * The model file of install module of ZenTaoMS.
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
 * @package     install
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php
class installModel extends model
{
    /* 检查禅道的版本。*/
    public function checkZenTaoVersion()
    {
    }

    /* 获得PHP版本。*/
    public function getPhpVersion()
    {
        return PHP_VERSION;
    }

    /* 检查PHP版本是否符合要求。*/
    public function checkPHP()
    {
        return $result = version_compare(PHP_VERSION, '5.2.0') >= 0 ? 'ok' : 'fail';
    }

    /* 检查PDO扩展是否载入。*/
    public function checkPDO()
    {
        return $result = extension_loaded('pdo') ? 'ok' : 'fail';
    }

    /* 检查PDO_MySQL扩展是否载入。*/
    public function checkPDOMySQL()
    {
        return $result = extension_loaded('pdo_mysql') ? 'ok' : 'fail';
    }

    /* 获得tmpRoot目录的信息。*/
    public function getTmpRoot()
    {
        $result['path']    = $this->app->getTmpRoot();
        $result['exists']  = is_dir($result['path']);
        $result['writable']= is_writable($result['path']);
        return $result;
    }

    /* 检查tmpRoot目录权限。*/
    public function checkTmpRoot()
    {
        $tmpRoot = $this->app->getTmpRoot();
        return $result = (is_dir($tmpRoot) and is_writable($tmpRoot)) ? 'ok' : 'fail';
    }

    /* 获得DataRoot目录的信息。*/
    public function getDataRoot()
    {
        $result['path']    = $this->app->getAppRoot() . 'www' . $this->app->getPathFix() . 'data';
        $result['exists']  = is_dir($result['path']);
        $result['writable']= is_writable($result['path']);
        return $result;
    }

    /* 检查dataRoot目录权限。*/
    public function checkDataRoot()
    {
        $dataRoot = $this->app->getAppRoot() . 'www' . $this->app->getPathFix() . 'data';
        return $result = (is_dir($dataRoot) and is_writable($dataRoot)) ? 'ok' : 'fail';
    }

    /* 获得INI文件的信息。*/
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

    /* 获得webRoot的地址。*/
    public function getWebRoot()
    {
        return rtrim(str_replace('\\', '/', pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_DIRNAME)), '/') . '/';
    }

    /* 检查配置。*/
    public function checkConfig()
    {
        $return->result = 'ok';

        /* 连接到数据库。*/
        $this->setDBParam();
        $this->dbh = $this->connectDB();
        if(!is_object($this->dbh))
        {
            $return->result = 'fail';
            $return->error  = $this->lang->install->errorConnectDB . $this->dbh;
            return $return;
        }

        /* 获得数据库版本。*/
        $version = $this->getMysqlVersion();

        /* 数据库不存在，尝试建之。*/
        if(!$this->dbExists())
        {
            if(!$this->createDB($version))
            {
                $return->result = 'fail';
                $return->error  = $this->lang->install->errorCreateDB;
                return $return;
            }
        }

        /* 创建表。*/
        if(!$this->createTable($version))
        {
            $return->result = 'fail';
            $return->error  = $this->lang->install->errorCreateTable;
            return $return;
        }
        return $return;
    }

    /* 设置数据库参数。*/
    public function setDBParam()
    {
        $this->config->db->host     = $this->post->dbHost;
        $this->config->db->name     = $this->post->dbName;
        $this->config->db->user     = $this->post->dbUser;
        $this->config->db->password = $this->post->dbPassword;
        $this->config->db->port     = $this->post->dbPort;
        $this->config->db->prefix   = $this->post->dbPrefix;

    }
    /* 连接到数据库。*/
    public function connectDB()
    {
        $dsn = "mysql:host={$this->config->db->host}; port={$this->config->db->port}";
        try 
        {
            $dbh = new PDO($dsn, $this->config->db->user, $this->config->db->password);
            $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->exec("SET NAMES {$this->config->db->encoding}");
            return $dbh;
        }
        catch (PDOException $exception)
        {
             return $exception->getMessage();
        }
    }

    /* 判断数据库是否存在。*/
    public function dbExists()
    {
        $sql = "SHOW DATABASES like '{$this->config->db->name}'";
        return $this->dbh->query($sql)->fetch();
    }

    /* 获得mysql的版本号。*/
    public function getMysqlVersion()
    {
        $sql = "SELECT VERSION() AS version";
        $result = $this->dbh->query($sql)->fetch();
        return substr($result->version, 0, 3);
    }

    /* 创建数据库。*/
    public function createDB($version)
    {
        $sql = "CREATE DATABASE `{$this->config->db->name}`";
        if($version > 4.1) $sql .= " DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
        return $this->dbh->query($sql);
    }

    /* 创建表。*/
    public function createTable($version)
    {
        $dbFile = $this->app->getAppRoot() . 'db' . $this->app->getPathFix() . 'zentao.sql';
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
            $table = str_replace('`zt_', $this->config->db->name . '.`zt_', $table);
            $table = str_replace('zt_', $this->config->db->prefix, $table);
            if(!$this->dbh->query($table)) return false;
        }
        return true;
    }

    /* 生成公司，设立管理员帐号。*/
    public function grantPriv()
    {
        if($this->post->password == '') die(js::error($this->lang->install->errorEmptyPassword));
        $admin->account  = $this->post->account;
        $admin->realname = $this->post->account;
        $admin->password = md5($this->post->password);
        $this->dao->replace(TABLE_USER)->data($admin)->autoCheck()->check('account', 'notempty')->exec();
        if(!dao::isError())
        {
            $company->name = $this->post->company;
            $company->pms  = $this->post->pms;
            $company->admins = ",$admin->account,";
            $this->dao->replace(TABLE_COMPANY)->data($company)->autoCheck()->batchCheck('name, pms', 'notempty')->exec();
            if(!dao::isError())
            {
                $companyID = $this->dbh->lastInsertID();
                $this->dao->update(TABLE_USER)->set('company')->eq($companyID)->where('account')->eq($admin->account)->limit(1)->exec();
            }
        }
    }
}
