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
     * @param  int    $downLink 
     * @access public
     * @return void
     */
    public function install($extension, $downLink = '')
    {
        if($downLink) $this->extension->download($extension, helper::safe64Decode($downLink));

        $packgeFile = $this->extension->getPackageFile($extension);
        if(!file_exists($packgeFile)) die(js::error('not found'));

        $this->extension->install($extension);
        echo 'installed';
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
