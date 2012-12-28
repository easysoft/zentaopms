<?php
/**
 * The control file of webapp of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <Yidong@cnezsoft.com>
 * @package     webapp
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class webapp extends control
{
    /**
     * Index for webapp. 
     * 
     * @param  int    $module 
     * @access public
     * @return void
     */
    public function index($module = 0)
    {
        $module  = (int)$module;
        $webapps = $this->webapp->getLocalApps('id', $module);

        $this->webapp->setMenu($module);

        $this->view->webapps    = $webapps;
        $this->view->moduleTree = $this->loadModel('tree')->getTreeMenu(0, 'webapp', 0, array('treeModel', 'createWebappLink'));
        $this->view->module     = $module;
        $this->display();
    }

    /**
     * Obtain web app. 
     * 
     * @param  string $type 
     * @param  string $param 
     * @param  int    $recTotal 
     * @param  int    $recPerPage 
     * @param  int    $pageID 
     * @access public
     * @return void
     */
    public function obtain($type = 'byUpdatedTime', $param = '', $recTotal = 0, $recPerPage = 10, $pageID = 1)
    {
        $this->webapp->setMenu();

        /* Init vars. */
        $type     = strtolower($type);
        $moduleID = $type == 'bymodule' ? (int)$param : 0;
        $webapps  = array();
        $pager    = null;

        /* Set the key. */
        if($type == 'bysearch') $param = helper::safe64Encode($this->post->key);

        /* Get results from the api. */
        $results = $this->webapp->getAppsByAPI($type, $param, $recTotal, $recPerPage, $pageID);
        if($results)
        {
            $this->app->loadClass('pager', $static = true);
            $pager   = new pager($results->dbPager->recTotal, $results->dbPager->recPerPage, $results->dbPager->pageID);
            $webapps = $results->webapps;
        }

        $this->view->moduleTree = $this->webapp->getModulesByAPI();
        $this->view->webapps    = $webapps;
        $this->view->installeds = $this->webapp->getLocalApps('appid');
        $this->view->pager      = $pager;
        $this->view->tab        = 'obtain';
        $this->view->type       = $type;
        $this->view->moduleID   = $moduleID;
        $this->display();
    }

    /**
     * Edit web app. 
     * 
     * @param  int    $webappID 
     * @access public
     * @return void
     */
    public function edit($webappID)
    {
        if($_POST)
        {
            $this->webapp->update($webappID);
            if(dao::isError())die(js::error(dao::getError()));
            echo "<script type='text/javascript'>";
            echo "var href = parent.parent.location.href;\n";
            echo "parent.parent.location.href = href.indexOf('#') < 0 ? href : href.substring(0, href.indexOf('#'));";
            echo "</script>";
            die();
        }

        $this->view->modules = $this->webapp->getModules();
        $this->view->webapp  = $this->webapp->getLocalAppByID($webappID);
        $this->display();
    }

    /**
     * Create web app. 
     * 
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $webappID = $this->webapp->create();
            if(dao::isError())die(js::error(dao::getError()));
            die(js::locate(inlink('index'), 'parent'));
        }

        $this->webapp->setMenu();

        $this->view->modules = $this->webapp->getModules();
        $this->display();
    }

    /**
     * Install web app. 
     * 
     * @param  int    $webappID 
     * @access public
     * @return void
     */
    public function install($webappID)
    {
        if($_POST)
        {
            $result = $this->webapp->install($webappID);
            if(dao::isError())
            {
                echo js::error(dao::getError());
                die(js::reload('parent'));
            }
            echo js::alert($this->lang->webapp->successInstall);
            die(js::locate(inlink('index'), 'parent.parent'));
        }

        $this->view->modules = $this->webapp->getModules();
        $this->display();
    }

    /**
     * uninstall web app. 
     * 
     * @param  int    $webappID 
     * @param  string $confirm 
     * @access public
     * @return void
     */
    public function uninstall($webappID, $confirm = 'no')
    {
        if($confirm == 'no') die(js::confirm($this->lang->webapp->confirmDelete, inlink('uninstall', "webappID=$webappID&confirm=yes")));

        $this->dao->delete()->from(TABLE_WEBAPP)->where('id')->eq($webappID)->exec();
        die(js::reload('parent'));
    }

    /**
     * Views add one by ajax.
     * 
     * @param  int    $webappID 
     * @access public
     * @return void
     */
    public function ajaxAddView($webappID)
    {
        $this->dao->update(TABLE_WEBAPP)->set('views=views+1')->where('id')->eq($webappID)->exec();
    }
}
