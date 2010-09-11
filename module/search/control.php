<?php
/**
 * The control file of search module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 青岛易软天创网络科技有限公司(www.cnezsoft.com)
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
