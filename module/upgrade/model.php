<?php
/**
 * The model file of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id: model.php 5019 2013-07-05 02:02:31Z wyd621@gmail.com $
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
            case '4_0_beta1': $this->execSQL($this->getUpgradeFile('4.0.beta1'));
            case '4_0_beta2':  
                $this->execSQL($this->getUpgradeFile('4.0.beta2'));
                $this->updateProjectType();
                $this->updateEstimatePriv();
            case '4_0':  
                $this->execSQL($this->getUpgradeFile('4.0'));
            case '4_0_1':  
                $this->execSQL($this->getUpgradeFile('4.0.1'));
                $this->addPriv4_0_1();
            case '4_1':
                $this->execSQL($this->getUpgradeFile('4.1'));
                $this->addPriv4_1();
                $this->processTaskFinish();
                $this->deleteCompany();
            case '4_2_beta':
                $this->execSQL($this->getUpgradeFile('4.2'));
            case '4_3_beta':
                $this->execSQL($this->getUpgradeFile('4.3'));
            case '5_0_beta1':
            case '5_0_beta2':
            case '5_0':
            case '5_1':
            case '5_2':
            case '5_2_1':
                $this->mergeProjectGoalAndDesc();
                $this->execSQL($this->getUpgradeFile('5.2.1'));
            case '5_3':
            case '6_0_beta1':
                $this->execSQL($this->getUpgradeFile('6.0.beta1'));
                $this->toLowerTable();
                $this->fixBugOSInfo();
                $this->fixTaskFinishedBy();
            case '6_0':
                $this->execSQL($this->getUpgradeFile('6.0'));
                $this->fixDataIndex();
            case '6_1':
                $this->execSQL($this->getUpgradeFile('6.1'));
            case '6_2':
            case '6_3':
            case '6_4':
            case '7_0':
                $this->execSQL($this->getUpgradeFile('7.0'));
            case '7_1':
                $this->execSQL($this->getUpgradeFile('7.1'));
                $this->initOrder();
            case '7_2':
            case '7_2_4':
                $this->execSQL($this->getUpgradeFile('7.2.4'));
            case '7_2_5':
                $this->adjustOrder7_3();
            case '7_3':
                $this->execSQL($this->getUpgradeFile('7.3'));
                $this->adjustPriv7_4_beta();
            case '7_4_beta':
                $this->execSQL($this->getUpgradeFile('7.4.beta'));
            case '8_0':
            case '8_0_1':
                $this->execSQL($this->getUpgradeFile('8.0.1'));
                $this->addPriv8_1();
            case '8_1':
                $this->execSQL($this->getUpgradeFile('8.1'));
            case '8_1_3':
                $this->execSQL($this->getUpgradeFile('8.1.3'));
                $this->addPriv8_2_beta();
                $this->adjustConfigSectionAndKey();
            case '8_2_beta':
            case '8_2':
            case '8_2_1':
                $this->execSQL($this->getUpgradeFile('8.2.1'));
            case '8_2_2':
            case '8_2_3':
            case '8_2_4':
            case '8_2_5':
            case '8_2_6':
                $this->execSQL($this->getUpgradeFile('8.2.6'));
                $this->adjustDocModule();
                $this->moveDocContent();
                $this->adjustPriv8_3();
            case '8_3':
            case '8_3_1':
                $this->execSQL($this->getUpgradeFile('8.3.1'));
                $this->renameMainLib();
                $this->adjustPriv8_4();
            case '8_4':
            case '8_4_1':
                $this->execSQL($this->getUpgradeFile('8.4.1'));
            case '9_0_beta':
                $this->execSQL($this->getUpgradeFile('9.0.beta'));
                $this->adjustPriv9_0();
            case '9_0':
                $this->fixProjectProductData();
            case '9_0_1':
                $this->execSQL($this->getUpgradeFile('9.0.1'));
                $this->addBugDeadlineToCustomFields();
                $this->adjustPriv9_0_1();
            case '9_1':
                $this->execSQL($this->getUpgradeFile('9.1'));
            case '9_1_1':
                $this->execSQL($this->getUpgradeFile('9.1.1'));
            case '9_1_2':
                $this->execSQL($this->getUpgradeFile('9.1.2'));
                $this->processCustomMenus();
                $this->adjustPriv9_2();
            case '9_2':
            case '9_2_1':
            case '9_3_beta':
                $this->execSQL($this->getUpgradeFile('9.3.beta'));
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
        case '4_0_beta1': $confirmContent .= file_get_contents($this->getUpgradeFile('4.0.beta1'));
        case '4_0_beta2': $confirmContent .= file_get_contents($this->getUpgradeFile('4.0.beta2'));
        case '4_0':       $confirmContent .= file_get_contents($this->getUpgradeFile('4.0'));
        case '4_0_1':     $confirmContent .= file_get_contents($this->getUpgradeFile('4.0.1'));
        case '4_1':       $confirmContent .= file_get_contents($this->getUpgradeFile('4.1'));
        case '4_2_beta':  $confirmContent .= file_get_contents($this->getUpgradeFile('4.2'));
        case '4_3_beta':  $confirmContent .= file_get_contents($this->getUpgradeFile('4.3'));
        case '5_0_beta1':
        case '5_0_beta2':
        case '5_0':
        case '5_1':
        case '5_2':
        case '5_2_1':     $confirmContent .= file_get_contents($this->getUpgradeFile('5.2.1'));
        case '5_3':
        case '6_0_beta1': $confirmContent .= file_get_contents($this->getUpgradeFile('6.0.beta1'));
        case '6_0':       $confirmContent .= file_get_contents($this->getUpgradeFile('6.0'));
        case '6_1':       $confirmContent .= file_get_contents($this->getUpgradeFile('6.1'));
        case '6_2':
        case '6_3':
        case '6_4':
        case '7_0':       $confirmContent .= file_get_contents($this->getUpgradeFile('7.0'));
        case '7_1':       $confirmContent .= file_get_contents($this->getUpgradeFile('7.1'));
        case '7_2':
        case '7_2_4':     $confirmContent .= file_get_contents($this->getUpgradeFile('7.2.4'));
        case '7_2_5':
        case '7_3':       $confirmContent .= file_get_contents($this->getUpgradeFile('7.3'));
        case '7_4_beta':  $confirmContent .= file_get_contents($this->getUpgradeFile('7.4.beta'));
        case '8_0':
        case '8_0_1':     $confirmContent .= file_get_contents($this->getUpgradeFile('8.0.1'));
        case '8_1':       $confirmContent .= file_get_contents($this->getUpgradeFile('8.1'));
        case '8_1_3':     $confirmContent .= file_get_contents($this->getUpgradeFile('8.1.3'));
        case '8_2_beta': 
        case '8_2':
        case '8_2_1':     $confirmContent .= file_get_contents($this->getUpgradeFile('8.2.1'));
        case '8_2_2':
        case '8_2_3':
        case '8_2_4':
        case '8_2_5':
        case '8_2_6':     $confirmContent .= file_get_contents($this->getUpgradeFile('8.2.6'));
        case '8_3':
        case '8_3_1':     $confirmContent .= file_get_contents($this->getUpgradeFile('8.3.1'));
        case '8_4':
        case '8_4_1':     $confirmContent .= file_get_contents($this->getUpgradeFile('8.4.1'));
        case '9_0_beta':  $confirmContent .= file_get_contents($this->getUpgradeFile('9.0.beta'));
        case '9_0':
        case '9_0_1':     $confirmContent .= file_get_contents($this->getUpgradeFile('9.0.1'));
        case '9_1':       $confirmContent .= file_get_contents($this->getUpgradeFile('9.1'));
        case '9_1_1':     $confirmContent .= file_get_contents($this->getUpgradeFile('9.1.1'));
        case '9_1_2':     $confirmContent .= file_get_contents($this->getUpgradeFile('9.1.2'));
        case '9_2':
        case '9_2_1':
        case '9_3_beta':  $confirmContent .= file_get_contents($this->getUpgradeFile('9.3.beta'));
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
        $userTemplates = $this->dao->select('id, content')->from($this->config->db->prefix . 'userTPL')->fetchAll();
            
        foreach($bugs as $id => $bug)
        {
            $bug->steps = ubb::parseUBB($bug->steps);
            $this->dao->update(TABLE_BUG)->data($bug)->where('id')->eq($bug->id)->exec();
        }
        foreach($userTemplates as $template)
        {
            $template->content = ubb::parseUBB($template->content);
            $this->dao->update($this->config->db->prefix . 'userTPL')->data($template)->where('id')->eq($template->id)->exec();
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
        $stories   = $this->dao->select('story, version, spec')->from($this->config->db->prefix . 'storySpec')->fetchAll();
        $todos     = $this->dao->select('id, `desc`')->from(TABLE_TODO)->fetchAll();
        $testTasks = $this->dao->select('id, `desc`')->from($this->config->db->prefix . 'testTask')->fetchAll();

        foreach($tasks as $task)
        {
            $task->desc = nl2br($task->desc);
            $this->dao->update(TABLE_TASK)->data($task)->where('id')->eq($task->id)->exec();
        }
        foreach($stories as $story)
        {
            $story->spec = nl2br($story->spec);
            $this->dao->update($this->config->db->prefix . 'storySpec')->data($story)->where('story')->eq($story->story)->andWhere('version')->eq($story->version)->exec();
        }

        foreach($todos as $todo)
        {
            $todo->desc = nl2br($todo->desc);
            $this->dao->update(TABLE_TODO)->data($todo)->where('id')->eq($todo->id)->exec();
        }

        foreach($testTasks as $testtask)
        {
            $testtask->desc = nl2br($testtask->desc);
            $this->dao->update($this->config->db->prefix . 'testTask')->data($testtask)->where('id')->eq($testtask->id)->exec();
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
        $plans    = $this->dao->select('id, `desc`')->from($this->config->db->prefix . 'productPlan')->fetchAll();
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
            $this->dao->update($this->config->db->prefix . 'productPlan')->data($plan)->where('id')->eq($plan->id)->exec();
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
                $this->dao->update(TABLE_ACTION)->set('action')->eq($action->action)->where('id')->eq($action->id)->exec();
            }
        }

        /* Update db. */
        foreach($tasks as $task)
        {
            $this->dao->update(TABLE_TASK)->data($task)->where('id')->eq($task->id)->exec();
        }

        $this->dao->update(TABLE_TASK)->set('assignedTo=openedBy, assignedDate = finishedDate')->where('status')->eq('done')->exec();
        $this->dao->update(TABLE_TASK)->set('assignedTo=openedBy, assignedDate = canceledDate')->where('status')->eq('cancel')->exec();

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
        $results = $this->dao->select('`case`, date, caseResult')->from($this->config->db->prefix . 'testResult')->orderBy('id desc')->fetchGroup('case');
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
     * Update type of projects. 
     * 
     * @access public
     * @return void
     */
    public function updateProjectType()
    {
        $projects = $this->dao->select('root')->from(TABLE_MODULE)->where('type')->eq('task')->fetchPairs('root'); 
        $this->dao->update(TABLE_PROJECT)->set('type')->eq('waterfall')->where('id')->in($projects)->exec();        
        return true;
    }

    /**
     * Update estimate priv.
     * 
     * @access public
     * @return void
     */
    public function updateEstimatePriv()
    {
        $privTable = $this->config->db->prefix . 'groupPriv';
        $groups = $this->dao->select('*')->from($privTable)
            ->where('module')->eq('task')
            ->andWhere('method')->eq('edit')
            ->fetchAll();
        foreach($groups as $group)
        {
            $this->dao->delete()->from($privTable)
                ->where('`group`')->eq($group->group)
                ->andWhere('module')->eq('task')
                ->andWhere('method')->eq('recordEstimate')
                ->exec();
            $this->dao->insert($privTable)
                ->set('company')->eq($group->company)
                ->set('`group`')->eq($group->group)
                ->set('module')->eq('task')
                ->set('method')->eq('recordEstimate')
                ->exec();

            $this->dao->delete()->from($privTable)
                ->where('`group`')->eq($group->group)
                ->andWhere('module')->eq('task')
                ->andWhere('method')->eq('editEstimate')
                ->exec();
            $this->dao->insert($privTable)
                ->set('company')->eq($group->company)
                ->set('`group`')->eq($group->group)
                ->set('module')->eq('task')
                ->set('method')->eq('editEstimate')
                ->exec();

            $this->dao->delete()->from($privTable)
                ->where('`group`')->eq($group->group)
                ->andWhere('module')->eq('task')
                ->andWhere('method')->eq('deleteEstimate')
                ->exec();
            $this->dao->insert($privTable)
                ->set('company')->eq($group->company)
                ->set('`group`')->eq($group->group)
                ->set('module')->eq('task')
                ->set('method')->eq('deleteEstimate')
                ->exec();
        }
        return true;
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
        $projectProducts = $this->dao->select('project,product')->from($this->config->db->prefix . 'projectProduct')->where('project')->in(array_keys($projects))->fetchGroup('project', 'product');
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
        $this->dao->delete()->from(TABLE_EXTENSION)->where('type')->eq('patch')->exec();
        $this->dao->delete()->from(TABLE_EXTENSION)->where('code')->in('zentaopatch,patch')->exec();
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
        return $this->app->getAppRoot() . 'db' . DS . 'update' . $version . '.sql';
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
        $ignoreCode   = '|1050|1060|1062|1091|1169|1061|';

        /* Read the sql file to lines, remove the comment lines, then join theme by ';'. */
        $sqls = explode("\n", file_get_contents($sqlFile));
        foreach($sqls as $key => $line) 
        {
            $line       = trim($line);
            $sqls[$key] = $line;

            /* Skip sql that is note. */
            if(preg_match('/^--|^#|^\/\*/', $line) or empty($line)) unset($sqls[$key]);
        }
        $sqls = explode(';', join("\n", $sqls));

        foreach($sqls as $sql)
        {
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
                $errorInfo = $e->errorInfo;
                $errorCode = $errorInfo[1];
                if(strpos($ignoreCode, "|$errorCode|") === false) self::$errors[] = $e->getMessage() . "<p>The sql is: $sql</p>";
            }
        }
    }

    /**
     * Add priv for version 4.0.1 
     * 
     * @access public
     * @return void
     */
    public function addPriv4_0_1()
    {
        $privTable = $this->config->db->prefix . 'groupPriv';
        $oldPriv = $this->dao->select('*')->from($privTable)
            ->where('module')->eq('company')
            ->andWhere('method')->eq('edit')
            ->fetchAll();

        foreach($oldPriv as $item)
        {
            $this->dao->insert($privTable)
                ->set('company')->eq($item->company)
                ->set('module')->eq('company')
                ->set('method')->eq('view')
                ->set('`group`')->eq($item->group)
                ->exec();
        }

        $oldPriv = $this->dao->select('*')->from($privTable)
            ->where('module')->eq('todo')
            ->andWhere('method')->eq('finish')
            ->fetchAll();

        foreach($oldPriv as $item)
        {
            $this->dao->insert($privTable)
                ->set('company')->eq($item->company)
                ->set('module')->eq('todo')
                ->set('method')->eq('batchFinish')
                ->set('`group`')->eq($item->group)
                ->exec();
        }

        return true;
    }

    /**
     * Add priv for version 4.1 
     * 
     * @access public
     * @return bool 
     */
    public function addPriv4_1()
    {
        $privTable = $this->config->db->prefix . 'groupPriv';
        $oldPriv = $this->dao->select('*')->from($privTable)
            ->where('module')->eq('tree')
            ->andWhere('method')->eq('browse')
            ->fetchAll();

        foreach($oldPriv as $item)
        {
            $this->dao->insert($privTable)
                ->set('company')->eq($item->company)
                ->set('module')->eq('tree')
                ->set('method')->eq('browseTask')
                ->set('`group`')->eq($item->group)
                ->exec();
        }

        return true;
    }

    /**
     * Add priv for version 8.1
     *
     * @access public
     * @return bool
     */
    public function addPriv8_1()
    {
        $privTable = $this->config->db->prefix . 'grouppriv';

        $oldPriv = $this->dao->select('*')->from($privTable)
            ->where('module')->eq('bug')
            ->andWhere('method')->eq('edit')
            ->fetchAll();
        foreach($oldPriv as $item)
        {
            $this->dao->replace($privTable)
                ->set('module')->eq('bug')
                ->set('method')->eq('linkBugs')
                ->set('`group`')->eq($item->group)
                ->exec();
            $this->dao->replace($privTable)
                ->set('module')->eq('bug')
                ->set('method')->eq('unlinkBug')
                ->set('`group`')->eq($item->group)
                ->exec();
        }

        $oldPriv = $this->dao->select('*')->from($privTable)
            ->where('module')->eq('story')
            ->andWhere('method')->eq('edit')
            ->fetchAll();
        foreach($oldPriv as $item)
        {
            $this->dao->replace($privTable)
                ->set('module')->eq('story')
                ->set('method')->eq('linkStory')
                ->set('`group`')->eq($item->group)
                ->exec();
            $this->dao->replace($privTable)
                ->set('module')->eq('story')
                ->set('method')->eq('unlinkStory')
                ->set('`group`')->eq($item->group)
                ->exec();
        }

        $oldPriv = $this->dao->select('*')->from($privTable)
            ->where('module')->eq('testcase')
            ->andWhere('method')->eq('edit')
            ->fetchAll();
        foreach($oldPriv as $item)
        {
            $this->dao->replace($privTable)
                ->set('module')->eq('testcase')
                ->set('method')->eq('linkCases')
                ->set('`group`')->eq($item->group)
                ->exec();
            $this->dao->replace($privTable)
                ->set('module')->eq('testcase')
                ->set('method')->eq('unlinkCase')
                ->set('`group`')->eq($item->group)
                ->exec();
        }

        return true;
    }

    /**
     * Add priv for 8.2.
     * 
     * @access public
     * @return bool
     */
    public function addPriv8_2_beta()
    {
        $privTable = $this->config->db->prefix . 'grouppriv';

        /* Change product-all priv. */
        $groups = $this->dao->select('`group`')->from($privTable)->where('`module`')->eq('product')->andWhere('`method`')->eq('index')->fetchPairs('group', 'group');
        foreach($groups as $group) $this->dao->replace($privTable)->set('module')->eq('product')->set('method')->eq('all')->set('`group`')->eq($group)->exec();

        /* Change project-all priv. */
        $groups = $this->dao->select('`group`')->from($privTable)->where('`module`')->eq('project')->andWhere('`method`')->eq('index')->fetchPairs('group', 'group');
        foreach($groups as $group) $this->dao->replace($privTable)->set('module')->eq('project')->set('method')->eq('all')->set('`group`')->eq($group)->exec();

        /* Add kanban and tree priv. */
        $groups = $this->dao->select('`group`')->from($privTable)->where('`module`')->eq('project')->andWhere('`method`')->eq('task')->fetchPairs('group', 'group');
        foreach($groups as $group)
        {
            $this->dao->replace($privTable)->set('module')->eq('project')->set('method')->eq('kanban')->set('`group`')->eq($group)->exec();
            $this->dao->replace($privTable)->set('module')->eq('project')->set('method')->eq('tree')->set('`group`')->eq($group)->exec();
        }

        /* Change manageContacts priv. */
        $groups = $this->dao->select('`group`')->from($privTable)->where('`module`')->eq('user')->andWhere('`method`')->eq('manageContacts')->fetchPairs('group', 'group');
        foreach($groups as $group) $this->dao->replace($privTable)->set('module')->eq('my')->set('method')->eq('manageContacts')->set('`group`')->eq($group)->exec();

        /* Change deleteContacts priv. */
        $groups = $this->dao->select('`group`')->from($privTable)->where('`module`')->eq('user')->andWhere('`method`')->eq('deleteContacts')->fetchPairs('group', 'group');
        foreach($groups as $group) $this->dao->replace($privTable)->set('module')->eq('my')->set('method')->eq('deleteContacts')->set('`group`')->eq($group)->exec();

        /* Change batchChangeModule priv. */
        $oldPriv = $this->dao->select('*')->from($privTable)
            ->where("(`module`='story'      and `method`='edit')")
            ->orWhere("(`module`='task'     and `method`='edit')")
            ->orWhere("(`module`='bug'      and `method`='edit')")
            ->orWhere("(`module`='testcase' and `method`='edit')")
            ->fetchAll();
        foreach($oldPriv as $item) $this->dao->replace($privTable)->set('module')->eq($item->module)->set('method')->eq('batchChangeModule')->set('`group`')->eq($item->group)->exec();

        return true;
    }

    /**
     * Adjust config section and key.
     * 
     * @access public
     * @return bool
     */
    public function adjustConfigSectionAndKey()
    {
        $this->dao->update(TABLE_CONFIG)->set('`key`')->eq('productProject')->where('`key`')->eq('productproject')->andWhere('module')->eq('custom')->exec();

        $this->dao->update(TABLE_CONFIG)->set('section')->eq('bugBrowse')->where('section')->eq('bugbrowse')->andWhere('module')->eq('datatable')->exec();
        $this->dao->update(TABLE_CONFIG)->set('section')->eq('productBrowse')->where('section')->eq('productbrowse')->andWhere('module')->eq('datatable')->exec();
        $this->dao->update(TABLE_CONFIG)->set('section')->eq('projectTask')->where('section')->eq('projecttask')->andWhere('module')->eq('datatable')->exec();
        $this->dao->update(TABLE_CONFIG)->set('section')->eq('testcaseBrowse')->where('section')->eq('testcasebrowse')->andWhere('module')->eq('datatable')->exec();
        $this->dao->update(TABLE_CONFIG)->set('section')->eq('testtaskCases')->where('section')->eq('testtaskcases')->andWhere('module')->eq('datatable')->exec();

        return true;
    }

    /**
     * To lower table.
     * 
     * @param  string $build 
     * @access public
     * @return bool
     */
    public function toLowerTable($build = 'basic')
    {
        $results    = $this->dbh->query("show Variables like '%table_names'")->fetchAll();
        $hasLowered = false;
        foreach($results as $result)
        {
            if(strtolower($result->Variable_name) == 'lower_case_table_names' and $result->Value == 1)
            {
                $hasLowered = true;
                break;
            }
        }
        if($hasLowered) return true;

        if($build == 'basic') $tables2Rename = $this->config->upgrade->lowerTables;
        if($build == 'pro') $tables2Rename = $this->config->upgrade->lowerProTables;
        if(!isset($tables2Rename)) return false;

        $tablesExists = $this->dbh->query('SHOW TABLES')->fetchAll();
        foreach($tablesExists as $key => $table) $tablesExists[$key] = current((array)$table);
        $tablesExists = array_flip($tablesExists);

        foreach($tables2Rename as $oldTable => $newTable)
        {
            if(!isset($tablesExists[$oldTable])) continue;
            
            $upgradebak = $newTable . '_othertablebak';
            if(isset($tablesExists[$upgradebak])) $this->dbh->query("DROP TABLE `$upgradebak`");
            if(isset($tablesExists[$newTable])) $this->dbh->query("RENAME TABLE `$newTable` TO `$upgradebak`");

            $tempTable = $oldTable . '_zentaotmp';
            $this->dbh->query("RENAME TABLE `$oldTable` TO `$tempTable`");
            $this->dbh->query("RENAME TABLE `$tempTable` TO `$newTable`");
        }

        return true;
    }

    /**
     * Process finishedBy and finishedDate of task.
     * 
     * @access public
     * @return bool
     */
    public function processTaskFinish()
    {
        $this->dao->update(TABLE_TASK)
            ->set('finishedBy = lastEditedBy')
            ->set('finishedDate = lastEditedDate')
            ->where('status')->in('done,closed')
            ->andWhere('finishedBy')->eq('')
            ->exec();

        return true;
    }

    /**
     * Delete company field for the table of zt_config and zt_groupPriv.
     * 
     * @access public
     * @return void
     */
    public function deleteCompany()
    {
        $privTable = $this->config->db->prefix . 'groupPriv';
        /* Delete priv that is not in this company. Prevent conflict when delete company's field.*/
        $this->dao->delete()->from($privTable)->where('company')->ne($this->app->company->id)->exec();
        $this->dbh->exec("ALTER TABLE " . $privTable . " DROP `company`;");

        /* Delete config that don't conform to the rules. Prevent conflict when delete company's field.*/
        $rows    = $this->dao->select('*')->from(TABLE_CONFIG)->orderBy('id desc')->fetchAll('id');
        $items   = array();
        $delList = array();
        foreach($rows as $config)
        {
            if(isset($items[$config->owner][$config->module][$config->section][$config->key]))
            {
                $delList[] = $config->id;
                continue;
            }

            $items[$config->owner][$config->module][$config->section][$config->key] = $config->id;
        }
        if($delList) $this->dao->delete()->from(TABLE_CONFIG)->where('id')->in($delList)->exec();

        $this->dbh->exec("ALTER TABLE " . TABLE_CONFIG . " DROP `company`;");

        return true;
    }

    /**
     * Merge the goal and desc of project.
     * 
     * @access public
     * @return void
     */
    public function mergeProjectGoalAndDesc()
    {
        $projects = $this->dao->select('*')->from(TABLE_PROJECT)->fetchAll('id');
        foreach($projects as $id => $project)
        {
            if(!isset($project->goal)) continue;

            $this->dao->update(TABLE_PROJECT)
                ->set('`desc`')->eq($project->desc . '<br />' . $project->goal)
                ->where('id')->eq($id)
                ->exec();
        }
        return true;
    }

    /**
     * Fix OS info of bugs.
     * 
     * @access public
     * @return void
     */
    public function fixBugOSInfo()
    {
        $this->dao->update(TABLE_BUG)->set('os')->eq('android')->where('os')->eq('andriod')->exec();
        $this->dao->update(TABLE_BUG)->set('os')->eq('osx')->where('os')->eq('mac')->exec();
    }

    /**
     * Fix finishedBy of task.
     * 
     * @access public
     * @return void
     */
    public function fixTaskFinishedBy()
    {
        $tasks = $this->dao->select('t1.id,t2.actor,t2.date')->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_ACTION)->alias('t2')
            ->on('t1.id = t2.objectID')
            ->leftJoin(TABLE_HISTORY)->alias('t3')
            ->on('t2.id = t3.action')
            ->where('t3.new')->eq(0)
            ->andWhere('t3.field')->eq('left')
            ->andWhere('t2.objectType')->eq('task')
            ->andWhere('t1.finishedBy')->eq('')
            ->andWhere('t1.status')->in('done,closed')
            ->andWhere('t1.deleted')->eq(0)
            ->fetchAll('id');
        foreach($tasks as $taskID => $task)
        {
            $this->dao->update(TABLE_TASK)
                ->set('finishedBy')->eq($task->actor)
                ->set('finishedDate')->eq($task->date)
                ->where('id')->eq($taskID)
                ->exec();
        }
    }

    /**
     * Touch index.html for upload when has not it.
     * 
     * @access public
     * @return bool
     */
    public function fixDataIndex()
    {
        $savePath = $this->loadModel('file')->savePath;
        foreach(glob($savePath . '*') as $childDir)
        {
            if(is_dir($childDir) and !is_file($childDir . '/index.html')) @touch($childDir . '/index.html');
        }
        return true;
    }

    /**
     * Init order.
     * 
     * @access public
     * @return bool
     */
    public function initOrder()
    {
        $dataList = $this->dao->select('id')->from(TABLE_PRODUCT)->orderBy('code_desc')->fetchAll();
        $i = 1;
        foreach($dataList as $data) $this->dao->update(TABLE_PRODUCT)->set('`order`')->eq($i++)->where('id')->eq($data->id)->exec();

        $dataList = $this->dao->select('id')->from(TABLE_PROJECT)->orderBy('code_desc')->fetchAll();
        $i = 1;
        foreach($dataList as $data) $this->dao->update(TABLE_PROJECT)->set('`order`')->eq($i++)->where('id')->eq($data->id)->exec();

        return true;
    }

    /**
     * Adjust order for 7.3
     * 
     * @access public
     * @return void
     */
    public function adjustOrder7_3()
    {
        $this->loadModel('product')->fixOrder();
        $this->loadModel('project')->fixOrder();

        return true;
    }

    /**
     * Adjust priv for 7.4.beta 
     * 
     * @access public
     * @return void
     */
    public function adjustPriv7_4_beta()
    {
        $groups = $this->dao->select('id')->from(TABLE_GROUP)->where('name')->ne('guest')->fetchPairs('id', 'id');
        foreach($groups as $groupID)
        {
            $groupPriv = new stdclass();
            $groupPriv->group = $groupID;
            $groupPriv->module = 'my';
            $groupPriv->method = 'unbind';
            $this->dao->replace(TABLE_GROUPPRIV)->data($groupPriv)->exec();
        }

        return true;
    }

    /**
     * Adjust doc module.
     * 
     * @access public
     * @return bool
     */
    public function adjustDocModule()
    {
        set_time_limit(0);
        $this->app->loadLang('doc');
        $productDocModules = $this->dao->select('*')->from(TABLE_MODULE)->where('type')->eq('productdoc')->orderBy('grade,id')->fetchAll('id');
        $allProductIdList  = $this->dao->select('id,name,acl,whitelist,createdBy')->from(TABLE_PRODUCT)->where('deleted')->eq('0')->fetchAll('id');
        foreach($allProductIdList as $productID => $product)
        {
            $this->dao->delete()->from(TABLE_DOCLIB)->where('product')->eq($productID)->exec();

            $lib = new stdclass();
            $lib->product = $productID;
            $lib->name    = $this->lang->doclib->main['product'];
            $lib->main    = 1;
            $lib->acl     = $product->acl == 'open' ? 'open' : 'custom';
            $lib->users   = $product->createdBy;
            if($product->acl == 'custom') $lib->groups = $product->whitelist;
            $this->dao->insert(TABLE_DOCLIB)->data($lib)->exec();
            $libID = $this->dao->lastInsertID();

            $relation = array();
            foreach($productDocModules as $moduleID => $module)
            {

                unset($module->id);
                $module->root = $libID;
                $module->type = 'doc';
                $this->dao->insert(TABLE_MODULE)->data($module)->exec();

                $newModuleID = $this->dao->lastInsertID();
                $relation[$moduleID] = $newModuleID;
                $newPaths = array();
                foreach(explode(',', trim($module->path, ',')) as $path)
                {
                    if(isset($relation[$path])) $newPaths[] = $relation[$path];
                }

                $newPaths = join(',', $newPaths);
                $this->dao->update(TABLE_MODULE)->set('path')->eq($newPaths)->set('parent')->eq($relation[$module->parent])->where('id')->eq($newModuleID)->exec();
                $this->dao->update(TABLE_DOC)->set('module')->eq($newModuleID)->where('product')->eq($productID)->andWhere('module')->eq($moduleID)->andWhere('lib')->eq('product')->exec();
            }
            $this->dao->update(TABLE_DOC)->set('lib')->eq($libID)->where('product')->eq($productID)->exec();
        }
        $this->dao->delete()->from(TABLE_MODULE)->where('id')->in(array_keys($productDocModules))->exec();

        $projectDocModules = $this->dao->select('*')->from(TABLE_MODULE)->where('type')->eq('projectdoc')->orderBy('grade,id')->fetchAll('id');
        $allProjectIdList  = $this->dao->select('id,name,acl,whitelist')->from(TABLE_PROJECT)->where('deleted')->eq('0')->fetchAll('id');
        foreach($allProjectIdList as $projectID => $project)
        {
            $this->dao->delete()->from(TABLE_DOCLIB)->where('project')->eq($projectID)->exec();

            $lib = new stdclass();
            $lib->project = $projectID;
            $lib->name    = $this->lang->doclib->main['project'];
            $lib->main    = 1;
            $lib->acl     = $project->acl == 'open' ? 'open' : 'custom';

            $teams = $this->dao->select('project, account')->from(TABLE_TEAM)->where('project')->eq($projectID)->fetchPairs('account', 'account');
            $lib->users = join(',', $teams);
            if($project->acl == 'custom') $lib->groups = $project->whitelist;
            $this->dao->insert(TABLE_DOCLIB)->data($lib)->exec();
            $libID = $this->dao->lastInsertID();

            $docLibs = $this->dao->select('id,users')->from(TABLE_DOCLIB)->alias('t1')
                ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.product=t2.product')
                ->where('t2.project')->eq($projectID)
                ->andWhere('t1.acl')->eq('custom')
                ->fetchAll('id');
            foreach($docLibs as $lib)
            {
                $docUsers = $teams + explode(',', $lib->users);
                $docUsers = array_unique($docUsers);
                $this->dao->update(TABLE_DOCLIB)->set('users')->eq(join(',', $docUsers))->where('id')->eq($lib->id)->exec();
            }

            $relation = array();
            foreach($projectDocModules as $moduleID => $module)
            {
                unset($module->id);
                $module->root = $libID;
                $module->type = 'doc';
                $this->dao->insert(TABLE_MODULE)->data($module)->exec();

                $newModuleID = $this->dao->lastInsertID();
                $relation[$moduleID] = $newModuleID;
                $newPaths = array();
                foreach(explode(',', trim($module->path, ',')) as $path)
                {
                    if(isset($relation[$path])) $newPaths[] = $relation[$path];
                }

                $newPaths = join(',', $newPaths);
                $newPaths = ",$newPaths,";
                $this->dao->update(TABLE_MODULE)->set('path')->eq($newPaths)->where('id')->eq($newModuleID)->exec();
                $this->dao->update(TABLE_DOC)->set('module')->eq($newModuleID)->where('project')->eq($projectID)->andWhere('module')->eq($moduleID)->exec();
            }
            $this->dao->update(TABLE_DOC)->set('lib')->eq($libID)->where('project')->eq($projectID)->exec();
        }
        $this->dao->delete()->from(TABLE_MODULE)->where('id')->in(array_keys($projectDocModules))->exec();

        return true;
    }

    /**
     * Update file objectID in editor.
     * 
     * @access public
     * @return bool
     */
    public function updateFileObjectID($type = '', $lastID = 0)
    {
        $limit = 100;
        if(empty($type)) $type = 'comment';
        $result['type']   = $type;
        $result['lastID'] = 0;
        if($type == 'comment')
        {
            $comments = $this->dao->select('id,objectType,objectID,comment')->from(TABLE_ACTION)->where('comment')->like('%data/upload/%')->andWhere('id')->gt($lastID)->orderBy('id')->limit($limit)->fetchAll('id');
            foreach($comments as $action)
            {
                $files = array();
                preg_match_all('/"data\/upload\/.*1\/([0-9]{6}\/[^"]+)"/', $action->comment, $output);
                foreach($output[1] as $path)$files[$path] = $path;
                $this->dao->update(TABLE_FILE)->set('objectType')->eq($action->objectType)->set('objectID')->eq($action->objectID)->set('extra')->eq('editor')->where('pathname')->in($files)->exec();
            }
            if(count($comments) < $limit)
            {
                $result['type']   = 'doc';
                $result['count']  = count($comments);
				$result['lastID'] = 0;
            }
            else
            {
                $result['type']   = 'comment';
                $result['count']  = count($comments);
                $result['lastID'] = $action->id;
            }
            return $result;
        }

        $editors['doc']         = array('table' => TABLE_DOCCONTENT,  'fields' => 'doc,`content`,`digest`');
        $editors['project']     = array('table' => TABLE_PROJECT,     'fields' => 'id,`desc`');
        $editors['bug']         = array('table' => TABLE_BUG,         'fields' => 'id,`steps`');
        $editors['release']     = array('table' => TABLE_RELEASE,     'fields' => 'id,`desc`');
        $editors['productplan'] = array('table' => TABLE_PRODUCTPLAN, 'fields' => 'id,`desc`');
        $editors['product']     = array('table' => TABLE_PRODUCT,     'fields' => 'id,`desc`');
        $editors['story']       = array('table' => TABLE_STORYSPEC,   'fields' => 'story,`spec`,`verify`');
        $editors['testtask']    = array('table' => TABLE_TESTTASK,    'fields' => 'id,`desc`,`report`');
        $editors['todo']        = array('table' => TABLE_TODO,        'fields' => 'id,`desc`');
        $editors['task']        = array('table' => TABLE_TASK,        'fields' => 'id,`desc`');
        $editors['build']       = array('table' => TABLE_BUILD,       'fields' => 'id,`desc`');

        $editor = $editors[$type];
        $fields = explode(',', $editor['fields']);
        $cond   = array();
        foreach($fields as $field)
        {
            if(strpos($field, '`') !== false) $cond[]  = $field . " like '%data/upload/%'";
            if(strpos($field, '`') === false) $idField = $field;
        }
        $objects = $this->dao->select($editor['fields'])->from($editor['table'])
            ->where($idField)->gt($lastID)
            ->beginIF($cond)->andWhere('(' . join(' OR ', $cond) . ')')->fi()
            ->orderBy($idField)
            ->limit($limit)
            ->fetchAll($idField);
        foreach($objects as $object)
        {
            $files    = array();
            $objectID = 0;
            foreach($fields as $field)
            {
                if(strpos($field, '`') === false)
                {
                    $objectID = $object->$field;
                }
                else
                {
                    $field = trim($field, '`');
                    preg_match_all('/"\/?data\/upload\/.*1\/([0-9]{6}\/[^"]+)"/', $object->$field, $output);
                    foreach($output[1] as $path)$files[$path] = $path;
                }
            }
            if($files) $this->dao->update(TABLE_FILE)->set('objectType')->eq($type)->set('objectID')->eq($objectID)->set('extra')->eq('editor')->where('pathname')->in($files)->exec();
        }
            if(count($objects) < $limit)
            {
                $editorKeys = array_keys($editors);
                foreach($editorKeys as $i => $objectType)
                {
                    if($type == $objectType)
                    {
                        $nextType = isset($editorKeys[$i + 1]) ? $editorKeys[$i + 1] : '';
                        break;
                    }
                }
                $result['type']   = empty($nextType) ? 'finish' : $nextType;
				$result['count']  = count($objects);
                $result['lastID'] = 0;
            }
            else
            {
                $result['type']   = $type;
                $result['count']  = count($objects);
                $result['lastID'] = $object->$idField;
            }
            return $result;
    }

    /**
     * Move doc content to table zt_doccontent.
     * 
     * @access public
     * @return bool
     */
    public function moveDocContent()
    {
        $descDoc = $this->dao->query('DESC ' .  TABLE_DOC)->fetchAll();
        $processFields = 0;
        foreach($descDoc as $field)
        {
            if($field->Field == 'content' or $field->Field == 'digest' or $field->Field == 'url') $processFields ++; 
        }
        if($processFields < 3) return true;

        $this->dao->exec('TRUNCATE TABLE ' . TABLE_DOCCONTENT);
        $stmt = $this->dao->select('id,title,digest,content,url')->from(TABLE_DOC)->query();
        $fileGroups = $this->dao->select('id,objectID')->from(TABLE_FILE)->where('objectType')->eq('doc')->fetchGroup('objectID', 'id');
        while($doc = $stmt->fetch())
        {
            $url = empty($doc->url) ? '' : urldecode($doc->url);
            $docContent = new stdclass();
            $docContent->doc      = $doc->id;
            $docContent->title    = $doc->title;
            $docContent->digest   = $doc->digest;
            $docContent->content  = $doc->content;
            $docContent->content .= empty($url) ? '' : $url;
            $docContent->version  = 1;
            $docContent->type     = 'html';
            if(isset($fileGroups[$doc->id])) $docContent->files = join(',', array_keys($fileGroups[$doc->id]));
            $this->dao->insert(TABLE_DOCCONTENT)->data($docContent)->exec();
        }
        $this->dao->exec('ALTER TABLE ' . TABLE_DOC . ' DROP `digest`');
        $this->dao->exec('ALTER TABLE ' . TABLE_DOC . ' DROP `content`');
        $this->dao->exec('ALTER TABLE ' . TABLE_DOC . ' DROP `url`');
        return true;
    }

    /**
     * Adjust priv 8.3 
     * 
     * @access public
     * @return bool
     */
    public function adjustPriv8_3()
    {
        $docPrivGroups = $this->dao->select('`group`')->from(TABLE_GROUPPRIV)->where('module')->eq('doc')->andWhere('method')->eq('index')->fetchPairs('group', 'group');
        foreach($docPrivGroups as $groupID)
        {
            $data = new stdclass();
            $data->group = $groupID;
            $data->module = 'doc';
            $data->method = 'allLibs';
            $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();

            $data->method = 'showFiles';
            $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();

            $data->method = 'objectLibs';
            $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
        }
        return true;
    }
    
    /**
     * Rename main lib.
     * 
     * @access public
     * @return bool
     */
    public function renameMainLib()
    {
        $this->app->loadLang('doc');
        $this->dao->update(TABLE_DOCLIB)->set('name')->eq($this->lang->doclib->main['product'])->where('product')->gt(0)->andWhere('main')->eq(1)->exec();
        $this->dao->update(TABLE_DOCLIB)->set('name')->eq($this->lang->doclib->main['project'])->where('project')->gt(0)->andWhere('main')->eq(1)->exec();
        return true;
    }

    /**
     * Adjust priv for 8.4.
     * 
     * @access public
     * @return bool
     */
    public function adjustPriv8_4()
    {
        $groups = $this->dao->select('`group`')->from(TABLE_GROUPPRIV)->where('module')->eq('branch')->andWhere('method')->eq('manage')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $data = new stdclass();
            $data->group = $groupID;
            $data->module = 'branch';
            $data->method = 'sort';
            $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
        }
        $groups = $this->dao->select('`group`')->from(TABLE_GROUPPRIV)->where('module')->eq('story')->andWhere('method')->eq('tasks')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $data = new stdclass();
            $data->group = $groupID;
            $data->module = 'story';
            $data->method = 'bugs';
            $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();

            $data->method = 'cases';
            $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
        }
        return true;
    }

    /**
     * Adjust priv for 9.0 
     * 
     * @access public
     * @return void
     */
    public function adjustPriv9_0()
    {
        $groups = $this->dao->select('`group`')->from(TABLE_GROUPPRIV)->where('module')->eq('testtask')->andWhere('method')->eq('results')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $data = new stdclass();
            $data->group = $groupID;
            $data->module = 'testcase';
            $data->method = 'bugs';
            $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
        }
        $groups = $this->dao->select('`group`')->from(TABLE_GROUPPRIV)->where('module')->eq('mail')->andWhere('method')->eq('delete')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $data = new stdclass();
            $data->group = $groupID;
            $data->module = 'mail';
            $data->method = 'resend';
            $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
        }
        return true;
    }

    /**
     * Fix projectproduct data.
     * 
     * @access public
     * @return bool
     */
    public function fixProjectProductData()
    {
        $this->dao->delete()->from(TABLE_PROJECTPRODUCT)->where('product')->eq(0)->exec();
        return true;
    }

    /**
     * Add bug deadline for custom fields.
     * 
     * @access public
     * @return bool
     */
    public function addBugDeadlineToCustomFields()
    {
        $createFieldsItems = $this->dao->select('id, value')->from(TABLE_CONFIG)
            ->where('module')->eq('bug')
            ->andWhere('section')->eq('custom')
            ->andWhere('`key`')->eq('createFields')
            ->fetchAll();
        $batchEditFieldsItems = $this->dao->select('id, value')->from(TABLE_CONFIG)
            ->where('module')->eq('bug')
            ->andWhere('section')->eq('custom')
            ->andWhere('`key`')->eq('batchEditFields')
            ->fetchAll();

        foreach($createFieldsItems as $createFieldsItem)
        {
            $value = empty($createFieldsItem->value) ? 'deadline' : $createFieldsItem->value . ",deadline";
            $this->dao->update(TABLE_CONFIG)->set('value')->eq($value)->where('id')->eq($createFieldsItem->id)->exec();
        }
        foreach($batchEditFieldsItems as $batchEditFieldsItem)
        {
            $value = empty($batchEditFieldsItem->value) ? 'deadline' : $batchEditFieldsItem->value . ",deadline";
            $this->dao->update(TABLE_CONFIG)->set('value')->eq($value)->where('id')->eq($batchEditFieldsItem->id)->exec();
        }

        return true;
    }

    /**
     * Adjust priv for 9.0.1. 
     * 
     * @access public
     * @return bool
     */
    public function adjustPriv9_0_1()
    {
        $groups = $this->dao->select('`group`')->from(TABLE_GROUPPRIV)->where('module')->eq('testcase')->andWhere('method')->eq('edit')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $data = new stdclass();
            $data->group  = $groupID;
            $data->module = 'testcase';
            $newMethods   = array('review', 'batchReview', 'batchCaseTypeChange', 'batchConfirmStoryChange');
            foreach($newMethods as $method)
            {
                $data->method = $method;
                $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
            }

            $data->module = 'testsuite';
            $newMethods   = array('create', 'edit', 'delete', 'linkCase', 'unlinkCase', 'batchUnlinkCases');
            foreach($newMethods as $method)
            {
                $data->method = $method;
                $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
            }
        }

        $groups = $this->dao->select('`group`')->from(TABLE_GROUPPRIV)->where('module')->eq('testtask')->andWhere('method')->eq('start')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $data = new stdclass();
            $data->group  = $groupID;
            $data->module = 'testtask';
            $newMethods   = array('activate', 'block', 'report');
            foreach($newMethods as $method)
            {
                $data->method = $method;
                $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
            }
        }

        $groups = $this->dao->select('distinct `group`')->from(TABLE_GROUPPRIV)->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $data = new stdclass();
            $data->group  = $groupID;
            $data->module = 'testsuite';
            $newMethods   = array('index', 'browse', 'view');
            foreach($newMethods as $method)
            {
                $data->method = $method;
                $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
            }
        }
        return true;
    }

    /**
     * Adjust priv for 9.2.
     * 
     * @access public
     * @return void
     */
    public function adjustPriv9_2()
    {
        $groups = $this->dao->select('`group`')->from(TABLE_GROUPPRIV)->where('module')->eq('testsuite')->andWhere('method')->eq('createCase')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $data = new stdclass();
            $data->group  = $groupID;
            $data->module = 'testsuite';
            $newMethods   = array('batchCreateCase', 'exportTemplet', 'import', 'showImport');
            foreach($newMethods as $method)
            {
                $data->method = $method;
                $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
            }
        }

        $groups = $this->dao->select('`group`')->from(TABLE_GROUPPRIV)->where('module')->eq('product')->andWhere('method')->eq('index')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $data = new stdclass();
            $data->group  = $groupID;
            $data->module = 'product';
            $data->method = 'build';
            $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
        }

        $groups = $this->dao->select('`group`')->from(TABLE_GROUPPRIV)->where('module')->eq('custom')->andWhere('method')->eq('flow')->fetchPairs('group', 'group');
        foreach($groups as $groupID)
        {
            $data = new stdclass();
            $data->group  = $groupID;
            $data->module = 'custom';
            $data->method = 'working';
            $this->dao->replace(TABLE_GROUPPRIV)->data($data)->exec();
        }
        return true;
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

    /**
     * Check safe file.
     * 
     * @access public
     * @return string|false
     */
    public function checkSafeFile()
    {
        $statusFile = $this->app->getAppRoot() . 'www' . DIRECTORY_SEPARATOR . 'ok.txt';
        return (!file_exists($statusFile) or (time() - filemtime($statusFile)) > 3600) ? $statusFile : false;
    }

    /**
     * Check weither process or not.
     * 
     * @access public
     * @return array
     */
    public function checkProcess()
    {
		$fromVersion = $this->config->installedVersion;
		$needProcess = array();
        if(strpos($fromVersion, 'pro') === false ? $fromVersion < '8.3' : $fromVersion < 'pro5.4') $needProcess['updateFile'] = true;
		return $needProcess;
    }

    /**
     * Process customMenus for different working. 
     * 
     * @access public
     * @return void
     */
    public function processCustomMenus()
    {
        $this->loadModel('setting')->setItem('system.common.global.flow', 'full');
        $customMenus = $this->dao->select('*')->from(TABLE_CONFIG)->where('section')->eq('customMenu')->fetchAll();

        foreach($customMenus as $customMenu)
        {
            $this->dao->update(TABLE_CONFIG)->set('`key`')->eq("full_{$customMenu->key}")->where('id')->eq($customMenu->id)->exec();
        }

        return !dao::isError();
    }
}
