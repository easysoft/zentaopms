<?php
declare(strict_types=1);
/**
 * The model file of extension module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     extension
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class extensionModel extends model
{
    /**
     * The extension manager version. Don't change it.
     */
    const EXT_MANAGER_VERSION = '1.3';

    /**
     * The api root.
     *
     * @var string
     * @access public
     */
    public $apiRoot;

    /**
     * The package root.
     *
     * @var string
     * @access public
     */
    public $pkgRoot;

    /**
     * 构造函数。
     * The construct function.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->apiRoot   = $this->config->extension->apiRoot;
        $this->classFile = $this->app->loadClass('zfile');
        $this->pkgRoot   = $this->app->getExtensionRoot() . 'pkg' . DS;
    }

    /**
     * 调用接口并返回结果中的data。
     * Fetch data from an api.
     *
     * @param  string $url
     * @access public
     * @return mixed
     */
    private function fetchAPI(string $url)
    {
        /* 拼接URL并调用接口。 */
        $version = $this->loadModel('upgrade')->getOpenVersion(str_replace('.', '_', $this->config->version));
        $version = str_replace('_', '.', $version);
        $url    .= (strpos($url, '?') === false ? '?' : '&') . 'lang=' . str_replace('-', '_', $this->app->getClientLang()) . '&managerVersion=' . self::EXT_MANAGER_VERSION;
        $url    .= '&zentaoVersion=' . $version . '&edition=' . $this->config->edition;
        $result  = json_decode(common::http($url));

        /* 返回接口结果。 */
        if(!isset($result->status))      return false;
        if($result->status != 'success') return false;
        if(isset($result->data) && md5($result->data) != $result->md5) return false;
        if(isset($result->data)) return json_decode($result->data);
        return false;
    }

    /**
     * 调用禅道官网接口获取插件的分类。
     * Get extension modules from the api.
     *
     * @access public
     * @return array|bool
     */
    public function getModulesByAPI(): array|bool
    {
        $requestType = helper::safe64Encode($this->config->requestType);
        $webRoot     = helper::safe64Encode($this->config->webRoot, '', false, true);
        $apiURL      = "{$this->apiRoot}apiGetmodules-{$requestType}-{$webRoot}.json";
        $data        = $this->fetchAPI($apiURL);

        if(isset($data->newmodules)) return $data->newmodules;
        return false;
    }

    /**
     * 调用禅道官网接口获取插件的版本。
     * Get versions for some extensions.
     *
     * @param  string     $extensions
     * @access public
     * @return array|bool
     */
    public function getVersionsByAPI(string $extensions): array|bool
    {
        $extensions = helper::safe64Encode($extensions);
        $apiURL     = "{$this->apiRoot}apiGetVersions-{$extensions}.json";
        $data       = $this->fetchAPI($apiURL);

        if(isset($data->versions)) return (array)$data->versions;
        return false;
    }

    /**
     * 调用禅道官网接口获取插件的列表。
     * Get extensions by some condition.
     *
     * @param  string      $type
     * @param  string      $param
     * @param  int         $recTotal
     * @param  int         $recPerPage
     * @param  int         $pageID
     * @access public
     * @return object|bool
     */
    public function getExtensionsByAPI(string $type, string $param, int $recTotal = 0, int $recPerPage = 20, int $pageID = 1): object|false
    {
        $apiURL = $this->apiRoot . "apiGetExtensions-$type-$param-$recTotal-$recPerPage-$pageID.json";
        $data   = $this->fetchAPI($apiURL);
        if(isset($data->extensions))
        {
            foreach($data->extensions as $extension)
            {
                $extension->currentRelease = isset($extension->compatibleRelease) ? $extension->compatibleRelease : $extension->latestRelease;
                $extension->currentRelease->compatible = isset($extension->compatibleRelease);
            }
            return $data;
        }
        return false;
    }

    /**
     * 根据插件的当前版本号返回插件的当前兼容版本。
     * Check incompatible extension
     *
     * @param  array  $versions
     * @access public
     * @return array
     */
    public function checkIncompatible(array $versions): array
    {
        $apiURL = $this->apiRoot . 'apiCheckIncompatible' . '.json?versions=' . helper::safe64Encode(json_encode($versions));
        $data   = $this->fetchAPI($apiURL);

        if(isset($data->incompatibleExts)) return (array)$data->incompatibleExts;
        return array();
    }

    /**
     * 根据状态获取本地安装的插件。
     * Get extensions by status.
     *
     * @param  string $status
     * @access public
     * @return array
     */
    public function getLocalExtensions(string $status): array
    {
        $extensions = $this->dao->select('*')->from(TABLE_EXTENSION)->where('status')->in($status)->fi()->fetchAll('code');
        foreach($extensions as $extension)
        {
            if($extension->site && stripos(strtolower($extension->site), 'http') === false) $extension->site = 'http://' . $extension->site;
        }
        return $extensions;
    }

    /**
     * 根据插件代号从数据库获取插件信息。
     * Get extension info from database.
     *
     * @param  string $extension
     * @access public
     * @return object
     */
    public function getInfoFromDB(string $extension): object|false
    {
        return $this->dao->select('*')->from(TABLE_EXTENSION)->where('code')->eq($extension)->fetch();
    }

    /**
     * 根据插件代号从插件包获取插件信息。
     * Get info of an extension from the package file.
     *
     * @param  string $extension
     * @access public
     * @return object
     */
    public function getInfoFromPackage(string $extension): object
    {
        /* Init the data. */
        $data = new stdclass();
        $data->name             = $extension;
        $data->code             = $extension;
        $data->version          = 'unknown';
        $data->author           = 'unknown';
        $data->desc             = $extension;
        $data->site             = 'unknown';
        $data->license          = 'unknown';
        $data->zentaoCompatible = '';
        $data->type             = '';
        $data->depends          = '';

        $info = $this->parseExtensionCFG($extension);
        foreach($info as $key => $value)
        {
            if(isset($data->$key)) $data->$key = is_null($value) ? '' : $value;
        }

        if(isset($info->zentaoversion))        $data->zentaoCompatible = $info->zentaoversion;
        if(isset($info->zentao['compatible'])) $data->zentaoCompatible = $info->zentao['compatible'];
        if(isset($info->depends))              $data->depends          = json_encode($info->depends);

        return $data;
    }

    /**
     * 根据插件代号从插件包获取配置信息。
     * Parse extension's config file.
     *
     * @param  string $extension
     * @access public
     * @return object
     */
    public function parseExtensionCFG(string $extension): object
    {
        /* First, try ini file. before 2.5 version. */
        $infoFile = $this->pkgRoot . "$extension/doc/copyright.txt";
        if(file_exists($infoFile)) return (object)parse_ini_file($infoFile);

        /**
         * Then try parse yaml file. since 2.5 version.
         */

        /* Try the yaml of current lang, then try en. */
        $info = new stdclass();
        $lang = $this->app->getClientLang();
        $infoFile = $this->pkgRoot . "$extension/doc/$lang.yaml";
        if(!file_exists($infoFile)) $infoFile = $this->pkgRoot . "$extension/doc/en.yaml";
        if(!file_exists($infoFile)) return $info;

        /* Load the yaml file and parse it into object. */
        $this->app->loadClass('spyc', true);
        $info = (object)spyc_load(file_get_contents($infoFile));
        if(isset($info->releases))
        {
            $info->version = key($info->releases);
            foreach(array_keys($info->releases) as $version)
            {
                if(version_compare($info->version, $version, '<')) $info->version = $version;
            }

            foreach($info->releases[$info->version] as $key => $value) $info->$key = $value;
        }
        return $info;
    }

    /**
     * 根据插件代号获取上传的插件包路径。
     * Get the full path of the zip file of a extension.
     *
     * @param  string $extension
     * @access public
     * @return string
     */
    public function getPackageFile(string $extension): string
    {
        return $this->app->getTmpRoot() . 'extension/' . $extension . '.zip';
    }

    /**
     * 根据插件代号获取插件包里的文件夹列表。
     * Get paths from an extension package.
     *
     * @param  string $extension
     * @access public
     * @return array
     */
    public function getPathsFromPackage(string $extension): array
    {
        $paths = array();
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
                $paths[] = dirname($file->filename);
            }
        }

        return array_unique($paths);
    }

    /**
     * 根据插件代号获取插件包里除db和doc目录外的文件列表。
     * Get all files from a package.
     *
     * @param  string $extension
     * @access public
     * @return array
     */
    public function getFilesFromPackage(string $extension): array
    {
        $extensionDir = $this->pkgRoot . $extension;
        return $this->classFile->readDir($extensionDir, array('db', 'doc'));
    }

    /**
     * 根据插件代号获取插件包的配置信息。
     * Get the extension's condition.
     *
     * @param  string $extenstion
     * @access public
     * @return object
     */
    public function getCondition(string $extension): object
    {
        $info = $this->parseExtensionCFG($extension);

        $condition = new stdclass();
        $condition->zentao    = array('compatible' => '', 'incompatible' => '');
        $condition->depends   = '';
        $condition->conflicts = '';

        if(isset($info->zentao))    $condition->zentao    = $info->zentao;
        if(isset($info->depends))   $condition->depends   = $info->depends;
        if(isset($info->conflicts)) $condition->conflicts = $info->conflicts;

        /* zentaoversion和zentaoVersion哪个有值取那个。 */
        if(isset($info->zentaoVersion)) $condition->zentao['compatible'] = $info->zentaoVersion;
        if(isset($info->zentaoversion)) $condition->zentao['compatible'] = $info->zentaoversion;

        return $condition;
    }

    /**
     * 处理授权协议内容。
     * Process license. If is opensource return the full text of it.
     *
     * @param  string $license
     * @access public
     * @return string
     */
    public function processLicense(string $license): string
    {
        if(strlen($license) > 10) return $license;    // more then 10 letters, not gpl, lgpl, apache, bsd or mit.

        $licenseFile = dirname(__FILE__) . '/license/' . strtolower($license) . '.txt';
        if(file_exists($licenseFile)) return file_get_contents($licenseFile);

        return $license;
    }

    /**
     * 根据插件代号获取数据库执行文件。
     * Get the install db file.
     *
     * @param  string $extension
     * @param  string $method    install|upgrade
     * @access public
     * @return string
     */
    public function getDBFile(string $extension, string $method = 'install'): string
    {
        return $this->pkgRoot . "$extension/db/$method.sql";
    }

    /**
     * 检查当前禅道版本是否包含在指定版本号中。
     * Check the extension's version is compatibility for zentao version
     *
     * @param  string $version
     * @access public
     * @return bool
     */
    public function checkVersion(string $version): bool
    {
        if($version == 'all') return true;

        $version = explode(',', $version);
        if(in_array($this->config->version, $version)) return true;
        return false;
    }

    /**
     * 删除安装的插件文件并返回错误提示。
     * Remove an extension.
     *
     * @param  string $extension
     * @access public
     * @return array
     */
    public function removePackage(string $extension): array
    {
        $extension = $this->getInfoFromDB($extension);
        if(empty($extension))           return array();
        if($extension->type == 'patch') return array();   // 无法删除补丁类型的插件包。

        $removeFilesTips = $this->removeExtensionFiles($extension->files);
        $removeDirsTips  = $this->removeExtensionDirs($extension->dirs);

        /* Clean model cache files. */
        $this->cleanModelCache();

        return array_merge($removeFilesTips, $removeDirsTips);
    }

    /**
     * 删除安装插件时拷贝到禅道目录的插件文件。
     * Remove extension files.
     *
     * @param  string  $files
     * @access private
     * @return array
     */
    private function removeExtensionFiles(string $files): array
    {
        $commandTips = array();
        $appRoot     = $this->app->getAppRoot();
        $files       = json_decode($files);
        if($files)
        {
            foreach($files as $file => $savedMD5)
            {
                $file = $appRoot . $file;
                if(!file_exists($file)) continue;

                /* 如果没有权限或者删除失败则返回提示信息。 */
                $parentDir = mb_substr($file, 0, strripos($file, '/'));
                if(!is_writable($file) || !is_writable($parentDir))
                {
                    $commandTips[] = PHP_OS == 'Linux' ? "sudo rm -fr $file" : "del $file";
                }
                elseif(@md5_file($file) != $savedMD5)
                {
                    $commandTips[] = PHP_OS == 'Linux' ? "sudo rm -fr $file" : "del $file";
                }
                elseif(!@unlink($file))
                {
                    $commandTips[] = PHP_OS == 'Linux' ? "sudo rm -fr $file" : "del $file";
                }
            }
        }

        return $commandTips;
    }

    /**
     * 删除安装插件时在禅道目录新增的文件夹。
     * Remove extension dirs.
     *
     * @param  string  $dirs
     * @access private
     * @return array
     */
    private function removeExtensionDirs(string $dirs): array
    {
        $commandTips = array();
        $appRoot     = $this->app->getAppRoot();
        $dirs        = json_decode($dirs);
        if($dirs)
        {
            rsort($dirs);    // remove from the lower level directory.
            foreach($dirs as $dir)
            {
                $path = rtrim($appRoot . $dir, '/');
                if(!is_dir($path)) continue;

                /* 如果没有权限或者删除失败则返回提示信息。*/
                $parentDir = mb_substr($path, 0, strripos($path, '/'));
                if(!is_writable($path) || !is_writable($parentDir))
                {
                    $commandTips[] = PHP_OS == 'Linux' ? "sudo rm -fr $appRoot$dir" : "rmdir $appRoot$dir /s /q";
                }
                elseif(!rmdir($path))
                {
                    $commandTips[] = PHP_OS == 'Linux' ? "sudo rm -fr $appRoot$dir" : "rmdir $appRoot$dir /s /q";
                }
            }
        }

        return $commandTips;
    }

    /**
     * 删除禅道model的缓存文件。
     * Clean model cache files.
     *
     * @access public
     * @return private
     */
    private function cleanModelCache()
    {
        $zfile           = $this->app->loadClass('zfile');
        $modelCacheFiles = glob($this->app->getTmpRoot() . 'model/*');
        foreach($modelCacheFiles as $cacheFile)
        {
            if(is_dir($cacheFile))
            {
                $zfile->removeDir($cacheFile);
            }
            elseif(is_writable($cacheFile) && !is_dir($cacheFile))
            {
                @unlink($cacheFile);
            }
        }
    }

    /**
     * 清除插件并删除文件。
     * Erase an extension's package file.
     *
     * @param  string $extension
     * @access public
     * @return array
     */
    public function erasePackage(string $extension): array
    {
        $this->dao->delete()->from(TABLE_EXTENSION)->where('code')->eq($extension)->exec();

        $packageFile = $this->getPackageFile($extension);
        if(!file_exists($packageFile)) return array();

        /* Remove the zip file. */
        $removeCommands = array();
        if(file_exists($packageFile) && !@unlink($packageFile))
        {
            $removeCommands[] = PHP_OS == 'Linux' ? "rm -fr $packageFile" : "del $packageFile";
        }

        /* Remove the extracted files. */
        $extractedDir = $this->pkgRoot . $extension;
        if($extractedDir and $extractedDir != '/' and !$this->classFile->removeDir($extractedDir))
        {
            $removeCommands[] = PHP_OS == 'Linux' ? "rm -fr $extractedDir" : "rmdir $extractedDir /s";
        }

        return $removeCommands;
    }

    /**
     * 执行插件包中安装或者卸载时的SQL语句。
     * Execute the extension db.
     *
     * @param  string $extension
     * @param  string $method     install|uninstall
     * @access public
     * @return object
     */
    public function executeDB(string $extension, string $method = 'install'): object
    {
        $result = new stdclass();
        $result->result = 'ok';
        $result->error  = '';

        $dbFile = $this->getDBFile($extension, $method);
        if(!file_exists($dbFile)) return $result;

        $ignoreCode = '|1050|1060|1062|1091|1169|';
        $sqls       = file_get_contents($this->getDBFile($extension, $method));
        $sqls       = explode(';', $sqls);
        foreach($sqls as $sql)
        {
            $sql = trim($sql);
            if(empty($sql)) continue;

            $sql = str_replace('zt_', $this->config->db->prefix, $sql);
            try
            {
                $this->dbh->query($sql);
            }
            catch(PDOException $e)
            {
                $errorInfo = $e->errorInfo;
                $errorCode = $errorInfo[1];
                if(strpos($ignoreCode, "|$errorCode|") === false) $result->error .= '<p>' . $e->getMessage() . "<br />THE SQL IS: $sql</p>";
            }
        }

        if($result->error) $result->result = 'fail';
        return $result;
    }

    /**
     * 保存插件信息到数据库。
     * Save the extension to database.
     *
     * @param  string $code
     * @param  string $type
     * @access public
     * @return bool
     */
    public function saveExtension(string $code, string $type): bool
    {
        //从插件包中获取配置信息。
        $extension = $this->getInfoFromPackage($code);

        $extension->status        = 'available';
        $extension->code          = $code;
        $extension->type          = empty($type) ? $extension->type : $type;
        $extension->installedTime = helper::now();

        $this->dao->replace(TABLE_EXTENSION)->data($extension)->exec();

        return !dao::isError();
    }

    /**
     * Update an extension.
     *
     * @param  string        $extension
     * @param  array|object  $data
     * @access public
     * @return int
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

    /**
     * Check depends extension.
     *
     * @param  string    $extension
     * @access public
     * @return array
     */
    public function checkDepends($extension)
    {
        $result        = array();
        $extensionInfo = $this->dao->select('*')->from(TABLE_EXTENSION)->where('code')->eq($extension)->fetch();
        $dependsExts   = $this->dao->select('*')->from(TABLE_EXTENSION)->where('depends')->like("%$extension%")->andWhere('status')->ne('available')->fetchAll();
        if($dependsExts)
        {
            foreach($dependsExts as $dependsExt)
            {
                $depends = json_decode($dependsExt->depends, true);
                if($this->compare4Limit($extensionInfo->version, $depends[$extension])) $result[] = $dependsExt->name;
            }
        }
        return $result;
    }

    /**
     * Compare for limit data.
     *
     * @param  string $version
     * @param  array  $limit
     * @param  string $type
     * @access public
     * @return void
     */
    public function compare4Limit($version, $limit, $type = 'between')
    {
        $result = false;
        if(empty($limit)) return true;

        if($limit == 'all')
        {
            $result = true;
        }
        else
        {
            if(!empty($limit['min']) and $version >= $limit['min']) $result = true;
            if(!empty($limit['max']) and $version <= $limit['max']) $result = true;
            if(!empty($limit['max']) and $version > $limit['max'] and $result) $result = false;
        }

        if($type != 'between') return !$result;
        return $result;
    }

    /**
     * Get extension expire date.
     *
     * @param  int    $extension
     * @access public
     * @return string
     */
    public function getExpireDate($extension)
    {
        $licencePath = $this->app->getConfigRoot() . 'license/';
        $today       = date('Y-m-d');
        $expiredDate = '';

        $licenceOrderFiles = glob($licencePath . 'order*.txt');
        foreach($licenceOrderFiles as $licenceOrderFile)
        {
            if(stripos($licenceOrderFile, "{$extension->code}{$extension->version}.txt") === false) continue;

            $order = file_get_contents($licenceOrderFile);
            $order = unserialize($order);
            if($order->type != 'life')
            {
                $days = isset($order->days) ? $order->days : 0;
                if($order->type == 'demo') $days = 31;
                if($order->type == 'year') $days = 365;
                $startDate  = !helper::isZeroDate($order->paidDate) ? $order->paidDate : $order->createdDate;
                if($days) $expiredDate = date('Y-m-d', strtotime($startDate) + $days * 24 * 3600);
            }
            else
            {
                $expiredDate = $order->type;
            }
        }

        return $expiredDate;
    }

    /**
     * Get plugins that are about to expire or have expired.
     *
     * @param  bool    $category
     * @access public
     * @return array
     */
    public function getExpiringPlugins($category = false)
    {
        $extensions = $this->getLocalExtensions('installed');

        $plugins = $category ? array('expiring' => array(), 'expired' => array()) : array();
        $today   = helper::today();
        foreach($extensions as $extension)
        {
            $expiredDate = $this->getExpireDate($extension);
            if(!empty($expiredDate) and $expiredDate != 'life')
            {
                $dateDiff = helper::diffDate($expiredDate, $today);
                if($category)
                {
                    if($dateDiff == 30 or $dateDiff == 14 or ($dateDiff <= 7 and $dateDiff >= 0)) $plugins['expiring'][] = $extension->name;
                    if($dateDiff <= -1) $plugins['expired'][] = $extension->name;
                }
                else
                {
                    if($dateDiff == 30 or $dateDiff == 14 or $dateDiff <= 7) $plugins[] = $extension->name;
                }
            }
        }
        return $plugins;
    }

    /**
     * Mark package active or disabled
     *
     * @param  string $extension
     * @param  string $action     disabled|active
     * @access public
     * @return bool
     */
    public function togglePackageDisable($extension, $action = 'disabled')
    {
        if(!is_dir($this->pkgRoot . $extension)) return true;

        $disabledFile = $this->pkgRoot . $extension . DS . 'disabled';
        if($action == 'disabled') touch($disabledFile);
        if($action == 'active' && file_exists($disabledFile)) unlink($disabledFile);
        return true;
    }
}
