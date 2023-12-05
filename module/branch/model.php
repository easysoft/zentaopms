<?php
declare(strict_types=1);
/**
 * The model file of branch module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     branch
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class branchModel extends model
{
    /**
     * 通过分支ID获取分支信息。
     * Get name by id.
     *
     * @param  string              $branchID
     * @param  int                 $productID
     * @param  string              $field
     * @access public
     * @return object|string|false
     */
    public function getByID(string $branchID, int $productID = 0, string $field = 'name'): object|string|false
    {
        if($branchID == 'all') return false;
        if(empty($branchID))
        {
            if(empty($productID)) $productID = (int)$this->session->product;
            $product = $this->loadModel('product')->getByID($productID);

            if(empty($product) || !isset($this->lang->product->branchName[$product->type])) return false;
            return $this->lang->branch->main;
        }

        $branch = $this->dao->select('*')->from(TABLE_BRANCH)->where('id')->eq($branchID)->fetch();

        if(!$branch) return false;
        return $field ? htmlspecialchars_decode($branch->{$field}) : $branch;
    }

    /**
     * 获取分支列表。
     * Get branch list.
     *
     * @param  int    $productID
     * @param  int    $executionID
     * @param  string $browseType
     * @param  string $orderBy
     * @param  object $pager
     * @param  bool   $withMainBranch
     * @access public
     * @return array
     */
    public function getList(int $productID, int $executionID = 0, string $browseType = 'active', string $orderBy = 'order', object|null $pager = null, bool $withMainBranch = true): array
    {
        $product = $this->loadModel('product')->getById($productID);
        if(!$product) return array();

        $executionBranches = array();
        if($executionID)
        {
            $executionBranches = $this->branchTao->getIdListByRelation($productID, $executionID);
            if(empty($executionBranches)) return array();

            if(in_array(BRANCH_MAIN, $executionBranches)) $withMainBranch = true;
        }

        /* Get branch list. */
        $branchList = $this->dao->select('*')->from(TABLE_BRANCH)
            ->where('deleted')->eq(0)
            ->beginIF($productID)->andWhere('product')->eq($productID)->fi()
            ->beginIF($productID && $executionID)->andWhere('id')->in(array_keys($executionBranches))->fi()
            ->beginIF(!in_array($browseType, array('withClosed', 'all')))->andWhere('status')->eq($browseType)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
        if($browseType == 'closed') return $branchList;

        $defaultBranch = BRANCH_MAIN;
        foreach($branchList as $branch) $defaultBranch = $branch->default ? $branch->id : $defaultBranch;

        if(!$withMainBranch) return $branchList;

        /* Display the main branch under all and active page. */
        $mainBranch = new stdclass();
        $mainBranch->id          = BRANCH_MAIN;
        $mainBranch->product     = $productID;
        $mainBranch->name        = $this->lang->branch->main;
        $mainBranch->default     = $defaultBranch ? 0 : 1;
        $mainBranch->status      = 'active';
        $mainBranch->createdDate = '';
        $mainBranch->closedDate  = '';
        $mainBranch->desc        = sprintf($this->lang->branch->mainBranch, $this->lang->product->branchName[$product->type]);
        $mainBranch->order       = 0;

        return array($mainBranch) + $branchList;
    }

    /**
     * 根据产品ID获取分支状态信息。
     * Get branch status information based on product ID.
     *
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getStatusList(int $productID): array
    {
        return $this->dao->select('id,status')->from(TABLE_BRANCH)->where('product')->eq($productID)->fetchPairs();
    }

    /**
     * 根据产品ID获取分支列表键值对。
     * Get branch pairs by product id.
     *
     * @param  int    $productID
     * @param  string $params active|noempty|all|withClosed
     * @param  int    $executionID
     * @param  string $mergedBranches
     * @access public
     * @return array
     */
    public function getPairs(int $productID, string $params = '', int $executionID = 0, string $mergedBranches = ''): array
    {
        $executionBranches = array();
        if($executionID)
        {
            $executionBranches = $this->branchTao->getIdListByRelation($productID, $executionID);
            if(empty($executionBranches)) return array();
        }

        $branches = $this->dao->select('id, name')->from(TABLE_BRANCH)
            ->where('deleted')->eq(0)
            ->beginIF($productID)->andWhere('product')->eq($productID)->fi()
            ->beginIF($productID && $executionID)->andWhere('id')->in(array_keys($executionBranches))->fi()
            ->beginIF(strpos($params, 'active') !== false)->andWhere('status')->eq('active')->fi()
            ->beginIF(!empty($mergedBranches))->andWhere('id')->notIN($mergedBranches)->fi()
            ->orderBy('`order`')
            ->fetchPairs('id', 'name');
        foreach($branches as $branchID => $branchName) $branches[$branchID] = htmlspecialchars_decode($branchName);

        if($executionID)
        {
            $branches = array('all' => $this->lang->branch->all, '0' => $this->lang->branch->main) + $branches;
            return $branches;
        }

        if(strpos($params, 'noempty') === false)
        {
            $product = $this->loadModel('product')->getById($productID);
            if(($productID && !$product) || ($product && $product->type == 'normal')) return array();
            $branches = array(BRANCH_MAIN => $this->lang->branch->main) + $branches;
        }

        if(strpos($params, 'all') !== false)
        {
            $branches = array('all' => $this->lang->branch->all) + $branches;
        }

        if(strpos($params, 'withClosed') !== false)
        {
            $closedBranches = $this->dao->select('id')->from(TABLE_BRANCH)->where('product')->eq($productID)->andWhere('status')->eq('closed')->fetchPairs();

            if(!empty($closedBranches))
            {
                foreach($closedBranches as $closedBranch) $branches[$closedBranch] .= ' (' . $this->lang->branch->statusList['closed'] . ')';
            }
        }
        return $branches;
    }

    /**
     * 通过分支ID列表获取分支名称列表。
     * Get the list of branch name from the branch ID list.
     *
     * @param  array  $branchIdList
     * @access public
     * @return array
     */
    public function getPairsByIdList(array $branchIdList): array
    {
        return $this->dao->select('id,name')->from(TABLE_BRANCH)->where('id')->in($branchIdList)->fetchPairs();
    }

    /**
     * 获取系统内所有分支id:name的键值对。
     * Get the key-value pairs of id:name for all branches in the system.
     *
     * @param  string $params
     * @access public
     * @return array
     */
    public function getAllPairs(string $params = ''): array
    {
        $branchGroups = $this->dao->select('*')->from(TABLE_BRANCH)->where('deleted')->eq(0)->orderBy('product,`order`')->fetchGroup('product', 'id');
        $products     = $this->loadModel('product')->getByIdList(array_keys($branchGroups));

        $branchPairs = array();
        foreach($branchGroups as $productID => $branches)
        {
            if(empty($products[$productID])) continue;

            $product = $products[$productID];
            foreach($branches as $branch)
            {
                $branchPairs[$branch->id] = $product->name . '/' . htmlspecialchars_decode($branch->name);
            }
        }

        if(strpos($params, 'noempty') === false) $branchPairs = array('0' => $this->lang->branch->main) + $branchPairs;
        return $branchPairs;
    }

    /**
     * Create a branch.
     *
     * @param  int       $productID
     * @param  object    $branch
     * @access public
     * @return int|false
     */
    public function create(int $productID, object $branch): int|false
    {
        $this->app->loadLang('product');
        $productType = $this->dao->select('`type`')->from(TABLE_PRODUCT)->where('id')->eq($productID)->fetch('type');
        $this->lang->error->unique = str_replace('@branch@', $this->lang->product->branchName[$productType], $this->lang->branch->existName);

        $lastOrder = (int)$this->dao->select('`order`')->from(TABLE_BRANCH)->where('product')->eq($productID)->orderBy('order_desc')->limit(1)->fetch('order');
        $branch->order   = $lastOrder + 1;
        $branch->product = $productID;
        $this->dao->insert(TABLE_BRANCH)->data($branch)
            ->batchCheck($this->config->branch->create->requiredFields, 'notempty')
            ->checkIF(!empty($branch->name), 'name', 'unique', "product = $productID")
            ->exec();

        if(dao::isError()) return false;
        return $this->dao->lastInsertID();
    }

    /**
     * 更新一个分支。
     * Update a branch.
     *
     * @param  int         $branchID
     * @param  object      $branch
     * @access public
     * @return array|false
     */
    public function update(int $branchID, object $branch): array|false
    {
        $oldBranch   = $this->getById((string)$branchID, 0, '');
        $productType = $this->getProductType($branchID);
        if(!$productType) $productType = 'branch';

        $this->app->loadLang('product');
        $this->lang->error->unique = str_replace('@branch@', $this->lang->product->branchName[$productType], $this->lang->branch->existName);

        $branch->closedDate = $branch->status == 'closed' ? helper::today() : null;
        $this->dao->update(TABLE_BRANCH)->data($branch)
            ->where('id')->eq($branchID)
            ->batchCheck($this->config->branch->edit->requiredFields, 'notempty')
            ->checkIF(!empty($branch->name)&& $branch->name != $oldBranch->name, 'name', 'unique', "product = $oldBranch->product")
            ->exec();
        if(dao::isError()) return false;

        $changes = common::createChanges($oldBranch, $branch);
        if($changes) $this->loadModel('action')->create('branch', $branchID, 'Edited');
        return $changes;
    }

    /**
     * 批量更新分支。
     * Batch update branch.
     *
     * @param  int         $productID
     * @param  array       $productID
     * @access public
     * @return array|false
     */
    public function batchUpdate(int $productID, array $branches): array|false
    {
        $this->app->loadLang('product');
        $productType = $this->dao->select('`type`')->from(TABLE_PRODUCT)->where('id')->eq($productID)->fetch('type');
        $this->lang->error->unique = str_replace('@branch@', $this->lang->product->branchName[$productType], $this->lang->branch->existName);

        $oldBranchList = $this->getList($productID, 0, 'all');
        foreach($branches as $index => $branch)
        {
            $branchID = $branch->branchID;
            if($branch->branchID == BRANCH_MAIN)
            {
                $newMainBranch = new stdClass();
                $newMainBranch->default = (isset($branch->default) and $branch->default == BRANCH_MAIN) ? 1 : 0;

                $changes[$branchID] = common::createChanges($oldBranchList[BRANCH_MAIN], $newMainBranch);
            }
            else
            {
                $newBranch = new stdclass();
                $newBranch->name       = $branch->name;
                $newBranch->desc       = $branch->desc;
                $newBranch->status     = $branch->status;
                $newBranch->default    = (isset($branch->default) && $branchID == $branch->default) ? 1 : 0;
                $newBranch->closedDate = $branch->status == 'closed' ? helper::today() : null;

                $this->dao->update(TABLE_BRANCH)->data($newBranch)
                    ->batchCheck($this->config->branch->create->requiredFields, 'notempty')
                    ->checkIF(!empty($branch->name) && $branch->name != $oldBranchList[$branchID]->name, 'name', 'unique', "product = $productID")
                    ->where('id')->eq($branchID)
                    ->exec();

                if(dao::isError()) dao::$errors[] = 'branch#' . ($index + 1) . dao::getError(true);

                $changes[$branchID] = common::createChanges($oldBranchList[$branchID], $branch);
            }
        }

        if(dao::isError()) return false;

        if(isset($branch->default)) $this->setDefault($productID, $branch->default);
        return $changes;
    }

    /**
     * 关闭一个分支。
     * Close a branch.
     *
     * @param  int    $branchID
     * @access public
     * @return bool
     */
    public function close(int $branchID): bool
    {
        $this->dao->update(TABLE_BRANCH)
            ->set('status')->eq('closed')
            ->set('closedDate')->eq(helper::today())
            ->set('`default`')->eq('0')
            ->where('id')->eq($branchID)
            ->exec();
        return !dao::isError();
    }

    /**
     * 激活一个分支。
     * Activate a branch.
     *
     * @param  int    $branchID
     * @access public
     * @return bool
     */
    public function activate(int $branchID): bool
    {
        $this->dao->update(TABLE_BRANCH)
            ->set('status')->eq('active')
            ->set('closedDate')->eq(null)
            ->where('id')->eq($branchID)
            ->exec();
        return !dao::isError();
    }

    /**
     * Manage branch.
     *
     * @param  int    $productID
     * @access public
     * @return bool|array
     */
    public function manage($productID)
    {
        $oldBranches = $this->getPairs($productID, 'noempty');
        $data        = fixer::input('post')->get();

        if(isset($data->branch))
        {
            foreach($data->branch as $branchID => $branch)
            {
                if(!$branch) return print(js::alert($this->lang->branch->nameNotEmpty));
                if($oldBranches[$branchID] != $branch) $this->dao->update(TABLE_BRANCH)->set('name')->eq($branch)->where('id')->eq($branchID)->exec();
            }
        }

        $branches = array();
        foreach($data->newbranch as $i => $branch)
        {
            if(empty($branch)) continue;
            $this->dao->insert(TABLE_BRANCH)->set('name')->eq($branch)->set('product')->eq($productID)->set('`order`')->eq(count($data->branch) + $i + 1)->exec();
            $branches[] = $this->dao->lastInsertId();
        }

        if(dao::isError()) return false;
        return $branches;
    }

    /**
     * 将产品改为正常类型时，解除分支与项目的关联。
     * Unlink branches for projects when product type is normal.
     *
     * @param  array   $productIDList
     * @access public
     * @return bool
     */
    public function unlinkBranch4Project(array $productIDList): bool
    {
        $productLinkedProject = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)
            ->where('product')->in($productIDList)
            ->andWhere('branch')->gt(0)
            ->fetchGroup('product', 'project');

        $this->dao->delete()->from(TABLE_PROJECTPRODUCT)
            ->where('product')->in($productIDList)
            ->andWhere('branch')->gt(0)
            ->exec();

        foreach($productLinkedProject as $productID => $projectList)
        {
            foreach($projectList as $projectID => $project)
            {
                $data = new stdClass();
                $data->product = $productID;
                $data->project = $projectID;
                $data->branch  = 0;
                $data->plan    = 0;

                $this->dao->replace(TABLE_PROJECTPRODUCT)->data($data)->exec();
            }
        }

        return !dao::isError();
    }

    /**
     * 产品改为多分支类型时，处理分支关联项目。
     * Link branches for projects when product type is not normal.
     *
     * @param  int|array $productID
     * @access public
     * @return void
     */
    public function linkBranch4Project(int|array $productID): void
    {
        if(is_array($productID))
        {
            foreach($productID as $id) $this->linkBranch4Project($id);
            return;
        }

        /* Get the branch of story, bug and case linked the project and execution. */
        $linkedBranchProject = array();
        $objectList          = array('projectstory', 'bug', 'case');
        foreach($objectList as $objectType)
        {
            $table               = $this->config->objectTables[$objectType];
            $linkedBranchProject = $this->dao->select('project,branch')->from($table)
                ->where('product')->eq($productID)
                ->andWhere('branch')->gt(0)
                ->beginIF($objectType != 'projectstory')->andWhere('project')->ne('0')->fi()
                ->fetchGroup('project', 'branch');
            foreach($linkedBranchProject as $projectID => $branchList)
            {
                foreach($branchList as $branchID => $branch) $linkedBranchProject[$projectID][$branchID] = $branchID;
            }

            if($objectType == 'projectstory') continue;

            $linkedBranchExecution = $this->dao->select('execution,branch')->from($table)
                ->where('product')->eq($productID)
                ->andWhere('branch')->gt(0)
                ->andWhere('execution')->ne(0)
                ->fetchGroup('execution', 'branch');
            foreach($linkedBranchExecution as $executionID => $branchList)
            {
                foreach($branchList as $branchID => $branch) $linkedBranchProject[$executionID][$branchID] = $branchID;
            }
        }

        foreach($linkedBranchProject as $projectID => $branchList)
        {
            foreach($branchList as $branchID)
            {
                $data = new stdClass();
                $data->product = $productID;
                $data->project = $projectID;
                $data->branch  = $branchID;
                $data->plan    = 0;

                $this->dao->replace(TABLE_PROJECTPRODUCT)->data($data)->exec();
            }
        }
    }

    /**
     * 按照产品分组获取分支数据。
     * Get branch group by products
     *
     * @param  array  $productIdList
     * @param  string $params        ignoreNormal|noempty|noclosed
     * @param  array  $appendBranch
     * @access public
     * @return array
     */
    public function getByProducts(array $productIdList, string $params = '', array $appendBranch = array()): array
    {
        $branches = $this->dao->select('*')->from(TABLE_BRANCH)
            ->where('product')->in($productIdList)
            ->andWhere('deleted')->eq(0)
            ->beginIF(strpos($params, 'noclosed') !== false)->andWhere('status')->eq('active')->fi()
            ->orderBy('`order`')
            ->fetchAll('id');

        if(!empty($appendBranch)) $branches += $this->dao->select('*')->from(TABLE_BRANCH)->where('id')->in($appendBranch)->orderBy('`order`')->fetchAll('id');
        $products = $this->loadModel('product')->getByIdList($productIdList);

        $branchGroups = array();
        foreach($branches as $branch)
        {
            if(!isset($products[$branch->product]->type)) continue;
            if($products[$branch->product]->type == 'normal')
            {
                if(strpos($params, 'ignoreNormal') === false) $branchGroups[$branch->product][0] = '';
            }
            else
            {
                $branchGroups[$branch->product][$branch->id] = htmlspecialchars_decode($branch->name);
            }
        }

        foreach($products as $product)
        {
            if($product->type == 'normal') continue;

            if(!isset($branchGroups[$product->id]))  $branchGroups[$product->id] = array();
            if(strpos($params, 'noempty') === false) $branchGroups[$product->id] = array('0' => $this->lang->branch->main) + $branchGroups[$product->id];
        }
        return $branchGroups;
    }

    /**
     * 根据分支ID获取产品类型。
     * Get product type by branch ID.
     *
     * @param  int          $branchID
     * @access public
     * @return string|false
     */
    public function getProductType(int $branchID): string|false
    {
        return $this->dao->select('t2.type')->from(TABLE_BRANCH)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.id')->eq($branchID)
            ->fetch('type');
    }

    /**
     * 分支排序。
     * Sort branch.
     *
     * @param  object $data
     * @access public
     * @return bool
     */
    public function sort(object $data): bool
    {
        $orderBy      = $data->orderBy;
        $branchIDList = explode(',', trim($data->branches, ','));

        if(strpos($orderBy, 'order') === false) return false;
        if(in_array(BRANCH_MAIN, $branchIDList)) unset($branchIDList[array_search(BRANCH_MAIN, $branchIDList)]);

        $branches = $this->dao->select('id,`order`')->from(TABLE_BRANCH)->where('id')->in($branchIDList)->orderBy($orderBy)->fetchPairs('order', 'id');
        foreach($branches as $order => $id)
        {
            $newID = array_shift($branchIDList);
            if($id == $newID) continue;
            $this->dao->update(TABLE_BRANCH)->set('`order`')->eq($order)->where('id')->eq($newID)->exec();
        }

        return !dao::isError();
    }

    /**
     * 检查分支数据。
     * Check branch data.
     *
     * @param  int    $branchID
     * @access public
     * @return bool
     */
    public function checkBranchData(int $branchID): bool
    {
        $modules = array('module', 'story', 'productplan', 'bug', 'case', 'release', 'build');
        foreach($modules as $module)
        {
            $firstData = $this->dao->select('id')->from($this->config->objectTables[$module])
                ->where('branch')->eq($branchID)
                ->andWhere('deleted')->eq(0)
                ->fetch();

            if($firstData) return false;
        }

        $project = $this->dao->select('t1.id')->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id=t2.project')
            ->where('t2.branch')->eq($branchID)
            ->andWhere('t1.deleted')->eq(0)
            ->limit(1)
            ->fetch();
        return empty($project);
    }

    /**
     * 设置默认分支。
     * Set default branch.
     *
     * @param   int    $productID
     * @param   int    $branchID
     * @accesss public
     * @return  bool
     */
    public function setDefault(int $productID, int $branchID): bool
    {
        $this->dao->update(TABLE_BRANCH)->set('`default`')->eq('0')
            ->where('product')->eq($productID)
            ->exec();

        if($branchID) $this->dao->update(TABLE_BRANCH)->set('`default`')->eq('1')->where('id')->eq($branchID)->exec();
        return !dao::isError();
    }

    /**
     * 获取关联到项目的产品分支。
     * Get branches of product which linked project.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getPairsByProjectProduct(int $projectID, int $productID): array
    {
        $branches = $this->dao->select('branch,t2.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_BRANCH)->alias('t2')->on('t1.branch=t2.id')
            ->where('t1.project')->eq($projectID)
            ->andWhere('t1.product')->eq($productID)
            ->andWhere('t2.deleted')->eq('0')
            ->fetchPairs('branch', 'name');

        $projectProduct = $this->branchTao->getIdListByRelation($productID, $projectID);
        if(isset($projectProduct[BRANCH_MAIN])) $branches = array(BRANCH_MAIN => $this->lang->branch->main) + $branches;

        return $branches;
    }

    /**
     * 判断是否展示分支标签。
     * Display of branch label.
     *
     * @param  int    $productID
     * @param  int    $moduleID
     * @param  int    $executionID
     * @access public
     * @return bool
     */
    public function showBranch(int $productID, int $moduleID = 0, int $executionID = 0): bool
    {
        $this->loadModel('product');
        if(!$productID)
        {
            if($moduleID)
            {
                $module = $this->loadModel('tree')->getByID($moduleID);
                if($module && $module->type != 'task') $productID = $module->root;
            }
            else if($executionID && $this->app->tab != 'project')
            {
                $productPairs = $this->product->getProductPairsByProject($executionID);
                if(count($productPairs) == 1) $productID = key($productPairs);
            }
        }
        if(!$productID) return false;

        $product = $this->product->getByID($productID);
        if(!$product || $product->type == 'normal') return false;

        $this->app->loadLang('datatable');
        $this->lang->datatable->showBranch = sprintf($this->lang->datatable->showBranch, $this->lang->product->branchName[$product->type]);
        return true;
    }

    /**
     * 设置分支/平台名称。
     * Set branch/platform name.
     *
     * @param  int    $productID
     * @access public
     * @return bool
     */
    public function changeBranchLanguage(int $productID): bool
    {
        $product = $this->loadModel('product')->getByID($productID);
        if(!$product || $product->type == 'normal') return false;

        $productType = $product->type;

        $this->lang->branch->create      = sprintf($this->lang->branch->create, $this->lang->product->branchName[$productType]);
        $this->lang->branch->edit        = sprintf($this->lang->branch->edit, $this->lang->product->branchName[$productType]);
        $this->lang->branch->name        = sprintf($this->lang->branch->name, $this->lang->product->branchName[$productType]);
        $this->lang->branch->desc        = sprintf($this->lang->branch->desc, $this->lang->product->branchName[$productType]);
        $this->lang->branch->manageTitle = sprintf($this->lang->branch->manageTitle, $this->lang->product->branchName[$productType]);
        $this->lang->branch->mainBranch  = sprintf($this->lang->branch->mainBranch, $this->lang->product->branchName[$productType]);

        $this->lang->branch->mergeTo           = str_replace('@branch@', $this->lang->product->branchName[$productType], $this->lang->branch->mergeTo);
        $this->lang->branch->mergeBranch       = str_replace('@branch@', $this->lang->product->branchName[$productType], $this->lang->branch->mergeBranch);
        $this->lang->branch->confirmDelete     = str_replace('@branch@', $this->lang->product->branchName[$productType], $this->lang->branch->confirmDelete);
        $this->lang->branch->confirmSetDefault = str_replace('@branch@', $this->lang->product->branchName[$productType], $this->lang->branch->confirmSetDefault);
        $this->lang->branch->canNotDelete      = str_replace('@branch@', $this->lang->product->branchName[$productType], $this->lang->branch->canNotDelete);
        $this->lang->branch->confirmClose      = str_replace('@branch@', $this->lang->product->branchName[$productType], $this->lang->branch->confirmClose);
        $this->lang->branch->confirmActivate   = str_replace('@branch@', $this->lang->product->branchName[$productType], $this->lang->branch->confirmActivate);
        $this->lang->branch->existName         = str_replace('@branch@', $this->lang->product->branchName[$productType], $this->lang->branch->existName);
        $this->lang->branch->mergeTips         = str_replace('@branch@', $this->lang->product->branchName[$productType], $this->lang->branch->mergeTips);
        $this->lang->branch->targetBranchTips  = str_replace('@branch@', $this->lang->product->branchName[$productType], $this->lang->branch->targetBranchTips);
        return true;
    }

    /**
     * 将多个分支合并到一个分支。
     * Merge multiple branches into one branch.
     *
     * @param  int      $productID
     * @param  string   $mergedBranches
     * @param  object   $data
     * @access public
     * @return int|bool
     */
    public function mergeBranch(int $productID, string $mergedBranches, object $data): int|bool
    {
        if($data->createBranch && empty($data->name))
        {
            $this->changeBranchLanguage($productID);
            dao::$errors['name'] = sprintf($this->lang->error->notempty, $this->lang->branch->name);
            return false;
        }

        /* Get the target branch. */
        if($data->createBranch)
        {
            $branch = new stdclass();
            foreach($this->config->branch->form->create as $field => $setting) $branch->$field = $data->$field;

            $targetBranch = $this->create($productID, $branch);
            $this->loadModel('action')->create('branch', $targetBranch, 'Opened');
        }
        else
        {
            $targetBranch = $data->targetBranch;
        }

        /* Branch. */
        $this->dao->delete()->from(TABLE_BRANCH)->where('id')->in($mergedBranches)->exec();

        $this->branchTao->afterMerge($productID, $targetBranch, $mergedBranches, $data);
        if(dao::isError()) return false;

        return $targetBranch;
    }

    /**
     * 获取分支的可操作动作。
     * Judge an action is clickable or not.
     *
     * @param object $branch
     * @param string $action
     * @static
     * @access public
     * @return bool
     */
    public static function isClickable($branch, $action)
    {
        if(empty($branch->id)) return false;
        if($branch->status == 'active' && $action == 'activate') return false;
        if($branch->status == 'closed' && $action == 'close')    return false;

        return true;
    }
}
