<?php
/**
 * The control file of search module of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     search
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class search extends control
{
    public function buildForm($module, $searchFields, $fieldParams, $actionURL, $queryID = 0)
    {
        $this->search->initSession($module, $searchFields, $fieldParams);

        $this->view->module       = $module;
        $this->view->groupItems   = $this->config->search->groupItems;
        $this->view->searchFields = $searchFields;
        $this->view->actionURL    = $actionURL;
        $this->view->fieldParams  = $this->search->setDefaultParams($searchFields, $fieldParams);
        $this->view->queries      = $this->search->getQueryPairs($module);
        $this->view->queryID      = $queryID;
        $this->display();
    }

    public function buildQuery()
    {
        $this->search->buildQuery();
        die(js::locate($this->post->actionURL, 'parent'));
    }

    public function saveQuery()
    {
        $this->search->saveQuery();
        if(dao::isError()) die(js::error(dao::getError()));
        die('success');
    }

    public function deleteQuery($queryID)
    {
        $this->dao->delete()->from(TABLE_USERQUERY)->where('id')->eq($queryID)->andWhere('account')->eq($this->app->user->account)->exec();
        die(js::reload('parent'));
    }

    public function select($productID, $projectID, $module, $moduleID)
    {
        $this->loadModel('product');
        $this->loadModel('task');

        if($module == 'story')
        {
            $fieldParams  = $this->config->product->search;
            $moduleTitles = $projectID ? $this->loadModel('story')->getProjectStoryPairs($projectID) : $this->loadModel('story')->getProductStoryPairs($productID);
        }
        else if($module == 'task')
        {
            $fieldParams  = $this->config->task->search;
            $moduleTitles = $this->loadModel('task')->getProjectTaskPairs($projectID);
        }
        $searchFields = $fieldParams['fields'];
        $fieldParams  = $fieldParams['params'];
        $this->search->initSession($module, $searchFields, $fieldParams);
        
        if(!empty($_POST['value1'])) $moduleTitles = $this->search->getBySelect($module, array_keys($moduleTitles), $_POST);
        
        $this->view->module       = $module;
        $this->view->moduleID     = $moduleID;
        $this->view->moduleTitles = $moduleTitles;
        $this->view->searchFields = $searchFields;
        $this->view->fieldParams  = $this->search->setDefaultParams($searchFields, $fieldParams);
        
        die($this->display());
    }
}
