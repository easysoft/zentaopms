<?php
/**
 * The model file of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
class upgradeModel extends model
{
    static $errors = array();

    public function __construct()
    {
        parent::__construct();
        $this->loadModel('setting');
    }

    /**
     * The execute method. According to the $fromVersion call related methods.
     * 
     * @param  string $fromVersion 
     * @access public
     * @return void
     */
    public function execute($fromVersion)
    {
        switch($fromVersion)
        {
        case '0_3beta': $this->execSQL($this->getUpgradeFile('0.3'));
        case '0_4beta': $this->execSQL($this->getUpgradeFile('0.4'));
        case '0_5beta': $this->execSQL($this->getUpgradeFile('0.5'));
        case '0_6beta': $this->execSQL($this->getUpgradeFile('0.6'));
        case '1_0beta':
            $this->execSQL($this->getUpgradeFile('1.0.beta'));
            $this->updateCompany();
        case '1_0rc1': $this->execSQL($this->getUpgradeFile('1.0.rc1'));
        case '1_0rc2':
        case '1_0':
        case '1_0_1': $this->execSQL($this->getUpgradeFile('1.0.1'));
        case '1_1': $this->execSQL($this->getUpgradeFile('1.1'));
        case '1_2':
            $this->execSQL($this->getUpgradeFile('1.2'));
            $this->updateUBB();
            $this->updateNL1_2();
        case '1_3':
            $this->execSQL($this->getUpgradeFile('1.3'));
            $this->updateNL1_3();
            $this->updateTasks();
        case '1_4': $this->execSQL($this->getUpgradeFile('1.4'));
        case '1_5': $this->execSQL($this->getUpgradeFile('1.5'));
        case '2_0': $this->execSQL($this->getUpgradeFile('2.0'));
        case '2_1': $this->execSQL($this->getUpgradeFile('2.1'));
        case '2_2':
            $this->execSQL($this->getUpgradeFile('2.2'));
            $this->updateCases();
            $this->updateActivatedCountOfBug();
        case '2_3': $this->execSQL($this->getUpgradeFile('2.3'));
        case '2_4': $this->execSQL($this->getUpgradeFile('2.4'));
        case '3_0_beta1':
            $this->execSQL($this->getUpgradeFile('3.0.beta1'));
            $this->updateAction();
            $this->setOrderData();
        case '3_0_beta2':
        case '3_0':
        case '3_1': $this->execSQL($this->getUpgradeFile('3.1'));
        case '3_2': $this->execSQL($this->getUpgradeFile('3.2'));
        case '3_2_1': $this->execSQL($this->getUpgradeFile('3.2.1'));
        case '3_3':
            $this->execSQL($this->getUpgradeFile('3.3'));
            $this->updateTaskAssignedTo();
            $this->loadModel('setting')->setItem('system', 'common', '', 'flow', 'full');
        case '4_0_beta1': $this->execSQL($this->getUpgradeFile('4.0.beta1'));

        default: if(!$this->isError()) $this->setting->updateVersion($this->config->version);
        }

        $this->deletePatch();
    }

    /**
     * Create the confirm contents.
     * 
     * @param  string $fromVersion 
     * @access public
     * @return string
     */
    public function getConfirm($fromVersion)
    {
        $confirmContent = '';
        switch($fromVersion)
        {
        case '0_3beta': $confirmContent .= file_get_contents($this->getUpgradeFile('0.3'));
        case '0_4beta': $confirmContent .= file_get_contents($this->getUpgradeFile('0.4'));
        case '0_5beta': $confirmContent .= file_get_contents($this->getUpgradeFile('0.5'));
        case '0_6beta': $confirmContent .= file_get_contents($this->getUpgradeFile('0.6'));
        case '1_0beta': $confirmContent .= file_get_contents($this->getUpgradeFile('1.0.beta'));
        case '1_0rc1':  $confirmContent .= file_get_contents($this->getUpgradeFile('1.0.rc1'));
        case '1_0rc2':
        case '1_0':
        case '1_0_1':     $confirmContent .= file_get_contents($this->getUpgradeFile('1.0.1'));
        case '1_1':       $confirmContent .= file_get_contents($this->getUpgradeFile('1.1'));
        case '1_2':       $confirmContent .= file_get_contents($this->getUpgradeFile('1.2'));
        case '1_3':       $confirmContent .= file_get_contents($this->getUpgradeFile('1.3'));
        case '1_4':       $confirmContent .= file_get_contents($this->getUpgradeFile('1.4'));
        case '1_5':       $confirmContent .= file_get_contents($this->getUpgradeFile('1.5'));
        case '2_0':       $confirmContent .= file_get_contents($this->getUpgradeFile('2.0'));
        case '2_1':       $confirmContent .= file_get_contents($this->getUpgradeFile('2.1'));
        case '2_2':       $confirmContent .= file_get_contents($this->getUpgradeFile('2.2'));
        case '2_3':       $confirmContent .= file_get_contents($this->getUpgradeFile('2.3'));
        case '2_4':       $confirmContent .= file_get_contents($this->getUpgradeFile('2.4'));
        case '3_0_beta1': $confirmContent .= file_get_contents($this->getUpgradeFile('3.0.beta1'));
        case '3_0_beta2':
        case '3_0':
        case '3_1':       $confirmContent .= file_get_contents($this->getUpgradeFile('3.1'));
        case '3_2':       $confirmContent .= file_get_contents($this->getUpgradeFile('3.2'));
        case '3_2_1':     $confirmContent .= file_get_contents($this->getUpgradeFile('3.2.1'));
        case '3_3':       $confirmContent .= file_get_contents($this->getUpgradeFile('3.3'));
        case '4.0.beta1': $confirmContent .= file_get_contents($this->getUpgradeFile('4.0.beta1'));
        }
        return str_replace('zt_', $this->config->db->prefix, $confirmContent);
    }

    /**
     * Update company field.
     *
     * This method is used to update since 1.0 beta. Any new tables added after 1.0 beta should skip.
     *
     * @access public
     * @return void
     */
    public function updateCompany()
    {
        /* Get user defined constants. */
        $constants     = get_defined_constants(true);
        $userConstants = $constants['user'];

        /* Update tables. */
        foreach($userConstants as $key => $value)
        {
            if(strpos($key, 'TABLE') === false) continue;
            if($key == 'TABLE_COMPANY') continue;

            $table  = $value;
            $result = $this->dbh->query("SHOW TABLES LIKE '$table'");
            if($result->rowCount() > 0)
            {
                $this->dbh->query("UPDATE $table SET company = '{$this->app->company->id}'");
            }
        }
    }

    /**
     * Update ubb code in bug table and user Templates table to html.
     * 
     * @access public
     * @return void
     */
    public function updateUBB()
    {
        $this->app->loadClass('ubb', true);

        $bugs = $this->dao->select('id, steps')->from(TABLE_BUG)->fetchAll();
        $userTemplates = $this->dao->select('id, content')->from(TABLE_USERTPL)->fetchAll();
            
        foreach($bugs as $id => $bug)
        {
            $bug->steps = ubb::parseUBB($bug->steps);
            $this->dao->update(TABLE_BUG)->data($bug)->where('id')->eq($bug->id)->exec();
        }
        foreach($userTemplates as $template)
        {
            $template->content = ubb::parseUBB($template->content);
            $this->dao->update(TABLE_USERTPL)->data($template)->where('id')->eq($template->id)->exec();
        }
    }

    /**
     * Update nl to br from 1.2 version.
     * 
     * @access public
     * @return void
     */
    public function updateNL1_2()
    {
        $tasks     = $this->dao->select('id, `desc`')->from(TABLE_TASK)->fetchAll();
        $stories   = $this->dao->select('story, version, spec')->from(TABLE_STORYSPEC)->fetchAll();
        $todos     = $this->dao->select('id, `desc`')->from(TABLE_TODO)->fetchAll();
        $testTasks = $this->dao->select('id, `desc`')->from(TABLE_TESTTASK)->fetchAll();

        foreach($tasks as $task)
        {
            $task->desc = nl2br($task->desc);
            $this->dao->update(TABLE_TASK)->data($task)->where('id')->eq($task->id)->exec();
        }
        foreach($stories as $story)
        {
            $story->spec = nl2br($story->spec);
            $this->dao->update(TABLE_STORYSPEC)->data($story)->where('story')->eq($story->story)->andWhere('version')->eq($story->version)->exec();
        }

        foreach($todos as $todo)
        {
            $todo->desc = nl2br($todo->desc);
            $this->dao->update(TABLE_TODO)->data($todo)->where('id')->eq($todo->id)->exec();
        }

        foreach($testTasks as $testtask)
        {
            $testtask->desc = nl2br($testtask->desc);
            $this->dao->update(TABLE_TESTTASK)->data($testtask)->where('id')->eq($testtask->id)->exec();
        }
    }

    /**
     * Update nl to br from 1.3 version.
     * 
     * @access public
     * @return void
     */
    public function updateNL1_3()
    {
        $products = $this->dao->select('id, `desc`')->from(TABLE_PRODUCT)->fetchAll();
        $plans    = $this->dao->select('id, `desc`')->from(TABLE_PRODUCTPLAN)->fetchAll();
        $releases = $this->dao->select('id, `desc`')->from(TABLE_RELEASE)->fetchAll();
        $projects = $this->dao->select('id, `desc`, goal')->from(TABLE_PROJECT)->fetchAll();
        $builds   = $this->dao->select('id, `desc`')->from(TABLE_BUILD)->fetchAll();

        foreach($products as $product)
        {
            $product->desc = nl2br($product->desc);
            $this->dao->update(TABLE_PRODUCT)->data($product)->where('id')->eq($product->id)->exec();
        }

        foreach($plans as $plan)
        {
            $plan->desc = nl2br($plan->desc);
            $this->dao->update(TABLE_PRODUCTPLAN)->data($plan)->where('id')->eq($plan->id)->exec();
        }

        foreach($releases as $release)
        {
            $release->desc = nl2br($release->desc);
            $this->dao->update(TABLE_RELEASE)->data($release)->where('id')->eq($release->id)->exec();
        }

        foreach($projects as $project)
        {
            $project->desc = nl2br($project->desc);
            $project->goal = nl2br($project->goal);
            $this->dao->update(TABLE_PROJECT)->data($project)->where('id')->eq($project->id)->exec();
        }

        foreach($builds as $build)
        {
            $build->desc = nl2br($build->desc);
            $this->dao->update(TABLE_BUILD)->data($build)->where('id')->eq($build->id)->exec();
        }
    }

    /**
     * Update task fields.
     * 
     * @access public
     * @return void
     */
    public function updateTasks()
    {
        /* Get all actions of tasks. */
        $actions = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('objectType')->eq('task')
            ->orderBy('id')
            ->fetchAll('id');

        /* Get histories about status field. */
        $histories = $this->dao->select()->from(TABLE_HISTORY)
            ->where('action')->in(array_keys($actions))
            ->andWhere('field')->eq('status')
            ->orderBy('id')
            ->fetchGroup('action');

        $tasks = array();
        foreach($actions as $action)
        {
            if(!isset($tasks[$action->objectID]))
            {
                $tasks[$action->objectID] = new stdclass;
            }
            $task = $tasks[$action->objectID];

            $task->id   = $action->objectID;
            $actionType = strtolower($action->action);

            /* Set the openedBy info. */
            if($actionType == 'opened')
            {
                $task->openedBy   = $action->actor;
                $task->openedDate = $action->date;
            }
            else
            {
                if(!isset($histories[$action->id])) continue;

                $actionHistories = $histories[$action->id];
                foreach($actionHistories as $history)
                {
                    /* Finished by. */
                    if($history->new == 'done')
                    {
                        $task->finishedBy   = $action->actor;
                        $task->finishedDate = $action->date;
                        $action->action     = 'finished';
                    }
                    /* Canceled By. */
                    elseif($history->new == 'cancel')
                    {
                        $task->canceledBy   = $action->actor;
                        $task->canceledDate = $action->date;
                        $action->action     = 'canceled';
                    }
                }

                /* Last edited by .*/
                $task->lastEditedBy   = $action->actor;
                $task->lastEditedDate = $action->date;

                /* Update action type. */
                $this->dao->update(TABLE_ACTION)->set('action')->eq($action->action)->where('id')->eq($action->id)->exec(false);
            }
        }

        /* Update db. */
        foreach($tasks as $task)
        {
            $this->dao->update(TABLE_TASK)->data($task, false)->where('id')->eq($task->id)->exec(false);
        }

        $this->dao->update(TABLE_TASK)->set('assignedTo=openedBy, assignedDate = finishedDate')->where('status')->eq('done')->exec(false);
        $this->dao->update(TABLE_TASK)->set('assignedTo=openedBy, assignedDate = canceledDate')->where('status')->eq('cancel')->exec(false);

        /* Update action name. */
    }

    /**
     * Update activated count of Bug. 
     * 
     * @access public
     * @return void
     */
    public function updateActivatedCountOfBug()
    {
        $bugActivatedActions = $this->dao->select('*')->from(TABLE_ACTION)->where('action')->eq('activated')->andWhere('objectType')->eq('bug')->fetchAll();
        if(!empty($bugActivatedActions))
        {
            foreach($bugActivatedActions as $action)
            {
                if(!isset($counts[$action->objectID]))  $counts[$action->objectID] = 0;
                $counts[$action->objectID] ++;
            }
            foreach($counts as $key => $count)
            {
                $this->dao->update(TABLE_BUG)->set('activatedCount')->eq($count)->where('id')->eq($key)->exec();
            }
        }
    }

    /**
     * Update lastRun and lastResult field in zt_case
     * 
     * @access public
     * @return void
     */
    public function updateCases()
    {
        $results = $this->dao->select('`case`, date, caseResult')->from(TABLE_TESTRESULT)->orderBy('id desc')->fetchGroup('case');
        foreach($results as $result)
        {
            $this->dao->update(TABLE_CASE)
                ->set('lastRun')->eq($result[0]->date)
                ->set('lastResult')->eq($result[0]->caseResult)
                ->where('id')->eq($result[0]->case)
                ->exec();
        }
    }

    /**
     * Update the data of action. 
     * 
     * @access public
     * @return void
     */
    public function updateAction()
    {
        /* Get projects and tasks from action table. */
        $projects = $this->dao->select('id')->from(TABLE_PROJECT)->fetchPairs('id');
        $tasks    = $this->dao->select('id, project')->from(TABLE_TASK)->fetchPairs('id');

        /* Get products of projects and tasks. */
        $projectProducts = $this->dao->select('project,product')->from(TABLE_PROJECTPRODUCT)->where('project')->in(array_keys($projects))->fetchGroup('project', 'product');
        $taskProducts    = $this->dao->select('t1.id, t2.product')->from(TABLE_TASK)->alias('t1')
                                ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
                                ->where('t1.id')->in(array_keys($tasks))
                                ->fetchPairs('id');

        /* Process project actions. */
        foreach($projects as $projectID)
        {
            $productList = isset($projectProducts[$projectID]) ? join(',', array_keys($projectProducts[$projectID])) : '';
            $this->dao->update(TABLE_ACTION)->set('product')->eq($productList)->where('objectType')->eq('project')->andWhere('objectID')->eq($projectID)->exec();
        }

        /* Process task actions. */
        foreach($tasks as $taskID => $projectID)
        {
            $productList = '';
            if($taskProducts[$taskID])
            {
                $productList = $taskProducts[$taskID];
            }
            else
            {
                $productList = isset($projectProducts[$projectID]) ? join(',', array_keys($projectProducts[$projectID])) : '';
            }
            $this->dao->update(TABLE_ACTION)->set('product')->eq($productList)->where('objectType')->eq('task')->andWhere('objectID')->eq($taskID)->andWhere('project')->eq($projectID)->exec();
        }

        $this->dao->update(TABLE_ACTION)->set("product = concat(',',product,',')")->exec();
        return true;
    }

    /**
     * Init the data of product and project order field. 
     * 
     * @access public
     * @return void
     */
    public function setOrderData()
    {
        $products = $this->dao->select('*')->from(TABLE_PRODUCT)->where('deleted')->eq(0)->orderBy('code')->fetchAll('id');
        foreach(array_keys($products) as $key => $productID)
        {
            $this->dao->update(TABLE_PRODUCT)->set('`order`')->eq(($key + 1) * 10)->where('id')->eq($productID)->exec();
        }
        $projects = $this->dao->select('*')->from(TABLE_PROJECT)->where('iscat')->eq(0)->andWhere('deleted')->eq(0)->orderBy('status, id desc')->fetchAll('id');
        foreach(array_keys($projects) as $key => $projectID)
        {
            $this->dao->update(TABLE_PROJECT)->set('`order`')->eq(($key + 1) * 10)->where('id')->eq($projectID)->exec();
        }
        return true;
    }

    /**
     * Update task assignedTo.
     * 
     * @access public
     * @return void
     */
    public function updateTaskAssignedTo()
    {
        $this->dao->update(TABLE_TASK)->set('assignedTo')->eq('closed')
            ->where('status')->eq('closed')
            ->andWhere('assignedTo')->eq('')
            ->exec();
        return true;
    }

    /**
     * Delete the patch record.
     * 
     * @access public
     * @return void
     */
    public function deletePatch()
    {
        $this->dao->delete()->from(TABLE_EXTENSION)->where('type')->eq('patch')->exec(false);
        $this->dao->delete()->from(TABLE_EXTENSION)->where('code')->in('zentaopatch,patch')->exec(false);
    }

    /**
     * Get the upgrade sql file.
     * 
     * @param  string $version 
     * @access public
     * @return string
     */
    public function getUpgradeFile($version)
    {
        return $this->app->getAppRoot() . 'db' . $this->app->getPathFix() . 'update' . $version . '.sql';
    }

    /**
     * Execute a sql.
     * 
     * @param  string  $sqlFile 
     * @access public
     * @return void
     */
    public function execSQL($sqlFile)
    {
        $mysqlVersion = $this->loadModel('install')->getMysqlVersion();

        /* Read the sql file to lines, remove the comment lines, then join theme by ';'. */
        $sqls = explode("\n", file_get_contents($sqlFile));
        foreach($sqls as $key => $line) 
        {
            $line       = trim($line);
            $sqls[$key] = $line;
            if(strpos($line, '--') !== false or empty($line)) unset($sqls[$key]);
        }
        $sqls = explode(';', join("\n", $sqls));

        foreach($sqls as $sql)
        {
            $sql = trim($sql);
            if(empty($sql)) continue;

            if($mysqlVersion <= 4.1)
            {
                $sql = str_replace('DEFAULT CHARSET=utf8', '', $sql);
                $sql = str_replace('CHARACTER SET utf8 COLLATE utf8_general_ci', '', $sql);
            }

            $sql = str_replace('zt_', $this->config->db->prefix, $sql);
            try
            {
                $this->dbh->exec($sql);
            }
            catch (PDOException $e) 
            {
                self::$errors[] = $e->getMessage() . "<p>The sql is: $sql</p>";
            }
        }
    }

    /**
     * Judge any error occers.
     * 
     * @access public
     * @return bool
     */
    public function isError()
    {
        return !empty(self::$errors);
    }

    /**
     * Get errors during the upgrading.
     * 
     * @access public
     * @return array
     */
    public function getError()
    {
        $errors = self::$errors;
        self::$errors = array();
        return $errors;
    }
}
