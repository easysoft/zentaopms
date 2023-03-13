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

        //$this->initBassicSql();
        $this->initDept();
        $this->initUser();
        $this->initProgram();
        //$this->initProduct();
        //$this->initPlan();
        $this->initProject();
        //$this->initBuild();
        //$this->initTask();
        $this->initExecution();
        //$this->initRelease();
        //$this->initStakeholder();
        //$this->initUserquery();
        //$this->initMessage();
        //$this->initUpdateKanban();
        //$this->initStory();
        //$this->initBug();
        //$this->initTest();
        //$this->initTodo();
        //$this->initSonPlan();

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

        $this->dao->update(TABLE_USERCONTACT)->set('account')->eq('admin')->where('account')->like('admin%')->exec();

        $productIDList = $this->dao->select('id')->from(TABLE_PRODUCT)->fetchAll();
        $projectIDList = $this->dao->select('id')->from(TABLE_PROJECT)->where('type')->eq('project')->fetchAll();
        $sprintIDList  = $this->dao->select('id')->from(TABLE_EXECUTION)->where('type')->in('sprint,stage,kanban')->fetchAll();

        $product = array();
        $project = array();
        $sprint  = array();
        foreach($productIDList as $productID) $product[] = $productID->id;
        foreach($projectIDList as $projectID) $project[] = $projectID->id;
        foreach($sprintIDList as $sprintID)   $sprint[]  = $sprintID->id;

        $products = ",".join(",",$product);
        $projects = ",".join(",",$project);
        $sprints  = ",".join(",",$sprint);

        $userViews = new stdclass();
        $userViews->account  = 'admin';
        $userViews->programs = ',1,2,3,4,5,6,7,8,9,10';
        $userViews->products = $products;
        $userViews->projects = $projects;
        $userViews->sprints  = $sprints;
        $this->dao->insert(TABLE_USERVIEW)->data($userViews)->exec();

        $guestViews = new stdclass();
        $guestViews->account  = 'guest';
        $guestViews->programs = '';
        $guestViews->products = '';
        $guestViews->projects = '';
        $guestViews->sprints  = '';
        $this->dao->insert(TABLE_USERVIEW)->data($guestViews)->exec();
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
     * Init Story.
     *
     * @access private
     * @return void
     */
    private function initStory()
    {
        $this->dao->update(TABLE_STORY)->set('`status`')->eq('active')->where('id')->le('20')->exec();
        $this->dao->update(TABLE_STORY)->set('`status`')->eq('draft')->where('id')->ge('300')->andwhere('id')->le('400')->exec();
        $this->dao->update(TABLE_STORY)->set('`closedBy`')->eq('')->set('`closedReason`')->eq('')->where('status')->ne('closed')->exec();
        $this->dao->update(TABLE_STORY)->set('`plan`')->eq('0')->where('id')->gt('300')->andwhere('id')->lt('321')->exec();

        $accounts = array('user92' => 'po82', 'user93' => 'po83', 'user94' => 'po84', 'user95' => 'po85', 'user96' => 'po86', 'user97' => 'po82');
        foreach($accounts as $key => $value)
        {
            $this->dao->update(TABLE_STORYESTIMATE)->set('`estimate`')->eq("{\"$value\":{\"account\":\"$value\",\"estimate\":1},\"$key\":{\"account\":\"$key\",\"estimate\":2}")->where('estimate')->eq($key)->exec();
        }

    }

    /**
     * Init Test.
     *
     * @access private
     * @return void
     */
    private function initBug()
    {
        $this->dao->update(TABLE_BUG)->set('`issueKey`')->eq('2:AX-W7K3_L7H_36P3H4le')->where('issueKey')->eq('17')->exec();
    }

    private function initTest()
    {
        $testResults = $this->dao->select('*')->from(TABLE_TESTRESULT)->fetchAll();

        foreach($testResults as $testResult)
        {
            if($testResult->caseResult == 'fail')
            {
                $this->dao->update(TABLE_TESTRESULT)->set('`stepResults`')->eq('a:1:{i:'.$testResult->run.';a:2:{s:6:\"result\";s:4:\"fail\";s:4:\"real\";s:0:\"\";}}')->where('id')->eq($testResult->id)->exec();
            }
            else
            {
                $this->dao->update(TABLE_TESTRESULT)->set('`stepResults`')->eq('a:1:{i:'.$testResult->run.';a:2:{s:6:\"result\";s:4:\"pass\";s:4:\"real\";s:0:\"\";}}')->where('id')->eq($testResult->id)->exec();
            }
        }
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
        $this->dao->update(TABLE_TASK)->set('assignedTo')->eq('po82')->set('mailto')->eq('user1,user2,user3')->set('frombug')->eq('1')->where('id')->eq(1)->andWhere('deleted')->eq(0)->exec();
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
     * init BassicSql.
     *
     * @access private
     * @return void
     */
    private function initBassicSql()
    {
        global $app;
        $sqlRoot = $app->getAppRoot();
        $this->dao->exec(file_get_contents($sqlRoot . '/data/zt_block.sql'));
        $this->dao->exec(file_get_contents($sqlRoot . '/data/zt_lang.sql'));
        $this->dao->exec(file_get_contents($sqlRoot . '/data/zt_cron.sql'));
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
        $projectProgramPairs = $this->dao->select('id,type')->from(TABLE_PROJECT)->where('type')->in('project,program')->andwhere('id')->lt('730')->fetchPairs();
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
        $this->dao->update(TABLE_STAKEHOLDER)->set('`type`')->eq('inside')->where('id')->le('300')->exec();
        $this->dao->update(TABLE_STAKEHOLDER)->set('`type`')->eq('outside')->set('`user`')->eq('outside1')->where('id')->eq('301')->exec();
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
        $this->dao->query("INSERT INTO `zt_userquery` (`id`, `account`, `module`, `title`, `form`, `sql`, `shortcut`) VALUES (3, 'admin',    'user', '用户测试条件', 'a:48:{s:13:\"fieldrealname\";s:0:\"\";s:10:\"fieldemail\";s:0:\"\";s:9:\"fielddept\";s:0:\"\";s:12:\"fieldaccount\";s:0:\"\";s:9:\"fieldrole\";s:0:\"\";s:10:\"fieldphone\";s:0:\"\";s:9:\"fieldjoin\";s:0:\"\";s:12:\"fieldvisions\";s:3:\"rnd\";s:7:\"fieldid\";s:0:\"\";s:13:\"fieldcommiter\";s:1:\"0\";s:11:\"fieldgender\";s:1:\"m\";s:7:\"fieldqq\";s:0:\"\";s:10:\"fieldskype\";s:0:\"\";s:13:\"fielddingding\";s:0:\"\";s:11:\"fieldweixin\";s:0:\"\";s:10:\"fieldslack\";s:0:\"\";s:13:\"fieldwhatsapp\";s:0:\"\";s:12:\"fieldaddress\";s:0:\"\";s:12:\"fieldzipcode\";s:0:\"\";s:6:\"andOr1\";s:3:\"AND\";s:6:\"field1\";s:8:\"realname\";s:9:\"operator1\";s:7:\"include\";s:6:\"value1\";s:9:\"白名单\";s:6:\"andOr2\";s:3:\"and\";s:6:\"field2\";s:5:\"email\";s:9:\"operator2\";s:7:\"include\";s:6:\"value2\";s:0:\"\";s:6:\"andOr3\";s:3:\"and\";s:6:\"field3\";s:4:\"dept\";s:9:\"operator3\";s:6:\"belong\";s:6:\"value3\";s:0:\"\";s:10:\"groupAndOr\";s:3:\"and\";s:6:\"andOr4\";s:3:\"AND\";s:6:\"field4\";s:7:\"account\";s:9:\"operator4\";s:7:\"include\";s:6:\"value4\";s:0:\"\";s:6:\"andOr5\";s:3:\"and\";s:6:\"field5\";s:4:\"role\";s:9:\"operator5\";s:1:\"=\";s:6:\"value5\";s:0:\"\";s:6:\"andOr6\";s:3:\"and\";s:6:\"field6\";s:5:\"phone\";s:9:\"operator6\";s:7:\"include\";s:6:\"value6\";s:0:\"\";s:6:\"module\";s:4:\"user\";s:9:\"actionURL\";s:74:\"/index.php?m=company&f=browse&browseType=all&param=myQueryID&type=bysearch\";s:10:\"groupItems\";s:1:\"3\";s:8:\"formType\";s:4:\"lite\";}',  '(( 1   AND `realname`  LIKE \'%白名单%\' ) AND ( 1  ))',   '0');");
        $this->dao->query("INSERT INTO `zt_userquery` (`id`, `account`, `module`, `title`, `form`, `sql`, `shortcut`) VALUES (4, 'admin',    'projectBuild', '项目版本搜索', 'a:38:{s:9:\"fieldname\";s:0:\"\";s:12:\"fieldproduct\";s:0:\"\";s:12:\"fieldscmPath\";s:0:\"\";s:13:\"fieldfilePath\";s:0:\"\";s:9:\"fielddate\";s:0:\"\";s:12:\"fieldbuilder\";s:0:\"\";s:9:\"fielddesc\";s:0:\"\";s:14:\"fieldexecution\";s:0:\"\";s:7:\"fieldid\";s:0:\"\";s:6:\"andOr1\";s:3:\"AND\";s:6:\"field1\";s:4:\"name\";s:9:\"operator1\";s:7:\"include\";s:6:\"value1\";s:8:\"版本7\";s:6:\"andOr2\";s:3:\"and\";s:6:\"field2\";s:2:\"id\";s:9:\"operator2\";s:1:\"=\";s:6:\"value2\";s:0:\"\";s:6:\"andOr3\";s:3:\"and\";s:6:\"field3\";s:7:\"product\";s:9:\"operator3\";s:1:\"=\";s:6:\"value3\";s:0:\"\";s:10:\"groupAndOr\";s:3:\"and\";s:6:\"andOr4\";s:3:\"AND\";s:6:\"field4\";s:7:\"scmPath\";s:9:\"operator4\";s:7:\"include\";s:6:\"value4\";s:0:\"\";s:6:\"andOr5\";s:3:\"and\";s:6:\"field5\";s:8:\"filePath\";s:9:\"operator5\";s:7:\"include\";s:6:\"value5\";s:0:\"\";s:6:\"andOr6\";s:3:\"and\";s:6:\"field6\";s:4:\"date\";s:9:\"operator6\";s:1:\"=\";s:6:\"value6\";s:0:\"\";s:6:\"module\";s:12:\"projectBuild\";s:9:\"actionURL\";s:73:\"/index.php?m=project&f=build&projectID=13&type=bysearch&queryID=myQueryID\";s:10:\"groupItems\";s:1:\"3\";s:8:\"formType\";s:4:\"more\";}',  '(( 1   AND `name`  LIKE \'%版本7%\' ) AND ( 1  ))',   '0');");
        $this->dao->query("INSERT INTO `zt_userquery` (`id`, `account`, `module`, `title`, `form`, `sql`, `shortcut`) VALUES (5, 'admin',    'executionBuild',   '执行版本搜索', 'a:37:{s:9:\"fieldname\";s:0:\"\";s:12:\"fieldproduct\";s:0:\"\";s:12:\"fieldscmPath\";s:0:\"\";s:13:\"fieldfilePath\";s:0:\"\";s:9:\"fielddate\";s:0:\"\";s:12:\"fieldbuilder\";s:0:\"\";s:9:\"fielddesc\";s:0:\"\";s:7:\"fieldid\";s:0:\"\";s:6:\"andOr1\";s:3:\"AND\";s:6:\"field1\";s:4:\"name\";s:9:\"operator1\";s:7:\"include\";s:6:\"value1\";s:8:\"版本17\";s:6:\"andOr2\";s:3:\"and\";s:6:\"field2\";s:2:\"id\";s:9:\"operator2\";s:1:\"=\";s:6:\"value2\";s:0:\"\";s:6:\"andOr3\";s:3:\"and\";s:6:\"field3\";s:7:\"product\";s:9:\"operator3\";s:1:\"=\";s:6:\"value3\";s:0:\"\";s:10:\"groupAndOr\";s:3:\"and\";s:6:\"andOr4\";s:3:\"AND\";s:6:\"field4\";s:7:\"scmPath\";s:9:\"operator4\";s:7:\"include\";s:6:\"value4\";s:0:\"\";s:6:\"andOr5\";s:3:\"and\";s:6:\"field5\";s:8:\"filePath\";s:9:\"operator5\";s:7:\"include\";s:6:\"value5\";s:0:\"\";s:6:\"andOr6\";s:3:\"and\";s:6:\"field6\";s:4:\"date\";s:9:\"operator6\";s:1:\"=\";s:6:\"value6\";s:0:\"\";s:6:\"module\";s:14:\"executionBuild\";s:9:\"actionURL\";s:78:\"/index.php?m=execution&f=build&executionID=101&type=bysearch&queryID=myQueryID\";s:10:\"groupItems\";s:1:\"3\";s:8:\"formType\";s:4:\"more\";}',    '(( 1   AND `name`  LIKE \'%版本17%\' ) AND ( 1  ))',   '0');");
        $this->dao->query("INSERT INTO `zt_userquery` (`id`, `account`, `module`, `title`, `form`, `sql`, `shortcut`) VALUES (6, 'admin',    'design',   '设计搜索', 'a:37:{s:9:\"fieldtype\";s:0:\"\";s:9:\"fieldname\";s:0:\"\";s:11:\"fieldcommit\";s:0:\"\";s:14:\"fieldcreatedBy\";s:0:\"\";s:16:\"fieldcreatedDate\";s:0:\"\";s:15:\"fieldassignedTo\";s:0:\"\";s:10:\"fieldstory\";s:1:\"0\";s:7:\"fieldid\";s:0:\"\";s:6:\"andOr1\";s:3:\"AND\";s:6:\"field1\";s:2:\"id\";s:9:\"operator1\";s:1:\"=\";s:6:\"value1\";s:0:\"\";s:6:\"andOr2\";s:3:\"and\";s:6:\"field2\";s:4:\"type\";s:9:\"operator2\";s:1:\"=\";s:6:\"value2\";s:0:\"\";s:6:\"andOr3\";s:3:\"and\";s:6:\"field3\";s:4:\"name\";s:9:\"operator3\";s:7:\"include\";s:6:\"value3\";s:19:\"这是一个设计1\";s:10:\"groupAndOr\";s:3:\"and\";s:6:\"andOr4\";s:3:\"AND\";s:6:\"field4\";s:6:\"commit\";s:9:\"operator4\";s:7:\"include\";s:6:\"value4\";s:0:\"\";s:6:\"andOr5\";s:3:\"and\";s:6:\"field5\";s:9:\"createdBy\";s:9:\"operator5\";s:1:\"=\";s:6:\"value5\";s:0:\"\";s:6:\"andOr6\";s:3:\"and\";s:6:\"field6\";s:11:\"createdDate\";s:9:\"operator6\";s:1:\"=\";s:6:\"value6\";s:0:\"\";s:6:\"module\";s:6:\"design\";s:9:\"actionURL\";s:86:\"/index.php?m=design&f=browse&projectID=41&productID=31&type=bySearch&queryID=myQueryID\";s:10:\"groupItems\";s:1:\"3\";s:8:\"formType\";s:4:\"more\";}',    '(( 1   AND `name`  LIKE \'%这是一个设计1%\' ) AND ( 1  ))',    '0');");
    }

    private function initMessage()
    {
        $this->dao->query("DELETE FROM `zt_config` where `key` = 'setting';");
        $this->dao->query("INSERT INTO `zt_config` (`vision`, `owner`, `module`, `section`, `key`, `value`) VALUES
            ('rnd',  'system',   'message',  '', 'setting',  '{\"mail\":{\"setting\":{\"story\":[\"opened\",\"edited\",\"commented\",\"frombug\",\"changed\",\"reviewed\",\"closed\",\"activated\",\"assigned\"],\"task\":[\"opened\",\"edited\",\"commented\",\"assigned\",\"confirmed\",\"started\",\"finished\",\"paused\",\"canceled\",\"restarted\",\"closed\",\"activated\"],\"testtask\":[\"opened\",\"edited\",\"closed\"],\"doc\":[\"created\",\"edited\"]}},\"message\":{\"setting\":{\"story\":[\"opened\",\"edited\",\"commented\",\"frombug\",\"changed\",\"reviewed\",\"closed\",\"activated\",\"assigned\"],\"task\":[\"opened\",\"edited\",\"commented\",\"assigned\",\"confirmed\",\"started\",\"finished\",\"paused\",\"canceled\",\"restarted\",\"closed\",\"activated\"],\"testtask\":[\"opened\",\"edited\",\"started\",\"blocked\",\"closed\",\"activated\"],\"todo\":[\"opened\",\"edited\"],\"doc\":[\"created\",\"edited\"]}}}');");
    }
    private function initTodo()
    {
        $toDay  = date('y-m-d');
        $addDay = date('Y-m-d',strtotime("+1 day"));

        $str  = '{"day":"1","specify":{"month":"0","day":"1"},"type":"day","beforeDays":11,"end":"","begin":"'.$toDay.'"}';
        $str2 = '{"specify":{"month":"0","day":"1"},"week":"1,3","type":"week","beforeDays":31,"end":"","begin":"'.$toDay.'"}';
        $str3 = '{"specify":{"month":"0","day":"1"},"month":"5,17,29","type":"month","beforeDays":301,"end":"","begin":"'.$toDay.'"}';

        $this->dao->update(TABLE_TODO)->SET('config')->eq($str)->where('id')->eq('2001')->exec();
        $this->dao->update(TABLE_TODO)->SET('date')->eq($addDay)->where('id')->eq('2002')->exec();
        $this->dao->update(TABLE_TODO)->SET('config')->eq($str2)->where('id')->eq('2003')->exec();
        $this->dao->update(TABLE_TODO)->SET('config')->eq($str3)->where('id')->eq('2005')->exec();

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
        $this->dao->query("update zt_kanbanspace set `whitelist` = '' where type in ('cooperation','public')");
        $this->dao->query("update zt_kanbanspace set `team` = '' where type = 'private'");

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
        $this->dao->query("update zt_kanbancell set `cards` = ',244,' where id = 401");
        $this->dao->query("update zt_kanbancell set `cards` = ',181,182,183,' where id = 412");
        $this->dao->query("update zt_kanbancell set `cards` = ',61,' where id = 421");
        $this->dao->query("update zt_kanbancell set `cards` = ',246,' where id = 428");
        $this->dao->query("update zt_kanbancell set `cards` = ',184,185,186,' where id = 439");
        $this->dao->query("update zt_kanbancell set `cards` = ',62,' where id = 448");
        $this->dao->query("update zt_kanbancell set `cards` = ',247,' where id =  458");
        $this->dao->query("update zt_kanbancell set `cards` = ',187,188,189,' where id = 469");
        $this->dao->query("update zt_kanbancell set `cards` = ',63,' where id = 477");
        $this->dao->query("update zt_kanbancell set `cards` = ',248,' where id = 485");
        $this->dao->query("update zt_kanbancell set `cards` = ',190,191,192,' where id = 496");
        $this->dao->query("update zt_kanbancell set `cards` = ',64,' where id = 504");
        $this->dao->query("update zt_kanbancolumn set `parent` = '-1' where id = 202");
        $this->dao->query("update zt_kanbancolumn set `parent` = '202' where id in ('203','204')");
        $this->dao->query("update zt_kanbancolumn set `parent` = '-1' where id = 206");
        $this->dao->query("update zt_kanbancolumn set `parent` = '206' where id in ('207','208')");
    }

    /**
     * Init son plan
     *
     * @access public
     * @return void
     */
    public function initSonPlan()
    {
        $this->dao->query("update zt_productplan set `parent` = '-1' where id < 10 and id > 0");
    }
}
