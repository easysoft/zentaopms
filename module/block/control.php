<?php
 /**
 * The control file of block of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class block extends control
{
    /**
     * construct.
     *
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        /* Mark the call from zentao or ranzhi. */
        $this->selfCall = !isset($_GET['hash']);
        if($this->methodName != 'admin' and $this->methodName != 'dashboard' and !$this->selfCall and !$this->loadModel('sso')->checkKey()) die('');
    }

    /**
     * Block admin.
     *
     * @param  int    $id
     * @param  string $module
     * @access public
     * @return void
     */
    public function admin($id = 0, $module = 'my')
    {
        $this->session->set('blockModule', $module);

        $title = $id == 0 ? $this->lang->block->createBlock : $this->lang->block->editBlock;

        if($module == 'my')
        {
            $modules = $this->lang->block->moduleList;
            foreach($modules as $moduleKey => $moduleName)
            {
                if($moduleKey == 'todo') continue;
                if(in_array($moduleKey, $this->app->user->rights['acls'])) unset($modules[$moduleKey]);
                if(!common::hasPriv($moduleKey, 'index')) unset($modules[$moduleKey]);
            }

            $closedBlock = isset($this->config->block->closed) ? $this->config->block->closed : '';
            if(strpos(",$closedBlock,", ",|assigntome,") === false) $modules['assigntome'] = $this->lang->block->assignToMe;
            if(strpos(",$closedBlock,", ",|dynamic,") === false) $modules['dynamic'] = $this->lang->block->dynamic;
            if(strpos(",$closedBlock,", ",|flowchart,") === false and $this->config->global->flow == 'full') $modules['flowchart'] = $this->lang->block->lblFlowchart;
            if(strpos(",$closedBlock,", ",|welcome,") === false and $this->config->global->flow == 'full') $modules['welcome'] = $this->lang->block->welcome;
            if(strpos(",$closedBlock,", ",|html,") === false) $modules['html'] = 'HTML';
            if(strpos(",$closedBlock,", ",|contribute,") === false) $modules['contribute'] = $this->lang->block->contribute;
            $modules = array('' => '') + $modules;

            $hiddenBlocks = $this->block->getHiddenBlocks();
            foreach($hiddenBlocks as $block) $modules['hiddenBlock' . $block->id] = $block->title;
            $this->view->modules = $modules;
        }
        elseif(isset($this->lang->block->moduleList[$module]))
        {
            $this->get->set('mode', 'getblocklist');
            if($module == 'project') $this->get->set('dashboard', 'project');
            $this->view->blocks = $this->fetch('block', 'main', "module=$module&id=$id");
            $this->view->module = $module;
        }

        $this->view->title   = $title;
        $this->view->block   = $this->block->getByID($id);
        $this->view->blockID = $id;
        $this->display();
    }

    /**
     * Set params when type is rss or html.
     *
     * @param  int    $id
     * @param  string $type
     * @access public
     * @return void
     */
    public function set($id, $type, $source = '')
    {
        if($_POST)
        {
            $source = isset($this->lang->block->moduleList[$source]) ? $source : '';
            $this->block->save($id, $source, $type, $this->session->blockModule);
            if(dao::isError())  die(js::error(dao::geterror()));
            die(js::reload('parent'));
        }

        $block = $this->block->getByID($id);
        if($block and empty($type)) $type = $block->block;
        if(isset($block->params->num) and !isset($block->params->count))
        {
            $block->params->count = $block->params->num;
            unset($block->params->num);
        }

        if(isset($this->lang->block->moduleList[$source]))
        {
            $func   = 'get' . ucfirst($type) . 'Params';
            $params = $this->block->$func($source);
            $this->view->params = json_decode($params, true);
        }
        elseif($type == 'assigntome')
        {
            $params = $this->block->getAssignToMeParams();
            $this->view->params = json_decode($params, true);
        }

        $this->view->source = $source;
        $this->view->type   = $type;
        $this->view->id     = $id;
        $this->view->block  = ($block) ? $block : array();
        $this->display();
    }

    /**
     * Delete block
     *
     * @param  int    $id
     * @param  string $sys
     * @param  string $type
     * @access public
     * @return void
     */
    public function delete($id, $module = 'my', $type = 'delete')
    {
        if($type == 'hidden')
        {
            $this->dao->update(TABLE_BLOCK)->set('hidden')->eq(1)->where('`id`')->eq($id)->andWhere('account')->eq($this->app->user->account)->andWhere('module')->eq($module)->exec();
        }
        else
        {
            $this->dao->delete()->from(TABLE_BLOCK)->where('`id`')->eq($id)->andWhere('account')->eq($this->app->user->account)->andWhere('module')->eq($module)->exec();
        }
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
        $this->loadModel('score')->create('block', 'set');
        $this->send(array('result' => 'success'));
    }

    /**
     * Sort block.
     *
     * @param  string    $oldOrder
     * @param  string    $newOrder
     * @param  string    $module
     * @access public
     * @return void
     */
    public function sort($orders, $module = 'my')
    {
        $orders    = explode(',', $orders);
        $blockList = $this->block->getBlockList($module);

        foreach ($orders as $order => $blockID)
        {
            $block = $blockList[$blockID];
            if(!isset($block)) continue;
            $block->order = $order;
            $this->dao->replace(TABLE_BLOCK)->data($block)->exec();
        }

        if(dao::isError()) $this->send(array('result' => 'fail'));
        $this->loadModel('score')->create('block', 'set');
        $this->send(array('result' => 'success'));
    }

    /**
     * Resize block
     * @param  integer $id
     * @access public
     * @return void
     */
    public function resize($id, $type, $data)
    {
        $block = $this->block->getByID($id);
        if($block)
        {
            $field = '';
            if($type == 'vertical') $field = 'height';
            if($type == 'horizontal') $field = 'grid';
            if(empty($field)) $this->send(array('result' => 'fail', 'code' => 400));

            $block->$field = $data;
            $block->params = helper::jsonEncode($block->params);
            $this->dao->replace(TABLE_BLOCK)->data($block)->exec();
            if(dao::isError()) $this->send(array('result' => 'fail', 'code' => 500));
            $this->send(array('result' => 'success'));
        }
        else
        {
            $this->send(array('result' => 'fail', 'code' => 404));
        }
    }

    /**
     * Display dashboard for app.
     *
     * @param  string    $module
     * @param  string    $type
     * @param  int       $projectID
     * @access public
     * @return void
     */
    public function dashboard($module, $type = '', $projectID = 0)
    {
        if($this->loadModel('user')->isLogon()) $this->session->set('blockModule', $module);
        $blocks = $this->block->getBlockList($module, $type);

        $commonField = 'common';
        if($module == 'project' and $projectID)
        {
            $project     = $this->loadModel('project')->getByID($this->session->project);
            $commonField = $project->model . 'common';
        }

        $inited = empty($this->config->$module->$commonField->blockInited) ? '' : $this->config->$module->$commonField->blockInited;

        /* Init block when vist index first. */
        if((empty($blocks) and !$inited and !defined('TUTORIAL')))
        {
            if($this->block->initBlock($module, $type)) die(js::reload());
        }

        $acls = $this->app->user->rights['acls'];
        $shortBlocks = $longBlocks = array();
        foreach($blocks as $key => $block)
        {
            if(!empty($block->source) and $block->source != 'todo' and !empty($acls['views']) and !isset($acls['views'][$block->source]))
            {
                unset($blocks[$key]);
                continue;
            }

            $block->params = json_decode($block->params);
            if(isset($block->params->num) and !isset($block->params->count)) $block->params->count = $block->params->num;

            $blockID = $block->block;
            $source  = empty($block->source) ? 'common' : $block->source;

            $block->blockLink = $this->createLink('block', 'printBlock', "id=$block->id&module=$block->module");
            $block->moreLink  = '';
            if(isset($this->lang->block->modules[$source]->moreLinkList->{$blockID}))
            {
                list($moduleName, $method, $vars) = explode('|', sprintf($this->lang->block->modules[$source]->moreLinkList->{$blockID}, isset($block->params->type) ? $block->params->type : ''));

                /* The list assigned to me jumps to the work page when click more button. */
                $block->moreLink = $this->createLink($moduleName, $method, $vars);
                if($moduleName == 'my' and strpos('task|story|requirement|bug|testcase|testtask|issue|risk', $method))
                {
                    $block->moreLink = $this->createLink($moduleName, 'work', 'mode=' . $method . '&' . $vars);
                }
                elseif($moduleName == 'project' and $method == 'dynamic')
                {
                    $block->moreLink = $this->createLink('project', 'dynamic', "projectID=$projectID&type=all");
                }
                elseif($moduleName == 'project' and $method == 'testtask')
                {
                    $block->moreLink = $this->createLink('project', 'testtask', "projectID=$projectID");
                }
            }
            elseif($block->block == 'dynamic')
            {
                $block->moreLink = $this->createLink('company', 'dynamic');
            }

            if($this->block->isLongBlock($block))
            {
                $longBlocks[$key] = $block;
            }
            else
            {
                $shortBlocks[$key] = $block;
            }
        }

        $this->view->longBlocks  = $longBlocks;
        $this->view->shortBlocks = $shortBlocks;
        $this->view->module      = $module;

        if($this->app->getViewType() == 'json') die(json_encode($blocks));

        $this->display();
    }

    /**
     * latest dynamic.
     *
     * @access public
     * @return void
     */
    public function dynamic()
    {
        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager(0, 30, 1);

        $this->view->actions = $this->loadModel('action')->getDynamic('all', 'today', 'date_desc', $pager);
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');
        $this->display();
    }

    /**
     * Welcome block.
     *
     * @access public
     * @return void
     */
    public function welcome()
    {
        $this->view->tutorialed = $this->loadModel('tutorial')->getTutorialed();

        $data = $this->block->getWelcomeBlockData();

        $this->view->tasks      = $data['tasks'];
        $this->view->doneTasks  = $data['doneTasks'];
        $this->view->bugs       = $data['bugs'];
        $this->view->stories    = $data['stories'];
        $this->view->executions = $data['executions'];

        $this->view->delay['task'] = $data['delayTask'];
        $this->view->delay['bug']  = $data['delayBug'];

        $time = date('H:i');
        $welcomeType = '19:00';
        foreach($this->lang->block->welcomeList as $type => $name)
        {
            if($time >= $type) $welcomeType = $type;
        }
        $this->view->welcomeType = $welcomeType;
        $this->display();
    }

    /**
     * Print contribute block.
     *
     * @access public
     * @return void
     */
    public function contribute()
    {
        $this->view->data = $this->loadModel('user')->getPersonalData();
        $this->display();
    }


    /**
     * Print block.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function printBlock($id, $module = 'my')
    {
        $block = $this->block->getByID($id);

        if(empty($block)) return false;

        $html = '';
        if($block->block == 'html')
        {
            if (empty($block->params->html))
            {
                $html = "<div class='empty-tip'>" . $this->lang->block->emptyTip . "</div>";
            }
            else
            {
                $html = "<div class='panel-body'><div class='article-content'>" . $block->params->html . '</div></div>';
            }
        }
        elseif($block->source != '')
        {
            $this->get->set('mode', 'getblockdata');
            $this->get->set('blockTitle', $block->title);
            $this->get->set('module', $block->module);
            $this->get->set('source', $block->source);
            $this->get->set('blockid', $block->block);
            $this->get->set('param', base64_encode(json_encode($block->params)));
            $html = $this->fetch('block', 'main', "module={$block->source}&id=$id");
        }
        elseif($block->block == 'dynamic')
        {
            $html = $this->fetch('block', 'dynamic');
        }
        elseif($block->block == 'flowchart')
        {
            $html = $this->fetch('block', 'flowchart');
        }
        elseif($block->block == 'assigntome')
        {
            $this->get->set('param', base64_encode(json_encode($block->params)));
            $html = $this->fetch('block', 'printAssignToMeBlock', 'longBlock=' . $this->block->isLongBlock($block));
        }
        elseif($block->block == 'welcome')
        {
            $html = $this->fetch('block', 'welcome');
        }
        elseif($block->block == 'contribute')
        {
            $html = $this->fetch('block', 'contribute');
        }

        echo $html;
    }

    /**
     * Main function.
     *
     * @access public
     * @return void
     */
    public function main($module = '', $id = 0)
    {
        if(!$this->selfCall)
        {
            $lang = str_replace('_', '-', $this->get->lang);
            $this->app->setClientLang($lang);
            $this->app->loadLang('common');
            $this->app->loadLang('block');

            if(!$this->block->checkAPI($this->get->hash)) die();
        }

        $mode = strtolower($this->get->mode);

        if($mode == 'getblocklist')
        {
            $model     = '';
            $block     = $this->block->getByID($id);
            $dashboard = $this->get->dashboard;

            /* Create a project block. */
            if($dashboard == 'project')
            {
                $project = $this->loadModel('project')->getByID($this->session->project);
                $model   = $project->model;
            }

            /* Edit a project block. */
            if($id and $block->module == 'project')
            {
                $model     = $block->type;
                $dashboard = 'project';
            }

            $blocks = $this->block->getAvailableBlocks($module, $dashboard, $model);
            if(!$this->selfCall)
            {
                echo $blocks;
                return true;
            }

            $blocks     = json_decode($blocks, true);
            $blockPairs = array('' => '') + $blocks;

            echo '<div class="form-group">';
            echo '<label for="moduleBlock" class="col-sm-3">' . $this->lang->block->lblBlock . '</label>';
            echo '<div class="col-sm-7">';
            if($model) echo html::hidden('type', $model);
            echo html::select('moduleBlock', $blockPairs, ($block and $block->source != '') ? $block->block : '', "class='form-control chosen'");
            echo '</div></div>';
        }
        elseif($mode == 'getblockform')
        {
            $code = strtolower($this->get->blockid);
            $func = 'get' . ucfirst($code) . 'Params';
            echo $this->block->$func($module);
        }
        elseif($mode == 'getblockdata')
        {
            $code = strtolower($this->get->blockid);

            $params = $this->get->param;
            $params = json_decode(base64_decode($params));
            if(isset($params->num) and !isset($params->count)) $params->count = $params->num;
            if(!$this->selfCall)
            {
                $this->app->user = $this->dao->select('*')->from(TABLE_USER)->where('ranzhi')->eq($params->account)->fetch();
                if(empty($this->app->user))
                {
                    $this->app->user = new stdclass();
                    $this->app->user->account = 'guest';
                }
                $this->app->user->admin  = strpos($this->app->company->admins, ",{$this->app->user->account},") !== false;
                $this->app->user->rights = $this->loadModel('user')->authorize($this->app->user->account);
                $this->app->user->groups = $this->user->getGroups($this->app->user->account);
                $this->app->user->view   = $this->user->grantUserView($this->app->user->account, $this->app->user->rights['acls']);

                $sso = base64_decode($this->get->sso);
                $this->view->sso  = $sso;
                $this->view->sign = strpos($sso, '?') === false ? '?' : '&';
            }

            if($id) $block = $this->block->getByID($id);
            $this->view->longBlock = $this->block->isLongBlock($id ? $block : $params);
            $this->view->selfCall  = $this->selfCall;
            $this->view->block     = $id ? $block : '';

            $this->viewType    = (isset($params->viewType) and $params->viewType == 'json') ? 'json' : 'html';
            $this->params      = $params;
            $this->view->code  = $this->get->blockid;
            $this->view->title = $this->get->blockTitle;

            $func = 'print' . ucfirst($code) . 'Block';
            if(method_exists('block', $func))
            {
                $this->$func($module);
            }
            else
            {
                $this->view->data = $this->block->$func($module, $params);
            }

            $this->view->moreLink = '';
            if(isset($this->lang->block->modules[$module]->moreLinkList->{$code}))
            {
                list($moduleName, $method, $vars) = explode('|', sprintf($this->lang->block->modules[$module]->moreLinkList->{$code}, isset($params->type) ? $params->type : ''));
                $this->view->moreLink = $this->createLink($moduleName, $method, $vars);
            }

            if($this->viewType == 'json')
            {
                unset($this->view->app);
                unset($this->view->config);
                unset($this->view->lang);
                unset($this->view->header);
                unset($this->view->position);
                unset($this->view->moduleTree);

                $output['status'] = is_object($this->view) ? 'success' : 'fail';
                $output['data']   = json_encode($this->view);
                $output['md5']    = md5(json_encode($this->view));
                die(json_encode($output));
            }

            $this->display();
        }
    }

    /**
     * Print List block.
     *
     * @access public
     * @return void
     */
    public function printListBlock($module = 'product')
    {
        $func = 'print' . ucfirst($module) . 'Block';
        $this->view->module = $module;
        $this->$func();

    }

    /**
     * Print todo block.
     *
     * @access public
     * @return void
     */
    public function printTodoBlock()
    {
        $limit = $this->viewType == 'json' ? 0 : (int)$this->params->count;
        $todos = $this->loadModel('todo')->getList('all', $this->app->user->account, 'wait, doing', $limit, $pager = null, $orderBy = 'date, begin');
        $uri   = $this->app->getURI(true);

        $this->session->set('todoList',     $uri, 'my');
        $this->session->set('bugList',      $uri, 'qa');
        $this->session->set('taskList',     $uri, 'execution');
        $this->session->set('storyList',    $uri, 'product');
        $this->session->set('testtaskList', $uri, 'qa');

        foreach($todos as $key => $todo)
        {
            if($todo->date == '2030-01-01') unset($todos[$key]);
        }

        $this->view->todos = $todos;
    }

    /**
     * Print task block.
     *
     * @access public
     * @return void
     */
    public function printTaskBlock()
    {
        $this->session->set('taskList',  $this->app->getURI(true), 'execution');
        if(preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) die();

        $account = $this->app->user->account;
        $type    = $this->params->type;

        $this->app->loadLang('execution');
        $this->view->tasks = $this->loadModel('task')->getUserTasks($account, $type, $this->viewType == 'json' ? 0 : (int)$this->params->count, null, $this->params->orderBy);
    }

    /**
     * Print bug block.
     *
     * @access public
     * @return void
     */
    public function printBugBlock()
    {
        $this->session->set('bugList', $this->app->getURI(true), 'qa');
        if(preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) die();

        $projectID = $this->lang->navGroup->qa  == 'project' ? $this->session->project : 0;
        $projectID = $this->view->block->module == 'my' ? 0 : $projectID;
        $this->view->bugs = $this->loadModel('bug')->getUserBugs($this->app->user->account, $this->params->type, $this->params->orderBy, $this->viewType == 'json' ? 0 : (int)$this->params->count, null, $projectID);
    }

    /**
     * Print case block.
     *
     * @access public
     * @return void
     */
    public function printCaseBlock()
    {
        $this->session->set('caseList', $this->app->getURI(true), 'qa');
        $this->app->loadLang('testcase');
        $this->app->loadLang('testtask');

        $projectID = $this->lang->navGroup->qa  == 'project' ? $this->session->project : 0;
        $projectID = $this->view->block->module == 'my' ? 0 : $projectID;

        $cases = array();
        if($this->params->type == 'assigntome')
        {
            $cases = $this->dao->select('t1.assignedTo AS assignedTo, t2.*')->from(TABLE_TESTRUN)->alias('t1')
                ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
                ->leftJoin(TABLE_TESTTASK)->alias('t3')->on('t1.task = t3.id')
                ->Where('t1.assignedTo')->eq($this->app->user->account)
                ->andWhere('t1.status')->ne('done')
                ->andWhere('t3.status')->ne('done')
                ->andWhere('t3.deleted')->eq(0)
                ->andWhere('t2.deleted')->eq(0)
                ->beginIF($projectID)->andWhere('t2.project')->eq($projectID)->fi()
                ->orderBy($this->params->orderBy)
                ->beginIF($this->viewType != 'json')->limit((int)$this->params->count)->fi()
                ->fetchAll();
        }
        elseif($this->params->type == 'openedbyme')
        {
            $cases = $this->dao->findByOpenedBy($this->app->user->account)->from(TABLE_CASE)
                ->andWhere('deleted')->eq(0)
                ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
                ->orderBy($this->params->orderBy)
                ->beginIF($this->viewType != 'json')->limit((int)$this->params->count)->fi()
                ->fetchAll();
        }
        $this->view->cases = $cases;
    }

    /**
     * Print testtask block.
     *
     * @access public
     * @return void
     */
    public function printTesttaskBlock()
    {
        $this->app->loadLang('testtask');

        $this->session->set('productList',  $this->app->getURI(true), 'product');
        $this->session->set('testtaskList', $this->app->getURI(true), 'qa');
        $this->session->set('buildList',    $this->app->getURI(true), 'execution');
        if(preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) die();

        $this->view->testtasks = $this->dao->select('t1.*,t2.name as productName,t3.name as buildName,t4.name as projectName')->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->leftJoin(TABLE_BUILD)->alias('t3')->on('t1.build=t3.id')
            ->leftJoin(TABLE_PROJECT)->alias('t4')->on('t1.execution=t4.id')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t5')->on('t1.execution=t5.project')
            ->where('t1.deleted')->eq('0')
            ->beginIF(!$this->app->user->admin)->andWhere('t1.product')->in($this->app->user->view->products)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t1.execution')->in($this->app->user->view->sprints)->fi()
            ->andWhere('t1.product = t5.product')
            ->beginIF($this->params->type != 'all')->andWhere('t1.status')->eq($this->params->type)->fi()
            ->orderBy('t1.id desc')
            ->beginIF($this->viewType != 'json')->limit((int)$this->params->count)->fi()
            ->fetchAll();
    }

    /**
     * Print story block.
     *
     * @access public
     * @return void
     */
    public function printStoryBlock()
    {
        $this->session->set('storyList', $this->app->getURI(true), 'product');
        if(preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) die();

        $this->app->loadClass('pager', $static = true);
        $count   = isset($this->params->count) ? (int)$this->params->count : 0;
        $pager   = pager::init(0, $count , 1);
        $type    = isset($this->params->type) ? $this->params->type : 'assignedTo';
        $orderBy = isset($this->params->type) ? $this->params->orderBy : 'id_asc';

        $this->view->stories = $this->loadModel('story')->getUserStories($this->app->user->account, $type, $orderBy, $this->viewType != 'json' ? $pager : '', 'story');
    }

    /**
     * Print plan block.
     *
     * @access public
     * @return void
     */
    public function printPlanBlock()
    {
        $this->session->set('productList', $this->app->getURI(true), 'product');
        $this->session->set('productPlanList', $this->app->getURI(true), 'product');

        $this->app->loadLang('productplan');
        $this->view->plans = $this->dao->select('t1.*,t2.name as productName')->from(TABLE_PRODUCTPLAN)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq('0')
            ->beginIF(!$this->app->user->admin)->andWhere('t1.product')->in($this->app->user->view->products)->fi()
            ->orderBy('t1.begin desc')
            ->beginIF($this->viewType != 'json')->limit((int)$this->params->count)->fi()
            ->fetchAll();
    }

    /**
     * Print releases block.
     *
     * @access public
     * @return void
     */
    public function printReleaseBlock()
    {
        $this->session->set('releaseList', $this->app->getURI(true), 'product');
        $this->session->set('buildList', $this->app->getURI(true), 'execution');

        $this->app->loadLang('release');
        $this->view->releases = $this->dao->select('t1.*,t2.name as productName,t3.name as buildName')->from(TABLE_RELEASE)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->leftJoin(TABLE_BUILD)->alias('t3')->on('t1.build=t3.id')
            ->where('t1.deleted')->eq('0')
            ->beginIF($this->view->block->module != 'my' and $this->session->project)->andWhere('t1.project')->eq((int)$this->session->project)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t1.product')->in($this->app->user->view->products)->fi()
            ->orderBy('t1.id desc')
            ->beginIF($this->viewType != 'json')->limit((int)$this->params->count)->fi()
            ->fetchAll();
    }

    /**
     * Print Build block.
     *
     * @access public
     * @return void
     */
    public function printBuildBlock()
    {
        $this->session->set('buildList', $this->app->getURI(true), 'execution');
        $this->app->loadLang('build');

        $builds = $this->dao->select('t1.*, t2.name as productName')->from(TABLE_BUILD)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq('0')
            ->beginIF(!$this->app->user->admin)->andWhere('t1.execution')->in($this->app->user->view->sprints)->fi()
            ->beginIF($this->view->block->module != 'my' and $this->session->project)->andWhere('t1.project')->eq((int)$this->session->project)->fi()
            ->orderBy('t1.id desc')
            ->beginIF($this->viewType != 'json')->limit((int)$this->params->count)->fi()
            ->fetchAll();
        $this->view->builds = $builds;
    }

    /**
     * Print project block.
     *
     * @access public
     * @return void
     */
    public function printProjectBlock()
    {
        $this->app->loadLang('execution');
        $this->app->loadLang('task');
        $count   = isset($this->params->count)   ? $this->params->count   : 15;
        $type    = isset($this->params->type)    ? $this->params->type    : 'all';
        $orderBy = isset($this->params->orderBy) ? $this->params->orderBy : 'id_desc';

        $this->view->projects = $this->loadModel('project')->getOverviewList('byStatus', $type, $orderBy, $count);
        $this->view->users    = $this->loadModel('user')->getPairs('noletter');
    }

    /**
     * Print product block.
     *
     * @access public
     * @return void
     */
    public function printProductBlock()
    {
        $this->app->loadClass('pager', $static = true);
        if(!empty($this->params->type) and preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) die();
        $count = isset($this->params->count) ? (int)$this->params->count : 0;
        $type  = isset($this->params->type) ? $this->params->type : '';
        $pager = pager::init(0, $count , 1);

        $productStats  = $this->loadModel('product')->getStats('order_desc', $this->viewType != 'json' ? $pager : '', $type);
        $productIdList = array();
        foreach($productStats as $product) $productIdList[] = $product->id;

        $this->view->executions = $this->dao->select('t1.product,t2.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.product')->in($productIdList)
            ->andWhere('t2.type')->in('stage,sprint')
            ->andWhere('t2.deleted')->eq(0)
            ->orderBy('t1.project')
            ->fetchPairs('product', 'name');
        $this->view->productStats = $productStats;
    }

    /**
     * Print statistic block.
     *
     * @param  string $module
     * @access public
     * @return void
     */
    public function printStatisticBlock($module = 'product')
    {
        $func = 'print' . ucfirst($module) . 'StatisticBlock';
        $this->view->module = $module;
        $this->$func();
    }

    /**
     * Print project statistic block.
     *
     * @access public
     * @return void
     */
    public function printProjectStatisticBlock()
    {
        if(!empty($this->params->type) and preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) die();

        /* Load models and langs. */
        $this->loadModel('project');
        $this->loadModel('weekly');
        $this->app->loadLang('task');
        $this->app->loadLang('story');
        $this->app->loadLang('bug');

        /* Set project status and count. */
        $status = isset($this->params->type)  ? $this->params->type       : 'all';
        $count  = isset($this->params->count) ? (int)$this->params->count : 15;

        /* Get projects. */
        $projects = $this->loadModel('project')->getOverviewList('byStatus', $status, 'id_desc', $count);
        if(empty($projects))
        {
            $this->view->projects = $projects;
            return false;
        }

        $today  = helper::today();
        if(isset($this->config->maxVersion)) $monday = date('Ymd', strtotime($this->loadModel('weekly')->getThisMonday($today)));
        $tasks  = $this->dao->select("project,
            sum(consumed) as totalConsumed,
            sum(if(status != 'cancel' and status != 'closed', `left`, 0)) as totalLeft")
            ->from(TABLE_TASK)
            ->where('project')->in(array_keys($projects))
            ->andWhere('deleted')->eq(0)
            ->andWhere('parent')->lt(1)
            ->groupBy('project')
            ->fetchAll('project');

        foreach($projects as $projectID => $project)
        {
            if($project->model == 'scrum')
            {
                $this->app->loadClass('pager', $static = true);
                $pager = pager::init(0, 3, 1);
                $project->progress   = $project->allStories == 0 ? 0 : round($project->doneStories / $project->allStories, 3) * 100;
                $project->executions = $this->project->getStats($projectID, 'all', 0, 0, 30, 'id_desc', $pager);
            }
            elseif($project->model == 'waterfall' and isset($this->config->maxVersion))
            {
                $begin   = $project->begin;
                $weeks   = $this->weekly->getWeekPairs($begin);
                $current = zget($weeks, $monday, '');
                $current = substr($current, 0, -11) . substr($current, -6);

                $project->pv = $this->weekly->getPV($projectID, $today);
                $project->ev = $this->weekly->getEV($projectID, $today);
                $project->ac = $this->weekly->getAC($projectID, $today);
                $project->sv = $this->weekly->getSV($project->ev, $project->pv);
                $project->cv = $this->weekly->getCV($project->ev, $project->ac);

                $progress = isset($tasks[$projectID]) ? (($tasks[$projectID]->totalConsumed + $tasks[$projectID]->totalLeft)) ? round($tasks[$projectID]->totalConsumed / ($tasks[$projectID]->totalConsumed + $tasks[$projectID]->totalLeft), 3) * 100 : 0 : 0;

                $project->current  = $current;
                $project->progress = $progress;
            }
        }

        $this->view->projects = $projects;
        $this->view->users    = $this->loadModel('user')->getPairs('noletter');
    }

    /**
     * Print product statistic block.
     *
     * @access public
     * @param  string $storyType requirement|story
     * @return void
     */
    public function printProductStatisticBlock($storyType = 'story')
    {
        if(!empty($this->params->type) and preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) die();

        $status = isset($this->params->type) ? $this->params->type : '';
        $count  = isset($this->params->count) ? $this->params->count : '';

        $products      = $this->loadModel('product')->getOrderedProducts($status, $count);
        $productIdList = array_keys($products);

        if(empty($products))
        {
            $this->view->products = $products;
            return false;
        }

        /* Get stories. */
        $stories = $this->dao->select('product, stage, COUNT(status) AS count')->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->andWhere('product')->in($productIdList)
            ->beginIF($storyType)->andWhere('type')->eq($storyType)->fi()
            ->groupBy('product, stage')
            ->fetchGroup('product', 'stage');

        /* Padding the stories to sure all status have records. */
        foreach($stories as $product => $story)
        {
            foreach(array_keys($this->lang->story->stageList) as $stage)
            {
                $story[$stage] = isset($story[$stage]) ? $story[$stage]->count : 0;
            }
            $stories[$product] = $story;
        }

        /* Get plans. */
        $plans = $this->dao->select('product, end')->from(TABLE_PRODUCTPLAN)
            ->where('deleted')->eq(0)
            ->andWhere('product')->in($productIdList)
            ->fetchGroup('product');
        foreach($plans as $product => $productPlans)
        {
            $expired   = 0;
            $unexpired = 0;

            foreach($productPlans as $plan)
            {
                if($plan->end <  helper::today()) $expired++;
                if($plan->end >= helper::today()) $unexpired++;
            }

            $plan = array();
            $plan['expired']   = $expired;
            $plan['unexpired'] = $unexpired;

            $plans[$product] = $plan;
        }

        /* Get releases. */
        $releases = $this->dao->select('product, status, COUNT(*) AS count')->from(TABLE_RELEASE)
            ->where('deleted')->eq(0)
            ->andWhere('product')->in($productIdList)
            ->groupBy('product, status')
            ->fetchGroup('product', 'status');
        foreach($releases as $product => $release)
        {
            $release['normal']    = isset($release['normal'])    ? $release['normal']->count    : 0;
            $release['terminate'] = isset($release['terminate']) ? $release['terminate']->count : 0;

            $releases[$product] = $release;
        }

        /* Get last releases. */
        $lastReleases = $this->dao->select('product, COUNT(*) AS count')->from(TABLE_RELEASE)
            ->where('date')->eq(date('Y-m-d', strtotime('-1 day')))
            ->andWhere('product')->in($productIdList)
            ->groupBy('product')
            ->fetchPairs();

        foreach($products as $productID => $product)
        {
            $product->stories     = isset($stories[$productID])      ? $stories[$productID]      : 0;
            $product->plans       = isset($plans[$productID])        ? $plans[$productID]        : 0;
            $product->releases    = isset($releases[$productID])     ? $releases[$productID]     : 0;
            $product->lastRelease = isset($lastReleases[$productID]) ? $lastReleases[$productID] : 0;
        }

        $this->app->loadLang('story');
        $this->app->loadLang('productplan');
        $this->app->loadLang('release');

        $this->view->products = $products;
    }

    /**
     * Print execution statistic block.
     *
     * @access public
     * @return void
     */
    public function printExecutionStatisticBlock()
    {
        if(!empty($this->params->type) and preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) die();

        $this->app->loadLang('task');
        $this->app->loadLang('story');
        $this->app->loadLang('bug');

        $status  = isset($this->params->type)  ? $this->params->type : 'undone';
        $count   = isset($this->params->count) ? (int)$this->params->count : 0;

        /* Get projects. */
        $projectID  = $this->view->block->module == 'my' ? 0 : (int)$this->session->project;
        $executions = $this->loadModel('execution')->getOrderedExecutions($projectID, $status, $count);
        if(empty($executions))
        {
            $this->view->executions = $executions;
            return false;
        }

        $executionIdList = array_keys($executions);

        /* Get tasks. Fix bug #2918.*/
        $yesterday  = date('Y-m-d', strtotime('-1 day'));
        $taskGroups = $this->dao->select("id,parent,project,status,finishedDate,estimate,consumed,`left`")->from(TABLE_TASK)
            ->where('project')->in($executionIdList)
            ->andWhere('deleted')->eq(0)
            ->fetchGroup('project', 'id');

        $tasks = array();
        foreach($taskGroups as $executionID => $taskGroup)
        {
            $undoneTasks       = 0;
            $yesterdayFinished = 0;
            $totalEstimate     = 0;
            $totalConsumed     = 0;
            $totalLeft         = 0;

            foreach($taskGroup as $taskID => $task)
            {
                if(strpos('wait|doing|pause', $task->status) !== false) $undoneTasks ++;
                if(strpos($task->finishedDate, $yesterday) !== false) $yesterdayFinished ++;

                if($task->parent == '-1') continue;

                $totalConsumed += $task->consumed;
                $totalEstimate += $task->estimate;
                if($task->status != 'cancel' and $task->status != 'closed') $totalLeft += $task->left;
            }

            $executions[$executionID]->totalTasks        = count($taskGroup);
            $executions[$executionID]->undoneTasks       = $undoneTasks;
            $executions[$executionID]->yesterdayFinished = $yesterdayFinished;
            $executions[$executionID]->totalEstimate     = round($totalEstimate, 1);
            $executions[$executionID]->totalConsumed     = round($totalConsumed, 1);
            $executions[$executionID]->totalLeft         = round($totalLeft, 1);
        }

        /* Get stories. */
        $stories = $this->dao->select("t1.project, count(t2.status) as totalStories, count(t2.status != 'closed' or null) as unclosedStories, count(t2.stage = 'released' or null) as releasedStories")->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->where('t1.project')->in($executionIdList)
            ->andWhere('t2.deleted')->eq(0)
            ->groupBy('project')
            ->fetchAll('project');

        foreach($stories as $executionID => $story)
        {
            foreach($story as $key => $value)
            {
                if($key == 'project') continue;
                $executions[$executionID]->$key = $value;
            }
        }

        /* Get bugs. */
        $bugs = $this->dao->select("project, count(status) as totalBugs, count(status = 'active' or null) as activeBugs, count(resolvedDate like '{$yesterday}%' or null) as yesterdayResolved")->from(TABLE_BUG)
            ->where('project')->in($executionIdList)
            ->andWhere('deleted')->eq(0)
            ->groupBy('project')
            ->fetchAll('project');

        foreach($bugs as $executionID => $bug)
        {
            foreach($bug as $key => $value)
            {
                if($key == 'project') continue;
                $executions[$executionID]->$key = $value;
            }
        }

        foreach($executions as $execution)
        {
            if(!isset($executions[$execution->id]->totalTasks))
            {
                $executions[$execution->id]->totalTasks        = 0;
                $executions[$execution->id]->undoneTasks       = 0;
                $executions[$execution->id]->yesterdayFinished = 0;
                $executions[$execution->id]->totalEstimate     = 0;
                $executions[$execution->id]->totalConsumed     = 0;
                $executions[$execution->id]->totalLeft         = 0;
            }
            if(!isset($executions[$execution->id]->totalBugs))
            {
                $executions[$execution->id]->totalBugs         = 0;
                $executions[$execution->id]->activeBugs        = 0;
                $executions[$execution->id]->yesterdayResolved = 0;
            }
            if(!isset($executions[$execution->id]->totalStories))
            {
                $executions[$execution->id]->totalStories    = 0;
                $executions[$execution->id]->unclosedStories = 0;
                $executions[$execution->id]->releasedStories = 0;
            }

            $executions[$execution->id]->progress      = ($execution->totalConsumed || $execution->totalLeft) ? round($execution->totalConsumed / ($execution->totalConsumed + $execution->totalLeft), 3) * 100 : 0;
            $executions[$execution->id]->taskProgress  = $execution->totalTasks ? round(($execution->totalTasks - $execution->undoneTasks) / $execution->totalTasks, 2) * 100 : 0;
            $executions[$execution->id]->storyProgress = $execution->totalStories ? round(($execution->totalStories - $execution->unclosedStories) / $execution->totalStories, 2) * 100 : 0;
            $executions[$execution->id]->bugProgress   = $execution->totalBugs ? round(($execution->totalBugs - $execution->activeBugs) / $execution->totalBugs, 2) * 100 : 0;
        }

        $this->view->executions = $executions;
    }

    /**
     * Print waterfall report block.
     *
     * @access public
     * @return void
     */
    public function printWaterfallReportBlock()
    {
        $this->app->loadLang('programplan');
        $project = $this->loadModel('project')->getByID($this->session->project);
        $today   = helper::today();
        $date    = date('Ymd', strtotime('this week Monday'));
        $begin   = $project->begin;
        $weeks   = $this->loadModel('weekly')->getWeekPairs($begin);
        $current = zget($weeks, $date, '');

        $task = $this->dao->select("
            sum(consumed) as totalConsumed,
            sum(if(status != 'cancel' and status != 'closed', `left`, 0)) as totalLeft")
            ->from(TABLE_TASK)->where('project')->eq($this->session->project)
            ->andWhere('deleted')->eq(0)
            ->andWhere('parent')->lt(1)
            ->fetch();

        $this->view->pv = $this->weekly->getPV($this->session->project, $today);
        $this->view->ev = $this->weekly->getEV($this->session->project, $today);
        $this->view->ac = $this->weekly->getAC($this->session->project, $today);
        $this->view->sv = $this->weekly->getSV($this->view->ev, $this->view->pv);
        $this->view->cv = $this->weekly->getCV($this->view->ev, $this->view->ac);

        $this->view->current  = $current;
        $this->view->progress = ($task->totalConsumed + $task->totalLeft) ? floor($task->totalConsumed / ($task->totalConsumed + $task->totalLeft) * 1000) / 1000 * 100 : 0;
    }

    /**
     * Print waterfall gantt block.
     *
     * @access public
     * @return void
     */
    public function printWaterfallGanttBlock()
    {
        $products  = $this->loadModel('product')->getProductPairsByProject($this->session->project);
        $productID = $this->session->product ? $this->session->product : 0;
        $productID = isset($products[$productID]) ? $productID : key($products);

        $this->view->plans     = $this->loadModel('programplan')->getDataForGantt($this->session->project, $productID, 0, 'task', false);
        $this->view->products  = $products;
        $this->view->productID = $productID;
    }

    /**
     * Print waterfall issue block.
     *
     * @access public
     * @return void
     */
    public function printWaterfallIssueBlock()
    {
        $uri = $this->app->getURI(true);
        $this->session->set('issueList', $uri, 'project');
        if(preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) die();
        $this->view->users  = $this->loadModel('user')->getPairs('noletter');
        $this->view->issues = $this->loadModel('issue')->getBlockIssues($this->session->project, $this->params->type, $this->viewType == 'json' ? 0 : (int)$this->params->count, $this->params->orderBy);
    }

    /**
     * Print waterfall risk block.
     *
     * @access public
     * @return void
     */
    public function printWaterfallRiskBlock()
    {
        $uri = $this->app->getURI(true);
        $this->session->set('riskList', $uri, 'project');
        $this->view->users = $this->loadModel('user')->getPairs('noletter');
        $this->view->risks = $this->loadModel('risk')->getBlockRisks($this->session->project, $this->params->type, $this->viewType == 'json' ? 0 : (int)$this->params->count, $this->params->orderBy);
    }

    /**
     * Print waterfall estimate block.
     *
     * @access public
     * @return void
     */
    public function printWaterfallEstimateBlock()
    {
        $this->app->loadLang('durationestimation');
        $this->loadModel('project');

        $projectID = $this->session->project;
        $members   = $this->loadModel('user')->getTeamMemberPairs($projectID, 'project');
        $budget    = $this->loadModel('workestimation')->getBudget($projectID);
        $workhour  = $this->loadModel('project')->getWorkhour($projectID);
        if(empty($budget)) $budget = new stdclass();

        $this->view->people    = $this->dao->select('sum(people) as people')->from(TABLE_DURATIONESTIMATION)->where('project')->eq($this->session->project)->fetch('people');
        $this->view->members   = count($members) ? count($members) - 1 : 0;
        $this->view->consumed  = $this->dao->select('sum(cast(consumed as decimal(10,2))) as consumed')->from(TABLE_TASK)->where('project')->eq($projectID)->andWhere('deleted')->eq(0)->andWhere('parent')->lt(1)->fetch('consumed');
        $this->view->budget    = $budget;
        $this->view->totalLeft = (float)$workhour->totalLeft;
    }

    /**
     * Print waterfall progress block.
     *
     * @access public
     * @return void
     */
    public function printWaterfallProgressBlock()
    {
        $this->loadModel('milestone');
        $this->loadModel('weekly');
        $this->app->loadLang('execution');

        $projectID = $this->session->project;
        $project   = $this->loadModel('project')->getByID($projectID);

        $begin = $project->begin;
        $today = helper::today();
        $end   = date('Y-m-d', strtotime($today));

        $charts['PV'] = '[';
        $charts['EV'] = '[';
        $charts['AC'] = '[';
        $i = 1;
        while($begin < $end)
        {
            $charts['labels'][] = $this->lang->block->time . $i . $this->lang->block->week;
            $charts['PV']      .= $this->weekly->getPV($projectID, $begin) . ',';
            $charts['EV']      .= $this->weekly->getEV($projectID, $begin) . ',';
            $charts['AC']      .= $this->weekly->getAC($projectID, $begin) . ',';
            $stageEnd           = $this->weekly->getThisSunday($begin);
            $begin              = date('Y-m-d', strtotime("$stageEnd + 1 day"));
            $i ++;
        }

        $charts['PV'] .= ']';
        $charts['EV'] .= ']';
        $charts['AC'] .= ']';

        $this->view->charts = $charts;
    }

    /**
     * Print srcum project block.
     *
     * @access public
     * @return void
     */
    public function printScrumOverviewBlock()
    {
        $projectID = $this->session->project;
        $this->app->loadLang('execution');
        $this->app->loadLang('bug');
        $totalData = $this->loadModel('project')->getOverviewList('byId', $projectID, 'id_desc', 1);

        $this->view->totalData = $totalData;
        $this->view->projectID = $projectID;
    }

    /**
     * Print srcum project list block.
     *
     * @access public
     * @return void
     */
    public function printScrumListBlock()
    {
        if(!empty($this->params->type) and preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) die();
        $count = isset($this->params->count) ? (int)$this->params->count : 15;
        $type  = isset($this->params->type) ? $this->params->type : 'undone';

        $this->app->loadClass('pager', $static = true);
        $pager = pager::init(0, $count, 1);
        $this->app->loadLang('execution');
        $this->view->executionStats = $this->loadModel('project')->getStats($this->session->project, $type, 0, 0, 30, 'id_desc', $pager);
    }

    /**
     * Print srcum product block.
     *
     * @access public
     * @return void
     */
    public function printScrumProductBlock()
    {
        $stories  = array();
        $bugs     = array();
        $releases = array();
        $count    = isset($this->params->count) ? (int)$this->params->count : 15;

        $products      = $this->dao->select('id, name')->from(TABLE_PRODUCT)->where('program')->eq($this->session->program)->limit(15)->fetchPairs();
        $productIdList = array_keys($products);
        if(!empty($productIdList))
        {
            $fields   = 'product, count(*) as total';
            $stories  = $this->dao->select($fields)->from(TABLE_STORY)->where('product')->in($productIdList)->andWhere('deleted')->eq('0')->groupBy('product')->fetchPairs();
            $bugs     = $this->dao->select($fields)->from(TABLE_BUG)->where('product')->in($productIdList)->andWhere('deleted')->eq('0')->groupBy('product')->fetchPairs();
            $releases = $this->dao->select($fields)->from(TABLE_RELEASE)->where('product')->in($productIdList)->andWhere('deleted')->eq('0')->groupBy('product')->fetchPairs();
        }

        $this->view->products = $products;
        $this->view->stories  = $stories;
        $this->view->bugs     = $bugs;
        $this->view->releases = $releases;
    }

    /**
     * Print sprint block.
     *
     * @access public
     * @return void
     */
    public function printSprintBlock()
    {
        $sprints = $this->dao->select('status, count(*) as sprints')->from(TABLE_EXECUTION)
            ->where('deleted')->eq(0)
            ->andWhere('type')->eq('sprint')
            ->andWhere('parent')->eq($this->session->project)
            ->groupBy('status')
            ->fetchPairs();

        $summary = new stdclass();
        $summary->total  = array_sum($sprints);
        $summary->doing  = zget($sprints, 'doing', 0);
        $summary->closed = zget($sprints, 'closed', 0);

        $progress = new stdclass();
        $progress->doing  = $summary->total == 0 ? 0 : round($summary->doing  / $summary->total, 3);
        $progress->closed = $summary->total == 0 ? 0 : round($summary->closed / $summary->total, 3);

        $this->view->summary  = $summary;
        $this->view->progress = $progress;
    }

    /**
     * Print project dynamic block.
     *
     * @access public
     * @return void
     */
    public function printProjectDynamicBlock()
    {
        $projectID = $this->session->project;

        $executions = $this->loadModel('execution')->getPairs($projectID);
        $products   = $this->loadModel('product')->getProductPairsByProject($projectID);
        $count      = isset($this->params->count) ? (int)$this->params->count : 10;

        $actions = array();
        $actions = $this->dao->select('*')->from(TABLE_ACTION)
            ->where('objectType')->eq('project')
            ->andWhere('objectID')->eq($projectID)
            ->beginIF(!empty($executions))->markLeft()->orWhere('execution')->in(array_keys($executions))->fi()->markRight()
            ->orderBy('date_desc')
            ->limit($count)
            ->fetchAll();

        $this->view->actions = empty($actions) ? array() : $this->loadModel('action')->transformActions($actions);
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');
    }

    /**
     * Print srcum road map block.
     *
     * @param  int    $productID
     * @param  int    $roadMapID
     * @access public
     * @return void
     */
    public function printScrumRoadMapBlock($productID = 0, $roadMapID = 0)
    {
        $this->session->set('releaseList',     $this->app->getURI(true), 'product');
        $this->session->set('productPlanList', $this->app->getURI(true), 'product');

        $products  = $this->loadModel('product')->getPairs('', $this->session->project);
        if(!is_numeric($productID)) $productID = key($products);

        $this->view->roadmaps  = $this->product->getRoadmap($productID, 0, 6);
        $this->view->productID = $productID;
        $this->view->roadMapID = $roadMapID;
        $this->view->products  = $products;
        $this->view->sync      = 1;

        if($_POST)
        {
            $this->view->sync = 0;
            $this->display('block', 'scrumroadmapblock');
        }
    }

    /**
     * Print srcum test block.
     *
     * @access public
     * @return void
     */
    public function printScrumTestBlock()
    {
        $this->session->set('testtaskList', $this->app->getURI(true), 'qa');
        $this->session->set('productList',  $this->app->getURI(true), 'product');
        $this->session->set('projectList',  $this->app->getURI(true), 'project');
        $this->session->set('buildList',    $this->app->getURI(true), 'execution');
        $this->app->loadLang('testtask');

        $count  = zget($this->params, 'count', 10);
        $status = isset($this->params->type)  ? $this->params->type : 'wait';

        $this->view->testtasks = $this->dao->select('t1.*,t2.name as productName,t3.name as buildName,t4.name as projectName')
            ->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->leftJoin(TABLE_BUILD)->alias('t3')->on('t1.build=t3.id')
            ->leftJoin(TABLE_PROJECT)->alias('t4')->on('t1.project=t4.id')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t5')->on('t1.project=t5.project')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.project')->eq($this->session->project)->fi()
            ->andWhere('t1.product = t5.product')
            ->beginIF($status != 'all')->andWhere('t1.status')->eq($status)->fi()
            ->orderBy('t1.id desc')
            ->limit($count)
            ->fetchAll();
    }

    /**
     * Print qa statistic block.
     *
     * @access public
     * @return void
     */
    public function printQaStatisticBlock()
    {
        if(!empty($this->params->type) and preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) die();

        $this->app->loadLang('bug');
        $status = isset($this->params->type)  ? $this->params->type : '';
        $count  = isset($this->params->count) ? (int)$this->params->count : 0;

        $projectID = $this->lang->navGroup->qa == 'project' ? $this->session->project : 0;
        $products  = $this->loadModel('product')->getOrderedProducts($status, $count, $projectID);
        if(empty($products))
        {
            $this->view->products = $products;
            return false;
        }

        $productIdList = array_keys($products);
        $today         = date(DT_DATE1);
        $yesterday     = date(DT_DATE1, strtotime('yesterday'));
        $testtasks     = $this->dao->select('*')->from(TABLE_TESTTASK)->where('product')->in($productIdList)->andWhere('project')->ne(0)->andWhere('deleted')->eq(0)->orderBy('id')->fetchAll('product');
        $bugs          = $this->dao->select("product, count(id) as total,
            count(assignedTo = '{$this->app->user->account}' or null) as assignedToMe,
            count(status != 'closed' or null) as unclosed,
            count((status != 'closed' and status != 'resolved') or null) as unresolved,
            count(confirmed = '0' or null) as unconfirmed,
            count((resolvedDate >= '$yesterday' and resolvedDate < '$today') or null) as yesterdayResolved,
            count((closedDate >= '$yesterday' and closedDate < '$today') or null) as yesterdayClosed")
            ->from(TABLE_BUG)
            ->where('product')->in($productIdList)
            ->andWhere('deleted')->eq(0)
            ->groupBy('product')
            ->fetchAll('product');

        $confirmedBugs = $this->dao->select('count(product) as product')->from(TABLE_ACTION)
            ->where('objectType')->eq('bug')
            ->andWhere('action')->eq('bugconfirmed')
            ->andWhere('date')->ge($yesterday)
            ->andWhere('date')->lt($today)
            ->groupBy('product')
            ->fetchPairs('product', 'product');

        foreach($products as $productID => $product)
        {
            $bug = isset($bugs[$productID]) ? $bugs[$productID] : '';
            $product->total              = empty($bug) ? 0 : $bug->total;
            $product->assignedToMe       = empty($bug) ? 0 : $bug->assignedToMe;
            $product->unclosed           = empty($bug) ? 0 : $bug->unclosed;
            $product->unresolved         = empty($bug) ? 0 : $bug->unresolved;
            $product->unconfirmed        = empty($bug) ? 0 : $bug->unconfirmed;
            $product->yesterdayResolved  = empty($bug) ? 0 : $bug->yesterdayResolved;
            $product->yesterdayClosed    = empty($bug) ? 0 : $bug->yesterdayClosed;
            $product->yesterdayConfirmed = empty($confirmedBugs[",$productID,"]) ? 0 : $confirmedBugs[",$productID,"];

            $product->assignedRate    = $product->total ? round($product->assignedToMe  / $product->total * 100, 2) : 0;
            $product->unresolvedRate  = $product->total ? round($product->unresolved    / $product->total * 100, 2) : 0;
            $product->unconfirmedRate = $product->total ? round($product->unconfirmed   / $product->total * 100, 2) : 0;
            $product->unclosedRate    = $product->total ? round($product->unclosed      / $product->total * 100, 2) : 0;
            $product->testtask        = isset($testtasks[$productID]) ? $testtasks[$productID] : '';
        }

        $this->view->products = $products;
    }

    /**
     * Print overview block.
     *
     * @access public
     * @return void
     */
    public function printOverviewBlock($module = 'product')
    {
        $func = 'print' . ucfirst($module) . 'OverviewBlock';
        $this->view->module = $module;
        $this->$func();
    }

    /**
     * Print product overview block.
     *
     * @access public
     * @return void
     */
    public function printProductOverviewBlock()
    {
        $normal = 0;
        $closed = 0;

        $products = $this->loadModel('product')->getList();
        foreach($products as $product)
        {
            if(!$this->product->checkPriv($product->id)) continue;

            if($product->status == 'normal') $normal++;
            if($product->status == 'closed') $closed++;
        }

        $total  = $normal + $closed;

        $this->view->total         = $total;
        $this->view->normal        = $normal;
        $this->view->closed        = $closed;
        $this->view->normalPercent = $total ? round(($normal / $total), 2) * 100 : 0;
    }

    /**
     * Print execution overview block.
     *
     * @access public
     * @return void
     */
    public function printExecutionOverviewBlock()
    {
        $projectID  = $this->view->block->module == 'my' ? 0 : (int)$this->session->project;
        $executions = $this->loadModel('execution')->getList($projectID);

        $total = 0;
        foreach($executions as $execution)
        {
            if(!isset($overview[$execution->status])) $overview[$execution->status] = 0;
            $overview[$execution->status]++;
            $total++;
        }

        $overviewPercent = array();
        foreach($this->lang->project->statusList as $statusKey => $statusName)
        {
            if(!isset($overview[$statusKey])) $overview[$statusKey] = 0;
            $overviewPercent[$statusKey] = $total ? round($overview[$statusKey] / $total, 2) * 100 . '%' : '0%';
        }

        $this->view->total           = $total;
        $this->view->overview        = $overview;
        $this->view->overviewPercent = $overviewPercent;
    }

    /**
     * Print qa overview block.
     *
     * @access public
     * @return void
     */
    public function printQaOverviewBlock()
    {
        $casePairs = $this->dao->select('lastRunResult, COUNT(*) AS count')->from(TABLE_CASE)
            ->where('1=1')
            ->beginIF($this->view->block->module != 'my' and $this->session->project)->andWhere('project')->eq((int)$this->session->project)->fi()
            ->groupBy('lastRunResult')
            ->fetchPairs();

        $total = array_sum($casePairs);

        $this->app->loadLang('testcase');
        foreach($this->lang->testcase->resultList as $result => $label)
        {
            if(!isset($casePairs[$result])) $casePairs[$result] = 0;
        }

        $casePercents = array();
        foreach($casePairs as $result => $count)
        {
            $casePercents[$result] = $total ? round($count / $total * 100, 2) : 0;
        }

        $this->view->total        = $total;
        $this->view->casePairs    = $casePairs;
        $this->view->casePercents = $casePercents;
    }

    /**
     * Print execution block.
     *
     * @access public
     * @return void
     */
    public function printExecutionBlock()
    {
        $this->app->loadClass('pager', $static = true);
        if(!empty($this->params->type) and preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) die();
        $count  = isset($this->params->count) ? (int)$this->params->count : 0;
        $status = isset($this->params->type)  ? $this->params->type : 'all';
        $pager  = pager::init(0, $count, 1);

        $projectID = $this->view->block->module == 'my' ? 0 : (int)$this->session->project;
        $this->view->executionStats = $this->loadModel('project')->getStats($projectID, $status, 0, 0, 30, 'id_asc', $pager);
    }

    /**
     * Print assign to me block.
     *
     * @access public
     * @return void
     */
    public function printAssignToMeBlock($longBlock = true)
    {
        if(common::hasPriv('todo',  'view')) $hasViewPriv['todo']  = true;
        if(common::hasPriv('task',  'view')) $hasViewPriv['task']  = true;
        if(common::hasPriv('bug',   'view')) $hasViewPriv['bug']   = true;
        if(common::hasPriv('risk',  'view') and isset($this->config->maxVersion)) $hasViewPriv['risk']  = true;
        if(common::hasPriv('issue', 'view') and isset($this->config->maxVersion)) $hasViewPriv['issue'] = true;
        if(common::hasPriv('story', 'view')) $hasViewPriv['story'] = true;

        $params = $this->get->param;
        $params = json_decode(base64_decode($params));
        $count  = array();

        if(isset($hasViewPriv['todo']))
        {
            $this->app->loadClass('date');
            $this->app->loadLang('todo');
            $stmt = $this->dao->select('*')->from(TABLE_TODO)
                ->where("(assignedTo = '{$this->app->user->account}' or (account='{$this->app->user->account}'))")
                ->andWhere('cycle')->eq(0)
                ->andWhere('deleted')->eq(0)
                ->andWhere('status')->eq('wait')
                ->orderBy('`date` desc');
            if(isset($params->todoNum)) $stmt->limit($params->todoNum);
            $todos = $stmt->fetchAll();

            foreach($todos as $key => $todo)
            {
                if($todo->status == 'done' and $todo->finishedBy == $this->app->user->account)
                {
                    unset($todos[$key]);
                    continue;
                }

                $todo->begin = date::formatTime($todo->begin);
                $todo->end   = date::formatTime($todo->end);
            }
            $count['todo'] = count($todos);
            $this->view->todos = $todos;
        }
        if(isset($hasViewPriv['task']))
        {
            $this->app->loadLang('task');
            $this->app->loadLang('execution');
            $stmt = $this->dao->select('*')->from(TABLE_TASK)
                ->where('assignedTo')->eq($this->app->user->account)
                ->andWhere('deleted')->eq('0')
                ->andWhere('status')->ne('closed')
                ->orderBy('id_desc');
            if(isset($params->taskNum)) $stmt->limit($params->taskNum);
            $tasks = $stmt->fetchAll();

            $count['task'] = count($tasks);
            $this->view->tasks = $tasks;
        }
        if(isset($hasViewPriv['bug']))
        {
            $this->app->loadLang('bug');
            $stmt = $this->dao->select('*')->from(TABLE_BUG)
                ->where('assignedTo')->eq($this->app->user->account)
                ->andWhere('deleted')->eq('0')
                ->andWhere('status')->ne('closed')
                ->orderBy('id_desc');
            if(isset($params->bugNum)) $stmt->limit($params->bugNum);
            $bugs = $stmt->fetchAll();

            $count['bug'] = count($bugs);
            $this->view->bugs = $bugs;
        }
        if(isset($hasViewPriv['risk']))
        {
            $this->app->loadLang('risk');
            $stmt = $this->dao->select('*')->from(TABLE_RISK)
                ->where('assignedTo')->eq($this->app->user->account)
                ->andWhere('deleted')->eq('0')
                ->andWhere('status')->ne('closed')
                ->orderBy('id_desc');
            if(isset($params->riskNum)) $stmt->limit($params->riskNum);
            $risks = $stmt->fetchAll();

            $count['risk'] = count($risks);
            $this->view->risks = $risks;
        }
        if(isset($hasViewPriv['issue']))
        {
            $this->app->loadLang('issue');
            $stmt = $this->dao->select('*')->from(TABLE_ISSUE)
                ->where('assignedTo')->eq($this->app->user->account)
                ->andWhere('deleted')->eq('0')
                ->andWhere('status')->ne('closed')
                ->orderBy('id_desc');
            if(isset($params->issueNum)) $stmt->limit($params->issueNum);
            $issues = $stmt->fetchAll();

            $count['issue'] = count($issues);
            $this->view->issues = $issues;
        }
        if(isset($hasViewPriv['story']))
        {
            $this->app->loadLang('story');
            $stmt = $this->dao->select('*')->from(TABLE_STORY)
                ->where('assignedTo')->eq($this->app->user->account)
                ->andWhere('deleted')->eq('0')
                ->andWhere('status')->ne('closed')
                ->orderBy('id_desc');
            if(isset($params->storyNum)) $stmt->limit($params->storyNum);
            $stories = $stmt->fetchAll();

            $count['story'] = count($stories);
            $this->view->stories = $stories;
        }

        $this->view->selfCall    = $this->selfCall;
        $this->view->hasViewPriv = $hasViewPriv;
        $this->view->count       = $count;
        $this->view->longBlock   = $longBlock;
        $this->display();
    }

    /**
     * Print recent project block.
     *
     * @access public
     * @return void
     */
    public function printRecentProjectBlock()
    {
        /* load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager(0, 3, 1);
        $this->view->projects = $this->loadModel('project')->getInfoList('all', 30, 'id_desc', $pager);
    }

    /**
     * Print project team block.
     *
     * @access public
     * @return void
     */
    public function printProjectTeamBlock()
    {
        $count   = isset($this->params->count)   ? $this->params->count   : 15;
        $status  = isset($this->params->type)    ? $this->params->type    : 'all';
        $orderBy = isset($this->params->orderBy) ? $this->params->orderBy : 'id_desc';

        /* Get projects. */
        $this->app->loadLang('task');
        $this->view->projects = $this->loadModel('project')->getOverviewList('byStatus', $status, $orderBy, $count);
    }

    /**
     * Print flow chart block
     * @access public
     * @return void
     */
    public function flowchart()
    {
        $this->display();
    }

    /**
     * Close block forever.
     *
     * @param  int    $blockID
     * @access public
     * @return void
     */
    public function close($blockID)
    {
        $block = $this->block->getByID($blockID);
        $closedBlock = isset($this->config->block->closed) ? $this->config->block->closed : '';
        $this->dao->delete()->from(TABLE_BLOCK)->where('source')->eq($block->source)->andWhere('block')->eq($block->block)->exec();
        $this->loadModel('setting')->setItem('system.block.closed', $closedBlock . ",{$block->source}|{$block->block}");
        die(js::reload('parent'));
    }

    /**
     * Ajax reset.
     *
     * @param  string $module
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function ajaxReset($module, $confirm = 'no')
    {
        if($confirm != 'yes') die(js::confirm($this->lang->block->confirmReset, inlink('ajaxReset', "module=$module&confirm=yes")));

        $this->dao->delete()->from(TABLE_BLOCK)->where('module')->eq($module)->andWhere('account')->eq($this->app->user->account)->exec();
        $this->dao->delete()->from(TABLE_CONFIG)->where('module')->eq($module)->andWhere('owner')->eq($this->app->user->account)->andWhere('`key`')->eq('blockInited')->exec();
        die(js::reload('parent'));
    }

    /**
     * Ajax for use new block.
     *
     * @param  string $module
     * @param  string $confirm
     * @access public
     * @return void
     */
    public function ajaxUseNew($module, $confirm = 'no')
    {
        if($confirm == 'yes')
        {
            $this->dao->delete()->from(TABLE_BLOCK)->where('module')->eq($module)->andWhere('account')->eq($this->app->user->account)->exec();
            $this->dao->delete()->from(TABLE_CONFIG)->where('module')->eq($module)->andWhere('owner')->eq($this->app->user->account)->andWhere('`key`')->eq('blockInited')->exec();
            die(js::reload('parent'));
        }
        elseif($confirm == 'no')
        {
            $this->loadModel('setting')->setItem("{$this->app->user->account}.$module.block.initVersion", $this->config->block->version);
        }
    }
}
