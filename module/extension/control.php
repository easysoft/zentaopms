<?php
/**
 * The control file of extension module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
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
     * @param  string $type     browse type.
     * @access public
     * @return void
     */
    public function browse($type = 'installed')
    {
        $this->view->header->title = $this->lang->extension->browse;
        $this->view->position[]    = $this->lang->extension->browse;
        $this->view->tab           = $type;
        $this->view->extensions    = $this->extension->getLocalExtensions($type);
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
     * @param  string $forceInstall 
     * @access public
     * @return void
     */
    public function install($extension, $downLink = '', $md5 = '', $forceInstall = 'no')
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
            if(file_exists($packageFile) and $forceInstall == 'no') 
            {
                $forceInstallLink = inlink('install', "extension=$extension&downLink=$downLink&md5=$md5&forecInstall=yes");
                $this->view->error = sprintf($this->lang->extension->errorPackageFileExists, $packageFile, $forceInstallLink);
                die($this->display());
            }

            /* Download the package file. */
            $this->extension->downloadPackage($extension, helper::safe64Decode($downLink));
            if(!file_exists($packageFile))
            {
                $this->view->error = sprintf($this->lang->extension->errorDownloadFailed, $packageFile);
                die($this->display());
            }
            elseif(md5_file($packageFile) != $md5)
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
            $this->view->error = $return->errors;
            die($this->display());
        }

        /* Extract the package. */
        $this->extension->extractPackage($extension);

        /* Save to database. */
        $this->extension->save2DB($extension);

        /* Copy files to target directory. */
        $this->view->files = $this->extension->copyPackageFiles($extension);

        /* Judge need execute db install or not. */
        if($this->extension->needExecuteDB($extension, 'install'))
        {
            $return = $this->extension->executeDB($extension, 'install');
            if($return->result != 'ok')
            {
                $this->view->error = sprintf($this->lang->extension->errorInstallDB, $return->error);
                die($this->display());
            }
            $this->extension->updateStatus($extension, 'installed');
        }
        else
        {
            $this->extension->updateStatus($extension, 'installed');
        }

        $this->view->downloadedPackage = !empty($downLink);

        $this->display();
    }

    public function uninstall()
    {
    }

    public function activate()
    {
    }

    public function deactivate()
    {
    }
}
