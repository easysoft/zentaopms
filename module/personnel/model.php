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
     * @param  string    $browseType
     * @param  string    $orderBy
     * @access public
     * @return array
     */
    public function getInputPersonnel($programID = 0, $browseType = 'all', $orderBy = 'id_desc')
    {
        $personnelList = array();

        /* Get all projects under the current program. */
        $projects = $this->dao->select('id,model,type,template,parent,path,name')->from(TABLE_PROJECT)
            ->where('type')->eq('project')
            ->beginIF($browseType != 'parent')->andWhere('path')->like("%,{$programID},%")->fi()
            ->beginIF($browseType == 'parent')->andWhere('parent')->eq($programID)->fi()
            ->andWhere('deleted')->eq('0')
            ->orderBy($orderBy)
            ->fetchAll('id');
        $personnelList['projects'] = $projects;
        if(empty($projects)) return $personnelList;

        /* Get the sprint or stage under the project. */
        $projectKeys = array_keys($projects);
        $sprints = $this->dao->select('id,model,type,template,parent,path,name')->from(TABLE_PROJECT)
            ->where('type')->in('stage,sprint')
            ->andWhere('parent')->in($projectKeys)
            ->andWhere('deleted')->eq('0')
            ->fetchGroup('parent', 'id');

        /* Get team members for projects, stage, sprints. */
        $sprintKeys = array(0);
        foreach($sprints as $sprint)
        {
            foreach($sprint as $id => $data) $sprintKeys[] = $id;
        }
        $teams = $this->dao->select('id,root,type,role,account')->from(TABLE_TEAM)
            ->where('root')->in($sprintKeys)
            ->andWhere('type')->in('project,stage,sprint')
            ->fetchGroup('root', 'id');

        $programNameList = $this->getProgramPairs();
        $personnelList['sprints'] = $sprints;
        $personnelList['teams']   = $teams;

        /* Get the program name for each level. */
        foreach($personnelList['projects'] as $id => $project)
        {
            $path = explode(',', $project->path);
            $path = array_filter($path);
            unset($path[$id]);
            $programName = '';
            foreach($path as $program)
            {
                if($program == $id) continue;
                $programName .= '/'. $programNameList[$program];
            }
            $personnelList['projects'][$id]->programName = $programName;
        }

        return $personnelList;
    }

    /**
     * Get all program set names.
     *
     * @access public
     * @return object
     */
    public function getProgramPairs()
    {
        return $this->dao->select('id,name')->from(TABLE_PROJECT)->where('type')->eq('program')->andWhere('deleted')->eq('0')->fetchPairs('id', 'name');
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
