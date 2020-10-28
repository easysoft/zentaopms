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
        if($program->acl == 'open')
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
        else
        {
            $personnelList = $this->dao->select('t2.id,t2.dept,t2.account,t2.role,t2.realname,t2.gender')->from(TABLE_USERVIEW)->alias('t1')
                ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account=t2.account')
                ->where("CONCAT(',', t1.programs, ',')")->like("%,$programID,%")
                ->beginIF($deptID > 0)->andWhere('t2.dept')->eq($deptID)->fi()
                ->beginIF($browseType == 'bysearch')->andWhere($accessibleQuery)->fi()
                ->page($pager)
                ->fetchAll();
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
     * Access to program set input staff.
     *
     * @param  int       $objectID
     * @param  string    $objectType  program|project|product|sprint
     * @param  string    $orderBy
     * @param  object    $pager
     * @access public
     * @return array
     */
    public function getWhitelist($objectID = 0, $objectType = '', $orderBy = 'id_desc', $pager = '')
    {
        return $this->dao->select('t1.id,t1.account,t2.realname,t2.role,t2.phone,t2.qq,t2.weixin,t2.email')->from(TABLE_ACL)->alias('t1')
            ->leftjoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('t1.objectID')->eq($objectID)
            ->andWhere('t1.type')->eq('whitelist')
            ->andWhere('t1.objectType')->eq($objectType)
            ->orderBy($orderBy)
            ->beginIF(!empty($pager))->page($pager)->fi()
            ->fetchAll();
    }

    /**
     * Get whitelisted accounts.
     *
     * @param  int    $objectID
     * @param  string $objectType
     * @access public
     * @return array
     */
    public function getWhitelistAccount($objectID = 0, $objectType = '')
    {
        return $this->dao->select('account')->from(TABLE_ACL)->where('objectID')->eq($objectID)->andWhere('objectType')->eq($objectType)->fetchPairs('account');
    }

    /**
     * Adding users to access control lists.
     *
     * @param  array   $users
     * @param  string  $objectType  program|project|product|sprint
     * @param  int     $objectID
     * @param  string  $type    whitelist|blacklist
     * @param  string  $source  upgrade|add|sync
     * @access public
     * @return void
     */
    public function updateWhitelist($users = array(), $objectType = '', $objectID = 0, $type = 'whitelist', $source = 'add')
    {
        $oldWhitelist = $this->dao->select('account,objectType,objectID,type,source,`desc`')->from(TABLE_ACL)->where('objectID')->eq($objectID)->andWhere('objectType')->eq($objectType)->fetchAll('account');
        $this->dao->delete()->from(TABLE_ACL)->where('objectID')->eq($objectID)->andWhere('objectType')->eq($objectType)->exec();

        $users = array_filter($users);
        $users = array_unique($users);

        $accounts = array();
        foreach($users as $account)
        {
            if(isset($oldWhitelist[$account]))
            {
                $this->dao->insert(TABLE_ACL)->data($oldWhitelist[$account])->exec();
                $accounts[$account] = $account;
                continue;
            }

            $acl             = new stdClass();
            $acl->account    = $account;
            $acl->objectType = $objectType;
            $acl->objectID   = $objectID;
            $acl->type       = $type;
            $acl->source     = $source;
            $this->dao->insert(TABLE_ACL)->data($acl)->autoCheck()->exec();
            $accounts[$account] = $account;
        }

        $objectTable = $objectType == 'product' ? TABLE_PRODUCT : TABLE_PROJECT;
        $whitelist   = ',' . implode(',', $accounts);
        $this->dao->update($objectTable)->set('whitelist')->eq($whitelist)->where('id')->eq($objectID)->exec();

        $deletedAccouns = array();
        foreach($oldWhitelist as $account => $whitelist)
        {
            if(!isset($accounts[$account])) $deletedAccouns[] = $account;
        }

        /* Synchronization of people from the product whitelist to the program set. */
        if($objectType == 'product')
        {
            $product = $this->loadModel('product')->getById($objectID);
            $programWhitelist = $this->getWhitelistAccount($product->program, 'program');
            $newWhitelist     = array_merge($programWhitelist, $accounts);
            $source           = $source == 'upgrade' ? 'upgrade' : 'sync';
            $this->updateWhitelist($newWhitelist, 'program', $product->program, 'whitelist', $source);

            /* Removal of persons from centralized program whitelisting. */
            foreach($deletedAccouns as $account) $this->deleteProgramWhitelist($objectID, $account);
        }

        /* Synchronization of people from the sprint white list to the project. */
        if($objectType == 'sprint')
        {
            $project = $this->dao->select('project')->from(TABLE_PROJECT)->where('id')->eq($objectID)->fetch('project', '');
            $projectWhitelist = $this->getWhitelistAccount($project, 'project');
            $newWhitelist     = array_merge($projectWhitelist, $accounts);
            $source           = $source == 'upgrade' ? 'upgrade' : 'sync';
            $this->updateWhitelist($newWhitelist, 'project', $project, 'whitelist', $source);

            /* Removal of whitelisted persons from projects. */
            foreach($deletedAccouns as $account) $this->deleteProjectWhitelist($objectID, $account);
        }
    }

    /**
     * Adding users to access control lists.
     *
     * @param  string  $objectType  program|project|product|sprint
     * @param  int     $objectID
     * @access public
     * @return void
     */
    public function addWhitelist($objectType = '', $objectID = 0)
    {
        $users = $this->post->accounts;
        $this->updateWhitelist($users, $objectType, $objectID);
    }

    /**
     * Determine whether the user exists in the white list of multiple products.
     *
     * @param  int     $objectID
     * @param  string  $account
     * @access public
     * @return void
     */
    public function deleteProgramWhitelist($objectID = 0, $account = '')
    {
        $program = $this->dao->select('id,program,whitelist')->from(TABLE_PRODUCT)->where('id')->eq($objectID)->fetch();
        if(empty($program)) return false;
        $programID = $program->program;
        $products  = $this->dao->select('id')->from(TABLE_PRODUCT)->where('program')->eq($programID)->andWhere('deleted')->eq('0')->fetchPairs('id');
        $whitelist = $this->dao->select('*')->from(TABLE_ACL)->where('objectID')->in($products)->andWhere('account')->eq($account)->andWhere('objectType')->eq('product')->fetch();

        /* Determine if the user exists in other products in the program set. */
        if(empty($whitelist))
        {
            $newWhitelist = str_replace(',' . $account, '', $program->whitelist);
            $this->dao->update(TABLE_PROGRAM)->set('whitelist')->eq($newWhitelist)->where('id')->eq($programID)->exec();
            $this->dao->delete()->from(TABLE_ACL)->where('objectID')->eq($programID)->andWhere('account')->eq($account)->andWhere('objectType')->eq('program')->exec();
        }
    }

    /**
     * Determine if the user is on a whitelist for multiple sprints
     *
     * @param  int     $objectID
     * @param  string  $account
     * @access public
     * @return void
     */
    public function deleteProjectWhitelist($objectID = 0, $account = '')
    {
        $project = $this->dao->select('id,project,whitelist')->from(TABLE_PROJECT)->where('id')->eq($objectID)->fetch();
        if(empty($project)) return false;
        $projectID = $project->project;
        $sprints   = $this->dao->select('id')->from(TABLE_PROJECT)->where('project')->eq($projectID)->andWhere('deleted')->eq('0')->fetchPairs('id');
        $whitelist = $this->dao->select('*')->from(TABLE_ACL)->where('objectID')->in($sprints)->andWhere('account')->eq($account)->andWhere('objectType')->eq('sprint')->fetch();

        /* Determine if the user exists in other sprints in the project set. */
        if(empty($whitelist))
        {
            $newWhitelist = str_replace(',' . $account, '', $project->whitelist);
            $this->dao->update(TABLE_PROJECT)->set('whitelist')->eq($newWhitelist)->where('id')->eq($projectID)->exec();
            $this->dao->delete()->from(TABLE_ACL)->where('objectID')->eq($projectID)->andWhere('account')->eq($account)->andWhere('objectType')->eq('project')->exec();
        }
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
