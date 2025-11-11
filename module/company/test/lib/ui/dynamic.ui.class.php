<?php
require_once dirname(__FILE__, 6) . '/test/lib/ui.php';

const DYNAMIC_MENUS = ['all', 'today', 'yesterday', 'thisWeek', 'lastWeek', 'thisMonth', 'lastMonth'];

class dynamicTester extends tester
{
    public $users;
    public $tasks;
    public $groupPrivs;
    public $actions;
    public $actionIndex;
    public $actionsByKey;
    public $pageTitle;
    public $prodNames;
    public $projNames;
    public $execNames;
    public $userNames;

    public function __construct()
    {
        parent::__construct();
        global $uiTester;

        $users = $uiTester->dao->select('u.*, ug.`group`')
            ->from(TABLE_USER)->alias('u')
            ->leftJoin(TABLE_USERGROUP)->alias('ug')->on('u.account = ug.account')
            ->fetchAll();

        $groupPrivs = $uiTester->dao->select('*')->from(TABLE_GROUPPRIV)->fetchAll();

        $this->tasks     = $uiTester->dao->select('id,name')->from(TABLE_TASK)->orderBy('id')->fetchAll();
        $this->prodNames = array_keys($uiTester->dao->select('name')->from(TABLE_PRODUCT)->fetchAll('name'));
        $this->projNames = array_keys($uiTester->dao->select('name')->from(TABLE_PROJECT)->where('type')->eq('project')->fetchAll('name'));
        $this->execNames = array_keys($uiTester->dao->select('name')->from(TABLE_PROJECT)->where('type')->eq('sprint')->fetchAll('name'));

        $this->groupPrivs = [];
        $this->actions    = [];
        $this->pageTitle  = $this->lang->company->dynamic ?? '动态';

        $this->userNames = ['all']; //'all'是一个虚拟的用户名，代表所有用户
        foreach($users as $u)
        {
            $this->users[$u->realname] = $u;
            $this->userNames[] = $u->realname;
        }

        foreach($groupPrivs as $gp) $this->groupPrivs[$gp->group][$gp->module][$gp->method] = $gp;
    }

    /**
     * 用户登录后从数据库中刷新数据（因为增加了登录信息）
     * Refresh the dynamic data after login
     *
     */
    private function refreshDataFromDB()
    {
        global $uiTester;
        $this->actions['all'] = [];
        foreach($this->users as $user)
        {
            $action = $uiTester->dao->select('a.*, user.realname as userName, prod.name as prodName, proj.name as projName, exec.name as execName')
                ->from(TABLE_ACTION)->alias('a')
                ->leftJoin(TABLE_ACTIONPRODUCT)->alias('ap')->on('a.id = ap.action')
                ->leftJoin(TABLE_USER)->alias('user')->on('a.actor = user.account')
                ->leftJoin(TABLE_PRODUCT)->alias('prod')->on('ap.product = prod.id')
                ->leftJoin(TABLE_PROJECT)->alias('proj')->on('a.project = proj.id')
                ->leftJoin(TABLE_PROJECT)->alias('exec')->on('a.execution = exec.id')
                ->where('a.actor')->eq($user->account)
                ->fetchAll();
            $this->actions[$user->realname] = $action;
            $this->actions['all'] = array_merge($this->actions['all'], $action);
        }

        $this->buildActionIndex();

        // 预构建所有维度组合的键值映射，使用笛卡尔积生成器替代多层嵌套循环。(效率一样，只是代码更简洁)
        $this->actionsByKey = [];
        foreach($this->cartesian([DYNAMIC_MENUS, $this->userNames, $this->prodNames, $this->projNames, $this->execNames]) as $combo)
        {
            list($menu, $userName, $prodName, $projName, $execName) = $combo;
            $key = "{$menu}|{$userName}|{$prodName}|{$projName}|{$execName}";
            $this->actionsByKey[$key] = $this->getActionsByDims($menu, $userName, $prodName, $projName, $execName);
        }
    }

    /**
     * 校验company模块dynamic视图内容
     * Verify the content of company dynamic view.
     *
     * @return object 成功或失败对象
     * @access public
     */
    public function verifyCompanyDynamicContent()
    {
        foreach($this->users as $user)
        {
            $this->login($user->account);
            $hasGroupPriv = ($user->account == 'admin') || isset($this->groupPrivs[$user->group]['company']['dynamic']);

            if($hasGroupPriv)
            {
                $this->refreshDataFromDB();
                $form = $this->initForm('company', 'dynamic', [], 'appIframe-system');
                $form->wait(2);
                $ret = $this->verifySelectedContent($form, $user);
                if($ret) return $ret;
                $ret = $this->verifySearch($form);
                if($ret) return $ret;
            }
            else
            {
                $form = null;
                try
                {
                    $form = $this->initForm('company', 'dynamic', [], 'appIframe-system');
                    return $this->failed("用户'{$user->account}'无'{$this->pageTitle}'页面访问权限却能访问页面");
                }
                catch(Exception $e)
                {
                    // 如果页面对象已初始化，则读取拒绝信息；否则视为无法访问直接跳过
                    if(is_object($form))
                    {
                        $form->wait(2);
                        $msg = $form->dom->denied->getText();
                        // 用户没有team权限应该显示拒绝框
                        if($msg == $user->account . " " . $this->lang->user->deny) continue;
                        return $this->failed("用户'{$user->account}'无'{$this->pageTitle}'页面访问权限却未显示拒绝框");
                    }
                    continue;
                }
            }
        }
        return $this->success('开源版m=company&f=dynamic测试成功');
    }

    /**
     * 抽查给定索引的任务动态搜索结果
     * Verify the search result of company dynamic view.
     *
     * @param  object $form 页面对象
     * @param  int    $index 任务索引，默认-1表示最后一个任务
     * @return object 成功或失败对象
     * @access public
     */
    private function verifySearch($form, $index = -1)
    {
        // 抽查ID=60的动态是否有数据
        $search = [
            'field1' => $this->lang->company->product,
            'value1' => $this->lang->product->allProduct,
            'field4' => $this->lang->action->objectID];
        $form->dom->searchForm->click();
        $form->wait(1);
        foreach($search as $key => $value)
        {
            $form->dom->{$key}->picker($value);
            $form->wait(1);
        }

        $index = $index < 0 ? count($this->tasks) - 1 : $index;
        $form->dom->value4->setValue($this->tasks[$index]->id);
        $form->wait(1);
        $form->dom->searchBtn->click();
        $form->wait(2);

        $displayed = $form->dom->searchResult->getText();
        $expected = $this->tasks[$index]->name;
        if($displayed != $expected) return $this->failed("ID={$this->tasks[$index]->id}的任务动态搜索结果为{$displayed}，不是预期的任务{$expected}");
        return null;
    }

    /**
     * 根据下拉菜单的选择校验内容
     * Verify content based on dropdown menu selection
     *
     * @param  object $form 页面对象
     * @param  object $user 当前登录用户
     * @return object       成功返回null或失败返回对象
     */
    private function verifySelectedContent($form, $user)
    {
        foreach(DYNAMIC_MENUS as $menu)
        {
            $header = $this->lang->company->featureBar->dynamic->{$menu} ?? $menu;
            $form->dom->{$menu}->click();

            foreach($this->userNames as $userName)
            {
                // 先设置筛选器，再点击周期菜单，确保计数依据最新条件重新渲染。
                // 筛选菜单共4个：用户|产品|项目|执行
                // 先清除已选用户，再选择当前用户。
                if($form->dom->userDeselect) $form->dom->userDeselect->click();
                // 如果当前用户是'all‘，则不选择任何用户。
                if($userName != 'all' && $form->dom->userSelect->getText() != $userName) $form->dom->userSelect->picker($userName);

                foreach($this->prodNames as $prodName)
                {
                    if($form->dom->productSelect->getText() != $prodName) $form->dom->productSelect->picker($prodName);

                    foreach($this->projNames as $projName)
                    {
                        if($form->dom->projectSelect->getText() != $projName) $form->dom->projectSelect->picker($projName);

                        foreach($this->execNames as $execName)
                        {
                            // 具体执行名字前面有'/', 没有选择(即所有执行)不带'/'
                            $prefix = ($execName == $this->lang->execution->common) ? '' : '/';
                            if($form->dom->execSelect->getText() != $prefix . $execName) $form->dom->execSelect->picker($prefix . $execName);
                            $form->wait(1);

                            // 点击时间周期菜单刷新计数
                            $form->dom->{$menu}->click();
                            $form->wait(2);

                            // 读取计数元素前做存在性检查，避免渲染延迟导致空节点。
                            $countNode = $form->dom->{$menu . 'Count'};
                            if(!$countNode)
                            {
                                $form->wait(1);
                                $countNode = $form->dom->{$menu . 'Count'};
                                if(!$countNode) return $this->failed("菜单'{$header}'计数元素未渲染，无法读取数量");
                            }

                            $displayed = (int) trim($countNode->getText());
                            $key       = "{$menu}|{$userName}|{$prodName}|{$projName}|{$execName}";
                            $expected  = count($this->actionsByKey[$key] ?? []);
                            $userName  = $userName == 'all' ? '所有用户' : $userName;

                            if($displayed != $expected) return $this->failed("用户'{$user->realname}'的'|{$header}|{$userName}|{$prodName}|{$projName}|{$execName}|' 动态数量错误，期望{$expected}条，显示{$displayed}条");
                        }
                    }
                }
            }
        }
        return null;
    }

    /**
     * 按照用户|产品|项目|执行构建操作索引
     * Build action index based on user|product|project|execution.
     */
    private function buildActionIndex()
    {
        $this->actionIndex = [];
        foreach($this->actions as $account => $list)
        {
            if($account === 'all') continue;
            if(empty($list)) continue;

            foreach($list as $action)
            {
                $prod = trim(is_object($action) ? ($action->prodName ?? '') : ($action['prodName'] ?? ''));
                $proj = trim(is_object($action) ? ($action->projName ?? '') : ($action['projName'] ?? ''));
                $exec = trim(is_object($action) ? ($action->execName ?? '') : ($action['execName'] ?? ''));
                $accountKey = trim($account);

                $base = [$accountKey, $prod, $proj, $exec];
                for($mask = 0; $mask < 16; $mask++)
                {
                    $u  = ($mask & 1)  ? '__ALL__' : $base[0];
                    $p  = ($mask & 2)  ? '__ALL__' : $base[1];
                    $pr = ($mask & 4)  ? '__ALL__' : $base[2];
                    $e  = ($mask & 8)  ? '__ALL__' : $base[3];
                    $k  = $u . '|' . $p . '|' . $pr . '|' . $e;
                    if(!isset($this->actionIndex[$k])) $this->actionIndex[$k] = [];
                    $this->actionIndex[$k][] = $action;
                }
            }
        }

        // 显式添加汇总键，确保'全部'维度必有数据
        $aggKey = '__ALL__|__ALL__|__ALL__|__ALL__';
        $this->actionIndex[$aggKey] = $this->actions['all'] ?? [];
    }

    /**
     * 根据给定维度组合返回数据
     * Get actions based on the combination of given dimensions.
     *
     * @param string $menu      时间菜单
     * @param string $userName  维度-用户（"all"或'realname'）
     * @param string $prodName  维度-产品名
     * @param string $projName  维度-项目名
     * @param string $execName  维度-执行名
     * @return object|null      返回过滤后的列表
     */
    public function getActionsByDims($menu, $userKey, $prodName, $projName, $execName)
    {
        // 将占位标签或“全部”视为 all 维度
        $productLabel = $this->lang->company->product ?? '产品';
        $projectLabel = $this->lang->company->project ?? '项目';
        $execLabel    = $this->lang->execution->common ?? '执行';
        $allLabel     = $this->lang->all ?? '全部';

        $prodName = (in_array($prodName, [$productLabel, $allLabel, ''], true)) ? 'all' : trim($prodName);
        $projName = (in_array($projName, [$projectLabel, $allLabel, ''], true)) ? 'all' : trim($projName);
        $execName = (in_array($execName, [$execLabel, $allLabel, ''], true)) ? 'all' : trim($execName);
        $userKey  = ($userKey === 'all') ? 'all' : trim($userKey);

        $u  = ($userKey === 'all') ? '__ALL__' : $userKey;
        $p  = ($prodName === 'all') ? '__ALL__' : $prodName;
        $pr = ($projName === 'all') ? '__ALL__' : $projName;
        $e  = ($execName === 'all') ? '__ALL__' : $execName;
        $k  = $u . '|' . $p . '|' . $pr . '|' . $e;

        $list = $this->actionIndex[$k] ?? [];
        return $this->filterItemsByMenu($list, $menu);
    }

    /**
     * 根据时间菜单过滤动态列表。
     * Filter dynamic by given period menu.
     *
     * @param  array  $actions  动态列表
     * @param  string $menu     菜单
     * @return array            过滤后的动态列表
     */
    private function filterItemsByMenu($actions, $menu)
    {
        if(empty($actions)) return [];

        list($beginTS, $endTS) = $this->getBounds($menu);
        if($beginTS === null || $endTS === null) return $actions;

        $filtered = [];
        foreach($actions as $id => $action)
        {
            $dateStr = is_object($action) ? ($action->date ?? '') : ($action['date'] ?? '');
            if(empty($dateStr)) continue;

            $ts = strtotime($dateStr);
            if($ts !== false && $ts >= $beginTS && $ts < $endTS) $filtered[$id] = $action;
        }

        return $filtered;
    }

    /**
     * 计算菜单的日期边界，返回 [beginTS, endTS]，左闭右开。
     * Calculate the date bounds for a given menu period.
     *
     * @param  string $menu 菜单名称
     * @return array        日期边界数组 [beginTS, endTS]
     */
    private function getBounds($menu)
    {
        $key = strtolower($menu);

        switch($key)
        {
            case 'today':
                return [strtotime('today midnight'), strtotime('tomorrow')];
            case 'yesterday':
                return [strtotime('yesterday midnight'), strtotime('today midnight')];
            case 'thisweek':
                return [strtotime('monday this week'), strtotime('monday next week')];
            case 'lastweek':
                return [strtotime('monday last week'), strtotime('monday this week')];
            case 'thismonth':
                $begin = strtotime(date('Y-m-01'));
                $end   = strtotime(date('Y-m-01', strtotime('+1 month', $begin)));
                return [$begin, $end];
            case 'lastmonth':
                $end   = strtotime(date('Y-m-01'));
                $begin = strtotime(date('Y-m-01', strtotime('-1 month', $end)));
                return [$begin, $end];
            default:
                return [null, null];
        }
    }

    /**
     * 笛卡尔生成器：按顺序产出各集合的组合，避免多层嵌套循环。
     * Cartesian generator producing combinations across provided sets.
     *
     * @param  array $sets [A1, A2, ...]，每个元素为一个可遍历集合
     * @return Generator    依次yield形如 [a1, a2, ...] 的组合
     */
    private function cartesian(array $sets)
    {
        if(empty($sets)) { yield []; return; }

        $first = array_shift($sets);
        foreach($first as $value)
        {
            // 递归yield后续集合的组合
            foreach($this->cartesian($sets) as $comb)
            {
                yield array_merge([$value], $comb);
            }
        }
    }
}
