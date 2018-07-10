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

            if($this->config->global->flow == 'onlyTask' or $this->config->global->flow == 'onlyStory') unset($modules['qa']);
            if($this->config->global->flow == 'onlyTask' or $this->config->global->flow == 'onlyTest')  unset($modules['product']);
            if($this->config->global->flow == 'onlyStory' or $this->config->global->flow == 'onlyTest') unset($modules['project']);

            $closedBlock = isset($this->config->block->closed) ? $this->config->block->closed : '';
            if(strpos(",$closedBlock,", ",|assigntome,") === false) $modules['assigntome'] = $this->lang->block->assignToMe;
            if(strpos(",$closedBlock,", ",|dynamic,") === false) $modules['dynamic'] = $this->lang->block->dynamic;
            if(strpos(",$closedBlock,", ",|flowchart,") === false and $this->config->global->flow == 'full') $modules['flowchart'] = $this->lang->block->lblFlowchart;
            if(strpos(",$closedBlock,", ",|welcome,") === false and $this->config->global->flow == 'full') $modules['welcome'] = $this->lang->block->welcome;
            if(strpos(",$closedBlock,", ",|html,") === false) $modules['html'] = 'HTML';
            $modules = array('' => '') + $modules;

            $hiddenBlocks = $this->block->getHiddenBlocks();
            foreach($hiddenBlocks as $block) $modules['hiddenBlock' . $block->id] = $block->title;
            $this->view->modules = $modules;
        }
        elseif(isset($this->lang->block->moduleList[$module]))
        {
            $this->get->set('mode', 'getblocklist');
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
     * @access public
     * @return void
     */
    public function dashboard($module)
    {
        if($this->loadModel('user')->isLogon()) $this->session->set('blockModule', $module);
        $blocks = $this->block->getBlockList($module);
        $inited = empty($this->config->$module->common->blockInited) ? '' : $this->config->$module->common->blockInited;

        /* Init block when vist index first. */
        if(empty($blocks) and !$inited and !defined('TUTORIAL'))
        {
            if($this->block->initBlock($module)) die(js::reload());
        }

        $shortBlocks = $longBlocks = array();
        foreach($blocks as $key => $block)
        {
            if($this->config->global->flow == 'onlyStory' and $block->source != 'product' and $block->source != 'todo' and $block->block != 'dynamic') unset($blocks[$key]);
            if($this->config->global->flow == 'onlyTask' and $block->source != 'project' and $block->source != 'todo' and $block->block != 'dynamic') unset($blocks[$key]);
            if($this->config->global->flow == 'onlyTest' and $block->source != 'qa' and $block->source != 'todo' and $block->block != 'dynamic') unset($blocks[$key]);

            $block->params  = json_decode($block->params);
            $blockID = $block->block;
            $source  = empty($block->source) ? 'common' : $block->source;

            $block->blockLink = $this->createLink('block', 'printBlock', "id=$block->id&module=$block->module");
            $block->moreLink  = '';
            if(isset($this->lang->block->modules[$source]->moreLinkList->{$blockID}))
            {
                list($moduleName, $method, $vars) = explode('|', sprintf($this->lang->block->modules[$source]->moreLinkList->{$blockID}, isset($block->params->type) ? $block->params->type : ''));
                $block->moreLink = $this->createLink($moduleName, $method, $vars);
            }
            elseif($block->block == 'dynamic')
            {
                $block->moreLink = $this->createLink('company', 'dynamic');
            }

            $block->actionLink = '';
            if($block->block == 'overview')
            {
                if($module == 'product' && common::hasPriv('product', 'create')) $block->actionLink = html::a($this->createLink('product', 'create'), "<i class='icon icon-sm icon-plus'></i> " . $this->lang->product->create, '', "class='btn'");
                if($module == 'project' && common::hasPriv('project', 'create')) $block->actionLink = html::a($this->createLink('project', 'create'), "<i class='icon icon-sm icon-plus'></i> " . $this->lang->project->create, '', "class='btn'");
                if($module == 'qa'      && common::hasPriv('testcase', 'create'))
                {
                    $this->app->loadLang('testcase');
                    $block->actionLink = html::a($this->createLink('testcase', 'create', 'productID='), "<i class='icon icon-sm icon-plus'></i> " . $this->lang->testcase->create, '', "class='btn'");
                }
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
        $this->view->actions = $this->loadModel('action')->getDynamic('all', 'today');
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
        $this->view->bugs       = $data['bugs'];
        $this->view->stories    = $data['stories'];
        $this->view->projects   = $data['projects'];
        $this->view->products   = $data['products'];

        $this->view->delay['task']    = $data['delayTask']; 
        $this->view->delay['bug']     = $data['delayBug']; 
        $this->view->delay['project'] = $data['delayProject']; 

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
                $html = "<div class='panel-body'><div class='article-content'>" . htmlspecialchars_decode($block->params->html) .'</div></div>';
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
            $blocks = $this->block->getAvailableBlocks($module);
            if(!$this->selfCall)
            {
                echo $blocks;
                return true;
            }

            $blocks     = json_decode($blocks, true);
            $blockPairs = array('' => '') + $blocks;

            $block = $this->block->getByID($id);

            echo '<div class="form-group">';
            echo '<label for="moduleBlock" class="col-sm-3">' . $this->lang->block->lblBlock . '</label>';
            echo '<div class="col-sm-7">';
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
            if(!$this->selfCall)
            {
                $this->app->user = $this->dao->select('*')->from(TABLE_USER)->where('ranzhi')->eq($params->account)->fetch();
                if(empty($this->app->user))
                {
                    $this->app->user = new stdclass();
                    $this->app->user->account = 'guest';
                }
                $this->app->user->rights = $this->loadModel('user')->authorize($this->app->user->account);

                $sso = base64_decode($this->get->sso);
                $this->view->sso  = $sso;
                $this->view->sign = strpos($sso, '?') === false ? '?' : '&';
            }

            $block = $this->block->getByID($id);
            $this->view->longBlock = $this->block->isLongBlock($block);

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
        $limit = $this->viewType == 'json' ? 0 : (int)$this->params->num;
        $todos = $this->loadModel('todo')->getList('all', $this->app->user->account, 'wait, doing', $limit, $pager = null, $orderBy = 'date, begin');
        $uri   = $this->server->http_referer;
        $this->session->set('todoList', $uri);
        $this->session->set('bugList',  $uri);
        $this->session->set('taskList', $uri);

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
        $this->session->set('taskList',  $this->server->http_referer);
        $this->session->set('storyList', $this->server->http_referer);
        if(preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) die();
        $this->view->tasks = $this->loadModel('task')->getUserTasks($this->app->user->account, $this->params->type, $this->viewType == 'json' ? 0 : (int)$this->params->num, null, $this->params->orderBy);
    }

    /**
     * Print bug block.
     *
     * @access public
     * @return void
     */
    public function printBugBlock()
    {
        $this->session->set('bugList', $this->server->http_referer);
        if(preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) die();
        $this->view->bugs = $this->loadModel('bug')->getUserBugs($this->app->user->account, $this->params->type, $this->params->orderBy, $this->viewType == 'json' ? 0 : (int)$this->params->num);
    }

    /**
     * Print case block.
     *
     * @access public
     * @return void
     */
    public function printCaseBlock()
    {
        $this->session->set('caseList', $this->server->http_referer);
        $this->app->loadLang('testcase');
        $this->app->loadLang('testtask');

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
                ->orderBy($this->params->orderBy)
                ->beginIF($this->viewType != 'json')->limit((int)$this->params->num)->fi()
                ->fetchAll();
        }
        elseif($this->params->type == 'openedbyme')
        {
            $cases = $this->dao->findByOpenedBy($this->app->user->account)->from(TABLE_CASE)
                ->andWhere('deleted')->eq(0)
                ->orderBy($this->params->orderBy)
                ->beginIF($this->viewType != 'json')->limit((int)$this->params->num)->fi()
                ->fetchAll();
        }
        $this->view->cases    = $cases;
    }

    /**
     * Print testtask block.
     *
     * @access public
     * @return void
     */
    public function printTesttaskBlock()
    {
        $this->session->set('testtaskList', $this->server->http_referer);
        if(preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) die();
        $this->app->loadLang('testtask');
        $products = $this->loadModel('product')->getPairs();
        $this->view->testtasks = $this->dao->select('t1.*,t2.name as productName,t3.name as buildName,t4.name as projectName')->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->leftJoin(TABLE_BUILD)->alias('t3')->on('t1.build=t3.id')
            ->leftJoin(TABLE_PROJECT)->alias('t4')->on('t1.project=t4.id')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t5')->on('t1.project=t5.project')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.product')->in(array_keys($products))
            ->andWhere('t1.product = t5.product')
            ->beginIF($this->params->type != 'all')->andWhere('t1.status')->eq($this->params->type)->fi()
            ->orderBy('t1.id desc')
            ->beginIF($this->viewType != 'json')->limit((int)$this->params->num)->fi()
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
        $this->session->set('storyList', $this->server->http_referer);
        if(preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) die();
        $this->app->loadClass('pager', $static = true);
        $num     = isset($this->params->num) ? (int)$this->params->num : 0;
        $pager   = pager::init(0, $num , 1);
        $type    = isset($this->params->type) ? $this->params->type : 'assignedTo';
        $orderBy = isset($this->params->type) ? $this->params->orderBy : 'id_asc';
        $this->view->stories  = $this->loadModel('story')->getUserStories($this->app->user->account, $type, $orderBy, $this->viewType != 'json' ? $pager : '');
    }

    /**
     * Print plan block.
     *
     * @access public
     * @return void
     */
    public function printPlanBlock()
    {
        $this->session->set('productPlanList', $this->server->http_referer);
        $this->app->loadLang('productplan');
        $products = $this->loadModel('product')->getPairs();
        $this->view->plans = $this->dao->select('t1.*,t2.name as productName')->from(TABLE_PRODUCTPLAN)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.product')->in(array_keys($products))
            ->orderBy('t1.begin desc')
            ->beginIF($this->viewType != 'json')->limit((int)$this->params->num)->fi()
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
        $this->session->set('releaseList', $this->server->http_referer);
        $this->app->loadLang('release');
        $products = $this->loadModel('product')->getPairs();
        $this->view->releases = $this->dao->select('t1.*,t2.name as productName,t3.name as buildName')->from(TABLE_RELEASE)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->leftJoin(TABLE_BUILD)->alias('t3')->on('t1.build=t3.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.product')->in(array_keys($products))
            ->orderBy('t1.id desc')
            ->beginIF($this->viewType != 'json')->limit((int)$this->params->num)->fi()
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
        $this->session->set('buildList', $this->server->http_referer);
        $this->app->loadLang('build');
        $projects = $this->loadModel('project')->getPairs();
        $this->view->builds = $this->dao->select('t1.*, t2.name as productName')->from(TABLE_BUILD)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.project')->in(array_keys($projects))
            ->orderBy('t1.id desc')
            ->beginIF($this->viewType != 'json')->limit((int)$this->params->num)->fi()
            ->fetchAll();
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
        $num   = isset($this->params->num) ? (int)$this->params->num : 0;
        $type  = isset($this->params->type) ? $this->params->type : '';
        $pager = pager::init(0, $num , 1);

        $productStats  = $this->loadModel('product')->getStats('order_desc', $this->viewType != 'json' ? $pager : '', $type);
        $productIdList = array();
        foreach($productStats as $product) $productIdList[] = $product->id;

        $this->view->projects = $this->dao->select('t1.product,t2.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.product')->in($productIdList)
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
     * Print product statistic block.
     *
     * @access public
     * @return void
     */
    public function printProductStatisticBlock()
    {
        if(!empty($this->params->type) and preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) die();

        $status  = isset($this->params->type) ? $this->params->type : '';
        $orderBy = isset($this->params->orderBy) ? $this->params->orderBy : 'id_asc';

        /* Get products. */
        $products = $this->loadModel('product')->getList($status);
        foreach($products as $productID => $product)
        {
            if(!$this->product->checkPriv($product)) unset($products[$productID]);
        }
        $productIDList = array_keys($products);
        if(!$productIDList)
        {
            $this->view->products = $products;
            return false;
        }
        $products = $this->dao->select('*')->from(TABLE_PRODUCT)
            ->where('id')->in($productIDList)
            ->orderBy($orderBy)
            ->fetchAll('id');

        /* Get stories. */
        $stories = $this->dao->select('product, stage, COUNT(status) AS count')->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->andWhere('product')->in($productIDList)
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
            ->andWhere('product')->in($productIDList)
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

        /* Get projects. */
        $projects = $this->dao->select('t1.product, t2.status, t2.end')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.product')->in($productIDList)
            ->andWhere('t2.deleted')->eq(0)
            ->fetchGroup('product');
        foreach($projects as $product => $productProjects)
        {
            $doing = 0;
            $done  = 0;
            $delay = 0;

            foreach($productProjects as $project)
            {
                if($project->status == 'doing') $doing++;
                if($project->status == 'done' or $project->status == 'closed') $done++;
                if($project->status != 'done' && $project->status != 'closed' && $project->status != 'suspended' && $project->end < helper::today()) $delay++;
            }

            $project = array();
            $project['doing'] = $doing;
            $project['done']  = $done;
            $project['delay'] = $delay;

            $projects[$product] = $project;
        }

        /* Get releases. */
        $releases = $this->dao->select('product, status, COUNT(*) AS count')->from(TABLE_RELEASE)
            ->where('deleted')->eq(0)
            ->andWhere('product')->in($productIDList)
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
            ->andWhere('product')->in($productIDList)
            ->groupBy('product')
            ->fetchPairs();

        foreach($products as $productID => $product)
        {
            $product->stories     = isset($stories[$productID])      ? $stories[$productID]      : 0;
            $product->plans       = isset($plans[$productID])        ? $plans[$productID]        : 0;
            $product->projects    = isset($projects[$productID])     ? $projects[$productID]     : 0;
            $product->releases    = isset($releases[$productID])     ? $releases[$productID]     : 0;
            $product->lastRelease = isset($lastReleases[$productID]) ? $lastReleases[$productID] : 0;
        }

        $this->app->loadLang('story');
        $this->app->loadLang('productplan');
        $this->app->loadLang('project');
        $this->app->loadLang('release');

        $this->view->products = $products;
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

        $this->app->loadLang('task');
        $this->app->loadLang('story');
        $this->app->loadLang('bug');

        $status  = isset($this->params->type) ? $this->params->type : '';
        $orderBy = isset($this->params->orderBy) ? $this->params->orderBy : 'id_asc';
        $num     = isset($this->params->num)  ? (int)$this->params->num : 0;

        /* Get projects. */
        $projects = $this->loadModel('project')->getList($status);
        foreach($projects as $projectID => $project)
        {
            if(!$this->project->checkPriv($project)) unset($projects[$projectID]);
        }
        $projectIDList = array_keys($projects);
        if(!$projectIDList)
        {
            $this->view->projects = $projects;
            return false;
        }
        $projects = $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('id')->in($projectIDList)
            ->orderBy($orderBy)
            ->beginIF($this->viewType != 'json')->limit($num)->fi()
            ->fetchAll('id');
        $projectIDList = array_keys($projects);


        /* Get tasks. */
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $tasks = $this->dao->select("project, count(id) as totalTasks, count(status not in ('wait,doing,pause') or null) as undoneTasks, count(finishedDate like '{$yesterday}%' or null) as yesterdayFinished, sum(if(status != 'cancel', estimate, 0)) as totalEstimate, sum(if(status != 'cancel', consumed, 0)) as totalConsumed, sum(if(status != 'cancel', `left`, 0)) as totalLeft")->from(TABLE_TASK)
            ->where('project')->in($projectIDList)
            ->andWhere('deleted')->eq(0)
            ->andWhere('parent')->eq(0)
            ->groupBy('project')
            ->fetchAll('project');

        foreach($tasks as $projectID => $task)
        {
            foreach($task as $key => $value)
            {
                if($key == 'project') continue;
                $projects[$projectID]->$key = $value;
            }
        }

        /* Get stories. */
        $stories = $this->dao->select("t1.project, count(t2.status) as totalStories, count(t2.status != 'closed' or null) as unclosedStories, count(t2.stage = 'released' or null) as releasedStories")->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story = t2.id')
            ->where('t1.project')->in($projectIDList)
            ->andWhere('t2.deleted')->eq(0)
            ->groupBy('project')
            ->fetchAll('project');

        foreach($stories as $projectID => $story)
        {
            foreach($story as $key => $value)
            {
                if($key == 'project') continue;
                $projects[$projectID]->$key = $value;
            }
        }

        /* Get bugs. */
        $bugs = $this->dao->select("project, status, count(status) as totalBugs, count(status = 'active' or null) as activeBugs, count(resolvedDate like '{$yesterday}%' or null) as yesterdayResolved")->from(TABLE_BUG)
            ->where('project')->in($projectIDList)
            ->andWhere('deleted')->eq(0)
            ->groupBy('project')
            ->fetchAll('project');

        foreach($bugs as $projectID => $bug)
        {
            foreach($bug as $key => $value)
            {
                if($key == 'project') continue;
                $projects[$projectID]->$key = $value;
            }
        }

        foreach($projects as $project)
        {
            if(!isset($projects[$project->id]->totalTasks))
            {
                $projects[$project->id]->totalTasks        = 0;
                $projects[$project->id]->undoneTasks       = 0;
                $projects[$project->id]->yesterdayFinished = 0;
                $projects[$project->id]->totalEstimate     = 0;
                $projects[$project->id]->totalConsumed     = 0;
                $projects[$project->id]->totalLeft         = 0;
            }
            if(!isset($projects[$project->id]->totalBugs))
            {
                $projects[$project->id]->totalBugs         = 0;
                $projects[$project->id]->activeBugs        = 0;
                $projects[$project->id]->yesterdayResolved = 0;
            }
            if(!isset($projects[$project->id]->totalStories))
            {
                $projects[$project->id]->totalStories    = 0;
                $projects[$project->id]->unclosedStories = 0;
                $projects[$project->id]->releasedStories = 0;
            }

            $projects[$project->id]->progress      = ($project->totalConsumed || $project->totalLeft) ? round($project->totalConsumed / ($project->totalConsumed + $project->totalLeft), 2) * 100 : 0;
            $projects[$project->id]->taskProgress  = $project->totalTasks ? round(($project->totalTasks - $project->undoneTasks) / $project->totalTasks, 2) * 100 : 0;
            $projects[$project->id]->storyProgress = $project->totalStories ? round(($project->totalStories - $project->unclosedStories) / $project->totalStories, 2) * 100 : 0;
            $projects[$project->id]->bugProgress   = $project->totalBugs ? round(($project->totalBugs - $project->activeBugs) / $project->totalBugs, 2) * 100 : 0;
        }

        $this->view->projects = $projects;
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

        $status  = isset($this->params->type) ? $this->params->type : '';
        $orderBy = isset($this->params->orderBy) ? $this->params->orderBy : 'id_asc';

        /* Get products. */
        $products = $this->loadModel('product')->getList($status);
        foreach($products as $productID => $product)
        {
            if(!$this->product->checkPriv($product)) unset($products[$productID]);
        }
        $productIDList = array_keys($products);
        if(!$productIDList)
        {
            $this->view->products = $products;
            return false;
        }
        $products = $this->dao->select('*')->from(TABLE_PRODUCT)
            ->where('id')->in($productIDList)
            ->orderBy($orderBy)
            ->fetchAll('id');

        $testedBuilds = $this->dao->select('build')->from(TABLE_TESTTASK)->where('product')->in(array_keys($products))->fetchPairs();
        $builds       = $this->dao->select('id, product, name, bugs')->from(TABLE_BUILD)->where('id')->in($testedBuilds)->fetchGroup('product', 'id');
        $openedBugs   = $this->dao->select('id, openedBuild')->from(TABLE_BUG)->where('openedBuild')->in($testedBuilds)->fetchGroup('openedBuild', 'id');

        /* Get bugs. */
        $bugIDList = array();
        foreach($builds as $product => $productBuilds)
        {
            foreach($productBuilds as $buildID => $build)
            {
                /* If don't clone $build, the exploded bugs of build will be stored in dao::$cache and it occurs an error when the qa statistic block be loaded twice. */
                $build = clone $build;
                $build->bugs = explode(',', trim($build->bugs, ','));
                foreach($build->bugs as $bugID) $bugIDList[$bugID] = $bugID;

                $builds[$product][$buildID] = $build;
            }
        }
        foreach($openedBugs as $buildBugs)
        {
            foreach($buildBugs as $bugID => $bug) $bugIDList[$bugID] = $bugID;
        }

        $today     = date(DT_DATE1);
        $yesterday = date(DT_DATE1, strtotime('yesterday'));

        $bugs = $this->loadModel('bug')->getByList($bugIDList);
        $confirmedBugs = $this->dao->select('objectID')->from(TABLE_ACTION)
            ->where('objectType')->eq('bug')
            ->andWhere('action')->eq('bugconfirmed')
            ->andWhere('date')->ge($yesterday)
            ->andWhere('date')->lt($today)
            ->fetchPairs();

        foreach($builds as $product => $productBuilds)
        {
            foreach($productBuilds as $buildID => $build)
            {
                $build->total              = 0;
                $build->assignedToMe       = 0;
                $build->unresolved         = 0;
                $build->unconfirmed        = 0;
                $build->unclosed           = 0;
                $build->yesterdayResolved  = 0;
                $build->yesterdayConfirmed = 0;
                $build->yesterdayClosed    = 0;

                $buildOpenedBugs = zget($openedBugs, $buildID, array());
                $build->bugs     = array_flip(array_merge(array_flip($build->bugs), array_flip(array_keys($buildOpenedBugs))));
                foreach($build->bugs as $key => $bugID)
                {
                    if(!isset($bugs[$bugID])) continue;

                    $bug = $bugs[$bugID];

                    if($bug->assignedTo = $this->app->user->account) $build->assignedToMe++;

                    if($bug->status != 'closed')
                    {
                        $build->unclosed++;

                        if($bug->status != 'resoloved')
                        {
                            $build->unresolved++;

                            if($bug->status != 'confirmed') $build->unconfirmed++;
                        }
                    }

                    if($bug->resolvedDate >= $yesterday && $bug->resolvedDate < $today) $build->yesterdayResolved++;
                    if($bug->closedDate   >= $yesterday && $bug->closedDate   < $today) $build->yesterdayClosed++;
                    if(isset($confirmedBugs[$bugID])) $yesterdayConfirmed++;

                    $build->total++;
                }

                $build->assignedRate    = $build->total ? round($build->assignedToMe  / $build->total * 100, 2) : 0;
                $build->unresolvedRate  = $build->total ? round($build->unresolved  / $build->total * 100, 2) : 0;
                $build->unconfirmedRate = $build->total ? round($build->unconfirmed / $build->total * 100, 2) : 0;
                $build->unclosedRate    = $build->total ? round($build->unclosed    / $build->total * 100, 2) : 0;
            }
        }

        foreach($products as $product) $product->builds = zget($builds, $product->id, array());

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
            if(!$this->product->checkPriv($product)) continue;

            if($product->status == 'normal') $normal++;
            if($product->status == 'closed') $closed++;
        }

        $this->view->total  = $normal + $closed;
        $this->view->normal = $normal;
        $this->view->closed = $closed;
    }

    /**
     * Print project overview block.
     *
     * @access public
     * @return void
     */
    public function printProjectOverviewBlock()
    {
        $projects = $this->loadModel('project')->getList();

        $total = 0;
        foreach($projects as $project)
        {
            if(!$this->project->checkPriv($project)) continue;

            if(!isset($overview[$project->status])) $overview[$project->status] = 0;
            $overview[$project->status]++;
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
        $casePairs = $this->dao->select('lastRunResult, COUNT(*) AS count')->from(TABLE_CASE)->groupBy('lastRunResult')->fetchPairs();
        $total     = array_sum($casePairs);

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
     * Print project block.
     *
     * @access public
     * @return void
     */
    public function printProjectBlock()
    {
        $this->app->loadClass('pager', $static = true);
        if(!empty($this->params->type) and preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) die();
        $num   = isset($this->params->num) ? (int)$this->params->num : 0;
        $type  = isset($this->params->type) ? $this->params->type : 'all';
        $pager = pager::init(0, $num, 1);
        $this->view->projectStats = $this->loadModel('project')->getProjectStats($type, $productID = 0, $branch = 0, $itemCounts = 30, $orderBy = 'order_desc', $this->viewType != 'json' ? $pager : '');
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

        $params = $this->get->param;
        $params = json_decode(base64_decode($params));

        if(isset($hasViewPriv['todo']))
        {
            $this->app->loadClass('date');
            $this->app->loadLang('todo');
            $stmt = $this->dao->select('*')->from(TABLE_TODO)
                ->where("(assignedTo = '{$this->app->user->account}' or (assignedTo = '' and account='{$this->app->user->account}'))")
                ->andWhere('cycle')->eq(0)
                ->orderBy('`date`');
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
            $this->view->todos = $todos;
        }
        if(isset($hasViewPriv['task']))
        {
            $this->app->loadLang('task');
            $stmt = $this->dao->select('*')->from(TABLE_TASK)
                ->where('assignedTo')->eq($this->app->user->account)
                ->andWhere('deleted')->eq('0')
                ->andWhere('status')->ne('closed')
                ->orderBy('id_desc');
            if(isset($params->taskNum)) $stmt->limit($params->taskNum);
            $tasks = $stmt->fetchAll();

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

            $this->view->bugs = $bugs;
        }

        $this->view->hasViewPriv = $hasViewPriv;
        $this->view->longBlock   = $longBlock;
        $this->display();
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
