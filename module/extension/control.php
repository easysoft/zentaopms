<?php
/**
 * The control file of extension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     extension
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class extension extends control
{
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
                    if($extension->version != $release->latestRelease->releaseVersion and $this->extension->checkVersion($release->latestRelease->zentaoVersion))
                    {
                        $upgradeLink = inlink('upgrade', "extension=$release->code&downLink=" . helper::safe64Encode($release->latestRelease->downLink) . "&md5={$release->latestRelease->md5}&type=$release->type");
                        $upgradeLink = ($release->latestRelease->charge or !$release->latestRelease->public) ? $release->latestRelease->downLink : $upgradeLink;
                        $extension->upgradeLink = $upgradeLink;
                    }
                }
            }
        }

        $this->view->header->title = $this->lang->extension->browse;
        $this->view->position[]    = $this->lang->extension->browse;
        $this->view->tab           = $status;
        $this->view->extensions    = $extensions;
        $this->view->versions      = $versions;
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
        $moduleID   = $type == 'bymodule' ? (int)$param : 0;
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

        $this->view->moduleTree = $this->extension->getModulesByAPI();
        $this->view->extensions = $extensions;
        $this->view->installeds = $this->extension->getLocalExtensions('installed');
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
        $this->view->error = '';
        $installTitle      = $upgrade == 'no' ? $this->lang->extension->install : $this->lang->extension->upgrade;
        $installType       = $upgrade == 'no' ? $this->lang->extension->installExt : $this->lang->extension->upgradeExt; 
        $this->view->installType   = $installType;
        $this->view->upgrade       = $upgrade;
        $this->view->header->title = $installTitle . $this->lang->colon . $extension;

        /* Get the package file name. */
        $packageFile = $this->extension->getPackageFile($extension);

        if($downLink)
        {
            /* Checking download path. */
            $return = $this->extension->checkDownloadPath();
            if($return->result != 'ok')
            {
                $this->view->error = $return->error;
                die($this->display());
            }

            /* Check file exists or not. */
            if(file_exists($packageFile) and $overridePackage == 'no')
            {
                $overrideLink = inlink('install', "extension=$extension&downLink=$downLink&md5=$md5&type=$type&overridePackage=yes&ignoreCompatible=$ignoreCompatible&overrideFile=$overrideFile&agreeLicense=$agreeLicense&upgrade=$upgrade");
                $this->view->error = sprintf($this->lang->extension->errorPackageFileExists, $packageFile, $installType, $overrideLink);
                die($this->display());
            }

            /* Download the package file. */
            $this->extension->downloadPackage($extension, helper::safe64Decode($downLink));
            if(!file_exists($packageFile))
            {
                $this->view->error = sprintf($this->lang->extension->errorDownloadFailed, $packageFile);
                die($this->display());
            }
            elseif($md5 != '' and md5_file($packageFile) != $md5)
            {
                unlink($packageFile);
                $this->view->error = sprintf($this->lang->extension->errorMd5Checking, $packageFile);
                die($this->display());
            }
        }

        /* Check the package file exists or not. */
        if(!file_exists($packageFile)) 
        {
            $this->view->error = sprintf($this->lang->extension->errorPackageNotFound, $packageFile);
            die($this->display());
        }

        /* Checking the extension pathes. */
        $return = $this->extension->checkExtensionPathes($extension);
        if($this->session->dirs2Created == false) $this->session->set('dirs2Created', $return->dirs2Created);    // Save the dirs to be created.
        if($return->result != 'ok')
        {
            $this->view->error = $return->errors;
            die($this->display());
        }

        /* Extract the package. */
        $return = $this->extension->extractPackage($extension);
        if($return->result != 'ok')
        {
            $this->view->error = sprintf($this->lang->extension->errorExtracted, $packageFile, $return->error);
            die($this->display());
        }

        /* Check version comptiable. */
        $zentaoVersion = $this->extension->getZentaoVersion($extension);
        if(!$this->extension->checkVersion($zentaoVersion) and $ignoreCompatible == 'no')
        {
            $ignoreLink = inlink('install', "extension=$extension&downLink=$downLink&md5=$md5&type=$type&overridePackage=$overridePackage&ignoreCompatible=yes&overrideFile=$overrideFile&agreeLicense=$agreeLicense&upgrade=$upgrade");
            $returnLink = inlink('obtain');
            $this->view->error = sprintf($this->lang->extension->errorCheckIncompatible, $installType, $ignoreLink, $installType, $returnLink);
            die($this->display());
        }

        /* Check files in the package conflicts with exists files or not. */
        if($overrideFile == 'no')
        {
            $return = $this->extension->checkFile($extension);
            if($return->result != 'ok')
            {
                $overrideLink = inlink('install', "extension=$extension&downLink=$downLink&md5=$md5&type=$type&overridePackage=$overridePackage&ignoreCompatible=$ignoreCompatible&overrideFile=yes&agreeLicense=$agreeLicense&upgrade=$upgrade");
                $returnLink   = inlink('obtain');
                $this->view->error = sprintf($this->lang->extension->errorFileConflicted, $return->error, $overrideLink, $returnLink);
                die($this->display());
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
            $agreeLink     = inlink('install', "extension=$extension&downLink=$downLink&md5=$md5&type=$type&overridePackage=$overridePackage&ignoreCompatible=$ignoreCompatible&overrideFile=$overrideFile&agreeLicense=yes&upgrade=$upgrade");
            $this->view->license   = $license;
            $this->view->author    = $extensionInfo->author;
            $this->view->agreeLink = $agreeLink;
            die($this->display());
        }

        /* The preInstall hook file. */
        $hook = $upgrade == 'yes' ? 'preupgrade' : 'preinstall';
        if($preHookFile = $this->extension->getHookFile($extension, $hook)) include $preHookFile;

        /* Save to database. */
        $this->extension->saveExtension($extension, $type);

        /* Copy files to target directory. */
        $this->view->files = $this->extension->copyPackageFiles($extension);

        /* Judge need execute db install or not. */
        $data->status = 'installed';
        $data->dirs   = $this->session->dirs2Created;
        $data->files  = $this->view->files;
        $data->installedTime = helper::now();
        $this->session->set('dirs2Created', array());   // clean the session.

        /* Execute the install.sql. */
        if($this->extension->needExecuteDB($extension, 'install'))
        {
            $return = $this->extension->executeDB($extension, 'install');
            if($return->result != 'ok')
            {
                $this->view->error = sprintf($this->lang->extension->errorInstallDB, $return->error);
                die($this->display());
            }
        }

        /* Update status, dirs, files and installed time. */
        $this->extension->updateExtension($extension, $data);
        $this->view->downloadedPackage = !empty($downLink);

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
    public function uninstall($extension)
    {
        if($preUninstallHook = $this->extension->getHookFile($extension, 'preuninstall')) include $preUninstallHook;

        $this->extension->executeDB($extension, 'uninstall');
        $this->extension->updateExtension($extension, array('status' => 'available'));
        $this->view->removeCommands = $this->extension->removePackage($extension);
        $this->view->header->title  = $this->lang->extension->uninstallFinished;

        if($postUninstallHook = $this->extension->getHookFile($extension, 'postuninstall')) include $postUninstallHook;
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
                die($this->display());
            }
        }

        $this->extension->copyPackageFiles($extension);
        $this->extension->updateExtension($extension, array('status' => 'installed'));
        $this->view->header->title = $this->lang->extension->activateFinished;
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
        $this->view->removeCommands = $this->extension->removePackage($extension);
        $this->view->header->title  = $this->lang->extension->deactivateFinished;
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
        if($_FILES)
        {
            $tmpName   = $_FILES['file']['tmp_name'];
            $fileName  = $_FILES['file']['name'];
            $extension = basename($fileName, '.zip');
            move_uploaded_file($tmpName, $this->app->getTmpRoot() . "/extension/$fileName");
            $info = $this->extension->getByExtension($extension);
            $type = $info->status == 'installed' ? 'upgrade' : 'install';
            $link = $type == 'install' ? inlink('install', "extension=$extension") : inlink('upgrade', "extension=$extension");
            $this->locate($link);
        }
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
        $this->view->header->title  = $this->lang->extension->eraseFinished;
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
        $this->locate(inlink('install', "extension=$extension&downLink=$downLink&md5=$md5&type=$type&overridePackage=no&ignoreCompatible=yes&overrideFile=no&agreeLicense=no&upgrade=yes"));
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
