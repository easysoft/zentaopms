<?php
/**
 * The model file of extension module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
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
        $this->classFile = $this->app->loadClass('file');
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
        $requestLang = str_replace('-', '_', $this->cookie->lang);
        $apiURL      = $this->apiRoot . 'apiGetmodules-' . $requestType . '-' . $webRoot . '-' . $requestLang . '.json';
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
        $requestLang = str_replace('-', '_', $this->cookie->lang);
        $apiURL = $this->apiRoot . 'apiGetExtensions-' . $type . '-' . $param . '-' . $requestLang . '.json';
        $data = $this->fetchAPI($apiURL);
        if(isset($data->extensions))
        {
            foreach($data->extensions as $extension)
            {
                $extension->compatible = $this->checkVersion($extension->zentaoVersion);
            }
            return $data;
        }
        return false;
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
     * Get extensions by status.
     * 
     * @param  string    $status 
     * @access public
     * @return array
     */
    public function getLocalExtensions($status)
    {
        return $this->dao->select('*')->from(TABLE_EXTENSION)->where('status')->eq($status)->fi()->fetchAll();
    }

    /**
     * Get extension info from database.
     * 
     * @param  string    $extension 
     * @access public
     * @return object
     */
    public function getInfoFromDB($extension)
    {
        return $this->dao->select('*')->from(TABLE_EXTENSION)->where('code')->eq($extension)->fi()->fetch();
    }

    /**
     * Get info of an extension from the package file.
     * 
     * @param  string    $extension 
     * @access public
     * @return object
     */
    public function getInfoFromPackage($extension)
    {
        /* Init the data. */
        $data->name    = $extension;
        $data->code    = $extension;
        $data->version = 'unknown';
        $data->author  = 'unknown';
        $data->desc    = $extension;
        $data->site    = 'unknown';
        $data->license = 'unknown';
        $data->zentaoVersion = '';

        /* Redad the info file of the package. */
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
     * Get pathes from an extension package.
     * 
     * @param  string    $extension 
     * @access public
     * @return array
     */
    public function getPathesFromPackage($extension)
    {
        $pathes = array();
        $packageFile = $this->getPackageFile($extension);

        /* Get files from the package file. */
        $this->app->loadClass('pclzip', true);
        $zip   = new pclzip($packageFile);
        $files = $zip->listContent();
        if($files)
        {
            foreach($files as $file)
            {
                $file = (object)$file;
                if($file->folder) continue;
                $file->filename = substr($file->filename, strpos($file->filename, '/') + 1);
                $pathes[] = dirname($file->filename);
            }
        }

        /* Append the pathes to stored the extracted files. */
        $pathes[] = "module/extension/ext/";

        return array_unique($pathes);
    }

    /**
     * Get all files from a package.
     * 
     * @param  string    $extension 
     * @access public
     * @return array
     */
    public function getFilesFromPackage($extension)
    {
        $extensionDir = "ext/$extension/";
        $files = $this->classFile->readDir($extensionDir, array('db', 'doc'));
        return $files;
    }

    /**
     * Get the extension's zentaoVersion 
     * 
     * @param  string    $extenstion 
     * @access public
     * @return string
     */
    public function getZentaoVersion($extension)
    {
        $zentaoVersion = '';
        $infoFile      = "ext/$extension/doc/copyright.txt";
        if(!file_exists($infoFile)) return $zentaoVersion;

        $info = parse_ini_file($infoFile);
        if(isset($info['zentaoVersion'])) return $info['zentaoVersion'];
        if(isset($info['zentaoversion'])) return $info['zentaoversion'];

        return $zentaoVersion;
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
        $return->dirs2Created  = array();

        $appRoot = $this->app->getAppRoot();
        $pathes  = $this->getPathesFromPackage($extension);
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
                $return->mkdirCommands .= "mkdir -p $path<br />";
                $return->chmodCommands .= "sudo chmod -R 777 $path<br />";
                $return->dirs2Created[] = $path;
            }
        }

        if($return->errors) $return->result = 'fail';
        $return->mkdirCommands = str_replace('/', DIRECTORY_SEPARATOR, $return->mkdirCommands);
        $return->errors .= $this->lang->extension->executeCommands . $return->mkdirCommands;
        if(PHP_OS == 'Linux') $return->errors .= $return->chmodCommands;
        return $return;
    }

    /**
     * Check the extension's version is compatibility for zentao version
     * 
     * @param  string    $version 
     * @access public
     * @return bool
     */
    public function checkVersion($version)
    {
        if($version == 'all') return true;
        $version = explode(',', $version);
        if(in_array($this->config->version, $version)) return true;
        return false;
    }

    /**
     * Check files in the package conflicts with exists files or not.
     * 
     * @param  string    $extension 
     * @param  string    $type
     * @param  bool      $isCheck
     * @access public
     * @return object
     */
    public function checkFile($extension)
    {
        $return->result = 'ok';
        $return->error  = '';

        $extensionFiles = $this->getFilesFromPackage($extension);
        $appRoot = $this->app->getAppRoot();
        foreach($extensionFiles as $extensionFile)
        {
            $compareFile = $appRoot . str_replace(realpath("ext/$extension") . '/', '', $extensionFile);
            if(!file_exists($compareFile)) continue;
            if(md5_file($extensionFile) != md5_file($compareFile)) $return->error .= $compareFile . '<br />';
        }

        if($return->error != '') $return->result = 'fail';
        return $return;
    }

    /**
     * Extract an extension.
     * 
     * @param  string    $extension 
     * @access public
     * @return object
     */
    public function extractPackage($extension) 
    {
        $return->result = 'ok';
        $return->error  = '';

        $packageFile = $this->getPackageFile($extension);
        $this->app->loadClass('pclzip', true);
        $zip = new pclzip($packageFile);
        $files = $zip->listContent();
        $removePath = $files[0]['filename'];
        if($zip->extract(PCLZIP_OPT_PATH, "ext/$extension", PCLZIP_OPT_REMOVE_PATH, $removePath) == 0)
        {
            $return->result = 'fail';
            $return->error  = $zip->errorInfo(true);
        }

        return $return;
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
            $copiedFiles = $this->classFile->copyDir($extensionDir . $path, $appRoot . $path);
        }
        foreach($copiedFiles as $key => $copiedFile)
        {
            $copiedFiles[$copiedFile] = md5_file($copiedFile);
            unset($copiedFiles[$key]);
        }
        return $copiedFiles;
    }

    /**
     * Remove an extension.
     * 
     * @param  string    $extension 
     * @access public
     * @return array     the remove commands need executed manually.
     */
    public function removePackage($extension)
    {
        $extension = $this->getInfoFromDB($extension);
        $dirs  = json_decode($extension->dirs);
        $files = json_decode($extension->files);
        $appRoot = $this->app->getAppRoot();
        $removeCommands = array();

        if($dirs)
        {
            foreach($dirs as $dir)
            {
                if(!@rmdir($appRoot . $dir)) $removeCommands[] = "rmdir $appRoot$dir";
            }
        }

        if($files)
        {
            foreach($files as $file => $savedMd5)
            {
                $file = $appRoot . $file;
                if(!file_exists($file)) continue;

                if(md5_file($file) != $savedMd5)
                {
                    $removeCommands[] = PHP_OS == 'Linux' ? "rm -fr $file #changed" : "del $file :changed";
                }
                elseif(!@unlink($file))
                {
                    $removeCommands[] = PHP_OS == 'Linux' ? "rm -fr $file" : "del $file";
                }
            }
        }
        return $removeCommands;
    }

    /**
     * Erase an extension's package file.
     * 
     * @param  string    $extension 
     * @access public
     * @return array     the remove commands need executed manually.
     */
    public function erasePackage($extension)
    {
        $removeCommands = array();

        $this->dao->delete()->from(TABLE_EXTENSION)->where('code')->eq($extension)->exec();

        /* Remove the zip file. */
        $packageFile = $this->getPackageFile($extension);
        if(file_exists($packageFile) and !@unlink($packageFile))
        {
            $removeCommands[] = PHP_OS == 'Linux' ? "rm -fr $packageFile" : "del $packageFile";
        }

        /* Remove the extracted files. */
        $extractedDir = realpath("ext/$extension");
        if(!$this->classFile->removeDir($extractedDir))
        {
            $removeCommands[] = PHP_OS == 'Linux' ? "rm -fr $extractedDir" : "rmdir $extractedDir /s";
        }

        return $removeCommands;
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

        $dbFile = $this->getDBFile($extension, $method);
        if(!file_exists($dbFile)) return $return;

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
     * Save the extension to database.
     * 
     * @param  int    $extension 
     * @access public
     * @return void
     */
    public function saveExtension($extension)
    {
        $code      = $extension;
        $extension = $this->getInfoFromPackage($extension);
        $extension->status = 'available';
        $extension->code   = $code;
        $this->dao->replace(TABLE_EXTENSION)->data($extension)->exec();
    }

    /**
     * Update an extension.
     * 
     * @param  string    $extension 
     * @param  string    $status 
     * @param  array     $files 
     * @access public
     * @return void
     */
    public function updateExtension($extension, $data)
    {
        $data = (object)$data;
        $appRoot = $this->app->getAppRoot();

        if(isset($data->dirs))
        {
            if($data->dirs)
            {
                foreach($data->dirs as $key => $dir)
                {
                    $data->dirs[$key] = str_replace($appRoot, '', $dir);
                }
            }
            $data->dirs = json_encode($data->dirs);
        }

        if(isset($data->files))
        {
            foreach($data->files as $fullFilePath => $md5)
            {
                $relativeFilePath = str_replace($appRoot, '', $fullFilePath);
                $data->files[$relativeFilePath] = $md5;
                unset($data->files[$fullFilePath]);
            }
            $data->files = json_encode($data->files);
        }
        return $this->dao->update(TABLE_EXTENSION)->data($data)->where('code')->eq($extension)->exec();
    }
}
