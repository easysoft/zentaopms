<?php
/**
 * The control file of build module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     build
 * @version     $Id: control.php 4992 2013-07-03 07:21:59Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
class build extends control
{
    /**
     * Create a buld.
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function create($projectID)
    {
        if(!empty($_POST))
        {
            $buildID = $this->build->create($projectID);
            if(dao::isError()) die(js::error(dao::getError()));
            $this->loadModel('action')->create('build', $buildID, 'opened');
            die(js::locate($this->createLink('project', 'build', "project=$projectID"), 'parent'));
        }

        /* Load these models. */
        $this->loadModel('story');
        $this->loadModel('bug');
        $this->loadModel('task');
        $this->loadModel('project');
        $this->loadModel('user');

        /* Set menu. */
        $this->project->setMenu($this->project->getPairs(), $projectID);

        /* Get stories and bugs. */
        $orderBy     = 'status_asc, stage_asc, id_desc';
        $stories     = $this->story->getProjectStories($projectID, $orderBy);
        $projectBugs = $this->bug->getProjectBugs($projectID); 
        $bugs        = array();
        foreach($projectBugs as $key => $bug)
        {
            if($bug->status == 'resolved')
            {
                $bugs[$key] = $bug;
                unset($projectBugs[$key]);
            }
            else if($bug->status == 'closed') 
            {
                unset($projectBugs[$key]);
            }
        }
        $bugs += $projectBugs;

        /* Assign. */
        $project = $this->loadModel('project')->getById($projectID);
        $this->view->title      = $project->name . $this->lang->colon . $this->lang->build->create;
        $this->view->position[] = html::a($this->createLink('project', 'task', "projectID=$projectID"), $project->name);
        $this->view->position[] = $this->lang->build->create;
        $this->view->products   = $this->project->getProducts($projectID);
        $this->view->projectID  = $projectID;
        $this->view->lastBuild  = $this->build->getLast($projectID);
        $this->view->users      = $this->user->getPairs('nodeleted');
        $this->view->stories    = $stories;
        $this->view->bugs       = $bugs;
        $this->view->orderBy    = $orderBy;
        $this->display();
    }

    /**
     * Edit a build.
     * 
     * @param  int    $buildID 
     * @access public
     * @return void
     */
    public function edit($buildID)
    {
        if(!empty($_POST))
        {
            $changes = $this->build->update($buildID);
            if(dao::isError()) die(js::error(dao::getError()));
            $files = $this->loadModel('file')->saveUpload('build', $buildID);

            if($changes or $files)
            {
                $fileAction = '';
                if(!empty($files)) $fileAction = $this->lang->addFiles . join(',', $files) . "\n" ;
                $actionID = $this->loadModel('action')->create('build', $buildID, 'Edited', $fileAction);
                if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            }
            die(js::locate(inlink('view', "buildID=$buildID"), 'parent'));
        }

        $this->loadModel('story');
        $this->loadModel('bug');
        $this->loadModel('project');

        /* Set menu. */
        $build = $this->build->getById((int)$buildID);
        $this->project->setMenu($this->project->getPairs(), $build->project);

        /* Get stories and bugs. */
        $orderBy = 'status_asc, stage_asc, id_desc';
        $stories = $this->story->getProjectStories($build->project, $orderBy);
        $bugs    = $this->bug->getProjectBugs($build->project); 

        /* Assign. */
        $project = $this->loadModel('project')->getById($build->project);
        $this->view->title      = $project->name . $this->lang->colon . $this->lang->build->edit;
        $this->view->position[] = html::a($this->createLink('project', 'task', "projectID=$build->project"), $project->name);
        $this->view->position[] = $this->lang->build->edit;
        $this->view->products   = $this->project->getProducts($build->project);
        $this->view->build      = $build;
        $this->view->users      = $this->loadModel('user')->getPairs('nodeleted', $build->builder);
        $this->view->stories    = $stories;
        $this->view->bugs       = $bugs;
        $this->view->orderBy    = $orderBy;
        $this->display();
    }
                                                          
    /**
     * View a build.
     * 
     * @param  int    $buildID 
     * @access public
     * @return void
     */
    public function view($buildID)
    {
        $this->loadModel('story');
        $this->loadModel('bug');

        /* Set menu. */
        $build = $this->build->getById((int)$buildID, true);
        if(!$build) die(js::error($this->lang->notFound) . js::locate('back'));

        $stories = $this->dao->select('*')->from(TABLE_STORY)->where('id')->in($build->stories)->fetchAll();
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'story');

        $bugs    = $this->dao->select('*')->from(TABLE_BUG)->where('id')->in($build->bugs)->fetchAll();
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'bug');

        $this->loadModel('project')->setMenu($this->project->getPairs(), $build->project);

        /* Assign. */
        $projects = $this->project->getPairs();
        $this->view->title      = "BUILD #$build->id $build->name - " . $projects[$build->project];
        $this->view->position[] = html::a($this->createLink('project', 'task', "projectID=$build->project"), $projects[$build->project]);
        $this->view->position[] = $this->lang->build->view;
        $this->view->products   = $this->project->getProducts($build->project);
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->build      = $build;
        $this->view->stories    = $stories;
        $this->view->bugs       = $bugs;
        $this->view->actions    = $this->loadModel('action')->getList('build', $buildID);
        $this->display();
    }
 
    /**
     * Delete a build.
     * 
     * @param  int    $buildID 
     * @param  string $confirm  yes|noe
     * @access public
     * @return void
     */
    public function delete($buildID, $confirm = 'no')
    {
        if($confirm == 'no')
        {
            die(js::confirm($this->lang->build->confirmDelete, $this->createLink('build', 'delete', "buildID=$buildID&confirm=yes")));
        }
        else
        {
            $build = $this->build->getById($buildID);
            $this->build->delete(TABLE_BUILD, $buildID);

            /* if ajax request, send result. */
            if($this->server->ajax)
            {
                if(dao::isError())
                {
                    $response['result']  = 'fail';
                    $response['message'] = dao::getError();
                }
                else
                {
                    $response['result']  = 'success';
                    $response['message'] = '';
                }
                $this->send($response);
            }
            die(js::locate($this->createLink('project', 'build', "projectID=$build->project"), 'parent'));
        }
    }

    /**
     * AJAX: get builds of a product in html select.
     * 
     * @param  int    $productID 
     * @param  string $varName      the name of the select object to create
     * @param  string $build        build to selected
     * @param  int    $index        the index of batch create bug.
     * @access public
     * @return string
     */
    public function ajaxGetProductBuilds($productID, $varName, $build = '', $index = 0)
    {
        if($varName == 'openedBuild' )  die(html::select($varName . '[]', $this->build->getProductBuildPairs($productID, 'noempty,release'), $build, 'size=4 class=form-control multiple'));
        if($varName == 'openedBuilds' ) die(html::select($varName . "[$index][]", $this->build->getProductBuildPairs($productID, 'noempty,release'), $build, 'size=4 class=form-control multiple'));
        if($varName == 'resolvedBuild') die(html::select($varName, $this->build->getProductBuildPairs($productID, 'noempty,release'), $build, "class='form-control'"));
    }

    /**
     * AJAX: get builds of a project in html select.
     * 
     * @param  int    $projectID
     * @param  string $varName      the name of the select object to create
     * @param  string $build        build to selected
     * @param  int    $index        the index of batch create bug.
     * @param  bool   $needCreate   if need to append the link of create build
     * @access public
     * @return string
     */
    public function ajaxGetProjectBuilds($projectID, $productID, $varName, $build = '', $index = 0, $needCreate = false)
    {
        if($varName == 'openedBuild')   
        {
            $builds = $this->build->getProjectBuildPairs($projectID, $productID, 'noempty,release');
            $output = html::select($varName . '[]', $builds , $build, 'size=4 class=form-control multiple');
            if(count($builds) == 1 and $needCreate)
            {
                $output .= html::a($this->createLink('build', 'create', "projectID=$projectID"), $this->lang->build->create, '_blank');
                $output .= html::a("javascript:loadProjectBuilds($projectID)", $this->lang->refresh);
            }
            die($output);
        }
        if($varName == 'openedBuilds')  die(html::select($varName . "[$index][]", $this->build->getProjectBuildPairs($projectID, $productID, 'noempty'), $build, 'size=4 class=form-control multiple'));
        if($varName == 'resolvedBuild') die(html::select($varName, $this->build->getProjectBuildPairs($projectID, $productID, 'noempty'), $build, "class='form-control'"));
        if($varName == 'testTaskBuild') die(html::select('build', $this->build->getProjectBuildPairs($projectID, $productID, 'noempty'), $build, "class='form-control'"));
    }
}
