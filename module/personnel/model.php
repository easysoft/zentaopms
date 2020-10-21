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
    public function getAccessiblePersonnel($programID = 0, $deptID = 0, $browseType = 'all', $queryID = 0, $pager)
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

        /* Determine who can be accessed based on access control. */
        $program = $this->loadModel('program')->getPGMByID($programID);
        if($program->acl == 'private')
        {
            $personnelList = $this->dao->select('t2.id,t2.dept,t2.account,t2.role,t2.realname,t2.gender')->from(TABLE_USERVIEW)->alias('t1')
                ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account=t2.account')
                ->where("CONCAT(',', t1.programs, ',')")->like("%,$programID,%")
                ->beginIF($deptID > 0)->andWhere('t2.dept')->eq($deptID)->fi()
                ->beginIF($browseType == 'bysearch')->andWhere($accessibleQuery)->fi()
                ->page($pager)
                ->fetchAll();
        }
        else
        {
            /* The program is public, and users are judged to be accessible by permission groups. */
            $accessibleGroupID = $this->loadModel('group')->getAccessProgramGroup();
            $personnelList = $this->dao->select('t1.account,t3.role,t3.dept,t3.realname,t3.gender,t3.id')->from(TABLE_USERGROUP)->alias('t1')
                ->leftJoin(TABLE_GROUPPRIV)->alias('t2')->on('t1.group = t2.group')
                ->leftJoin(TABLE_USER)->alias('t3')->on('t1.account = t3.account')
                ->where('t1.group')->in($accessibleGroupID)
                ->andWhere('t2.module')->eq('program')
                ->andWhere('t2.method')->eq('PGMBrowse')
                ->beginIF($deptID > 0)->andWhere('t3.dept')->eq($deptID)->fi()
                ->beginIF($browseType == 'bysearch')->andWhere($accessibleQuery)->fi()
                ->page($pager)
                ->fetchAll('account');
        }

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
        $programNameList = $this->dao->select('id, name')->from(TABLE_PROGRAM)->where('type')->eq('program')->andWhere('deleted')->eq(0)->fetchPairs();
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
        $projectKeys  = array_keys($projects);
        $projectObjet = $this->dao->select('id,project,model,type,parent,path,grade,name')->from(TABLE_PROJECT)
            ->where('project')->in($projectKeys)
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
            $objectRows[$project->id] = isset($sprintAndStage[$project->id]) ? count($sprintAndStage[$project->id]) + 1 : 1;
            if(!isset($sprintAndStage[$project->id])) continue;
            foreach($sprintAndStage[$project->id] as $object)
            {
                $objectRows[$object->id] = 1;
                if($object->type == 'sprint')
                {
                    $objectRows[$object->id]   = isset($teams[$object->id]) ? count($teams[$object->id]) + 1 : 1;
                    $objectRows[$project->id] += $objectRows[$object->id] > 1 ? count($teams[$object->id]) : 0;;
                }
                elseif($object->type == 'stage' && isset($childrenStage[$object->id]))
                {
                    $objectRows[$object->id]  += count($childrenStage[$object->id]);
                    $objectRows[$project->id] += count($childrenStage[$object->id]);
                    foreach($childrenStage[$object->id] as $stage)
                    {
                        $objectRows[$stage->id]    = isset($teams[$stage->id]) ? count($teams[$stage->id]) + 1 : 1;
                        $objectRows[$object->id]  += $objectRows[$stage->id] > 1 ? count($teams[$stage->id]) : 0;
                        $objectRows[$project->id] += $objectRows[$stage->id] > 1 ? count($teams[$stage->id]) : 0;
                    }
                }
                else
                {
                    $objectRows[$object->id]   = isset($teams[$object->id]) ? count($teams[$object->id]) + 1 : 1;
                    $objectRows[$project->id] += $objectRows[$object->id] > 1 ? count($teams[$object->id]) : 0;
                }
            }
        }

        return array('sprintAndStage' => $sprintAndStage, 'childrenStage' => $childrenStage, 'teams' => $teams, 'objectRows' => $objectRows);
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
