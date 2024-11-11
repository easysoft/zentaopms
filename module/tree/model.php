<?php
declare(strict_types=1);
/**
 * The model file of tree module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     tree
 * @version     $Id: model.php 5149 2013-07-16 01:47:01Z zhujinyonging@gmail.com $
 * @link        https://www.zentao.net
 */
class treeModel extends model
{
    /**
     * 通过ID获取模块信息。
     * Get module by ID.
     *
     * @param  int    $moduleID
     * @access public
     * @return object
     */
    public function getByID(int $moduleID)
    {
        return $this->dao->findById((int)$moduleID)->from(TABLE_MODULE)->fetch();
    }

    /**
     * 获取模块列表(全路径名称)
     * Get all module pairs with path.
     *
     * @access public
     * @return object
     */
    public function getAllModulePairs(string $type = 'task')
    {
        $modules = $this->dao->select('*')->from(TABLE_MODULE)
            ->where('(type')->eq('story')
            ->beginIF($type == 'task')->orWhere('type')->eq('task')->fi()
            ->beginIF($type == 'bug')->orWhere('type')->eq('bug')->fi()
            ->beginIF($type == 'case')->orWhere('type')->eq('case')->fi()
            ->beginIF($type == 'ticket')->orWhere('type')->eq('ticket')->fi()
            ->beginIF($type == 'feedback')->orWhere('type')->eq('feedback')->fi()
            ->markRight(1)
            ->andWhere('deleted')->eq('0')
            ->orderBy('grade asc')
            ->fetchAll();

        $pairs    = array();
        $pairs[0] = '/';
        foreach($modules as $module)
        {
            if($module->grade == 1)
            {
                $pairs[$module->id] = '/' . $module->name;
                continue;
            }

            $moduleName = '/' . $module->name;
            $pairs[$module->id] = isset($pairs[$module->parent]) ? $pairs[$module->parent] . $moduleName : $moduleName;
        }

        return $pairs;
    }

    /**
     * 构建SQL查询语句。
     * Build the sql query.
     *
     * @param  int    $rootID
     * @param  string $type
     * @param  int    $startModule
     * @param  string $branch
     * @param  string $param
     * @param  int    $grade
     * @access public
     * @return string
     */
    public function buildMenuQuery(int $rootID, string $type, int $startModule = 0, string $branch = 'all', string $param = 'nodeleted', int $grade = 0): string
    {
        /* Set the start module. */
        $startModulePath = '';
        if($startModule > 0)
        {
            $startModule = $this->getById($startModule);
            if($startModule) $startModulePath = $startModule->path . '%';
        }

        /* If feedback module is merge add story module.*/
        $syncConfig = $this->getSyncConfig($type);

        if(($type == 'feedback' || $type == 'ticket') && strpos($param, 'noproduct') === false && isset($syncConfig[$rootID])) $type  = 'story,' . $type;
        if($this->isMergeModule($rootID, $type))
        {
            return $this->dao->select('id, name, root, branch, grade, path, parent, owner, type')->from(TABLE_MODULE)
                ->where('root')->eq((int)$rootID)
                ->beginIF($type == 'task')->andWhere('type')->eq('task')->fi()
                ->beginIF($type != 'task')->andWhere('type')->in("story,$type")->fi()
                ->beginIF($startModulePath)->andWhere('path')->like($startModulePath)->fi()
                ->beginIF($branch !== 'all' && $branch !== '' && strpos($param, 'noMainBranch') === false)
                ->andWhere("(branch")->eq(0)
                ->orWhere('branch')->eq($branch)
                ->markRight(1)
                ->fi()
                ->beginIF($branch !== 'all' && $branch !== '' && strpos($param, 'noMainBranch') !== false)
                ->andWhere('branch')->eq($branch)
                ->fi()
                ->beginIF(strpos($param, 'nodeleted') !== false)->andWhere('deleted')->eq(0)->fi()
                ->orderBy('grade desc, `order`, type desc')
                ->get();
        }

        /* $createdVersion < 4.1 or $type == 'story'. */
        return $this->dao->select('*')->from(TABLE_MODULE)
            ->where('1=1')
            ->beginIF($type != 'feedback' || !empty($rootID))->andwhere('root')->eq((int)$rootID)->fi()
            ->andWhere('type')->in($type)
            ->beginIF($grade)->andWhere('grade')->le($grade)->fi()
            ->beginIF($startModulePath)->andWhere('path')->like($startModulePath)->fi()
            ->beginIF($branch !== 'all' && $branch !== '' && strpos($param, 'noMainBranch') === false)
            ->andWhere('(branch')->eq(0)
            ->orWhere('branch')->eq($branch)
            ->markRight(1)
            ->fi()
            ->beginIF($branch !== 'all' && $branch !== '' && strpos($param, 'noMainBranch') !== false)
            ->andWhere('branch')->eq($branch)
            ->fi()
            ->beginIF(strpos($param, 'nodeleted') !== false)->andWhere('deleted')->eq(0)->fi()
            ->orderBy('grade desc, `order`')
            ->get();
    }

    /**
     * 获取某个分支模块的下拉菜单。
     * Get option menu by branch.
     *
     * @param  int    $rootID
     * @param  string $type
     * @param  int    $startModule
     * @param  string $branch
     * @param  string $param
     * @param  string $grade
     * @param  string $divide /|>
     * @access public
     * @return array
     */
    protected function getOptionMenuByBranch(int $rootID, string $type = 'story', int $startModule = 0, string $branch = 'all', string $param = 'nodeleted', string $grade = 'all', string $divide = '/'): array
    {
        $branches = array($branch => '');
        if($branch != 'all' && strpos('story|bug|case', $type) !== false)
        {
            $product = $this->loadModel('product')->getById($rootID);
            if($product && $product->type == 'normal') $branches = array('0' => '');
            if($product && $product->type != 'normal')
            {
                $branchPairs = $this->loadModel('branch')->getPairs($rootID, 'all');
                foreach(explode(',', (string)$branch) as $branchID)
                {
                    $branchID = (int)$branchID;
                    $branches[$branchID] = $branchPairs[$branchID];
                }
            }
        }

        $syncConfig = $this->getSyncConfig($type); // If feedback or ticket module is merge add story module.

        $treeMenu = array();
        foreach($branches as $branchID => $branch)
        {
            $stmt    = $this->app->dbQuery($this->buildMenuQuery($rootID, $type, $startModule, (string)$branchID, $param));
            $modules = array();
            while($module = $stmt->fetch())
            {
                if(($type == 'feedback' || $type == 'ticket') && $module->type == 'story') // If is feedback or ticket filter story module by grade.
                {
                    if(isset($syncConfig[$module->root]) && $module->grade > $syncConfig[$module->root]) continue;
                }
                if($grade != 'all' && $module->grade > $grade) continue;
                $modules[$module->id] = $module;
            }

            foreach($modules as $module)
            {
                $branchName = (isset($product) && $product->type != 'normal' && (string)$module->branch === BRANCH_MAIN) ? $this->lang->branch->main : $branch;
                $this->buildTreeArray($treeMenu, $modules, $module, (empty($branchName)) ? '/' : "/$branchName/", $divide);
            }
        }

        ksort($treeMenu);
        $topMenu  = explode("\n", trim((string)array_shift($treeMenu)));
        $lastMenu = array(0 => '/');
        foreach($topMenu as $menu)
        {
            if(!strpos($menu, '|')) continue;
            list($label, $moduleID) = explode('|', $menu);
            $lastMenu[$moduleID] = $label;
        }

        return $lastMenu;
    }

    /**
     * 获取模块的下拉菜单。
     * Get option menu.
     *
     * @param  int          $rootID
     * @param  string       $type
     * @param  int          $startModule
     * @param  string|array $branch
     * @param  string       $param
     * @param  string       $grade
     * @param  string       $divide /|>
     * @access public
     * @return array
     */
    public function getOptionMenu(int $rootID, string $type = 'story', int $startModule = 0, string|array $branch = 'all', string $param = 'nodeleted', string $grade = 'all', string $divide = '/'): array
    {
        /* 新手引导。 Tutorial. */
        if(commonModel::isTutorialMode())
        {
            $modulePairs = $this->loadModel('tutorial')->getModulePairs();
            if(!is_array($branch)) return $modulePairs;

            $modules = array();
            foreach($branch as $branchID) $modules[$branchID] = $modulePairs;
            return $modules;
        }

        if($type == 'line') $rootID = 0;

        if(is_array($branch))
        {
            $modules = array();
            foreach($branch as $branchID) $modules[$branchID] = $this->getOptionMenuByBranch($rootID, $type, $startModule, (string)$branchID, $param, $grade, $divide);

            return $modules;
        }

        return $this->getOptionMenuByBranch($rootID, $type, $startModule, $branch, $param, $grade, $divide);
    }

    /**
     * 获取模块对列表。
     * Get module pairs.
     *
     * @param  int    $rootID
     * @param  string $viewType
     * @param  string $showModule
     * @access public
     * @return array
     */
    public function getModulePairs(int $rootID, string $viewType = 'story', string $showModule = 'end', string $extra = '')
    {
        if(common::isTutorialMode()) $modulePairs = $this->loadModel('tutorial')->getModulePairs();

        if($viewType == 'task')
        {
            $products = array_keys($this->loadModel('product')->getProductPairsByProject($rootID));
            if(!$this->isMergeModule($rootID, $viewType) || !$products)
            {
                $modules = $this->dao->select('id,name,path,short')->from(TABLE_MODULE)->where('root')->eq($rootID)->andWhere('type')->in($viewType)->andWhere('deleted')->eq(0)->fetchAll('id');
            }
            else
            {
                $modules = $this->dao->select('id,name,path,short')->from(TABLE_MODULE)
                    ->where("((root = '" . (int)$rootID . "' && type = 'task')")
                    ->orWhere('(root')->in($products)->andWhere('type')->eq('story')
                    ->markRight(2)
                    ->andWhere('deleted')->eq(0)
                    ->fetchAll('id');
            }
        }
        else
        {
            /* When case with libIdList then append lib modules. */
            $modules = array();
            if($this->isMergeModule($rootID, $viewType) || !$rootID) $viewType .= ',story';
            $modules += $this->dao->select('id,name,path,short')->from(TABLE_MODULE)
                ->where('type')->in($viewType)
                ->beginIF($rootID)->andWhere('root')->eq($rootID)->fi()
                ->andWhere('deleted')->eq(0)
                ->fetchAll('id');
        }

        $modulePairs = array();
        foreach($modules as $moduleID => $module)
        {
            list($baseModule) = explode(',', trim($module->path, ','));
            if($showModule == 'base' && isset($modules[$baseModule])) $module = $modules[$baseModule];
            $modulePairs[$moduleID] = $module->short ? $module->short : $module->name;
        }

        return $modulePairs;
    }

    /**
     * 获取项目的模块下拉菜单。
     * Get an option menu of task in html.
     *
     * @param  int    $rootID
     * @param  int    $startModule
     * @param  string $extra
     * @access public
     * @return array
     */
    public function getTaskOptionMenu(int $rootID, int $startModule = 0, string $extra = ''): array
    {
        /* If createdVersion <= 4.1, go to getOptionMenu(). */
        $products     = $this->loadModel('product')->getProductPairsByProject($rootID);
        $branchGroups = $this->loadModel('branch')->getByProducts(array_keys($products), 'noclosed');

        if(!$this->isMergeModule($rootID, 'task') || !$products) return $this->getOptionMenu($rootID, 'task', $startModule);

        /* createdVersion > 4.1. */
        $startModulePath = '';
        if($startModule > 0)
        {
            $startModule = $this->getById($startModule);
            if($startModule) $startModulePath = $startModule->path . '%';
        }
        $treeMenu   = array();
        $lastMenu[] = '/';
        $executionModules = $this->getTaskTreeModules($rootID, true);
        $noProductModules = $this->dao->select('*')->from(TABLE_MODULE)->where('root')->eq($rootID)->andWhere('type')->eq('task')->andWhere('parent')->eq(0)->andWhere('deleted')->eq(0)->orderBy('grade desc, branch, `order`, type')->fetchPairs('id', 'name');

        /* Fix for not in product modules. */
        $productNum = count($products);
        foreach(array('product' => $products, 'noProduct' => $noProductModules) as $type => $rootModules)
        {
            foreach($rootModules as $id => $rootModule)
            {
                $activeBranch = isset($branchGroups[$id]) ? array_keys($branchGroups[$id]) : array();
                if($type == 'product')
                {
                    $modules = $this->dao->select('*')->from(TABLE_MODULE)->where("((root = '" . (int)$rootID . "' && type = 'task' && parent != 0) OR (root = $id && type = 'story'))")
                        ->beginIF($startModulePath)->andWhere('path')->like($startModulePath)->fi()
                        ->beginIF($activeBranch)->andWhere('branch')->in($activeBranch)->fi()
                        ->andWhere('deleted')->eq(0)
                        ->orderBy('grade desc, branch, `order`, type')
                        ->fetchAll('id');
                }
                else
                {
                    $modules = $this->dao->select('*')->from(TABLE_MODULE)->where('root')->eq((int)$rootID)
                        ->andWhere('type')->eq('task')
                        ->andWhere('path')->like("%,$id,%")
                        ->beginIF($startModulePath)->andWhere('path')->like($startModulePath)->fi()
                        ->andWhere('deleted')->eq(0)
                        ->orderBy('grade desc, `order`, type')
                        ->fetchAll('id');
                }

                foreach($modules as $module)
                {
                    $parentModules = explode(',', trim($module->path, ','));
                    if($type == 'product' && isset($noProductModules[$parentModules[0]])) continue;
                    /* Fix bug #2007. */
                    if($type == 'product' && $module->type == 'task' && !isset($modules[$parentModules[0]])) continue;
                    $rootName = ($productNum > 1 && $type == 'product') ? "/$rootModule/" : '/';
                    if($type == 'product' && $module->branch && isset($branchGroups[$id][$module->branch])) $rootName .= $branchGroups[$id][$module->branch] . '/';
                    $this->buildTreeArray($treeMenu, $modules, $module, $rootName);
                }

                ksort($treeMenu);
                $topMenu = array_shift($treeMenu);
                $topMenu = explode("\n", trim((string)$topMenu));
                foreach($topMenu as $menu)
                {
                    if(!strpos($menu, '|')) continue;
                    list($label, $moduleID) = explode('|', $menu);
                    if(isset($executionModules[$moduleID]) || strpos($extra, 'allModule') !== false) $lastMenu[$moduleID] = $label;
                }
                foreach($topMenu as $moduleID => $moduleName)
                {
                    if(!isset($executionModules[$moduleID])) unset($treeMenu[$moduleID]);
                }
            }
        }
        return $lastMenu;
    }

    /**
     * 构建模块树数组。
     * Build tree array.
     *
     * @param  array  $&treeMenu
     * @param  array  $modules
     * @param  object $module
     * @param  string $moduleName
     * @param  string $divide
     * @access protected
     * @return void
     */
    protected function buildTreeArray(array & $treeMenu, array $modules, object $module, string $moduleName = '/', string $divide = '/'): void
    {
        $parentModules = array_filter(explode(',', $module->path));
        foreach($parentModules as $parentModuleID)
        {
            if(empty($parentModuleID)) continue;
            if(empty($modules[$parentModuleID])) continue;
            $moduleName .= $modules[$parentModuleID]->name . $divide;
        }
        $moduleName = rtrim($moduleName, $divide);
        $moduleName .= "|$module->id\n";

        if(isset($treeMenu[$module->id]) && !empty($treeMenu[$module->id]))
        {
            if(isset($treeMenu[$module->parent]))
            {
                $treeMenu[$module->parent] .= $moduleName;
            }
            else
            {
                $treeMenu[$module->parent] = $moduleName;
            }
            $treeMenu[$module->parent] .= $treeMenu[$module->id];
        }
        else
        {
            if(isset($treeMenu[$module->parent]) && !empty($treeMenu[$module->parent]))
            {
                $treeMenu[$module->parent] .= $moduleName;
            }
            else
            {
                $treeMenu[$module->parent] = $moduleName;
            }
        }
    }

    /**
     * 获取模块树菜单。
     * Get the tree menu.
     *
     * @param  int    $rootID
     * @param  string $type
     * @param  int    $startModule
     * @param  array  $userFunc     the function used to create link
     * @param  array  $extra        extra params
     * @param  string $branch       product branch
     * @access public
     * @return array
     */
    public function getTreeMenu(int $rootID, string $type = 'root', int $startModule = 0, array $userFunc = array(), array|string $extra = array(), string $branch = 'all'): array
    {
        if($type == 'line') $rootID = 0;

        $this->loadModel('branch');
        $projectID        = zget($extra, 'projectID', 0);
        $branches         = array($branch => '');
        $executionModules = array();
        if($branch && empty($projectID))
        {
            $branchName = $this->branch->getByID($branch);
            $branches   = array($branch => $branchName);
            $extra      = $userFunc[1] == 'createTestTaskLink' ? $extra : array('rootID' => $rootID, 'branch' => $branch);
        }

        $product = $this->loadModel('product')->getByID($rootID);

        $onlyGetLinked = ($projectID && $this->config->vision != 'lite');
        if(strpos('story|bug|case', $type) !== false && $branch === 'all' && empty($projectID))
        {
            if($product->type != 'normal') $branches = array(BRANCH_MAIN => $this->lang->branch->main) + $this->loadModel('branch')->getPairs($rootID, 'noempty');
        }
        elseif(strpos(',case,bug,', ",$type,") !== false && $this->app->tab == 'execution')
        {
            if($product->type != 'normal' && $projectID) $branches += $this->branch->getPairs($product->id, 'noempty', $projectID);
            if($onlyGetLinked) $executionModules = $this->getTaskTreeModules($projectID, true, $type, array('branchID' => $branch));
        }
        elseif(($type == 'story' && $this->app->rawModule == 'projectstory') || (strpos(',case,bug,', ",$type,") !== false && $this->app->tab == 'project'))
        {
            if($product->type != 'normal' && $projectID) $branches += $this->branch->getPairs((int)$product->id, 'noempty', $projectID);
            if($onlyGetLinked) $executionModules = $this->getTaskTreeModules($projectID, true, $type, $type == 'story' ? array() : array('branchID' => $branch));
        }

        /* Add for task #1945. check the module has case or no. */
        if($type == 'case' && !empty($extra)) $this->loadModel('testtask');

        $modules = array();
        $stmt    = $this->app->dbQuery($this->buildMenuQuery($rootID, $type, $startModule, $branch));
        while($module = $stmt->fetch())
        {
            if($onlyGetLinked && !isset($executionModules[$module->id]) && empty($product->shadow)) continue;

            $data = $this->buildTree($module, $type, '0', $userFunc, $extra, $branch);
            if($data) $modules[] = $data;
        }

        return $modules;
    }

    /**
     * 获取任务模块树菜单。
     * Get the tree menu of task.
     *
     * @param  int    $rootID
     * @param  int    $productID
     * @param  int    $startModule
     * @param  array  $userFunc
     * @param  string $extra
     * @access public
     * @return array
     */
    public function getTaskTreeMenu(int $rootID, int $productID = 0, int $startModule = 0, array $userFunc = array(), string $extra = ''): array
    {
        $extra = array('tip' => true, 'extra' => $extra, 'executionID' => $rootID, 'productID' => $productID);

        /* If createdVersion <= 4.1, go to getTreeMenu(). */
        $products      = $this->loadModel('product')->getProductPairsByProject($rootID);
        $branchGroups  = $this->loadModel('branch')->getByProducts(array_keys($products));

        if(!$this->isMergeModule($rootID, 'task') || !$products)
        {
            $extra['tip'] = false;
            return (array)$this->getTreeMenu($rootID, 'task', $startModule, $userFunc, $extra);
        }

        /* createdVersion > 4.1. */
        $menu = array();

        /* Set the start module. */
        $startModulePath = '';
        if($startModule > 0)
        {
            $startModule = $this->getById($startModule);
            if($startModule) $startModulePath = $startModule->path . '%';
        }

        $executionModules = $this->getTaskTreeModules($rootID, true);

        /* Get module according to product. */
        $productNum = count($products);
        foreach($products as $id => $product)
        {
            /* 产品名也要放在树状菜单上。Show the product in tree menu. */
            $menuItem = new stdclass();
            $menuItem->id     = "product-$id";
            $menuItem->name   = $product;
            $menuItem->parent = 0;
            $menuItem->url    = helper::createLink('execution', 'task', "executionID=$rootID&status=byProduct&praram=$id");
            $menu["product-$id"] = $menuItem;

            /* tree menu. */
            if(empty($branchGroups[$id])) $branchGroups[$id]['0'] = '';
            foreach($branchGroups[$id] as $branch => $branchName)
            {
                $query = $this->dao->select('*')->from(TABLE_MODULE)->where("((root = '" . (int)$rootID . "' && type = 'task' && parent != 0) OR (root = $id && type = 'story' && branch ='$branch'))")
                    ->beginIF($startModulePath)->andWhere('path')->like($startModulePath)->fi()
                    ->andWhere('deleted')->eq(0)
                    ->orderBy('grade desc, `order`, type')
                    ->get();
                $stmt = $this->app->dbQuery($query);
                while($module = $stmt->fetch())
                {
                    if(!isset($executionModules[$module->id]) && strpos($extra['extra'], 'allModule') === false) continue;

                    if($module->type == 'story' && $module->root > 0 && $module->parent == 0) $module->parent = "product-{$module->root}";
                    $module->url = helper::createLink('execution', 'task', "executionID={$rootID}&type=byModule&param={$module->id}");
                    $menu[$module->id] = $module;
                }
            }
        }

        /* Get execution module. */
        if($startModule == 0)
        {
            $query = $this->dao->select('*')->from(TABLE_MODULE)
                ->where('root')->eq((int)$rootID)
                ->andWhere('type')->eq('task')
                ->andWhere('deleted')->eq(0)
                ->orderBy('grade desc, `order`, type')
                ->get();
            $stmt  = $this->app->dbQuery($query);
            while($module = $stmt->fetch())
            {
                $module->url = helper::createLink('execution', 'task', "executionID={$rootID}&type=byModule&param={$module->id}");
                $menu[$module->id] = $module;
            }
        }

        return $menu;
    }

    /**
     * 获取 bug 的模块。
     * Get the modules of bug.
     *
     * @param  int    $rootID
     * @param  int    $productID
     * @param  int    $startModule
     * @param  array  $userFunc
     * @param  array  $extra
     * @access public
     * @return void
     */
    public function getBugTreeMenu(int $rootID, int $productID = 0, int $startModule = 0, array $userFunc = array(), array $extra = array()): array
    {
        $extra += array('executionID' => $rootID, 'projectID' => $rootID, 'productID' => $productID, 'tip' => true);
        $tab    = $this->app->tab;

        /* If createdVersion <= 4.1, go to getTreeMenu(). */
        $products     = $tab == 'execution' ? $this->loadModel('product')->getProducts($rootID) : $this->loadModel('product')->getProductPairsByProject($rootID);
        $branchGroups = $this->loadModel('branch')->getByProducts(array_keys($products));

        /* Set the start module. */
        $startModulePath = '';
        if($startModule > 0)
        {
            $startModule = $this->getByID($startModule);
            if($startModule) $startModulePath = $startModule->path . '%';
        }

        $executionModules = $this->getTaskTreeModules($rootID, true, 'bug');

        /* Get module according to product. */
        $moduleName = strpos(',project,execution,', ",$tab,") !== false ? $this->app->tab  : 'bug';
        $methodName = strpos(',project,execution,', ",$tab,") !== false ? 'bug' : 'browse';
        $param      = strpos(',project,execution,', ",$tab,") !== false ? "{$tab}ID={$rootID}&" : '';

        $modules = array();
        foreach($products as $productID => $product)
        {
            $data = new stdclass();
            $data->id     = uniqid();
            $data->parent = '0';
            $data->name   = is_object($product) ? $product->name : $product;
            $data->url    = helper::createLink($moduleName, $methodName, "{$param}productID=$productID");
            $modules[] = $data;

            if(empty($branchGroups[$productID])) $branchGroups[$productID]['0'] = '';
            foreach($branchGroups[$productID] as $branch => $branchName)
            {
                $query = $this->dao->select('*')->from(TABLE_MODULE)->where("((root = $productID && type = 'bug' && branch = '$branch') OR (root = $productID && type = 'story' && branch = '$branch'))")
                    ->beginIF($startModulePath)->andWhere('path')->like($startModulePath)->fi()
                    ->andWhere('deleted')->eq(0)
                    ->orderBy('grade desc, `order`, type')
                    ->get();
                $stmt = $this->app->dbQuery($query);
                while($module = $stmt->fetch())
                {
                    if(isset($executionModules[$module->id])) $modules[] = $this->buildTree($module, 'bug', $data->id, $userFunc, $extra, (string)$branch);
                }
            }
        }

        return $modules;
    }

    /**
     * 获取用例模块树菜单。
     * Get the tree menu of case in html.
     *
     * @param  int    $rootID
     * @param  int    $productID
     * @param  int    $startModule
     * @param  array  $userFunc
     * @param  string $extra
     * @access public
     * @return array
     */
    public function getCaseTreeMenu(int $rootID, int $productID = 0, int $startModule = 0, array $userFunc = array(), string $extra = ''): array
    {
        $extra = array('projectID' => $rootID, 'executionID' => $rootID, 'productID' => $productID, 'tip' => true, 'extra' => $extra);

        /* If createdVersion <= 4.1, go to getTreeMenu(). */
        $products     = $this->app->tab != 'execution' ? $this->loadModel('product')->getProductPairsByProject($rootID) : $this->loadModel('product')->getProducts($rootID);
        $branchGroups = $this->dao->select('t1.product as product,branch,t3.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->leftJoin(TABLE_BRANCH)->alias('t3')->on('t1.branch=t3.id')
            ->where('t1.project')->eq($rootID)
            ->andWhere('t2.type')->ne('normal')
            ->andWhere('t1.product')->in(array_keys($products))
            ->andWhere('t2.deleted')->eq('0')
            ->fetchGroup('product', 'branch');

        $this->app->loadLang('branch');
        foreach($branchGroups as $productID => $branches)
        {
            $branchGroups[$productID][0] = $this->lang->branch->main;
            foreach($branches as $branchID => $branchInfo)
            {
                $branchGroups[$productID][$branchID] = $branchID == BRANCH_MAIN ? $this->lang->branch->main : $branchInfo->name;
            }
        }

        /* Set the start module. */
        $startModulePath = '';
        if($startModule > 0)
        {
            $startModule = $this->getById($startModule);
            if($startModule) $startModulePath = $startModule->path . '%';
        }

        $executionModules = $this->getTaskTreeModules($rootID, true, 'case');

        /* Get module according to product. */
        $productNum = count($products);
        $moduleName = strpos(',project,execution,', ",{$this->app->tab},") !== false ? $this->app->tab  : 'testcase';
        $methodName = strpos(',project,execution,', ",{$this->app->tab},") !== false ? 'testcase' : 'browse';
        $param      = $this->app->tab == 'project' ? "projectID={$this->session->project}&" : '';
        $param      = $this->app->tab == 'execution' ? "executionID={$rootID}&" : $param;

        $modules = array();
        foreach($products as $productID => $product)
        {
            $extra['productID'] = $productID;

            $data = new stdclass();
            $data->id     = uniqid();
            $data->parent = 0;
            $data->name   = is_object($product) ? $product->name : $product;
            $data->url    = helper::createLink($moduleName, $methodName, $param . "productID=$productID");
            $modules[] = $data;

            if(empty($branchGroups[$productID])) $branchGroups[$productID]['0'] = '';
            foreach($branchGroups[$productID] as $branch => $branchName)
            {
                $query = $this->dao->select('*')->from(TABLE_MODULE)->where("((root = $productID && type = 'case' && branch = '$branch') OR (root = $productID && type = 'story' && branch = '$branch'))")
                    ->beginIF($startModulePath)->andWhere('path')->like($startModulePath)->fi()
                    ->andWhere('deleted')->eq(0)
                    ->orderBy('grade desc, `order`, type')
                    ->get();
                $stmt = $this->app->dbQuery($query);
                while($module = $stmt->fetch())
                {
                    if(isset($executionModules[$module->id])) $modules[] = $this->buildTree($module, 'case', $data->id, $userFunc, $extra, (string)$branch);
                }
            }
        }
        return $modules;
    }

    /**
     * 获取项目关联的story。
     * Get project story tree menu.
     *
     * @param  int          $rootID
     * @param  int          $startModule
     * @param  string|array $userFunc
     * @param  array        $extra
     * @access public
     * @return array
     */
    public function getProjectStoryTreeMenu(int $rootID, int $startModule = 0, string|array$userFunc = '', array $extra = array()): array
    {
        $this->app->loadLang('branch');

        $menu = array();

        if($this->app->rawModule == 'projectstory') $extra['projectID'] = $rootID;
        if($this->app->rawModule == 'execution') $extra['executionID'] = $rootID;
        $startModulePath = '';
        if($startModule > 0)
        {
            $startModule = $this->getById($startModule);
            if($startModule) $startModulePath = $startModule->path . '%';
        }

        $executionModules = $this->getTaskTreeModules($rootID, true);

        /* Get module according to product. */
        $products     = $this->loadModel('product')->getProductPairsByProject($rootID);
        $branchGroups = $this->loadModel('execution')->getBranchByProduct(array_keys($products), $rootID);

        $productNum = count($products);
        $storyType  = zget($extra, 'storyType', 'story');
        foreach($products as $id => $product)
        {
            $extra['productID']   = $id;
            $projectProductLink   = helper::createLink('projectstory', 'story', "projectID=$rootID&productID=$id&branch=all&browseType=&param=0&storyType={$storyType}");
            $executionProductLink = helper::createLink('execution', 'story', "executionID=$rootID&storyType={$storyType}&orderBy=&type=byProduct&praram=$id");
            $link = $this->app->rawModule == 'projectstory' ? $projectProductLink : $executionProductLink;
            if($productNum > 1)
            {
                $data = new stdclass();
                $data->id     = "p_{$id}";
                $data->parent = '0';
                $data->name   = $product;
                $data->url    = $link;

                $menu[] = $data;
            }

            /* tree menu. */
            $branchGroups[$id][BRANCH_MAIN] = empty($branchGroups[$id]) ? '' : $this->lang->branch->main;
            foreach($branchGroups[$id] as $branch => $branchName)
            {
                $treeMenu = array();
                $query = $this->dao->select('*')->from(TABLE_MODULE)
                    ->where('root')->eq((int)$id)
                    ->andWhere('type')->eq('story')
                    ->beginIF(count($branchGroups[$id]) > 1)->andWhere('branch')->eq($branch)->fi()
                    ->beginIF($startModulePath)->andWhere('path')->like($startModulePath)->fi()
                    ->andWhere('deleted')->eq(0)
                    ->orderBy('grade desc, branch, `order`, type')
                    ->get();
                $stmt = $this->app->dbQuery($query);
                while($module = $stmt->fetch())
                {
                    /* Ignore unused modules. */
                    if(!isset($executionModules[$module->id]) && $this->config->vision != 'lite') continue;

                    $treeMenu = $this->buildTree($module, 'story', '0', $userFunc, $extra);
                    if($productNum > 1 && $module->parent == 0) $treeMenu->parent = "p_{$module->root}";
                    $menu[] = $treeMenu;
                }
            }
        }

        return $menu;
    }

    /**
     * 获取主机模块树菜单。
     * Get host tree menu.
     *
     * @access public
     * @return array
     */
    public function getHostTreeMenu(): array
    {
        $menu = array();
        /* tree menu. */
        $treeMenu = array();
        $stmt = $this->dao->select('*')->from(TABLE_MODULE)
            ->where('type')->eq('host')
            ->andWhere('deleted')->eq(0)
            ->orderBy('grade_desc,id_asc')
            ->query();

        while($module = $stmt->fetch())
        {
            $treeMenu = $this->buildTree($module, '', '0', array('treeModel', 'createHostLink'));
            if($module->parent == 0) $treeMenu->parent = $module->root;

            $menu[] = $treeMenu;
        }

        return $menu;
    }

    /**
     * 获取模块树。
     * Get tree structure.
     *
     * @param  int    $rootID
     * @param  string $type
     * @access public
     * @return array
     */
    public function getTreeStructure(int $rootID, string $type): array
    {
        $stmt = $this->app->dbQuery($this->buildMenuQuery($rootID, $type, $startModule = 0));
        return $this->getDataStructure($stmt, $type, $rootID);
    }

    /**
     * 获取某个项目的所有任务模块树。
     * Get full task tree
     * @param  int $executionID, common value is execution id
     * @param  int $productID
     * @access public
     * @return array
     */
    public function getTaskStructure(int $rootID, int $productID = 0): array
    {
        $extra = array('executionID' => $rootID, 'productID' => $productID, 'tip' => true);

        /* If createdVersion <= 4.1, go to getTreeMenu(). */
        $products      = $this->loadModel('product')->getProductPairsByProject($rootID);
        $branchGroups  = $this->loadModel('branch')->getByProducts(array_keys($products));

        if(!$this->isMergeModule($rootID, 'task') || !$products)
        {
            $extra['tip'] = false;
            $stmt = $this->app->dbQuery($this->buildMenuQuery($rootID, 'task', $startModule = 0));
            if(empty($products))
            {
                $this->app->loadConfig('execution');
                $this->config->execution->task->allModule = 1;
            }
            return $this->getDataStructure($stmt, 'task', $rootID);
        }

        /* only get linked modules && ignore others. */
        $executionModules = $this->getTaskTreeModules($rootID, true);

        /* Get module according to product. */
        $fullTrees = array();
        foreach($products as $id => $product)
        {
            $productInfo = $this->product->getById($id);
            /* tree menu. */
            $productTree = array();
            $branchTrees = array();
            if(empty($branchGroups[$id])) $branchGroups[$id]['0'] = '';
            foreach($branchGroups[$id] as $branch => $branchName)
            {
                $query = $this->dao->select('id, name, type, grade, path, parent, root')->from(TABLE_MODULE)->where("((root = '" . (int)$rootID . "' && type = 'task' && parent != 0) OR (root = $id && type = 'story' && branch ='$branch'))")
                    ->andWhere('deleted')->eq(0)
                    ->orderBy('grade desc, `order`, type')
                    ->get();
                $stmt = $this->app->dbQuery($query);
                if($branch == 0) $productTree = $this->getDataStructure($stmt, 'task', $rootID, $executionModules);
                if($branch != 0)
                {
                    $children = $this->getDataStructure($stmt, 'task', $rootID, $executionModules);
                    if($children) $branchTrees[] = array('name' => $branchName, 'root' => $id, 'type' => 'branch', 'actions' => false, 'children' => $children);
                }
            }
            if($branchTrees) $productTree[] = array('name' => $this->lang->product->branchName[$productInfo->type], 'root' => $id, 'type' => 'branch', 'actions' => false, 'children' => $branchTrees);
            $fullTrees[] = array('name' => $productInfo->name, 'root' => $id, 'type' => 'product', 'actions' => false, 'children' => $productTree);
        }

        /* Get execution module. */
        $query = $this->dao->select('id,name,type,grade,path,parent')->from(TABLE_MODULE)
            ->where('root')->eq((int)$rootID)
            ->andWhere('type')->eq('task')
            ->andWhere('deleted')->eq(0)
            ->orderBy('grade desc, `order`, type')
            ->get();
        $stmt       = $this->app->dbQuery($query);
        $taskTrees  = $this->getDataStructure($stmt, 'task', $rootID, $executionModules);
        foreach($taskTrees as $taskModule) $fullTrees[] = $taskModule;

        return $fullTrees;
    }

    /**
     * 构造模块树。
     * Build tree.
     *
     * @param  object     $module
     * @param  string     $type
     * @param  string     $parent
     * @param  array      $userFunc
     * @param  array      $extra
     * @param  int        $branch
     * @access protected
     * @return object|false
     */
    protected function buildTree(object $module, string $type, string $parent = '0', array $userFunc = array(), array|string $extra = array(), string $branch = 'all'): object|false
    {
        /* Add for task #1945. check the module has case or no. */
        if((isset($extra['rootID']) && isset($extra['branch']) && $branch === 'null') || ($type == 'case' && is_numeric($extra)))
        {
            static $objects = array();
            if(empty($objects))
            {
                if(is_array($extra))
                {
                    $table   = $this->config->objectTables[$type];
                    $objects = $this->dao->select('module')->from($table)->where('product')->eq((int)$extra['rootID'])->andWhere('branch')->eq($extra['branch'])->fetchAll('module');
                }
                else
                {
                    $objects = $this->dao->select('t1.*,t2.module')->from(TABLE_TESTRUN)->alias('t1')
                        ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
                        ->where('t1.task')->eq((int)$extra)
                        ->fetchAll('module');
                }
            }
            static $modules = array();
            if(empty($modules))
            {
                $typeCondition = "type='story'";
                if($type != 'story') $typeCondition .= " || type='{$type}'";
                $modules = $this->dao->select('id,path')->from(TABLE_MODULE)->where('root')->eq($module->root)->andWhere("({$typeCondition})")->fetchPairs('id', 'path');
            }
            $childModules = array();
            foreach($modules as $moduleID => $modulePath)
            {
                if(strpos($modulePath, $module->path) === 0) $childModules[$moduleID] = $moduleID;
            }
            $hasObjects = false;
            foreach($childModules as $moduleID)
            {
                if(isset($objects[$moduleID]))
                {
                    $hasObjects = true;
                    break;
                }
            }
            if(!$hasObjects) return false;
        }

        if(is_array($extra)) $extra['branchID'] = $branch;
        if(empty($extra))
        {
            $extra = array();
            $extra['branchID'] = $branch;
        }

        return call_user_func($userFunc, $type, $module, $parent, $extra);
    }

    /**
     * 获取执行的模块。
     * Get execution modules.
     *
     * @param  int    $executionID
     * @param  bool   $parent
     * @param  string $linkObject
     * @param  array  $extra
     * @access public
     * @return array
     */
    public function getTaskTreeModules(int $executionID, bool $parent = false, string $linkObject = 'story', array $extra = array()): array
    {
        $field = $parent ? 'path' : 'id';
        $paths = array();

        if($linkObject)
        {
            $branch = zget($extra, 'branchID', 0);

            /* 获取执行下关联Bug、Story、Case的模块。 Get object paths of this execution. */
            if($linkObject == 'story' || $linkObject == 'case') // Get story or case from projectStory and projectCase, get bug from zt_bug
            {
                $table1 = TABLE_PROJECTSTORY;
                $table2 = TABLE_STORY;
                if($linkObject == 'case')
                {
                    $table1 = TABLE_PROJECTCASE;
                    $table2 = TABLE_CASE;
                }

                $paths = $this->dao->select("t4.{$field}")->from(TABLE_PROJECT)->alias('t1')
                    ->leftJoin($table1)->alias('t2')->on('t1.id=t2.project')
                    ->leftJoin($table2)->alias('t3')->on("t2.{$linkObject}=t3.id")
                    ->leftJoin(TABLE_MODULE)->alias('t4')->on('t3.module=t4.id')
                    ->where('t3.deleted')->eq(0)
                    ->andWhere('t4.deleted')->eq(0)
                    ->andWhere('t1.project', true)->eq($executionID)->orWhere('t2.project')->eq($executionID)->markRight(1)
                    ->beginIF(isset($extra['branchID']) && $branch !== 'all')->andWhere('t3.branch')->eq($branch)->fi()
                    ->fetchPairs();
            }
            elseif($linkObject == 'bug' && str_contains(',project,execution,', ",{$this->app->tab},"))
            {
                $paths = $this->dao->select("t2.{$field}")->from(TABLE_BUG)->alias('t1')
                    ->leftJoin(TABLE_MODULE)->alias('t2')->on('t1.module = t2.id')
                    ->where('t1.deleted')->eq(0)
                    ->andWhere('t2.deleted')->eq(0)
                    ->beginIF(isset($extra['branchID']) && $branch !== 'all')->andWhere('t1.branch')->eq($branch)->fi()
                    ->andWhere("t1.{$this->app->tab}")->eq($executionID)
                    ->fetchPairs();
            }
            else
            {
                return $paths;
            }
        }
        else
        {
            $productGroups = $this->dao->select('product,branch')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($executionID)->fetchGroup('product', 'branch');
            $modules       = $this->dao->select('id,root,branch')->from(TABLE_MODULE)->where('root')->in(array_keys($productGroups))->andWhere('type')->eq('story')->andWhere('deleted')->eq(0)->fetchAll();

            foreach($modules as $module)
            {
                if(empty($module->branch)) $paths[$module->id] = $module->id;
                if(isset($productGroups[$module->root][0]) || isset($productGroups[$module->root][$module->branch])) $paths[$module->id] = $module->id;
            }
        }

        if(!str_contains(',case,bug,', ",$linkObject,"))
        {
            /* Add task paths of this execution.*/
            $paths += $this->dao->select($field)->from(TABLE_MODULE)->where('root')->eq($executionID)->andWhere('type')->eq('task')->andWhere('deleted')->eq(0)->fetchPairs($field, $field);

            /* Add task paths of this execution for has existed. */
            $paths += $this->dao->select("t1.{$field}")->from(TABLE_MODULE)->alias('t1')
            ->leftJoin(TABLE_TASK)->alias('t2')->on('t1.id=t2.module')
            ->where('t2.module')->ne(0)
            ->andWhere('t2.execution')->eq($executionID)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.type')->eq('story')
            ->andWhere('t1.deleted')->eq(0)
            ->fetchPairs($field, $field);
        }

        /* Get all modules from paths. */
        $executionModules = array();
        foreach($paths as $path)
        {
            foreach(explode(',', (string)$path) as $module) $executionModules[$module] = $module;
        }

        return array_filter($executionModules);
    }

    /**
     * 生成需求链接。
     * Create link of a story.
     *
     * @param  string $type
     * @param  object $module
     * @param  string $parent
     * @param  array  $extra
     * @access public
     * @return object
     */
    public function createStoryLink(string $type, object $module, string $parent = '0', array $extra = array()): object
    {
        $data = new stdclass();
        $data->id     = $parent ? uniqid() : (string)$module->id;
        $data->parent = $parent ? $parent : (string)$module->parent;
        $data->name   = $module->name;

        if(isset($extra['projectID']) && !empty($extra['projectID']))
        {
            $productID = zget($extra, 'productID', 0);
            $projectID = $extra['projectID'];
            $data->url = helper::createLink('projectstory', 'story', "projectID=$projectID&productID=$productID&branch=&browseType=byModule&param={$module->id}&storyType=story");
        }
        elseif(isset($extra['executionID']) && !empty($extra['executionID']))
        {
            $executionID = $extra['executionID'];
            $data->url   = helper::createLink('execution', 'story', "executionID=$executionID&storyType=story&orderBy=order_desc&type=byModule&param={$module->id}");
        }
        else
        {
            $data->url = helper::createLink('product', 'browse', "root={$module->root}&branch={$extra['branchID']}&type=byModule&param={$module->id}&storyType=story");
        }

        return $data;
    }

    /**
     * 生成用户需求链接。
     * Create link of requirement for waterfall.
     *
     * @param  string $type
     * @param  object $module
     * @param  string $parent
     * @param  array  $extra
     * @access public
     * @return object
     */
    public function createRequirementLink(string $type, object $module, string $parent = '0', array $extra = array()): object
    {
        $data = new stdclass();
        $data->id     = $parent ? uniqid() : (string)$module->id;
        $data->parent = $parent ? $parent : (string)$module->parent;
        $data->name   = $module->name;

        if(isset($extra['projectID']) && !empty($extra['projectID']))
        {
            $productID = zget($extra, 'productID', 0);
            $projectID = $extra['projectID'];
            $data->url = helper::createLink('projectstory', 'story', "projectID=$projectID&productID=$productID&branch=&browseType=byModule&param={$module->id}&storyType=requirement");
        }
        elseif(isset($extra['executionID']) && !empty($extra['executionID']))
        {
            $executionID = $extra['executionID'];
            $data->url   = helper::createLink('execution', 'story', "executionID=$executionID&storyType=requirement&orderBy=order_desc&type=byModule&param={$module->id}");
        }
        else
        {
            $branch = isset($extra['branchID']) ? $extra['branchID'] : 'all';
            $data->url = helper::createLink('product', 'browse', "root={$module->root}&branch=$branch&type=byModule&param={$module->id}&storyType=requirement");
        }

        return $data;
    }

    /**
     * 生成业务需求链接。
     * Create link of epic for waterfall.
     *
     * @param  string $type
     * @param  object $module
     * @param  string $parent
     * @param  array  $extra
     * @access public
     * @return object
     */
    public function createEpicLink(string $type, object $module, string $parent = '0', array $extra = array()): object
    {
        $data = new stdclass();
        $data->id     = $parent ? uniqid() : (string)$module->id;
        $data->parent = $parent ? $parent : (string)$module->parent;
        $data->name   = $module->name;

        if(isset($extra['projectID']) && !empty($extra['projectID']))
        {
            $productID = zget($extra, 'productID', 0);
            $projectID = $extra['projectID'];
            $data->url = helper::createLink('projectstory', 'story', "projectID=$projectID&productID=$productID&branch=&browseType=byModule&param={$module->id}&storyType=epic");
        }
        elseif(isset($extra['executionID']) && !empty($extra['executionID']))
        {
            $executionID = $extra['executionID'];
            $data->url   = helper::createLink('execution', 'story', "executionID=$executionID&storyType=epic&orderBy=order_desc&type=byModule&param={$module->id}");
        }
        else
        {
            $branch = isset($extra['branchID']) ? $extra['branchID'] : 'all';
            $data->url = helper::createLink('product', 'browse', "root={$module->root}&branch=$branch&type=byModule&param={$module->id}&storyType=epic");
        }

        return $data;
    }

    /**
     * 生成任务链接。
     * Create link of a task.
     *
     * @param  string $type
     * @param  object $module
     * @access public
     * @return object
     */
    public function createTaskLink(string $type, object $module): object
    {
        $data = new stdclass();
        $data->id     = (string)$module->id;
        $data->parent = (string)$module->parent;
        $data->name   = $module->name;
        $data->url    = helper::createLink('execution', 'task', "executionID={$module->root}&type=byModule&param={$module->id}");

        return $data;
    }

    /**
     * 生成文档链接。
     * Create link of a doc.
     *
     * @param  string $type
     * @param  object $module
     * @access public
     * @return object
     */
    public function createDocLink(string $type, object $module): object
    {
        $data = new stdclass();
        $data->id     = $parent ? uniqid() : (string)$module->id;
        $data->parent = $parent ? $parent : (string)$module->parent;
        $data->name   = $module->name;
        $data->url    = helper::createLink('doc', 'browse', "libID={$module->root}&browseType=byModule&param={$module->id}");

        return $data;
    }

    /**
     * Create the manage link of a module.
     *
     * @param  string $type
     * @param  object $module
     * @access public
     * @return object
     */
    public function createManageLink(string $type, object $module): object
    {
        static $users;
        if(empty($users)) $users = $this->loadModel('user')->getPairs('noletter');

        $name = $module->name;
        if(strpos('bug,case', $type) !== false) $name .= '[' . strtoupper(substr($type, 0, 1)) . ']';
        if($type == 'bug' && $module->owner)    $name .= '[' . $users[$module->owner] . ']';

        $data = new stdclass();
        $data->id     = (string)$module->id;
        $data->parent = (string)$module->parent;
        $data->name   = $name;
        $data->url    = helper::createLink('subject', 'browse', "currentModuleID={$module->id}");

        return $data;
    }

    /**
     * 设置Bug模块树的点击链接。
     * Click link to set Bug module tree.
     *
     * @param  string $type
     * @param  object $module
     * @param  string $parent
     * @param  array  $extra
     * @access public
     * @return object
     */
    public function createBugLink(string $type, object $module, string $parent, array $extra = array()): object
    {
        $moduleName = strpos(',project,execution,', ",{$this->app->tab},") !== false ? $this->app->tab : 'bug';
        $methodName = strpos(',project,execution,', ",{$this->app->tab},") !== false ? 'bug' : 'browse';
        $param      = "root={$module->root}&branch=&type=byModule&param={$module->id}";

        $extraType = (isset($extra['type']) && $extra['type'] != 'bysearch') ? $extra['type'] : 'all';
        $projectID = zget($extra, 'projectID', 0);
        $branchID  = zget($extra, 'branchID', 0);
        $orderBy   = zget($extra, 'orderBy', '');
        $build     = zget($extra, 'build', 0);
        if($this->app->tab == 'execution') $param = "executionID={$projectID}&productID={$module->root}&branch={$branchID}&orderBy={$orderBy}&build={$build}&type={$extraType}&param={$module->id}";
        if($this->app->tab == 'project')   $param = "projectID={$projectID}&productID={$module->root}&branch={$branchID}&orderBy={$orderBy}&build={$build}&type={$extraType}&param={$module->id}";

        $data = new stdclass();
        $data->id     = (string)$module->id;
        $data->parent = $parent && empty($module->parent) ? $parent : (string)$module->parent;
        $data->name   = $module->name;
        $data->url    = helper::createLink($moduleName, $methodName, $param);

        return $data;
    }

    /**
     * 生成测试用例的链接。
     * Create module of a test case.
     *
     * @param  string       $type
     * @param  object       $module
     * @param  string       $parent
     * @param  array|string $extra
     * @access public
     * @return object
     */
    public function createCaseLink(string $type, object $module, string $parent, array|string $extra = array()): object
    {
        $moduleName  = strpos(',project,execution,', ",{$this->app->tab},") !== false ? $this->app->tab : 'testcase';
        $methodName  = strpos(',project,execution,', ",{$this->app->tab},") !== false ? 'testcase' : 'browse';
        $param       = $this->app->tab == 'project' ? "projectID={$this->session->project}&" : "";
        $param       = $this->app->tab == 'execution' ? "executionID={$extra['projectID']}&" : $param;
        $branch      = isset($extra['branchID']) ? $extra['branchID'] : 'all';
        $moduleParam = $this->app->tab == 'execution' ? "&param=0&moduleID={$module->id}" : "&param={$module->id}";

        $data = new stdclass();
        $data->id     = (string)$module->id;
        $data->parent = $parent && empty($module->parent) ? $parent : (string)$module->parent;
        $data->name   = $module->name;
        $data->url    = helper::createLink($moduleName, $methodName, $param . "productID={$module->root}&branch=$branch&browseType=byModule" . $moduleParam);

        return $data;
    }

    /**
     * 生成测试任务的链接。
     * Create link of a test task.
     *
     * @param  string       $type
     * @param  object       $module
     * @param  string       $parent
     * @param  array|string $extra
     * @access public
     * @return object
     */
    public function createTestTaskLink(string $type, object $module, string $parent, array|string $extra = array()): object
    {
        $data = new stdclass();
        $data->id     = $parent ? uniqid() : (string)$module->id;
        $data->parent = $parent ? $parent : (string)$module->parent;
        $data->name   = $module->name;
        $data->url    = helper::createLink('testtask', 'cases', "taskID=$extra&type=byModule&module={$module->id}");

        return $data;
    }

    /**
     * 生成用例库的链接。
     * Create case lib link.
     *
     * @param  string $type
     * @param  object $module
     * @access public
     * @return object
     */
    public function createCaseLibLink($type, $module): object
    {
        $data = new stdclass();
        $data->id     = $module->id;
        $data->parent = $module->parent;
        $data->name   = $module->name;
        $data->url    = helper::createLink('caselib', 'browse', "root={$module->root}&type=byModule&param={$module->id}");

        return $data;
    }

    /**
     * 生成分支的链接。
     * Create branch link
     *
     * @param  string $type
     * @param  int    $rootID
     * @param  int    $branchID
     * @param  string $branch
     * @access public
     * @return string
     */
    public function createBranchLink($type, $rootID, $branchID, $branch): string
    {
        if($type == 'story') return html::a(helper::createLink('product', 'browse', "productID={$rootID}&branch=$branchID"), $branch, '_self', "id='branch{$branchID}'");
        if($type == 'bug')   return html::a(helper::createLink('bug', 'browse', "productID={$rootID}&branch=$branchID"), $branch, '_self', "id='branch{$branchID}'");
        if($type == 'case')  return html::a(helper::createLink('testcase', 'browse', "productID={$rootID}&branch=$branchID"), $branch, '_self', "id='branch{$branchID}'");

        return '';
    }

    /**
     * 生成反馈的链接。
     * Create link of feedback.
     *
     * @param  string $type
     * @param  object $module
     * @access public
     * @return object
     */
    public function createFeedbackLink(string $type, object $module): object
    {
        $data = new stdclass();
        $data->id     = $module->id;
        $data->parent = $module->parent;
        $data->name   = $module->name;
        $data->url    = helper::createLink('feedback', $this->app->methodName, "type=byModule&param={$module->id}");

        return $data;
    }

    /**
     * 生成工单的链接。
     * Create link of ticket.
     *
     * @param  string $type
     * @param  object $module
     * @access public
     * @return object
     */
    public function createTicketLink(string $type, object $module): object
    {
        $data = new stdclass();
        $data->id     = $module->id;
        $data->parent = $module->parent;
        $data->name   = $module->name;
        $data->url    = helper::createLink('ticket', $this->app->methodName, "type=byModule&param={$module->id}");

        return $data;
    }

    /**
     * Create link of practice.
     *
     * @param  string $type
     * @param  object $module
     * @access public
     * @return string
     */
    public function createPracticeLink(string $type, object $module): string
    {
        return html::a(helper::createLink('traincourse', 'practicebrowse', "moduleID={$module->id}"), $module->name, '_self', "id='module{$module->id}' title='{$module->name}'");
    }

    /**
     * 生成培训技能的链接。
     * Create link of trainskill.
     *
     * @param  string $type
     * @param  object $module
     * @param  string $extra
     * @access public
     * @return string
     */
    public function createTrainSkillLink(string $type, object $module, string $extra = ''): string
    {
        return html::a(helper::createLink('trainskill', 'browse', "type=byModule&param={$module->id}"), $module->name, '', "id='module{$module->id}' title='{$module->name}'");
    }

    /**
     * 生成培训课程的链接。
     * Create link of traincourse.
     *
     * @param  string $type
     * @param  object $module
     * @param  string $extra
     * @access public
     * @return string
     */
    public function createTrainCourseLink(string $type, object $module, string $extra = ''): string
    {
        return html::a(helper::createLink('traincourse', 'browse', "type=byModule&param={$module->id}"), $module->name, '', "id='module{$module->id}' title='{$module->name}'");
    }

    /**
     * 生成培训评论的链接。
     * Create link of trainpost.
     *
     * @param  string $type
     * @param  object $module
     * @param  string $extra
     * @access public
     * @return string
     */
    public function createTrainPostLink(string $type, object $module, string $extra = ''): string
    {
        return html::a(helper::createLink('trainpost', 'browse', "type=byModule&param={$module->id}"), $module->name, '', "id='module{$module->id}' title='{$module->name}'");
    }

    /**
     * 生成仪表盘的链接。
     * Create dashboard link.
     *
     * @param  string $type
     * @param  object $module
     * @param  string $extra
     * @access public
     * @return string
     */
    public function createDashboardLink(string $type, object $module, string $extra = ''): string
    {
        return html::a(helper::createLink('dashboard', 'browse', "type=bymodule&param={$module->id}"), $module->name, '', "id='module{$module->id}' title='{$module->name}'");
    }

    /**
     * 生成报告的链接。
     * Create report link.
     *
     * @param  string $type
     * @param  object $module
     * @param  array  $extra
     * @access public
     * @return string
     */
    public function createReportLink(string $type, object $module, string $extra): string
    {
        $dimension = zget($extra, 'dimension', 0);
        return html::a(helper::createLink('report', 'browsereport', "dimension={$dimension}&module={$module->id}"), $module->name, '', "id='module{$module->id}' title='{$module->name}'");
    }

    /**
     * 生成主机的链接。
     * Create link of a host.
     *
     * @param  string $type
     * @param  object $module
     * @param  string $parent
     * @param  array  $extra
     * @access public
     * @return object
     */
    public function createHostLink(string $type, object $module, string $parent = '0', array $extra = array()): object
    {
        $data = new stdclass();
        $data->id     = $parent ? uniqid() : (string)$module->id;
        $data->parent = $parent ? $parent : (string)$module->parent;
        $data->name   = $module->name;

        $data->url = helper::createLink('host', 'browse', "browseType=bymodule&param={$module->id}");

        return $data;
    }

    /**
     * 获取一个模块的子模块。
     * Get sons of a module.
     *
     * @param  int    $rootID
     * @param  int    $moduleID
     * @param  string $type
     * @param  string $branch
     * @access public
     * @return array
     */
    public function getSons(int $rootID, int $moduleID, string $type = 'root', string $branch = '0'): array
    {
        if(common::isTutorialMode()) return array();

        $syncConfig = $this->getSyncConfig($type);

        if($type  == 'line') $rootID = 0;
        if(($type == 'feedback' || $type == 'ticket') && isset($syncConfig[$rootID])) $type = "$type,story";

        /* if createVersion <= 4.1 or type == 'story', only get modules of its type. */
        if(!$this->isMergeModule($rootID, $type) || $type == 'story')
        {

            return $this->dao->select('*')->from(TABLE_MODULE)
                ->where('root')->eq((int)$rootID)
                ->andWhere('parent')->eq((int)$moduleID)
                ->andWhere('type')->in($type)
                ->beginIF($branch !== 'all')
                ->andWhere("(branch")->eq(0)
                ->orWhere("branch")->eq((int)$branch)
                ->markRight(1)
                ->fi()
                ->andWhere('deleted')->eq(0)
                ->orderBy('`order`')
                ->fetchAll();
        }

        /* else get modules of its type && story type. */
        if(strpos('task|case|bug', $type) !== false) $type = "$type,story";

        return $this->dao->select('*')->from(TABLE_MODULE)
            ->where('root')->eq((int)$rootID)
            ->andWhere('parent')->eq((int)$moduleID)
            ->andWhere('type')->in($type)
            ->beginIF($branch !== 'all')
            ->andWhere("(branch")->eq(0)
            ->orWhere("branch")->eq((int)$branch)
            ->markRight(1)
            ->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy('`order`, type desc')
            ->fetchAll();
    }

    /**
     * 获取一个任务模块的子模块。
     * Get sons of a task module.
     *
     * @param  int    $rootID
     * @param  int    $productID
     * @param  int    $moduleID
     * @access public
     * @return array
     */
    public function getTaskSons(int $rootID, int $productID, int $moduleID): array
    {
        if($moduleID)
        {
            return $this->dao->select('*')->from(TABLE_MODULE)
                ->where('root')->eq((int)$rootID)
                ->andWhere('parent')->eq((int)$moduleID)
                ->andWhere('type')->in("task,story")
                ->andWhere('deleted')->eq(0)
                ->orderBy('`order`, type')
                ->fetchAll();
        }
        else
        {
            return $this->dao->select('*')->from(TABLE_MODULE)
                ->where('parent')->eq(0)
                ->andWhere('deleted')->eq(0)
                ->andWhere("(root = '" . (int)$rootID . "' && type = 'task'")
                ->orWhere("root = '" . (int)$productID . "' && type = 'story')")
                ->orderBy('`order`, type')
                ->fetchAll();
        }
    }

    /**
     * 获取所有子模块的ID列表。
     * Get id list of a module's children.
     *
     * @param  int     $moduleID
     * @access public
     * @return array
     */
    public function getAllChildId(int $moduleID): array
    {
        if($moduleID == 0) return array();

        $module = $this->getById((int)$moduleID);
        if(empty($module)) return array();

        return $this->dao->select('id')->from(TABLE_MODULE)->where("CONCAT(',', path, ',')")->like("%$module->path%")->andWhere('deleted')->eq(0)->fetchPairs();
    }

    /**
     * 获取一个模块的所有父模块。
     * Get parents of a module.
     *
     * @param  int    $moduleID
     * @param  bool   $queryAll
     * @access public
     * @return array
     */
    public function getParents(int $moduleID, bool $queryAll = false): array
    {
        if($moduleID == 0 || common::isTutorialMode()) return array();
        $path = $this->dao->select('path')->from(TABLE_MODULE)->where('id')->eq((int)$moduleID)->fetch('path');
        $path = trim($path, ',');
        if(!$path) return array();
        return $this->dao->select('*')->from(TABLE_MODULE)
            ->where('id')->in($path)
            ->beginIF(!$queryAll)->andWhere('deleted')->eq(0)->fi()
            ->orderBy('grade')
            ->fetchAll();
    }

    /**
     * 获取一个模块所属的产品。
     * Get product by moduleID.
     *
     * @param  int    $moduleID
     * @access public
     * @return object|false
     */
    public function getProduct(int $moduleID): object|false
    {
        if($moduleID == 0) return false;
        $path  = $this->dao->select('path')->from(TABLE_MODULE)->where('id')->eq($moduleID)->fetch('path');
        $paths = explode(',', trim($path, ','));
        if(!$path) return false;

        $moduleID = $paths[0];
        $module   = $this->dao->select('*')->from(TABLE_MODULE)->where('id')->eq($moduleID)->fetch();
        if($module->type != 'story' || !$module->root) return false;
        return $this->dao->select('id,name')->from(TABLE_PRODUCT)->where('id')->eq($module->root)->fetch();
    }

    /**
     * 获取一个需求模块。
     * Get the module that its type == 'story'.
     *
     * @param  int    $moduleID
     * @access public
     * @return int
     */
    public function getStoryModule(int $moduleID): int
    {
        $module = $this->dao->select('id,type,parent')->from(TABLE_MODULE)->where('id')->eq((int)$moduleID)->fetch();
        if(empty($module)) return 0;

        while(!empty($module) && $module->id && $module->type != 'story')
        {
            $module = $this->dao->select('id,type,parent')->from(TABLE_MODULE)->where('id')->eq($module->parent)->fetch();
        }

        return empty($module) ? 0 : $module->id;
    }

    /**
     * 获取一些模块的名称。
     * Get modules name.
     *
     * @param  array  $moduleIdList
     * @param  bool   $allPath
     * @param  bool   $branchPath
     * @access public
     * @return array
     */
    public function getModulesName(array $moduleIdList, bool $allPath = true, bool $branchPath = false): array
    {
        if(!$allPath) return $this->dao->select('id, name')->from(TABLE_MODULE)->where('id')->in($moduleIdList)->andWhere('deleted')->eq(0)->fetchPairs('id', 'name');

        /* Get modules && submodules through id list. */
        $modules     = $this->dao->select('id, name, path, branch')->from(TABLE_MODULE)->where('id')->in($moduleIdList)->andWhere('deleted')->eq(0)->fetchAll('path');
        $allModules  = $this->dao->select('id, name')->from(TABLE_MODULE)->where('id')->in(join(array_keys($modules)))->andWhere('deleted')->eq(0)->fetchPairs('id', 'name');

        /* Constructs a key-value pair for the module name:id. */
        $branchIDList = array();
        $modulePairs  = array();
        foreach($modules as $module)
        {
            $paths      = explode(',', trim($module->path, ','));
            $moduleName = '';
            foreach($paths as $path) $moduleName .= '/' . zget($allModules, $path);
            $modulePairs[$module->id] = $moduleName;

            if($module->branch) $branchIDList[$module->branch] = $module->branch;
        }

        if(!$branchPath) return $modulePairs;

        /* Prefixes the module name with the branch name. */
        $branchs = $this->dao->select('id, name')->from(TABLE_BRANCH)->where('id')->in($branchIDList)->andWhere('deleted')->eq(0)->fetchALL('id');
        foreach($modules as $module)
        {
            if(!isset($modulePairs[$module->id])) continue;

            $branchName = isset($branchs[$module->branch]) ? '/' . $branchs[$module->branch]->name : '';
            $modulePairs[$module->id] = $branchName . $modulePairs[$module->id];
        }

        return $modulePairs;
    }

    /**
     * 更新模块排序。
     * Update modules' order.
     *
     * @param  array   $orders
     * @access public
     * @return void
     */
    public function updateOrder(array $orders): void
    {
        asort($orders);
        $orderInfo = $this->dao->select('*')->from(TABLE_MODULE)->where('id')->in(array_keys($orders))->andWhere('deleted')->eq(0)->fetchAll('id');
        $newOrders = array();
        foreach(array_keys($orders) as $moduleID)
        {
            $parent = $orderInfo[$moduleID]->parent;
            $grade  = $orderInfo[$moduleID]->grade;
            $branch = $orderInfo[$moduleID]->branch;

            if(!isset($newOrders[$parent][$grade][$branch]))
            {
                $newOrders[$parent][$grade][$branch] = 1;
            }
            else
            {
                $newOrders[$parent][$grade][$branch] ++;
            }

            $newOrder = $newOrders[$parent][$grade][$branch] * 10;
            $this->dao->update(TABLE_MODULE)->set('`order`')->eq($newOrder)->where('id')->eq((int)$moduleID)->limit(1)->exec();
        }
    }

    /**
     * 修改模块的所有子模块。
     * Manage childs of a module.
     *
     * @param  int    $rootID
     * @param  string $type
     * @access public
     * @return array|false
     */
    public function manageChild(int $rootID, string $type): array|false
    {
        if($type == 'line') $rootID = 0;

        $data           = fixer::input('post')->get();
        $childs         = $data->modules;
        $parentModuleID = (int)$data->parentModuleID;

        foreach($childs as $moduleID => $moduleName)
        {
            if(preg_match('/(\s+)/', trim($moduleName)))
            {
                dao::$errors['root'] = $this->lang->tree->shouldNotBlank;
                return false;
            }
        }

        $module         = new stdClass();
        $module->root   = $rootID;
        $module->type   = $type;
        $module->parent = $parentModuleID;
        $repeatName     = $this->checkUnique($module, $childs);
        if($repeatName)
        {
            dao::$errors['root'] = sprintf($this->lang->tree->repeatName, $repeatName);
            return false;
        }

        $parentModule = $this->getByID($parentModuleID);

        $branches = isset($data->branch) ? $data->branch : array();
        $orders   = isset($data->order)  ? $data->order  : array();
        $shorts   = $data->shorts;
        if($parentModule)
        {
            $grade      = $parentModule->grade + 1;
            $parentPath = $parentModule->path;
        }
        else
        {
            $grade      = 1;
            $parentPath = ',';
        }
        $i = 1;

        $oldModules = $this->getOptionMenu($rootID, 'story', 0, 'all');

        $createIdList = array();
        $editIdList   = array();
        foreach($childs as $moduleID => $moduleName)
        {
            if(empty($moduleName)) continue;

            /* The new modules. */
            if(is_numeric($moduleID))
            {
                if(isset($orders[$moduleID]) && !empty($orders[$moduleID]))
                {
                    $order = $orders[$moduleID];
                }
                else
                {
                    $order = (int)$this->post->maxOrder + $i * 10;
                    $i ++;
                }

                $module         = new stdClass();
                $module->root   = $rootID;
                $module->name   = strip_tags(trim($moduleName));
                $module->parent = $parentModuleID;
                $module->branch = isset($branches[$moduleID]) ? $branches[$moduleID] : 0;
                $module->short  = $shorts[$moduleID];
                $module->grade  = $grade;
                $module->type   = $type;
                $module->order  = $order;
                $this->dao->insert(TABLE_MODULE)->data($module)->exec();
                $moduleID       = $this->dao->lastInsertID();
                $createIdList[] = $moduleID;
                $childPath      = $parentPath . "$moduleID,";
                $this->dao->update(TABLE_MODULE)->set('path')->eq($childPath)->where('id')->eq($moduleID)->limit(1)->exec();
            }
            else
            {
                $originID = $moduleID;
                $short    = $shorts[$moduleID];
                $order    = $orders[$moduleID];
                $moduleID = (int)str_replace('id', '', $moduleID);

                $oldModule = $this->getByID($moduleID);

                $data = new stdClass();
                $data->name   = strip_tags(trim($moduleName));
                $data->short  = $short;
                $data->order  = $order;
                $data->branch = isset($branches[$originID]) ? $branches[$originID] : 0;

                $this->setModuleLang();
                $this->dao->update(TABLE_MODULE)->data($data)->autoCheck()->where('id')->eq($moduleID)->limit(1)->exec();

                $newModule = $this->getByID($moduleID);
                if(common::createChanges($oldModule, $newModule))
                {
                    $editIdList[]             = $moduleID;
                    $moduleChanges[$moduleID] = common::createChanges($oldModule, $newModule);
                }
            }
        }

        if($type == 'story' || strpos($this->config->tree->groupTypes, ",$type,") !== false)
        {
            $objectType = $type == 'story' ? 'module' : 'chartgroup';
            $this->loadModel('action');
            if(!empty($createIdList)) $actionID = $this->action->create($objectType, $rootID, 'created', '', implode(',', $createIdList));

            if(!empty($editIdList))
            {
                $changes    = array();
                $newModules = $this->getOptionMenu($rootID, 'story', 0, 'all');
                foreach($moduleChanges as $moduleID => $moduleChange)
                {
                    foreach($moduleChange as $change)
                    {
                        if($change['field'] == 'name')
                        {
                            $change['old']  = zget($oldModules, $moduleID);
                            $change['new']  = zget($newModules, $moduleID);
                            $change['diff'] = '';
                        }
                        $changes[] = $change;
                    }
                }
                $actionID = $this->action->create($objectType, $rootID, 'edited', '', implode(',', $editIdList));
                if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            }
        }
        return $createIdList;
    }

    /**
     * 更新模块信息。
     * Update a module.
     *
     * @param  int    $moduleID
     * @param  string $type
     * @access public
     * @return bool
     */
    public function update(int $moduleID, string $type = ''): bool
    {
        $module = fixer::input('post')->get();
        $self   = $this->getById($moduleID);
        $changes = common::createChanges($self, $module);
        if(!isset($_POST['branch'])) $module->branch = $self->branch;

        if($self)
        {
            if($type == 'ticket' || $type == 'feedback') $module->root = $self->root;
            if($type == 'host' || !isset($module->root)) $module->root = 0;
            if(strpos($this->config->tree->groupTypes, ",$type,") !== false) $module->root = $self->root;
            if($self->root && !$module->root) $module->root = $self->root;
            if($self->parent != $module->parent || $self->root != $module->root)
            {
                $maxOrder = $this->dao->select('MAX(`order`) AS `order`')->from(TABLE_MODULE)->where('parent')->eq($module->parent)->andWhere('root')->eq($module->root)->fetch('order');
                $module->order = $maxOrder ? ++ $maxOrder : $self->order;
            }

            if($module->parent) $self->parent = $module->parent;
        }

        $repeatName = $this->checkUnique($self, array("id{$self->id}" => $module->name), array("id{$self->id}" => $module->branch));
        if($repeatName)
        {
            $tips = in_array($self->type, array('doc', 'api')) ? $this->lang->tree->repeatDirName : $this->lang->tree->repeatName;
            dao::$errors['name'] = sprintf($tips, $repeatName);
            return false;
        }

        if((empty($module->root) || empty($module->name)) && in_array($self->type, array('doc', 'api')))
        {
            $this->app->loadLang('doc');
            if(empty($module->root))
            {
                dao::$errors['root'] = sprintf($this->lang->error->notempty, $this->lang->doc->lib);
                return false;
            }
            if(empty($module->name))
            {
                dao::$errors['name'] = sprintf($this->lang->error->notempty, $this->lang->doc->catalogName);
                return false;
            }
        }

        if(empty($module->name))
        {
            dao::$errors['name'] = sprintf($this->lang->error->notempty, $this->lang->tree->name);
            return false;
        }

        $modules = $self->type == 'story' ? $this->getOptionMenu($self->root, 'story', 0, 'all') : array();

        $parent = $this->getById((int)$this->post->parent);
        $childs = $this->getAllChildId($moduleID);
        $module->name  = strip_tags(trim($module->name));
        $module->grade = $parent ? $parent->grade + 1 : 1;
        $module->path  = $parent ? $parent->path . $moduleID . ',' : ',' . $moduleID . ',';
        $this->dao->update(TABLE_MODULE)->data($module)->autoCheck()->check('name', 'notempty')->where('id')->eq($moduleID)->exec();
        $this->dao->update(TABLE_MODULE)->set('grade = grade + 1')->where('id')->in($childs)->andWhere('id')->ne($moduleID)->exec();
        $this->dao->update(TABLE_MODULE)->set('owner')->eq($this->post->owner)->where('id')->in($childs)->andWhere('owner')->eq('')->exec();
        $this->dao->update(TABLE_MODULE)->set('owner')->eq($this->post->owner)->where('id')->in($childs)->andWhere('owner')->eq($self->owner)->exec();

        if($self->type == 'story' || strpos($this->config->tree->groupTypes, ",$self->type,") !== false)
        {
            $objectType = $self->type == 'story' ? 'module' : 'chartgroup';
            $rootID     = isset($module->root) ? (int)$module->root : $self->root;
            $newModules = $this->getOptionMenu($rootID, 'story', 0, 'all');

            foreach($changes as $id => $change)
            {
                if($change['field'] == 'name')
                {
                    $changes[$id]['old']  = zget($modules, $moduleID);
                    $changes[$id]['new']  = zget($newModules, $moduleID);
                    $changes[$id]['diff'] = '';
                    break;
                }
            }
            $actionID = $this->loadModel('action')->create($objectType, $self->root, 'edited', '', $moduleID);
            if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            if(isset($module->root) && $module->root != $self->root)
            {
                $actionID = $this->action->create($objectType, $rootID, 'edited', '', $moduleID);
                if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            }
        }

        if(isset($module->root) && $module->root != $self->root)
        {
            $this->dao->update(TABLE_MODULE)->set('root')->eq($module->root)->where('id')->in($childs)->exec();
            $this->dao->update(TABLE_MODULE)->set('branch')->eq($module->branch)->where('id')->in($childs)->exec();
            if($self->type == 'doc')
            {
                $this->dao->update(TABLE_DOC)->set('`lib`')->eq($module->root)
                    ->where('module')->eq($moduleID)
                    ->orWhere('module')->in($childs)
                    ->exec();
            }
        }
        $this->fixModulePath(isset($module->root) ? (int)$module->root : $self->root, $self->type);
        if(isset($module->root) && $module->root != $self->root) $this->changeRoot($moduleID, $self->root, (int)$module->root, $self->type);

        return true;
    }

    /**
     * 修改模块下关联事项的root，注意： 修改module本身的root不在该逻辑中。
     * Change root of items in the module.
     *
     * @param  int    $moduleID
     * @param  int    $oldRoot
     * @param  int    $newRoot
     * @param  string $type
     * @access public
     * @return void
     */
    public function changeRoot(int $moduleID, int $oldRoot, int $newRoot, string $type): void
    {
        /* Get all children id list. */
        $childIdList = $this->dao->select('id')->from(TABLE_MODULE)->where('path')->like("%,$moduleID,%")->andWhere('deleted')->eq(0)->fetchPairs('id');

        /* Update product field for stories, bugs, cases under this module. */
        $this->dao->update(TABLE_STORY)->set('product')->eq($newRoot)->where('module')->in($childIdList)->exec();
        $this->dao->update(TABLE_BUG)->set('product')->eq($newRoot)->where('module')->in($childIdList)->exec();
        $this->dao->update(TABLE_CASE)->set('product')->eq($newRoot)->where('module')->in($childIdList)->exec();
        $this->dao->update(TABLE_DOC)->set('lib')->eq($newRoot)->where('module')->in($childIdList)->exec();

        if($type != 'story') return;

        /* If the type if story, check it's releated projects. */
        $projectStories = $this->dao->select('DISTINCT t1.id,t1.version,t2.project')
            ->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t2')->on('t1.id=t2.story')
            ->where('t1.module')->in($childIdList)
            ->andWhere('t2.product')->eq($oldRoot)
            ->fetchAll('id');
        $executions = array();
        foreach($projectStories as $story)
        {
            $this->dao->update(TABLE_PROJECTSTORY)
                ->set('product')->eq($newRoot)
                ->where('project')->eq($story->project)
                ->andWhere('story')->eq($story->id)
                ->andWhere('version')->eq($story->version)
                ->exec();
            $executions[$story->project] = $story->project;
        }

        if($executions)
        {
            $projectProduct = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->in($executions)->fetchGroup('project', 'product');
            $linkedProduct  = $this->dao->select('DISTINCT project,product')->from(TABLE_PROJECTSTORY)->where('project')->in($executions)->fetchGroup('project', 'product');
            foreach($executions as $executionID)
            {
                if(!isset($projectProduct[$executionID]) || !in_array($newRoot, array_keys($projectProduct[$executionID]))) $this->dao->insert(TABLE_PROJECTPRODUCT)->set('project')->eq($executionID)->set('product')->eq($newRoot)->exec();
                if(isset($linkedProduct[$executionID])  && !in_array($oldRoot, array_keys($linkedProduct[$executionID])))
                {
                    $this->dao->delete()->from(TABLE_PROJECTPRODUCT)->where('project')->eq($project)->andWhere('product')->eq($oldRoot)->exec();
                    $this->dao->update(TABLE_BUILD)->set('product')->eq($newRoot)->where('product')->eq($oldRoot)->andWhere('execution')->eq($executionID)->exec();
                }
            }
        }
    }

    /**
     * 删除一个模块。
     * Delete a module.
     *
     * @param  int    $moduleID
     * @access public
     * @return bool
     */
    public function remove(int $moduleID): bool
    {
        $module = $this->getById($moduleID);
        if(empty($module)) return false;

        $childs = $this->getAllChildId($moduleID);
        $childs[$moduleID] = $moduleID;

        $objectType = (!empty($module->type) && strpos($this->config->tree->groupTypes, ",$module->type,") !== false) ? 'chartgroup' : 'module';
        /* Mark deletion when delete a module. */
        $this->dao->update(TABLE_MODULE)->set('deleted')->eq(1)->where('id')->in($childs)->exec();
        foreach($childs as $childID)
        {
            $this->loadModel('action')->create($objectType, $childID, 'deleted', '', actionModel::CAN_UNDELETED);
        }

        $this->fixModulePath($module->root, $module->type);
        $cookieName = '';
        switch ($module->type)
        {
            case 'line':
                $this->dao->update(TABLE_PRODUCT)->set('line')->eq('0')->where('line')->eq($moduleID)->exec();
                break;
            case 'task':
                $this->dao->update(TABLE_TASK)->set('module')->eq($module->parent)->where('module')->in($childs)->exec();
                $cookieName = 'moduleBrowseParam';
                break;
            case 'bug':
                $this->dao->update(TABLE_BUG)->set('module')->eq($module->parent)->where('module')->in($childs)->exec();
                $cookieName = 'bugModule';
                break;
            case 'case':
                $this->dao->update(TABLE_CASE)->set('module')->eq($module->parent)->where('module')->in($childs)->exec();
                $cookieName = 'caseModule';
                break;
            case 'story':
                $this->dao->update(TABLE_STORY)->set('module')->eq($module->parent)->where('module')->in($childs)->exec();
                $this->dao->update(TABLE_TASK)->set('module')->eq($module->parent)->where('module')->in($childs)->exec();
                $this->dao->update(TABLE_BUG)->set('module')->eq($module->parent)->where('module')->in($childs)->exec();
                $this->dao->update(TABLE_CASE)->set('module')->eq($module->parent)->where('module')->in($childs)->exec();
                $this->dao->update(TABLE_FEEDBACK)->set('module')->eq($module->parent)->where('module')->in($childs)->exec();
                $this->dao->update(TABLE_TICKET)->set('module')->eq($module->parent)->where('module')->in($childs)->exec();
                $cookieName = 'storyModule';
                break;
            case 'chart':
                $this->dao->update(TABLE_CHART)->set('`group`')->eq($module->parent)->where('`group`')->in($childs)->exec();
                break;
            case 'report':
                $this->dao->update(TABLE_REPORT)->set('`module`')->eq($module->parent)->where('`module`')->in($childs)->exec();
                break;
            case 'dataview':
                $this->dao->update(TABLE_DATAVIEW)->set('`group`')->eq($module->parent)->where('`group`')->in($childs)->exec();
                break;
            case 'feedback':
                $this->dao->update(TABLE_FEEDBACK)->set('module')->eq($module->parent)->where('module')->in($childs)->exec();
                $cookieName = 'feedbackModule';
                break;
            case 'ticket':
                $this->dao->update(TABLE_TICKET)->set('module')->eq($module->parent)->where('module')->in($childs)->exec();
                $cookieName = 'ticketModule';
                break;
        }
        $sessionValue = $this->session->{$module->type . 'List'};
        if($sessionValue && strpos($sessionValue, 'param=' . $moduleID)) $this->session->set($module->type . 'List', str_replace('param=' . $moduleID, 'param=0', $sessionValue));
        if($cookieName) helper::setcookie($cookieName, 0, time() - 3600, $this->config->webRoot, '', $this->config->cookieSecure, true);

        return true;
    }

    /**
     * 修复模块的Path和Grade。
     * Fix the path, grade fields according to the id && parent fields.
     *
     * @param  int    $rootID
     * @param  string $type
     * @access public
     * @return void
     */
    public function fixModulePath(int $rootID, string $type): void
    {
        /* Get all modules grouped by parent. */
        if($type == 'bug' || $type == 'case') $type = 'story,' . $type;
        $groupModules = $this->dao->select('id, parent, branch')->from(TABLE_MODULE)->where('root')->eq($rootID)->andWhere('type')->in($type)->andWhere('deleted')->eq(0)->fetchGroup('parent', 'id');
        $modules = array();

        /* Cycle the groupModules until it has no item any more. */
        while(count($groupModules) > 0)
        {
            $oldCounts = count($groupModules);    // Record the counts before processing.
            foreach($groupModules as $parentModuleID => $childModules)
            {
                /* If the parentModule doesn't exsit in the modules, skip it. If exists, compute it's child modules. */
                if(!isset($modules[$parentModuleID]) && $parentModuleID != 0) continue;
                if($parentModuleID == 0)
                {
                    $parentModule = new stdclass();
                    $parentModule->grade = 0;
                    $parentModule->path  = ',';
                }
                else
                {
                    $parentModule = $modules[$parentModuleID];
                }

                /* Compute it's child modules. */
                foreach($childModules as $childModuleID => $childModule)
                {
                    $childModule->grade  = $parentModule->grade + 1;
                    $childModule->path   = $parentModule->path . $childModule->id . ',';
                    if(isset($parentModule->branch))$childModule->branch = $parentModule->branch;
                    $modules[$childModuleID] = $childModule;    // Save child module to modules, thus the child of child can compute it's grade && path.
                }
                unset($groupModules[$parentModuleID]);    // Remove it from the groupModules.
            }
            if(count($groupModules) == $oldCounts) break;   // If after processing, no module processed, break the cycle.
        }

        /* Save modules to database. */
        foreach($modules as $module) $this->dao->update(TABLE_MODULE)->data($module)->where('id')->eq($module->id)->limit(1)->exec();
    }

    /**
     * 检查模块是否重名。
     * Check unique module name.
     *
     * @param  object $module   检查范围，包括root,type,parent
     * @param  array  $modules
     * @param  array  $branches 检查范围
     * @access public
     * @return string|false
     */
    public function checkUnique(object $module, $modules = array(), $branches = array()): string|false
    {
        if(empty($branches)) $branches = $this->post->branch;
        if(empty($branches) && isset($module->branch)) $branches = array($module->branch);
        if(empty($branches)) $branches = array(0);

        /* If no modules to check, check the name of module. */
        if(empty($modules) && isset($module->name)) $modules = array($module->name);

        $branches       = array_unique($branches);
        $rootID         = $module->root;
        $viewType       = $module->type;
        $parentModuleID = $module->parent;

        if($this->isMergeModule($rootID, $viewType) && $viewType != 'task') $viewType .= ',story';

        $existsModules  = $this->dao->select('id,branch,name')->from(TABLE_MODULE)->where('root')->eq($rootID)->andWhere('type')->in($viewType)->andWhere('parent')->eq($parentModuleID)->andWhere('branch')->in($branches)->andWhere('deleted')->eq(0)->fetchAll();
        $checkedModules = ',';
        $repeatName     = '';
        foreach($modules as $id => $name)
        {
            $existed = false;
            if(!is_numeric($id) && strpos($id, 'id') === 0)
            {
                $existed  = true;
                $moduleID = substr($id, 2);
            }

            if(strpos($checkedModules, ",$name,") !== false) return $name;

            foreach($existsModules as $existsModule)
            {
                if($name == $existsModule->name && (!$existed || $moduleID != $existsModule->id) && (!isset($branches[$id]) || $branches[$id] == $existsModule->branch)) return $name;
            }
            $checkedModules .= "$name,";
        }

        return false;
    }

    /**
     * 检查是否是已合并模块。4.1后task,case,bug也会使用story的模块。
     * Check merge module version.
     *
     * @param  int    $rootID
     * @param  string $viewType
     * @access public
     * @return bool
     */
    public function isMergeModule(int $rootID, string $viewType): bool
    {
        if(!in_array($viewType, array('bug', 'case', 'task'))) return false;

        /* Get createdVersion. */
        $table          = $viewType == 'task' ? TABLE_PROJECT : TABLE_PRODUCT;
        $versionField   = $viewType == 'task' ? 'openedVersion' : 'createdVersion';
        $createdVersion = $this->dao->select($versionField)->from($table)->where('id')->eq($rootID)->fetch($versionField);
        if(!$createdVersion) return true;

        if(is_numeric($createdVersion[0]) && version_compare($createdVersion, '4.1', '<=')) return false;
        return true;

    }

    /**
     * 获取产品的模块树。
     * Get full trees.
     *
     * @param  int        $rootID
     * @param  string     $viewType
     * @param  string|int $branchID
     * @param  int        $currentModuleID
     * @access public
     * @return array
     */
    public function getProductStructure(int $rootID, string $viewType, string $branchID = 'all', int $currentModuleID = 0): array
    {
        if(common::isTutorialMode()) return array();

        if($viewType == 'line') $rootID = 0;
        $stmt  = $this->app->dbQuery($this->buildMenuQuery($rootID, $viewType, $currentModuleID, $branchID));
        $trees = $this->getDataStructure($stmt, $viewType, $rootID, array(), $branchID);

        return $trees;
    }

    /**
     * 获取模块树。
     * Get full modules tree.
     *
     * @param  object $stmt
     * @param  string $viewType
     * @param  int    $rootID
     * @param  array  $keepModules
     * @param  string $branch
     * @access public
     * @return array
     */
    public function getDataStructure(object $stmt, string $viewType, int $rootID, array $keepModules = array(), string $branch = 'all'): array
    {
        $parent = array();

        /* If feedback || ticket module is merge add story module.*/
        $syncConfig = $this->getSyncConfig($viewType);

        while($module = $stmt->fetch())
        {
            /* If is feedback || ticket filter story module by grade.*/
            if(($viewType == 'feedback' || $viewType == 'ticket') && $module->type == 'story')
            {
                if(isset($syncConfig[$module->root]) && $module->grade > $syncConfig[$module->root]) continue;
            }

            /* Ignore useless module for task. */
            $allModule = (isset($this->config->execution->task->allModule) && ($this->config->execution->task->allModule == 1));
            if($keepModules && !isset($keepModules[$module->id]) && !$allModule) continue;
            if($viewType == 'task' && empty($keepModules) && !$allModule) continue;
            if(isset($parent[$module->id]))
            {
                $module->children = $parent[$module->id]->children;
                unset($parent[$module->id]);
            }
            if(!isset($parent[$module->parent])) $parent[$module->parent] = new stdclass();

            if($viewType == 'task')
            {
                $module->url = helper::createLink('tree', 'browsetask', "rootID=$rootID&productID=0&moduleID=$module->id");
            }
            else if(in_array($viewType, array('pivot', 'chart')))
            {
                $module->url = helper::createLink('tree', 'browsegroup', "dimensionID=$rootID&groupID=$module->id&type=$viewType");
            }
            else
            {
                $module->url = helper::createLink('tree', 'browse', "rootID=$rootID&view=$viewType&currentModuleID=$module->id&branchID=$branch");
            }
            $parent[$module->parent]->children[] = $module;
        }

        if($viewType == 'task') $parentTypePairs = $this->dao->select('id,type')->from(TABLE_MODULE)->where('id')->in(array_keys($parent))->andWhere('deleted')->eq(0)->fetchPairs('id', 'type');

        $tree = array();
        foreach($parent as $module)
        {
            foreach($module->children as $item)
            {
                if($viewType == 'task' && isset($parentTypePairs[$item->parent]) && $parentTypePairs[$item->parent] != 'task') continue;
                if($item->parent != 0) continue; // Filter project children modules.
                $tree[] = $item;
            }
        }
        return $tree;
    }

    /**
     * 获取文档模块树。
     * Get all doc structure.
     *
     * @access public
     * @return array
     */
    public function getDocStructure(): array
    {
        $stmt = $this->app->dbQuery($this->dao->select('*')->from(TABLE_MODULE)->where('type')->eq('doc')->andWhere('deleted')->eq(0)->orderBy('`grade`_desc, `order`')->get());
        $parent = array();
        while($module = $stmt->fetch())
        {
            if(!isset($parent[$module->root])) $parent[$module->root] = array();

            if(isset($parent[$module->root][$module->id]))
            {
                $module->children = $parent[$module->root][$module->id]->children;
                unset($parent[$module->root][$module->id]);
            }
            if(!isset($parent[$module->root][$module->parent])) $parent[$module->root][$module->parent] = new stdclass();
            $parent[$module->root][$module->parent]->children[] = $module;
        }

        $tree = array();
        foreach($parent as $root => $modules)
        {
            foreach($modules as $module)
            {
                foreach($module->children as $children)
                {
                    if($children->parent != 0 && !empty($tree[$root]))
                    {
                        foreach($tree[$root] as $firstChildren)
                        {
                            if($firstChildren->id == $children->parent) $firstChildren->children[] = $children;
                        }
                    }
                    $tree[$root][] = $children;
                }
            }
        }

        return $tree;
    }

    /**
     * 获取反馈和工单是否已跟产品同步的信息。
     * Get syncProduct module config.
     *
     * @param  string $type feedback|ticket
     * @access public
     * @return array
     */
    public function getSyncConfig(string $type = ''): array
    {
        if(!isset($this->config->global->syncProduct)) return array();

        /* If feedback or ticket module is merge add story module.*/
        $syncConfig = json_decode($this->config->global->syncProduct, true);
        $syncConfig = isset($syncConfig[$type]) ? $syncConfig[$type] : array();
        return $syncConfig;
    }

    /**
     * 加载模块的语言项。
     * Load module language.
     *
     * @access public
     * @return void
     */
    public function setModuleLang(): void
    {
        $this->lang->module        = new stdclass();
        $this->lang->module->name  = $this->lang->tree->wordName;
        $this->lang->module->short = $this->lang->tree->short;
    }

    /**
     * 创建模块。
     * Create module.
     *
     * @access public
     * @return object|false
     */
    public function createModule(): object|false
    {
        $data = fixer::input('post')
            ->setDefault('name', '')
            ->setDefault('createType', 'child')
            ->setDefault('objectID', 0)
            ->setDefault('order', 10)
            ->cleanInt('libID,parentID,objectID')
            ->get();

        $module         = new stdClass();
        $module->root   = zget($data, 'libID', 0);
        $module->type   = zget($data, 'moduleType', 'doc');
        $module->parent = zget($data, 'parentID', 0);
        $module->name   = strip_tags(trim($data->name));
        $module->branch = 0;
        $module->short  = '';
        $module->order  = $data->order;

        if(empty($module->name))
        {
            dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->tree->dir);
            return false;
        }

        if($data->createType == 'same')
        {
            $baseModule = $this->getByID($data->objectID);
            if(!empty($baseModule))
            {
                $module->parent = $baseModule->parent;
                $module->order  = $baseModule->order;
            }
        }
        elseif($data->createType == 'child')
        {
            $maxOrder = $this->dao->select('`order`')->from(TABLE_MODULE)
                ->where('root')->eq($module->root)
                ->andWhere('parent')->eq($module->parent)
                ->andWhere('type')->eq($module->type)
                ->orderBy('order_desc')
                ->limit(1)
                ->fetch('order');

            $module->order = (int)$maxOrder + 10;
        }

        $repeatName = $this->checkUnique($module);
        if($repeatName)
        {
            dao::$errors[] = sprintf($this->lang->tree->repeatDirName, $repeatName);
            return false;
        }

        $parent = $this->getByID($module->parent);
        $module->grade = $module->parent ? $parent->grade + 1 : 1;
        $this->dao->insert(TABLE_MODULE)->data($module)->exec();
        $moduleID = $this->dao->lastInsertID();

        if($data->createType == 'same')
        {
            $this->dao->update(TABLE_MODULE)
                ->set('`order` = `order` + 10')
                ->where('`root`')->eq($module->root)
                ->andWhere('`parent`')->eq($module->parent)
                ->andWhere('`type`')->eq($module->type)
                ->andWhere('`order`')->gt($module->order)
                ->exec();

            $module->order += 10;
        }

        $modulePath = "$moduleID,";
        if($module->parent) $modulePath = $parent->path . $modulePath;
        $this->dao->update(TABLE_MODULE)->set('`path`')->eq($modulePath)->set('`order`')->eq($module->order)->where('id')->eq($moduleID)->limit(1)->exec();

        return $this->getByID($moduleID);
    }

    /**
     * 获取分组模块对列表。
     * Get group pairs.
     *
     * @param  int    $dimensionID
     * @param  int    $parentGroup
     * @param  int    $grade
     * @param  string $type
     * @access public
     * @return array
     */
    public function getGroupPairs(int $dimensionID = 0, int $parentGroup = 0, int $grade = 2, string $type = 'chart'): array
    {
        $groups = $this->dao->select('id,name,grade,parent')->from(TABLE_MODULE)
            ->where('root')->eq($dimensionID)
            ->beginIF(!empty($parentGroup))->andWhere('root')->eq($dimensionID)->fi()
            ->andWhere('type')->eq($type)
            ->andWhere('deleted')->eq(0)
            ->orderBy('order')
            ->fetchGroup('grade', 'id');

        $groupPairs = array();
        if(!empty($groups[1]))
        {
            foreach($groups[1] as $parentGroup)
            {
                if($grade == 1) $groupPairs[$parentGroup->id] = $parentGroup->name;
                if($grade == 2 && !empty($groups[2]))
                {
                    foreach($groups[2] as $childGroup)
                    {
                        if($parentGroup->id == $childGroup->parent) $groupPairs[$childGroup->id] = '/' . $parentGroup->name . '/' . $childGroup->name;
                    }
                }
            }
        }

        return $groupPairs;
    }
}
