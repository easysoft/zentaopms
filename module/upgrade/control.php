<?php
/**
 * The control file of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id: control.php 5119 2013-07-12 08:06:42Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
class upgrade extends control
{
    /**
     * The index page.
     * 
     * @access public
     * @return void
     */
    public function index()
    {
        /* Locate to index page of my module, if upgrade.php does not exist. */
        $upgradeFile = $this->app->wwwRoot . 'upgrade.php';
        if(!file_exists($upgradeFile)) $this->locate($this->createLink('my', 'index'));

        if(version_compare($this->config->installedVersion, '6.4', '<=')) $this->locate(inlink('license'));
        $this->locate(inlink('backup'));
    }

    /**
     * Check agree license.
     * 
     * @access public
     * @return void
     */
    public function license()
    {   
        if($this->get->agree == true) $this->locate(inlink('backup'));

        $this->view->title   = $this->lang->upgrade->common;
        $this->view->license = file_get_contents($this->app->getBasePath() . 'doc/LICENSE');
        $this->display();
    }   

    /**
     * Backup.
     * 
     * @access public
     * @return void
     */
    public function backup()
    {
        $this->view->title = $this->lang->upgrade->common;
        $this->display();
    }

    /**
     * Select the version of old zentao.
     * 
     * @access public
     * @return void
     */
    public function selectVersion()
    {
        $version = str_replace(array(' ', '.'), array('', '_'), $this->config->installedVersion);
        $version = strtolower($version);
        $this->view->title      = $this->lang->upgrade->common . $this->lang->colon . $this->lang->upgrade->selectVersion;
        $this->view->position[] = $this->lang->upgrade->common;
        $this->view->version    = $version;
        $this->display();
    }

    /**
     * Confirm the version.
     * 
     * @access public
     * @return void
     */
    public function confirm()
    {
        $this->view->title       = $this->lang->upgrade->confirm;
        $this->view->position[]  = $this->lang->upgrade->common;
        $this->view->confirm     = $this->upgrade->getConfirm($this->post->fromVersion);
        $this->view->fromVersion = $this->post->fromVersion;

        /* When sql is empty then skip it. */
        if(empty($this->view->confirm)) $this->locate(inlink('execute', "fromVersion={$this->post->fromVersion}"));

        $this->display();
    }

    /**
     * Execute the upgrading.
     * 
     * @access public
     * @return void
     */
    public function execute($fromVersion = '')
    {
        $fromVersion = isset($_POST['fromVersion']) ? $this->post->fromVersion : $fromVersion;
        $this->upgrade->execute($fromVersion);

        $this->view->title      = $this->lang->upgrade->result;
        $this->view->position[] = $this->lang->upgrade->common;

        if(!$this->upgrade->isError())
        {
            $this->app->loadLang('install');
            $this->view->result = 'success';
        }
        else
        {
            $this->view->result = 'fail';
            $this->view->errors = $this->upgrade->getError();
        }
        $this->display();
        if($this->view->result == 'fail') die();

       @unlink($this->app->getAppRoot() . 'www/install.php');
       @unlink($this->app->getAppRoot() . 'www/upgrade.php');
    }

    /**
     * Check extension.
     * 
     * @access public
     * @return void
     */
    public function checkExtension()
    {
            $this->loadModel('extension');
            $extensions = $this->extension->getLocalExtensions('installed');

            $versions = array();
            foreach($extensions as $code => $extension) $versions[$code] = $extension->version;

            $incompatibleExts = $this->extension->checkIncompatible($versions);
            $extensionsName   = array();
            if(empty($incompatibleExts)) $this->locate(inlink('selectVersion'));

            $removeCommands = array();
            foreach($incompatibleExts as $extension)
            {
                $this->extension->updateExtension($extension, array('status' => 'deactivated'));
                $removeCommands[$extension] = $this->extension->removePackage($extension);
                $extensionsName[$extension] = $extensions[$extension]->name;
            }

            $data = '';
            if($extensionsName)
            {
                $data .= "<h3>{$this->lang->upgrade->forbiddenExt}</h3>";
                $data .= '<ul>';
                foreach($extensionsName as $extension => $extensionName)
                {
                    $data .= "<li>$extensionName";
                    if($removeCommands[$extension]) $data .= '<p>'. $this->lang->extension->unremovedFiles . '</p> <p>' . join('<br />', $removeCommands[$extension]) . '</p>';
                    $data .= '</li>';
                }
                $data .= '</ul>';
            }

            $this->view->title = $this->lang->upgrade->checkExtension;
            $this->view->data  = $data;
            $this->display();
    }
}
