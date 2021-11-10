<?php
/**
 * The model file of branch module of ZenTaoCMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
     * Get branch list.
     *
     * @param  int    $productID
     * @param  string $browseType
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($productID, $browseType = 'active', $orderBy = 'order', $pager = null)
    {
        $branchList = $this->dao->select('*')->from(TABLE_BRANCH)
            ->where('deleted')->eq(0)
            ->andWhere('product')->eq($productID)
            ->beginIF($browseType != 'all')->andWhere('status')->eq($browseType)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        if($browseType == 'closed') return $branchList;

        $defaultBranch = BRANCH_MAIN;
        foreach($branchList as $branch) $defaultBranch = $branch->default ? $branch->id : $defaultBranch;

        /* Display the main branch under all and active page. */
        $mainBranch = new stdclass();
        $mainBranch->id          = BRANCH_MAIN;
        $mainBranch->product     = $productID;
        $mainBranch->name        = $this->lang->branch->main;
        $mainBranch->default     = $defaultBranch ? 0 : 1;
        $mainBranch->status      = 'active';
        $mainBranch->createdDate = '';
        $mainBranch->closedDate  = '';
        $mainBranch->desc        = $this->lang->branch->mainBranch;
        $mainBranch->order       = 0;

        return array($mainBranch) + $branchList;
    }

    /**
     * Get pairs.
     *
     * @param  int    $productID
     * @param  string $params
     * @access public
     * @return array
     */
    public function getPairs($productID, $params = '')
    {
        $branches = $this->dao->select('*')->from(TABLE_BRANCH)
            ->where('deleted')->eq(0)
            ->beginIF($productID)->andWhere('product')->eq($productID)->fi()
            ->beginIF(strpos($params, 'active') !== false)->andWhere('status')->eq('active')->fi()
            ->orderBy('`order`')
            ->fetchPairs('id', 'name');
        foreach($branches as $branchID => $branchName) $branches[$branchID] = htmlspecialchars_decode($branchName);

        if(strpos($params, 'noempty') === false)
        {
            $product = $this->loadModel('product')->getById($productID);
            if(!$product or $product->type == 'normal') return array();
            $branches = array('0' => $this->lang->branch->main) + $branches;
        }

        if(strpos($params, 'all') !== false)
        {
            $branches = array('all' => $this->lang->branch->all) + $branches;
        }
        return $branches;
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
     * @access public
     * @return int|bool
     */
    public function create($productID)
    {
        $branch = fixer::input('post')
            ->add('product', $productID)
            ->add('createdDate', helper::today())
            ->add('status', 'active')
            ->get();

        $existBranchName = $this->dao->select('name')->from(TABLE_BRANCH)->where('name')->eq($branch->name)->fetch('name');
        if(!empty($existBranchName))
        {
            $this->app->loadLang('product');
            $productType = $this->dao->select('`type`')->from(TABLE_PRODUCT)->where('id')->eq($productID)->fetch('type');

            dao::$errors['name'] = str_replace('@branch@', $this->lang->product->branchName[$productType], $this->lang->branch->existName);
            return false;
        }

        $lastOrder = (int)$this->dao->select('`order`')->from(TABLE_BRANCH)->where('product')->eq($productID)->orderBy('order_desc')->limit(1)->fetch('order');
        $branch->order = empty($lastOrder) ? 1 : $lastOrder + 1;

        $this->dao->insert(TABLE_BRANCH)->data($branch)
            ->batchCheck($this->config->branch->create->requiredFields, 'notempty')
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
        $newBranch->closedDate = $newBranch->status == 'closed' ? helper::today() : '';

        $this->dao->update(TABLE_BRANCH)->data($newBranch)
            ->where('id')->eq($branchID)
            ->batchCheck($this->config->branch->edit->requiredFields, 'notempty')
            ->exec();

        if(!dao::isError()) return common::createChanges($oldBranch, $newBranch);
        return false;
    }

    /**
     * Batch update branch.
     *
     * @access public
     * @return array
     */
    public function batchUpdate($productID)
    {
        $data = fixer::input('post')->get();
        $oldBranchList = $this->getList($productID, 'all');
        $branchIDList  = array_keys($this->post->IDList);

        foreach($branchIDList as $branchID)
        {
            if($branchID == BRANCH_MAIN)
            {
                $newMainBranch = new stdClass();
                $newMainBranch->default = (isset($data->default) and $data->default == BRANCH_MAIN) ? 1 : 0;

                $changes[$branchID] = common::createChanges($oldBranchList[BRANCH_MAIN], $newMainBranch);
            }
            else
            {
                $branch = new stdclass();
                $branch->name       = $data->name[$branchID];
                $branch->desc       = $data->desc[$branchID];
                $branch->status     = $data->status[$branchID];
                $branch->default    = (isset($data->default) and $branchID == $data->default) ? 1 : 0;
                $branch->closedDate = $branch->status == 'closed' ? helper::today() : '';

                $this->dao->update(TABLE_BRANCH)->data($branch, 'default')
                    ->batchCheck($this->config->branch->create->requiredFields, 'notempty')
                    ->where('id')->eq($branchID)
                    ->exec();

                if(dao::isError()) die(js::error('branch#' . $branchID . dao::getError(true)));

                $changes[$branchID] = common::createChanges($oldBranchList[$branchID], $branch);
            }
        }

        if(isset($data->default)) $this->setDefault($productID, $data->default);

        return $changes;
    }

    /**
     * Close a branch.
     *
     * @param  int    $branchID
     * @access public
     * @return void
     */
    public function close($branchID)
    {
        $this->dao->update(TABLE_BRANCH)
            ->set('status')->eq('closed')
            ->set('closedDate')->eq(helper::today())
            ->set('`default`')->eq('0')
            ->where('id')->eq($branchID)
            ->exec();
    }

    /**
     * Activate a branch.
     *
     * @param  int    $branchID
     * @access public
     * @return void
     */
    public function activate($branchID)
    {
        $this->dao->update(TABLE_BRANCH)
            ->set('status')->eq('active')
            ->set('closedDate')->eq('')
            ->where('id')->eq($branchID)
            ->exec();
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
                if(!$branch) die(js::alert($this->lang->branch->nameNotEmpty));
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
    public function setParamsForLink($module, $link, $projectID, $productID, $branch)
    {
        $linkHtml = strpos('programplan', $module) !== false ? sprintf($link, $projectID, $productID, $branch) : sprintf($link, $productID, $branch);
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
        $defaultBranch = $this->dao->select('id')->from(TABLE_BRANCH)
            ->where('product')->eq($productID)
            ->andWhere('`default`')->eq('1')
            ->fetch('id');

        if(!empty($defaultBranch)) $this->dao->update(TABLE_BRANCH)->set('`default`')->eq('0')->where('id')->eq($defaultBranch)->exec();

        $this->dao->update(TABLE_BRANCH)->set('`default`')->eq('1')->where('id')->eq($branchID)->exec();
    }
}
