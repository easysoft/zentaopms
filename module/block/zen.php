<?php
class blockZen extends block
{
    /**
     * 初始化用户某个仪表盘下的区块数据。
     * Init block when account use first.
     *
     * @param  string $dashboard
     * @access public
     * @return bool
     */
    public function initBlock(string $dashboard): bool
    {
        if(!$dashboard) return false;

        $flow    = isset($this->config->global->flow) ? $this->config->global->flow : 'full';
        $account = $this->app->user->account;
        $vision  = $this->config->vision;

        /* 获取该仪表盘下的初始化默认布局。 */
        $blocks = $dashboard == 'my' ? $this->lang->block->default[$flow][$dashboard] : $this->lang->block->default[$dashboard];

        foreach($blocks as $block)
        {
            /* 根据module和code生成区块的宽度和高度。 */
            $defaultSize = $this->config->block->defaultSize; // 默认为区块的统一默认尺寸。
            if(!empty($this->config->block->size[$block['module']][$block['code']]))                  $defaultSize     = $this->config->block->size[$block['module']][$block['code']];
            if(!empty($this->config->block->size[$block['module']][$block['code']][$block['width']])) $block['height'] = $this->config->block->size[$block['module']][$block['code']][$block['width']];
            if(empty($block['width']))  $block['width']  = reset(array_keys($defaultSize));
            if(empty($block['height'])) $block['height'] = reset($defaultSize);

            $block['account']   = $account;   // 所属用户。
            $block['dashboard'] = $dashboard; // 所属仪表盘。
            $block['params']    = isset($block['params']) ? helper::jsonEncode($block['params']) : ''; // 配置项信息。
            $block['vision']    = $this->config->vision;                         // 所属用户界面。
            $block['left']      = $block['width'] == 1 ? 2 : 0;                  // 距左侧宽度。
            $block['top']       = $this->block->computeBlockTop((object)$block); // 距顶部高度。

            $this->block->create((object)$block);
        }
        if(dao::isError()) return false;

        /* 保存当前区块已经被初始化过的记录。 */
        $this->loadModel('setting')->setItem("$account.$dashboard.common.blockInited@$vision", '1');
        $this->loadModel('setting')->setItem("$account.$dashboard.block.initVersion", (string)$this->config->block->version);

        return true;
    }

    /**
     * 根据仪表盘获取可使用的模块列表。
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

        return $modules;
    }

    /**
     * 根据模块获取可使用的区块列表。
     * Get block options when adding or editing blocks.
     *
     * @param  string        $module
     * @access protected
     * @return string[]|true
     */
    protected function getAvailableCodes(string $module): array|bool
    {
        /* 获取当前模块下的所有区块列表。 */
        if($module && isset($this->lang->block->modules[$module]))
        {
            $blocks = $this->lang->block->modules[$module]->availableBlocks;
        }
        else
        {
            $blocks = array();
        }

        /* 过滤掉永久关闭的区块。 */
        if(isset($this->config->block->closed))
        {
            foreach($blocks as $blockKey => $blockName)
            {
                if(strpos(",{$this->config->block->closed},", ",{$module}|{$blockKey},") !== false) unset($blocks[$blockKey]);
            }
        }

        return $blocks;
    }

    /**
     * 根据区块获取区块相关可配置参数列表。
     * Get other form items when adding or editing blocks.
     *
     * @param  string    $module
     * @param  string    $code
     * @access protected
     * @return array
     */
    protected function getAvailableParams(string $module = '', string $code = ''): array
    {
        /* 特殊的模块对code特殊处理。 */
        if($code == 'todo' || $code == 'list' || $module == 'assigntome')
        {
            $code = $module;
        }
        elseif($code == 'statistic')
        {
            $code = $module . $code;
        }

        $params = zget($this->config->block->params, $code, array());
        $params = json_decode(json_encode($params), true);

        return $params;
    }

    /**
     * 自动生成区块的默认标题。
     * Get block default title.
     *
     * @param  string    $modules
     * @param  string    $module
     * @param  array     $codes
     * @param  string    $code
     * @param  array     $params
     * @access protected
     * @return string
     */
    protected function getBlockTitle(array $modules, string $module, array $codes, string $code, array $params): string
    {
        $blockTitle = zget($codes, $code, ''); // 标快的标题默认是区块列表下拉选中的内容。

        /* scrumtest以外的区块标题前面要根据选中的type类型填充内容。 如：已关闭的产品列表。 */
        if($module != 'scrumtest' || $code == 'all')
        {
            $options = isset($params['type']['options']) ? $params['type']['options'] : array();
            if(!empty($options)) $blockTitle = vsprintf($this->lang->block->blockTitle, array(reset($options), $blockTitle));
            if(empty($blockTitle) && !in_array($module, array('product', 'project', 'execution', 'qa'))) $blockTitle = zget($modules, $module, ''); // 特殊的区块标题使用模块列表选中的文本内容。 如：欢迎总览区块。
        }

        return $blockTitle;
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

            $defaultSize = $this->config->block->defaultSize; // 默认为区块的统一默认尺寸。
            if(!empty($this->config->block->size[$block->module][$block->code])) $defaultSize = $this->config->block->size[$block->module][$block->code];

            if(empty($block->width))  $block->width  = reset(array_keys($defaultSize));
            if(empty($block->height)) $block->height = !empty($this->config->block->size[$block->module][$block->code][$block->width]) ? $this->config->block->size[$block->module][$block->code][$block->width] : reset($defaultSize);

            /* 设置区块距离左侧的宽度和距离顶部的高度。 */
            $block->left = $block->width == 1 ? 2 : 0;
        }

        /* 根据每个区块的高度和宽度重新生成各个区块的left和top属性。 */
        $blocks = array_values($blocks);
        $height = array(1 => 0, 2 => 0);
        foreach($blocks as $block)
        {
            if($block->width == 3)
            {
                $block->top = max($height[1], $height[2]);
                $height[1] += $block->height;
                $height[2] += $block->height;
            }
            else
            {
                $block->top = $height[$block->width];
                $height[$block->width] += $block->height;
            }
        }
        return $blocks;
    }

    /**
     * 补全区块的加载更多链接。
     * Get the more link of the block.
     *
     * @param  object    $block
     * @param  int       $projectID
     * @access protected
     * @return object
     */
    protected function createMoreLink(object $block, int $projectID): object
    {
        $module = empty($block->module) ? 'common' : $block->module;

        $params = helper::safe64Encode("module={$block->module}&projectID={$projectID}");
        $block->blockLink = $this->createLink('block', 'printBlock', "id=$block->id&params=$params");
        $block->moreLink  = '';
        if(isset($this->config->block->modules[$module]->moreLinkList->{$block->code}))
        {
            list($moduleName, $method, $vars) = explode('|', sprintf($this->config->block->modules[$module]->moreLinkList->{$block->code}, isset($block->params->type) ? $block->params->type : ''));

            /* The list assigned to me jumps to the work page when click more button. */
            $block->moreLink = $this->createLink($moduleName, $method, $vars);
            if($moduleName == 'my' && strpos($this->config->block->workMethods, $method) !== false)
            {
                /* 处理研发需求或任务列表区块点击更多后的跳转连接。 */
                if($moduleName == 'my' && $method == 'task' && $block->params->type != 'assignedTo' || $moduleName == 'my' && $method == 'story' && $block->params->type != 'assignedTo' && $block->params->type != 'reviewBy')
                {
                    $block->moreLink = $this->createLink('my', 'contribute', "module={$method}&type={$block->params->type}");
                }
                else
                {
                    $block->moreLink = $this->createLink($moduleName, 'work', 'mode=' . $method . '&' . $vars);
                }
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
            if($todo->date == FUTURE_TIME || ($todo->type == 'task' && isset($suspendedTasks[$todo->objectID]))) unset($todos[$key]);
        }
        return $todos;
    }

    /**
     * latest dynamic.
     *
     * @access protected
     * @return void
     */
    protected function printDynamicBlock(): void
    {
        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager(0, 30, 1);

        $this->view->actions = $this->loadModel('action')->getDynamic('all', 'today', 'date_desc', $pager);
        $this->view->users   = $this->loadModel('user')->getPairs('nodeleted|noletter|all');
    }

    /**
     * Latest dynamic by zentao official.
     *
     * @access protected
     * @return void
     */
    protected function printZentaoDynamicBlock(): void
    {
        $this->app->loadModuleConfig('admin');

        $dynamics = array();
        $result = json_decode(preg_replace('/[[:cntrl:]]/mu', '', common::http($this->config->admin->dynamicAPIURL)));
        if(!empty($result->data))
        {
            $data = $result->data;
            if(!empty($data->zentaosalon) && time() < strtotime($data->zentaosalon->time))
            {
                $data->zentaosalon->title     = $this->lang->block->zentaodynamic->zentaosalon;
                $data->zentaosalon->label     = formatTime($data->zentaosalon->time, DT_DATE4) . ' ' . $data->zentaosalon->name;
                $data->zentaosalon->linklabel = $this->lang->block->zentaodynamic->registration;
                $dynamics[] = $data->zentaosalon;
            }
            if(!empty($data->publicclass) && time() < strtotime($data->zentaosalon->time))
            {
                $data->publicclass->title     = $this->lang->block->zentaodynamic->publicclass;
                $data->publicclass->label     = formatTime($data->publicclass->time, DT_DATE4) . $this->lang->datepicker->dayNames[date('w', strtotime($data->publicclass->time))] . ' ' . formatTime($data->publicclass->time, 'H:i');
                $data->publicclass->linklabel = $this->lang->block->zentaodynamic->reservation;
                $dynamics[] = $data->publicclass;
            }
            if(!empty($data->release))
            {
                foreach($data->release as $release)
                {
                    $release->title = $this->lang->block->zentaodynamic->release;
                    $release->label = formatTime($data->publicclass->time, DT_DATE4) . $this->lang->datepicker->dayNames[date('w', strtotime($data->publicclass->time))];
                    $dynamics[] = $release;
                }
            }
        }
        $this->view->dynamics = $dynamics;
    }

    /**
     * 打印欢迎总览区块。
     * Welcome block.
     *
     * @access protected
     * @return void
     */
    protected function printWelcomeBlock(): void
    {
        /* 计算当前时间是早上还是中午还是下午还是晚上。 */
        $time = date('H:i');
        $welcomeType = '19:00';
        foreach($this->lang->block->welcomeList as $type => $name) $welcomeType = $time >= $type ? $type : $welcomeType;

        /* 获取禅道陪伴当前用户总天数。 */
        $firstUseDate = $this->dao->select('date')->from(TABLE_ACTION)
            ->where('date')->gt('1970-01-01')
            ->andWhere('actor')->eq($this->app->user->account)
            ->orderBy('date_asc')
            ->limit('1')
            ->fetch('date');

        $usageDays = 1; // 最小陪伴天数是一天。
        if($firstUseDate) $usageDays = ceil((time() - strtotime($firstUseDate)) / 3600 / 24);

        $yesterday  = strtotime("-1 day");
        /* 获取昨日完成的任务数。 */
        $finishTask      = 0;
        $finishTaskGroup = $this->loadModel('metric')->getResultByCode('count_of_daily_finished_task_in_user', array('user' => $this->app->user->account, 'year' => date('Y', $yesterday), 'month' => date('m', $yesterday), 'day' => date('d', $yesterday)));
        if(!empty($finishTaskGroup))
        {
            $finishTaskGroup = reset($finishTaskGroup);
            $finishTask      = zget($finishTaskGroup, 'value', 0);
        }

        /* 获取昨日解决的BUG数。 */
        $fixBug      = 0;
        $fixBugGroup = $this->metric->getResultByCode('count_of_daily_fixed_bug_in_user', array('user' => $this->app->user->account, 'year' => date('Y', $yesterday), 'month' => date('m', $yesterday), 'day' => date('d', $yesterday)));
        if(!empty($fixBug))
        {
            $fixBugGroup = reset($fixBugGroup);
            $fixBug      = zget($fixBugGroup, 'value', 0);
        }

        /* 根据完成任务和修复bug的数量给与称号。 */
        $honorary = '';
        if($finishTask || $fixBug)
        {
            $honorary = $finishTask > $fixBug ? 'task' : 'bug';
            $honorary = zget($this->lang->block->honorary, $honorary, '');
        }

        /* 生成指派给我的数据。 */
        $assignToMe = array();
        foreach($this->lang->block->welcome->assignList as $field => $label)
        {
            $type = 'assignedTo';
            if($field == 'testcase') $type = 'assigntome';

            /* 根据不同的模块生成不同的度量项查询码。 */
            $code = "assigned_{$field}";
            if($field == 'story')    $code = "pending_story";
            if($field == 'testcase') $code = "assigned_case";

            /* 查询当前指派给当前用户的不同数据。 */
            $assignedGroup = $this->metric->getResultByCode("count_of_{$code}_in_user", array('user' => $this->app->user->account));
            $count = 0;
            if(!empty($assignedGroup))
            {
                $assignedGroup = reset($assignedGroup);
                $count         = zget($assignedGroup, 'value', 0);
            }
            $assignToMe[$field] = array('number' => $count, 'delay' => 0, 'href' => helper::createLink('my', 'work', "mode=$field&type=$type"));
        }

        /* 生成待我审批的数据。 */
        $reviewByMe = array();
        foreach($this->lang->block->welcome->reviewList as $field => $label)
        {
            /* 根据不同的模块生成不同的度量项查询码。 */
            $code = "reviewing_{$field}";

            /* 查询当前指派给当前用户的不同数据。 */
            $reviewingGroup = $this->metric->getResultByCode("count_of_{$code}_in_user", array('user' => $this->app->user->account));
            $count = 0;
            if(!empty($reviewingGroup))
            {
                $reviewingGroup = reset($reviewingGroup);
                $count          = zget($reviewingGroup, 'value', 0);
            }
            $reviewByMe[$field] = array('number' => $count, 'delay' => 0);
        }

        $this->view->todaySummary = date(DT_DATE3, time()) . ' ' . $this->lang->datepicker->dayNames[date('w', time())]; // 当前年月日 星期几。
        $this->view->welcomeType  = $welcomeType;
        $this->view->usageDays    = $usageDays;
        $this->view->finishTask   = $finishTask;
        $this->view->fixBug       = $fixBug;
        $this->view->honorary     = $honorary;
        $this->view->assignToMe   = $assignToMe;
        $this->view->reviewByMe   = $reviewByMe;
    }

    /**
     * Print contribute block.
     *
     * @access protected
     * @return void
     */
    protected function printContributeBlock(): void
    {
        $this->view->data = $this->loadModel('user')->getPersonalData();
    }

    /**
     * Print todo block.
     *
     * @params object     $block
     * @access protected
     * @return void
     */
    protected function printTodoListBlock(object $block): void
    {
        $limit = ($this->viewType == 'json' || !isset($block->params->count)) ? 0 : (int)$block->params->count;
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
     * @params object     $block
     * @access protected
     * @return void
     */
    protected function printTaskBlock(object $block): void
    {
        $this->session->set('taskList',  $this->createLink('my', 'index'), 'execution');
        if(preg_match('/[^a-zA-Z0-9_]/', $block->params->type)) return;

        $account = $this->app->user->account;
        $type    = $block->params->type;

        $this->app->loadLang('execution');
        $this->view->tasks = $this->loadModel('task')->getUserTasks($account, $type, $this->viewType == 'json' ? 0 : (int)$block->params->count, null, $block->params->orderBy);;
    }

    /**
     * Print bug block.
     *
     * @params object     $block
     * @access protected
     * @return void
     */
    protected function printBugBlock(object $block): void
    {
        $this->session->set('bugList', $this->createLink('my', 'index'), 'qa');
        if(preg_match('/[^a-zA-Z0-9_]/', $block->params->type)) return;

        $projectID = $this->lang->navGroup->qa  == 'project' ? $this->session->project : 0;
        $projectID = $block->dashboard == 'my' ? 0 : $projectID;
        $this->view->bugs = $this->loadModel('bug')->getUserBugs($this->app->user->account, $block->params->type, $block->params->orderBy, $this->viewType == 'json' ? 0 : (int)$block->params->count, null, $projectID);
    }

    /**
     * Print case block.
     *
     * @params object     $block
     * @access protected
     * @return void
     */
    protected function printCaseBlock(object $block): void
    {
        $this->session->set('caseList', $this->createLink('my', 'index'), 'qa');
        $this->app->loadLang('testcase');
        $this->app->loadLang('testtask');

        $projectID = $this->lang->navGroup->qa  == 'project' ? $this->session->project : 0;
        $projectID = $block->dashboard == 'my' ? 0 : $projectID;

        $cases = array();
        if($block->params->type == 'assigntome')
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
                ->orderBy($block->params->orderBy)
                ->beginIF($this->viewType != 'json')->limit((int)$block->params->count)->fi()
                ->fetchAll();
        }
        elseif($block->params->type == 'openedbyme')
        {
            $cases = $this->dao->findByOpenedBy($this->app->user->account)->from(TABLE_CASE)
                ->andWhere('deleted')->eq(0)
                ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
                ->orderBy($block->params->orderBy)
                ->beginIF($this->viewType != 'json')->limit((int)$block->params->count)->fi()
                ->fetchAll();
        }
        $this->view->cases = $cases;
    }

    /**
     * Print testtask block.
     *
     * @params object     $block
     * @access protected
     * @return void
     */
    protected function printTesttaskBlock($block): void
    {
        $this->app->loadLang('testtask');

        $uri = $this->createLink('my', 'index');
        $this->session->set('productList',  $uri, 'product');
        $this->session->set('testtaskList', $uri, 'qa');
        $this->session->set('buildList',    $uri, 'execution');
        if(preg_match('/[^a-zA-Z0-9_]/', $block->params->type)) return;

        $this->view->projects  = $this->loadModel('project')->getPairsByProgram();
        $this->view->testtasks = $this->dao->select("t1.*,t2.name as productName,t2.shadow,t3.name as buildName,t4.name as projectName, CONCAT(t4.name, '/', t3.name) as executionBuild")->from(TABLE_TESTTASK)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->leftJoin(TABLE_BUILD)->alias('t3')->on('t1.build=t3.id')
            ->leftJoin(TABLE_PROJECT)->alias('t4')->on('t1.execution=t4.id')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t5')->on('t1.execution=t5.project')
            ->where('t1.deleted')->eq('0')
            ->beginIF(!$this->app->user->admin)->andWhere('t1.product')->in($this->app->user->view->products)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t1.execution')->in($this->app->user->view->sprints)->fi()
            ->andWhere('t1.product = t5.product')
            ->beginIF($block->params->type != 'all')->andWhere('t1.status')->eq($block->params->type)->fi()
            ->orderBy('t1.id desc')
            ->beginIF($this->viewType != 'json')->limit((int)$block->params->count)->fi()
            ->fetchAll();
    }

    /**
     * Print story block.
     *
     * @params object     $block
     * @access protected
     * @return void
     */
    protected function printStoryBlock(object $block): void
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
     * 打印产品计划列表区块。
     * Print plan block.
     *
     * @param  object    $block
     * @access protected
     * @return bool
     */
    protected function printPlanBlock(object $block): void
    {
        $uri = $this->createLink('my', 'index');
        $this->session->set('productList', $uri, 'product');
        $this->session->set('productPlanList', $uri, 'product');

        $this->app->loadClass('pager', true);
        $count = isset($block->params->count) ? (int)$block->params->count : 0;
        $pager = pager::init(0, $count , 1);

        $this->view->products = $this->loadModel('product')->getPairs();
        $this->view->plans    = $this->loadModel('productplan')->getList(0, 0, 'all', $pager, 'begin_desc', 'noproduct');
    }

    /**
     * 打印产品发布列表区块数据。
     * Print releases block.
     *
     * @param  object    $block
     * @access protected
     * @return void
     */
    protected function printReleaseBlock(object $block): void
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
            ->beginIF($block->dashboard != 'my' && $this->session->project)->andWhere('t1.project')->eq((int)$this->session->project)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t1.product')->in($this->app->user->view->products)->fi()
            ->orderBy('t1.id desc')
            ->beginIF($this->viewType != 'json')->limit((int)$block->params->count)->fi()
            ->fetchAll();
    }

    /**
     * 打印产品统计区块数据。
     * Print product release statistic block.
     *
     * @param  object    $block
     * @access protected
     * @return void
     */
    protected function printReleaseStatisticBlock(object $block): void
    {
        $years  = array();
        $months = array();
        $groups = array();
        for($i = 5; $i >= 0; $i --)
        {
            $years[date('Y', strtotime("first day of -{$i} month"))] = date('Y', strtotime("first day of -{$i} month"));
            $months[date('m', strtotime("first day of -{$i} month"))] = date('m', strtotime("first day of -{$i} month"));
            $groups[date('Y-m', strtotime("first day of -{$i} month"))] = date('Y-m', strtotime("first day of -{$i} month"));
        }
        $monthRelease = $this->loadModel('metric')->getResultByCode('count_of_monthly_created_release', array('year' => join(',', $years), 'month' => join(',', $months)));

        $products      = $this->loadModel('product')->getOrderedProducts('all');
        $productIdList = array_keys($products);
        $releaseGroup  = $this->metric->getResultByCode('count_of_annual_created_release_in_product', array('product' => join(',', $productIdList), 'year' => date('Y')));

        foreach($groups as $group)
        {
            $releaseData[$group]  = 0;
            if(!empty($monthRelease))
            {
                foreach($monthRelease as $release)
                {
                    if($group == "{$release['year']}-{$release['month']}") $releaseData[$group] = $release['value'];
                }
            }
        }

        $releases = array();
        foreach($products as $product)
        {
            $releases[$product->name] = 0;
            if(!empty($releaseGroup))
            {
                foreach($releaseGroup as $release)
                {
                    if($product->id == $release['product']) $releases[$product->name] = $release['value'];
                }
            }
        }
        arsort($releases);

        $this->view->releaseData = $releaseData;
        $this->view->releases    = $releases;
    }

    /**
     * Print Build block.
     *
     * @param  object    $block
     * @access protected
     * @return void
     */
    protected function printBuildBlock(object $block): void
    {
        $this->session->set('buildList', $this->createLink('my', 'index'), 'execution');
        $this->app->loadLang('build');

        $builds = $this->dao->select('t1.*, t2.name AS productName, t2.shadow, t3.name AS projectName')->from(TABLE_BUILD)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t1.project=t3.id')
            ->where('t1.deleted')->eq('0')
            ->beginIF(!$this->app->user->admin)->andWhere('t1.execution')->in($this->app->user->view->sprints)->fi()
            ->beginIF($block->dashboard != 'my' && $this->session->project)->andWhere('t1.project')->eq((int)$this->session->project)->fi()
            ->orderBy('t1.id desc')
            ->beginIF($this->viewType != 'json')->limit((int)$block->params->count)->fi()
            ->fetchAll();

        $this->view->builds = $builds;
    }

    /**
     * Print project block.
     *
     * @param  object    $block
     * @access protected
     * @return void
     */
    protected function printProjectBlock(object $block): void
    {
        $this->app->loadLang('execution');
        $this->app->loadLang('task');
        $count   = isset($block->params->count)   ? $block->params->count   : 15;
        $type    = isset($block->params->type)    ? $block->params->type    : 'all';
        $orderBy = isset($block->params->orderBy) ? $block->params->orderBy : 'id_desc';

        $projects = $this->loadModel('project')->getOverviewList($type, 0, $orderBy, $count);

        /* Get all tasks and compute totalEstimate, totalConsumed, totalLeft, progress according to them. */
        $tasks = $this->dao->select('id, project, estimate, consumed, `left`, status, closedReason, execution')
            ->from(TABLE_TASK)
            ->where('project')->in(array_keys($projects))
            ->andWhere('parent')->lt(1)
            ->andWhere('deleted')->eq(0)
            ->fetchGroup('project', 'id');
        $hours = $this->loadModel('program')->computeProjectHours($tasks);

        $projects = $this->program->appendStatToProjects($projects, 'hours', array('hours' => $hours));
        foreach($projects as $project) $project->progress = $project->hours->progress;

        $this->view->projects = $projects;
        $this->view->users    = $this->loadModel('user')->getPairs('noletter');
    }

    /**
     * Print product block.
     *
     * @param  object    $block
     * @access protected
     * @return void
     */
    protected function printProductListBlock(object $block): void
    {
        $this->app->loadClass('pager', true);
        $count = isset($block->params->count) ? (int)$block->params->count : 0;
        $type  = isset($block->params->type) ? $block->params->type : '';
        $pager = pager::init(0, $count , 1);

        $products = $this->loadModel('product')->getList(0, $type);
        $productStats = $this->product->getStats(array_keys($products), 'order_desc', $this->viewType != 'json' ? $pager : '');
        $this->view->productStats = $productStats;

        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->avatarList = $this->user->getAvatarPairs();
    }

    /**
     * Get data of the project overview block.
     *
     * @param  object $block
     * @access protected
     * @return void
     */
    protected function printProjectOverviewBlock(object $block): void
    {
        $this->loadModel('metric');

        /* 通过度量项获取项目总量数据。 */
        $projectCount = 0;
        $projectCountGroup = $this->metric->getResultByCode('count_of_project');
        if(!empty($projectCountGroup))
        {
            $projectCountGroup = reset($projectCountGroup);
            $projectCount      = zget($projectCountGroup, 'value', 0);
        }

        /* 通过度量项获取最近三年完成的项目数据。 */
        $years = array();
        $i     = 3;
        foreach(array('lastTwoYear', 'lastYear', 'thisYear') as $year)
        {
            $i --;
            $years[$year] = date('Y', strtotime("first day of -{$i} year"));
        }
        $finishedProjectGroup = $this->metric->getResultByCode('count_of_annual_finished_project', array('year' => join(',', $years)));
        if($finishedProjectGroup) $finishedProjectGroup = array_column($finishedProjectGroup, null, 'year');

        /* 组装前台所需的cards数组。 */
        $cards = array();
        $cards[0] = new stdclass();
        $cards[0]->value = $projectCount;
        $cards[0]->class = 'text-primary';
        $cards[0]->label = $this->lang->block->projectoverview->totalProject;
        $cards[0]->url   = common::hasPriv('project', 'browse') ? helper::createLink('project', 'browse', 'programID=0&browseType=all') : null;

        $cards[1] = new stdclass();
        $cards[1]->value = isset($finishedProjectGroup[date('Y')]['value']) ? $finishedProjectGroup[date('Y')]['value'] : 0;
        $cards[1]->label = $this->lang->block->projectoverview->thisYear;

        $cardGroup = new stdclass();
        $cardGroup->type  = 'cards';
        $cardGroup->cards = $cards;

        /* 获取最近三年最多完成的项目数量。 */
        $maxCount = 0;
        if($finishedProjectGroup)
        {
            foreach($finishedProjectGroup as $data)
            {
                if($maxCount < $data['value']) $maxCount = $data['value'];
            }

        }

        /* 组将前台柱状图所需的bars数组。 */
        $bars = array();
        foreach($years as $code => $year)
        {
            $bar = new stdclass();
            $bar->label = $year;
            $bar->value = isset($finishedProjectGroup[$year]['value']) ? $finishedProjectGroup[$year]['value'] : 0;;
            $bar->rate  = $maxCount ? round($bar->value / $maxCount * 100) . '%' : '0%';

            $bars[] = $bar;
        }

        $barGroup = new stdclass();
        $barGroup->type  = 'barChart';
        $barGroup->title = $this->lang->block->projectoverview->lastThreeYear;
        $barGroup->bars  = $bars;

        $this->view->groups = array($cardGroup, $barGroup);
    }

    /**
     * Print project statistic block.
     *
     * @param  object    $block
     * @access protected
     * @return void
     */
    protected function printProjectStatisticBlock(object $block): void
    {
        if(!empty($block->params->type) && preg_match('/[^a-zA-Z0-9_]/', $block->params->type)) return;

        /* Set project status and count. */
        $status = isset($block->params->type)  ? $block->params->type       : 'all';
        $count  = isset($block->params->count) ? (int)$block->params->count : 15;

        /* Get projects. */
        $excludedModel = $this->config->edition == 'max' ? '' : 'waterfall';
        $projects      = $this->loadModel('project')->getProjectList($status, 'order_asc', $count, $excludedModel);
        $projectIdList = array_keys($projects);

        $this->loadModel('metric');
        $riskCountGroup    = $this->metric->getResultByCode('count_of_opened_risk_in_project',  array('project' => join(',', $projectIdList)));
        $issueCountGroup   = $this->metric->getResultByCode('count_of_opened_issue_in_project', array('project' => join(',', $projectIdList)));
        /* 敏捷项目的统计信息。 */
        $investedGroup      = $this->metric->getResultByCode('day_of_invested_in_project',         array('project' => join(',', $projectIdList)));
        $consumeTaskGroup   = $this->metric->getResultByCode('consume_of_task_in_project',         array('project' => join(',', $projectIdList)));
        $leftTaskGroup      = $this->metric->getResultByCode('left_of_task_in_project',            array('project' => join(',', $projectIdList)));
        $countStoryGroup    = $this->metric->getResultByCode('scale_of_story_in_project',          array('project' => join(',', $projectIdList)));
        $finishedStoryGroup = $this->metric->getResultByCode('count_of_finished_story_in_project', array('project' => join(',', $projectIdList)));
        $unclosedStoryGroup = $this->metric->getResultByCode('count_of_unclosed_story_in_project', array('project' => join(',', $projectIdList)));
        $countTaskGroup     = $this->metric->getResultByCode('count_of_task_in_project',           array('project' => join(',', $projectIdList)));
        $waitTaskGroup      = $this->metric->getResultByCode('count_of_wait_task_in_project',      array('project' => join(',', $projectIdList)));
        $doingTaskGroup     = $this->metric->getResultByCode('count_of_doing_task_in_project',     array('project' => join(',', $projectIdList)));
        $countBugGroup      = $this->metric->getResultByCode('count_of_bug_in_project',            array('project' => join(',', $projectIdList)));
        $closedBugGroup     = $this->metric->getResultByCode('count_of_closed_bug_in_project ',    array('project' => join(',', $projectIdList)));
        $activatedBugGroup  = $this->metric->getResultByCode('count_of_activated_bug_in_project',  array('project' => join(',', $projectIdList)));

        if($investedGroup)      $investedGroup      = array_column($investedGroup,      null, 'project');
        if($consumeTaskGroup)   $consumeTaskGroup   = array_column($consumeTaskGroup,   null, 'project');
        if($leftTaskGroup)      $leftTaskGroup      = array_column($leftTaskGroup,      null, 'project');
        if($countStoryGroup)    $countStoryGroup    = array_column($countStoryGroup,    null, 'project');
        if($finishedStoryGroup) $finishedStoryGroup = array_column($finishedStoryGroup, null, 'project');
        if($unclosedStoryGroup) $unclosedStoryGroup = array_column($unclosedStoryGroup, null, 'project');
        if($countTaskGroup)     $countTaskGroup     = array_column($countTaskGroup,     null, 'project');
        if($waitTaskGroup)      $waitTaskGroup      = array_column($waitTaskGroup,      null, 'project');
        if($doingTaskGroup)     $doingTaskGroup     = array_column($doingTaskGroup,     null, 'project');
        if($countBugGroup)      $countBugGroup      = array_column($countBugGroup,      null, 'project');
        if($closedBugGroup)     $closedBugGroup     = array_column($closedBugGroup,     null, 'project');
        if($activatedBugGroup)  $activatedBugGroup  = array_column($activatedBugGroup,  null, 'project');

        /* 瀑布项目的统计信息。 */
        $taskProgressGroup = $this->metric->getResultByCode('progress_of_task_in_project',      array('project' => join(',', $projectIdList)));
        $SVGroup           = $this->metric->getResultByCode('sv_in_waterfall',                  array('project' => join(',', $projectIdList)));
        $PVGroup           = $this->metric->getResultByCode('pv_of_task_in_waterfall',          array('project' => join(',', $projectIdList)));
        $EVGroup           = $this->metric->getResultByCode('ev_of_finished_task_in_waterfall', array('project' => join(',', $projectIdList)));
        $CVGroup           = $this->metric->getResultByCode('cv_in_waterfall',                  array('project' => join(',', $projectIdList)));
        $ACGroup           = $this->metric->getResultByCode('ac_of_all_in_waterfall',           array('project' => join(',', $projectIdList)));

        if($riskCountGroup)    $riskCountGroup    = array_column($riskCountGroup,    null, 'project');
        if($issueCountGroup)   $issueCountGroup   = array_column($issueCountGroup,   null, 'project');
        if($taskProgressGroup) $taskProgressGroup = array_column($taskProgressGroup, null, 'project');
        if($SVGroup)           $SVGroup           = array_column($SVGroup,           null, 'project');
        if($PVGroup)           $PVGroup           = array_column($PVGroup,           null, 'project');
        if($EVGroup)           $EVGroup           = array_column($EVGroup,           null, 'project');
        if($CVGroup)           $CVGroup           = array_column($CVGroup,           null, 'project');
        if($ACGroup)           $ACGroup           = array_column($ACGroup,           null, 'project');

        /* 将获取的统计信息按照projectID补充到projects数组中。 */
        $this->loadModel('execution');
        $this->app->loadClass('pager', true);
        $pager  = pager::init(0, 3, 1);
        $today  = helper::today();
        foreach($projects as $projectID => $project)
        {
            $project->progress = isset($taskProgressGroup[$projectID]['value']) ? $taskProgressGroup[$projectID]['value'] * 100 : 0;
            if(in_array($project->model, array('scrum', 'kanban', 'agileplus')))
            {
                $project->executions   = $this->execution->getStatData($projectID, 'all', 0, 0, false, '', 'id_desc', $pager);
                $project->costs        = isset($investedGroup[$projectID]['value'])      ? $investedGroup[$projectID]['value']      : 0;
                $project->consumed     = isset($consumeTaskGroup[$projectID]['value'])   ? $consumeTaskGroup[$projectID]['value']   : 0;
                $project->remainder    = isset($leftTaskGroup[$projectID]['value'])      ? $leftTaskGroup[$projectID]['value']      : 0;
                $project->storyPoints  = isset($countStoryGroup[$projectID]['value'])    ? $countStoryGroup[$projectID]['value']    : 0;
                $project->done         = isset($finishedStoryGroup[$projectID]['value']) ? $finishedStoryGroup[$projectID]['value'] : 0;
                $project->undone       = isset($unclosedStoryGroup[$projectID]['value']) ? $unclosedStoryGroup[$projectID]['value'] : 0;
                $project->tasks        = isset($countTaskGroup[$projectID]['value'])     ? $countTaskGroup[$projectID]['value']     : 0;
                $project->wait         = isset($waitTaskGroup[$projectID]['value'])      ? $waitTaskGroup[$projectID]['value']      : 0;
                $project->doing        = isset($doingTaskGroup[$projectID]['value'])     ? $doingTaskGroup[$projectID]['value']     : 0;
                $project->bugs         = isset($countBugGroup[$projectID]['value'])      ? $countBugGroup[$projectID]['value']      : 0;
                $project->resolvedDate = isset($closedBugGroup[$projectID]['value'])     ? $closedBugGroup[$projectID]['value']     : 0;
                $project->active       = isset($activatedBugGroup[$projectID]['value'])  ? $activatedBugGroup[$projectID]['value']  : 0;
            }
            elseif(in_array($project->model, array('waterfall', 'waterfallplus')))
            {
                $project->pv = isset($PVGroup[$projectID]['value']) ? $PVGroup[$projectID]['value'] * 100 : 0;
                $project->ev = isset($EVGroup[$projectID]['value']) ? $EVGroup[$projectID]['value'] * 100 : 0;
                $project->ac = isset($ACGroup[$projectID]['value']) ? $ACGroup[$projectID]['value'] * 100 : 0;
                $project->sv = isset($SVGroup[$projectID]['value']) ? $SVGroup[$projectID]['value'] * 100 : 0;
                $project->cv = isset($CVGroup[$projectID]['value']) ? $CVGroup[$projectID]['value'] * 100 : 0;
            }
            if($project->end != LONG_TIME) $project->remainingDays = helper::diffDate($project->end, $today);
        }

        $this->view->projects = $projects;
        $this->view->users    = $this->loadModel('user')->getPairs('noletter');
    }

    /**
     * 打印产品统计区块。
     * Print product statistic block.
     *
     * @param  object    $block
     * @access protected
     * @return bool
     */
    protected function printProductStatisticBlock(object $block): void
    {
        /* 获取需要统计的产品列表。 */
        /* Obtain a list of products that require statistics. */
        $status         = isset($block->params->type)  ? $block->params->type  : '';
        $count          = isset($block->params->count) ? $block->params->count : '';
        $products       = $this->loadModel('product')->getOrderedProducts($status, (int)$count);
        $productIdList  = array_keys($products);

        /* 按照产品分组获取产品需求交付率度量项。 */
        $storyDeliveryRate = $this->loadModel('metric')->getResultByCode('rate_of_delivery_story_in_product', array('product' => join(',', $productIdList)));

        /* 按照产品分组获取产品有效需求数度量项。 */
        $totalStories = $this->metric->getResultByCode('count_of_valid_story_in_product', array('product' => join(',', $productIdList)));

        /* 按照产品分组获取产品已交付需求数度量项。 */
        $closedStories = $this->metric->getResultByCode('count_of_delivered_story_in_product', array('product' => join(',', $productIdList)));

        /* 按照产品分组获取产品未关闭需求数度量项。 */
        $unclosedStories = $this->metric->getResultByCode('count_of_unclosed_story_in_product', array('product' => join(',', $productIdList)));

        if(!empty($storyDeliveryRate)) $storyDeliveryRate = array_column($storyDeliveryRate, null, 'product');
        if(!empty($totalStories))      $totalStories      = array_column($totalStories,      null, 'product');
        if(!empty($closedStories))     $closedStories     = array_column($closedStories,     null, 'product');
        if(!empty($unclosedStories))   $unclosedStories   = array_column($unclosedStories,   null, 'product');

        /* 按照产品和日期分组获取产品每月新增和完成的需求数度量项。 */
        $years  = array();
        $months = array();
        $groups = array();
        for($i = 5; $i >= 0; $i --)
        {
            $years[date('Y', strtotime("first day of -{$i} month"))] = date('Y', strtotime("first day of -{$i} month"));
            $months[date('m', strtotime("first day of -{$i} month"))] = date('m', strtotime("first day of -{$i} month"));
            $groups[date('Y-m', strtotime("first day of -{$i} month"))] = date('Y-m', strtotime("first day of -{$i} month"));
        }
        $monthFinish  = $this->metric->getResultByCode('count_of_monthly_finished_story_in_product', array('product' => join(',', $productIdList), 'year' => join(',', $years), 'month' => join(',', $months)));
        $monthCreated = $this->metric->getResultByCode('count_of_monthly_created_story_in_product', array('product' => join(',', $productIdList), 'year' => join(',', $years), 'month' => join(',', $months)));

        /* 根据产品列表获取预计开始日期距离现在最近且预计开始日期大于当前日期的未开始状态计划。 */
        /* Obtain an unstarted status plan based on the product list, with an expected start date closest to the current date and an expected start date greater than the current date. */
        $newPlan = $this->dao->select('*')->from(TABLE_PRODUCTPLAN)
            ->where('deleted')->eq('0')
            ->andWhere('product')->in($productIdList)
            ->andWhere('begin')->ge(date('Y-m-01'))
            ->andWhere('status')->eq('wait')
            ->orderBy('begin_desc')
            ->fetchGroup('product', 'product');

        /* 根据产品列表获取实际开始日期距离当前最近的进行中状态的执行。 */
        /* Obtain the execution of the current in progress status closest to the actual start date based on the product list. */
        $newExecution = $this->dao->select('execution.*,relation.product')->from(TABLE_EXECUTION)->alias('execution')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('relation')->on('execution.id=relation.project')
            ->where('execution.deleted')->eq('0')
            ->andWhere('execution.type')->eq('sprint')
            ->andWhere('relation.product')->in($productIdList)
            ->andWhere('execution.status')->eq('doing')
            ->orderBy('realBegan_asc')
            ->fetchGroup('product', 'product');

        /* 根据产品列表获取发布日期距离现在最近且发布日期小于当前日期的发布。 */
        /* Retrieve releases with the latest release date from the product list and a release date earlier than the current date. */
        $newRelease = $this->dao->select('*')->from(TABLE_RELEASE)
            ->where('deleted')->eq('0')
            ->andWhere('product')->in($productIdList)
            ->andWhere('date')->lt(date('Y-m-01'))
            ->orderBy('date_asc')
            ->fetchGroup('product', 'product');

        /* 将按照产品分组的统计数据放入产品列表中。 */
        /* Place statistical data grouped by product into the product list. */
        foreach($products as $productID => $product)
        {
            $product->storyDeliveryRate = isset($storyDeliveryRate[$productID]['value']) ? $storyDeliveryRate[$productID]['value'] * 100 : 0;
            $product->totalStories      = isset($totalStories[$productID]['value']) ? $totalStories[$productID]['value'] : 0;
            $product->closedStories     = isset($closedStories[$productID]['value']) ? $closedStories[$productID]['value'] : 0;
            $product->unclosedStories   = isset($unclosedStories[$productID]['value']) ? $unclosedStories[$productID]['value'] : 0;
            $product->newPlan           = isset($newPlan[$productID][$productID]) ? $newPlan[$productID][$productID] : '';
            $product->newExecution      = isset($newExecution[$productID][$productID]) ? $newExecution[$productID][$productID] : '';
            $product->newRelease        = isset($newRelease[$productID][$productID]) ? $newRelease[$productID][$productID] : '';

            foreach($groups as $group)
            {
                $product->monthFinish[$group]  = 0;
                $product->monthCreated[$group] = 0;
                if(!empty($monthFinish))
                {
                    foreach($monthFinish as $story)
                    {
                        if($group == "{$story['year']}-{$story['month']}" && $productID == $story['product']) $product->monthFinish[$group] = $story['value'];
                    }
                }
                if(!empty($monthCreated))
                {
                    foreach($monthCreated as $story)
                    {
                        if($group == "{$story['year']}-{$story['month']}" && $productID == $story['product']) $product->monthCreated[$group] = $story['value'];
                    }
                }
            }
        }

        $this->view->products = $products;
    }

    /**
     * Print execution statistic block.
     *
     * @param  object    $block
     * @param  array     $params
     * @access protected
     * @return void
     */
    protected function printExecutionStatisticBlock(object $block, array $params = array()): void
    {
        if(!empty($block->params->type) && preg_match('/[^a-zA-Z0-9_]/', $block->params->type)) return;

        $this->app->loadLang('task');
        $this->app->loadLang('story');
        $this->app->loadLang('bug');

        $status  = isset($block->params->type)  ? $block->params->type : 'undone';
        $count   = isset($block->params->count) ? (int)$block->params->count : 0;

        /* Get projects. */
        $projectID = $block->dashboard == 'my' ? 0 : (int)$this->session->project;
        if(isset($params['project'])) $projectID = (int)$params['project'];
        $executions = $this->loadModel('execution')->getOrderedExecutions($projectID, $status, $count, 'skipparent');
        if(empty($executions))
        {
            $this->view->executions = $executions;
            return;
        }

        $executionIdList = array_keys($executions);

        /* Get tasks. Fix bug #2918.*/
        $yesterday  = date('Y-m-d', strtotime('-1 day'));
        $taskGroups = $this->dao->select('id,parent,execution,status,finishedDate,estimate,consumed,`left`')->from(TABLE_TASK)
            ->where('execution')->in($executionIdList)
            ->andWhere('deleted')->eq(0)
            ->fetchGroup('execution', 'id');

        foreach($taskGroups as $executionID => $taskGroup)
        {
            $undoneTasks       = 0;
            $yesterdayFinished = 0;
            $totalEstimate     = 0;
            $totalConsumed     = 0;
            $totalLeft         = 0;

            foreach($taskGroup as $task)
            {
                if(strpos('wait|doing|pause|cancel', $task->status) !== false) $undoneTasks ++;
                if($task->finishedDate && strpos($task->finishedDate, $yesterday) !== false) $yesterdayFinished ++;

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

        $this->view->executions       = $executions;
        $this->view->projects         = $this->loadModel('project')->getPairs();
        $this->view->currentProjectID = $projectID;
    }

    /**
     * Print waterfall report block.
     *
     * @access protected
     * @return void
     */
    protected function printWaterfallReportBlock(): void
    {
        $this->app->loadLang('programplan');
        $project = $this->loadModel('project')->getByID($this->session->project);
        $today   = helper::today();
        $date    = date('Ymd', strtotime('this week Monday'));
        $begin   = $project->begin;
        $weeks   = $this->loadModel('weekly')->getWeekPairs($begin);
        $current = zget($weeks, $date, '');

        $this->weekly->save($this->session->project, $date);

        $pvAndev = $this->weekly->getPVEV($this->session->project, $today);
        $this->view->pv = (float)$pvAndev['PV'];
        $this->view->ev = (float)$pvAndev['EV'];
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
    protected function printWaterfallGeneralReportBlock(): void
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
    protected function printWaterfallGanttBlock(): void
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
    protected function printWaterfallIssueBlock(): void
    {
        $this->printIssueBlock();
    }

    /**
     * Print waterfall risk block.
     *
     * @access protected
     * @return void
     */
    protected function printWaterfallRiskBlock(): void
    {
        $this->printRiskBlock();
    }

    /**
     * Print waterfall estimate block.
     *
     * @access protected
     * @return void
     */
    protected function printWaterfallEstimateBlock(): void
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
    protected function printWaterfallProgressBlock(): void
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
    protected function printScrumOverviewBlock(): void
    {
        $projectID = $this->session->project;
        $this->app->loadLang('bug');
        $totalData = $this->loadModel('project')->getOverviewList('', $projectID, 'id_desc', 1);

        $project = zget($totalData, $projectID, new stdclass());
        $this->app->loadClass('pager', true);
        $pager = pager::init(0, 3, 1);
        $project->progress   = $project->allStories == 0 ? 0 : round($project->doneStories / $project->allStories, 3) * 100;
        $project->executions = $this->loadModel('execution')->getStatData($projectID, 'all', 0, 0, false, '', 'id_desc', $pager);

        $this->view->totalData = $totalData;
        $this->view->projectID = $projectID;
        $this->view->project   = $project;
    }

    /**
     * Print srcum project list block.
     *
     * @param  object    $block
     * @access protected
     * @return void
     */
    protected function printScrumListBlock(object $block): void
    {
        if(!empty($block->params->type) && preg_match('/[^a-zA-Z0-9_]/', $block->params->type)) return;
        $count = isset($block->params->count) ? (int)$block->params->count : 15;
        $type  = isset($block->params->type) ? $block->params->type : 'undone';

        $this->app->loadClass('pager', true);
        $pager = pager::init(0, $count, 1);
        $this->loadModel('execution');
        $this->view->executionStats = !commonModel::isTutorialMode() ? $this->execution->getStatData($this->session->project, $type, 0, 0, false, '', 'id_desc', $pager) : array($this->loadModel('tutorial')->getExecution());
    }

    /**
     * Print srcum product block.
     *
     * @param  object    $block
     * @access protected
     * @return void
     */
    protected function printScrumProductBlock(object $block): void
    {
        $stories  = array();
        $bugs     = array();
        $releases = array();
        $count    = isset($block->params->count) ? (int)$block->params->count : 15;

        $products      = $this->dao->select('id, name')->from(TABLE_PRODUCT)->where('program')->eq($this->session->program)->limit($count)->fetchPairs();
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
    protected function printScrumIssueBlock(): void
    {
        $this->printIssueBlock();
    }

    /**
     * Print scrum risk block.
     *
     * @access protected
     * @return void
     */
    protected function printScrumRiskBlock(): void
    {
        $this->printRiskBlock();
    }

    /**
     * Print issue block.
     *
     * @param  object    $block
     * @access protected
     * @return void
     */
    private function printIssueBlock(object $block): void
    {
        $uri = $this->app->tab == 'my' ? $this->createLink('my', 'index') : $this->server->http_referer;
        $this->session->set('issueList', $uri, 'project');
        if(preg_match('/[^a-zA-Z0-9_]/', $block->params->type)) return;
        $this->view->users  = $this->loadModel('user')->getPairs('noletter');
        $this->view->issues = $this->loadModel('issue')->getBlockIssues($this->session->project, $block->params->type, $this->viewType == 'json' ? 0 : (int)$block->params->count, $block->params->orderBy);
    }

    /**
     * Print risk block.
     *
     * @param  object    $block
     * @access protected
     * @return void
     */
    private function printRiskBlock(object $block): void
    {
        $uri = $this->app->tab == 'my' ? $this->createLink('my', 'index') : $this->server->http_referer;
        $this->session->set('riskList', $uri, 'project');
        $this->view->users = $this->loadModel('user')->getPairs('noletter');
        $this->view->risks = $this->loadModel('risk')->getBlockRisks($this->session->project, $block->params->type, $this->viewType == 'json' ? 0 : (int)$block->params->count, $block->params->orderBy);
    }

    /**
     * Print sprint block.
     *
     * @param  object $block
     * @access protected
     * @return void
     */
    protected function printSprintBlock(object $block): void
    {
        $this->printExecutionOverviewBlock($block, array(), 'sprint', (int)$this->session->project, true);
    }

    /**
     * Print project dynamic block.
     *
     * @param  object    $block
     * @access protected
     * @return void
     */
    protected function printProjectDynamicBlock(object $block): void
    {
        $projectID = $this->session->project;
        $count     = isset($block->params->count) ? (int)$block->params->count : 10;

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager(0, $count, 1);

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
    protected function printScrumRoadMapBlock($productID = 0, $roadMapID = 0): void
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
     * @param  object    $block
     * @access protected
     * @return void
     */
    protected function printScrumTestBlock(object $block): void
    {
        $uri = $this->app->tab == 'my' ? $this->createLink('my', 'index') : $this->server->http_referer;
        $this->session->set('testtaskList', $uri, 'qa');
        $this->session->set('productList',  $uri, 'product');
        $this->session->set('projectList',  $uri, 'project');
        $this->session->set('buildList',    $uri, 'execution');
        $this->app->loadLang('testtask');

        $count  = zget($block->params, 'count', 10);
        $status = isset($block->params->type)  ? $block->params->type : 'wait';

        $this->view->project   = $this->loadModel('project')->getByID($this->session->project);
        $this->view->testtasks = $this->dao->select("t1.*,t2.name as productName,t3.name as buildName,t4.name as projectName, CONCAT(t4.name, '/', t3.name) as executionBuild")
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
     * @param  object    $block
     * @access protected
     * @return void
     */
    protected function printQaStatisticBlock(object $block): void
    {
        if(!empty($block->params->type) && preg_match('/[^a-zA-Z0-9_]/', $block->params->type)) return;

        $this->app->loadLang('bug');
        $status = isset($block->params->type)  ? $block->params->type : '';
        $count  = isset($block->params->count) ? (int)$block->params->count : 0;

        $projectID     = $this->lang->navGroup->qa == 'project' ? $this->session->project : 0;
        $products      = $this->loadModel('product')->getOrderedProducts($status, $count, $projectID, 'all');
        $productIdList = array_keys($products);

        $this->loadModel('metric');
        $years  = array();
        $months = array();
        for($i = 0; $i <= 1; $i ++)
        {
            $years[] = date('Y', strtotime("-{$i} day"));
            $months[] = date('m', strtotime("-{$i} day"));
        }
        $createdBugGroup  = $this->metric->getResultByCode('count_of_daily_created_bug_in_product',  array('product' => join(',', $productIdList), 'year' => join(',', $years), 'month' => join(',', $months)));
        $resolvedBugGroup = $this->metric->getResultByCode('count_of_daily_resolved_bug_in_product', array('product' => join(',', $productIdList), 'year' => join(',', $years), 'month' => join(',', $months)));
        $closedBugGroup   = $this->metric->getResultByCode('count_of_daily_closed_bug_in_product',   array('product' => join(',', $productIdList), 'year' => join(',', $years), 'month' => join(',', $months)));
        $bugFixRate       = $this->metric->getResultByCode('rate_of_fixed_bug_in_product',           array('product' => join(',', $productIdList)));
        $effectiveBug     = $this->metric->getResultByCode('count_of_effective_bug_in_product',      array('product' => join(',', $productIdList)));
        $restoredBug      = $this->metric->getResultByCode('count_of_restored_bug_in_product',       array('product' => join(',', $productIdList)));
        $activatedBug     = $this->metric->getResultByCode('count_of_activated_bug_in_product',      array('product' => join(',', $productIdList)));

        if(!empty($bugFixRate))   $bugFixRate   = array_column($bugFixRate,   null, 'product');
        if(!empty($effectiveBug)) $effectiveBug = array_column($effectiveBug, null, 'product');
        if(!empty($restoredBug))  $restoredBug  = array_column($restoredBug,  null, 'product');
        if(!empty($activatedBug)) $activatedBug = array_column($activatedBug, null, 'product');

        foreach($products as $productID => $product)
        {
            $product->addToday          = 0;
            $product->addYesterday      = 0;
            $product->resolvedToday     = 0;
            $product->resolvedYesterday = 0;
            $product->closedToday       = 0;
            $product->closedYesterday   = 0;
            if(!empty($createdBugGroup))
            {
                foreach($createdBugGroup as $data)
                {
                    $currentDay = "{$data['year']}-{$data['month']}-{$data['day']}";
                    if($currentDay == date('Y-m-d') && $productID == $data['product'])                      $product->addToday     = $data['value'];
                    if($currentDay == date('Y-m-d', strtotime("-1 day")) && $productID == $data['product']) $product->addYesterday = $data['value'];
                }
            }

            if(!empty($resolvedBugGroup))
            {
                foreach($resolvedBugGroup as $data)
                {
                    $currentDay = "{$data['year']}-{$data['month']}-{$data['day']}";
                    if($currentDay == date('Y-m-d') && $productID == $data['product'])                      $product->resolvedToday     = $data['value'];
                    if($currentDay == date('Y-m-d', strtotime("-1 day")) && $productID == $data['product']) $product->resolvedYesterday = $data['value'];
                }
            }

            if(!empty($closedBugGroup))
            {
                foreach($closedBugGroup as $data)
                {
                    $currentDay = "{$data['year']}-{$data['month']}-{$data['day']}";
                    if($currentDay == date('Y-m-d') && $productID == $data['product'])                      $product->closedToday     = $data['value'];
                    if($currentDay == date('Y-m-d', strtotime("-1 day")) && $productID == $data['product']) $product->closedYesterday = $data['value'];
                }
            }

            $product->closedBugRate = isset($bugFixRate[$productID]['value'])   ? $bugFixRate[$productID]['value'] * 100 : 0;
            $product->totalBug      = isset($effectiveBug[$productID]['value']) ? $effectiveBug[$productID]['value']     : 0;
            $product->closedBug     = isset($restoredBug[$productID]['value'])  ? $restoredBug[$productID]['value']      : 0;
            $product->activatedBug  = isset($activatedBug[$productID]['value']) ? $activatedBug[$productID]['value']     : 0;
        }

        $this->view->products = $products;
    }

    /**
     * 传递产品总览区块页面数据。
     * Transfer product overview block page data.
     *
     * @param  object $block
     * @param  array  $params
     * @access protected
     * @return bool
     */
    protected function printProductOverviewBlock(object $block, array $params = array()): void
    {
        if($block->width == 1) $this->printShortProductOverview();
        if($block->width == 3) $this->printLongProductOverview($params);
    }

    /**
     * 传递产品总览短区块页面数据。
     * Transfer short product overview block page data.
     *
     * @access protected
     * @return void
     */
    protected function printShortProductOverview(): void
    {
        $data = new stdclass();
        $data->productCount   = 0;
        $data->releaseCount   = 0;
        $data->milestoneCount = 0;

        $this->loadModel('metric');
        $productCount = $this->metric->getResultByCode('count_of_product');
        if(!empty($productCount))
        {
            $productCount = reset($productCount);
            $data->productCount = zget($productCount, 'value', 0);
        }

        $releaseCount = $this->metric->getResultByCode('count_of_annual_created_release', array('year' => date('Y')));
        if(!empty($releaseCount))
        {
            $releaseCount = reset($releaseCount);
            $data->releaseCount = zget($releaseCount, 'value', 0);
        }

        $milestoneCount = $this->metric->getResultByCode('count_of_marker_release');
        if(!empty($milestoneCount))
        {
            $milestoneCount = reset($milestoneCount);
            $data->milestoneCount = zget($milestoneCount, 'value', 0);
        }

        $this->view->data = $data;
    }

    /**
     * 传递产品总览长区块页面数据。
     * Transfer long product overview block page data.
     *
     * @param  array $params
     * @access protected
     * @return void
     */
    protected function printLongProductOverview(array $params = array()): void
    {
        $year = isset($params['year']) ? (int)$params['year'] : date('Y');

        /* 初始化报表数据为0。 */
        $data = new stdclass();
        $data->productLineCount             = 0;
        $data->productCount                 = 0;
        $data->unfinishedPlanCount          = 0;
        $data->unclosedStoryCount           = 0;
        $data->activeBugCount               = 0;
        $data->finishedReleaseCount['year'] = 0;
        $data->finishedReleaseCount['week'] = 0;
        $data->finishedStoryCount['year']   = 0;
        $data->finishedStoryCount['week']   = 0;
        $data->finishedStoryPoint['year']   = 0;
        $data->finishedStoryPoint['week']   = 0;

        /* 从度量项获取各统计数据。 */
        $this->loadModel('metric');
        $productLineCount = $this->metric->getResultByCode('count_of_line'); // 产品线总量。
        if(!empty($productLineCount))
        {
            $productLineCount = reset($productLineCount);
            $data->productLineCount = zget($productLineCount, 'value', 0);
        }

        $productCount = $this->metric->getResultByCode('count_of_product'); // 产品总量。
        if(!empty($productCount))
        {
            $productCount = reset($productCount);
            $data->productCount = zget($productCount, 'value', 0);
        }

        $unfinishedPlanCount = $this->metric->getResultByCode('count_of_unfinished_productplan'); // 未完成计划数。
        if(!empty($unfinishedPlanCount))
        {
            $unfinishedPlanCount = reset($unfinishedPlanCount);
            $data->unfinishedPlanCount = zget($unfinishedPlanCount, 'value', 0);
        }

        $unclosedStoryCount = $this->metric->getResultByCode('count_of_unclosed_story'); // 未关闭需求数。
        if(!empty($unclosedStoryCount))
        {
            $unclosedStoryCount = reset($unclosedStoryCount);
            $data->unclosedStoryCount = zget($unclosedStoryCount, 'value', 0);
        }

        $activeBugCount = $this->metric->getResultByCode('count_of_activated_bug'); // 激活Bug数。
        if(!empty($activeBugCount))
        {
            $activeBugCount = reset($activeBugCount);
            $data->activeBugCount = zget($activeBugCount, 'value', 0);
        }

        $createdReleaseGroup = $this->metric->getResultByCode('count_of_annual_created_release'); // 已完成发布数。
        $finishedStoryGroup  = $this->metric->getResultByCode('count_of_annual_finished_story'); // 已完成需求数。
        $storyScaleGroup     = $this->metric->getResultByCode('scale_of_annual_finished_story'); // 已完成需求规模。

        if($createdReleaseGroup) $createdReleaseGroup = array_column($createdReleaseGroup, null, 'year');
        if($finishedStoryGroup)  $finishedStoryGroup  = array_column($finishedStoryGroup,  null, 'year');
        if($storyScaleGroup)     $storyScaleGroup     = array_column($storyScaleGroup,     null, 'year');

        if(!empty($createdReleaseGroup[$year])) $data->finishedReleaseCount['year'] = zget($createdReleaseGroup[$year], 'value', 0);
        if(!empty($finishedStoryGroup[$year]))  $data->finishedStoryCount['year']   = zget($finishedStoryGroup[$year],  'value', 0);
        if(!empty($storyScaleGroup[$year]))     $data->finishedStoryPoint['year']   = zget($storyScaleGroup[$year],     'value', 0);

        /* 获取有数据的年份，默认显示今年。 */
        $years = array(date('Y') => 0);
        if($createdReleaseGroup || $finishedStoryGroup || $storyScaleGroup) $years = $years + $createdReleaseGroup + $finishedStoryGroup + $storyScaleGroup;

        $this->view->years       = array_keys($years);
        $this->view->currentYear = $year;
        $this->view->data        = $data;
    }

    /**
     * Print execution overview block.
     *
     * @param  object    $block
     * @param  string    $code          executionoverview|sprint
     * @param  array     $params
     * @param  int       $project
     * @param  bool      $showClosed    true|false
     * @access protected
     * @return void
     */
    protected function printExecutionOverviewBlock(object $block, array $params = array(), string $code = 'executionoverview', int $project = 0, bool $showClosed = false): void
    {
        $query = $this->dao->select('id, status, Year(closedDate) AS year')->from(TABLE_PROJECT)
            ->where('deleted')->eq('0')
            ->andWhere('type')->in('sprint, stage, kanban')
            ->andWhere('multiple')->eq('1')
            ->andWhere('vision')->eq($this->config->vision)
            ->beginIF($project)->andWhere('project')->eq($project)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->sprints)->fi();

        $statusPairs = $query->fetchPairs('id', 'status');
        $yearPairs   = $query->fetchPairs('id', 'year');
        $yearPairs   = array_map(function($year){return $year == null ? 0 : $year;}, $yearPairs);
        $statusStats = array_count_values($statusPairs);
        $yearStats   = array_count_values($yearPairs);

        $cards = array();
        $cards[0] = new stdclass();
        $cards[0]->value = array_sum($statusStats);
        $cards[0]->class = 'text-primary';
        $cards[0]->label = $this->lang->block->{$code}->totalExecution;

        $url = common::hasPriv('execution', 'all') ? helper::createLink('execution', 'all', 'status=all') : null;
        if($project) $url = common::hasPriv('project', 'execution') ? helper::createLink('project', 'execution', "status=all&projectID=$project") : null;
        $cards[0]->url = $url;

        $cards[1] = new stdclass();
        $cards[1]->value = zget($yearStats, date('Y'), 0);
        $cards[1]->label = $this->lang->block->{$code}->thisYear;

        $cardGroup = new stdclass();
        $cardGroup->type  = 'cards';
        $cardGroup->cards = $cards;

        $this->app->loadLang('execution');

        $max = 0;
        foreach($this->lang->execution->statusList as $status => $label)
        {
            if(!$showClosed && $status == 'closed') continue;

            $$status = zget($statusStats, $status, 0);
            if($max < $$status) $max = $$status;
        }

        $bars = array();
        foreach($this->lang->execution->statusList as $status => $label)
        {
            if(!$showClosed && $status == 'closed') continue;

            $bar = new stdclass();
            $bar->label = $label;
            $bar->value = $$status;
            $bar->rate  = $max ? round($$status / $max, 4) * 100 . '%' : '0%';

            $bars[] = $bar;
        }

        $barGroup = new stdclass();
        $barGroup->type  = 'barChart';
        $barGroup->title = $this->lang->block->{$code}->statusCount;
        $barGroup->bars  = $bars;

        $this->view->groups = array($cardGroup, $barGroup);
    }

    /**
     * Print qa overview block.
     *
     * @param  object    $block
     * @access protected
     * @return void
     */
    protected function printQaOverviewBlock(object $block): void
    {
        $casePairs = $this->dao->select('lastRunResult, COUNT(*) AS count')->from(TABLE_CASE)
            ->where('1=1')
            ->beginIF($block->module != 'my' && $this->session->project)->andWhere('project')->eq((int)$this->session->project)->fi()
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
     * @param  object    $block
     * @access protected
     * @return void
     */
    protected function printExecutionListBlock(object $block): void
    {
        if(!empty($block->params->type) && preg_match('/[^a-zA-Z0-9_]/', $block->params->type)) return;

        $count  = isset($block->params->count) ? (int)$block->params->count : 0;
        $status = isset($block->params->type)  ? $block->params->type : 'all';

        $this->loadModel('execution');
        $this->app->loadClass('pager', true);
        $pager = pager::init(0, $count, 1);

        $projectID = $block->module == 'my' ? 0 : (int)$this->session->project;

        $executions = $this->execution->getStatData($projectID, $status, 0, 0, false, 'skipParent', 'id_asc', $pager);
        if($executions)
        {
            foreach($executions as $execution)
            {
                $execution->totalEstimate  = $execution->hours->totalEstimate;
                $execution->totalLeft      = $execution->hours->totalLeft;
                $execution->progress       = $execution->hours->progress;
                $execution->burns          = !empty($execution->burns) ? join(',', $execution->burns) : array();
            }
        }
        $this->view->executions = $executions ? $executions : array();
    }

    /**
     * Print assign to me block.
     *
     * @param  object    $block
     * @access protected
     * @return void
     */
    protected function printAssignToMeBlock(object $block): void
    {
        $hasIssue   = helper::hasFeature('issue');
        $hasRisk    = helper::hasFeature('risk');
        $hasMeeting = helper::hasFeature('meeting');

        $hasViewPriv = array();
        if(common::hasPriv('todo',  'view')) $hasViewPriv['todo'] = true;

        $limitCount = !empty($params->reviewCount) ? $params->reviewCount : 20;
        $this->app->loadClass('pager', true);
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

        if(common::hasPriv('task',  'view'))                                                                                        $hasViewPriv['task']        = true;
        if(common::hasPriv('story', 'view') && $this->config->vision != 'lite')                                                     $hasViewPriv['story']       = true;
        if($this->config->URAndSR && common::hasPriv('story', 'view') && $this->config->vision != 'lite')                           $hasViewPriv['requirement'] = true;
        if(common::hasPriv('bug',   'view') && $this->config->vision != 'lite')                                                     $hasViewPriv['bug']         = true;
        if(common::hasPriv('testcase', 'view') && $this->config->vision != 'lite')                                                  $hasViewPriv['testcase']    = true;
        if(common::hasPriv('testtask', 'cases') && $this->config->vision != 'lite')                                                 $hasViewPriv['testtask']    = true;
        if(common::hasPriv('risk',  'view') && $this->config->edition == 'max' && $this->config->vision != 'lite' && $hasRisk)      $hasViewPriv['risk']        = true;
        if(common::hasPriv('issue', 'view') && $this->config->edition == 'max' && $this->config->vision != 'lite' && $hasIssue)     $hasViewPriv['issue']       = true;
        if(common::hasPriv('meeting', 'view') && $this->config->edition == 'max' && $this->config->vision != 'lite' && $hasMeeting) $hasViewPriv['meeting']     = true;
        if(common::hasPriv('feedback', 'view') && in_array($this->config->edition, array('max', 'biz')))                            $hasViewPriv['feedback']    = true;
        if(common::hasPriv('ticket', 'view') && in_array($this->config->edition, array('max', 'biz')))                              $hasViewPriv['ticket']      = true;

        $params          = $block->params;
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
                ->beginIF($objectType == 'story')->andWhere('t1.type')->eq('story')->andWhere('t2.deleted')->eq('0')->andWhere('t1.vision')->eq($this->config->vision)->fi()
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
                    if($todo->type == 'task' && isset($tasks[$todo->objectID]))
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
                    if($task->deadline) $task->deadline = date('m-d', strtotime($task->deadline));
                    $task->estimate .= 'h';
                    $task->left     .= 'h';
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
        }

        $this->view->users          = $this->loadModel('user')->getPairs('all,noletter');
        $this->view->isExternalCall = $this->isExternalCall();
        $this->view->hasViewPriv    = $hasViewPriv;
        $this->view->count          = $count;
    }

    /**
     * Print recent project block.
     *
     * @access protected
     * @return void
     */
    protected function printRecentProjectBlock(): void
    {
        /* load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager(0, 3, 1);
        $this->view->projects = $this->loadModel('project')->getList('all', 'id_desc', true, $pager);
    }

    /**
     * Print project team block.
     *
     * @param  object    $block
     * @access protected
     * @return void
     */
    protected function printProjectTeamBlock(object $block): void
    {
        $count   = isset($block->params->count)   ? $block->params->count   : 15;
        $status  = isset($block->params->type)    ? $block->params->type    : 'all';
        $orderBy = isset($block->params->orderBy) ? $block->params->orderBy : 'id_desc';

        /* Get projects. */
        $this->app->loadLang('task');
        $this->app->loadLang('program');
        $this->app->loadLang('execution');
        $this->view->projects = $this->loadModel('project')->getOverviewList($status, 0, $orderBy, $count);
    }

    /**
     * Print document statistic block.
     *
     * @access protected
     * @return void
     */
    protected function printDocStatisticBlock(): void
    {
        $this->view->statistic = $this->loadModel('doc')->getStatisticInfo();
    }

    /**
     * Print document dynamic block.
     *
     * @access protected
     * @return void
     */
    protected function printDocDynamicBlock(): void
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
    protected function printDocMyCollectionBlock(): void
    {
        /* Load pager. */
        $this->app->loadClass('pager', true);
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
    protected function printDocRecentUpdateBlock(): void
    {
        /* Load pager. */
        $this->app->loadClass('pager', true);
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
    protected function printDocViewListBlock(): void
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
    protected function printDocCollectListBlock(): void
    {
        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager(0, 6, 1);

        $this->view->docList = $this->loadModel('doc')->getDocsByBrowseType('all', 0, 0, 'collects_desc', $pager);
    }

    /**
     * Print product's document block.
     *
     * @param  object    $block
     * @param  array     $params
     * @access protected
     * @return void
     */
    protected function printProductDocBlock(object $block, array $params = array()): void
    {
        $type = 'involved';
        if(isset($params['type'])) $type = $params['type'];

        $this->loadModel('doc');
        $this->session->set('docList', $this->createLink('doc', 'index'), 'doc');

        /* Set project status and count. */
        $count         = isset($block->params->count) ? (int)$block->params->count : 15;
        $products      = $this->loadModel('product')->getOrderedProducts('all');
        $involveds     = $this->product->getOrderedProducts('involved');
        $productIdList = array_merge(array_keys($products), array_keys($involveds));

        $stmt = $this->dao->select('id,product,lib,title,type,addedBy,addedDate,editedDate,status,acl,`groups`,users,deleted')->from(TABLE_DOC)->alias('t1')
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

        $this->view->type     = $type;
        $this->view->users    = $this->loadModel('user')->getPairs('noletter');
        $this->view->products = $type == 'involveds' ? $hasDataInvolveds : $hasDataProducts;
        $this->view->docGroup = $docGroup;
    }

    /**
     * Print project's document block.
     *
     * @param  object    $block
     * @param  array     $params
     * @access protected
     * @return void
     */
    protected function printProjectDocBlock(object $block, array $params = array()): void
    {
        $type = 'involved';
        if(isset($params['type'])) $type = $params['type'];

        $this->loadModel('doc');
        $this->app->loadLang('project');
        $this->session->set('docList', $this->createLink('doc', 'index'), 'doc');

        /* Set project status and count. */
        $count    = isset($block->params->count) ? (int)$block->params->count : 15;
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

        $this->view->type     = $type;
        $this->view->users    = $this->loadModel('user')->getPairs('noletter');
        $this->view->projects = $type == 'involveds' ? $hasDataInvolveds : $hasDataProjects;
        $this->view->docGroup = $docGroup;
    }

    /**
     * Print guide block
     *
     * @param  object    $block
     * @access protected
     * @return void
     */
    protected function printGuideBlock($block)
    {
        $this->app->loadLang('custom');
        $this->app->loadLang('my');
        $this->loadModel('setting');

        $this->view->blockID       = $block->id;
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
     * 打印产品月度推进分析区块。
     * Print product monthly progress block.
     *
     * @access protected
     * @return void
     */
    protected function printMonthlyProgressBlock()
    {
        $years  = array();
        $months = array();
        $groups = array();
        for($i = 5; $i >= 0; $i --)
        {
            $years[date('Y', strtotime("first day of -{$i} month"))] = date('Y', strtotime("first day of -{$i} month"));
            $months[date('m', strtotime("first day of -{$i} month"))] = date('m', strtotime("first day of -{$i} month"));
            $groups[date('Y-m', strtotime("first day of -{$i} month"))] = date('Y-m', strtotime("first day of -{$i} month"));
        }
        $monthFinishedScale = $this->loadModel('metric')->getResultByCode('scale_of_monthly_finished_story', array('year' => join(',', $years), 'month' => join(',', $months)));
        $monthCreatedStory  = $this->metric->getResultByCode('count_of_monthly_created_story',  array('year' => join(',', $years), 'month' => join(',', $months)));
        $monthFinishedStory = $this->metric->getResultByCode('count_of_monthly_finished_story', array('year' => join(',', $years), 'month' => join(',', $months)));
        $monthCreatedBug    = $this->metric->getResultByCode('count_of_monthly_created_bug',    array('year' => join(',', $years), 'month' => join(',', $months)));
        $monthFinishedBug   = $this->metric->getResultByCode('count_of_monthly_fixed_bug',      array('year' => join(',', $years), 'month' => join(',', $months)));

        foreach($groups as $group)
        {
            $doneStoryEstimate[$group] = 0;
            $doneStoryCount[$group]    = 0;
            $createStoryCount[$group]  = 0;
            $fixedBugCount[$group]     = 0;
            $createBugCount[$group]    = 0;

            if(!empty($monthFinishedScale))
            {
                foreach($monthFinishedScale as $scale)
                {
                    if($group == "{$scale['year']}-{$scale['month']}") $doneStoryEstimate[$group] = $scale['value'];
                }
            }

            if(!empty($monthCreatedStory))
            {
                foreach($monthCreatedStory as $story)
                {
                    if($group == "{$story['year']}-{$story['month']}") $doneStoryCount[$group] = $story['value'];
                }
            }

            if(!empty($monthFinishedStory))
            {
                foreach($monthFinishedStory as $story)
                {
                    if($group == "{$story['year']}-{$story['month']}") $createStoryCount[$group] = $story['value'];
                }
            }

            if(!empty($monthCreatedBug))
            {
                foreach($monthCreatedBug as $bug)
                {
                    if($group == "{$bug['year']}-{$bug['month']}") $fixedBugCount[$group] = $bug['value'];
                }
            }

            if(!empty($monthFinishedBug))
            {
                foreach($monthFinishedBug as $bug)
                {
                    if($group == "{$bug['year']}-{$bug['month']}") $createBugCount[$group] = $bug['value'];
                }
            }
        }
        $this->view->doneStoryEstimate = $doneStoryEstimate;
        $this->view->doneStoryCount    = $doneStoryCount;
        $this->view->createStoryCount  = $createStoryCount;
        $this->view->fixedBugCount     = $fixedBugCount;
        $this->view->createBugCount    = $createBugCount;
    }

    /**
     * 打印产品年度工作量统计区块。
     * Print product annual workload statisitc block.
     *
     * @access protected
     * @return void
     */
    protected function printAnnualWorkloadBlock()
    {
        $products      = $this->loadModel('product')->getPairs();
        $productIdList = array_keys($products);

        $this->loadModel('metric');
        $finishEstimateGroup = $this->metric->getResultByCode('scale_of_annual_finished_story_in_product', array('product' => join(',', $productIdList), 'year' => date('Y')));
        $doneStoryGroup      = $this->metric->getResultByCode('count_of_annual_finished_story_in_product', array('product' => join(',', $productIdList), 'year' => date('Y')));
        $resolvedBugGroup    = $this->metric->getResultByCode('count_of_annual_restored_bug_in_product',   array('product' => join(',', $productIdList), 'year' => date('Y')));

        if(!empty($finishEstimateGroup)) $finishEstimateGroup = array_column($finishEstimateGroup, null, 'product');
        if(!empty($doneStoryGroup))      $doneStoryGroup      = array_column($doneStoryGroup,      null, 'product');
        if(!empty($resolvedBugGroup))    $resolvedBugGroup    = array_column($resolvedBugGroup,    null, 'product');

        $doneStoryEstimate = array();
        $doneStoryCount    = array();
        $resolvedBugCount  = array();
        foreach($products as $productID => $productName)
        {
            $doneStoryEstimate[$productID] = isset($finishEstimateGroup[$productID]['value']) ? $finishEstimateGroup[$productID]['value'] : 0;
            $doneStoryCount[$productID]    = isset($doneStoryGroup[$productID]['value'])      ? $doneStoryGroup[$productID]['value']      : 0;
            $resolvedBugCount[$productID]  = isset($resolvedBugGroup[$productID]['value'])    ? $resolvedBugGroup[$productID]['value']    : 0;
        }
        arsort($doneStoryEstimate);
        arsort($doneStoryCount);
        arsort($resolvedBugCount);

        $this->view->products          = $products;
        $this->view->doneStoryEstimate = $doneStoryEstimate;
        $this->view->doneStoryCount    = $doneStoryCount;
        $this->view->resolvedBugCount  = $resolvedBugCount;
        $this->view->maxStoryEstimate  = max($doneStoryEstimate);
        $this->view->maxStoryCount     = max($doneStoryCount);
        $this->view->maxBugCount       = max($resolvedBugCount);
    }

    /**
     * Print bug statistic block.
     *
     * @param  object    $block
     * @access protected
     * @return void
     */
    protected function printBugStatisticBlock(object $block)
    {
        $this->app->loadClass('pager', true);
        $count = isset($block->params->count) ? (int)$block->params->count : 0;
        $type  = isset($block->params->type) ? $block->params->type : '';
        $pager = pager::init(0, $count , 1);

        $today = strtotime(helper::today());
        $begin = strtotime(date('Y-m', strtotime('+2 month', $today)));
        $end   = strtotime(date('Y-m', $today));
        $begin = strtotime('2023-09');
        $end   = strtotime('2024-02');

        $months         = array();
        $products       = $this->loadModel('product')->getList(0, $block->params->type);
        $totalBugs      = array();
        $closedBugs     = array();
        $unresovledBugs = array();
        $resolvedRate   = array();
        $activateBugs   = array();
        $resolveBugs    = array();
        $closeBugs      = array();
        $products       = $this->loadModel('product')->getList(0, $type);
        foreach($products as $productID => $product)
        {
            $closedBugs[$productID]     = rand(10, 10000);
            $unresovledBugs[$productID] = rand(10, 1000);
            $totalBugs[$productID]      = rand(100, 10000);
            $resolvedRate[$productID]   = rand(1, 100);
            for($date = $begin; $date <= $end; $date = strtotime('+1 month', $date))
            {
                $month = date('Y-m', $date);
                $activateBugs[$productID][$month] = rand(100, 400);
                $resolveBugs[$productID][$month]  = rand(100, 400);
                $closeBugs[$productID][$month]    = rand(100, 400);

                $month = (int)ltrim(date('m', $date), '0');

                $monthName = in_array($this->app->getClientLang(), array('zh-cn','zh-tw')) ? "{$month}{$this->lang->block->month}" : zget($this->lang->datepicker->monthNames, $month - 1, '');
                if($month == 1) $monthName .= "\n" . date('Y', $date) . (in_array($this->app->getClientLang(), array('zh-cn','zh-tw')) ? $this->lang->year : '');

                if(count($closedBugs) == 1) $months[] = $monthName;
            }
        }

        $this->app->loadLang('bug');

        $this->view->months         = $months;
        $this->view->products       = $products;
        $this->view->totalBugs      = $totalBugs;
        $this->view->closedBugs     = $closedBugs;
        $this->view->unresovledBugs = $unresovledBugs;
        $this->view->resolvedRate   = $resolvedRate;
        $this->view->activateBugs   = $activateBugs;
        $this->view->resolveBugs    = $resolveBugs;
        $this->view->closeBugs      = $closeBugs;
    }

    /**
     * 打印团队成就区块。
     * Print Team Achievement block.
     *
     * @access protected
     * @return void
     */
    protected function printTeamAchievementBlock()
    {
        $years  = array();
        $months = array();
        for($i = 0; $i <= 1; $i ++)
        {
            $years[] = date('Y', strtotime("-{$i} day"));
            $months[] = date('m', strtotime("-{$i} day"));
        }

        $this->loadModel('metric');
        $finishedTaskGroup = $this->metric->getResultByCode('count_of_daily_finished_task', array('year' => join(',', $years), 'month' => join(',', $months))); // 完成任务数。
        $createdStoryGroup = $this->metric->getResultByCode('count_of_daily_created_story', array('year' => join(',', $years), 'month' => join(',', $months))); // 创建需求数。
        $closedBugGroup    = $this->metric->getResultByCode('count_of_daily_closed_bug',    array('year' => join(',', $years), 'month' => join(',', $months))); // 关闭Bug数。
        $runCaseGroup      = $this->metric->getResultByCode('count_of_daily_run_case',      array('year' => join(',', $years), 'month' => join(',', $months))); // 执行用例数。
        $consumedGroup     = $this->metric->getResultByCode('hour_of_daily_effort',         array('year' => join(',', $years), 'month' => join(',', $months))); // 消耗工时。
        $totalEffortGroup  = $this->metric->getResultByCode('day_of_daily_effort',          array('year' => join(',', $years), 'month' => join(',', $months))); // 累计工作量。

        /* 获取今日完成任务数和昨日完成任务数。 */
        $finishedTasks  = 0;
        $yesterdayTasks = 0;
        if($finishedTaskGroup)
        {
            foreach($finishedTaskGroup as $data)
            {
                $currentDay = "{$data['year']}-{$data['month']}-{$data['day']}";
                if($currentDay == date('Y-m-d'))                      $finishedTasks  = $data['value'];
                if($currentDay == date('Y-m-d', strtotime("-1 day"))) $yesterdayTasks = $data['value'];
            }
        }

        /* 获取今日创建需求数和昨日创建需求数。 */
        $createdStories   = 0;
        $yesterdayStories = 0;
        if($createdStoryGroup)
        {
            foreach($createdStoryGroup as $data)
            {
                $currentDay = "{$data['year']}-{$data['month']}-{$data['day']}";
                if($currentDay == date('Y-m-d'))                      $createdStories   = $data['value'];
                if($currentDay == date('Y-m-d', strtotime("-1 day"))) $yesterdayStories = $data['value'];
            }
        }

        /* 获取今日关闭BUG数和昨日关闭BUG数。 */
        $closedBugs    = 0;
        $yesterdayBugs = 0;
        if($closedBugGroup)
        {
            foreach($closedBugGroup as $data)
            {
                $currentDay = "{$data['year']}-{$data['month']}-{$data['day']}";
                if($currentDay == date('Y-m-d'))                      $closedBugs    = $data['value'];
                if($currentDay == date('Y-m-d', strtotime("-1 day"))) $yesterdayBugs = $data['value'];
            }
        }

        /* 获取今日执行用例数和昨日执行用例数。 */
        $runCases       = 0;
        $yesterdayCases = 0;
        if($runCaseGroup)
        {
            foreach($runCaseGroup as $data)
            {
                $currentDay = "{$data['year']}-{$data['month']}-{$data['day']}";
                if($currentDay == date('Y-m-d'))                      $runCases       = $data['value'];
                if($currentDay == date('Y-m-d', strtotime("-1 day"))) $yesterdayCases = $data['value'];
            }
        }

        /* 获取今日消耗工时和昨日消耗工时。 */
        $consumedHours  = 0;
        $yesterdayHours = 0;
        if($consumedGroup)
        {
            foreach($consumedGroup as $data)
            {
                $currentDay = "{$data['year']}-{$data['month']}-{$data['day']}";
                if($currentDay == date('Y-m-d'))                      $consumedHours  = $data['value'];
                if($currentDay == date('Y-m-d', strtotime("-1 day"))) $yesterdayHours = $data['value'];
            }
        }

        /* 获取总投入的人天和今日投入的人天。 */
        $totalWorkload = 0;
        $todayWorkload = 0;
        if($totalEffortGroup)
        {
            foreach($totalEffortGroup as $data)
            {
                $totalWorkload += $data['value'];
                $currentDay = "{$data['year']}-{$data['month']}-{$data['day']}";
                if($currentDay == date('Y-m-d')) $todayWorkload = $data['value'];
            }
        }

        $this->view->finishedTasks   = $finishedTasks;
        $this->view->comparedTasks   = $finishedTasks - $yesterdayTasks;
        $this->view->createdStories  = $createdStories;
        $this->view->comparedStories = $createdStories - $yesterdayStories;
        $this->view->closedBugs      = $closedBugs;
        $this->view->comparedBugs    = $closedBugs - $yesterdayBugs;
        $this->view->runCases        = $runCases;
        $this->view->comparedCases   = $runCases - $yesterdayCases;
        $this->view->consumedHours   = $consumedHours;
        $this->view->comparedHours   = $consumedHours - $yesterdayHours;
        $this->view->totalWorkload   = $totalWorkload;
        $this->view->todayWorkload   = $todayWorkload;

    }

    /**
     * 打印单个产品统计区块。
     * Print product statistic block.
     *
     * @param  object    $block
     * @access protected
     * @return bool
     */
    protected function printSingleStatisticBlock(object $block): void
    {
        /* 获取需要统计的产品列表。 */
        /* Obtain a list of product that require statistics. */
        $status    = isset($block->params->type)  ? $block->params->type  : '';
        $count     = isset($block->params->count) ? $block->params->count : '';
        $productID = $this->session->product;

        $this->loadModel('metric');
        $storyDeliveryRate = $this->metric->getResultByCode('rate_of_delivery_story_in_product',   array('product' => $productID));
        $totalStories      = $this->metric->getResultByCode('count_of_valid_story_in_product',     array('product' => $productID));
        $closedStories     = $this->metric->getResultByCode('count_of_delivered_story_in_product', array('product' => $productID));
        $unclosedStories   = $this->metric->getResultByCode('count_of_unclosed_story_in_product',  array('product' => $productID));

        if(!empty($storyDeliveryRate)) $storyDeliveryRate = array_column($storyDeliveryRate, null, 'product');
        if(!empty($totalStories))      $totalStories      = array_column($totalStories,      null, 'product');
        if(!empty($closedStories))     $closedStories     = array_column($closedStories,     null, 'product');
        if(!empty($unclosedStories))   $unclosedStories   = array_column($unclosedStories,   null, 'product');

        $years  = array();
        $months = array();
        $groups = array();
        for($i = 5; $i >= 5; $i --)
        {
            $years[date('Y', strtotime("first day of -{$i} month"))] = date('Y', strtotime("first day of -{$i} month"));
            $months[date('m', strtotime("first day of -{$i} month"))] = date('m', strtotime("first day of -{$i} month"));
            $groups[date('Y-m', strtotime("first day of -{$i} month"))] = date('Y-m', strtotime("first day of -{$i} month"));
        }
        $monthFinish  = $this->metric->getResultByCode('count_of_monthly_finished_story_in_product', array('product' => $productID, 'year' => join(',', $years), 'month' => join(',', $months)));
        $monthCreated = $this->metric->getResultByCode('count_of_monthly_created_story_in_product',  array('product' => $productID, 'year' => join(',', $years), 'month' => join(',', $months)));
        if(empty($monthFinish)) $monthFinish = array();
        if(empty($monthCreated)) $monthCreated = array();

        /* 根据产品列表获取预计开始日期距离现在最近且预计开始日期大于当前日期的未开始状态计划。 */
        /* Obtain an unstarted status plan based on the product list, with an expected start date closest to the current date and an expected start date greater than the current date. */
        $newPlan = $this->dao->select('*')->from(TABLE_PRODUCTPLAN)
            ->where('deleted')->eq('0')
            ->andWhere('product')->eq($productID)
            ->andWhere('begin')->ge(date('Y-m-01'))
            ->andWhere('status')->eq('wait')
            ->orderBy('begin_desc')
            ->fetch();

        /* 根据产品列表获取实际开始日期距离当前最近的进行中状态的执行。 */
        /* Obtain the execution of the current in progress status closest to the actual start date based on the product list. */
        $newExecution = $this->dao->select('execution.*,relation.product')->from(TABLE_EXECUTION)->alias('execution')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('relation')->on('execution.id=relation.project')
            ->where('execution.deleted')->eq('0')
            ->andWhere('execution.type')->eq('sprint')
            ->andWhere('relation.product')->eq($productID)
            ->andWhere('execution.status')->eq('doing')
            ->orderBy('realBegan_asc')
            ->fetch();

        /* 根据产品列表获取发布日期距离现在最近且发布日期小于当前日期的发布。 */
        /* Retrieve releases with the latest release date from the product list and a release date earlier than the current date. */
        $newRelease = $this->dao->select('*')->from(TABLE_RELEASE)
            ->where('deleted')->eq('0')
            ->andWhere('product')->eq($productID)
            ->andWhere('date')->lt(date('Y-m-01'))
            ->orderBy('date_asc')
            ->fetch();

        $product = $this->loadModel('product')->getByID($productID);

        $product->storyDeliveryRate = !empty($storyDeliveryRate) && !empty($storyDeliveryRate[$productID]) ? zget($storyDeliveryRate[$productID], 'value') * 100 : 0;
        $product->totalStories      = !empty($totalStories)      && !empty($totalStories[$productID])      ? zget($totalStories[$productID], 'value') : 0;
        $product->closedStories     = !empty($closedStories)     && !empty($closedStories[$productID])     ? zget($closedStories[$productID], 'value') : 0;
        $product->unclosedStories   = !empty($unclosedStories)   && !empty($unclosedStories[$productID])   ? zget($unclosedStories[$productID], 'value') : 0;
        $product->newPlan           = $newPlan;
        $product->newExecution      = $newExecution;
        $product->newRelease        = $newRelease;

        foreach($groups as $group)
        {
            $product->monthFinish[$group]  = 0;
            $product->monthCreated[$group] = 0;
            if(!empty($monthFinish))
            {
                foreach($monthFinish as $story)
                {
                    if($group == "{$story['year']}-{$story['month']}" && $productID == $story['product']) $product->monthFinish[$group] = $story['value'];
                }
            }
            if(!empty($monthCreated))
            {
                foreach($monthCreated as $story)
                {
                    if($group == "{$story['year']}-{$story['month']}" && $productID == $story['product']) $product->monthCreated[$group] = $story['value'];
                }
            }
        }

        $this->view->product      = $product;
        $this->view->monthFinish  = $monthFinish;
        $this->view->monthCreated = $monthCreated;
    }

    /**
     * 打印单个产品的bug统计区块。
     * Print single product bug statistic block.
     *
     * @param  object    $block
     * @access protected
     * @return void
     */
    protected function printSingleBugStatisticBlock(object $block)
    {
        $this->app->loadClass('pager', true);
        $count     = isset($block->params->count) ? (int)$block->params->count : 0;
        $type      = isset($block->params->type) ? $block->params->type : '';
        $pager     = pager::init(0, $count , 1);
        $productID = $this->session->product;

        $today = strtotime(helper::today());
        $begin = strtotime(date('Y-m', strtotime('-2 month', $today)));
        $end   = strtotime(date('Y-m', $today));

        $closedBug     = rand(10, 10000);
        $unresovledBug = rand(10, 1000);
        $totalBug      = rand(100, 10000);
        $resolvedRate  = rand(1, 100);
        $months        = array();
        $activateBugs  = array();
        $resolveBugs   = array();
        $closeBugs     = array();
        for($date = $begin; $date <= $end; $date = strtotime('+1 month', $date))
        {
            $month = date('Y-m', $date);
            $activateBugs[$month] = rand(100, 400);
            $resolveBugs[$month]  = rand(100, 400);
            $closeBugs[$month]    = rand(100, 400);

            $month = (int)ltrim(date('m', $date), '0');

            $monthName = in_array($this->app->getClientLang(), array('zh-cn','zh-tw')) ? "{$month}{$this->lang->block->month}" : zget($this->lang->datepicker->monthNames, $month - 1, '');
            if($month == 1) $monthName .= "\n" . date('Y', $date) . (in_array($this->app->getClientLang(), array('zh-cn','zh-tw')) ? $this->lang->year : '');

            $months[] = $monthName;
        }

        $this->app->loadLang('bug');

        $this->view->months        = $months;
        $this->view->productID     = $productID;
        $this->view->totalBug      = $totalBug;
        $this->view->closedBug     = $closedBug;
        $this->view->unresovledBug = $unresovledBug;
        $this->view->resolvedRate  = $resolvedRate;
        $this->view->activateBugs  = $activateBugs;
        $this->view->resolveBugs   = $resolveBugs;
        $this->view->closeBugs     = $closeBugs;
    }

    /**
     * 打印单个产品的需求列表区块。
     * Print single product story block.
     *
     * @params object     $block
     * @access protected
     * @return void
     */
    protected function printSingleStoryBlock(object $block): void
    {
        $this->session->set('storyList', $this->createLink('product', 'dashboard'), 'product');
        if(preg_match('/[^a-zA-Z0-9_]/', $block->params->type)) return;

        $this->app->loadClass('pager', true);
        $count     = isset($block->params->count) ? (int)$block->params->count : 0;
        $pager     = pager::init(0, $count , 1);
        $type      = isset($block->params->type) ? $block->params->type : 'assignedTo';
        $orderBy   = isset($block->params->type) ? $block->params->orderBy : 'id_asc';
        $productID = $this->session->product;

        $this->view->stories = $this->loadModel('story')->getUserStories($this->app->user->account, $type, $orderBy, $this->viewType != 'json' ? $pager : '', 'story', true, 0, $productID);
    }

    /**
     * 打印单个产品发布列表区块数据。
     * Print releases block.
     *
     * @param  object    $block
     * @access protected
     * @return void
     */
    protected function printSingleReleaseBlock(object $block): void
    {
        $uri = $this->createLink('product', 'dashboard');
        $this->session->set('releaseList', $uri, 'product');
        $this->session->set('buildList', $uri, 'execution');

        $productID = $this->session->product;

        $this->app->loadLang('release');
        $this->view->releases = $this->dao->select('t1.*,t2.name as productName,t3.name as buildName')->from(TABLE_RELEASE)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->leftJoin(TABLE_BUILD)->alias('t3')->on('t1.build=t3.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t1.product')->eq($productID)
            ->orderBy('t1.id desc')
            ->beginIF($this->viewType != 'json')->limit((int)$block->params->count)->fi()
            ->fetchAll();
    }

    /**
     * 打印单个产品计划列表区块。
     * Print single product plan block.
     *
     * @param  object    $block
     * @access protected
     * @return bool
     */
    protected function printSinglePlanBlock(object $block): void
    {
        $uri = $this->createLink('product', 'dashboard');
        $this->session->set('productList', $uri, 'product');
        $this->session->set('productPlanList', $uri, 'product');

        $this->app->loadClass('pager', true);
        $count     = isset($block->params->count) ? (int)$block->params->count : 0;
        $pager     = pager::init(0, $count , 1);
        $productID = $this->session->product;
        $product   = $this->loadModel('product')->getByID($productID);

        $this->view->plans    = $this->loadModel('productplan')->getList($productID, 0, 'all', $pager, 'begin_desc', 'noproduct');
        $this->view->products = array($productID => $product->name);
    }

    /**
     * Print single product latest dynamic.
     *
     * @access protected
     * @return void
     */
    protected function printSingleDynamicBlock(): void
    {
        $productID = $this->session->product;

        /* Load pager. */
        $this->app->loadClass('pager', true);
        $pager = new pager(0, 30, 1);

        $this->view->actions = $this->loadModel('action')->getDynamic('all', 'today', 'date_desc', $pager, $productID);
        $this->view->users   = $this->loadModel('user')->getPairs('nodeleted|noletter|all');
    }

    /**
     * 打印产品月度推进分析区块.
     * Print monthly progress for the product.
     *
     * @access protected
     * @return void
     */
    protected function printSingleMonthlyProgressBlock(): void
    {
        $productID = $this->session->product;

        $months            = array();
        $doneStoryEstimate = array();
        $doneStoryCount    = array();
        $createStoryCount  = array();
        $fixedBugCount     = array();
        $createBugCount    = array();
        $releaseCount      = array();

        $today = strtotime(helper::today());
        $begin = strtotime(date('Y-m', strtotime('+2 month', $today)));
        $end   = strtotime(date('Y-m', $today));
        $begin = strtotime('2023-09');
        $end   = strtotime('2024-02');
        for($date = $begin; $date <= $end; $date = strtotime('+1 month', $date))
        {
            $month = date('Y-m', $date);
            $doneStoryEstimate[$month] = rand(100, 400);
            $doneStoryCount[$month]    = rand(100, 400);
            $createStoryCount[$month]  = rand(100, 400);
            $fixedBugCount[$month]     = rand(100, 400);
            $createBugCount[$month]    = rand(100, 400);
            $releaseCount[$month]      = rand(100, 400);

            $month = (int)ltrim(date('m', $date), '0');

            $monthName = in_array($this->app->getClientLang(), array('zh-cn','zh-tw')) ? "{$month}{$this->lang->block->month}" : zget($this->lang->datepicker->monthNames, $month - 1, '');
            if($month == 1) $monthName .= "\n" . date('Y', $date) . (in_array($this->app->getClientLang(), array('zh-cn','zh-tw')) ? $this->lang->year : '');

            $months[] = $monthName;
        }

        $this->view->months            = $months;
        $this->view->doneStoryEstimate = $doneStoryEstimate;
        $this->view->doneStoryCount    = $doneStoryCount;
        $this->view->createStoryCount  = $createStoryCount;
        $this->view->fixedBugCount     = $fixedBugCount;
        $this->view->createBugCount    = $createBugCount;
        $this->view->releaseCount      = $releaseCount;
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
