<?php
/**
 * The control file of extension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     extension
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class extension extends control
{
    /**
     * Construct function.
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);

        $statusFile = $this->loadModel('common')->checkSafeFile();
        if($statusFile)
        {
            $this->loadModel('admin')->setMenu();
            $this->view->title      = $this->lang->extension->browse;
            $this->view->position[] = $this->lang->extension->browse;

            $statusFile = str_replace('\\', '/', $statusFile);
            $this->view->error = sprintf($this->lang->extension->noticeOkFile, $statusFile, $statusFile);
            $this->display('extension', 'safe');
            helper::end();
        }
    }

    /**
     * Browse extensions.
     *
     * @param  string   $status
     * @access public
     * @return void
     */
    public function browse($status = 'installed')
    {
        $extensions = $this->extension->getLocalExtensions($status);
        $versions   = array();
        if($extensions and $status == 'installed')
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
                    if(isset($release->latestRelease) and $extension->version != $release->latestRelease->releaseVersion and $this->extension->checkVersion($release->latestRelease->zentaoCompatible))
                    {
                        $upgradeLink = inlink('upgrade', "extension=$release->code&downLink=&md5=&type=$release->type");
                        $upgradeLink = ($release->latestRelease->charge or !$release->latestRelease->public) ? $release->latestRelease->downLink : $upgradeLink;
                        $extension->upgradeLink = $upgradeLink;
                    }
                }
            }
        }

        $this->view->title      = $this->lang->extension->browse;
        $this->view->position[] = $this->lang->extension->browse;
        $this->view->tab        = $status;
        $this->view->extensions = $extensions;
        $this->view->versions   = $versions;
        $this->display();
    }

    /**
     * Obtain extensions from the community.
     *
     * @param  string $type
     * @param  string $param
     * @access public
     * @return void
     */
    public function obtain($type = 'byUpdatedTime', $param = '', $recTotal = 0, $recPerPage = 10, $pageID = 1)
    {
        /* Init vars. */
        $type       = strtolower($type);
        $moduleID   = $type == 'bymodule' ? (int)base64_decode($param) : 0;
        $extensions = array();
        $pager      = null;

        /* Set the key. */
        if($type == 'bysearch') $param = helper::safe64Encode($this->post->key);

        /* Get results from the api. */
        $results = $this->extension->getExtensionsByAPI($type, $param, $recTotal, $recPerPage, $pageID);
        if($results)
        {
            $this->app->loadClass('pager', $static = true);
            $pager      = new pager($results->dbPager->recTotal, $results->dbPager->recPerPage, $results->dbPager->pageID);
            $extensions = $results->extensions;
        }

        $this->view->title      = $this->lang->extension->obtain;
        $this->view->position[] = $this->lang->extension->obtain;
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
     * @param  int    $upgrade
     * @access public
     * @return void
     */
    public function install($extension, $downLink = '', $md5 = '', $type = '', $overridePackage = 'no', $ignoreCompatible = 'no', $overrideFile = 'no', $agreeLicense = 'no', $upgrade = 'no')
    {
        set_time_limit(0);

        $this->view->error = '';
        $installTitle      = $upgrade == 'no' ? $this->lang->extension->install : $this->lang->extension->upgrade;
        $installType       = $upgrade == 'no' ? $this->lang->extension->installExt : $this->lang->extension->upgradeExt;
        $this->view->installType = $installType;
        $this->view->upgrade     = $upgrade;
        $this->view->title       = $installTitle . $extension;

        $statusFile = $this->loadModel('common')->checkSafeFile();
        if($statusFile)
        {
            $this->view->error = sprintf($this->lang->extension->noticeOkFile, $statusFile, $statusFile);
            return $this->display();
        }
        /* Get the package file name. */
        $packageFile = $this->extension->getPackageFile($extension);

        /* Check the package file exists or not. */
        if(!file_exists($packageFile))
        {
            $this->view->error = sprintf($this->lang->extension->errorPackageNotFound, $packageFile);
            return $this->display();
        }

        /* Checking the extension paths. */
        $return = $this->extension->checkExtensionPaths($extension);
        if($this->session->dirs2Created == false) $this->session->set('dirs2Created', $return->dirs2Created, 'admin');    // Save the dirs to be created.
        if($return->result != 'ok')
        {
            $this->view->error = $return->errors;
            return $this->display();
        }

        /* Extract the package. */
        $return = $this->extension->extractPackage($extension);
        if($return->result != 'ok')
        {
            $this->view->error = sprintf($this->lang->extension->errorExtracted, $packageFile, $return->error);
            return $this->display();
        }
        /* Get condition. e.g. zentao|depends|conflicts. */
        $condition = $this->extension->getCondition($extension);
        $installedExts = $this->extension->getLocalExtensions('installed');

        /* Check version incompatible */
        $incompatible = $condition->zentao['incompatible'];
        if($this->extension->checkVersion($incompatible))
        {
            $this->view->error = $this->lang->extension->errorIncompatible;
            return $this->display();
        }

        /* Check conflicts. */
        $conflicts = $condition->conflicts;
        if($conflicts)
        {
            $conflictsExt = '';
            foreach($conflicts as $code => $limit)
            {
                if(isset($installedExts[$code]))
                {
                    if($this->extension->compare4Limit($installedExts[$code]->version, $limit)) $conflictsExt .= $installedExts[$code]->name . " ";
                }
            }

            if($conflictsExt)
            {
                $this->view->error = sprintf($this->lang->extension->errorConflicts, $conflictsExt);
                return $this->display();
            }
        }

        /* Check Depends. */
        $depends = $condition->depends;
        if($depends)
        {
            $dependsExt = '';
            foreach($depends as $code => $limit)
            {
                $noDepends = false;
                if(isset($installedExts[$code]))
                {
                    if($this->extension->compare4Limit($installedExts[$code]->version, $limit, 'noBetween')) $noDepends = true;
                }
                else
                {
                    $noDepends = true;
                }

                $extVersion = '';
                if($limit != 'all')
                {
                    $extVersion .= '(';
                    if(!empty($limit['min'])) $extVersion .= '>=v' . $limit['min'];
                    if(!empty($limit['max'])) $extVersion .= ' <=v' . $limit['max'];
                    $extVersion .=')';
                }
                if($noDepends) $dependsExt .= $code . $extVersion . ' ' . html::a(inlink('obtain', 'type=bycode&param=' . helper::safe64Encode($code)), $this->lang->extension->installExt, '_blank') . '<br />';
            }

            if($noDepends)
            {
                $this->view->error = sprintf($this->lang->extension->errorDepends, $dependsExt);
                return $this->display();
            }
        }

        /* Check version compatible. */
        $zentaoCompatible = $condition->zentao['compatible'];
        if(!$this->extension->checkVersion($zentaoCompatible) and $ignoreCompatible == 'no')
        {
            $ignoreLink = inlink('install', "extension=$extension&downLink=&md5=$md5&type=$type&overridePackage=$overridePackage&ignoreCompatible=yes&overrideFile=$overrideFile&agreeLicense=$agreeLicense&upgrade=$upgrade");
            $returnLink = inlink('obtain');
            $this->view->error = sprintf($this->lang->extension->errorCheckIncompatible, $installType, $ignoreLink, $installType, $returnLink);
            return $this->display();
        }

        /* Check files in the package conflicts with exists files or not. */
        if($overrideFile == 'no')
        {
            $return = $this->extension->checkFile($extension);
            if($return->result != 'ok')
            {
                $overrideLink = inlink('install', "extension=$extension&downLink=&md5=$md5&type=$type&overridePackage=$overridePackage&ignoreCompatible=$ignoreCompatible&overrideFile=yes&agreeLicense=$agreeLicense&upgrade=$upgrade");
                $returnLink   = inlink('obtain');
                $this->view->error = sprintf($this->lang->extension->errorFileConflicted, $return->error, $overrideLink, $returnLink);
                return $this->display();
            }
        }

        if($upgrade == 'yes')
        {
            $newInfo = $this->extension->parseExtensionCFG($extension);
            $this->post->upgradeVersion = isset($newInfo->version) ? $newInfo->version : '';
            $oldInfo = $this->extension->getInfoFromDB($extension);
            $this->post->installedVersion = $oldInfo ? $oldInfo->version : '';
        }

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

        /* The preInstall hook file. */
        $hook = $upgrade == 'yes' ? 'preupgrade' : 'preinstall';
        if($preHookFile = $this->extension->getHookFile($extension, $hook)) include $preHookFile;

        /* Save to database. */
        $this->extension->saveExtension($extension, $type);

        /* Copy files to target directory. */
        $this->view->files = $this->extension->copyPackageFiles($extension);

        /* Judge need execute db install or not. */
        $data = new stdclass();
        $data->status = 'installed';
        $data->dirs   = $this->session->dirs2Created;
        $data->files  = $this->view->files;
        $data->installedTime = helper::now();
        $this->session->set('dirs2Created', array(), 'admin');   // clean the session.

        /* Execute the install.sql. */
        if($upgrade == 'no' and $this->extension->needExecuteDB($extension, 'install'))
        {
            $return = $this->extension->executeDB($extension, 'install');
            if($return->result != 'ok')
            {
                $this->view->error = sprintf($this->lang->extension->errorInstallDB, $return->error);
                return $this->display();
            }
        }

        /* Update status, dirs, files and installed time. */
        $this->extension->updateExtension($extension, $data);
        $this->view->downloadedPackage = false;

        /* The postInstall hook file. */
        $hook = $upgrade == 'yes' ? 'postupgrade' : 'postinstall';
        if($postHookFile = $this->extension->getHookFile($extension, $hook)) include $postHookFile;

        $this->display();
    }

    /**
     * Uninstall an extension.
     *
     * @param  string    $extension
     * @access public
     * @return void
     */
    public function uninstall($extension, $confirm = 'no')
    {
        /* Determine whether need to back up. */
        $info   = $this->extension->getInfoFromDB($extension);
        $dbFile = $this->extension->getDBFile($extension, 'uninstall');
        if($confirm == 'no' and file_exists($dbFile))
        {
            $this->view->title   = $this->lang->extension->waring;
            $this->view->confirm = 'no';
            $this->view->code    = $extension;
            return $this->display();
        }

        $dependsExts = $this->extension->checkDepends($extension);
        if($dependsExts)
        {
            $this->view->error = sprintf($this->lang->extension->errorUninstallDepends, join(' ', $dependsExts));
            return $this->display();
        }

        $preUninstallHook = $this->extension->getHookFile($extension, 'preuninstall');
        if($preUninstallHook && $info->status == 'installed') include $preUninstallHook;

        if(file_exists($dbFile)) $this->view->backupFile = $this->extension->backupDB($extension);

        $this->extension->executeDB($extension, 'uninstall');
        $this->extension->updateExtension($extension, array('status' => 'available'));
        $this->extension->togglePackageDisable($extension, 'disabled');

        $this->view->title          = $this->lang->extension->uninstallFinished;
        $this->view->removeCommands = $this->extension->removePackage($extension);

        $postUninstallHook = $this->extension->getHookFile($extension, 'postuninstall');
        if($postUninstallHook && $info->status == 'installed') include $postUninstallHook;
        $this->display();
    }

    /**
     * Activate an extension;
     *
     * @param  string    $extension
     * @access public
     * @return void
     */
    public function activate($extension, $ignore = 'no')
    {
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
        $this->view->title      = $this->lang->extension->activateFinished;
        $this->view->position[] = $this->lang->extension->activateFinished;
        $this->display();
    }

    /**
     * Deactivate an extension
     *
     * @param  string    $extension
     * @access public
     * @return void
     */
    public function deactivate($extension)
    {
        $this->extension->updateExtension($extension, array('status' => 'deactivated'));
        $this->extension->togglePackageDisable($extension, 'disabled');
        $this->view->removeCommands = $this->extension->removePackage($extension);
        $this->view->title      = $this->lang->extension->deactivateFinished;
        $this->view->position[] = $this->lang->extension->deactivateFinished;
        $this->display();
    }

    /**
     * Upload an extension
     *
     * @access public
     * @return void
     */
    public function upload()
    {
        $this->app->loadLang('file');

        $statusFile = $this->loadModel('common')->checkSafeFile();
        if($statusFile)
        {
            $this->view->error = sprintf($this->lang->extension->noticeOkFile, $statusFile, $statusFile);
            return $this->display();
        }

        if($_FILES)
        {
            if($_FILES['file']['error'] == UPLOAD_ERR_NO_FILE) return print(js::alert($this->lang->extension->errorFileNotEmpty));

            $tmpName   = $_FILES['file']['tmp_name'];
            $fileName  = $_FILES['file']['name'];
            $dest      = $this->app->getTmpRoot() . "extension/$fileName";

            if(!is_dir(dirname($dest))) mkdir(dirname($dest));
            if(!move_uploaded_file($tmpName, $dest))
            {
                $downloadPath = $this->app->getTmpRoot() . 'extension/';
                $errorMessage = strip_tags(sprintf($this->lang->extension->errorDownloadPathNotWritable, $downloadPath, $downloadPath));
                return print(js::alert($errorMessage));
            }

            $extension = basename($fileName, '.zip');
            $return    = $this->extension->extractPackage($extension);
            if($return->result != 'ok')
            {
                unlink($dest);
                return print(js::alert(str_replace("'", "\'", sprintf($this->lang->extension->errorExtracted, $fileName, $return->error))));
            }

            $info = $this->extension->parseExtensionCFG($extension);
            if(isset($info->code) and $info->code != $extension)
            {
                $classFile = $this->app->loadClass('zfile');
                $classFile->removeDir($this->extension->pkgRoot . $extension);
                rename($this->app->getTmpRoot() . "/extension/$fileName", $this->app->getTmpRoot() . "/extension/{$info->code}.zip");
                $extension = $info->code;
            }

            $info = $this->extension->getInfoFromDB($extension);
            $type = (!empty($info) and ($info->status == 'installed' or $info->status == 'deactivated')) ? 'upgrade' : 'install';
            $link = $type == 'install' ? inlink('install', "extension=$extension") : inlink('upgrade', "extension=$extension");
            return print(js::locate($link, 'parent'));
        }

        $maxUploadSize = strtoupper(ini_get('upload_max_filesize'));

        $this->view->maxUploadSize  = $maxUploadSize;
        $this->view->exceedLimitMsg = sprintf($this->lang->file->errorFileSize, $maxUploadSize);
        $this->display();
    }

    /**
     * Erase an extension.
     *
     * @param  string    $extension
     * @access public
     * @return void
     */
    public function erase($extension)
    {
        $this->view->removeCommands = $this->extension->erasePackage($extension);
        $this->view->title      = $this->lang->extension->eraseFinished;
        $this->view->position[] = $this->lang->extension->eraseFinished;
        $this->display();
    }

    /**
     * Update extension.
     *
     * @param  string $extension
     * @param  string $downLink
     * @param  string $md5
     * @param  string $type
     * @access public
     * @return void
     */
    public function upgrade($extension, $downLink = '', $md5 = '', $type = '')
    {
        $this->extension->removePackage($extension);
        $this->locate(inlink('install', "extension=$extension&downLink=&md5=$md5&type=$type&overridePackage=no&ignoreCompatible=yes&overrideFile=no&agreeLicense=no&upgrade=yes"));
    }

    /**
     * Browse the structure of extension.
     *
     * @param  int    $extension
     * @access public
     * @return void
     */
    public function structure($extension)
    {
        $this->view->extension = $this->extension->getInfoFromDB($extension);
        $this->display();
    }
}
