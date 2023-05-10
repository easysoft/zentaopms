<?php
class blockZen extends block
{
    /**
     * 添加或编辑区块时获取可使用的模块选项。
     * Get module options when adding or editing blocks.
     *
     * @param  string    $dashboard
     * @access protected
     * @return string[]
     */
    protected function getAvailableModules(string $dashboard): array
    {
        /* 只有在我的地盘仪表盘中添加区块才会选择对应模块，否则直接使用对应仪表盘所在的模块。*/
        /* Only when adding blocks to my dashboard can I select the corresponding module, otherwise I will directly use the module where the corresponding dashboard is located.*/
        if($dashboard != 'my') return array();

        $modules = $this->lang->block->moduleList;

        /* Unable to display the doc module on my dashboard. */
        unset($modules['doc']);

        if($this->config->global->flow != 'full') unset($modules['guide']);
        if($this->config->vision != 'rnd')        unset($modules['contribute']);

        /* 从配置项中取出不同模块的首页对应的控制器方法。*/
        /* Retrieve the controller method corresponding to the homepage of different modules from the configuration item.*/
        list($programModule, $programMethod)     = explode('-', $this->config->programLink);
        list($productModule, $productMethod)     = explode('-', $this->config->productLink);
        list($projectModule, $projectMethod)     = explode('-', $this->config->projectLink);
        list($executionModule, $executionMethod) = explode('-', $this->config->executionLink);

        $closedBlock = isset($this->config->block->closed) ? $this->config->block->closed : '';
        foreach($modules as $moduleKey => $moduleName)
        {
            if($moduleKey == 'todo') continue;
            /* Determine if the user has permission for the current module. */
            if(in_array($moduleKey, $this->app->user->rights['acls'])) unset($modules[$moduleKey]);

            $method = 'index';
            if($moduleKey == 'program')   $method = $programMethod;
            if($moduleKey == 'product')   $method = $productMethod;
            if($moduleKey == 'project')   $method = $projectMethod;
            if($moduleKey == 'execution') $method = $executionMethod;

            /* After obtaining module permissions, it is necessary to verify whether there is permission for the module homepage. */
            if(!common::hasPriv($moduleKey, $method)) unset($modules[$moduleKey]);

            /* 被永久关闭的区块删除对应选项。 */
            /* Delete corresponding options for blocks that have been permanently closed. */
            if(strpos(",$closedBlock,", ",$moduleKey|$moduleKey,") !== false) unset($modules[$moduleKey]);
        }

        return array('' => '') + $modules;
    }

    /**
     * 添加或编辑区块时获取可使用的区块选项。
     * Get block options when adding or editing blocks.
     *
     * @param  string        $dashboard
     * @param  string        $module
     * @access protected
     * @return string[]|true
     */
    protected function getAvailableCodes(string $dashboard, string $module): array|bool
    {
        if($this->isExternalCall())
        {
            $lang = str_replace('_', '-', $this->get->lang);
            $this->app->setClientLang($lang);
            $this->app->loadLang('common');
            $this->app->loadLang('block');

            if(!$this->block->checkAPI($this->get->hash)) return array();
        }

        if($dashboard == 'my')
        {
            if($module && isset($this->lang->block->modules[$module]))
            {
                $blocks = $this->lang->block->modules[$module]->availableBlocks;
            }
            else
            {
                $blocks = array();
            }
        }
        else
        {
            if($dashboard && isset($this->lang->block->modules[$dashboard]))
            {
                $blocks = $this->lang->block->modules[$dashboard]->availableBlocks;
            }
            else
            {
                $blocks = $this->lang->block->availableBlocks;
            }
        }

        if(isset($this->config->block->closed))
        {
            foreach($blocks as $blockKey => $blockName)
            {
                if(strpos(",{$this->config->block->closed},", ",{$module}|{$blockKey},") !== false) unset($blocks[$blockKey]);
            }
        }

        if($this->isExternalCall())
        {
            echo json_encode($blocks);
            return true;
        }

        return !empty($blocks) ? $blocks : array();
    }

    /**
     * 添加或编辑区块时获取其他表单项。
     * Get other form items when adding or editing blocks.
     *
     * @param  string    $module
     * @param  string    $code
     * @access protected
     * @return array
     */
    protected function getAvailableParams(string $module = '', string $code = ''): array
    {
        if($code == 'todo' || $code == 'list' || $module == 'assigntome')
        {
            $code = $module;
        }
        elseif($code == 'statistic')
        {
            $code = $module . $code;
        }

        $params = zget($this->config->block->params, $code, '');
        $params = json_decode(json_encode($params), true);

        return !empty($params) ? $params : array();
    }

    /**
     * 处理每个区块以渲染 UI。
     * Process each block for render UI.
     *
     * @param  array     $blocks
     * @param  int       $projectID
     * @access protected
     * @return array
     */
    protected function processBlockForRender(array $blocks, int $projectID): array
    {
        /* 根据用户的权限，和当前系统开启的权限 处理区块列表。*/
        $acls = $this->app->user->rights['acls'];
        foreach($blocks as $key => $block)
        {
            /* 将没有开启功能区块过虑。 */
            if($block->code == 'waterfallrisk' && !helper::hasFeature('waterfall_risk'))   continue;
            if($block->code == 'waterfallissue' && !helper::hasFeature('waterfall_issue')) continue;
            if($block->code == 'scrumrisk' && !helper::hasFeature('scrum_risk'))           continue;
            if($block->code == 'scrumissue' && !helper::hasFeature('scrum_issue'))         continue;

            /* 将没有视图权限的区块过滤。 */
            if(!empty($block->module) && $block->module != 'todo' && !empty($acls['views']) && !isset($acls['views'][$block->module]))
            {
                unset($blocks[$key]);
                continue;
            }

            /* 处理 params 信息中  count 的值，当没有  count 字段时 ，将 num 字段赋值给 count。 */
            $block->params = json_decode($block->params);
            if(isset($block->params->num) && !isset($block->params->count)) $block->params->count = $block->params->num;

            /* 生成更多链接。 */
            $this->createMoreLink($block, $projectID);
        }
        return $blocks;
    }

    /**
     * 补全区块的加载链接。
     * Get the more link of the block.
     *
     * @param  object    $block
     * @param  int       $projectID
     * @access protected
     * @return object
     */
    private function createMoreLink(object $block, int $projectID): object
    {
        $module = empty($block->module) ? 'common' : $block->module;

        $block->blockLink = $this->createLink('block', 'printBlock', "id=$block->id&module=$block->module");
        $block->moreLink  = '';
        if(isset($this->config->block->modules[$module]->moreLinkList->{$block->code}))
        {
            list($moduleName, $method, $vars) = explode('|', sprintf($this->config->block->modules[$module]->moreLinkList->{$block->code}, isset($block->params->type) ? $block->params->type : ''));

            /* The list assigned to me jumps to the work page when click more button. */
            $block->moreLink = $this->createLink($moduleName, $method, $vars);
            if($moduleName == 'my' && strpos($this->config->block->workMethods, $method) !== false)
            {
                $block->moreLink = $this->createLink($moduleName, 'work', 'mode=' . $method . '&' . $vars);
            }
            elseif($moduleName == 'project' && $method == 'dynamic')
            {
                $block->moreLink = $this->createLink('project', 'dynamic', "projectID=$projectID&type=all");
            }
            elseif($moduleName == 'project' && $method == 'execution')
            {
                $block->moreLink = $this->createLink('project', 'execution', "status=all&projectID=$projectID");
            }
            elseif($moduleName == 'project' && $method == 'testtask')
            {
                $block->moreLink = $this->createLink('project', 'testtask', "projectID=$projectID");
            }
            elseif($moduleName == 'testtask' && $method == 'browse')
            {
                $block->moreLink = $this->createLink('testtask', 'browse', 'productID=0&branch=0&type=all,totalStatus');
            }
        }
        elseif($block->code == 'dynamic')
        {
            $block->moreLink = $this->createLink('company', 'dynamic');
        }
        return $block;
    }

    /**
     * 将区块数组拆分为短区块数组和长区块数组。
     * Split blocks array into short blocks and long blocks.
     *
     * @param  array     $blocks
     * @access protected
     * @return array
     */
    protected function splitBlocksByLen(array $blocks): array
    {
        $shortBlocks = $longBlocks = array();
        foreach($blocks as $key => $block)
        {
            if($this->block->isLongBlock($block))
            {
                $longBlocks[$key] = $block;
            }
            else
            {
                $shortBlocks[$key] = $block;
            }
        }

        return array($shortBlocks, $longBlocks);
    }

    /**
     * 生成 HTML 区块。
     * Generate HTML block.
     *
     * @param  object    $block
     * @access protected
     * @return string
     */
    protected function generateHtmlBlock(object $block): string
    {
        if(empty($block->params->html))
        {
            return "<div class='empty-tip'>" . $this->lang->block->emptyTip . '</div>';
        }

        return "<div class='panel-body'><div class='article-content'>" . $block->params->html . '</div></div>';
    }

    /**
     * 根据来源生成默认区块。
     * Generate default block by source.
     *
     * @param  object    $block
     * @access protected
     * @return string
     */
    protected function generateDefaultBlockBySource(object $block): string
    {
        $this->get->set('mode', 'getblockdata');
        $this->get->set('blockTitle', $block->title);
        $this->get->set('module', $block->module);
        $this->get->set('blockid', $block->code);
        $this->get->set('param', base64_encode(json_encode($block->params)));

        return $this->fetch('block', 'main', "module={$block->module}&id={$block->id}");
    }

    /**
     * 生成指派给我的区块。
     * Generate assign to me block.
     *
     * @param  object    $block
     * @access protected
     * @return string
     */
    protected function generateAssignToMeBlock(object $block): string
    {
        $this->get->set('param', base64_encode(json_encode($block->params)));

        return $this->fetch('block', 'printAssignToMeBlock', 'longBlock=' . $this->block->isLongBlock($block));
    }

    /**
     * 去掉待定和已暂停的任务。
     * Remove undetermined and suspended tasks.
     *
     * @param  array     $todos
     * @access protected
     * @return array
     */
    protected function unsetTodos(array $todos): array
    {
        $suspendedTasks = $this->loadModel('task')->getUserSuspendedTasks($this->app->user->account);
        foreach($todos as $key => $todo)
        {
            /* '2030-01-01' means undetermined */
            if($todo->date == '2030-01-01' || ($todo->type == 'task' && isset($suspendedTasks[$todo->idvalue])))
            {
                unset($todos[$key]);
            }
        }
        return $todos;
    }

    /**
     * latest dynamic.
     *
     * @access protected
     * @return void
     */
    protected function printDynamicBlock()
    {
        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager(0, 30, 1);

        $this->view->actions = $this->loadModel('action')->getDynamic('all', 'today', 'date_desc', $pager);
        $this->view->users   = $this->loadModel('user')->getPairs('nodeleted|noletter|all');
    }

    /**
     * Welcome block.
     *
     * @access protected
     * @return void
     */
    protected function printWelcomeBlock()
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
    }

    /**
     * Print contribute block.
     *
     * @access protected
     * @return void
     */
    protected function printContributeBlock()
    {
        $this->view->data = $this->loadModel('user')->getPersonalData();
    }

    /**
     * Print todo block.
     *
     * @access protected
     * @return void
     */
    protected function printTodoListBlock()
    {
        $limit = ($this->viewType == 'json' || !isset($this->params->count)) ? 0 : (int)$this->params->count;
        $todos = $this->loadModel('todo')->getList('all', $this->app->user->account, 'wait, doing', $limit, null, 'date, begin');
        $uri   = $this->createLink('my', 'index');

        $this->session->set('todoList',     $uri, 'my');
        $this->session->set('bugList',      $uri, 'qa');
        $this->session->set('taskList',     $uri, 'execution');
        $this->session->set('storyList',    $uri, 'product');
        $this->session->set('testtaskList', $uri, 'qa');

        $todos = $this->unsetTodos($todos);

        $this->view->todos = $todos;
    }

    /**
     * Print task block.
     *
     * @access protected
     * @return void
     */
    protected function printTaskBlock()
    {
        $this->session->set('taskList',  $this->createLink('my', 'index'), 'execution');
        if(preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) return;

        $account = $this->app->user->account;
        $type    = $this->params->type;

        $this->app->loadLang('execution');
        $this->view->tasks = $this->loadModel('task')->getUserTasks($account, $type, $this->viewType == 'json' ? 0 : (int)$this->params->count, null, $this->params->orderBy);
    }

    /**
     * Print bug block.
     *
     * @access protected
     * @return void
     */
    protected function printBugBlock()
    {
        $this->session->set('bugList', $this->createLink('my', 'index'), 'qa');
        if(preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) return;

        $projectID = $this->lang->navGroup->qa  == 'project' ? $this->session->project : 0;
        $projectID = $this->view->block->module == 'my' ? 0 : $projectID;
        $this->view->bugs = $this->loadModel('bug')->getUserBugs($this->app->user->account, $this->params->type, $this->params->orderBy, $this->viewType == 'json' ? 0 : (int)$this->params->count, null, $projectID);
    }

    /**
     * Print case block.
     *
     * @access protected
     * @return void
     */
    protected function printCaseBlock()
    {
        $this->session->set('caseList', $this->createLink('my', 'index'), 'qa');
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
     * @access protected
     * @return void
     */
    protected function printTesttaskBlock()
    {
        $this->app->loadLang('testtask');

        $uri = $this->createLink('my', 'index');
        $this->session->set('productList',  $uri, 'product');
        $this->session->set('testtaskList', $uri, 'qa');
        $this->session->set('buildList',    $uri, 'execution');
        if(preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) return;

        $this->view->projects  = $this->loadModel('project')->getPairsByProgram();
        $this->view->testtasks = $this->dao->select('t1.*,t2.name as productName,t2.shadow,t3.name as buildName,t4.name as projectName')->from(TABLE_TESTTASK)->alias('t1')
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
     * @params object     $block
     * @access protected
     * @return void
     */
    protected function printStoryBlock($block) :void
    {
        $this->session->set('storyList', $this->createLink('my', 'index'), 'product');
        if(preg_match('/[^a-zA-Z0-9_]/', $block->params->type)) return;

        $this->app->loadClass('pager', true);
        $count   = isset($block->params->count) ? (int)$block->params->count : 0;
        $pager   = pager::init(0, $count , 1);
        $type    = isset($block->params->type) ? $block->params->type : 'assignedTo';
        $orderBy = isset($block->params->type) ? $block->params->orderBy : 'id_asc';

        $this->view->stories = $this->loadModel('story')->getUserStories($this->app->user->account, $type, $orderBy, $this->viewType != 'json' ? $pager : '', 'story');
    }

    /**
     * Print plan block.
     *
     * @param  object    $block
     * @access protected
     * @return bool
     */
    protected function printPlanBlock(object $block): bool
    {
        $uri = $this->createLink('my', 'index');
        $this->session->set('productList', $uri, 'product');
        $this->session->set('productPlanList', $uri, 'product');

        $this->app->loadLang('productplan');
        $this->view->plans = $this->dao->select('t1.*,t2.name as productName')->from(TABLE_PRODUCTPLAN)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t2.shadow')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('t1.product')->in($this->app->user->view->products)->fi()
            ->orderBy('t1.begin desc')
            ->beginIF($this->viewType != 'json')->limit($block->params->count)->fi()
            ->fetchAll();

        return !dao::getError();
    }

    /**
     * 打印产品发布列表区块数据。
     * Print releases block.
     *
     * @param  object    $block
     * @access protected
     * @return void
     */
    protected function printReleaseBlock($block)
    {
        $uri = $this->createLink('my', 'index');
        $this->session->set('releaseList', $uri, 'product');
        $this->session->set('buildList', $uri, 'execution');

        $this->app->loadLang('release');
        $this->view->releases = $this->dao->select('t1.*,t2.name as productName,t3.name as buildName')->from(TABLE_RELEASE)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->leftJoin(TABLE_BUILD)->alias('t3')->on('t1.build=t3.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t2.shadow')->eq(0)
            ->beginIF($block->module != 'my' && $this->session->project)->andWhere('t1.project')->eq((int)$this->session->project)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t1.product')->in($this->app->user->view->products)->fi()
            ->orderBy('t1.id desc')
            ->beginIF($this->viewType != 'json')->limit((int)$block->params->count)->fi()
            ->fetchAll();
    }

    /**
     * Print Build block.
     *
     * @access protected
     * @return void
     */
    protected function printBuildBlock()
    {
        $this->session->set('buildList', $this->createLink('my', 'index'), 'execution');
        $this->app->loadLang('build');

        $builds = $this->dao->select('t1.*, t2.name AS productName, t2.shadow, t3.name AS projectName')->from(TABLE_BUILD)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t1.project=t3.id')
            ->where('t1.deleted')->eq('0')
            ->beginIF(!$this->app->user->admin)->andWhere('t1.execution')->in($this->app->user->view->sprints)->fi()
            ->beginIF($this->view->block->module != 'my' && $this->session->project)->andWhere('t1.project')->eq((int)$this->session->project)->fi()
            ->orderBy('t1.id desc')
            ->beginIF($this->viewType != 'json')->limit((int)$this->params->count)->fi()
            ->fetchAll();
        $this->view->builds = $builds;
    }

    /**
     * Print project block.
     *
     * @access protected
     * @return void
     */
    protected function printProjectBlock()
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
     * @access protected
     * @return void
     */
    protected function printProductListBlock()
    {
        $this->app->loadClass('pager', $static = true);
        if(!empty($this->params->type) and preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) return;
        $count = isset($this->params->count) ? (int)$this->params->count : 0; 
        $type  = isset($this->params->type) ? $this->params->type : '';
        $pager = pager::init(0, $count , 1);

        $productStats  = $this->loadModel('product')->getStats('order_desc', $this->viewType != 'json' ? $pager : '', $type);
        $productIdList = array();
        foreach($productStats as $product) $productIdList[] = $product->id;

        $this->app->loadLang('project');
        $executions = $this->dao->select('t1.product,t2.id,t2.project,t2.name,t2.multiple')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.product')->in($productIdList)
            ->andWhere('t2.type')->in('stage,sprint')
            ->andWhere('t2.deleted')->eq(0)
            ->orderBy('t1.project')
            ->fetchAll('product');

        $executionPairs = array();
        $noMultiples    = array();
        foreach($executions as $execution)
        {    
            if(empty($execution->multiple)) $noMultiples[$execution->product] = $execution->project;
            $executionPairs[$execution->product] = $execution->name;
        }
        if($noMultiples)
        {
            $noMultipleProjects = $this->dao->select('id,name')->from(TABLE_PROJECT)->where('id')->in($noMultiples)->fetchPairs('id', 'name');
            foreach($noMultiples as $productID => $projectID)
            {
                if(isset($noMultipleProjects[$projectID])) $executionPairs[$productID] = $noMultipleProjects[$projectID] . "({$this->lang->project->disableExecution})";
            }
        }

        $this->view->executions   = $executionPairs;
        $this->view->productStats = $productStats;
    }

    /**
     * Print project statistic block.
     *
     * @access protected
     * @return void
     */
    protected function printProjectStatisticBlock()
    {
        if(!empty($this->params->type) && preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) return;

        /* Load models and langs. */
        $this->loadModel('project');
        $this->loadModel('weekly');
        $this->loadModel('execution');
        $this->app->loadLang('task');
        $this->app->loadLang('story');
        $this->app->loadLang('bug');

        /* Set project status and count. */
        $status = isset($this->params->type)  ? $this->params->type       : 'all';
        $count  = isset($this->params->count) ? (int)$this->params->count : 15;

        /* Get projects. */
        $excludedModel = $this->config->edition == 'max' ? '' : 'waterfall';
        $projects      = $this->project->getOverviewList('byStatus', $status, 'order_asc', $count, $excludedModel);
        if(empty($projects))
        {
            $this->view->projects = $projects;
            return false;
        }

        $today  = helper::today();
        $monday = date('Ymd', strtotime($this->loadModel('weekly')->getThisMonday($today)));
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
            if(in_array($project->model, array('scrum', 'kanban', 'agileplus')))
            {
                $this->app->loadClass('pager', true);
                $pager = pager::init(0, 3, 1);
                $project->progress   = $project->allStories == 0 ? 0 : round($project->doneStories / $project->allStories, 3) * 100;
                $project->executions = $this->execution->getStatData($projectID, 'all', 0, 0, false, '', 'id_desc', $pager);
            }
            elseif(in_array($project->model, array('waterfall', 'waterfallplus')))
            {
                $begin   = $project->begin;
                $weeks   = $this->weekly->getWeekPairs($begin);
                $current = zget($weeks, $monday, '');
                $current = substr($current, 0, -11) . substr($current, -6);

                $PVEV = $this->weekly->getPVEV($projectID, $today);
                $project->pv = $PVEV['PV'];
                $project->ev = $PVEV['EV'];
                $project->ac = $this->weekly->getAC($projectID, $today);
                $project->sv = $this->weekly->getSV($project->ev, $project->pv);
                $project->cv = $this->weekly->getCV($project->ev, $project->ac);

                $progress = 0;
                if(isset($tasks[$projectID]) && ($tasks[$projectID]->totalConsumed + $tasks[$projectID]->totalLeft))
                {
                    $progress = round($tasks[$projectID]->totalConsumed / ($tasks[$projectID]->totalConsumed + $tasks[$projectID]->totalLeft), 3) * 100;
                }

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
     * @param  object $block
     * @access protected
     * @return void
     */
    protected function printProductStatisticBlock($block)
    {
        $status = isset($block->params->type)  ? $block->params->type  : '';
        $count  = isset($block->params->count) ? $block->params->count : '';

        $products      = $this->loadModel('product')->getOrderedProducts($status, (int)$count);
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
            ->andWhere('type')->eq('story')
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
     * @access protected
     * @return void
     */
    protected function printExecutionStatisticBlock()
    {
        if(!empty($this->params->type) && preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) return;

        $this->app->loadLang('task');
        $this->app->loadLang('story');
        $this->app->loadLang('bug');

        $status  = isset($this->params->type)  ? $this->params->type : 'undone';
        $count   = isset($this->params->count) ? (int)$this->params->count : 0;

        /* Get projects. */
        $projectID  = $this->view->block->module == 'my' ? 0 : (int)$this->session->project;
        $executions = $this->loadModel('execution')->getOrderedExecutions($projectID, $status, $count, 'skipparent');
        if(empty($executions))
        {
            $this->view->executions = $executions;
            return false;
        }

        $executionIdList = array_keys($executions);

        /* Get tasks. Fix bug #2918.*/
        $yesterday  = date('Y-m-d', strtotime('-1 day'));
        $taskGroups = $this->dao->select('id,parent,execution,status,finishedDate,estimate,consumed,`left`')->from(TABLE_TASK)
            ->where('execution')->in($executionIdList)
            ->andWhere('deleted')->eq(0)
            ->fetchGroup('execution', 'id');

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
                if(strpos('wait|doing|pause|cancel', $task->status) !== false) $undoneTasks ++;
                if(strpos($task->finishedDate, $yesterday) !== false) $yesterdayFinished ++;

                if($task->parent == '-1') continue;

                $totalConsumed += $task->consumed;
                $totalEstimate += $task->estimate;
                if($task->status != 'cancel' && $task->status != 'closed') $totalLeft += $task->left;
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
        $bugs = $this->dao->select("execution, count(status) as totalBugs, count(status = 'active' or null) as activeBugs, count(resolvedDate like '{$yesterday}%' or null) as yesterdayResolved")->from(TABLE_BUG)
            ->where('execution')->in($executionIdList)
            ->andWhere('deleted')->eq(0)
            ->groupBy('execution')
            ->fetchAll('execution');

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
     * @access protected
     * @return void
     */
    protected function printWaterfallReportBlock()
    {
        $this->app->loadLang('programplan');
        $project = $this->loadModel('project')->getByID($this->session->project);
        $today   = helper::today();
        $date    = date('Ymd', strtotime('this week Monday'));
        $begin   = $project->begin;
        $weeks   = $this->loadModel('weekly')->getWeekPairs($begin);
        $current = zget($weeks, $date, '');

        $this->weekly->save($this->session->project, $date);

        $PVEV = $this->weekly->getPVEV($this->session->project, $today);
        $this->view->pv = (float)$PVEV['PV'];
        $this->view->ev = (float)$PVEV['EV'];
        $this->view->ac = (float)$this->weekly->getAC($this->session->project, $today);
        $this->view->sv = $this->weekly->getSV($this->view->ev, $this->view->pv);
        $this->view->cv = $this->weekly->getCV($this->view->ev, $this->view->ac);

        $left     = (float)$this->weekly->getLeft($this->session->project, $today);
        $progress = (!empty($this->view->ac) || !empty($left)) ? floor($this->view->ac / ($this->view->ac + $left) * 1000) / 1000 * 100 : 0;
        $this->view->progress = $progress > 100 ? 100 : $progress;
        $this->view->current  = $current;
    }

    /**
     * Print waterfall general report block.
     *
     * @access protected
     * @return void
     */
    protected function printWaterfallGeneralReportBlock()
    {
        $this->app->loadLang('programplan');
        $this->loadModel('project');
        $this->loadModel('weekly');

        $data = $this->project->getWaterfallPVEVAC($this->session->project);
        $this->view->pv = (float)$data['PV'];
        $this->view->ev = (float)$data['EV'];
        $this->view->ac = (float)$data['AC'];
        $this->view->sv = $this->weekly->getSV($this->view->ev, $this->view->pv);
        $this->view->cv = $this->weekly->getCV($this->view->ev, $this->view->ac);

        $left     = (float)$data['left'];
        $progress = (!empty($this->view->ac) || !empty($left)) ? floor($this->view->ac / ($this->view->ac + $left) * 1000) / 1000 * 100 : 0;
        $this->view->progress = $progress > 100 ? 100 : $progress;
    }

    /**
     * Print waterfall gantt block.
     *
     * @access protected
     * @return void
     */
    protected function printWaterfallGanttBlock()
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
     * @access protected
     * @return void
     */
    protected function printWaterfallIssueBlock()
    {
        return $this->printIssueBlock();
    }

    /**
     * Print waterfall risk block.
     *
     * @access protected
     * @return void
     */
    protected function printWaterfallRiskBlock()
    {
        $this->printRiskBlock();
    }

    /**
     * Print waterfall estimate block.
     *
     * @access protected
     * @return void
     */
    protected function printWaterfallEstimateBlock()
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
     * @access protected
     * @return void
     */
    protected function printWaterfallProgressBlock()
    {
        $this->loadModel('milestone');
        $this->loadModel('weekly');
        $this->app->loadLang('execution');

        $projectID     = $this->session->project;
        $projectWeekly = $this->dao->select('*')->from(TABLE_WEEKLYREPORT)->where('project')->eq($projectID)->orderBy('weekStart_asc')->fetchAll('weekStart');

        $charts['PV'] = '[';
        $charts['EV'] = '[';
        $charts['AC'] = '[';
        foreach($projectWeekly as $weekStart => $data)
        {
            $charts['labels'][] = $weekStart;
            $charts['PV']      .= $data->pv . ',';
            $charts['EV']      .= $data->ev . ',';
            $charts['AC']      .= $data->ac . ',';
        }

        $charts['PV'] .= ']';
        $charts['EV'] .= ']';
        $charts['AC'] .= ']';

        $this->view->charts = $charts;
    }

    /**
     * Print srcum project block.
     *
     * @access protected
     * @return void
     */
    protected function printScrumOverviewBlock()
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
     * @access protected
     * @return void
     */
    protected function printScrumListBlock()
    {
        if(!empty($this->params->type) && preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) return;
        $count = isset($this->params->count) ? (int)$this->params->count : 15;
        $type  = isset($this->params->type) ? $this->params->type : 'undone';

        $this->app->loadClass('pager', true);
        $pager = pager::init(0, $count, 1);
        $this->loadModel('execution');
        $this->view->executionStats = !defined('TUTORIAL') ? $this->execution->getStatData($this->session->project, $type, 0, 0, false, '', 'id_desc', $pager) : array($this->loadModel('tutorial')->getExecution());
    }

    /**
     * Print srcum product block.
     *
     * @access protected
     * @return void
     */
    protected function printScrumProductBlock()
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
     * Print scrum issue block.
     *
     * @access protected
     * @return void
     */
    protected function printScrumIssueBlock()
    {
        return $this->printIssueBlock();
    }

    /**
     * Print scrum risk block.
     *
     * @access protected
     * @return void
     */
    protected function printScrumRiskBlock()
    {
        $this->printRiskBlock();
    }

    /**
     * Print issue block.
     *
     * @access protected
     * @return void
     */
    private function printIssueBlock()
    {
        $uri = $this->app->tab == 'my' ? $this->createLink('my', 'index') : $this->server->http_referer;
        $this->session->set('issueList', $uri, 'project');
        if(preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) return;
        $this->view->users  = $this->loadModel('user')->getPairs('noletter');
        $this->view->issues = $this->loadModel('issue')->getBlockIssues($this->session->project, $this->params->type, $this->viewType == 'json' ? 0 : (int)$this->params->count, $this->params->orderBy);
    }

    /**
     * Print risk block.
     *
     * @access protected
     * @return void
     */
    private function printRiskBlock()
    {
        $uri = $this->app->tab == 'my' ? $this->createLink('my', 'index') : $this->server->http_referer;
        $this->session->set('riskList', $uri, 'project');
        $this->view->users = $this->loadModel('user')->getPairs('noletter');
        $this->view->risks = $this->loadModel('risk')->getBlockRisks($this->session->project, $this->params->type, $this->viewType == 'json' ? 0 : (int)$this->params->count, $this->params->orderBy);
    }

    /**
     * Print sprint block.
     *
     * @access protected
     * @return void
     */
    protected function printSprintBlock()
    {
        $sprints = $this->dao->select('status, count(*) as sprints')->from(TABLE_EXECUTION)
            ->where('deleted')->eq(0)
            ->beginIF($this->config->vision == 'lite')->andWhere('type')->eq('kanban')->fi()
            ->beginIF($this->config->vision == 'rnd')->andWhere('type')->in('sprint,kanban')->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->sprints)->fi()
            ->andWhere('project')->eq($this->session->project)
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
     * @access protected
     * @return void
     */
    protected function printProjectDynamicBlock()
    {
        $projectID = $this->session->project;

        $executions = $this->loadModel('execution')->getPairs($projectID);
        $products   = $this->loadModel('product')->getProductPairsByProject($projectID);
        $count      = isset($this->params->count) ? (int)$this->params->count : 10;

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager(0, 30, 1);

        $this->view->actions = $this->loadModel('action')->getDynamic('all', 'all', 'date_desc', $pager, 'all', $projectID);
        $this->view->users   = $this->loadModel('user')->getPairs('noletter');
    }

    /**
     * Print srcum road map block.
     *
     * @param  int    $productID
     * @param  int    $roadMapID
     * @access protected
     * @return void
     */
    protected function printScrumRoadMapBlock($productID = 0, $roadMapID = 0)
    {
        $uri = $this->app->tab == 'my' ? $this->createLink('my', 'index') : $this->server->http_referer;
        $this->session->set('releaseList',     $uri, 'product');
        $this->session->set('productPlanList', $uri, 'product');

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
     * @access protected
     * @return void
     */
    protected function printScrumTestBlock()
    {
        $uri = $this->app->tab == 'my' ? $this->createLink('my', 'index') : $this->server->http_referer;
        $this->session->set('testtaskList', $uri, 'qa');
        $this->session->set('productList',  $uri, 'product');
        $this->session->set('projectList',  $uri, 'project');
        $this->session->set('buildList',    $uri, 'execution');
        $this->app->loadLang('testtask');

        $count  = zget($this->params, 'count', 10);
        $status = isset($this->params->type)  ? $this->params->type : 'wait';

        $this->view->project   = $this->loadModel('project')->getByID($this->session->project);
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
     * @access protected
     * @return void
     */
    protected function printQaStatisticBlock()
    {
        if(!empty($this->params->type) && preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) return;

        $this->app->loadLang('bug');
        $status = isset($this->params->type)  ? $this->params->type : '';
        $count  = isset($this->params->count) ? (int)$this->params->count : 0;

        $projectID  = $this->lang->navGroup->qa == 'project' ? $this->session->project : 0;
        $products   = $this->loadModel('product')->getOrderedProducts($status, $count, $projectID, 'all');
        $executions = $this->loadModel('execution')->getPairs($projectID, 'all', 'empty|withdelete');
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
            count(IF(assignedTo = '{$this->app->user->account}', 1, null)) as assignedToMe,
            count(IF(status != 'closed', 1, null)) as unclosed,
            count(IF(status != 'closed' and status != 'resolved', 1, null)) as unresolved,
            count(IF(confirmed = '0' and toStory = '0', 1, null)) as unconfirmed,
            count(IF(resolvedDate >= '$yesterday' and resolvedDate < '$today', 1, null)) as yesterdayResolved,
            count(IF(closedDate >= '$yesterday' and closedDate < '$today', 1, null)) as yesterdayClosed")
            ->from(TABLE_BUG)
            ->where('product')->in($productIdList)
            ->andWhere('execution')->in(array_keys($executions))
            ->andWhere('deleted')->eq(0)
            ->groupBy('product')
            ->fetchAll('product');

        $confirmedBugs = $this->dao->select('product')->from(TABLE_ACTION)
            ->where('objectType')->eq('bug')
            ->andWhere('action')->eq('bugconfirmed')
            ->andWhere('date')->ge($yesterday)
            ->andWhere('date')->lt($today)
            ->fetchAll();
        $productConfirmedBugs = array();
        foreach($confirmedBugs as $bug)
        {
            if(!isset($productConfirmedBugs[$bug->product])) $productConfirmedBugs[$bug->product] = 0;
            $productConfirmedBugs[$bug->product]++;
        }

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
            $product->yesterdayConfirmed = empty($productConfirmedBugs[",$productID,"]) ? 0 : $productConfirmedBugs[",$productID,"];

            $product->assignedRate    = $product->total ? round($product->assignedToMe  / $product->total * 100, 2) : 0;
            $product->unresolvedRate  = $product->total ? round($product->unresolved    / $product->total * 100, 2) : 0;
            $product->unconfirmedRate = $product->total ? round($product->unconfirmed   / $product->total * 100, 2) : 0;
            $product->unclosedRate    = $product->total ? round($product->unclosed      / $product->total * 100, 2) : 0;
            $product->testtask        = isset($testtasks[$productID]) ? $testtasks[$productID] : '';
        }

        $this->view->products = $products;
    }

    /**
     * Print product overview block.
     *
     * @access protected
     * @return void
     */
    protected function printProductOverviewBlock()
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
     * @access protected
     * @return void
     */
    protected function printExecutionOverviewBlock()
    {
        $projectID  = $this->view->block->module == 'my' ? 0 : (int)$this->session->project;
        $executions = $this->loadModel('execution')->getList($projectID);

        $total = 0;
        foreach($executions as $execution)
        {
            if(empty($execution->multiple)) continue;
            if(!isset($overview[$execution->status])) $overview[$execution->status] = 0;
            $overview[$execution->status]++;
            $total++;
        }

        $overviewPercent = array();
        $this->app->loadLang('project');
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
     * @access protected
     * @return void
     */
    protected function printQaOverviewBlock()
    {
        $casePairs = $this->dao->select('lastRunResult, COUNT(*) AS count')->from(TABLE_CASE)
            ->where('1=1')
            ->beginIF($this->view->block->module != 'my' && $this->session->project)->andWhere('project')->eq((int)$this->session->project)->fi()
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
     * @access protected
     * @return void
     */
    protected function printExecutionBlock()
    {
        if(!empty($this->params->type) && preg_match('/[^a-zA-Z0-9_]/', $this->params->type)) return;

        $count  = isset($this->params->count) ? (int)$this->params->count : 0;
        $status = isset($this->params->type)  ? $this->params->type : 'all';

        $this->loadModel('execution');
        $this->app->loadClass('pager', true);
        $pager = pager::init(0, $count, 1);

        $projectPairs = $this->dao->select('id,name')->from(TABLE_PROJECT)->where('type')->eq('project')->fetchPairs('id', 'name');
        $projectID    = $this->view->block->module == 'my' ? 0 : (int)$this->session->project;

        $this->view->executionStats = $this->execution->getStatData($projectID, $status, 0, 0, false, 'skipParent', 'id_asc', $pager);
    }

    /**
     * Print assign to me block.
     *
     * @access protected
     * @return void
     */
    protected function printAssignToMeBlock($longBlock = true)
    {
        $hasIssue   = helper::hasFeature('issue');
        $hasRisk    = helper::hasFeature('risk');
        $hasMeeting = helper::hasFeature('meeting');

        $hasViewPriv = array();
        if(common::hasPriv('todo',  'view'))                                                                                        $hasViewPriv['todo']        = true;
        if(common::hasPriv('task',  'view'))                                                                                        $hasViewPriv['task']        = true;
        if(common::hasPriv('bug',   'view') && $this->config->vision != 'lite')                                                     $hasViewPriv['bug']         = true;
        if(common::hasPriv('story', 'view') && $this->config->vision != 'lite')                                                     $hasViewPriv['story']       = true;
        if($this->config->URAndSR && common::hasPriv('story', 'view') && $this->config->vision != 'lite')                           $hasViewPriv['requirement'] = true;
        if(common::hasPriv('risk',  'view') && $this->config->edition == 'max' && $this->config->vision != 'lite' && $hasRisk)      $hasViewPriv['risk']        = true;
        if(common::hasPriv('issue', 'view') && $this->config->edition == 'max' && $this->config->vision != 'lite' && $hasIssue)     $hasViewPriv['issue']       = true;
        if(common::hasPriv('meeting', 'view') && $this->config->edition == 'max' && $this->config->vision != 'lite' && $hasMeeting) $hasViewPriv['meeting']     = true;
        if(common::hasPriv('feedback', 'view') && in_array($this->config->edition, array('max', 'biz')))                            $hasViewPriv['feedback']    = true;
        if(common::hasPriv('ticket', 'view') && in_array($this->config->edition, array('max', 'biz')))                              $hasViewPriv['ticket']      = true;

        $params          = $this->get->param;
        $params          = json_decode(base64_decode($params));
        $count           = array();
        $objectList      = array('todo' => 'todos', 'task' => 'tasks', 'bug' => 'bugs', 'story' => 'stories', 'requirement' => 'requirements');
        $objectCountList = array('todo' => 'todoCount', 'task' => 'taskCount', 'bug' => 'bugCount', 'story' => 'storyCount', 'requirement' => 'requirementCount');
        if($this->config->edition == 'max')
        {
            if($hasRisk)
            {
                $objectList      += array('risk' => 'risks');
                $objectCountList += array('risk' => 'riskCount');
            }

            if($hasIssue)
            {
                $objectList      += array('issue' => 'issues');
                $objectCountList += array('issue' => 'issueCount');
            }

            $objectList      += array('feedback' => 'feedbacks', 'ticket' => 'tickets');
            $objectCountList += array('feedback' => 'feedbackCount', 'ticket' => 'ticketCount');
        }

        if($this->config->edition == 'biz')
        {
            $objectList      += array('feedback' => 'feedbacks', 'ticket' => 'tickets');
            $objectCountList += array('feedback' => 'feedbackCount', 'ticket' => 'ticketCount');
        }

        $tasks = $this->loadModel('task')->getUserSuspendedTasks($this->app->user->account);
        foreach($objectCountList as $objectType => $objectCount)
        {
            if(!isset($hasViewPriv[$objectType])) continue;

            $table      = $objectType == 'requirement' ? TABLE_STORY : $this->config->objectTables[$objectType];
            $orderBy    = $objectType == 'todo' ? '`date` desc' : 'id_desc';
            $limitCount = isset($params->{$objectCount}) ? $params->{$objectCount} : 0;
            $objects    = $this->dao->select('t1.*')->from($table)->alias('t1')
                ->beginIF($objectType == 'story' || $objectType == 'requirement')->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')->fi()
                ->beginIF($objectType == 'bug')->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')->fi()
                ->beginIF($objectType == 'task')->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.execution=t2.id')->fi()
                ->beginIF($objectType == 'issue' || $objectType == 'risk')->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')->fi()
                ->beginIF($objectType == 'ticket')->leftJoin(TABLE_USER)->alias('t2')->on('t1.openedBy = t2.account')->fi()
                ->where('t1.deleted')->eq(0)
                ->andWhere('t1.assignedTo')->eq($this->app->user->account)->fi()
                ->beginIF($objectType == 'story')->andWhere('t1.type')->eq('story')->andWhere('t2.deleted')->eq('0')->fi()
                ->beginIF($objectType == 'requirement')->andWhere('t1.type')->eq('requirement')->andWhere('t2.deleted')->eq('0')->fi()
                ->beginIF($objectType == 'bug')->andWhere('t2.deleted')->eq('0')->fi()
                ->beginIF($objectType == 'story' || $objectType == 'requirement')->andWhere('t2.deleted')->eq('0')->fi()
                ->beginIF($objectType == 'todo')->andWhere('t1.cycle')->eq(0)->andWhere('t1.status')->eq('wait')->andWhere('t1.vision')->eq($this->config->vision)->fi()
                ->beginIF($objectType != 'todo')->andWhere('t1.status')->ne('closed')->fi()
                ->beginIF($objectType == 'feedback')->andWhere('t1.status')->in('wait, noreview')->fi()
                ->beginIF($objectType == 'issue' || $objectType == 'risk')->andWhere('t2.deleted')->eq(0)->fi()
                ->beginIF($objectType == 'ticket')->andWhere('t1.status')->in('wait,doing,done')->fi()
                ->orderBy($orderBy)
                ->beginIF($limitCount)->limit($limitCount)->fi()
                ->fetchAll();

            if($objectType == 'todo')
            {
                $this->app->loadClass('date');
                $this->app->loadLang('todo');
                foreach($objects as $key => $todo)
                {
                    if($todo->status == 'done' && $todo->finishedBy == $this->app->user->account)
                    {
                        unset($objects[$key]);
                        continue;
                    }
                    if($todo->type == 'task' && isset($tasks[$todo->idvalue]))
                    {
                        unset($objects[$key]);
                        continue;
                    }

                    $todo->begin = date::formatTime($todo->begin);
                    $todo->end   = date::formatTime($todo->end);
                }
            }

            if($objectType == 'task')
            {
                $this->app->loadLang('task');
                $this->app->loadLang('execution');

                $objects = $this->loadModel('task')->getUserTasks($this->app->user->account, 'assignedTo');

                foreach($objects as $k => $task)
                {
                    if(in_array($task->status, array('closed', 'cancel'))) unset($objects[$k]);
                }
                if($limitCount > 0) $objects = array_slice($objects, 0, $limitCount);
            }

            if($objectType == 'bug')   $this->app->loadLang('bug');
            if($objectType == 'risk')  $this->app->loadLang('risk');
            if($objectType == 'issue') $this->app->loadLang('issue');

            if($objectType == 'feedback' || $objectType == 'ticket')
            {
                $this->app->loadLang('feedback');
                $this->app->loadLang('ticket');
                $this->view->users    = $this->loadModel('user')->getPairs('all,noletter');
                $this->view->products = $this->dao->select('id, name')->from(TABLE_PRODUCT)->where('deleted')->eq('0')->fetchPairs('id', 'name');
            }

            $count[$objectType] = count($objects);
            $this->view->{$objectList[$objectType]} = $objects;
        }

        if(isset($hasViewPriv['meeting']))
        {
            $this->app->loadLang('meeting');
            $today        = helper::today();
            $now          = date('H:i:s', strtotime(helper::now()));
            $meetingCount = isset($params->meetingCount) ? isset($params->meetingCount) : 0;

            $meetings = $this->dao->select('*')->from(TABLE_MEETING)->alias('t1')
                ->leftjoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
                ->where('t1.deleted')->eq('0')
                ->andWhere('t2.deleted')->eq('0')
                ->andWhere('(t1.date')->gt($today)
                ->orWhere('(t1.begin')->gt($now)
                ->andWhere('t1.date')->eq($today)
                ->markRight(2)
                ->andwhere('(t1.host')->eq($this->app->user->account)
                ->orWhere('t1.participant')->in($this->app->user->account)
                ->markRight(1)
                ->orderBy('t1.id_desc')
                ->beginIF($meetingCount)->limit($meetingCount)->fi()
                ->fetchAll();

            $count['meeting'] = count($meetings);
            $this->view->meetings = $meetings;
            $this->view->depts    = $this->loadModel('dept')->getOptionMenu();
            $this->view->users    = $this->loadModel('user')->getPairs('all,noletter');
        }

        $limitCount = !empty($params->reviewCount) ? $params->reviewCount : 20;
        $this->app->loadClass('pager', $static = true);
        $pager = new pager(0, $limitCount, 1);
        $reviews = $this->loadModel('my')->getReviewingList('all', 'time_desc', $pager);
        if($reviews)
        {
            $hasViewPriv['review'] = true;
            $count['review']       = count($reviews);
            $this->view->reviews   = $reviews;
            if($this->config->edition == 'max')
            {
                $this->app->loadLang('approval');
                $this->view->flows = $this->dao->select('module,name')->from(TABLE_WORKFLOW)->where('buildin')->eq(0)->fetchPairs('module', 'name');
            }
        }

        $this->view->isExternalCall = $this->isExternalCall();
        $this->view->hasViewPriv    = $hasViewPriv;
        $this->view->count          = $count;
        $this->view->longBlock      = $longBlock;
    }

    /**
     * Print recent project block.
     *
     * @access protected
     * @return void
     */
    protected function printRecentProjectBlock()
    {
        /* load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager(0, 3, 1);
        $this->view->projects = $this->loadModel('project')->getList('all', 'id_desc', true, $pager);
    }

    /**
     * Print project team block.
     *
     * @access protected
     * @return void
     */
    protected function printProjectTeamBlock()
    {
        $count   = isset($this->params->count)   ? $this->params->count   : 15;
        $status  = isset($this->params->type)    ? $this->params->type    : 'all';
        $orderBy = isset($this->params->orderBy) ? $this->params->orderBy : 'id_desc';

        /* Get projects. */
        $this->app->loadLang('task');
        $this->app->loadLang('program');
        $this->app->loadLang('execution');
        $this->view->projects = $this->loadModel('project')->getOverviewList('byStatus', $status, $orderBy, $count);
    }

    /**
     * Print document statistic block.
     *
     * @access protected
     * @return void
     */
    protected function printDocStatisticBlock()
    {
        $this->view->statistic = $this->loadModel('doc')->getStatisticInfo();
    }

    /**
     * Print document dynamic block.
     *
     * @access protected
     * @return void
     */
    protected function printDocDynamicBlock()
    {
        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager(0, 30, 1);

        $this->view->actions = $this->loadModel('doc')->getDynamic($pager);
        $this->view->users   = $this->loadModel('user')->getPairs('nodeleted|noletter|all');
    }

    /**
     * Print my collection of documents block.
     *
     * @access protected
     * @return void
     */
    protected function printDocMyCollectionBlock()
    {
        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager(0, 6, 1);

        $docList = $this->loadModel('doc')->getDocsByBrowseType('collectedbyme', 0, 0, 'editedDate_desc', $pager);
        $libList = array();
        foreach($docList as $doc)
        {
            $doc->editedDate   = substr($doc->editedDate, 0, 10);
            $doc->editInterval = helper::getDateInterval($doc->editedDate);

            $libList[] = $doc->lib;
        }

        $this->view->docList  = $docList;
    }

    /**
     * Print recent update block.
     *
     * @access protected
     * @return void
     */
    protected function printDocRecentUpdateBlock()
    {
        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        $pager = new pager(0, 6, 1);

        $docList = $this->loadModel('doc')->getDocsByBrowseType('byediteddate', 0, 0, 'editedDate_desc', $pager);
        $libList = array();
        foreach($docList as $doc)
        {
            $doc->editedDate   = substr($doc->editedDate, 0, 10);
            $doc->editInterval = helper::getDateInterval($doc->editedDate);

            $libList[] = $doc->lib;
        }

        $this->view->docList  = $docList;
    }

    /**
     * Print view list block.
     *
     * @access protected
     * @return void
     */
    protected function printDocViewListBlock()
    {
        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager(0, 6, 1);

        $this->view->docList = $this->loadModel('doc')->getDocsByBrowseType('all', 0, 0,'views_desc', $pager);
    }

    /**
     * Print collect list block.
     *
     * @access protected
     * @return void
     */
    protected function printDocCollectListBlock()
    {
        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager(0, 6, 1);

        $this->view->docList = $this->loadModel('doc')->getDocsByBrowseType('all', 0, 0, 'collects_desc', $pager);
    }

    /**
     * Print product's document block.
     *
     * @access protected
     * @return void
     */
    protected function printProductDocBlock()
    {
        $this->loadModel('doc');
        $this->session->set('docList', $this->createLink('doc', 'index'), 'doc');

        /* Set project status and count. */
        $count         = isset($this->params->count) ? (int)$this->params->count : 15;
        $products      = $this->loadModel('product')->getOrderedProducts('all');
        $involveds     = $this->product->getOrderedProducts('involved');
        $productIdList = array_merge(array_keys($products), array_keys($involveds));

        $stmt = $this->dao->select('id,product,lib,title,type,addedBy,addedDate,editedDate,status,acl,groups,users,deleted')->from(TABLE_DOC)->alias('t1')
            ->where('deleted')->eq(0)
            ->andWhere('product')->in($productIdList)
            ->orderBy('product,status,editedDate_desc')
            ->query();
        $docGroup = array();
        while($doc = $stmt->fetch())
        {
            if(!isset($docGroup[$doc->product])) $docGroup[$doc->product] = array();
            if(count($docGroup[$doc->product]) >= $count) continue;
            if($this->doc->checkPrivDoc($doc)) $docGroup[$doc->product][$doc->id] = $doc;
        }

        $hasDataProducts = $hasDataInvolveds = array();
        foreach($products as $productID => $product)
        {
            if(isset($docGroup[$productID]) && count($docGroup[$productID]) > 0)
            {
                $hasDataProducts[$productID] = $product;
                if(isset($involveds[$productID])) $hasDataInvolveds[$productID] = $product;
            }
        }

        $this->view->users     = $this->loadModel('user')->getPairs('noletter');
        $this->view->products  = $hasDataProducts;
        $this->view->involveds = $hasDataInvolveds;
        $this->view->docGroup  = $docGroup;
    }

    /**
     * Print project's document block.
     *
     * @access protected
     * @return void
     */
    protected function printProjectDocBlock()
    {
        $this->loadModel('doc');
        $this->app->loadLang('project');
        $this->session->set('docList', $this->createLink('doc', 'index'), 'doc');

        /* Set project status and count. */
        $count    = isset($this->params->count) ? (int)$this->params->count : 15;
        $projects = $this->dao->select('*')->from(TABLE_PROJECT)
            ->where('deleted')->eq('0')
            ->andWhere('vision')->eq($this->config->vision)
            ->andWhere('type')->eq('project')
            ->beginIF($this->config->vision == 'rnd')->andWhere('model')->ne('kanban')->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->projects)->fi()
            ->orderBy('order_asc,id_desc')
            ->fetchAll('id');

        $involveds = $this->dao->select('t1.*')->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin(TABLE_TEAM)->alias('t2')->on('t1.id=t2.root')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.vision')->eq($this->config->vision)
            ->andWhere('t1.type')->eq('project')
            ->beginIF($this->config->vision == 'rnd')->andWhere('t1.model')->ne('kanban')->fi()
            ->andWhere('t2.type')->eq('project')
            ->beginIF(!$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->projects)->fi()
            ->andWhere('t1.openedBy', true)->eq($this->app->user->account)
            ->orWhere('t1.PM')->eq($this->app->user->account)
            ->orWhere('t2.account')->eq($this->app->user->account)
            ->markRight(1)
            ->orderBy('t1.order_asc,t1.id_desc')
            ->fetchAll('id');

        $projectIdList = array_keys($projects);

        $stmt = $this->dao->select('t1.id,t1.lib,t1.title,t1.type,t1.addedBy,t1.addedDate,t1.editedDate,t1.status,t1.acl,t1.groups,t1.users,t1.deleted,if(t1.project = 0, t2.project, t1.project) as project')->from(TABLE_DOC)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted', true)->eq(0)
            ->orWhere('t2.deleted is null')
            ->markRight(1)
            ->andWhere('t1.project', true)->in($projectIdList)
            ->orWhere('t2.project')->in($projectIdList)
            ->markRight(1)
            ->orderBy('project,t1.status,t1.editedDate_desc')
            ->query();
        $docGroup = array();
        while($doc = $stmt->fetch())
        {
            if(!isset($docGroup[$doc->project])) $docGroup[$doc->project] = array();
            if(count($docGroup[$doc->project]) >= $count) continue;
            if($this->doc->checkPrivDoc($doc)) $docGroup[$doc->project][$doc->id] = $doc;
        }

        $hasDataProjects = $hasDataInvolveds = array();
        foreach($projects as $projectID => $project)
        {
            if(isset($docGroup[$projectID]) && count($docGroup[$projectID]) > 0)
            {
                $hasDataProjects[$projectID] = $project;
                if(isset($involveds[$projectID])) $hasDataInvolveds[$projectID] = $project;
            }
        }

        $this->view->users     = $this->loadModel('user')->getPairs('noletter');
        $this->view->projects  = $hasDataProjects;
        $this->view->involveds = $hasDataInvolveds;
        $this->view->docGroup  = $docGroup;
    }

    /**
     * Print guide block
     *
     * @param  int    $blockID
     * @access protected
     * @return void
     */
    protected function printGuideBlock($blockID = 0)
    {
        $this->app->loadLang('custom');
        $this->app->loadLang('my');
        $this->loadModel('setting');

        $this->view->blockID       = $blockID;
        $this->view->programs      = $this->loadModel('program')->getTopPairs('', 'noclosed', true);
        $this->view->programID     = isset($this->config->global->defaultProgram) ? $this->config->global->defaultProgram : 0;
        $this->view->URSRList      = $this->loadModel('custom')->getURSRPairs();
        $this->view->URSR          = $this->setting->getURSR();
        $this->view->programLink   = isset($this->config->programLink)   ? $this->config->programLink   : 'program-browse';
        $this->view->productLink   = isset($this->config->productLink)   ? $this->config->productLink   : 'product-all';
        $this->view->projectLink   = isset($this->config->projectLink)   ? $this->config->projectLink   : 'project-browse';
        $this->view->executionLink = isset($this->config->executionLink) ? $this->config->executionLink : 'execution-task';
    }

    /**
     * 判断是否为内部调用。
     * Check request client is chandao or not.
     *
     * @access protected
     * @return bool
     */
    protected function isExternalCall(): bool
    {
        return isset($_GET['hash']);
    }

    /**
     * 为control 层 printBlock 方法返回json 格式数据。
     * Return json data for printBlock.
     *
     * @access protected
     * @return string
     */
    protected function printBlock4Json(): string
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
        return print(json_encode($output));
    }

    /**
     * 组织外部数据。
     * Organiza external data.
     *
     * @param  object    $block
     * @access protected
     * @return void
     */
    protected function organizaExternalData(object $block)
    {
        $lang = isset($this->get->lang) ? $this->get->lang : 'zh-cn';
        $lang = str_replace('_', '-', $lang);
        $this->app->setClientLang($lang);
        $this->app->loadLang('common');

        if(!isset($block->params) && !isset($block->params->account))
        {
            $this->app->user = new stdclass();
            $this->app->user->account = 'guest';
            $this->app->user->realname= 'guest';
        }
        else
        {
            $this->app->user = $this->dao->select('*')->from(TABLE_USER)->where('ranzhi')->eq($block->params->account)->fetch();
            if(empty($this->app->user))
            {
                $this->app->user = new stdclass();
                $this->app->user->account = 'guest';
                $this->app->user->realname= 'guest';
            }
        }
        $this->app->user->admin  = strpos($this->app->company->admins, ",{$this->app->user->account},") !== false;
        $this->app->user->rights = $this->loadModel('user')->authorize($this->app->user->account);
        $this->app->user->groups = $this->user->getGroups($this->app->user->account);
        $this->app->user->view   = $this->user->grantUserView($this->app->user->account, $this->app->user->rights['acls']);

        $sso = isset($this->get->sso) ? base64_decode($this->get->sso) : '';
        $this->view->sso  = $sso;
        $this->view->sign = strpos($sso, '?') === false ? '?' : '&';
    }
}
