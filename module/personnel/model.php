<?php
/**
 * The model file of personnel of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     personnel
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class personnelModel extends model
{
    /**
     * Access to program set input staff.
     *
     * @param  int       $programID
     * @param  int       $deptID
     * @param  string    $browseType
     * @param  string    $orderBy
     * @param  int       $queryID
     * @param  object    $pager
     * @access public
     * @return array
     */
    public function getAccessiblePersonnel($programID = 0, $deptID = 0, $browseType = 'all', $orderBy = 't2.id_desc', $queryID = 0, $pager)
    {
        $accessibleQuery = '';
        if($browseType == 'bysearch')
        {
            $query = $queryID ? $this->loadModel('search')->getQuery($queryID) : '';
            if($query)
            {
                $this->session->set('accessibleQuery', $query->sql);
                $this->session->set('accessibleForm', $query->form);
            }
            if($this->session->accessibleQuery == false) $this->session->set('accessibleQuery', ' 1=1');
            $accessibleQuery = $this->session->accessibleQuery;
        }

        $personnelList = $this->dao->select('t2.id,t2.dept,t2.account,t2.role,t2.realname,t2.gender')->from(TABLE_USERVIEW)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account=t2.account')
            ->where('t1.programs')->like(',%' . $programID . ',%')
            ->beginIF($deptID > 0)->andWhere('t2.dept')->eq($deptID)->fi()
            ->beginIF($browseType == 'bysearch')->andWhere($accessibleQuery)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();

        return $personnelList;
    }

    /**
     * Access to program set input staff.
     *
     * @param  int       $programID
     * @param  string    $browseType
     * @param  string    $orderBy
     * @access public
     * @return array
     */
    public function getInputPersonnel($programID = 0, $browseType = 'all', $orderBy = 'id_desc')
    {
        $personnelList = array();

        /* Get all projects under the current program. */
        $projects = $this->dao->select('id,model,type,parent,path,name')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->andWhere('path')->like("%,$programID,%")
            ->beginIF($browseType == 'scrum')->andWhere('model')->eq('scrum')->fi()
            ->beginIF($browseType == 'waterfall')->andWhere('model')->eq('waterfall')->fi()
            ->beginIF($browseType == 'parent')->andWhere('parent')->eq($programID)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->andWhere('deleted')->eq('0')
            ->orderBy($orderBy)
            ->fetchAll('id');

        $personnelList['projects'] = $projects;
        if(empty($projects)) return $personnelList;

        $sprintAndStage = $this->getSprintAndStage($projects);
        $personnelList['sprintAndStage'] = $sprintAndStage['sprintAndStage'];
        $personnelList['childrenStage']  = $sprintAndStage['childrenStage'];
        $personnelList['teams']          = $sprintAndStage['teams'];
        $personnelList['objectRows']     = $sprintAndStage['objectRows'];

        /* Get the program name for each level. */
        $programNameList = $this->loadModel('program')->getPGMPairs();
        foreach($personnelList['projects'] as $id => $project)
        {
            $path = explode(',', $project->path);
            $path = array_filter($path);
            unset($path[$id]);
            $programName = '';
            foreach($path as $program)
            {
                if($program == $id) continue;
                $programName .= '/' . $programNameList[$program];
            }
            $personnelList['projects'][$id]->programName = $programName;
        }

        return $personnelList;
    }

    /**
     * Access to data on stages and sprints.
     *
     * @param  object    $projects
     * @access public
     * @return array
     */
    public function getSprintAndStage($projects)
    {
        /* Get all sprints and iterations under the project. */
        $userViewID   = array_merge(array(0), explode(',', $this->app->user->view->stages), explode(',', $this->app->user->view->sprints));
        $projectKeys  = array_keys($projects);
        $projectObjet = $this->dao->select('id,project,model,type,parent,path,grade,name')->from(TABLE_PROJECT)
            ->where('project')->in($projectKeys)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($userViewID)->fi()
            ->andWhere('deleted')->eq('0')
            ->orderBy('id_desc')
            ->fetchAll();

        /* Get the team's root ID, separate the parent-child iteration. */
        $rootIDList     = array();
        $sprintAndStage = array();
        $childrenStage  = array();
        foreach($projectObjet as $id => $object)
        {
            if($object->grade == 1)
            {
                $sprintAndStage[$object->project][] = $object;
            }
            else
            {
                $childrenStage[$object->parent][] = $object;
            }
            $rootIDList[] = $object->id;
        }

        $teams = $this->dao->select('t1.id,t1.root,t1.type,t1.role,t1.account,t2.realname')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account=t2.account')
            ->where('t1.root')->in($rootIDList)
            ->andWhere('t1.type')->in('stage,sprint')
            ->fetchGroup('root', 'id');

        /* Calculate the number of cross rows for iterations and sprints. */
        $objectRows = array();
        foreach($projects as $project)
        {
            $objectRows[$project->id] = isset($sprintAndStage[$project->id]) ? count($sprintAndStage[$project->id]) : 1;
            if(!isset($sprintAndStage[$project->id])) continue;
            foreach($sprintAndStage[$project->id] as $object)
            {
                $objectRows[$object->id] = 1;
                if($object->type == 'sprint')
                {
                    $objectRows[$object->id]   = isset($teams[$object->id]) ? count($teams[$object->id]) + 1 : 1;
                    $objectRows[$project->id] += isset($teams[$object->id]) ? count($teams[$object->id]) + 1 : 0;;
                }
                elseif($object->type == 'stage' && isset($childrenStage[$object->id]))
                {
                    $objectRows[$object->id] += count($childrenStage[$object->id]);
                    foreach($childrenStage[$object->id] as $stage)
                    {
                        $objectRows[$stage->id]    = isset($teams[$stage->id]) ? count($teams[$stage->id]) + 1 : 1;
                        $objectRows[$object->id]  += isset($teams[$stage->id]) ? count($teams[$stage->id]) : 0;
                        $objectRows[$project->id] += $objectRows[$stage->id];
                    }
                }
                else
                {
                    $objectRows[$object->id]   = isset($teams[$object->id]) ? count($teams[$object->id]) + 1 : 1;
                    $objectRows[$project->id] += $objectRows[$object->id];
                }
            }
        }

        return array('sprintAndStage' => $sprintAndStage, 'childrenStage' => $childrenStage, 'teams' => $teams, 'objectRows' => $objectRows);
    }

    /**
     * Get the treemenu of departments.
     *
     * @param  int     $deptID
     * @param  string  $userFunc
     * @param  int     $param
     * @access public
     * @return string
     */
    public function getTreeMenu($deptID = 0, $userFunc, $param = 0)
    {
        $deptMenu = array();
        $stmt = $this->dbh->query($this->buildMenuQuery($deptID));
        while($dept = $stmt->fetch())
        {
            $linkHtml = call_user_func($userFunc, $dept, $param);

            if(isset($deptMenu[$dept->id]) and !empty($deptMenu[$dept->id]))
            {
                if(!isset($deptMenu[$dept->parent])) $deptMenu[$dept->parent] = '';
                $deptMenu[$dept->parent] .= "<li>$linkHtml";
                $deptMenu[$dept->parent] .= "<ul>".$deptMenu[$dept->id]."</ul>\n";
            }
            else
            {
                if(isset($deptMenu[$dept->parent]) and !empty($deptMenu[$dept->parent]))
                {
                    $deptMenu[$dept->parent] .= "<li>$linkHtml\n";
                }
                else
                {
                    $deptMenu[$dept->parent] = "<li>$linkHtml\n";
                }
            }
            $deptMenu[$dept->parent] .= "</li>\n";
        }

        krsort($deptMenu);
        $deptMenu = array_pop($deptMenu);
        $lastMenu = "<ul class='tree' data-ride='tree' data-name='tree-dept'>{$deptMenu}</ul>\n";
        return $lastMenu;
    }

    /**
     * Build the query.
     *
     * @param  int    $rootDeptID
     * @access public
     * @return object
     */
    public function buildMenuQuery($rootDeptID)
    {
        $rootDept = $this->loadModel('dept')->getByID($rootDeptID);
        if(!$rootDept)
        {
            $rootDept = new stdclass();
            $rootDept->path = '';
        }

        return $this->dao->select('*')->from(TABLE_DEPT)
            ->beginIF($rootDeptID > 0)->where('path')->like($rootDept->path . '%')->fi()
            ->orderBy('grade desc, `order`')
            ->get();
    }

    /**
     * Create access links by department.
     *
     * @param  object  $dept
     * @param  int     $programID
     * @access public
     * @return string
     */
    public function createMemberLink($dept = 0, $programID = 0)
    {
        return html::a(helper::createLink('personnel', 'accessible', "program={$programID}&deptID={$dept->id}"), $dept->name, '_self', "id='dept{$dept->id}'");
    }

    /**
     * Build search form.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildSearchForm($queryID = 0, $actionURL = '')
    {
        $this->config->personnel->accessible->search['actionURL'] = $actionURL;
        $this->config->personnel->accessible->search['queryID']   = $queryID;

        $this->loadModel('search')->setSearchParams($this->config->personnel->accessible->search);
    }
}
