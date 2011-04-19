<?php
/**
 * The model file of extension module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2011 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     extension
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class extensionModel extends model
{
    /**
     * The api agent(use snoopy).
     * 
     * @var object   
     * @access public
     */
    public $agent;

    /**
     * The api root.
     * 
     * @var string
     * @access public
     */
    public $apiRoot;

    /**
     * The construct function.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->setAgent();
        $this->setApiRoot();
    }

    /**
     * Set the api agent.
     * 
     * @access public
     * @return void
     */
    public function setAgent()
    {
        $this->agent = $this->app->loadClass('snoopy');
    }

    /**
     * Set the apiRoot.
     * 
     * @access public
     * @return void
     */
    public function setApiRoot()
    {
        $this->apiRoot = $this->config->extension->apiRoot;
    }

    public function getLocalExtensions($status)
    {
        return $this->dao->findByStatus($status)->from(TABLE_EXTENSION)->fetchAll();
    }

    /**
     * Fetch data from an api.
     * 
     * @param  string    $url 
     * @access public
     * @return mixed
     */
    public function fetchAPI($url)
    {
        $this->agent->fetch($url);
        $result = json_decode($this->agent->results);
        if(!isset($result->status)) return false;
        if($result->status != 'success') return false;
        if(isset($result->data) and md5($result->data) != $result->md5) return false;
        if(isset($result->data)) return json_decode($result->data);
    }

    /**
     * Get extension modules from the api.
     * 
     * @access public
     * @return void
     */
    public function getModulesByAPI()
    {
        $requestType = $this->config->requestType;
        $webRoot     = helper::safe64Encode($this->config->webRoot);
        $apiURL      = $this->apiRoot . 'apiGetmodules-' . $requestType . '-' . $webRoot . '.json';
        $data = $this->fetchAPI($apiURL);
        if(isset($data->modules)) return $data->modules;
        return false;
    }

    /**
     * Get extensions by some condition.
     * 
     * @param  string    $type 
     * @param  mixe d    $param 
     * @access public
     * @return void
     */
    public function getExtensionsByAPI($type, $param)
    {
        $apiURL = $this->apiRoot . 'apiGetExtensions-' . $type . '-' . $param . '.json';
        $data = $this->fetchAPI($apiURL);
        if(isset($data->extensions)) return $data;
        return false;
    }

    /**
     * Get the full path of the zip file of a extension. 
     * 
     * @param  string    $extension 
     * @access public
     * @return string
     */
    public function getPackageFile($extension)
    {
        return $this->app->getTmpRoot() . 'extension/' . $extension . '.zip';
    }

    /**
     * Check the download path.
     * 
     * @access public
     * @return object   the check result.
     */
    public function checkDownloadPath()
    {
        /* Init the return. */
        $return->result = 'ok';
        $return->error  = '';

        $tmpRoot = $this->app->getTmpRoot();
        $downloadPath = $tmpRoot . 'extension';

        if(!is_dir($downloadPath))
        {
            if(is_writable($tmpRoot))
            {
                mkdir($downloadPath);
            }
            else
            {
                $return->result = 'fail';
                $return->error  = sprintf($this->lang->extension->errorDownloadPathNotFound, $downloadPath, $downloadPath);
            }
        }
        elseif(!is_writable($downloadPath))
        {
            $return->result = 'fail';
            $return->error  = sprintf($this->lang->extension->errorDownloadPathNotWritable, $downloadPath, $downloadPath);
        }
        return $return;
    }

    /**
     * Check extension files.
     * 
     * @param  string    $extension 
     * @access public
     * @return object    the check result.
     */
    public function checkExtensionPathes($extension)
    {
        $return->result        = 'ok';
        $return->errors        = '';
        $return->mkdirCommands = '';
        $return->chmodCommands = '';

        $appRoot = $this->app->getAppRoot();
        $pathes  = $this->extractPathesFromPackage($extension);
        foreach($pathes as $path)
        {
            if($path == 'db' or $path == 'doc') continue;
            $path = $appRoot . $path;
            if(is_dir($path))
            {
                if(!is_writable($path))
                {
                    $return->errors .= sprintf($this->lang->extension->errorTargetPathNotWritable, $path) . '<br />';
                    $return->chmodCommands .= "sudo chmod -R 777 $path<br />";
                }
            }
            elseif(!is_writable(dirname($path)))
            {
                $return->errors .= sprintf($this->lang->extension->errorTargetPathNotExists, $path) . '<br />';
                $return->mkdirCommands .= "mkdir $path<br />";
                $return->chmodCommands .= "sudo chmod -R 777 $path<br />";
            }
        }

        if($return->errors) $return->result = 'fail';
        $return->mkdirCommands = str_replace('/', DIRECTORY_SEPARATOR, $return->mkdirCommands);
        $return->errors .= $this->lang->extension->executeCommands . $return->mkdirCommands;
        if(PHP_OS == 'Linux') $return->errors .= $return->chmodCommands;
        return $return;
    }

    /**
     * Download an extension.
     * 
     * @param  string    $extension 
     * @param  string    $downLink 
     * @access public
     * @return void
     */
    public function downloadPackage($extension, $downLink)
    {
        $packageFile = $this->getPackageFile($extension);
        $this->agent->fetch($downLink);
        file_put_contents($packageFile, $this->agent->results);
    }

    /**
     * Extract an extension.
     * 
     * @param  string    $extension 
     * @access public
     * @return void
     */
    public function extractPackage($extension)
    {
        /* Extract the zip file. */
        $packageFile = $this->getPackageFile($extension);
        $this->app->loadClass('pclzip', true);
        $zip = new pclzip($packageFile);
        $files = $zip->listContent();
        $removePath = $files[0]['filename'];
        $zip->extract(PCLZIP_OPT_PATH, "ext/$extension", PCLZIP_OPT_REMOVE_PATH, $removePath);
    }

    /**
     * Extract pathes from an extension package.
     * 
     * @param  string    $extension 
     * @access public
     * @return array
     */
    public function extractPathesFromPackage($extension)
    {
        $pathes = array();
        $packageFile = $this->getPackageFile($extension);

        /* Get files from the package file. */
        $this->app->loadClass('pclzip', true);
        $zip   = new pclzip($packageFile);
        $files = $zip->listContent();
        foreach($files as $file)
        {
            $file = (object)$file;
            if($file->folder) continue;
            $file->filename = substr($file->filename, strpos($file->filename, '/') + 1);
            $pathes[] = dirname($file->filename);
        }

        /* Append the pathes to stored the extracted files. */
        $pathes[] = "module/extension/ext/";

        return array_unique($pathes);
    }

    /**
     * Copy package files. 
     * 
     * @param  int    $extension 
     * @access public
     * @return array
     */
    public function copyPackageFiles($extension)
    {
        $appRoot      = $this->app->getAppRoot();
        $extensionDir = "ext/$extension/";
        $pathes       = scandir($extensionDir);
        $copiedFiles  = array();

        foreach($pathes as $path)
        {
            if($path == 'db' or $path == 'doc' or $path == '..' or $path == '.') continue;
            $copiedFiles = $this->copyDir($extensionDir . $path, $appRoot . $path);
        }

        return $copiedFiles;
    }

    /**
     * Get the install db file.
     * 
     * @param  string    $extension 
     * @param  string    $method 
     * @access public
     * @return string
     */
    public function getDBFile($extension, $method = 'install')
    {
        return "ext/$extension/db/$method.sql";
    }

    /**
     * Judge need execute db install or not.
     * 
     * @param  string    $extension 
     * @param  string    $method 
     * @access public
     * @return bool
     */
    public function needExecuteDB($extension, $method = 'install')
    {
        return file_exists($this->getDBFile($extension, $method));
    }

    /**
     * Install the db.
     * 
     * @param  int    $extension 
     * @access public
     * @return object
     */
    public function executeDB($extension, $method = 'install')
    {
        $return->result = 'ok';
        $return->error  = '';

        $sqls = file_get_contents($this->getDBFile($extension, $method));
        $sqls = explode(';', $sqls);

        foreach($sqls as $sql)
        {
            $sql = trim($sql);
            if(empty($sql)) continue;
            $sql = str_replace('zt_', $this->config->db->prefix, $sql);

            try
            {
                $this->dbh->query($sql);
            }
            catch (PDOException $e) 
            {
                $return->error .= '<p>' . $e->getMessage() . "<br />THE SQL IS: $sql</p>";
            }
        }
        if($return->error) $return->result = 'fail';
        return $return;
    }

    /**
     * Copy a directory from an directory to another directory.
     * 
     * @param  string    $from 
     * @param  string    $to 
     * @access public
     * @return array     copied files.
     */
    public function copyDir($from, $to)
    {
        static $copiedFiles = array();

        if(!is_dir($from) or !is_readable($from)) return $copiedFiles;
        if(!is_dir($to))
        {
            if(!is_writable(dirname($to))) return $copiedFiles;
            mkdir($to);
        }

        $from    = realpath($from) . '/';
        $to      = realpath($to) . '/';
        $entries = scandir($from);

        foreach($entries as $entry)
        {
            if($entry == '.' or $entry == '..') continue;

            $fullEntry = $from . $entry;
            if(is_file($fullEntry))
            {
                copy($fullEntry, $to . $entry);
                $copiedFiles[] = $fullEntry;
            }
            else
            {
                $nextFrom = $from . $entry;
                $nextTo   = $to . $entry;
                $this->copyDir($nextFrom, $nextTo);
            }
        }
        return $copiedFiles;
    }

    /**
     * Remove a dir.
     * 
     * @param  string    $dir 
     * @access public
     * @return bool
     */
    public function removeDir($dir)
    {
        $dir = realpath($dir) . '/';

        if(!is_writable($dir)) return false;
        if(!is_dir($dir)) return true;

        $entries = scandir($dir);
        foreach($entries as $entry)
        {
            if($entry == '.' or $entry == '..') continue;

            $fullEntry = $dir . $entry;
            if(is_file($fullEntry))
            {
                unlink($fullEntry);
            }
            else
            {
                $this->removeDir($fullEntry);
            }
        }
        rmdir($dir);
        return true;
    }

    /**
     * Save the extension to database.
     * 
     * @param  int    $extension 
     * @access public
     * @return void
     */
    public function save2DB($extension)
    {
        $extension = $this->getExtensionInfo($extension);
        $extension->status = 'available';
        $this->dao->replace(TABLE_EXTENSION)->data($extension)->exec();
    }

    /**
     * Update the status of an extension.
     * 
     * @param  string    $extension 
     * @param  string    $status 
     * @access public
     * @return void
     */
    public function updateStatus($extension, $status)
    {
        return $this->dao->update(TABLE_EXTENSION)->set('status')->eq($status)->where('code')->eq($extension)->exec();
    }

    /**
     * Get info of an extension.
     * 
     * @param  string    $extension 
     * @access public
     * @return object
     */
    public function getExtensionInfo($extension)
    {
        $data->name    = $extension;
        $data->code    = $extension;
        $data->version = 'unknown';
        $data->author  = 'unknown';
        $data->desc    = $extension;
        $data->site    = 'unknown';
        $data->license = 'unknown';
        $data->zentaoVersion = '';

        $infoFile = "ext/$extension/doc/copyright.txt";
        if(!file_exists($infoFile)) return $data;

        $info = (object)parse_ini_file($infoFile);
        foreach($info as $key => $value)
        {
            if(isset($data->$key)) $data->$key = $value;
        }
        if(isset($info->zentaoversion)) $data->zentaoVersion = $info->zentaoversion;
        return $data;
    }
}
