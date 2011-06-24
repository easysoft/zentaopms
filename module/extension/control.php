<?php
/**
 * The control file of extension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
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
        $this->view->header->title = $this->lang->extension->browse;
        $this->view->position[]    = $this->lang->extension->browse;
        $this->view->tab           = $status;
        $this->view->extensions    = $this->extension->getLocalExtensions($status);
        $this->display();
    }

    /**
     * Obtain an extension from the community.
     * 
     * @param  string $type 
     * @param  string $param 
     * @access public
     * @return void
     */
    public function obtain($type = 'byDownloads', $param = '', $recTotal = 0, $recPerPage = 10, $pageID = 1)
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
     * @param  string $overridePackage 
     * @param  string $ignoreCompitable 
     * @param  string $overrideFile
     * @access public
     * @return void
     */
    public function install($extension, $downLink = '', $md5 = '', $overridePackage = 'no', $ignoreCompitable = 'no', $overrideFile = 'no')
    {
        $this->view->error = '';
        $this->view->header->title = $this->lang->extension->install . $this->lang->colon . $extension;

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
                $overrideLink = inlink('install', "extension=$extension&downLink=$downLink&md5=$md5&overridePackage=yes&ignoreCompitable=$ignoreCompitable");
                $this->view->error = sprintf($this->lang->extension->errorPackageFileExists, $packageFile, $overrideLink);
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
        if($return->result != 'ok')
        {
            $this->session->set('dirs2Created', $return->dirs2Created);   // Save the dirs to be created.

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
        if(!$this->extension->checkVersion($zentaoVersion) and $ignoreCompitable == 'no')
        {
            $ignoreLink = inlink('install', "extension=$extension&downLink=$downLink&md5=$md5&overridePackage=$overridePackage&ignoreCompitable=yes");
            $returnLink = inlink('obtain');
            $this->view->error = sprintf($this->lang->extension->errorCheckIncompatible, $ignoreLink, $returnLink);
            die($this->display());
        }

        /* Check files in the package conflicts with exists files or not. */
        if($overrideFile == 'no')
        {
            $return = $this->extension->checkFile($extension);
            if($return->result != 'ok')
            {
                $overrideLink = inlink('install', "extension=$extension&downLink=$downLink&md5=$md5&overridePackage=$overridePackage&ignoreCompitable=$ignoreCompitable&overrideFile=yes");
                $returnLink   = inlink('obtain');
                $this->view->error = sprintf($this->lang->extension->errorFileConflicted, $return->error, $overrideLink, $returnLink);
                die($this->display());
            }
        }

        /* Save to database. */
        $this->extension->saveExtension($extension);

        /* Copy files to target directory. */
        $this->view->files = $this->extension->copyPackageFiles($extension);

        /* Judge need execute db install or not. */
        $data->status = 'installed';
        $data->dirs   = $this->session->dirs2Created;
        $data->files  = $this->view->files;
        $data->installedTime = helper::now();

        if($this->extension->needExecuteDB($extension, 'install'))
        {
            $return = $this->extension->executeDB($extension, 'install');
            if($return->result != 'ok')
            {
                $this->view->error = sprintf($this->lang->extension->errorInstallDB, $return->error);
                die($this->display());
            }
            $this->extension->updateExtension($extension, $data);
        }
        else
        {
            $this->extension->updateExtension($extension, $data);
        }

        $this->view->downloadedPackage = !empty($downLink);

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
        $this->extension->executeDB($extension, 'uninstall');
        $this->extension->updateExtension($extension, array('status' => 'available'));
        $this->view->removeCommands = $this->extension->removePackage($extension);
        $this->view->header->title  = $this->lang->extension->uninstallFinished;
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
            $this->locate(inlink('install', "extension=$extension"));
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
}
