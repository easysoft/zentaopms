<?php
declare(strict_types=1);
/**
 * The model file of branch module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     branch
 * @version     $Id$
 * @link        http://www.zentao.net
 */
class branchModel extends model
{
    /**
     * Get name by id.
     *
     * @param  int    $branchID
     * @param  int    $productID
     * @param  string $field
     * @access public
     * @return string|array
     */
    public function getById($branchID, $productID = 0, $field = 'name')
    {
        if($branchID == 'all') return false;
        if(empty($branchID))
        {
            if(empty($productID)) $productID = $this->session->product;
            $product = $this->loadModel('product')->getById($productID);
            if(empty($product) or !isset($this->lang->product->branchName[$product->type])) return false;
            return $this->lang->branch->main;
        }

        if(empty($field)) return $this->dao->select('*')->from(TABLE_BRANCH)->where('id')->eq($branchID)->fetch();

        return htmlspecialchars_decode($this->dao->select('*')->from(TABLE_BRANCH)->where('id')->eq($branchID)->fetch($field));
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

        array_unshift($branchList, $mainBranch);
        return $branchList;
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
        if(!$productID) $productID = 0;

        $executionBranches = array();
        if($executionID)
        {
            $executionBranches = $this->branchTao->getIdListByRelation($productID, $executionID);
            if(empty($executionBranches)) return array();
        }

        $branches = $this->dao->select('*')->from(TABLE_BRANCH)
            ->where('deleted')->eq(0)
            ->beginIF($productID)->andWhere('product')->eq($productID)->fi()
            ->beginIF($productID and $executionID)->andWhere('id')->in(array_keys($executionBranches))->fi()
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
            $branches = array('0' => $this->lang->branch->main) + $branches;
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
     * Get all pairs.
     *
     * @param  string $params
     * @access public
     * @return array
     */
    public function getAllPairs($params = '')
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
     * @param  int    $productID
     * @param  bool   $withMerge
     * @access public
     * @return int|bool
     */
    public function create($productID, $withMerge = false)
    {
        $branch = fixer::input('post')
            ->add('product', $productID)
            ->add('createdDate', helper::today())
            ->add('status', 'active')
            ->removeIF($withMerge, 'createBranch,mergedBranchIDList,targetBranch')
            ->get();

        $lastOrder = (int)$this->dao->select('`order`')->from(TABLE_BRANCH)->where('product')->eq($productID)->orderBy('order_desc')->limit(1)->fetch('order');
        $branch->order = empty($lastOrder) ? 1 : $lastOrder + 1;

        $this->app->loadLang('product');
        $productType = $this->dao->select('`type`')->from(TABLE_PRODUCT)->where('id')->eq($productID)->fetch('type');
        $this->lang->error->unique = str_replace('@branch@', $this->lang->product->branchName[$productType], $this->lang->branch->existName);

        $this->dao->insert(TABLE_BRANCH)->data($branch)
            ->batchCheck($this->config->branch->create->requiredFields, 'notempty')
            ->checkIF(!empty($branch->name), 'name', 'unique', "product = $productID")
            ->exec();

        if(!dao::isError()) return $this->dao->lastInsertID();
        return false;
    }

    /**
     * Update branch.
     *
     * @param  int    $branchID
     * @access public
     * @return array|bool
     */
    public function update($branchID)
    {
        $oldBranch = $this->getById($branchID, 0, '');

        $newBranch = fixer::input('post')->get();
        $newBranch->closedDate = $newBranch->status == 'closed' ? helper::today() : null;

        $this->app->loadLang('product');
        $productType = $this->getProductType($branchID);
        $this->lang->error->unique = str_replace('@branch@', $this->lang->product->branchName[$productType], $this->lang->branch->existName);

        $this->dao->update(TABLE_BRANCH)->data($newBranch)
            ->where('id')->eq($branchID)
            ->batchCheck($this->config->branch->edit->requiredFields, 'notempty')
            ->checkIF(!empty($newBranch->name) and $newBranch->name != $oldBranch->name, 'name', 'unique', "product = $oldBranch->product")
            ->exec();

        if(!dao::isError()) return common::createChanges($oldBranch, $newBranch);
        return false;
    }

    /**
     * 批量更新分支。
     * Batch update branch.
     *
     * @param  int         $productID
     * @access public
     * @return array|false
     */
    public function batchUpdate(int $productID): array|false
    {
        $branches = form::batchData($this->config->branch->form->batchedit)->get();
        $oldBranchList = $this->getList($productID, 0, 'all');

        $this->app->loadLang('product');
        $productType = $this->dao->select('`type`')->from(TABLE_PRODUCT)->where('id')->eq($productID)->fetch('type');
        $this->lang->error->unique = str_replace('@branch@', $this->lang->product->branchName[$productType], $this->lang->branch->existName);

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
                $newBranch->default    = (isset($branch->default) and $branchID == $branch->default) ? 1 : 0;
                $newBranch->closedDate = $branch->status == 'closed' ? helper::today() : null;

                $this->dao->update(TABLE_BRANCH)->data($newBranch)
                    ->batchCheck($this->config->branch->create->requiredFields, 'notempty')
                    ->checkIF(!empty($branch->name) and $branch->name != $oldBranchList[$branchID]->name, 'name', 'unique', "product = $productID")
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
     * Unlink branches for projects when product type is normal.
     *
     * @param  int|array $productIDList
     * @access public
     * @return void
     */
    public function unlinkBranch4Project($productIDList)
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
    }

    /**
     * Link branches for projects when product type is not normal.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function linkBranch4Project($productID)
    {
        if(is_array($productID))
        {
            foreach($productID as $id) $this->linkBranch4Project($id);
            return;
        }

        $linkedBranchProject = array();

        $storyLinkedBranchProject = $this->dao->select('project,branch')->from(TABLE_PROJECTSTORY)
            ->where('product')->eq($productID)
            ->andWhere('branch')->gt(0)
            ->fetchGroup('project', 'branch');
        foreach($storyLinkedBranchProject as $projectID => $branchList)
        {
            foreach($branchList as $branchID => $branch) $linkedBranchProject[$projectID][$branchID] = $branchID;
        }

        $bugLinkedBranchProject = $this->dao->select('project,branch')->from(TABLE_BUG)
            ->where('product')->eq($productID)
            ->andWhere('branch')->gt(0)
            ->andWhere('project')->ne(0)
            ->fetchGroup('project', 'branch');
        foreach($bugLinkedBranchProject as $projectID => $branchList)
        {
            foreach($branchList as $branchID => $branch) $linkedBranchProject[$projectID][$branchID] = $branchID;
        }

        $bugLinkedBranchExecution = $this->dao->select('execution,branch')->from(TABLE_BUG)
            ->where('product')->eq($productID)
            ->andWhere('branch')->gt(0)
            ->andWhere('execution')->ne(0)
            ->fetchGroup('execution', 'branch');
        foreach($bugLinkedBranchExecution as $executionID => $branchList)
        {
            foreach($branchList as $branchID => $branch) $linkedBranchProject[$executionID][$branchID] = $branchID;
        }

        $caseLinkedBranchProject = $this->dao->select('project,branch')->from(TABLE_CASE)
            ->where('product')->eq($productID)
            ->andWhere('branch')->gt(0)
            ->andWhere('project')->ne(0)
            ->fetchGroup('project', 'branch');
        foreach($caseLinkedBranchProject as $projectID => $branchList)
        {
            foreach($branchList as $branchID => $branch) $linkedBranchProject[$projectID][$branchID] = $branchID;
        }

        $caseLinkedBranchExecution = $this->dao->select('execution,branch')->from(TABLE_CASE)
            ->where('product')->eq($productID)
            ->andWhere('branch')->gt(0)
            ->andWhere('execution')->ne(0)
            ->fetchGroup('execution', 'branch');
        foreach($caseLinkedBranchExecution as $executionID => $branchList)
        {
            foreach($branchList as $branchID => $branch) $linkedBranchProject[$executionID][$branchID] = $branchID;
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
     * Get branch group by products
     *
     * @param  array  $products
     * @param  string $params
     * @param  array  $appendBranch
     * @access public
     * @return array
     */
    public function getByProducts($products, $params = '', $appendBranch = '')
    {
        $branches = $this->dao->select('*')->from(TABLE_BRANCH)
            ->where('product')->in($products)
            ->andWhere('deleted')->eq(0)
            ->beginIF(strpos($params, 'noclosed') !== false)->andWhere('status')->eq('active')->fi()
            ->orderBy('`order`')
            ->fetchAll('id');

        if(!empty($appendBranch)) $branches += $this->dao->select('*')->from(TABLE_BRANCH)->where('id')->in($appendBranch)->orderBy('`order`')->fetchAll('id');
        $products = $this->loadModel('product')->getByIdList($products);

        $branchGroups = array();
        foreach($branches as $branch)
        {
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
     * Get product bype by branch.
     *
     * @param  int    $branchID
     * @access public
     * @return void
     */
    public function getProductType($branchID)
    {
        return $this->dao->select('t2.type')->from(TABLE_BRANCH)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.id')->eq($branchID)
            ->fetch('type');
    }

    /**
     * Sort branch.
     *
     * @access public
     * @return void
     */
    public function sort()
    {
        $orderBy      = $this->post->orderBy;
        $branchIDList = explode(',', trim($this->post->branches, ','));

        if(strpos($orderBy, 'order') === false) return false;
        if(in_array(BRANCH_MAIN, $branchIDList)) unset($branchIDList[array_search(BRANCH_MAIN, $branchIDList)]);

        $branches = $this->dao->select('id,`order`')->from(TABLE_BRANCH)->where('id')->in($branchIDList)->orderBy($orderBy)->fetchPairs('order', 'id');
        foreach($branches as $order => $id)
        {
            $newID = array_shift($branchIDList);
            if($id == $newID) continue;
            $this->dao->update(TABLE_BRANCH)->set('`order`')->eq($order)->where('id')->eq($newID)->exec();
        }
    }

    /**
     * Check branch data.
     *
     * @param  int    $branchID
     * @access public
     * @return bool
     */
    public function checkBranchData($branchID)
    {
        $module  = $this->dao->select('id')->from(TABLE_MODULE)->where('branch')->eq($branchID)->andWhere('deleted')->eq(0)->limit(1)->fetch();
        $story   = $this->dao->select('id')->from(TABLE_STORY)->where('branch')->eq($branchID)->andWhere('deleted')->eq(0)->limit(1)->fetch();
        $plan    = $this->dao->select('id')->from(TABLE_PRODUCTPLAN)->where('branch')->eq($branchID)->andWhere('deleted')->eq(0)->limit(1)->fetch();
        $bug     = $this->dao->select('id')->from(TABLE_BUG)->where('branch')->eq($branchID)->andWhere('deleted')->eq(0)->limit(1)->fetch();
        $case    = $this->dao->select('id')->from(TABLE_CASE)->where('branch')->eq($branchID)->andWhere('deleted')->eq(0)->limit(1)->fetch();
        $release = $this->dao->select('id')->from(TABLE_RELEASE)->where('branch')->eq($branchID)->andWhere('deleted')->eq(0)->limit(1)->fetch();
        $build   = $this->dao->select('id')->from(TABLE_BUILD)->where('branch')->eq($branchID)->andWhere('deleted')->eq(0)->limit(1)->fetch();
        $project = $this->dao->select('t1.id')->from(TABLE_PROJECT)->alias('t1')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id=t2.project')
            ->where('t2.branch')->eq($branchID)
            ->andWhere('t1.deleted')->eq(0)
            ->limit(1)
            ->fetch();
        return empty($module) && empty($story) && empty($bug) && empty($case) && empty($release) && empty($build) && empty($plan) && empty($project);
    }

    /**
     * Setting parameters for link.
     *
     * @param  string $module
     * @param  string $link
     * @param  int    $projectID
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function setParamsForLink($module, $link, $projectID, $productID)
    {
        $linkHtml = strpos('programplan', $module) !== false ? sprintf($link, $projectID, $productID, '{id}') : sprintf($link, $productID, '{id}');
        return $linkHtml;
    }

    /**
     * Set default branch.
     *
     * @param   int    $productID
     * @param   int    $branchID
     * @accesss public
     * @return  void
     */
    public function setDefault($productID, $branchID)
    {
        $this->dao->update(TABLE_BRANCH)->set('`default`')->eq('0')
            ->where('product')->eq($productID)
            ->exec();

        if($branchID) $this->dao->update(TABLE_BRANCH)->set('`default`')->eq('1')->where('id')->eq($branchID)->exec();
    }

    /**
     * Get branches of product which linked project.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getPairsByProjectProduct($projectID, $productID)
    {
        $branches = $this->dao->select('branch,t2.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_BRANCH)->alias('t2')->on('t1.branch=t2.id')
            ->where('t1.project')->eq($projectID)
            ->andWhere('t1.product')->eq($productID)
            ->andWhere('t2.deleted')->eq('0')
            ->fetchPairs('branch', 'name');

        $projectProduct = $this->dao->select('*')->from(TABLE_PROJECTPRODUCT)->where('project')->eq($projectID)->andWhere('product')->eq($productID)->fetchAll('branch');

        if(isset($projectProduct[BRANCH_MAIN])) $branches = array(BRANCH_MAIN => $this->lang->branch->main) + $branches;

        return $branches;
    }

    /**
     * Display of branch label.
     *
     * @param  int $productID
     * @param  int $moduleID
     * @param  int $executionID
     * @access public
     * @return bool
     */
    public function showBranch(int $productID, int $moduleID = 0, int $executionID = 0): bool
    {
        $this->loadModel('product');
        if(empty($productID) and empty($moduleID))
        {
            $productPairs = $this->product->getProductPairsByProject($executionID);
            if($this->app->tab != 'project') $productID = count($productPairs) == 1 ? key($productPairs) : 0;
        }
        elseif(empty($productID) and !empty($moduleID))
        {
            $module    = $this->loadModel('tree')->getById($moduleID);
            $productID = $module->type != 'task' ? $module->root : 0;
        }

        $product = $productID ? $this->product->getById($productID) : '';

        if($product and $product->type != 'normal')
        {
            $this->app->loadLang('datatable');
            $this->lang->datatable->showBranch = sprintf($this->lang->datatable->showBranch, $this->lang->product->branchName[$product->type]);
            return true;
        }

        return false;
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
        $targetBranch = $data->createBranch ? $this->create($productID, true) : $data->targetBranch;
        if($data->createBranch) $this->loadModel('action')->create('branch', $targetBranch, 'Opened');

        /* Branch. */
        $this->dao->delete()->from(TABLE_BRANCH)->where('id')->in($mergedBranches)->exec();

        $this->branchTao->afterMerge($productID, $targetBranch, $mergedBranches, $data);
        if(dao::isError()) return false;

        return $targetBranch;
    }

    /**
     * Judge an action is clickable or not..
     *
     * @param object $branch
     * @param string $action
     * @static
     * @access public
     * @return bool
     */
    public static function isClickable($branch, $action)
    {
        if(!$branch->id) return false;
        if($branch->status == 'active' && $action == 'activate') return false;
        if($branch->status == 'closed' && $action == 'close')    return false;

        return true;
    }
}
