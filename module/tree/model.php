<?php
/**
 * The model file of tree module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     tree
 * @version     $Id: model.php 5149 2013-07-16 01:47:01Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
class treeModel extends model
{
    /**
     * Get module by ID.
     *
     * @param  int    $moduleID
     * @access public
     * @return object
     */
    public function getByID($moduleID)
    {
        return $this->dao->findById((int)$moduleID)->from(TABLE_MODULE)->fetch();
    }

    /**
     * Get all module pairs with path.
     *
     * @access public
     * @return object
     */
    public function getAllModulePairs($type = 'task')
    {
        $modules = $this->dao->select('*')->from(TABLE_MODULE)
            ->where('type')->eq('story')
            ->beginIF($type == 'task')->orWhere('type')->eq('task')->fi()
            ->beginIF($type == 'bug')->orWhere('type')->eq('bug')->fi()
            ->beginIF($type == 'case')->orWhere('type')->eq('case')->fi()
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
     * Build the sql query.
     *
     * @param  int        $rootID
     * @param  string     $type
     * @param  int        $startModule
     * @param  string|int $branch
     * @param  string     $param
     * @param  int        $grade
     * @access public
     * @return void
     */
    public function buildMenuQuery($rootID, $type, $startModule = 0, $branch = 'all', $param = 'nodeleted', $grade = 0)
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

        if(($type == 'feedback' or $type == 'ticket') and strpos($param, 'noproduct') === false and isset($syncConfig[$rootID])) $type  = 'story,' . $type;
        if($this->isMergeModule($rootID, $type))
        {
            return $this->dao->select('*')->from(TABLE_MODULE)
                ->where('root')->eq((int)$rootID)
                ->beginIF($type == 'task')->andWhere('type')->eq('task')->fi()
                ->beginIF($type != 'task')->andWhere('type')->in("story,$type")->fi()
                ->beginIF($startModulePath)->andWhere('path')->like($startModulePath)->fi()
                ->beginIF($branch !== 'all' and $branch !== '' and $branch !== false and strpos($param, 'noMainBranch') === false)
                ->andWhere("(branch")->eq(0)
                ->orWhere('branch')->eq($branch)
                ->markRight(1)
                ->fi()
                ->beginIF($branch !== 'all' and $branch !== '' and $branch !== false and strpos($param, 'noMainBranch') !== false)
                ->andWhere('branch')->eq($branch)
                ->fi()
                ->beginIF(strpos($param, 'nodeleted') !== false)->andWhere('deleted')->eq(0)->fi()
                ->orderBy('grade desc, `order`, type desc')
                ->get();
        }

        /* $createdVersion < 4.1 or $type == 'story'. */
        return $this->dao->select('*')->from(TABLE_MODULE)
            ->where('1=1')
            ->beginIF($type != 'feedback' or !empty($rootID))->andwhere('root')->eq((int)$rootID)->fi()
            ->andWhere('type')->in($type)
            ->beginIF($grade)->andWhere('grade')->le($grade)->fi()
            ->beginIF($startModulePath)->andWhere('path')->like($startModulePath)->fi()
            ->beginIF($branch !== 'all' and $branch !== '' and $branch !== false and strpos($param, 'noMainBranch') === false)
            ->andWhere('(branch')->eq(0)
            ->orWhere('branch')->eq($branch)
            ->markRight(1)
            ->fi()
            ->beginIF($branch !== 'all' and $branch !== '' and $branch !== false and strpos($param, 'noMainBranch') !== false)
            ->andWhere('branch')->eq($branch)
            ->fi()
            ->beginIF(strpos($param, 'nodeleted') !== false)->andWhere('deleted')->eq(0)->fi()
            ->orderBy('grade desc, `order`')
            ->get();
    }

    /**
     * Create an option menu in html.
     *
     * @param  int       $rootID
     * @param  string    $type
     * @param  int       $startModule
     * @param  int|array $branch
     * @param  string    $param
     * @param  string    $grade
     * @param  string    $divide /|>
     * @access public
     * @return void
     */
    public function getOptionMenu($rootID, $type = 'story', $startModule = 0, $branch = 0, $param = 'nodeleted', $grade = 'all', $divide = '/')
    {
        if(empty($branch) and !is_array($branch)) $branch = 0;
        if(defined('TUTORIAL'))
        {
            $modulePairs = $this->loadModel('tutorial')->getModulePairs();
            if(!is_array($branch)) return $modulePairs;

            $modules = array();
            foreach($branch as $branchID) $modules[$branchID] = $modulePairs;
            return $modules;
        }

        /* If type of $branch is array, get modules of these branches. */
        if(is_array($branch))
        {
            $modules = array();
            foreach($branch as $branchID) $modules[$branchID] = $this->getOptionMenu($rootID, $type, $startModule, $branchID, $param);

            return $modules;
        }

        if($type == 'line') $rootID = 0;

        $branches = array($branch => '');
        if($branch != 'all' and strpos('story|bug|case', $type) !== false)
        {
            $product = $this->loadModel('product')->getById($rootID);
            if($product and $product->type != 'normal')
            {
                $branchPairs = $this->loadModel('branch')->getPairs($rootID, 'all');
                foreach(explode(',', $branch) as $branchID) $branches += array($branchID => $branchPairs[$branchID]);
            }
            elseif($product and $product->type == 'normal')
            {
                $branches = array(0 => '');
            }
        }

        /* If feedback or ticket module is merge add story module.*/
        $syncConfig = $this->getSyncConfig($type);

        $treeMenu = array();
        foreach($branches as $branchID => $branch)
        {
            $stmt    = $this->app->dbQuery($this->buildMenuQuery($rootID, $type, $startModule, $branchID, $param));
            $modules = array();
            while($module = $stmt->fetch())
            {
                /* If is feedback or ticket filter story module by grade.*/
                if(($type == 'feedback' or $type == 'ticket') and $module->type == 'story')
                {
                    if(isset($syncConfig[$module->root]) and $module->grade > $syncConfig[$module->root]) continue;
                }

                if($grade != 'all' and $module->grade > $grade) continue;
                $modules[$module->id] = $module;
            }

            foreach($modules as $module)
            {
                $branchName = (isset($product) and $product->type != 'normal' and $module->branch === BRANCH_MAIN) ? $this->lang->branch->main : $branch;
                $this->buildTreeArray($treeMenu, $modules, $module, (empty($branchName)) ? '/' : "/$branchName/", $divide);
            }
        }

        ksort($treeMenu);
        $topMenu = array_shift($treeMenu);
        $topMenu = explode("\n", trim((string)$topMenu));
        $lastMenu[] = '/';
        foreach($topMenu as $menu)
        {
            if(!strpos($menu, '|')) continue;
            list($label, $moduleID) = explode('|', $menu);
            $lastMenu[$moduleID] = $label;
        }

        return $lastMenu;
    }

    /**
     * Get module pairs.
     *
     * @param  int    $rootID
     * @param  string $viewType
     * @param  string $showModule
     * @access public
     * @return array
     */
    public function getModulePairs($rootID, $viewType = 'story', $showModule = 'end', $extra = '')
    {
        if($viewType == 'task')
        {
            $products = array_keys($this->loadModel('product')->getProductPairsByProject($rootID));
            if(!$this->isMergeModule($rootID, $viewType) or !$products)
            {
                $modules = $this->dao->select('id,name,path,short')->from(TABLE_MODULE)->where('root')->eq($rootID)->andWhere('type')->in($viewType)->andWhere('deleted')->eq(0)->fetchAll('id');
            }
            else
            {
                $modules = $this->dao->select('id,name,path,short')->from(TABLE_MODULE)
                    ->where("((root = '" . (int)$rootID . "' and type = 'task')")
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
            if($this->isMergeModule($rootID, $viewType) or !$rootID) $viewType .= ',story';
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
            if($showModule == 'base' and isset($modules[$baseModule])) $module = $modules[$baseModule];
            $modulePairs[$moduleID] = $module->short ? $module->short : $module->name;
        }

        return $modulePairs;
    }

    /**
     * Create an option menu of task in html.
     *
     * @param  int    $rootID
     * @param  int    $startModule
     * @access public
     * @return void
     */
    public function getTaskOptionMenu($rootID, $productID = 0, $startModule = 0, $extra = '')
    {
        /* If createdVersion <= 4.1, go to getOptionMenu(). */
        $products     = $this->loadModel('product')->getProductPairsByProject($rootID);
        $branchGroups = $this->loadModel('branch')->getByProducts(array_keys($products), 'noclosed');

        if(!$this->isMergeModule($rootID, 'task') or !$products) return $this->getOptionMenu($rootID, 'task', $startModule);

        /* createdVersion > 4.1. */
        $startModulePath = '';
        if($startModule > 0)
        {
            $startModule = $this->getById($startModule);
            if($startModule)
            {
                $startModulePath = $startModule->path . '%';
                $modulePaths = explode(",", $startModulePath);
                $rootModule  = $this->getById($modulePaths[0]);
                $productID   = $rootModule->root;
            }
        }
        $treeMenu   = array();
        $lastMenu[] = '/';
        $executionModules = $this->getTaskTreeModules($rootID, true);
        $noProductModules = $this->dao->select('*')->from(TABLE_MODULE)
                    ->where('root')->eq((int)$rootID)
                    ->andWhere('type')->eq('task')
                    ->andWhere('parent')->eq(0)
                    ->andWhere('deleted')->eq(0)
                    ->orderBy('grade desc, branch, `order`, type')
                    ->fetchPairs('id', 'name');

        /* Fix for not in product modules. */
        $productNum = count($products);
        foreach(array('product' => $products, 'noProduct' => $noProductModules) as $type => $rootModules)
        {
            foreach($rootModules as $id => $rootModule)
            {
                $activeBranch = isset($branchGroups[$id]) ? array_keys($branchGroups[$id]) : array();
                if($type == 'product')
                {
                    $modules = $this->dao->select('*')->from(TABLE_MODULE)->where("((root = '" . (int)$rootID . "' and type = 'task' and parent != 0) OR (root = $id and type = 'story'))")
                        ->beginIF($startModulePath)->andWhere('path')->like($startModulePath)->fi()
                        ->beginIF(!empty($activeBranch))->andWhere('branch')->in($activeBranch)->fi()
                        ->andWhere('deleted')->eq(0)
                        ->orderBy('grade desc, branch, `order`, type')
                        ->fetchAll('id');
                }
                else
                {
                    $modules = $this->dao->select('*')->from(TABLE_MODULE)
                        ->where('root')->eq((int)$rootID)
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
                    if($type == 'product' and isset($noProductModules[$parentModules[0]])) continue;
                    /* Fix bug #2007. */
                    if($type == 'product' and $module->type == 'task' and !isset($modules[$parentModules[0]])) continue;
                    $rootName = ($productNum > 1 and $type == 'product') ? "/$rootModule/" : '/';
                    if($type == 'product' and $module->branch and isset($branchGroups[$id][$module->branch])) $rootName .= $branchGroups[$id][$module->branch] . '/';
                    $this->buildTreeArray($treeMenu, $modules, $module, $rootName);
                }

                ksort($treeMenu);
                $topMenu = array_shift($treeMenu);
                $topMenu = explode("\n", trim((string)$topMenu));
                foreach($topMenu as $menu)
                {
                    if(!strpos($menu, '|')) continue;
                    list($label, $moduleID) = explode('|', $menu);
                    if(isset($executionModules[$moduleID]) or strpos($extra, 'allModule') !== false) $lastMenu[$moduleID] = $label;
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
     * Build tree array.
     *
     * @param  $&treeMenu
     * @param  array  $modules
     * @param  object $module
     * @param  string $moduleName
     * @access public
     * @return void
     */
    public function buildTreeArray(& $treeMenu, $modules, $module, $moduleName = '/', $divide = '/')
    {
        $parentModules = explode(',', $module->path);
        foreach($parentModules as $parentModuleID)
        {
            if(empty($parentModuleID)) continue;
            if(empty($modules[$parentModuleID])) continue;
            $moduleName .= $modules[$parentModuleID]->name . $divide;
        }
        $moduleName = rtrim($moduleName, $divide);
        $moduleName .= "|$module->id\n";

        if(isset($treeMenu[$module->id]) and !empty($treeMenu[$module->id]))
        {
            if(isset($treeMenu[$module->parent]))
            {
                $treeMenu[$module->parent] .= $moduleName;
            }
            else
            {
                $treeMenu[$module->parent] = $moduleName;;
            }
            $treeMenu[$module->parent] .= $treeMenu[$module->id];
        }
        else
        {
            if(isset($treeMenu[$module->parent]) and !empty($treeMenu[$module->parent]))
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
     * Get the tree menu in html.
     *
     * @param  int    $rootID
     * @param  string $type
     * @param  int    $startModule
     * @param  string $userFunc     the function used to create link
     * @param  string $extra        extra params
     * @param  string $branch       product branch
     * @param  string $extraParams  extra params
     * @access public
     * @return string
     */
    public function getTreeMenu($rootID, $type = 'root', $startModule = 0, $userFunc = '', $extra = '', $branch = 0, $extraParams = '')
    {
        if($type == 'line') $rootID = 0;

        $this->loadModel('branch');
        $projectID        = zget($extra, 'projectID', 0);
        $projectModel     = $this->dao->findByID($projectID)->from(TABLE_PROJECT)->fetch('model');
        $branches         = array($branch => '');
        $executionModules = array();
        if($branch and empty($projectID))
        {
            $branchName = $this->branch->getById($branch);
            $branches   = array($branch => $branchName);
            $extra      = $userFunc[1] == 'createTestTaskLink' ? $extra : array('rootID' => $rootID, 'branch' => $branch);
        }

        $manage  = $userFunc[1] == 'createManageLink' ? true : false;
        $product = $this->loadModel('product')->getById($rootID);

        $onlyGetLinked = ($projectID and $this->config->vision != 'lite');
        if(strpos('story|bug|case', $type) !== false and $branch === 'all' and empty($projectID))
        {
            if($product->type != 'normal') $branches = array(BRANCH_MAIN => $this->lang->branch->main) + $this->loadModel('branch')->getPairs($rootID, 'noempty');
        }
        elseif(strpos(',case,bug,', ",$type,") !== false and $this->app->tab == 'execution')
        {
            if($product->type != 'normal' and $projectID) $branches += $this->branch->getPairs($product->id, 'noempty', $projectID);
            if($onlyGetLinked) $executionModules = $this->getTaskTreeModules($projectID, true, $type, array('branchID' => $branch));
        }
        elseif(($type == 'story' and $this->app->rawModule == 'projectstory') or (strpos(',case,bug,', ",$type,") !== false and $this->app->tab == 'project'))
        {
            if($product->type != 'normal' and $projectID) $branches += $this->branch->getPairs($product->id, 'noempty', $projectID);
            if($onlyGetLinked) $executionModules = $this->getTaskTreeModules($projectID, true, $type, $type == 'story' ? array() : array('branchID' => $branch));
        }

        /* Add for task #1945. check the module has case or no. */
        if($type == 'case' and !empty($extra)) $this->loadModel('testtask');

        $lastMenu = '';
        $treeMenu = array();
        $stmt     = $this->app->dbQuery($this->buildMenuQuery($rootID, $type, $startModule, $branch));
        while($module = $stmt->fetch())
        {
            if(!$onlyGetLinked)
            {
                $this->buildTree($treeMenu, $module, $type, $userFunc, $extra, $branch);
            }
            elseif(isset($executionModules[$module->id]) || !empty($product->shadow) || $projectModel == 'ipd')
            {
                $this->buildTree($treeMenu, $module, $type, $userFunc, $extra, $branch);
            }
        }
        ksort($treeMenu);
        $lastMenu .= array_shift($treeMenu);

        if($lastMenu) $lastMenu = "<ul id='modules' class='tree' data-ride='tree' data-name='tree-{$type}'>$lastMenu</ul>\n";

        return $lastMenu;
    }

    /**
     * Get the tree menu of task in html.
     *
     * @param  int    $rootID
     * @param  int    $productID
     * @param  int    $startModule
     * @param  string $userFunc
     * @param  string $extra
     * @access public
     * @return void
     */
    public function getTaskTreeMenu($rootID, $productID = 0, $startModule = 0, $userFunc = '', $extra = '')
    {
        $extra = array('executionID' => $rootID, 'productID' => $productID, 'tip' => true, 'extra' => $extra);

        /* If createdVersion <= 4.1, go to getTreeMenu(). */
        $products      = $this->loadModel('product')->getProductPairsByProject($rootID);
        $branchGroups  = $this->loadModel('branch')->getByProducts(array_keys($products));

        if(!$this->isMergeModule($rootID, 'task') or !$products)
        {
            $extra['tip'] = false;
            return $this->getTreeMenu($rootID, 'task', $startModule, $userFunc, $extra);
        }

        /* createdVersion > 4.1. */
        $menu = "<ul id='modules' class='tree' data-ride='tree' data-name='tree-task'>";

        /* Set the start module. */
        $startModulePath = '';
        if($startModule > 0)
        {
            $startModule = $this->getById($startModule);
            if($startModule) $startModulePath = $startModule->path . '%';
        }

        $manage = $userFunc[1] == 'createTaskManageLink' ? true : false;

        /* if not manage, only get linked modules and ignore others. */
        if(!$manage) $executionModules = $this->getTaskTreeModules($rootID, true);

        /* Get module according to product. */
        $productNum = count($products);
        foreach($products as $id => $product)
        {
            $extra['productID'] = $id;
            if($manage)
            {
                $menu .= "<li>" . $product;
            }
            else
            {
                $link  = helper::createLink('execution', 'task', "executionID=$rootID&status=byProduct&praram=$id");
                if($productNum > 1) $menu .= "<li>" . html::a($link, $product, '_self', "id='product$id'");
            }

            /* tree menu. */
            $tree = '';
            if(empty($branchGroups[$id])) $branchGroups[$id]['0'] = '';
            foreach($branchGroups[$id] as $branch => $branchName)
            {
                $treeMenu = array();
                $query = $this->dao->select('*')->from(TABLE_MODULE)->where("((root = '" . (int)$rootID . "' and type = 'task' and parent != 0) OR (root = $id and type = 'story' and branch ='$branch'))")
                    ->beginIF($startModulePath)->andWhere('path')->like($startModulePath)->fi()
                    ->andWhere('deleted')->eq(0)
                    ->orderBy('grade desc, `order`, type')
                    ->get();
                $stmt = $this->app->dbQuery($query);
                while($module = $stmt->fetch())
                {
                    if(!$manage and !isset($executionModules[$module->id]) and strpos($extra['extra'], 'allModule') === false) continue;
                    $this->buildTree($treeMenu, $module, 'task', $userFunc, $extra);
                }
                if(isset($treeMenu[0]) and $branch) $treeMenu[0] = "<li><a>$branchName</a><ul>{$treeMenu[0]}</ul></li>";
                $tree .= isset($treeMenu[0]) ? $treeMenu[0] : '';
            }

            if($tree && ($productNum > 1 or $manage)) $tree = "<ul>" . $tree . "</ul>\n</li>";
            $menu .= $tree;
        }

        /* Get execution module. */
        if($startModule == 0)
        {
            /* tree menu. */
            $treeMenu = array();
            $query = $this->dao->select('*')->from(TABLE_MODULE)
                ->where('root')->eq((int)$rootID)
                ->andWhere('type')->eq('task')
                ->andWhere('deleted')->eq(0)
                ->orderBy('grade desc, `order`, type')
                ->get();
            $stmt  = $this->app->dbQuery($query);
            while($module = $stmt->fetch()) $this->buildTree($treeMenu, $module, 'task', $userFunc, $extra);

            $tree  = isset($treeMenu[0]) ? $treeMenu[0] : '';
            $menu .= $tree . '</li>';
        }
        $menu .= '</ul>';
        return $menu;
    }

    /**
     * Get full task tree
     * @param  integer $executionID, common value is execution id
     * @param  integer $productID
     * @access public
     * @return array
     */
    public function getTaskStructure($rootID, $productID = 0)
    {
        $extra = array('executionID' => $rootID, 'productID' => $productID, 'tip' => true);

        /* If createdVersion <= 4.1, go to getTreeMenu(). */
        $products      = $this->loadModel('product')->getProductPairsByProject($rootID);
        $branchGroups  = $this->loadModel('branch')->getByProducts(array_keys($products));

        if(!$this->isMergeModule($rootID, 'task') or !$products)
        {
            $extra['tip'] = false;
            $stmt = $this->app->dbQuery($this->buildMenuQuery($rootID, 'task', $startModule = 0));
            if(empty($products)) $this->config->execution->task->allModule = 1;
            return $this->getDataStructure($stmt, 'task');
        }

        /* only get linked modules and ignore others. */
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
                $query = $this->dao->select('*')->from(TABLE_MODULE)->where("((root = '" . (int)$rootID . "' and type = 'task' and parent != 0) OR (root = $id and type = 'story' and branch ='$branch'))")
                    ->andWhere('deleted')->eq(0)
                    ->orderBy('grade desc, `order`, type')
                    ->get();
                $stmt = $this->app->dbQuery($query);
                if($branch == 0) $productTree = $this->getDataStructure($stmt, 'task', $executionModules);
                if($branch != 0)
                {
                    $children = $this->getDataStructure($stmt, 'task', $executionModules);
                    if($children) $branchTrees[] = array('name' => $branchName, 'root' => $id, 'type' => 'branch', 'actions' => false, 'children' => $children);
                }
            }
            if($branchTrees) $productTree[] = array('name' => $this->lang->product->branchName[$productInfo->type], 'root' => $id, 'type' => 'branch', 'actions' => false, 'children' => $branchTrees);
            $fullTrees[] = array('name' => $productInfo->name, 'root' => $id, 'type' => 'product', 'actions' => false, 'children' => $productTree);
        }

        /* Get execution module. */
        $query = $this->dao->select('*')->from(TABLE_MODULE)
            ->where('root')->eq((int)$rootID)
            ->andWhere('type')->eq('task')
            ->andWhere('deleted')->eq(0)
            ->orderBy('grade desc, `order`, type')
            ->get();
        $stmt       = $this->app->dbQuery($query);
        $taskTrees  = $this->getDataStructure($stmt, 'task', $executionModules);
        foreach($taskTrees as $taskModule) $fullTrees[] = $taskModule;
        return $fullTrees;
    }

    /**
     * Get tree structure.
     *
     * @param  int    $rootID
     * @param  string $type
     * @access public
     * @return array
     */
    public function getTreeStructure($rootID, $type)
    {
        $stmt = $this->app->dbQuery($this->buildMenuQuery($rootID, $type, $startModule = 0));
        return $this->getDataStructure($stmt, $type);
    }

    /**
     * Get the tree menu of bug in html.
     *
     * @param  int    $rootID
     * @param  int    $productID
     * @param  int    $startModule
     * @param  string $userFunc
     * @param  string $extra
     * @access public
     * @return void
     */
    public function getBugTreeMenu($rootID, $productID = 0, $startModule = 0, $userFunc = '', $extra = '')
    {
        $extra += array('executionID' => $rootID, 'projectID' => $rootID, 'productID' => $productID, 'tip' => true);
        $tab    = $this->app->tab;

        /* If createdVersion <= 4.1, go to getTreeMenu(). */
        $products      = $tab == 'execution' ? $this->loadModel('product')->getProducts($rootID) : $this->loadModel('product')->getProductPairsByProject($rootID);
        $branchGroups  = $this->loadModel('branch')->getByProducts(array_keys($products));

        /* createdVersion > 4.1. */
        $menu = "<ul id='modules' class='tree' data-ride='tree' data-name='tree-bug'>";

        /* Set the start module. */
        $startModulePath = '';
        if($startModule > 0)
        {
            $startModule = $this->getById($startModule);
            if($startModule) $startModulePath = $startModule->path . '%';
        }

        $executionModules = $this->getTaskTreeModules($rootID, true, 'bug');

        /* Get module according to product. */
        $productNum = count($products);
        $moduleName = strpos(',project,execution,', ",$tab,") !== false ? $this->app->tab  : 'bug';
        $methodName = strpos(',project,execution,', ",$tab,") !== false ? 'bug' : 'browse';
        $param      = strpos(',project,execution,', ",$tab,") !== false ? "{$tab}ID={$rootID}&" : '';
        foreach($products as $id => $product)
        {
            $extra['productID'] = $id;
            $link  = helper::createLink($moduleName, $methodName, "{$param}productID=$id");
            $menu .= "<li>" . html::a($link, is_object($product) ? $product->name : $product, '_self', "id='product$id'");

            /* tree menu. */
            $tree = '';
            if(empty($branchGroups[$id])) $branchGroups[$id]['0'] = '';
            foreach($branchGroups[$id] as $branch => $branchName)
            {
                $query = $this->dao->select('*')->from(TABLE_MODULE)->where("((root = $id and type = 'bug' and branch='$branch') OR (root = $id and type = 'story' and branch ='$branch'))")
                    ->beginIF($startModulePath)->andWhere('path')->like($startModulePath)->fi()
                    ->andWhere('deleted')->eq(0)
                    ->orderBy('grade desc, `order`, type')
                    ->get();
                $treeMenu = array();
                $stmt     = $this->app->dbQuery($query);
                while($module = $stmt->fetch())
                {
                    if(isset($executionModules[$module->id])) $this->buildTree($treeMenu, $module, 'bug', $userFunc, $extra, $branch);
                }
                $tree .= isset($treeMenu[0]) ? $treeMenu[0] : '';
            }

            if($tree) $tree = "<ul>" . $tree . "</ul>\n</li>";
            $menu .= $tree;
        }
        $menu .= '</ul>';
        return $menu;
    }

    /**
     * Get the tree menu of case in html.
     *
     * @param  int    $rootID
     * @param  int    $productID
     * @param  int    $startModule
     * @param  string $userFunc
     * @param  string $extra
     * @access public
     * @return void
     */
    public function getCaseTreeMenu($rootID, $productID = 0, $startModule = 0, $userFunc = '', $extra = '')
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

        /* createdVersion > 4.1. */
        $menu = "<ul id='modules' class='tree' data-ride='tree' data-name='tree-case'>";

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
        foreach($products as $id => $product)
        {
            $extra['productID'] = $id;
            $link  = helper::createLink($moduleName, $methodName, $param . "productID=$id");
            $menu .= "<li>" . html::a($link, is_object($product) ? $product->name : $product, '_self', "id='product$id' data-app='{$this->app->tab}'");

            /* tree menu. */
            $tree = '';
            if(empty($branchGroups[$id])) $branchGroups[$id]['0'] = '';
            foreach($branchGroups[$id] as $branch => $branchName)
            {
                $treeMenu = array();
                $query = $this->dao->select('*')->from(TABLE_MODULE)->where("((root = $id and type = 'case' and branch = '$branch') OR (root = $id and type = 'story' and branch = '$branch'))")
                    ->beginIF($startModulePath)->andWhere('path')->like($startModulePath)->fi()
                    ->andWhere('deleted')->eq(0)
                    ->orderBy('grade desc, `order`, type')
                    ->get();
                $stmt = $this->app->dbQuery($query);
                while($module = $stmt->fetch())
                {
                    if(isset($executionModules[$module->id])) $this->buildTree($treeMenu, $module, 'case', $userFunc, $extra, $branch);
                }
                $tree .= isset($treeMenu[0]) ? $treeMenu[0] : '';
            }

            if($tree) $tree = "<ul>" . $tree . "</ul>\n</li>";
            $menu .= $tree;
        }
        $menu .= '</ul>';
        return $menu;
    }

    /**
     * Get project story tree menu.
     *
     * @param  int    $rootID
     * @param  int    $startModule
     * @param  string $userFunc
     * @param  string $storyType
     * @access public
     * @return string
     */
    public function getProjectStoryTreeMenu($rootID, $startModule = 0, $userFunc = '', $storyType = 'story')
    {
        $this->app->loadLang('branch');

        if($this->app->rawModule == 'projectstory') $extra['projectID'] = $rootID;
        if($this->app->rawModule == 'execution') $extra['executionID'] = $rootID;
        $menu = "<ul id='modules' class='tree' data-ride='tree' data-name='tree-story'>";
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
        foreach($products as $id => $product)
        {
            $extra['productID']   = $id;
            $projectProductLink   = helper::createLink('projectstory', 'story', "projectID=$rootID&productID=$id&branch=all&browseType=&param=0&storyType=$storyType");
            $executionProductLink = helper::createLink('execution', 'story', "executionID=$rootID&storyType=$storyType&orderBy=&type=byProduct&praram=$id");
            $link = $this->app->rawModule == 'projectstory' ? $projectProductLink : $executionProductLink;
            if($productNum > 1) $menu .= "<li>" . html::a($link, $product, '_self', "id='product$id' title=$product");

            /* tree menu. */
            $tree = '';
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
                    /* If not manage, ignore unused modules. */
                    if(isset($executionModules[$module->id])) $this->buildTree($treeMenu, $module, 'story', $userFunc, $extra);
                }
                $tree .= isset($treeMenu[0]) ? $treeMenu[0] : '';
            }
            if($productNum > 1) $tree = "<ul>" . $tree . "</ul>\n</li>";
            $menu .= $tree;
        }

        $menu .= '</ul>';
        return $menu;
    }

    /**
     * Get project story tree menu.
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
            $treeMenu = $this->build20Tree($module, '', 0, array('treeModel', 'createHostLink'));
            if($module->parent == 0) $treeMenu->parent = $module->root;

            $menu[] = $treeMenu;
        }

        return $menu;
    }

    /**
     * Build tree.
     *
     * @param  & $&treeMenu
     * @param  object $module
     * @param  string $type
     * @param  string $userFunc
     * @param  array  $extra
     * @param  int    $branch
     * @access public
     * @return void
     */
    public function buildTree(& $treeMenu, $module, $type, $userFunc, $extra, $branch = 'all')
    {
        /* Add for task #1945. check the module has case or no. */
        if((isset($extra['rootID']) and isset($extra['branch']) and $branch === 'null') or ($type == 'case' and is_numeric($extra)))
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
                if($type != 'story') $typeCondition .= " or type='{$type}'";
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
            if(!$hasObjects) return;
        }

        if(is_array($extra)) $extra['branchID'] = $branch;
        if(empty($extra))
        {
            $extra = array();
            $extra['branchID'] = $branch;
        }
        $linkHtml = call_user_func($userFunc, $type, $module, $extra);

        if(isset($treeMenu[$module->id]) and !empty($treeMenu[$module->id]))
        {
            if(!isset($treeMenu[$module->parent])) $treeMenu[$module->parent] = '';
            $treeMenu[$module->parent] .= "<li class='closed'>$linkHtml";
            $treeMenu[$module->parent] .= "<ul>" . $treeMenu[$module->id] . "</ul>\n";
        }
        else
        {
            if(!isset($treeMenu[$module->parent])) $treeMenu[$module->parent] = "";
            $treeMenu[$module->parent] .= "<li>$linkHtml\n";
        }
        $treeMenu[$module->parent] .= "</li>\n";
    }

    /**
     * Build tree of version 20.
     *
     * @param  object     $module
     * @param  string     $type
     * @param  int|string $parent
     * @param  array      $userFunc
     * @param  array      $extra
     * @param  int        $branch
     * @access public
     * @return object|false
     */
    public function build20Tree(object $module, string $type, $parent = 0, array $userFunc = array(), $extra = array(), string $branch = 'all')
    {
        /* Add for task #1945. check the module has case or no. */
        if((isset($extra['rootID']) and isset($extra['branch']) and $branch === 'null') or ($type == 'case' and is_numeric($extra)))
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
                if($type != 'story') $typeCondition .= " or type='{$type}'";
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
     * Get execution modules.
     *
     * @param  int    $executionID
     * @param  bool   $parent
     * @param  string $linkObject
     * @param  array  $extra
     * @access public
     * @return array
     */
    public function getTaskTreeModules($executionID, $parent = false, $linkObject = 'story', $extra = array())
    {
        $executionModules = array();
        $field = $parent ? 'path' : 'id';

        if($linkObject == 'story')
        {
            $table1 = TABLE_PROJECTSTORY;
            $table2 = TABLE_STORY;
        }
        if($linkObject == 'case')
        {
            $table1 = TABLE_PROJECTCASE;
            $table2 = TABLE_CASE;
        }

        if($linkObject)
        {
            $branch = zget($extra, 'branchID', 0);
            /* Get object paths of this execution. */
            if(strpos(',story,case,', ",$linkObject,") !== false)
            {
                $paths = $this->dao->select('DISTINCT t3.' . $field)->from($table1)->alias('t1')
                    ->leftJoin($table2)->alias('t2')->on('t1.' . $linkObject . ' = t2.id')
                    ->leftJoin(TABLE_MODULE)->alias('t3')->on('t2.module = t3.id')
                    ->where('t1.project')->eq($executionID)
                    ->andWhere('t3.deleted')->eq(0)
                    ->andWhere('t2.deleted')->eq(0)
                    ->beginIF(isset($extra['branchID']) and $branch !== 'all')->andWhere('t2.branch')->eq($branch)->fi()
                    ->fetchPairs();
            }
            elseif($linkObject == 'bug' and strpos(',project,execution,', ",{$this->app->tab},") !== false)
            {
                $paths = $this->dao->select('DISTINCT t2.' . $field)->from(TABLE_BUG)->alias('t1')
                    ->leftJoin(TABLE_MODULE)->alias('t2')->on('t1.module = t2.id')
                    ->where('t1.deleted')->eq(0)
                    ->andWhere('t2.deleted')->eq(0)
                    ->beginIF(isset($extra['branchID']) and $branch !== 'all')->andWhere('t1.branch')->eq($branch)->fi()
                    ->andWhere("t1.{$this->app->tab}")->eq($executionID)
                    ->fetchPairs();
            }
            else
            {
                return array();
            }
        }
        else
        {
            $productGroups = $this->dao->select('product,branch')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($executionID)->fetchGroup('product', 'branch');
            $modules = $this->dao->select('id,root,branch')->from(TABLE_MODULE)
                ->where('root')->in(array_keys($productGroups))
                ->andWhere('type')->eq('story')
                ->andWhere('deleted')->eq(0)
                ->fetchAll();

            $paths = array();
            foreach($modules as $module)
            {
                if(empty($module->branch)) $paths[$module->id] = $module->id;
                if(isset($productGroups[$module->root][0]) or isset($productGroups[$module->root][$module->branch])) $paths[$module->id] = $module->id;
            }
        }

        if(strpos(',case,bug,', ",$linkObject,") === false)
        {
            /* Add task paths of this execution.*/
            $paths += $this->dao->select($field)->from(TABLE_MODULE)
                ->where('root')->eq($executionID)
                ->andWhere('type')->eq('task')
                ->andWhere('deleted')->eq(0)
                ->fetchPairs();

            /* Add task paths of this execution for has existed. */
            $paths += $this->dao->select('DISTINCT t1.' . $field)->from(TABLE_MODULE)->alias('t1')
            ->leftJoin(TABLE_TASK)->alias('t2')->on('t1.id=t2.module')
            ->where('t2.module')->ne(0)
            ->andWhere('t2.execution')->eq($executionID)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.type')->eq('story')
            ->andWhere('t1.deleted')->eq(0)
            ->fetchPairs();
        }

        /* Get all modules from paths. */
        foreach($paths as $path)
        {
            $modules = explode(',', $path);
            foreach($modules as $module) $executionModules[$module] = $module;
        }
        return $executionModules;
    }

    /**
     * Create link of a story.
     *
     * @param  string $type
     * @param  object $module
     * @param  array  $extra
     * @access public
     * @return string
     */
    public function createStoryLink($type, $module, $extra = array())
    {
        if(isset($extra['projectID']) and !empty($extra['projectID']))
        {
            $productID = zget($extra, 'productID', 0);
            $projectID = $extra['projectID'];
            return html::a(helper::createLink('projectstory', 'story', "projectID=$projectID&productID=$productID&branch=&browseType=byModule&param={$module->id}&storyType=story"), $module->name, '_self', "id='module{$module->id}' title='{$module->name}'");
        }
        elseif(isset($extra['executionID']) and !empty($extra['executionID']))
        {
            $executionID = $extra['executionID'];
            return html::a(helper::createLink('execution', 'story', "executionID=$executionID&storyType=story&orderBy=order_desc&type=byModule&param={$module->id}"), $module->name, '_self', "id='module{$module->id}' title='{$module->name}'");
        }
        else
        {
            return html::a(helper::createLink('product', 'browse', "root={$module->root}&branch={$extra['branchID']}&type=byModule&param={$module->id}&storyType=story"), $module->name, '_self', "id='module{$module->id}' title='{$module->name}'");
        }
    }

    /**
     * Create link of requirement for waterfall.
     *
     * @param  string $type
     * @param  object $module
     * @param  array  $extra
     * @access public
     * @return string
     */
    public function createRequirementLink($type, $module, $extra = array())
    {
        if(isset($extra['projectID']) and !empty($extra['projectID']))
        {
            $productID = zget($extra, 'productID', 0);
            $projectID = $extra['projectID'];
            return html::a(helper::createLink('projectstory', 'story', "projectID=$projectID&productID=$productID&branch=&browseType=byModule&param={$module->id}&storyType=requirement"), $module->name, '_self', "id='module{$module->id}' title='{$module->name}'");
        }
        elseif(isset($extra['executionID']) and !empty($extra['executionID']))
        {
            $executionID = $extra['executionID'];
            return html::a(helper::createLink('execution', 'story', "executionID=$executionID&storyType=requirement&orderBy=order_desc&type=byModule&param={$module->id}"), $module->name, '_self', "id='module{$module->id}' title='{$module->name}'");
        }
        else
        {
            return html::a(helper::createLink('product', 'browse', "root={$module->root}&branch={$extra['branchID']}&type=byModule&param={$module->id}&storyType=requirement"), $module->name, '_self', "id='module{$module->id}' title='{$module->name}'");
        }
    }

    /**
     * Create link of a product line.
     *
     * @param  string $type
     * @param  object $module
     * @param  array  $extra
     * @access public
     * @return string
     */
    public function createLineLink($type, $module, $extra)
    {
        $productID = $extra['productID'];
        $status    = $extra['status'];
        return html::a(helper::createLink('product', 'all', "productID={$productID}&line={$module->id}&status={$status}"), $module->name, '_self', "id='module{$module->id}'");
    }

    /**
     * Create link of a task.
     *
     * @param  string $type
     * @param  object $module
     * @param  array  $extra
     * @access public
     * @return string
     */
    public function createTaskLink($type, $module, $extra)
    {
        $executionID = $extra['executionID'];
        $productID   = $extra['productID'];
        return html::a(helper::createLink('execution', 'task', "executionID={$executionID}&type=byModule&param={$module->id}"), $module->name, '_self', "id='module{$module->id}' title='{$module->name}'");
    }

    /**
     * Create link of project story.
     *
     * @param  string $type
     * @param  object $module
     * @param  array  $extra
     * @access public
     * @return string
     */
    public function createProjectStoryLink($type, $module, $extra)
    {
        $productID = $extra['productID'];
        return html::a(helper::createLink('projectstory', 'story', "projectID={$extra['executionID']}&productID=$productID&branch=&browseType=byModule&param={$module->id}"), $module->name, '_self', "id='module{$module->id}' title='{$module->name}'");
    }

    /**
     * Create link of a doc.
     *
     * @param  object   $module
     * @access public
     * @return string
     */
    public function createDocLink($type, $module, $extra = '')
    {
        return html::a(helper::createLink('doc', 'browse', "libID={$module->root}&browseType=byModule&param={$module->id}"), $module->name, '_self', "id='module{$module->id}' title='{$module->name}'");
    }

    /**
     * Create the manage link of a module.
     *
     * @param  object   $module
     * @access public
     * @return string
     */
    public function createManageLink($type, $module, $extra)
    {
        $branchID = $extra['branchID'];
        $tip = strpos('bug,case', $type) === false ? '' : ' <span style="font-size:smaller;">[' . strtoupper(substr($type, 0, 1)) . ']</span>';
        static $users;
        if(empty($users)) $users = $this->loadModel('user')->getPairs('noletter');
        $linkHtml  = $module->name;
        $linkHtml .= $module->type != 'story' ? $tip : '';
        if($type == 'bug' and $module->owner) $linkHtml .= '<span class="owner">[' . $users[$module->owner] . ']</span>';
        if($type != 'story' and $module->type == 'story')
        {
            if(common::hasPriv('tree', 'edit') and $type == 'bug') $linkHtml .= ' ' . html::a(helper::createLink('tree', 'edit',   "module={$module->id}&type=$type&branch=$branchID"), $this->lang->tree->edit, '', 'data-toggle="modal" data-type="ajax"');
            if(common::hasPriv('tree', 'browse')) $linkHtml .= ' ' . html::a(helper::createLink('tree', 'browse', "root={$module->root}&type=$type&module={$module->id}&branch=$branchID"), $this->lang->tree->child);
        }
        else
        {
            if(common::hasPriv('tree', 'edit')) $linkHtml .= ' ' . html::a(helper::createLink('tree', 'edit',   "module={$module->id}&type=$type&branch=$branchID"), $this->lang->tree->edit, '', 'data-toggle="modal" data-type="ajax" data-width="500"');
            if(common::hasPriv('tree', 'browse') and strpos($this->config->tree->noBrowse, ",$module->type,") === false) $linkHtml .= ' ' . html::a(helper::createLink('tree', 'browse', "root={$module->root}&type=$type&module={$module->id}&branch=$branchID"), $this->lang->tree->child);
            if(common::hasPriv('tree', 'delete')) $linkHtml .= ' ' . html::a(helper::createLink('tree', 'delete', "root={$module->root}&module={$module->id}"), $this->lang->delete, 'hiddenwin');
            if(common::hasPriv('tree', 'updateorder')) $linkHtml .= ' ' . html::input("orders[$module->id]", $module->order, 'class="text-center w-40px inline"');
        }
        return $linkHtml;
    }

    /**
     * Create the task manage link of a module.
     *
     * @param  int    $productID
     * @param  int    $module
     * @access public
     * @return void
     */
    public function createTaskManageLink($type, $module, $extra)
    {
        $executionID = $extra['executionID'];
        $productID   = $extra['productID'];
        $tip         = $extra['tip'];
        $linkHtml    = $module->name;
        $linkHtml   .= ($tip and $module->type != 'story') ? ' [T]' : '';
        if($module->type == 'story')
        {
            if(common::hasPriv('tree', 'browseTask')) $linkHtml .= ' ' . html::a(helper::createLink('tree', 'browsetask', "rootID=$executionID&productID=$productID&module={$module->id}"), $this->lang->tree->child);
        }
        else
        {
            if(common::hasPriv('tree', 'edit'))        $linkHtml .= ' ' . html::a(helper::createLink('tree', 'edit', "module={$module->id}&type=task"), $this->lang->tree->edit, '', 'data-toggle="modal" data-type="ajax"');
            if(common::hasPriv('tree', 'browseTask'))  $linkHtml .= ' ' . html::a(helper::createLink('tree', 'browsetask', "rootID=$executionID&productID=$productID&module={$module->id}"), $this->lang->tree->child);
            if(common::hasPriv('tree', 'delete'))      $linkHtml .= ' ' . html::a(helper::createLink('tree', 'delete', "root={$module->root}&module={$module->id}"), $this->lang->delete, 'hiddenwin');
            if(common::hasPriv('tree', 'updateorder')) $linkHtml .= ' ' . html::input("orders[$module->id]", $module->order, 'style="width:30px;text-align:center"');
        }
        return $linkHtml;
    }

    /**
     * Create link of a bug.
     *
     * @param  string $type
     * @param  object $module
     * @param  array  $extra
     * @access public
     * @return string
     */
    public function createBugLink($type, $module, $extra = array())
    {
        $moduleName = strpos(',project,execution,', ",{$this->app->tab},") !== false ? $this->app->tab : 'bug';
        $methodName = strpos(',project,execution,', ",{$this->app->tab},") !== false ? 'bug' : 'browse';
        $param      = "root={$module->root}&branch=&type=byModule&param={$module->id}";

        $extra['type'] = (isset($extra['type']) and $extra['type'] != 'bysearch') ? $extra['type'] : 'all';
        if($this->app->tab == 'execution') $param = "execuitonID={$extra['projectID']}&productID={$module->root}&branch={$extra['branchID']}&orderBy={$extra['orderBy']}&build={$extra['build']}&type={$extra['type']}&param={$module->id}";
        if($this->app->tab == 'project') $param = "projectID={$extra['projectID']}&productID={$module->root}&branch={$extra['branchID']}&orderBy={$extra['orderBy']}&build={$extra['build']}&type={$extra['type']}&param={$module->id}";
        return html::a(helper::createLink($moduleName, $methodName, $param), $module->name, '_self', "id='module{$module->id}' title='{$module->name}'");
    }

    /**
     * Create link of a test case.
     *
     * @param  string $type
     * @param  object $module
     * @param  array  $extra
     * @access public
     * @return string
     */
    public function createCaseLink($type, $module, $extra = array())
    {
        $moduleName = strpos(',project,execution,', ",{$this->app->tab},") !== false ? $this->app->tab : 'testcase';
        $methodName = strpos(',project,execution,', ",{$this->app->tab},") !== false ? 'testcase' : 'browse';
        $param      = $this->app->tab == 'project' ? "projectID={$this->session->project}&" : "";
        $param      = $this->app->tab == 'execution' ? "executionID={$extra['projectID']}&" : $param;
        return html::a(helper::createLink($moduleName, $methodName, $param . "root={$module->root}&branch={$extra['branchID']}&type=byModule&param={$module->id}"), $module->name, '_self', "id='module{$module->id}' data-app='{$this->app->tab}' title='{$module->name}'");
    }

    /**
     * Create link of a test task.
     *
     * @param  object  $module
     * @access public
     * @return string
     */
    public function createTestTaskLink($type, $module, $extra)
    {
        return html::a(helper::createLink('testtask', 'cases', "taskID=$extra&type=byModule&module={$module->id}"), $module->name, '_self', "id='module{$module->id}' title='{$module->name}'");
    }

    /**
     * Create case lib link
     *
     * @param  string $type
     * @param  string $module
     * @access public
     * @return string
     */
    public function createCaseLibLink($type, $module)
    {
        return html::a(helper::createLink('caselib', 'browse', "root={$module->root}&type=byModule&param={$module->id}"), $module->name, '_self', "id='module{$module->id}' title='{$module->name}'");
    }

    /**
     * Create branch link
     *
     * @param  string $type
     * @param  int    $rootID
     * @param  int    $branchID
     * @param  string $branch
     * @access public
     * @return string
     */
    public function createBranchLink($type, $rootID, $branchID, $branch)
    {
        if($type == 'story') return html::a(helper::createLink('product', 'browse', "productID={$rootID}&branch=$branchID"), $branch, '_self', "id='branch{$branchID}'");
        if($type == 'bug')   return html::a(helper::createLink('bug', 'browse', "productID={$rootID}&branch=$branchID"), $branch, '_self', "id='branch{$branchID}'");
        if($type == 'case')  return html::a(helper::createLink('testcase', 'browse', "productID={$rootID}&branch=$branchID"), $branch, '_self', "id='branch{$branchID}'");
    }

    /**
     * Create link of feedback.
     *
     * @param  string $type
     * @param  object $module
     * @access public
     * @return string
     */
    public function createFeedbackLink($type, $module)
    {
        return html::a(helper::createLink('feedback', $this->app->methodName, "type=byModule&param={$module->id}"), $module->name, '_self', "id='module{$module->id}' title='{$module->name}'");
    }

    /**
     * Create link of ticket.
     *
     * @param  string $type
     * @param  object $module
     * @access public
     * @return string
     */
    public function createTicketLink($type, $module)
    {
        return html::a(helper::createLink('ticket', $this->app->methodName, "type=byModule&param={$module->id}"), $module->name, '_self', "id='module{$module->id}' title='{$module->name}'");
    }

    /**
     * Create link of practice.
     *
     * @param  string $type
     * @param  object $module
     * @access public
     * @return string
     */
    public function createPracticeLink($type, $module)
    {
        return html::a(helper::createLink('traincourse', 'practicebrowse', "moduleID={$module->id}"), $module->name, '_self', "id='module{$module->id}' title='{$module->name}'");
    }

    /**
     * Create link of trainskill.
     *
     * @param  string $type
     * @param  object $module
     * @param  string $extra
     * @access public
     * @return string
     */
    public function createTrainSkillLink($type, $module, $extra = '')
    {
        return html::a(helper::createLink('trainskill', 'browse', "type=byModule&param={$module->id}"), $module->name, '', "id='module{$module->id}' title='{$module->name}'");
    }

    /**
     * Create link of traincourse.
     *
     * @param  string $type
     * @param  object $module
     * @param  string $extra
     * @access public
     * @return string
     */
    public function createTrainCourseLink($type, $module, $extra = '')
    {
        return html::a(helper::createLink('traincourse', 'browse', "type=byModule&param={$module->id}"), $module->name, '', "id='module{$module->id}' title='{$module->name}'");
    }

    /**
     * Create link of trainpost.
     *
     * @param  string $type
     * @param  object $module
     * @param  string $extra
     * @access public
     * @return string
     */
    public function createTrainPostLink($type, $module, $extra = '')
    {
        return html::a(helper::createLink('trainpost', 'browse', "type=byModule&param={$module->id}"), $module->name, '', "id='module{$module->id}' title='{$module->name}'");
    }

    /**
     * Create dashboard link.
     *
     * @param  string $type
     * @param  object $module
     * @param  string $extra
     * @access public
     * @return string
     */
    public function createDashboardLink($type, $module, $extra = '')
    {
        return html::a(helper::createLink('dashboard', 'browse', "type=bymodule&param={$module->id}"), $module->name, '', "id='module{$module->id}' title='{$module->name}'");
    }

    /**
     * Create report link.
     *
     * @param  string $type
     * @param  object $module
     * @param  array  $extra
     * @access public
     * @return string
     */
    public function createReportLink($type, $module, $extra)
    {
        $dimension = zget($extra, 'dimension', 0);
        return html::a(helper::createLink('report', 'browsereport', "dimension={$dimension}&module={$module->id}"), $module->name, '', "id='module{$module->id}' title='{$module->name}'");
    }

    /**
     * Create link of a host.
     *
     * @param  string $type
     * @param  object $module
     * @param  int    $parent
     * @param  array  $extra
     * @access public
     * @return object
     */
    public function createHostLink(string $type, object $module, int $parent = 0, array $extra = array()): object
    {
        $data = new stdclass();
        $data->id     = $parent ? uniqid() : $module->id;
        $data->parent = $parent ? $parent : $module->parent;
        $data->name   = $module->name;

        $data->url = helper::createLink('host', 'browse', "browseType=bymodule&param={$module->id}");

        return $data;
    }

    /**
     * Get sons of a module.
     *
     * @param  int    $rootID
     * @param  int    $moduleID
     * @param  string $type
     * @param  int    $branch
     * @access public
     * @return array
     */
    public function getSons($rootID, $moduleID, $type = 'root', $branch = 0)
    {
        $syncConfig = $this->getSyncConfig($type);

        if($type  == 'line') $rootID = 0;
        if(($type == 'feedback' or $type == 'ticket') and isset($syncConfig[$rootID])) $type = "$type,story";

        /* if createVersion <= 4.1 or type == 'story', only get modules of its type. */
        if(!$this->isMergeModule($rootID, $type) or $type == 'story')
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

        /* else get modules of its type and story type. */
        if(strpos('task|case|bug', $type) !== false) $type = "$type,story";

        return $this->dao->select('*')->from(TABLE_MODULE)
            ->where('root')->eq((int)$rootID)
            ->andWhere('parent')->eq((int)$moduleID)
            ->andWhere('type')->in($type)
            ->andWhere()
            ->markLeft(1)
            ->where("branch")->eq(0)
            ->beginIF($branch != 0)->orWhere("branch")->eq((int)$branch)->fi()
            ->markRight(1)
            ->andWhere('deleted')->eq(0)
            ->orderBy('`order`, type desc')
            ->fetchAll();
    }

    /**
     * Get sons of a task module.
     *
     * @param  int    $rootID
     * @param  int    $productID
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function getTaskSons($rootID, $productID, $moduleID)
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
                ->andWhere("(root = '" . (int)$rootID . "' and type = 'task'")
                ->orWhere("root = '" . (int)$productID . "' and type = 'story')")
                ->orderBy('`order`, type')
                ->fetchAll();
        }
    }

    /**
     * Get id list of a module's childs.
     *
     * @param  int     $moduleID
     * @access public
     * @return array
     */
    public function getAllChildId($moduleID)
    {
        if($moduleID == 0) return array();

        $module = $this->getById((int)$moduleID);
        if(empty($module)) return array();

        return $this->dao->select('id')->from(TABLE_MODULE)->where("CONCAT(',', path, ',')")->like("%$module->path%")->andWhere('deleted')->eq(0)->fetchPairs();
    }

    /**
     * Get project module.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function getProjectModule($projectID, $productID = 0)
    {
        $modules = array();
        $rootModules = $this->dao->select('path')->from(TABLE_MODULE)
            ->where('root')->eq($productID)
            ->andWhere('type')->eq('story')
            ->andWhere('parent')->eq(0)
            ->andWhere('deleted')->eq(0)
            ->fetchAll();
        foreach($rootModules as $module)
        {
            $modules += $this->dao->select('id')->from(TABLE_MODULE)
                ->where('path')->like($module->path . '%')
                ->andWhere('deleted')->eq(0)
                ->fetchPairs();
        }
        return $modules;
    }

    /**
     * Get parents of a module.
     *
     * @param  int    $moduleID
     * @access public
     * @return array
     */
    public function getParents($moduleID)
    {
        if($moduleID == 0) return array();
        $path = $this->dao->select('path')->from(TABLE_MODULE)->where('id')->eq((int)$moduleID)->fetch('path');
        $path = trim($path, ',');
        if(!$path) return array();
        return $this->dao->select('*')->from(TABLE_MODULE)->where('id')->in($path)->andWhere('deleted')->eq(0)->orderBy('grade')->fetchAll();
    }

    /**
     * Get product by moduleID.
     *
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function getProduct($moduleID)
    {
        if($moduleID == 0) return '';
        $path  = $this->dao->select('path')->from(TABLE_MODULE)->where('id')->eq((int)$moduleID)->fetch('path');
        $paths = explode(',', trim($path, ','));
        if(!$path) return '';
        $moduleID = $paths[0];
        $module   = $this->dao->select('*')->from(TABLE_MODULE)->where('id')->eq($moduleID)->fetch();
        if($module->type != 'story' or !$module->root) return '';
        return $this->dao->select('name')->from(TABLE_PRODUCT)->where('id')->eq($module->root)->fetch();
    }

    /**
     * Get the module that its type == 'story'.
     *
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function getStoryModule($moduleID)
    {
        $module = $this->dao->select('id,type,parent')->from(TABLE_MODULE)->where('id')->eq((int)$moduleID)->fetch();
        if(empty($module)) return 0;

        while(!empty($module) and $module->id and $module->type != 'story')
        {
            $module = $this->dao->select('id,type,parent')->from(TABLE_MODULE)->where('id')->eq($module->parent)->fetch();
        }

        return empty($module) ? 0 : $module->id;
    }

    /**
     * Get modules name.
     *
     * @param  array  $moduleIdList
     * @param  bool   $allPath
     * @param  bool   $branchPath
     * @access public
     * @return array
     */
    public function getModulesName($moduleIdList, $allPath = true, $branchPath = false)
    {
        if(!$allPath) return $this->dao->select('id, name')->from(TABLE_MODULE)->where('id')->in($moduleIdList)->andWhere('deleted')->eq(0)->fetchPairs('id', 'name');

        $modules     = $this->dao->select('id, name, path, branch')->from(TABLE_MODULE)->where('id')->in($moduleIdList)->andWhere('deleted')->eq(0)->fetchAll('path');
        $allModules  = $this->dao->select('id, name')->from(TABLE_MODULE)->where('id')->in(join(array_keys($modules)))->andWhere('deleted')->eq(0)->fetchPairs('id', 'name');

        $branchIDList = array();
        $modulePairs = array();
        foreach($modules as $module)
        {
            $paths = explode(',', trim($module->path, ','));
            $moduleName = '';
            foreach($paths as $path) $moduleName .= '/' . $allModules[$path];
            $modulePairs[$module->id] = $moduleName;

            if($module->branch) $branchIDList[$module->branch] = $module->branch;
        }

        if(!$branchPath) return $modulePairs;

        $branchs  = $this->dao->select('id, name')->from(TABLE_BRANCH)->where('id')->in($branchIDList)->andWhere('deleted')->eq(0)->fetchALL('id');
        foreach($modules as $module)
        {
            if(isset($modulePairs[$module->id]))
            {
                $branchName = isset($branchs[$module->branch]) ? '/' . $branchs[$module->branch]->name : '';
                $modulePairs[$module->id] = $branchName . $modulePairs[$module->id];
            }
        }

        return $modulePairs;
    }

    /**
     * Update modules' order.
     *
     * @param  array   $orders
     * @access public
     * @return void
     */
    public function updateOrder($orders)
    {
        asort($orders);
        $orderInfo = $this->dao->select('*')->from(TABLE_MODULE)->where('id')->in(array_keys($orders))->andWhere('deleted')->eq(0)->fetchAll('id');
        $newOrders = array();
        foreach($orders as $moduleID => $order)
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
     * Manage childs of a module.
     *
     * @param  int    $rootID
     * @param  string $type
     * @access public
     * @return array
     */
    public function manageChild($rootID, $type)
    {
        if($type == 'line') $rootID = 0;

        $data           = fixer::input('post')->get();
        $childs         = $data->modules;
        $parentModuleID = $data->parentModuleID;

        foreach($childs as $moduleID => $moduleName)
        {
            if(preg_match('/(^\s+$)/', $moduleName)) helper::end(js::alert($this->lang->tree->shouldNotBlank));
        }

        $module         = new stdClass();
        $module->root   = $rootID;
        $module->type   = $type;
        $module->parent = $parentModuleID;
        $repeatName     = $this->checkUnique($module, $childs);
        if($repeatName) helper::end(js::alert(sprintf($this->lang->tree->repeatName, $repeatName)));

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
                if(isset($orders[$moduleID]) and !empty($orders[$moduleID]))
                {
                    $order = $orders[$moduleID];
                }
                else
                {
                    $order = $this->post->maxOrder + $i * 10;
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
                $short    = $shorts[$moduleID];
                $order    = $orders[$moduleID];
                $moduleID = str_replace('id', '', $moduleID);

                $oldModule = $this->getByID($moduleID);

                $data = new stdClass();
                $data->name  = strip_tags(trim($moduleName));
                $data->short = $short;
                $data->order = $order;

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

        if($type == 'story' or strpos($this->config->tree->groupTypes, ",$type,") !== false)
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
     * Update a module.
     *
     * @param  int    $moduleID
     * @access public
     * @return void
     */
    public function update($moduleID)
    {
        $module = fixer::input('post')->get();
        $self   = $this->getById($moduleID);
        $changes = common::createChanges($self, $module);
        if(!isset($_POST['branch'])) $module->branch = $self->branch;

        if($self)
        {
            if($self->root and !isset($module->root)) $module->root = $self->root;
            if($self->parent != $module->parent or $self->root != $module->root)
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
            helper::end(js::alert(sprintf($tips, $repeatName)));
        }

        if((empty($module->root) or empty($module->name)) and in_array($self->type, array('doc', 'api')))
        {
            $this->app->loadLang('doc');
            if(empty($module->root)) helper::end(js::alert(sprintf($this->lang->error->notempty, $this->lang->doc->lib)));
            if(empty($module->name)) helper::end(js::alert(sprintf($this->lang->error->notempty, $this->lang->doc->catalogName)));
        }

        $modules = $self->type == 'story' ? $this->getOptionMenu($self->root, 'story', 0, 'all') : array();

        $parent = $this->getById($this->post->parent);
        $childs = $this->getAllChildId($moduleID);
        $module->name  = strip_tags(trim($module->name));
        $module->grade = $parent ? $parent->grade + 1 : 1;
        $module->path  = $parent ? $parent->path . $moduleID . ',' : ',' . $moduleID . ',';
        $this->dao->update(TABLE_MODULE)->data($module)->autoCheck()->check('name', 'notempty')->where('id')->eq($moduleID)->exec();
        $this->dao->update(TABLE_MODULE)->set('grade = grade + 1')->where('id')->in($childs)->andWhere('id')->ne($moduleID)->exec();
        $this->dao->update(TABLE_MODULE)->set('owner')->eq($this->post->owner)->where('id')->in($childs)->andWhere('owner')->eq('')->exec();
        $this->dao->update(TABLE_MODULE)->set('owner')->eq($this->post->owner)->where('id')->in($childs)->andWhere('owner')->eq($self->owner)->exec();

        if($self->type == 'story' or strpos($this->config->tree->groupTypes, ",$self->type,") !== false)
        {
            $objectType = $self->type == 'story' ? 'module' : 'chartgroup';
            $rootID     = isset($module->root) ? $module->root : $self->root;
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
            if(isset($module->root) and $module->root != $self->root)
            {
                $actionID = $this->action->create($objectType, $rootID, 'edited', '', $moduleID);
                if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            }
        }

        if(isset($module->root) and $module->root != $self->root)
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
        $this->fixModulePath(isset($module->root) ? $module->root : $self->root, $self->type);
        if(isset($module->root) and $module->root != $self->root) $this->changeRoot($moduleID, $self->root, $module->root, $self->type);
    }

    /**
     * Change root.
     *
     * @param  int    $moduleID
     * @param  int    $oldRoot
     * @param  int    $newRoot
     * @param  string $type
     * @access public
     * @return void
     */
    public function changeRoot($moduleID, $oldRoot, $newRoot, $type)
    {
        /* Get all children id list. */
        $childIdList = $this->dao->select('id')->from(TABLE_MODULE)->where('path')->like("%,$moduleID,%")->andWhere('deleted')->eq(0)->fetchPairs('id', 'id');

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
                if(!isset($projectProduct[$executionID]) or !in_array($newRoot, array_keys($projectProduct[$executionID]))) $this->dao->insert(TABLE_PROJECTPRODUCT)->set('project')->eq($executionID)->set('product')->eq($newRoot)->exec();
                if(isset($linkedProduct[$executionID])  and !in_array($oldRoot, array_keys($linkedProduct[$executionID])))
                {
                    $this->dao->delete()->from(TABLE_PROJECTPRODUCT)->where('project')->eq($project)->andWhere('product')->eq($oldRoot)->exec();
                    $this->dao->update(TABLE_BUILD)->set('product')->eq($newRoot)->where('product')->eq($oldRoot)->andWhere('execution')->eq($executionID)->exec();
                }
            }
        }
    }

    /**
     * Delete a module.
     *
     * @param  int    $moduleID
     * @param  null   $null      compatible with that of model::delete()
     * @access public
     * @return void
     */
    public function delete($moduleID, $null = null)
    {
        $module = $this->getById($moduleID);
        if(empty($module)) return false;

        $childs = $this->getAllChildId($moduleID);
        $childs[$moduleID] = $moduleID;

        $objectType = (!empty($module->type) and strpos($this->config->tree->groupTypes, ",$module->type,") !== false) ? 'chartgroup' : 'module';
        /* Mark deletion when delete a module. */
        $this->dao->update(TABLE_MODULE)->set('deleted')->eq(1)->where('id')->in($childs)->exec();
        foreach($childs as $childID)
        {
            $this->loadModel('action')->create($objectType, $childID, 'deleted', '', $extra = ACTIONMODEL::CAN_UNDELETED);
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
            case 'doc':
                $this->dao->update(TABLE_DOC)->set('`module`')->eq($module->parent)->where('`module`')->in($childs)->exec();
                break;
        }
        if(strpos($this->session->{$module->type . 'List'}, 'param=' . $moduleID)) $this->session->set($module->type . 'List', str_replace('param=' . $moduleID, 'param=0', $this->session->{$module->type . 'List'}));
        if($cookieName) setcookie($cookieName, 0, time() - 3600, $this->config->webRoot, '', $this->config->cookieSecure, false);

        return true;
    }

    /**
     * Fix the path, grade fields according to the id and parent fields.
     *
     * @param  string    $root
     * @param  string    $type
     * @access public
     * @return void
     */
    public function fixModulePath($root, $type)
    {
        /* Get all modules grouped by parent. */
        if($type == 'bug' or $type == 'case') $type = 'story,' . $type;
        $groupModules = $this->dao->select('id, parent, branch')->from(TABLE_MODULE)->where('root')->eq($root)->andWhere('type')->in($type)->andWhere('deleted')->eq(0)->fetchGroup('parent', 'id');
        $modules = array();

        /* Cycle the groupModules until it has no item any more. */
        while(count($groupModules) > 0)
        {
            $oldCounts = count($groupModules);    // Record the counts before processing.
            foreach($groupModules as $parentModuleID => $childModules)
            {
                /* If the parentModule doesn't exsit in the modules, skip it. If exists, compute it's child modules. */
                if(!isset($modules[$parentModuleID]) and $parentModuleID != 0) continue;
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
                    $modules[$childModuleID] = $childModule;    // Save child module to modules, thus the child of child can compute it's grade and path.
                }
                unset($groupModules[$parentModuleID]);    // Remove it from the groupModules.
            }
            if(count($groupModules) == $oldCounts) break;   // If after processing, no module processed, break the cycle.
        }

        /* Save modules to database. */
        foreach($modules as $module) $this->dao->update(TABLE_MODULE)->data($module)->where('id')->eq($module->id)->limit(1)->exec();
    }

    /**
     * Check unique module name.
     *
     * @param  int    $rootID
     * @param  string $viewType
     * @param  int    $parentModuleID
     * @param  array  $modules
     * @param  array  $branches
     * @access public
     * @return bool
     */
    public function checkUnique($module, $modules = array(), $branches = array())
    {
        if(empty($branches)) $branches = $this->post->branch;
        if(empty($branches) and isset($module->branch)) $branches = array($module->branch);
        if(empty($branches)) $branches = array(0);
        if(empty($modules) and isset($module->name)) $modules = array($module->name);
        $branches       = array_unique($branches);
        $rootID         = $module->root;
        $viewType       = $module->type;
        $parentModuleID = $module->parent;

        if($this->isMergeModule($rootID, $viewType) and $viewType != 'task') $viewType .= ',story';

        $existsModules  = $this->dao->select('id,branch,name')->from(TABLE_MODULE)->where('root')->eq($rootID)->andWhere('type')->in($viewType)->andWhere('parent')->eq($parentModuleID)->andWhere('branch')->in($branches)->andWhere('deleted')->eq(0)->fetchAll();
        $checkedModules = ',';
        $repeatName     = '';
        foreach($modules as $id => $name)
        {
            $existed = false;
            if(strpos($id, 'id') === 0)
            {
                $existed  = true;
                $moduleID = substr($id, 2);
            }

            if(strpos($checkedModules, ",$name,") !== false)
            {
                $repeatName = $name;
                break;
            }

            foreach($existsModules as $existsModule)
            {
                if($name == $existsModule->name and (!$existed or $moduleID != $existsModule->id) and (!isset($branches[$id]) or $branches[$id] == $existsModule->branch))
                {
                    $repeatName = $name;
                    break 2;
                }
            }
            $checkedModules .= "$name,";
        }
        if($repeatName) return $repeatName;
        return false;
    }

    /**
     * Check merge module version.
     *
     * @param  int    $rootID
     * @param  string $viewType
     * @access public
     * @return bool
     */
    public function isMergeModule($rootID, $viewType)
    {
        if($viewType == 'bug' or $viewType == 'case' or $viewType == 'task')
        {
            /* Get createdVersion. */
            $table          = $viewType == 'task' ? TABLE_PROJECT : TABLE_PRODUCT;
            $versionField   = $viewType == 'task' ? 'openedVersion' : 'createdVersion';
            $createdVersion = $this->dao->select($versionField)->from($table)->where('id')->eq($rootID)->fetch($versionField);
            if($createdVersion)
            {
                if(is_numeric($createdVersion[0]) and version_compare($createdVersion, '4.1', '<=')) return false;
                return true;
            }
        }
        return false;
    }

    /**
     * Get full trees.
     *
     * @param  int        $rootID
     * @param  string     $viewType
     * @param  string|int $branchID
     * @param  int        $currentModuleID
     * @access public
     * @return array
     */
    public function getProductStructure($rootID, $viewType, $branchID = 'all', $currentModuleID = 0)
    {
        if($viewType == 'line') $rootID = 0;
        $stmt  = $this->app->dbQuery($this->buildMenuQuery($rootID, $viewType, $currentModuleID, $branchID));
        $trees = $this->getDataStructure($stmt, $viewType);

        return $trees;
    }

    /**
     * Get full modules tree
     * @param  object $stmt
     * @param  string $viewType
     * @param  array  $keepModules
     * @access public
     * @return array
     */
    public function getDataStructure($stmt, $viewType, $keepModules = array())
    {
        $parent = array();

        /* If feedback or ticket module is merge add story module.*/
        $syncConfig = $this->getSyncConfig($viewType);

        while($module = $stmt->fetch())
        {
            /* If is feedback or ticket filter story module by grade.*/
            if(($viewType == 'feedback' or $viewType == 'ticket') and $module->type == 'story')
            {
                if(isset($syncConfig[$module->root]) and $module->grade > $syncConfig[$module->root]) continue;
            }

            /* Ignore useless module for task. */
            $allModule = (isset($this->config->execution->task->allModule) and ($this->config->execution->task->allModule == 1));
            if($keepModules and !isset($keepModules[$module->id]) and !$allModule) continue;
            if($viewType == 'task' and empty($keepModules) and !$allModule) continue;
            if(isset($parent[$module->id]))
            {
                $module->children = $parent[$module->id]->children;
                unset($parent[$module->id]);
            }
            if(!isset($parent[$module->parent])) $parent[$module->parent] = new stdclass();
            $parent[$module->parent]->children[] = $module;
        }

        if($viewType == 'task') $parentTypePairs = $this->dao->select('*')->from(TABLE_MODULE)->where('id')->in(array_keys($parent))->andWhere('deleted')->eq(0)->fetchPairs('id', 'type');

        $tree = array();
        foreach($parent as $module)
        {
            foreach($module->children as $children)
            {
                if($viewType == 'task' and isset($parentTypePairs[$children->parent]) and $parentTypePairs[$children->parent] != 'task') continue;
                if($children->parent != 0) continue; // Filter project children modules.
                $tree[] = $children;
            }
        }
        return $tree;
    }

    /**
     * Get all doc structure.
     *
     * @access public
     * @return array
     */
    public function getDocStructure()
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
     * Get syncProduct module config.
     *
     * @param  string $type feedback|ticket
     * @access public
     * @return array
     */
    public function getSyncConfig($type = '')
    {
        /* If feedback or ticket module is merge add story module.*/
        $syncConfig = json_decode($this->config->global->syncProduct, true);
        $syncConfig = isset($syncConfig[$type]) ? $syncConfig[$type] : array();
        return $syncConfig;
    }

    /**
      * Load module language.
      *
      * @access public
      * @return void
      */
    public function setModuleLang()
    {
        $this->lang->module        = new stdclass();
        $this->lang->module->name  = $this->lang->tree->wordName;
        $this->lang->module->short = $this->lang->tree->short;
    }

    /**
     * Create module.
     *
     * @access public
     * @return bool|object
     */
    public function createModule()
    {
        $data = fixer::input('post')
            ->setDefault('name', '')
            ->setDefault('createType', 'child')
            ->setDefault('objectID', 0)
            ->setDefault('order', 10)
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
     * Get group pairs.
     *
     * @param  int    $dimensionID
     * @param  int    $parentGroup
     * @param  int    $grade
     * @param  string $type
     * @access public
     * @return array
     */
    public function getGroupPairs($dimensionID = 0, $parentGroup = 0, $grade = 2, $type = 'chart')
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
                if($grade == 2 and !empty($groups[2]))
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
