<?php
/**
 * Processor for data generation.
 *
 * @package zentao
 * @version $id$
 * @copyright 2009-2022 Easysoft corp.
 * @author zjy
 * @license ZPL
 */
class Processor
{
    /**
     * Construct
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        global $dao;
        $this->dao = $dao;
    }

    /**
     * Init data.
     *
     * @access public
     * @return void
     */
    public function init()
    {
        $this->dao->begin();

        $this->initDept();
        $this->initUser();
        $this->initProgram();
        $this->initProduct();
        $this->initPlan();
        $this->initProject();
        $this->initBuild();
        $this->initTask();
        $this->initExecution();
        $this->initRelease();
        $this->initStakeholder();
        $this->initUserquery();
        $this->initUpdateKanban();

        $this->dao->commit();
    }

    /**
     * Init department.
     *
     * @access public
     * @return void
     */
    private function initDept()
    {
        $data = array('parent' => 2, 'path' => ",2,5,", 'grade' => 2);
        $this->dao->update(TABLE_DEPT)->data($data)->where('id')->eq(5)->exec();

        $data = array('parent' => 2, 'path' => ",2,6,", 'grade' => 2);
        $this->dao->update(TABLE_DEPT)->data($data)->where('id')->eq(6)->exec();

        for($id = 18; $id <= 27; $id++)
        {
            $parent = $id - 10;
            $child  = $id + 10;

            $data = array('parent' => $parent, 'path' => ",$parent,$id,", 'grade' => 2);
            $this->dao->update(TABLE_DEPT)->data($data)->where('id')->eq($id)->exec();

            $data = array('parent' => $id, 'path' => ",$parent,$id,$child,", 'grade' => 3);
            $this->dao->update(TABLE_DEPT)->data($data)->where('id')->eq($child)->exec();
        }
    }

    /**
     * Init user.
     *
     * @access public
     * @return void
     */
    private function initUser()
    {
        $users = array();
        $users['user1'] = array('account' => 'program1whitelist', 'realname' => '项目集1白名单用户');
        $users['user2'] = array('account' => 'noprogram1', 'realname' => '不在项目集1用户');

        foreach($users as $account => $user) $this->dao->update(TABLE_USER)->data($user)->where('account')->eq($account)->exec();
    }

    /**
     * Init program.
     *
     * @access public
     * @return void
     */
    private function initProgram()
    {
    }

    /**
     * Init product.
     *
     * @access public
     * @return void
     */
    private function initProduct()
    {
    }

    /**
     * Init product plan.
     *
     * @access public
     * @return void
     */
    private function initPlan()
    {
    }

    /**
     * Init project.
     *
     * @access public
     * @return void
     */
    private function initProject()
    {
    }

    /**
     * Init build.
     *
     * @access public
     * @return void
     */
    private function initBuild()
    {
    }

    /**
     * Init task.
     *
     * @access public
     * @return void
     */
    private function initTask()
    {
        $parentList   = $this->dao->select('parent')->from(TABLE_TASK)->where('parent')->gt(0)->fetchAll('parent');
        $parentIDList = array_keys($parentList);
        $parentID     = implode(',', $parentIDList);

        $this->dao->update(TABLE_TASK)->set('parent')->eq(-1)->where('id')->in($parentID)->andWhere('deleted')->eq(0)->exec();
    }

    /**
     * Init execution.
     *
     * @access public
     * @return void
     */
    private function initExecution()
    {
        /* Add relationship of projectproduct. */
        $projectProducts = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->fetchAll();
        $productsInProject = array();
        foreach($projectProducts as $relation)
        {
            if(!isset($productsInProject[$relation->project])) $productsInProject[$relation->project] = array();
            $productsInProject[$relation->project][] = $relation->product;
        }

        $executions = $this->dao->select('*')->from(TABLE_PROJECT)->where('type')->in('sprint,kanban,stage')->fetchAll();
        foreach($executions as $execution)
        {
            $products = $productsInProject[$execution->project];
            foreach($products as $product)
            {
                $data = new stdclass();
                $data->project = $execution->id;
                $data->product = $product;
                $this->dao->insert(TABLE_PROJECTPRODUCT)->data($data)->exec();
            }
        }
    }

    /**
     * Init release.
     *
     * @access public
     * @return void
     */
    private function initRelease()
    {
    }

    /**
     *  Init Stakeholder.
     *
     *  @access public
     *  @return void
     */
    private function initStakeholder()
    {
        /* Add stakeholder of project. */
        $projectProgramPairs = $this->dao->select('id,type')->from(TABLE_PROJECT)->where('type')->in('project,program')->fetchPairs();
        $accounts            = $this->dao->select('id,account')->from(TABLE_USER)->fetchPairs();

        foreach($projectProgramPairs as $id => $type)
        {
            $users = array($accounts[$id*10] ,$accounts[$id*10-1] ,$accounts[$id*10-2]);
            $data = new stdclass();
            foreach($users as $userID => $userAccount)
            {
                $data->objectID   = $id;
                $data->objectType = $type;
                $data->user       = $userAccount;
                $this->dao->insert(TABLE_STAKEHOLDER)->data($data)->exec();
            }
        }
    }

    /**
     * Init userquery.
     *
     * @access public
     * @return void
     */
    private function initUserquery()
    {
        $this->dao->query("DELETE FROM `zt_userquery`;");
        $this->dao->query("INSERT INTO `zt_userquery` (`id`, `account`, `module`, `title`, `form`, `sql`, `shortcut`) VALUES (1, 'admin',    'task', '任务查询测试条件',   'a:60:{s:9:\"fieldname\";s:0:\"\";s:11:\"fieldstatus\";s:0:\"\";s:9:\"fielddesc\";s:0:\"\";s:15:\"fieldassignedTo\";s:0:\"\";s:8:\"fieldpri\";s:1:\"0\";s:14:\"fieldexecution\";s:0:\"\";s:11:\"fieldmodule\";s:4:\"ZERO\";s:13:\"fieldestimate\";s:0:\"\";s:9:\"fieldleft\";s:0:\"\";s:13:\"fieldconsumed\";s:0:\"\";s:9:\"fieldtype\";s:0:\"\";s:12:\"fieldfromBug\";s:0:\"\";s:17:\"fieldclosedReason\";s:0:\"\";s:13:\"fieldopenedBy\";s:0:\"\";s:15:\"fieldfinishedBy\";s:0:\"\";s:13:\"fieldclosedBy\";s:0:\"\";s:13:\"fieldcancelBy\";s:1:\"0\";s:17:\"fieldlastEditedBy\";s:0:\"\";s:11:\"fieldmailto\";s:0:\"\";s:17:\"fieldfinishedList\";s:0:\"\";s:15:\"fieldopenedDate\";s:0:\"\";s:13:\"fielddeadline\";s:0:\"\";s:15:\"fieldestStarted\";s:0:\"\";s:16:\"fieldrealStarted\";s:0:\"\";s:17:\"fieldassignedDate\";s:0:\"\";s:17:\"fieldfinishedDate\";s:0:\"\";s:15:\"fieldclosedDate\";s:0:\"\";s:17:\"fieldcanceledDate\";s:0:\"\";s:19:\"fieldlastEditedDate\";s:0:\"\";s:7:\"fieldid\";s:0:\"\";s:15:\"fieldcanceledBy\";s:0:\"\";s:6:\"andOr1\";s:3:\"AND\";s:6:\"field1\";s:4:\"name\";s:9:\"operator1\";s:7:\"include\";s:6:\"value1\";s:2:\"aa\";s:6:\"andOr2\";s:3:\"and\";s:6:\"field2\";s:2:\"id\";s:9:\"operator2\";s:1:\"=\";s:6:\"value2\";s:0:\"\";s:6:\"andOr3\";s:3:\"and\";s:6:\"field3\";s:6:\"status\";s:9:\"operator3\";s:1:\"=\";s:6:\"value3\";s:0:\"\";s:10:\"groupAndOr\";s:3:\"and\";s:6:\"andOr4\";s:3:\"AND\";s:6:\"field4\";s:4:\"desc\";s:9:\"operator4\";s:7:\"include\";s:6:\"value4\";s:0:\"\";s:6:\"andOr5\";s:3:\"and\";s:6:\"field5\";s:10:\"assignedTo\";s:9:\"operator5\";s:1:\"=\";s:6:\"value5\";s:0:\"\";s:6:\"andOr6\";s:3:\"and\";s:6:\"field6\";s:3:\"pri\";s:9:\"operator6\";s:1:\"=\";s:6:\"value6\";s:1:\"0\";s:6:\"module\";s:4:\"task\";s:9:\"actionURL\";s:77:\"/index.php?m=execution&f=task&executionID=101&status=bySearch&param=myQueryID\";s:10:\"groupItems\";s:1:\"3\";s:8:\"formType\";s:4:\"lite\";}',  '(( 1   AND `name`  LIKE \'%11%\' ) AND ( 1  )) AND deleted = \'0\'',   '0');");
        $this->dao->query("INSERT INTO `zt_userquery` (`id`, `account`, `module`, `title`, `form`, `sql`, `shortcut`) VALUES (2, 'admin',    'executionStory',   '需求查找条件', 'a:56:{s:10:\"fieldtitle\";s:0:\"\";s:13:\"fieldkeywords\";s:0:\"\";s:11:\"fieldstatus\";s:0:\"\";s:10:\"fieldstage\";s:0:\"\";s:8:\"fieldpri\";s:1:\"0\";s:12:\"fieldproduct\";s:1:\"0\";s:11:\"fieldbranch\";s:0:\"\";s:11:\"fieldmodule\";s:4:\"ZERO\";s:9:\"fieldplan\";s:0:\"\";s:13:\"fieldestimate\";s:0:\"\";s:11:\"fieldsource\";s:0:\"\";s:15:\"fieldsourceNote\";s:0:\"\";s:12:\"fieldfromBug\";s:0:\"\";s:13:\"fieldopenedBy\";s:0:\"\";s:15:\"fieldreviewedBy\";s:0:\"\";s:15:\"fieldassignedTo\";s:0:\"\";s:13:\"fieldclosedBy\";s:0:\"\";s:17:\"fieldlastEditedBy\";s:0:\"\";s:11:\"fieldmailto\";s:0:\"\";s:17:\"fieldclosedReason\";s:0:\"\";s:12:\"fieldversion\";s:0:\"\";s:15:\"fieldopenedDate\";s:0:\"\";s:17:\"fieldreviewedDate\";s:0:\"\";s:17:\"fieldassignedDate\";s:0:\"\";s:15:\"fieldclosedDate\";s:0:\"\";s:19:\"fieldlastEditedDate\";s:0:\"\";s:7:\"fieldid\";s:0:\"\";s:6:\"andOr1\";s:3:\"AND\";s:6:\"field1\";s:5:\"title\";s:9:\"operator1\";s:7:\"include\";s:6:\"value1\";s:3:\"362\";s:6:\"andOr2\";s:3:\"and\";s:6:\"field2\";s:2:\"id\";s:9:\"operator2\";s:1:\"=\";s:6:\"value2\";s:0:\"\";s:6:\"andOr3\";s:3:\"and\";s:6:\"field3\";s:8:\"keywords\";s:9:\"operator3\";s:7:\"include\";s:6:\"value3\";s:0:\"\";s:10:\"groupAndOr\";s:3:\"and\";s:6:\"andOr4\";s:3:\"AND\";s:6:\"field4\";s:5:\"stage\";s:9:\"operator4\";s:1:\"=\";s:6:\"value4\";s:0:\"\";s:6:\"andOr5\";s:3:\"and\";s:6:\"field5\";s:6:\"status\";s:9:\"operator5\";s:1:\"=\";s:6:\"value5\";s:0:\"\";s:6:\"andOr6\";s:3:\"and\";s:6:\"field6\";s:3:\"pri\";s:9:\"operator6\";s:1:\"=\";s:6:\"value6\";s:1:\"0\";s:6:\"module\";s:14:\"executionStory\";s:9:\"actionURL\";s:95:\"/index.php?m=execution&f=story&executionID=101&orderBy=pri_desc&type=bySearch&queryID=myQueryID\";s:10:\"groupItems\";s:1:\"3\";s:8:\"formType\";s:4:\"lite\";}',    '(( 1   AND `title`  LIKE \'%362%\' ) AND ( 1  ))', '0');");
    }

    /**
     * Init initUpdateKanban.
     *
     * @access public
     * @return void
     */
    private function initUpdateKanban()
    {
        $this->dao->query("update zt_kanbancolumn set `limit` = '-1' where id >= 1 and id <= 400");

        $kanban = $this->dao->select('id,type,region,name,color,`limit`,`order`')->from(TABLE_KANBANCOLUMN)->where('id')->gt('400')->fetchAll();
        $group = 101;
        foreach($kanban as $key => $value)
        {
            $id = $value->id;

            $value->limit = '-1';
            if(!isset($value->parent)) $value->parent = 0;

            if(in_array($value->type, ['develop', 'test', 'resolving']))
            {
                $value->parent = '-1';
                $kanban[$key+1]->parent = $value->id;
                $kanban[$key+2]->parent = $value->id;
            }

            $value->group = $group;
            if($value->type == 'closed')
            {
                $group++;
            }
            unset($value->id);
            $this->dao->update(TABLE_KANBANCOLUMN)->data($value)->where('id')->eq($id)->exec();
        }

        $kanbancell = $this->dao->select('id,type')->from(TABLE_KANBANCELL)->where('id')->gt('400')->fetchAll();
        $kanbanlane = 101;
        foreach($kanbancell as $key => $value)
        {
            $id = $value->id;

            $value->lane = $kanbanlane;

            if($value->type == 'story' && isset($kanbancell[$key+1]) && $kanbancell[$key+1]->type == 'bug')
            {
                $kanbanlane++;
            }

            if($value->type == 'bug' && isset($kanbancell[$key+1]) && $kanbancell[$key+1]->type == 'task')
            {
                $kanbanlane++;
            }
            if($value->type == 'task' && isset($kanbancell[$key+1]) && $kanbancell[$key+1]->type == 'story')
            {
                $kanbanlane++;
            }
            unset($value->id);
            $this->dao->update(TABLE_KANBANCELL)->data($value)->where('id')->eq($id)->exec();
        }
        $this->dao->query("update zt_kanbancell set `cards` = '' where  id > 400");
    }
}
