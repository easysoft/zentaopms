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
     * @return object
     */
    public function checkExtensionPathes($extension)
    {
        $return->result      = 'ok';
        $return->errors      = '';
        $return->dirCommands = '';
        $return->modCommands = '';

        $appRoot = $this->app->getAppRoot();
        $pathes  = $this->extractPathesFromPackage($extension);
        foreach($pathes as $path)
        {
            if($path == 'db' or $path == 'doc') continue;
            $path = $appRoot . $path;
            if(file_exists($path))
            {
                if(!is_writable($path))
                {
                    $return->errors   .= sprintf($this->lang->extension->errorTargetPathNotWritable, $path) . '<br />';
                    $return->modCommands .= "chmod -R 777 $path<br />";
                }
            }
            elseif(!is_writable(dirname($path)))
            {
                $return->errors .= sprintf($this->lang->extension->errorTargetPathNotExists, $path) . '<br />';
                $return->dirCommands .= "mkdir $path<br />";
                $return->modCommands .= "chmod -R 777 $path<br />";
            }
        }
        if($return->errors) $return->result = 'fail';
        $return->dirCommands = str_replace('/', DIRECTORY_SEPARATOR, $return->dirCommands);
        $return->errors .= $return->dirCommands . $return->modCommands;
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
        $packageFile = $this->getPackageFile($extension);

        /* Extract the zip file. */
        $this->app->loadClass('pclzip', true);
        $zip = new pclzip($packageFile);
        $zip->extract(PCLZIP_OPT_PATH, 'ext');
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
        return array_unique($pathes);
    }

    /**
     * Copy package files. 
     * 
     * @param  int    $extension 
     * @access public
     * @return void
     */
    public function copyPackageFiles($extension)
    {
        $this->xcopy("ext/$extension/module", $this->app->getAppRoot() . 'module');
        $this->xcopy("ext/$extension/www",    $this->app->getAppRoot() . 'www');
        $this->xcopy("ext/$extension/bin",    $this->app->getAppRoot() . 'bin');
        $this->xcopy("ext/$extension/config", $this->app->getAppRoot() . 'config');
        $this->xcopy("ext/$extension/lib",    $this->app->getAppRoot() . 'bli');
    }

    /**
     * Copy a directory from an directory to another directory.
     * 
     * @param  string    $from 
     * @param  string    $to 
     * @access public
     * @return array     copied files.
     */
    public function xcopy($from, $to)
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
                $this->xcopy($nextFrom, $nextTo);
            }
        }
        return $copiedFiles;
    }
}
