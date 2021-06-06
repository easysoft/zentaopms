<?php
/**
 * The control file of projectBuild module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     projectBuild
 * @version     $Id: control.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class projectBuild extends control
{
    /**
     * Browse builds of a project.
     *
     * @param  int    $projectID
     * @param  string $type      all|product|bysearch
     * @param  int    $param
     * @access public
     * @return void
     */
    public function browse($projectID = 0, $type = 'all', $param = 0)
    {
        $this->loadModel('project');
        $this->loadModel('execution');
        $this->loadModel('build');
        $project = $this->project->getByID($projectID);

        /* Get products' list. */
        $products = $this->project->getProducts($projectID, false);
        $products = array('' => '') + $products;

        /* Build the search form. */
        $type      = strtolower($type);
        $queryID   = ($type == 'bysearch') ? (int)$param : 0;
        $actionURL = $this->createLink('projectbuild', 'browse', "projectID=$projectID&type=bysearch&queryID=myQueryID");

        $executions = $this->execution->getByProject($projectID, 'all', '', true);
        $this->config->build->search['fields']['project'] = $this->project->lang->executionCommon;
        $this->config->build->search['params']['project'] = array('operator' => '=', 'control' => 'select', 'values' => array('' => '') + $executions);

        $this->project->buildProjectBuildSearchForm($products, $queryID, $actionURL, 'project');
        commonModel::setAppObjectID('project', $projectID);

        if($type == 'bysearch')
        {
            $builds = $this->build->getProjectBuildsBySearch((int)$projectID, (int)$param);
        }
        else
        {
            $builds = $this->build->getProjectBuilds((int)$projectID, $type, $param);
        }

        /* Set project builds. */
        $projectBuilds = array();
        if(!empty($builds))
        {
            foreach($builds as $build) $projectBuilds[$build->product][] = $build;
        }

        /* Header and position. */
        $this->view->title      = $project->name . $this->lang->colon . $this->lang->execution->build;
        $this->view->position[] = html::a(inlink('browse', "projectID=$projectID"), $project->name);
        $this->view->position[] = $this->lang->execution->build;

        $this->view->users         = $this->loadModel('user')->getPairs('noletter');
        $this->view->buildsTotal   = count($builds);
        $this->view->projectBuilds = $projectBuilds;
        $this->view->projectID     = $projectID;
        $this->view->product       = $type == 'product' ? $param : 'all';
        $this->view->project       = $project;
        $this->view->products      = $products;
        $this->view->executions    = $executions;
        $this->view->type          = $type;

        $this->display();
    }
}
