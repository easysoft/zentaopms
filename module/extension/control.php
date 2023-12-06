<?php
/**
 * The control file of extension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     extension
 * @version     $Id$
 * @link        https://www.zentao.net
 */
class extension extends control
{
    /**
     * 插件列表页面。
     * Browse extensions.
     *
     * @param  string $status
     * @access public
     * @return void
     */
    public function browse(string $status = 'installed')
    {
        $this->extensionZen->checkSafe();

        $versions   = array();
        $extensions = $this->extension->getLocalExtensions($status);
        if($extensions && $status == 'installed')
        {
            /* Get latest release from remote. */
            $extCodes = helper::safe64Encode(join(',', array_keys($extensions)));
            $results = $this->extension->getExtensionsByAPI('bycode', $extCodes, $recTotal = 0, $recPerPage = 1000, $pageID = 1);
            if(isset($results->extensions))
            {
                $remoteReleases = $results->extensions;
                foreach($remoteReleases as $release)
                {
                    if(!isset($extensions[$release->code])) continue;

                    $extension = $extensions[$release->code];
                    $extension->viewLink = $release->viewLink;
                    if(isset($release->latestRelease) && $extension->version != $release->latestRelease->releaseVersion && $this->extension->checkVersion($release->latestRelease->zentaoCompatible))
                    {
                        $upgradeLink = inlink('upgrade', "extension=$release->code&downLink=&md5=&type=$release->type");
                        $upgradeLink = ($release->latestRelease->charge || !$release->latestRelease->public) ? $release->latestRelease->downLink : $upgradeLink;
                        $extension->upgradeLink = $upgradeLink;
                    }
                }
            }
        }

        $this->view->title      = $this->lang->extension->browse;
        $this->view->tab        = $status;
        $this->view->extensions = $extensions;
        $this->view->versions   = $versions;
        $this->display();
    }

    /**
     * 从禅道官网的插件市场获得插件。
     * Obtain extensions from the community.
     *
     * @param  string $type
     * @param  string $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function obtain(string $type = 'byUpdatedTime', string $param = '', int $recTotal = 0, int $recPerPage = 10, int $pageID = 1)
    {
        $this->extensionZen->checkSafe();

        /* Init vars. */
        $type       = strtolower($type);
        $moduleID   = $type == 'bymodule' ? (int)base64_decode($param) : 0;
        $extensions = array();
        $pager      = null;

        /* Set the key. */
        if($type == 'bysearch') $param = helper::safe64Encode($this->post->key ? $this->post->key : '');

        /* Get results from the api. */
        $results = $this->extension->getExtensionsByAPI($type, $param, $recTotal, $recPerPage, $pageID);
        if($results)
        {
            $this->app->loadClass('pager', $static = true);
            $pager      = new pager($results->dbPager->recTotal, $results->dbPager->recPerPage, $results->dbPager->pageID);
            $extensions = (array)$results->extensions;
        }

        $this->view->title      = $this->lang->extension->obtain;
        $this->view->moduleTree = $this->extension->getModulesByAPI();
        $this->view->extensions = $extensions;
        $this->view->installeds = $this->extension->getLocalExtensions('installed,deactivated');
        $this->view->pager      = $pager;
        $this->view->tab        = 'obtain';
        $this->view->type       = $type;
        $this->view->moduleID   = $moduleID;
        $this->display();
    }

    /**
     * 安装插件流程。
     * Install a extension
     *
     * @param  string $extension
     * @param  string $downLink
     * @param  string $md5
     * @param  string $type
     * @param  string $overridePackage
     * @param  string $ignoreCompatible
     * @param  string $overrideFile
     * @param  string $agreeLicense
     * @param  string $upgrade
     * @access public
     * @return void
     */
    public function install(string $extension, string $downLink = '', string $md5 = '', string $type = '', string $overridePackage = 'no', string $ignoreCompatible = 'no', string $overrideFile = 'no', string $agreeLicense = 'no', string $upgrade = 'no')
    {
        $this->extensionZen->checkSafe();
        set_time_limit(0);

        $installTitle = $upgrade == 'no' ? $this->lang->extension->install : $this->lang->extension->upgrade;
        $installType  = $upgrade == 'no' ? $this->lang->extension->installExt : $this->lang->extension->upgradeExt;
        $this->view->title       = $installTitle . $extension;
        $this->view->installType = $installType;
        $this->view->upgrade     = $upgrade;
        $this->view->error       = '';

        /* 插件安装前的合规校验。 */
        $ignoreLink   = inlink('install', "extension=$extension&downLink=&md5=$md5&type=$type&overridePackage=$overridePackage&ignoreCompatible=yes&overrideFile=$overrideFile&agreeLicense=$agreeLicense&upgrade=$upgrade");
        $overrideLink = inlink('install', "extension=$extension&downLink=&md5=$md5&type=$type&overridePackage=$overridePackage&ignoreCompatible=$ignoreCompatible&overrideFile=yes&agreeLicense=$agreeLicense&upgrade=$upgrade");
        $this->extensionZen->checkExtension($extension, $ignoreCompatible, $ignoreLink, $overrideFile, $overrideLink, $installType);
        if($this->view->error) return $this->display();

        if($upgrade == 'yes')
        {
            $newInfo = $this->extension->parseExtensionCFG($extension);
            $this->post->upgradeVersion = isset($newInfo->version) ? $newInfo->version : '';
            $oldInfo = $this->extension->getInfoFromDB($extension);
            $this->post->installedVersion = $oldInfo ? $oldInfo->version : '';
        }

        /* 打印授权协议同意表单。 */
        /* Print the license form. */
        if($agreeLicense == 'no')
        {
            $extensionInfo = $this->extension->getInfoFromPackage($extension);
            $license       = $this->extension->processLicense($extensionInfo->license);
            $agreeLink     = inlink('install', "extension=$extension&downLink=&md5=$md5&type=$type&overridePackage=$overridePackage&ignoreCompatible=$ignoreCompatible&overrideFile=$overrideFile&agreeLicense=yes&upgrade=$upgrade");
            $this->view->license   = $license;
            $this->view->author    = $extensionInfo->author;
            $this->view->agreeLink = $agreeLink;
            return $this->display();
        }

        /* 安装插件。 */
        $this->extensionZen->installExtension($extension, $type, $upgrade);
        $this->display();
    }

    /**
     * 卸载插件流程。
     * Uninstall an extension.
     *
     * @param  string $extension
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function uninstall(string $extension, string $confirm = 'no')
    {
        $this->extensionZen->checkSafe();

        /* Determine whether need to back up. */
        $dbFile = $this->extension->getDBFile($extension, 'uninstall');
        if($confirm == 'no' && file_exists($dbFile))
        {
            $this->view->title   = $this->lang->extension->waring;
            $this->view->confirm = 'no';
            $this->view->code    = $extension;
            return $this->display();
        }

        /* 相关依赖插件检查。 */
        $dependsExts = $this->extension->checkDepends($extension);
        if($dependsExts)
        {
            $this->view->error = sprintf($this->lang->extension->errorUninstallDepends, join(' ', $dependsExts));
            return $this->display();
        }

        /* 卸载前的钩子加载。 */
        if($preUninstallHook = $this->extension->getHookFile($extension, 'preuninstall')) include $preUninstallHook;

        if(file_exists($dbFile)) $this->view->backupFile = $this->extension->backupDB($extension);

        $this->extension->executeDB($extension, 'uninstall');
        $this->extension->updateExtension($extension, array('status' => 'available'));
        $this->extension->togglePackageDisable($extension, 'disabled');

        $this->view->title          = $this->lang->extension->uninstallFinished;
        $this->view->removeCommands = $this->extension->removePackage($extension);

        /* 卸载后的钩子加载。 */
        if($postUninstallHook = $this->extension->getHookFile($extension, 'postuninstall')) include $postUninstallHook;
        $this->display();
    }

    /**
     * 激活插件。
     * Activate an extension;
     *
     * @param  string $extension
     * @access public
     * @return void
     */
    public function activate(string $extension, string $ignore = 'no')
    {
        $this->extensionZen->checkSafe();

        if($ignore == 'no')
        {
            $return = $this->extension->checkFile($extension);
            if($return->result != 'ok')
            {
                $ignoreLink = inlink('activate', "extension=$extension&ignore=yes");
                $resetLink  = inlink('browse', 'type=deactivated');
                $this->view->error = sprintf($this->lang->extension->errorFileConflicted, $return->error, $ignoreLink, $resetLink);
                return $this->display();
            }
        }

        $this->extension->togglePackageDisable($extension, 'active');
        $this->extension->copyPackageFiles($extension);
        $this->extension->updateExtension($extension, array('status' => 'installed'));

        $this->view->title = $this->lang->extension->activateFinished;
        $this->display();
    }

    /**
     * 禁用插件。
     * Deactivate an extension
     *
     * @param  string $extension
     * @access public
     * @return void
     */
    public function deactivate(string $extension)
    {
        $this->extensionZen->checkSafe();

        $this->extension->updateExtension($extension, array('status' => 'deactivated'));
        $this->extension->togglePackageDisable($extension, 'disabled');

        $this->view->title          = $this->lang->extension->deactivateFinished;
        $this->view->removeCommands = $this->extension->removePackage($extension);
        $this->display();
    }

    /**
     * 上传插件并读取插件信息。
     * Upload an extension
     *
     * @access public
     * @return void
     */
    public function upload()
    {
        if($_FILES)
        {
            /* 检查上传附件的错误信息。 */
            if(!array_filter($_FILES['files']['name'])) return $this->send(array('result' => 'fail', 'message' => $this->lang->extension->errorFileNotEmpty));

            $tmpName   = $_FILES['files']['tmp_name'][0];
            $fileName  = $_FILES['files']['name'][0];
            $dest      = $this->app->getTmpRoot() . "extension/$fileName";

            /* 创建目录并将上传的插件包移动到该目录下。 */
            if(!is_dir(dirname($dest))) mkdir(dirname($dest));
            if(!move_uploaded_file($tmpName, $dest))
            {
                /* 如果文件移动失败则返回错误信息。 */
                $downloadPath = $this->app->getTmpRoot() . 'extension/';
                $errorMessage = strip_tags(sprintf($this->lang->extension->errorDownloadPathNotWritable, $downloadPath, $downloadPath));
                return $this->send(array('result' => 'fail', 'message' => $errorMessage));
            }

            /* 解压插件包, 失败则删除插件包并返回错误信息。 */
            $extension = basename($fileName, '.zip');
            $return    = $this->extension->extractPackage($extension);
            if($return->result != 'ok')
            {
                unlink($dest);
                return $this->send(array('result' => 'fail', 'message' => str_replace("'", "\'", sprintf($this->lang->extension->errorExtracted, $fileName, $return->error))));
            }

            $info = $this->extension->parseExtensionCFG($extension);
            if(isset($info->code) && $info->code != $extension)
            {
                /* 如果插件包的插件代号和插件包名字不一致，则删除解压文件并将上传的插件包改名为插件代号。 */
                $classFile = $this->app->loadClass('zfile');
                $classFile->removeDir($this->extension->pkgRoot . $extension); // 这里删掉也没事，后续安装升级的时候会再次解压。
                rename($this->app->getTmpRoot() . "/extension/$fileName", $this->app->getTmpRoot() . "/extension/{$info->code}.zip");
                $extension = $info->code;
            }

            /* 判断是否已经安装过此插件，安装过做升级操作，否则做安装操作。 */
            $info = $this->extension->getInfoFromDB($extension);
            $type = (!empty($info) && ($info->status == 'installed' || $info->status == 'deactivated')) ? 'upgrade' : 'install';
            $link = $type == 'install' ? inlink('install', "extension=$extension") : inlink('upgrade', "extension=$extension");
            return $this->send(array('result' => 'success', 'callback' => array('name' => 'loadInModal', 'params' => $link)));
        }

        $this->extensionZen->checkSafe();
        $this->view->maxUploadSize = strtoupper(ini_get('upload_max_filesize'));;
        $this->display();
    }

    /**
     * 清除插件。
     * Erase an extension.
     *
     * @param  string $extension
     * @access public
     * @return void
     */
    public function erase($extension)
    {
        $this->extensionZen->checkSafe();

        $this->view->removeCommands = $this->extension->erasePackage($extension);
        $this->view->title          = $this->lang->extension->eraseFinished;
        $this->display();
    }

    /**
     * 升级插件。
     * Update extension.
     *
     * @param  string $extension
     * @param  string $downLink
     * @param  string $md5
     * @param  string $type
     * @access public
     * @return void
     */
    public function upgrade(string $extension, string $downLink = '', string $md5 = '', string $type = '')
    {
        $this->extensionZen->checkSafe();

        $this->extension->removePackage($extension);
        $this->locate(inlink('install', "extension=$extension&downLink=&md5=$md5&type=$type&overridePackage=no&ignoreCompatible=yes&overrideFile=no&agreeLicense=no&upgrade=yes"));
    }

    /**
     * 查看插件的目录结构。
     * Browse the structure of extension.
     *
     * @param  string $extension
     * @access public
     * @return void
     */
    public function structure(string $extension)
    {
        $this->extensionZen->checkSafe();

        $this->view->extension = $this->extension->getInfoFromDB($extension);
        $this->display();
    }

    /**
     * 安全验证页面。
     * Security verification.
     *
     * @param  string $statusFile
     * @access public
     * @return void
     */
    public function safe(string $statusFile)
    {
        $statusFile = str_replace('\\', '/', $statusFile);
        $this->view->error = sprintf($this->lang->extension->noticeOkFile, $statusFile, $statusFile);
        $this->view->title = $this->lang->extension->browse;
        $this->display();
    }
}
