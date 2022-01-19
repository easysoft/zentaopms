#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';

/**

title=测试 projectModel::getByID;
cid=1
pid=1

*/

class Tester
{
    public function __construct($user)
    {
        global $tester;

        su($user);
        $this->project = $tester->loadModel('project');
    }

    /**
     * Get project by ID.
     *
     * @param  int $projectID
     * @access public
     * @return object
     */
    public function getProjectByID($projectID)
    {
        return $this->project->getByID($projectID);
    }

    /**
     * Get program by ID.
     *
     * @param  int $programID
     * @access public
     * @return object
     */
    public function getProgramByID($programID)
    {
        return $this->project->getByID($programID, 'program');
    }

    /**
     * Get sprint by ID.
     *
     * @param  int $sprintID
     * @access public
     * @return object
     */
    public function getSprintByID($sprintID)
    {
        return $this->project->getByID($sprintID, 'sprint');
    }

    /**
     * Get stage by ID.
     *
     * @param  int $stageID
     * @access public
     * @return object
     */
    public function getStageByID($stageID)
    {
        return $this->project->getByID($stageID, 'stage');
    }

    /**
     * Get Kanban by ID,
     *
     * @param  int $kanbanID
     * @access public
     * @return void
     */
    public function getKanbanByID($kanbanID)
    {
        return $this->project->getByID($kanbanID, 'kanban');
    }
}

$t = new Tester('admin');

/* Get project by ID. */
r($t->getProjectByID(11)) && p('code,type') && e('project1,project'); //获取ID等于11的项目
r($t->getProjectByID(0))  && p('code,type') && e('0');                //获取不存在的项目

/* Get program by ID. */
r($t->getProgramByID(1)) && p('code,type') && e('program1,program'); //获取ID等于1的项目集
r($t->getProgramByID(0)) && p('code,type') && e('0');                //获取不存在的项目集

/* Get sprint by ID. */
r($t->getSprintByID(101)) && p('code,type') && e('project1,sprint'); //获取ID等于101的冲刺
r($t->getSprintByID(0))    && p('code,type') && e('0');               //获取不存在的冲刺

/* Get stage by ID. */
r($t->getStageByID(131)) && p('code,type') && e('project31,stage'); //获取ID等于131的阶段
r($t->getStageByID(0))   && p('code,type') && e('0');               //获取不存在的阶段

/* Get Kanban by ID. */
r($t->getKanbanByID(161)) && p('code,type') && e('project61,kanban'); //获取ID等于161的阶段
r($t->getKanbanByID(0))   && p('code,type') && e('0');                //获取不存在的看板
