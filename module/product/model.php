<?php
declare(strict_types=1);
/**
 * The model file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @link        https://www.zentao.net
 */
class productModel extends model
{
    /**
     * 检查是否有权限访问该产品。
     * Check privilege.
     *
     * @param  int    $product
     * @access public
     * @return bool
     */
    public function checkPriv(int $productID): bool
    {
        return !empty($productID) && ($this->app->user->admin || (strpos(",{$this->app->user->view->products},", ",{$productID},") !== false));
    }

    /**
     * 根据id获取产品。
     * Get product by id.
     *
     * @param  int    $productID
     * @access public
     * @return object|false
     */
    public function getByID(int $productID): object|false
    {
        if(commonModel::isTutorialMode()) return $this->loadModel('tutorial')->getProduct();
        $product = $this->fetchById($productID);
        if(!$product) return false;

        return $this->loadModel('file')->replaceImgURL($product, 'desc');
    }

    /**
     * 根据id列表获取产品列表。
     * Get products by id list.
     *
     * @param  array  $productIdList
     * @access public
     * @return array
     */
    public function getByIdList(array $productIdList): array
    {
        return $this->dao->select('*')->from(TABLE_PRODUCT)->where('id')->in($productIdList)->fetchAll('id');
    }

    /**
     * 通过session 中的搜索条件查询产品列表。
     * Get product list by search.
     *
     * @param  int    $queryID
     * @access public
     * @return array
     */
    public function getListBySearch(int $queryID = 0): array
    {
        $this->loadModel('search')->setQuery('product', $queryID);

        $productQuery = $this->session->productQuery;
        $productQuery = preg_replace('/`(\w+)`/', 't1.`$1`', $productQuery);

        return $this->dao->select('t1.id as id,t1.*')->from(TABLE_PRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROGRAM)->alias('t2')->on('t1.program = t2.id')
            ->where($productQuery)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.shadow')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->products)->fi()
            ->andWhere("FIND_IN_SET('{$this->config->vision}', t1.vision)")
            ->orderBy('t1.order_asc')
            ->fetchAll('id');
    }

    /**
     * 获取产品键值对列表。
     * Get product pairs.
     *
     * @param  string       $mode      all|noclosed
     * @param  int          $programID
     * @param  string|array $append
     * @param  string|int   $shadow    all|0|1
     * @return int[]
     */
    public function getPairs(string $mode = '', int $programID = 0, string|array $append = '', string|int $shadow = 0): array
    {
        if(commonModel::isTutorialMode()) return $this->loadModel('tutorial')->getProductPairs();
        return $this->productTao->fetchPairs($mode, $programID, $append, $shadow);
    }

    /**
     * 根据项目获取关联的产品键值对列表。
     * Get product pairs by project.
     *
     * @param  int          $projectID
     * @param  string       $status    all|noclosed
     * @param  string|array $append
     * @param  bool         $noDeleted
     * @access public
     * @return array
     */
    public function getProductPairsByProject(int $projectID = 0, string $status = 'all', string $append = '', bool $noDeleted = true): array
    {
        if(empty($projectID)) return array();
        return $this->getProducts($projectID, $status, '', false, $append, $noDeleted);
    }

    /**
     * 获取所有与某类型的项目所关联的产品。
     * Get product pairs by project model.
     *
     * @param  string $model all|scrum|waterfall|kanban
     * @access public
     * @return array
     */
    public function getPairsByProjectModel(string $model = 'all'): array
    {
        return $this->dao->select('t3.id, t3.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product=t3.id')
            ->where('t3.deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('t3.id')->in($this->app->user->view->products)->fi()
            ->andWhere("FIND_IN_SET('{$this->config->vision}', t3.vision)")
            ->beginIF($model != 'all')->andWhere('t2.model')->eq($model)
            ->fetchPairs('id', 'name');
    }

    /**
     * 获取关联项目的产品。
     * Get products by project.
     *
     * @param  string|int   $projectID
     * @param  string       $status         all|noclosed
     * @param  string       $orderBy
     * @param  bool         $withBranch
     * @param  string|array $append
     * @param  bool         $noDeleted
     * @access public
     * @return int[]
     */
    public function getProducts(int $projectID = 0, string $status = 'all', string $orderBy = '', bool $withBranch = true, string|array $append = '', bool $noDeleted = true): array
    {
        /* 如果是新手教程模式，直接返回测试数据。*/
        if(commonModel::isTutorialMode())
        {
            $this->loadModel('tutorial');
            if(!$withBranch) return $this->tutorial->getProductPairs();
            return $this->tutorial->getExecutionProducts();
        }

        /* 初始化变量。 */
        $projectProducts = $this->productTao->getProductsByProjectID($projectID, $append, $status, $orderBy, $noDeleted);
        $products        = array();

        /* 如果不返回分支信息，则返回 id=>name 的键值对。 */
        if(!$withBranch)
        {
            foreach($projectProducts as $product) $products[$product->id] = $product->deleted ? $product->name . "({$this->lang->product->deleted})" : $product->name;
            return $products;
        }

        /* 将分支 ID 、计划 ID 合并到产品数据中。*/
        foreach($projectProducts as $product)
        {
            if(!isset($products[$product->id]))
            {
                $products[$product->id] = $product;
                $products[$product->id]->branches = array();
                $products[$product->id]->plans    = array();
            }

            $products[$product->id]->branches[$product->branch] = $product->branch;
            if($product->plan) $products[$product->id]->plans[$product->plan] = $product->plan;
            unset($product->branch, $product->plan);
        }

        return $products;
    }

    /**
     * 通过项目id查询关联的产品id。
     * Get product id list or first id by project.
     *
     * @param  int    $projectID
     * @param  bool   $isFirst
     * @access public
     * @return object[]|int
     */
    public function getProductIDByProject(int $projectID, bool $isFirst = true): array|int
    {
        $products = $this->dao->select('product')->from(TABLE_PROJECTPRODUCT)
            ->where('1=1')
            ->beginIF(!$this->app->user->admin)->andWhere('product')->in($this->app->user->view->products)->fi()
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->fetchPairs();

        if($isFirst === false) return $products;
        return empty($products) ? 0 : current($products);
    }

    /**
     * 根据项目id获取其对应的影子产品。
     * Get shadow product by project id.
     *
     * @param  int    $projectID
     * @access public
     * @return object|false
     */
    public function getShadowProductByProject(int $projectID): object|false
    {
        return $this->dao->select('products.*')->from(TABLE_PRODUCT)->alias('products')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('relations')->on('products.id = relations.product')
            ->where('products.shadow')->eq(1)
            ->andWhere('relations.project')->eq($projectID)
            ->fetch();
    }

    /**
     * 遍历产品列表，将产品线拼接到产品名上。
     * Iterate products, concatenating product line onto product name.
     *
     * @param  array  $products
     * @access public
     * @return array
     */
    public function concatProductLine(array $products): array
    {
        $lines = $this->getLinePairs();
        $productsWithLine = array();
        $productsNoLine   = array();

        foreach($products as $product)
        {
            if(array_key_exists($product->line, $lines))
            {
                $lineName = $lines[$product->line];
                if(in_array($this->config->systemMode, array('ALM', 'PLM'))) $product->name = $lineName . '/' . $product->name;
                $productsWithLine[] = $product;
            }
            else
            {
                $productsNoLine[] = $product;
            }
        }

        return array_merge($productsWithLine, $productsNoLine);
    }

    /**
     * 获取排序后的产品列表，顺序为：我的产品、其他人的产品、关闭的产品。
     * Get ordered products.
     *
     * @param  string     $status
     * @param  int        $num
     * @param  int        $projectID
     * @param  string|int $shadow     all|0|1
     * @access public
     * @return array
     */
    public function getOrderedProducts(string $status, int $num = 0, int $projectID = 0, int|string $shadow = 0): array
    {
        $products = array();
        if($projectID)
        {
            $pairs    = $this->getProducts($projectID, $status == 'normal' ? 'noclosed' : '');
            $products = $this->getByIdList(array_keys($pairs));
        }
        else
        {
            $products = $this->getList(0, $status, $num, 0, $shadow);
        }
        if(empty($products)) return $products;

        $productList     = $this->concatProductLine($products);
        $currentUser     = $this->app->user->account;
        $orderedProducts = $mineProducts = $othersProducts = $closedProducts = array();
        foreach($productList as $product)
        {
            if(!$this->app->user->admin and !$this->checkPriv($product->id)) continue;
            if($product->status == 'normal' and $product->PO == $currentUser) $mineProducts[$product->id]   = $product;
            if($product->status == 'normal' and $product->PO != $currentUser) $othersProducts[$product->id] = $product;
            if($product->status == 'closed')                                  $closedProducts[$product->id] = $product;
        }
        $orderedProducts = $mineProducts + $othersProducts + $closedProducts;

        if(empty($num)) return $orderedProducts;
        return array_slice($orderedProducts, 0, $num, true);
    }

    /**
     * 获取多分支产品和多平台产品。
     * Get Multi-branch and Multi-platform product pairs.
     *
     * @param  int    $programID
     * @access public
     * @return array
     */
    public function getMultiBranchPairs(int $programID = 0): array
    {
        return $this->dao->select('id')->from(TABLE_PRODUCT)
            ->where('deleted')->eq(0)
            ->beginIF($programID)->andWhere('program')->eq($programID)->fi()
            ->andWhere('type')->in('branch,platform')
            ->fetchPairs();
    }

    /**
     * 获取以所属项目集分组的产品列表。
     * Get products group by program.
     *
     * @access public
     * @return array
     */
    public function getProductsGroupByProgram(): array
    {
        $products = $this->getList(0, 'noclosed');

        $programIdList = array_unique(array_column($products, 'program'));
        $programs      = $this->loadModel('program')->getPairsByList($programIdList);

        $productGroup = array();
        foreach($products as $product) $productGroup[$product->program][$product->id] = zget($programs, $product->program, '') . '/' . $product->name;

        return $productGroup;
    }

    /**
     * 用对象数据创建产品
     * Create a product.
     *
     * @param  object  $product
     * @param  string  $lineName
     * @access public
     * @return int|false
     */
    public function create(object $product, string $lineName = ''): int|false
    {
        /* Insert product and get the product ID. */
        $this->lang->error->unique = $this->lang->error->repeat;
        $this->dao->insert(TABLE_PRODUCT)->data($product)->autoCheck()
            ->checkIF(!empty($product->name), 'name', 'unique', "`program` = {$product->program} AND `deleted` = '0'")
            ->checkIF(!empty($product->code), 'code', 'unique', "`deleted` = '0'")
            ->batchCheck($this->config->product->create->requiredFields, 'notempty')
            ->checkFlow()
            ->exec();
        if(dao::isError()) return false;

        $productID = $this->dao->lastInsertID();

        /* Fix order and line fields for product. */
        $fixData = new stdclass();
        $fixData->order = $productID * 5;
        if(!empty($lineName))
        {
            $lineID = $this->productTao->createLine((int)$product->program, $lineName);
            if($lineID) $fixData->line = $lineID;
        }
        $this->dao->update(TABLE_PRODUCT)->data($fixData)->where('id')->eq($productID)->exec();

        /* Update and create linked data. */
        $this->loadModel('action')->create('product', $productID, 'opened');
        $uid = empty($this->post->uid) ? '' : $this->post->uid;
        $this->loadModel('file')->updateObjectID($uid, $productID, 'product');
        $this->productTao->createMainLib($productID);
        if($product->whitelist)     $this->loadModel('personnel')->updateWhitelist(explode(',', $product->whitelist), 'product', $productID);
        if($product->acl != 'open') $this->loadModel('user')->updateUserView(array($productID), 'product');

        return $productID;
    }

    /**
     * 更新产品数据。
     * Update a product.
     *
     * @param  int    $productID
     * @param  object $product
     * @access public
     * @return array|false
     */
    public function update(int $productID, object $product): array|false
    {
        $oldProduct = $this->fetchByID($productID);

        $this->lang->error->unique = $this->lang->error->repeat;
        $result = $this->productTao->doUpdate($product, $productID, zget($product, 'program', $oldProduct->program));
        if(!$result) return false;

        /* Update objectID field of file recode, that upload by editor. */
        $this->loadModel('file')->updateObjectID($this->post->uid, $productID, 'product');

        $whitelist = explode(',', $product->whitelist);
        $this->loadModel('personnel')->updateWhitelist($whitelist, 'product', $productID);
        if($oldProduct->acl != $product->acl and $product->acl != 'open') $this->loadModel('user')->updateUserView(array($productID), 'product');

        if($product->type == 'normal' and $oldProduct->type != 'normal') $this->loadModel('branch')->unlinkBranch4Project(array($productID));
        if($product->type != 'normal' and $oldProduct->type == 'normal') $this->loadModel('branch')->linkBranch4Project($productID);

        /* Save action and changes. */
        $changes = common::createChanges($oldProduct, $product);
        if($changes)
        {
            $actionID = $this->loadModel('action')->create('product', $productID, 'edited');
            $this->action->logHistory($actionID, $changes);
        }

        return $changes;
    }

    /**
     * 批量更新产品信息。
     * Batch update products.
     *
     * @param  array $products
     * @access public
     * @return array
     */
    public function batchUpdate(array $products): array
    {
        if(empty($products)) return array();

        /* 初始化变量。*/
        $oldProducts        = $this->getByIdList(array_keys($products));
        $allChanges         = array();
        $updateViewProducts = array();
        $unlinkProducts     = array();
        $linkProducts       = array();

        /* 根据产品ID，循环更新的产品信息. */
        $this->loadModel('personnel');
        $this->lang->error->unique = $this->lang->error->repeat;
        foreach($products as $productID => $product)
        {
            $oldProduct = $oldProducts[$productID];
            if(in_array($this->config->systemMode, array('ALM', 'PLM'))) $programID = (int)zget($product, 'program', $oldProduct->program);

            $result = $this->productTao->doUpdate($product, $productID, $programID);
            if(!$result) return array('result' => 'fail', 'message' => 'product#' . $productID . dao::getError(true));

            /* When acl is open, white list set empty. */
            if($product->acl == 'open') $this->personnel->updateWhitelist(array(), 'product', $productID);

            /* 如果产品类型或权限有修改，则标记该产品，用做后面处理。*/
            if($oldProduct->acl != $product->acl and $product->acl != 'open') $updateViewProducts[] = $productID;
            if($product->type == 'normal' and $oldProduct->type != 'normal')  $unlinkProducts[]     = $productID;
            if($product->type != 'normal' and $oldProduct->type == 'normal')  $linkProducts[]       = $productID;

            $allChanges[$productID] = common::createChanges($oldProduct, $product);
        }

        /* 对标记的产品，批量做对应的后续处理。*/
        if(!empty($updateViewProducts)) $this->loadModel('user')->updateUserView($updateViewProducts, 'product');
        if(!empty($unlinkProducts))     $this->loadModel('branch')->unlinkBranch4Project($unlinkProducts);
        if(!empty($linkProducts))       $this->loadModel('branch')->linkBranch4Project($linkProducts);

        /* Save actions. */
        $this->loadModel('action');
        foreach($allChanges as $productID => $changes)
        {
            if(empty($changes)) continue;

            $actionID = $this->action->create('product', $productID, 'Edited');
            $this->action->logHistory($actionID, $changes);
        }

        return $allChanges;
    }

    /**
     * 关闭产品。
     * Close product.
     *
     * @param  int          $productID
     * @param  object       $product    must have status field.
     * @param  string|false $comment
     * @access public
     * @return array|false
     */
    public function close(int $productID, object $product, string|false $comment = ''): array|false
    {
        $oldProduct = $this->getByID($productID);
        if(empty($product)) return false;

        $this->dao->update(TABLE_PRODUCT)->data($product)->autoCheck()
            ->checkFlow()
            ->where('id')->eq($productID)
            ->exec();

        if(dao::isError()) return false;

        $changes = common::createChanges($oldProduct, $product);
        if(!empty($comment) or !empty($changes))
        {
            $actionID = $this->loadModel('action')->create('product', $productID, 'Closed', $comment);
            $this->action->logHistory($actionID, $changes);
        }
        return $changes;
    }

    /**
     * 激活产品。
     * Activate a product.
     *
     * @param  int          $productID
     * @param  object       $product    must have status field.
     * @param  string|false $comment
     * @access public
     * @return array|false
     */
    public function activate(int $productID, object $product, string|false $comment = ''): array|false
    {
        $oldProduct = $this->getByID($productID);
        if(empty($product)) return false;

        $this->dao->update(TABLE_PRODUCT)->data($product)->autoCheck()
            ->checkFlow()
            ->where('id')->eq($productID)
            ->exec();

        if(dao::isError()) return false;

        $changes = common::createChanges($oldProduct, $product);
        if(!empty($comment) or !empty($changes))
        {
            $actionID = $this->loadModel('action')->create('product', $productID, 'Activated', $comment);
            $this->action->logHistory($actionID, $changes);
        }
        return $changes;
    }

    /**
     * 更新排序。
     * Sort order field.
     *
     * @param  array  $sortedIdList
     * @access public
     * @return void
     */
    public function updateOrder(array $sortedIdList): void
    {
        /* Remove programID. */
        $sortedIdList = array_values(array_filter(array_map(function($id){return (is_numeric($id) and $id > 0) ? $id : null;}, $sortedIdList)));
        if(empty($sortedIdList)) return;

        /* Get the list of products before sorting. */
        $products = $this->dao->select('t1.`order`, t1.id')->from(TABLE_PRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROGRAM)->alias('t2')->on('t1.program = t2.id')
            ->where('t1.id')->in($sortedIdList)
            ->orderBy('t2.order_asc, t1.line_desc, t1.order_asc')
            ->fetchPairs('order', 'id');

        /* Update order by sorted id list. */
        foreach($products as $order => $id)
        {
            $newID = array_shift($sortedIdList);
            if($id == $newID) continue;
            $this->dao->update(TABLE_PRODUCT)->set('`order`')->eq($order)->where('id')->eq($newID)->exec();
        }
    }

    /**
     * 添加或更新产品线。
     * Add or update product line.
     *
     * @param  array  $lines
     * @access public
     * @return array
     */
    public function manageLine(array $lines): array
    {
        /* Init product line object. */
        $line = new stdclass();
        $line->type   = 'line';
        $line->parent = 0;
        $line->grade  = 1;

        /* Get the max order number. */
        $maxOrder = (int)$this->dao->select("`order`")->from(TABLE_MODULE)->where('type')->eq('line')->orderBy('`order`_desc')->limit(1)->fetch('order');

        foreach($lines as $programID => $lineNameList)
        {
            foreach($lineNameList as $lineID => $lineName)
            {
                $isInsert = is_numeric($lineID);
                if(!$isInsert) $lineID = str_replace('id', '', $lineID);

                /* Build product line data. */
                $line->name = strip_tags(trim($lineName));
                $line->root = $programID;
                if($isInsert)
                {
                    $maxOrder   += 10; //Reserve for extension. Increment the order number by 10.
                    $line->order = $maxOrder;
                }

                /* Update product line. */
                if(!$isInsert)
                {
                    unset($line->order);
                    $this->dao->update(TABLE_MODULE)->data($line)->where('id')->eq($lineID)->exec();
                    continue;
                }

                /* Insert product line. */
                $this->dao->insert(TABLE_MODULE)->data($line)->exec();
                $lineID = $this->dao->lastInsertID();

                /* Compute product line path and update it. */
                $path = ",$lineID,";
                $this->dao->update(TABLE_MODULE)->set('path')->eq($path)->where('id')->eq($lineID)->exec();
            }
        }

        if(dao::isError()) return array('result' => 'fail', 'message' => dao::getError());
        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true, 'load' => true);
    }

    /**
     * 获取需求列表。
     * Get stories.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  string $browseType bymodule|unclosed|allstory|assignedtome|openedbyme|reviewbyme|draftstory|reviewedbyme|assignedbyme|closedbyme|activestory|changingstory|reviewingstory|willclose|closedstory
     * @param  int    $queryID
     * @param  int    $moduleID
     * @param  string $type       requirement|story
     * @param  string $sort
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getStories(int $productID, string $branch, string $browseType, int $queryID, int $moduleID, string $type = 'story', string $sort = 'id_desc', object|null$pager = null): array
    {
        if(commonModel::isTutorialMode()) return $this->loadModel('tutorial')->getStories();

        /* Set modules and browse type. */
        $modules    = $moduleID ? $this->loadModel('tree')->getAllChildID($moduleID) : '0';
        $browseType = $browseType == 'bybranch' ? 'bymodule' : $browseType;
        $browseType = ($browseType == 'bymodule' and $this->session->storyBrowseType and $this->session->storyBrowseType != 'bysearch') ? $this->session->storyBrowseType : $browseType;

        /* Get stories by browseType. */
        if(!isset($this->story)) $this->loadModel('story');

        if($browseType == 'assignedtome')   return $this->story->getByAssignedTo($productID, $branch, $modules, $this->app->user->account, $type, $sort, $pager);
        if($browseType == 'openedbyme')     return $this->story->getByOpenedBy($productID, $branch, $modules, $this->app->user->account, $type, $sort, $pager);
        if($browseType == 'reviewedbyme')   return $this->story->getByReviewedBy($productID, $branch, $modules, $this->app->user->account, $type, $sort, $pager);
        if($browseType == 'reviewbyme')     return $this->story->getByReviewBy($productID, $branch, $modules, $this->app->user->account, $type, $sort, $pager);
        if($browseType == 'closedbyme')     return $this->story->getByClosedBy($productID, $branch, $modules, $this->app->user->account, $type, $sort, $pager);
        if($browseType == 'assignedbyme')   return $this->story->getByAssignedBy($productID, $branch, $modules, $this->app->user->account, $type, $sort, $pager);

        if($browseType == 'unplan')         return $this->story->getByPlan($productID, $queryID, $modules, '', $type, $sort, $pager);
        if($browseType == 'allstory')       return $this->story->getProductStories($productID, $branch, $modules, 'all', $type, $sort, true, '', $pager);
        if($browseType == 'bymodule')       return $this->story->getProductStories($productID, $branch, $modules, 'all', $type, $sort, true, '', $pager);
        if($browseType == 'bysearch')       return $this->story->getBySearch($productID, $branch, $queryID, $sort, 0, $type, '', '', $pager);
        if($browseType == 'willclose')      return $this->story->get2BeClosed($productID, $branch, $modules, $type, $sort, $pager);

        if($browseType == 'fromfeedback')   return $this->story->getFeedbackStories($productID, $branch, $modules, $type, $sort, $pager);

        if($browseType == 'unclosed')
        {
            $unclosedStatus = $this->lang->story->statusList;
            unset($unclosedStatus['closed']);
            return $this->story->getProductStories($productID, $branch, $modules, array_keys($unclosedStatus), $type, $sort, true, '', $pager);
        }

        /* Set default function called, when browseType is (draftstory, activestory, changingstory, reviewingstory, closedstory, developingstory, launchedstory). */
        return $this->story->getByStatus($productID, $branch, $modules, substr($browseType, 0, -5), $type, $sort, $pager);
    }

    /**
     * 批量获取需求的阶段数据。
     * Batch get story stage.
     *
     * @param  array  $stories.
     * @access public
     * @return array
     */
    public function batchGetStoryStage(array $stories): array
    {
        /* Set story id list. */
        $storyIdList = array();
        foreach($stories as $story) $storyIdList[$story->id] = $story->id;

        return $this->loadModel('story')->batchGetStoryStage($storyIdList);
    }

    /**
     * 在需求列表页面构建搜索表单。
     * Build search form for story list page.
     *
     * @param  int    $productID
     * @param  array  $products
     * @param  int    $queryID
     * @param  int    $actionURL
     * @param  string $storyType
     * @param  string $branch
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function buildSearchForm(int $productID, array $products, int $queryID, string $actionURL, string $storyType = 'story', string $branch = '', int $projectID = 0): void
    {
        $searchConfig = $this->config->product->search;

        $searchConfig['queryID']   = $queryID;
        $searchConfig['actionURL'] = $actionURL;

        /* Get product data. */
        $product = ($this->app->tab == 'project' && empty($productID)) ? $products : array();
        if(empty($product) && isset($products[$productID])) $product = array($productID => $products[$productID]);
        $searchConfig['params']['product']['values'] = $product + array('all' => $this->lang->product->allProduct);

        /* Get module data. */
        $projectID = ($this->app->tab == 'project' && empty($projectID)) ? $this->session->project : $projectID;
        $searchConfig['params']['module']['values'] = $this->productTao->getModulesForSearchForm($productID, $products, $branch, $projectID);

        if($storyType == 'requirement')
        {
            /* Change for requirement story title. */
            $this->lang->story->title  = str_replace($this->lang->SRCommon, $this->lang->URCommon, $this->lang->story->title);
            $this->lang->story->create = str_replace($this->lang->SRCommon, $this->lang->URCommon, $this->lang->story->create);
            $searchConfig['fields']['title'] = $this->lang->story->title;
            unset($searchConfig['fields']['plan']);
            unset($searchConfig['params']['plan']);
            unset($searchConfig['fields']['stage']);
            unset($searchConfig['params']['stage']);
        }
        else
        {
            /* Get product plan data. */
            $productIdList = ($this->app->tab == 'project' && empty($productID)) ? array_keys($products) : array($productID);
            $branchParam   = ($this->app->tab == 'project' && empty($productID)) ? '' : $branch;
            $searchConfig['params']['plan']['values'] = $this->loadModel('productplan')->getPairs($productIdList, (empty($branchParam) || $branchParam == 'all') ? '' : $branchParam);
        }

        /* Get branch data. */
        if($productID)
        {
            $productInfo = $this->getByID($productID);
            if(!empty($productInfo->shadow))
            {
                unset($searchConfig['fields']['product']);
                unset($searchConfig['params']['product']);
            }
            if($productInfo->type == 'normal' || $this->app->tab == 'assetlib')
            {
                unset($searchConfig['fields']['branch']);
                unset($searchConfig['params']['branch']);
            }
            else
            {
                $searchConfig['fields']['branch'] = sprintf($this->lang->product->branch, $this->lang->product->branchName[$productInfo->type]);
                $searchConfig['params']['branch']['values']  = array('' => '', '0' => $this->lang->branch->main) + $this->loadModel('branch')->getPairs($productID, 'noempty');
            }
        }

        $this->loadModel('search')->setSearchParams($searchConfig);
    }

    /**
     * 在产品列表页面构建搜索表单。
     * Build search form for product list page.
     *
     * @param  int    $queryID
     * @param  string $actionURL
     * @access public
     * @return void
     */
    public function buildProductSearchForm(int $queryID, string $actionURL): void
    {
        $this->config->product->all->search['queryID']   = $queryID;
        $this->config->product->all->search['actionURL'] = $actionURL;

        if(in_array($this->config->systemMode, array('ALM', 'PLM')))
        {
            $this->config->product->all->search['params']['program']['values'] = $this->loadModel('program')->getTopPairs('noclosed');
            $this->config->product->all->search['params']['line']['values']    = $this->getLinePairs();
        }

        $this->loadModel('search')->setSearchParams($this->config->product->all->search);
    }

    /**
     * 根据产品列表获取关联的项目列表。
     * Get project pairs by product.
     *
     * @param  array  $productIdList
     * @access public
     * @return array
     */
    public function getProjectPairsByProductIdList(array $productIdList): array
    {
        return $this->dao->select('t2.id, t2.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.product')->in($productIdList)
            ->andWhere('t2.type')->eq('project')
            ->beginIF(!$this->app->user->admin)->andWhere('t2.id')->in($this->app->user->view->projects)->fi()
            ->andWhere('t2.deleted')->eq('0')
            ->orderBy('t1.product,order_asc')
            ->fetchPairs('id', 'name');
    }

    /**
     * 获取关联某产品的项目键值对列表。
     * Get project pairs by product.
     *
     * @param  int    $productID
     * @param  string $branch        'all'|''|int
     * @param  int    $appendProject
     * @param  string $status        all|noclosed
     * @param  string $param         multiple|
     * @access public
     * @return array
     */
    public function getProjectPairsByProduct(int $productID, string $branch = '0', string $appendProject = '', string $status = '', string $param = ''): array
    {
        $product = $this->getByID($productID);
        if(empty($product)) return array();

        $appendProject = $this->productTao->formatAppendParam($appendProject);
        return $this->dao->select('t2.id, t2.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('(t1.product')->eq($productID)
            ->andWhere('t2.type')->eq('project')
            ->andWhere('t2.deleted')->eq('0')
            ->beginIF($status == 'noclosed')->andWhere('t2.status')->ne('closed')->fi()
            ->beginIF(strpos($param, 'multiple') !== false)->andWhere('t2.multiple')->ne('0')->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t2.id')->in($this->app->user->view->projects)->fi()
            ->beginIF($product->type != 'normal' and $branch !== '' and $branch != 'all')->andWhere('t1.branch')->in($branch)->fi()
            ->markRight(1)
            ->beginIF($appendProject)->orWhere('t2.id')->in($appendProject)->fi()
            ->orderBy('order_asc')
            ->fetchPairs('id', 'name');
    }

    /**
     * 获取与该产品关联的项目列表。
     * Get project list by product.
     *
     * @param  int         $productID
     * @param  string      $browseType    all|undone|wait|doing|done
     * @param  string      $branch
     * @param  bool        $involved     $this->cookie->involved or $involved
     * @param  string      $orderBy
     * @param  object|null $pager
     * @access public
     * @return array
     */
    public function getProjectListByProduct(int $productID, string $browseType = 'all', string $branch = '0', bool $involved = false, string $orderBy = 'order_desc', object|null $pager = null): array
    {
        $branch = $branch ? $branch : '0';
        if(!$involved) $projectList = $this->productTao->fetchAllProductProjects($productID, $browseType, $branch, $orderBy, $pager);
        if($involved)  $projectList = $this->productTao->fetchInvolvedProductProjects($productID, $browseType, $branch, $orderBy, $pager);

        /* Determine how to display the name of the program. */
        $programList = $this->loadModel('program')->getParentPairs('', 'all');
        foreach($projectList as $id => $project)
        {
            $programName = $project->parent ? zget($programList, $project->parent, '') : '';
            $projectList[$id]->programName = preg_replace('/\//', '', $programName, 1);
        }

        return $projectList;
    }

    /**
     * 根据产品，获取与该产品关联的项目的统计信息。
     * Get project stats by product.
     *
     * @param  int       $productID
     * @param  string    $browseType
     * @param  int       $branch
     * @param  bool      $involved     $this->cookie->involved or $involved
     * @param  string    $orderBy
     * @param  object    $pager
     * @access public
     * @return int[]
     */
    public function getProjectStatsByProduct(int $productID, string $browseType = 'all', string $branch = '0', bool $involved = false, string $orderBy = 'order_desc', object|null $pager = null): array
    {
        $projects = $this->getProjectListByProduct($productID, $browseType, $branch, $involved, $orderBy, $pager);
        if(empty($projects)) return array();

        return $this->program->appendStatToProjects($projects);
    }

    /**
     * 获取关联了某产品的执行列表。
     * Get executions by product and project.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  int    $projectID
     * @param  string $mode           stagefilter|noclosed|multiple
     * @param  array  $unAllowedStage
     * @access public
     * @return array
     */
    public function getExecutionPairsByProduct(int $productID, string $branch = '', int $projectID = 0, string $mode = '', array $unAllowedStage = array()): array
    {
        if(empty($productID)) return array();

        /* Determine executions order. */
        $projects         = $this->loadModel('project')->getByIdList(array($projectID));
        $hasWaterfall     = in_array('waterfall',     helper::arrayColumn($projects, 'model'));
        $hasWaterfallplus = in_array('waterfallplus', helper::arrayColumn($projects, 'model'));
        $waterfallOrderBy = ($hasWaterfall || $hasWaterfallplus) ? 't2.begin_asc,t2.id_asc' : 't2.begin_desc,t2.id_desc';

        $executions = $this->dao->select('t2.id,t2.name,t2.project,t2.grade,t2.path,t2.parent,t2.attribute,t2.multiple,t3.name as projectName')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.project = t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t2.project = t3.id')
            ->where('t1.product')->eq($productID)
            ->andWhere('t2.type')->in('sprint,kanban,stage')
            ->beginIF($projectID)->andWhere('t2.project')->in($projectID)->fi()
            ->beginIF($branch !== '' and strpos($branch, 'all') === false)->andWhere('t1.branch')->in($branch)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t2.id')->in($this->app->user->view->sprints)->fi()
            ->beginIF(strpos($mode, 'noclosed') !== false)->andWhere('t2.status')->ne('closed')->fi()
            ->beginIF(strpos($mode, 'multiple') !== false)->andWhere('t2.multiple')->eq('1')->fi()
            ->beginIF(!empty($unAllowedStage))->andWhere('t2.attribute')->notIn($unAllowedStage)->fi()  //针对瀑布项目，过滤掉不允许的阶段
            ->andWhere('t2.deleted')->eq('0')
            ->orderBy($waterfallOrderBy)
            ->fetchAll('id');

        if(empty($executions)) return array();
        if($projectID) $executions = $this->loadModel('execution')->resetExecutionSorts($executions);

        return $this->productTao->buildExecutionPairs($executions, $mode, empty($projectID));
    }

    /**
     * 获取产品路线图数据。
     * Get roadmap of product.
     *
     * @param  int    $productID
     * @param  string $branch    all|0|1
     * @param  int    $count
     * @access public
     * @return array
     */
    public function getRoadmap(int $productID, string $branch = '0', int $count = 0): array
    {
        /* Get group roadmap data. */
        list($groupRoadmap, $return) = $this->getGroupRoadmapData($productID, $branch, $count);
        if($return) return $groupRoadmap;

        /* Get last 5 roadmap. */
        $lastKeys    = array_slice(array_keys($groupRoadmap), 0, 5);
        $lastRoadmap = array();
        $lastRoadmap['total'] = 0;
        foreach($lastKeys as $key)
        {
            if($key == '2030')
            {
                $lastRoadmap[$this->lang->productplan->future] = $groupRoadmap[$key];
            }
            else
            {
                $lastRoadmap[$key] = $groupRoadmap[$key];
            }

            foreach($groupRoadmap[$key] as $branchRoadmap) $lastRoadmap['total'] += (count($branchRoadmap, 1) - count($branchRoadmap));
        }

        return $lastRoadmap;
    }

    /**
     * Process roadmap.
     *
     * @param  array  $roadmap
     * @param  int    $branch
     * @access public
     * @return array
     */
    public function processRoadmap(array $roadmapGroups, string $branch): array
    {
        $newRoadmap = array();
        foreach($roadmapGroups as $branchRoadmap)
        {
            foreach($branchRoadmap as $branchID => $roadmap)
            {
                if($branch != $branchID) continue;
                foreach($roadmap as $roadmapItem) $newRoadmap[] = $roadmapItem;
            }
        }
        krsort($newRoadmap);
        return $newRoadmap;
    }

    /**
     * 使用产品ID获取产品统计信息。
     * Get product stat data by product ID.
     *
     * @param  int    $productID
     * @param  string $storyType
     * @access public
     * @return object|bool
     */
    public function getStatByID($productID, $storyType = 'story')
    {
        /* Check privilege. */
        if(!$this->checkPriv($productID)) return false;

        /* Get product. */
        $product = $this->getByID($productID);
        if(empty($product)) return false;

        /* Attach statistic data. */
        $product->stories    = $this->getStoryStatusCountByID($productID, $storyType);
        $product->plans      = $this->productTao->getStatCountByID(TABLE_PRODUCTPLAN,    $productID);
        $product->builds     = $this->productTao->getStatCountByID(TABLE_BUILD,          $productID);
        $product->cases      = $this->productTao->getStatCountByID(TABLE_CASE,           $productID);
        $product->bugs       = $this->productTao->getStatCountByID(TABLE_BUG,            $productID);
        $product->docs       = $this->productTao->getStatCountByID(TABLE_DOC,            $productID);
        $product->releases   = $this->productTao->getStatCountByID(TABLE_RELEASE,        $productID);
        $product->projects   = $this->productTao->getStatCountByID(TABLE_PROJECTPRODUCT, $productID);
        $product->executions = $this->productTao->getStatCountByID('executions',         $productID);
        $product->progress   = $this->productTao->getStatCountByID('progress',           $productID);

        $product->storyDeliveryRate = 0;
        $storyDeliveryRate = $this->loadModel('metric')->getResultByCode('rate_of_delivery_story_in_product', array('product' => $productID));
        if($storyDeliveryRate)
        {
            $storyDeliveryRate = current($storyDeliveryRate);
            $product->storyDeliveryRate = $storyDeliveryRate['value'] * 100;
        }

        return $product;
    }

    /**
     * 获取产品统计信息。
     * Get product stats.
     *
     * @param  array       $productIdList
     * @param  string      $orderBy order_asc|program_asc
     * @param  object|null $pager
     * @param  string      $storyType requirement|story
     * @param  int         $programID
     * @access public
     * @return array
     */
    public function getStats(array $productIdList, string $orderBy = 'order_asc', object|null $pager = null, string $storyType = 'story', int $programID = 0): array
    {
        /* Call the getProductStats method of the tutorial module if you are in tutorial mode.*/
        if(commonModel::isTutorialMode()) return $this->loadModel('tutorial')->getProductStats();

        if(empty($productIdList)) return array();

        $stats = $this->getStatsProducts($productIdList, $programID == 0, $orderBy, $pager);

        $latestReleases       = $this->productTao->getStatisticByType($productIdList, 'latestReleases');
        $projectCountPairs    = $this->productTao->getProjectCountPairs($productIdList);
        $executionCountPairs  = $this->productTao->getExecutionCountPairs($productIdList);
        $coveragePairs        = $this->getCaseCoveragePairs($productIdList);

        foreach($stats as $product)
        {
            $product->projects   = zget($projectCountPairs, $product->id, 0);
            $product->executions = zget($executionCountPairs, $product->id, 0);
            $product->coverage   = zget($coveragePairs, $product->id, 0);

            $latestRelease = isset($latestReleases[$product->id]) ? $latestReleases[$product->id][0] : null;
            $product->latestRelease     = $latestRelease ? $latestRelease->name : '';
            $product->latestReleaseDate = $latestRelease ? $latestRelease->date : '';
        }

        return $stats;
    }

    /**
     * 获取看板页面的产品统计信息。
     * Get stats for product kanban.
     *
     * @param  string $browseType
     * @access public
     * @return array
     */
    public function getStats4Kanban($browseType = 'my'): array
    {
        /* Get base data. */
        $productList    = $this->getList();
        foreach($productList as $id => $product)
        {
            if($browseType == 'my')
            {
                if($product->PO != $this->app->user->account) unset($productList[$id]);
            }
            else
            {
                if($product->PO == $this->app->user->account) unset($productList[$id]);
            }
        }

        $projectList    = $this->loadModel('program')->getProjectStats(0, 'doing');
        $productIdList  = array_keys($productList);
        $projectProduct = $this->productTao->getProjectProductList($productList);
        $planList       = $this->productTao->getPlanList($productIdList);
        $executionList  = $this->productTao->getExecutionList(array_keys($projectList), $productIdList);
        $releaseList    = $this->productTao->getReleaseList($productIdList);

        /* Filter latest executions. */
        $projectLatestExecutions = array();
        $latestExecutionList     = array();
        $today                   = helper::today();
        foreach($executionList as $projectID => $executions)
        {
            foreach($executions as &$execution)
            {
                /* Calculate delayed execution. */
                if($execution->status != 'done' && $execution->status != 'closed' && $execution->status != 'suspended')
                {
                    $delay = helper::diffDate($today, $execution->end);
                    if($delay > 0) $execution->delay = $delay;
                }
            }

            /* Used to computer execution progress. */
            $latestExecutionList[key($executions)] = current($executions);

            /* Used for display in page. */
            $projectLatestExecutions[$projectID] = current($executions);
        }

        /* Build result. */
        $statsData = array($productList, $planList, $projectList, $projectProduct, $projectLatestExecutions, $releaseList);
        /* Convert predefined HTML entities to characters. */
        $statsData = $this->convertHtmlSpecialChars($statsData);

        return $statsData;
    }

    /**
     * 获取项目集下的产品线。
     * Get product line pairs by program.
     *
     * @param  int|array $programID
     * @access public
     * @return string[]
     */
    public function getLinePairs(int|array $programIdList = 0): array
    {
        return $this->dao->select('id,name')->from(TABLE_MODULE)
            ->where('type')->eq('line')
            ->beginIF($programIdList)->andWhere('root')->in($programIdList)->fi()
            ->andWhere('deleted')->eq(0)
            ->fetchPairs('id', 'name');
    }

    /*
     * 根据项目集编号获取产品线列表。
     * Get product lines by program id list.
     *
     * @param  array  $programIdList
     * @access public
     * @return object[]
     */
    public function getLines(array $programIdList = array()): array
    {
        return $this->dao->select('*')->from(TABLE_MODULE)
            ->where('type')->eq('line')
            ->beginIF($programIdList)->andWhere('root')->in($programIdList)->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy('`order`')
            ->fetchAll();
    }

    /**
     * 获取产品需求的摘要信息。
     * Get the summary of product's stories.
     *
     * @param  array  $stories
     * @param  string $storyType  story|requirement
     * @access public
     * @return string
     */
    public function summary(array $stories, string $storyType = 'story'): string
    {
        $totalEstimate = 0.0;
        $storyIdList   = array();

        $rateCount = 0;
        $allCount  = 0;
        $SRTotal   = 0;
        foreach($stories as $story)
        {
            if($storyType == 'requirement' && $story->type == 'story') $SRTotal += 1;
            if(!empty($story->type) && $story->type != $storyType) continue;

            $totalEstimate += $story->estimate;
            $allCount ++;

            if($story->parent >= 0 && ($story->status != 'closed' || in_array($story->closedReason, array('done', 'postponed'))))
            {
                $storyIdList[] = $story->id;
                $rateCount ++;
            }

            /* When the status is not closed or closedReason is done or postponed then add cases rate..*/
            if(empty($story->children)) continue;
            foreach($story->children as $child)
            {
                if($storyType == 'requirement' && $child->type == 'story') $SRTotal += 1;
                if($child->type != $storyType) continue;

                $allCount ++;
                if($child->status != 'closed' || in_array($child->closedReason, array('done', 'postponed')))
                {
                    $storyIdList[] = $child->id;
                    $rateCount ++;
                }
            }
        }

        $casesCount = count($this->productTao->filterNoCasesStory($storyIdList));
        $rate       = empty($stories) || $rateCount == 0 ? 0 : round($casesCount / $rateCount, 2);

        if($storyType == 'story') return sprintf($this->lang->product->storySummary, $allCount, $totalEstimate, $rate * 100 . "%");
        return sprintf($this->lang->product->requirementSummary, $allCount, $SRTotal, $totalEstimate);
    }

    /**
     * 判断某操作是否可点击。
     * Judge an action is clickable.
     *
     * @param  object $product
     * @param  string $action
     * @access public
     * @return bool
     */
    public static function isClickable(object $product, string $action): bool
    {
        $action = strtolower($action);

        if($action == 'close') return $product->status != 'closed';

        return true;
    }

    /**
     * 为产品列表页和详情页构建操作按钮。
     * Build operate menu.
     *
     * @param  object $product
     * @access public
     * @return array
     */
    public function buildOperateMenu(object $product): array
    {
        /* Declare menu list. */
        $menuList = array
        (
            'main'   => array(),
            'suffix' => array()
        );

        $params = "product=$product->id";

        if($product->status != 'closed' && common::hasPriv('product', 'close')) $menuList['main'][] = $this->config->product->actionList['close'];
        if($product->status == 'closed' && common::hasPriv('product', 'activate')) $menuList['main'][] = $this->config->product->actionList['activate'];

        if(common::hasPriv('product', 'edit'))
        {
            unset($this->config->product->actionList['edit']['text']);
            $this->config->product->actionList['edit']['url'] = helper::createLink('product', 'edit', $params);
            $menuList['suffix'][] = $this->config->product->actionList['edit'];
        }

        if(common::hasPriv('product', 'delete')) $menuList['suffix'][] = $this->config->product->actionList['delete'];

        return $menuList;
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
    public function setParamsForLink(string $module, string $link, int $projectID, int $productID): string
    {
        return $module == 'programplan' ? sprintf($link, $projectID, $productID) : sprintf($link, $productID);
    }

    /**
     * 将预定义的HTML实体转换为字符。
     * Convert predefined HTML entities to characters.
     *
     * @param  array $statsData
     * @return array
     */
    public function convertHtmlSpecialChars(array $statsData): array
    {
        if(empty($statsData)) return array();

        foreach($statsData as $key => $data)
        {
            if(empty($data)) continue;

            if($key == 'productList' || $key == 'projectList') array_map(function($item){return $item->name = htmlspecialchars_decode($item->name, ENT_QUOTES);}, $data);
            if($key == 'planList')
            {
                foreach($data as $plan)
                {
                    if(empty($plan)) continue;
                    array_map(function($planItem){return $planItem->title = htmlspecialchars_decode($planItem->title, ENT_QUOTES);}, $plan);
                }
            }
        }

        return $statsData;
    }

    /**
     * 把访问的产品ID等状态信息保存到session和cookie中。
     * Save the product id user last visited to session and cookie.
     *
     * @param  int    $productID
     * @param  array  $products
     * @access public
     * @return int
     */
    public function checkAccess(int $productID, array $products): int
    {
        if(commonModel::isTutorialMode()) return $productID;

        $productID = $this->getAccessibleProductID($productID, $products);

        $this->session->set('product', $productID, $this->app->tab);

        /* If preProductID changed, then reset preBranch. */
        if($this->cookie->preProductID != $this->session->product)
        {
            $this->cookie->set('preBranch', 0);
            helper::setcookie('preBranch', '0');
        }

        helper::setcookie('preProductID', (string)$productID);

        return $productID;
    }

    /**
     * 获取可访问的产品ID。
     * Get accessible product ID.
     *
     * @param  int       $productID
     * @param  array     $products
     * @access protected
     * @return int
     */
    protected function getAccessibleProductID(int $productID, array $products): int
    {
        if(empty($productID))
        {
            if($this->cookie->preProductID and isset($products[$this->cookie->preProductID])) $productID = (int)$this->cookie->preProductID;
            if(empty($productID) && empty($this->session->product)) $productID = (int)key($products);
        }

        if(isset($products[$productID])) return $productID;

        /* 产品ID已经被删除，不存在于产品列表中。*/
        /* Product ID does not exist in products list, it may be deleted. */
        /* Confirm if product exist. */
        $product = $this->getByID($productID);
        if(empty($product) or $product->deleted == 1) $productID = (int)key($products);

        /* If product is invisible for current user, respond access denied message. */
        if($productID && !$this->checkPriv($productID))
        {
            $productID = (int)key($products);
            $this->accessDenied($this->lang->product->accessDenied);
        }

        return (int)$productID;
    }

    /**
     * 输出访问被拒绝提示信息。
     * Show accessDenied response.
     *
     * @param  string  $tips
     * @access public
     * @return void
     */
    public function accessDenied(string $tips): bool
    {
        if(commonModel::isTutorialMode()) return true;

        return $this->app->control->sendError($tips, helper::createLink('product', 'all'));
    }

    /**
     * 设置导航菜单。
     * Set menu.
     *
     * @param  int         $productID
     * @param  string|int  $branch    all|''|int
     * @param  string      $extra     requirement|story
     * @access public
     * @return bool
     */
    public function setMenu(int $productID = 0, string|int $branch = '', string $extra = ''): bool
    {
        if(!commonModel::isTutorialMode() and $productID != 0 and !$this->checkPriv($productID))
        {
            $this->accessDenied($this->lang->product->accessDenied);
            return true;
        }

        /* 用真实数据替换导航配置的占位符，并删除无用配置项。 */
        $params = array('branch' => $branch);
        common::setMenuVars('product', $productID, $params);
        if(strpos($extra, 'requirement') !== false) unset($this->lang->product->moreSelects['willclose']);

        $product = $this->fetchByID($productID);
        if(!$product) return false;

        /* 设置导航中分支的显示数据。*/
        /* 如果产品类型是正常的，隐藏导航中分支的显示。*/
        if($product->type == 'normal')
        {
            unset($this->lang->product->menu->settings['subMenu']->branch);
            return true;
        }

        /* 如果产品类型是多分支、多平台的，将真实数据替换@branch@匹配符。*/
        $branchLink = $this->lang->product->menu->settings['subMenu']->branch['link'];
        $this->lang->product->menu->settings['subMenu']->branch['link'] = str_replace('@branch@', $this->lang->product->branchName[$product->type], $branchLink);
        $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);
        return true;
    }

    /**
     * 删除产品。
     * Delete product by ID.
     *
     * @param  int       $productID
     * @access protected
     * @return bool
     */
    protected function deleteByID(int $productID): bool
    {
        $this->delete(TABLE_PRODUCT, $productID);
        $this->dao->update(TABLE_DOCLIB)->set('deleted')->eq(1)->where('product')->eq($productID)->exec();
        return !dao::isError();
    }

    /**
     * 格式化数据表格输出数据。
     * Format data for list.
     *
     * @param  object $product
     * @param  array  $users
     * @access public
     * @return object
     */
    public function formatDataForList(object $product, array $users): object
    {
         $product->type               = 'product';
         $product->productLine        = $product->lineName;
         $product->PO                 = !empty($product->PO) ? zget($users, $product->PO) : '';
         $product->testCaseCoverage   = $product->coverage;
         $product->storyCompleteRate  = $product->totalStories == 0 ? 0 : round($product->finishedStories / $product->totalStories, 3) * 100;
         $product->bugFixedRate       = ($product->unresolvedBugs + $product->fixedBugs) == 0 ? 0 : round($product->fixedBugs / ($product->unresolvedBugs + $product->fixedBugs), 3) * 100;

        return $product;
    }

    /**
     * 构建路线图页面数据。
     * Build roadmap for UI.
     *
     * @param  array  $roadmaps
     * @param  int    $branchKey
     * @access public
     * @return array
     */
    public function buildRoadmapForUI(array $roadmaps, int $branchKey = 0): array
    {
        unset($roadmaps['total']);

        $data = array();
        foreach($roadmaps as $year => $yearRoadmaps)
        {
            if(!isset($yearRoadmaps[$branchKey])) continue;

            foreach($yearRoadmaps[$branchKey] as $roadmapData)
            {
                $yearNodes = array();
                foreach($roadmapData as $roadmap)
                {
                    $isPlan     = (isset($roadmap->begin) && isset($roadmap->end));
                    $moduleName = $isPlan ? 'productplan' : 'release';
                    $node       = array();
                    $node['href']    = common::hasPriv($moduleName, 'view') ? helper::createLink($moduleName, 'view', "id={$roadmap->id}") : '###';
                    $node['version'] = $isPlan ? $roadmap->title : $roadmap->name;
                    $node['date']    = $isPlan ? $roadmap->begin . '~' . $roadmap->end : $roadmap->date;
                    $node['marker']  = !empty($roadmap->marker);
                    $yearNodes[] = $node;
                }

                $count = count($yearNodes);
                $cols  = 5;
                $lines = array();
                for($i = 0; $i * $cols < $count; $i ++) $lines[] = array_slice($yearNodes, $i * $cols, $cols);
                $data[$year] = array_reverse($lines);
            }
        }
        return $data;
    }

    /**
     * 删除一个产品线。
     * Delete a product line.
     *
     * @param  int    $lineID
     * @access public
     * @return bool
     */
    public function deleteLine(int $lineID): bool
    {
        $this->dao->update(TABLE_MODULE)->set('deleted')->eq(1)->where('id')->eq($lineID)->exec();
        $this->dao->update(TABLE_PRODUCT)->set('line')->eq('0')->where('line')->eq($lineID)->exec();

        return !dao::isError();
    }

    /**
     * 获取产品列表。
     * Get products list.
     *
     * @param  int        $programID
     * @param  string     $status    all|noclosed|involved|review|normal|closed
     * @param  int        $limit
     * @param  int        $line
     * @param  string|int $shadow    all | 0 | 1
     * @param  string     $fields    * or fieldList, such as id,name,program
     * @access protected
     * @return array
     */
    protected function getList(int $programID = 0, string $status = 'all', int $limit = 0, int $line = 0, string|int $shadow = 0, string $fields = '*'): array
    {
        $fields = explode(',', $fields);
        $fields = trim(implode(',t1.', $fields), ',');

        return $this->dao->select("DISTINCT t1.$fields,t2.order")->from(TABLE_PRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROGRAM)->alias('t2')->on('t1.program = t2.id')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t3')->on('t3.product = t1.id')
            ->leftJoin(TABLE_TEAM)->alias('t4')->on("t4.root = t3.project and t4.type='project'")
            ->where('t1.deleted')->eq(0)
            ->beginIF($shadow !== 'all')->andWhere('t1.shadow')->eq((int)$shadow)->fi()
            ->beginIF($programID)->andWhere('t1.program')->eq($programID)->fi()
            ->beginIF($line > 0)->andWhere('t1.line')->eq($line)->fi()
            ->beginIF(strpos($status, 'feedback') === false && !$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->products)->fi()
            ->andWhere("FIND_IN_SET('{$this->config->vision}', t1.vision)")
            ->beginIF(strpos($status, 'noclosed') !== false)->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF(!in_array($status, array('all', 'noclosed', 'involved', 'review'), true))->andWhere('t1.status')->in($status)->fi()
            ->beginIF($status == 'involved')
            ->andWhere('t1.PO', true)->eq($this->app->user->account)
            ->orWhere('t1.QD')->eq($this->app->user->account)
            ->orWhere('t1.RD')->eq($this->app->user->account)
            ->orWhere('t1.createdBy')->eq($this->app->user->account)
            ->orWhere('t4.account')->eq($this->app->user->account)
            ->markRight(1)
            ->fi()
            ->beginIF($status == 'review')
            ->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.reviewers)")
            ->andWhere('t1.reviewStatus')->eq('doing')
            ->fi()
            ->orderBy('t2.order_asc, t1.line_desc, t1.order_asc')
            ->beginIF($limit > 0)->limit($limit)->fi()
            ->fetchAll('id');
    }

    /**
     * 获取用于统计的产品列表。
     * Get products list for statistic.
     *
     * @param  int[]       $productIdList
     * @param  bool        $appendProgram
     * @param  string      $orderBy
     * @param  object|null $pager
     * @access public
     * @return array
     */
    public function getStatsProducts(array $productIdList, bool $appendProgram, string $orderBy, object|null $pager = null): array
    {
        if($orderBy == 'program_asc')
        {
            $products = $this->productTao->getPagerProductsWithProgramIn($productIdList, $pager);
        }
        else
        {
            $products = $this->productTao->getPagerProductsIn($productIdList, $pager, $orderBy);
        }

        /* Fetch product lines. */
        $linePairs = $this->getLinePairs();
        foreach($products as $product) $product->lineName = zget($linePairs, $product->line, '');

        if(!$appendProgram) return $products;

        $programIdList = array();
        foreach($products as $product) $programIdList[] = $product->program;
        $programs = $this->loadModel('program')->getBaseDataList(array_unique($programIdList));

        $finishClosedStory = $this->dao->select('product, count(1) as finish')->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->andWhere('status')->eq('closed')
            ->andWhere('closedReason')->eq('done')
            ->groupBy('product')
            ->fetchPairs('product', 'finish');
        $launchedStory = $this->dao->select('product, count(1) as launched')->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->andWhere('status')->eq('launched')
            ->groupBy('product')
            ->fetchPairs('product', 'launched');
        $developingStory = $this->dao->select('product, count(1) as developing')->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->andWhere('status')->eq('developing')
            ->groupBy('product')
            ->fetchPairs('product', 'developing');
        foreach($products as $productID => $product)
        {
            $product->programName = isset($programs[$product->program]) ? $programs[$product->program]->name : '';
            $product->programPM   = isset($programs[$product->program]) ? $programs[$product->program]->PM : '';

            $products[$productID]->finishClosedStories = isset($finishClosedStory[$productID]) ? $finishClosedStory[$productID] : 0;
            $products[$productID]->launchedStories     = isset($launchedStory[$productID]) ? $launchedStory[$productID] : 0;
            $products[$productID]->developingStories   = isset($developingStory[$productID]) ? $developingStory[$productID] : 0;
        }

        return $products;
    }

    /**
     * 获取用于数据统计的研发需求和用户需求列表。
     * Get dev stories and user requirements for statistics.
     *
     * @param  array   $productIdList
     * @param  string  $storyType
     * @access public
     * @return array[]
     */
    public function getStatsStoriesAndRequirements(array $productIdList, string $storyType): array
    {
        $this->loadModel('story');
        $stories      = $this->story->getStoriesCountByProductIDs($productIdList, 'story');
        $requirements = $this->story->getStoriesCountByProductIDs($productIdList);

        /* Padding the stories to sure all products have records. */
        $defaultStory = array_keys($this->lang->story->statusList);
        foreach($productIdList as $productID)
        {
            if(!isset($stories[$productID]))      $stories[$productID]      = $defaultStory;
            if(!isset($requirements[$productID])) $requirements[$productID] = $defaultStory;
        }

        /* Collect count for each status of stories. */
        foreach($stories as $key => $story)
        {
            foreach(array_keys($this->lang->story->statusList) as $status) $story[$status] = isset($story[$status]) ? $story[$status]->count : 0;
            $stories[$key] = $story;
        }

        /* Collect count for each status of requirements. */
        foreach($requirements as $key => $requirement)
        {
            foreach(array_keys($this->lang->story->statusList) as $status) $requirement[$status] = isset($requirement[$status]) ? $requirement[$status]->count : 0;
            $requirements[$key] = $requirement;
        }

        /* Story type is 'requirement'. */
        if($storyType == 'requirement') $stories = $requirements;

        return array($stories, $requirements);
    }

    /**
     * 获取多个产品关联需求用例覆盖率的键值对。
     * Get K-V pairs of product ID and test case coverage.
     *
     * @param  int[]  $productIdList
     * @access public
     * @return array
     */
    public function getCaseCoveragePairs(array $productIdList): array
    {
        if(empty($productIdList)) return array();

        /* Get storie list by product ID list. */
        $storyList = $this->loadModel('story')->getStoriesByProductIdList($productIdList);

        /* Get case count of each story. */
        $storyIdList      = array();
        $productStoryList = array();
        foreach($storyList as $story)
        {
            $storyIdList[] = $story->id;

            if(!isset($productStoryList[$story->product])) $productStoryList[$story->product] = array();
            $productStoryList[$story->product][] = $story->id;
        }
        $caseCountPairs = $this->productTao->getCaseCountByStoryIdList($storyIdList);

        /* Calculate coverage. */
        $coveragePairs = array();
        foreach($productStoryList as $productID => $list)
        {
            $total = count($list);

            $totalCovered = 0;
            foreach($list as $storyID)
            {
                if(isset($caseCountPairs[$storyID])) $totalCovered++;
            }

            $coveragePairs[$productID] = $total ? round($totalCovered * 100 / $total) : 0;
        }

        return $coveragePairs;
    }

    /**
     * 从产品统计数据中统计项目集。
     * Statistics program data from statistics data of product.
     *
     * @param  array  $productStats
     * @access public
     * @return array
     */
    public function statisticProgram(array $productStats): array
    {
        if(commonModel::isTutorialMode()) return $this->loadModel('tutorial')->getProductStats();

        $programStructure = array();

        foreach($productStats as $product)
        {
            $programStructure[$product->program][$product->line]['products'][$product->id] = $product;

            /* Generate line data. */
            if($product->line && $this->config->vision != 'or')
            {
                $programStructure[$product->program][$product->line]['lineName'] = $product->lineName;
                $programStructure[$product->program][$product->line] = $this->productTao->statisticProductData('line', $programStructure, $product);
            }

            /* Generate program data. */
            if($product->program && $this->config->vision != 'or')
            {
                $programStructure[$product->program]['programName'] = $product->programName;
                $programStructure[$product->program]['programPM']   = $product->programPM;
                $programStructure[$product->program]['id']          = $product->program;
                $programStructure[$product->program]                = $this->productTao->statisticProductData('program', $programStructure, $product);
            }
        }

        return $programStructure;
    }

    /**
     * 通过产品ID查询产品关联需求的各状态统计总数。每一个需求状态对应一个数量。
     * Get stories total count of each status by product ID. Every story status has a count value.
     *
     * @param  int      $productID
     * @param  string   $storyType
     * @access public
     * @return object[]
     */
    public function getStoryStatusCountByID(int $productID, string $storyType = 'story'): array
    {
        /* 通过产品ID获取每一个需求状态对应的数量。Get count of each story status of the product. */
        $statusCountList = $this->loadModel('story')->getStoriesCountByProductID($productID, $storyType);
        foreach($statusCountList as $status => $stat) $statusCountList[$status] = $stat->count;

        /* 确保每一种需求状态都是有值的。Padding the stories to make sure all status have records. */
        foreach(array_keys($this->lang->story->statusList) as $status)
        {
            if(!isset($statusCountList[$status])) $statusCountList[$status] = 0;
        }

        return $statusCountList;
    }

    /**
     * 获取产品路线图的分组数据。
     * Get group roadmap data of product.
     *
     * @param  int       $productID
     * @param  string    $branch    all|0|1
     * @param  int       $count
     * @access public
     * @return [array, bool]
     */
    public function getGroupRoadmapData(int $productID, string $branch, int $count): array
    {
        $roadmap = array();
        $return  = false;

        /* Get product plans. */
        $planList = $this->loadModel('productplan')->getList($productID, $branch);

        /* Filter the valid plans, then get the ordered and parents plans. */
        list($orderedPlans, $parentPlans) = $this->productTao->filterOrderedAndParentPlans($planList);

        /* Get roadmaps of product plans. */
        list($roadmap, $total, $return) = $this->getRoadmapOfPlans($orderedPlans, $parentPlans, $branch, $count);
        if($return) return array($roadmap, $return);

        /* Get roadmpas of product releases. */
        $releases = $this->loadModel('release')->getList($productID, $branch);
        list($roadmap, $subTotal, $return) = $this->getRoadmapOfReleases($roadmap, $releases, $branch, $count);
        if($return) return array($roadmap, $return);
        $total += $subTotal;

        /* Re-group with branch ID. */
        $groupRoadmap = array();
        foreach($roadmap as $year => $branchRoadmap)
        {
            foreach($branchRoadmap as $branch => $roadmapItems)
            {
                /* Split roadmap items into multiple lines. */
                $totalData = count($roadmapItems);
                $rows      = ceil($totalData / 8);
                $maxPerRow = ceil($totalData / $rows);

                $groupRoadmap[$year][$branch] = array_chunk($roadmapItems, (int)$maxPerRow);
                foreach(array_keys($groupRoadmap[$year][$branch]) as $row) krsort($groupRoadmap[$year][$branch][$row]);
            }
        }

        return array($groupRoadmap, $return);
    }

    /**
     * 获取发布的路线图数据。
     * Get roadmap of releases.
     *
     * @param  array   $roadmap
     * @param  array   $parents
     * @param  string  $branch
     * @param  int     $count
     * @access public
     * @return [array, int, bool]
     */
    public function getRoadmapOfReleases(array $roadmap, array $releases, string $branch, int $count): array
    {
        $total           = 0;
        $return          = false;
        $orderedReleases = array();

        /* Collect releases. */
        foreach($releases as $release) $orderedReleases[$release->date][] = $release;

        krsort($orderedReleases);
        foreach($orderedReleases as $releases)
        {
            krsort($releases);
            foreach($releases as $release)
            {
                $year         = substr($release->date, 0, 4);
                $branchIdList = explode(',', trim($release->branch, ','));
                $branchIdList = array_unique($branchIdList);
                foreach($branchIdList as $branchID)
                {
                    if($branchID === '') continue;
                    $roadmap[$year][$branchID][] = $release;
                }
                $total++;

                /* Exceed required count .*/
                if($count > 0 and $total >= $count)
                {
                    $roadmap = $this->processRoadmap($roadmap, $branch);
                    $return  = true;
                    return array($roadmap, $total, $return);
                }
            }
        }

        if($count > 0)
        {
            $roadmap = $this->processRoadmap($roadmap, $branch);
            $return = true;
        }

        return array($roadmap, $total, $return);
    }

    /**
     * 获取计划的路线图数据。
     * Get roadmap of plans.
     *
     * @param  array   $orderedPlans
     * @param  array   $parents
     * @param  string  $branch
     * @param  int     $count
     * @access public
     * @return [array, int, bool]
     */
    public function getRoadmapOfPlans(array $orderedPlans, array $parents, string $branch, int $count): array
    {
        $return  = false;
        $total   = 0;
        $roadmap = array();

        foreach($orderedPlans as $plans)
        {
            krsort($plans);
            foreach($plans as $plan)
            {
                /* Attach parent plan. */
                if($plan->parent > 0 and isset($parents[$plan->parent])) $plan->title = $parents[$plan->parent] . ' / ' . $plan->title;

                $year         = substr($plan->end, 0, 4);
                $branchIdList = explode(',', trim($plan->branch, ','));
                $branchIdList = array_unique($branchIdList);
                foreach($branchIdList as $branchID)
                {
                    if($branchID === '') continue;
                    $roadmap[$year][$branchID][] = $plan;
                }
                $total++;

                /* Exceed requested count. */
                if($count > 0 and $total >= $count)
                {
                    $roadmap = $this->processRoadmap($roadmap, $branch);
                    $return  = true;
                    break;
                }
            }
        }

        return array($roadmap, $total, $return);
    }

    /**
     * 获取产品1.5级导航下拉项跳转链接。
     * Get product link for 1.5 level navigation.
     *
     * @param  string  $module
     * @param  string  $method
     * @param  string  $extra
     * @param  bool    $branch
     * @access public
     * @return string
     */
    public function getProductLink(string $module, string $method, string $extra = '', bool $branch = false): string
    {
        $branchID    = $branch ? "%s" : 'all';
        $branchParam = $branch ? "&branch=%s" : '';
        $extraParam  = $extra  ? "&extras=$extra" : '';
        $params      = explode(',', $extra);
        $method      = strtolower($method);

        if($module == 'project'    && $method == 'bug')         return helper::createLink($module, $method,       "projectID={$params[0]}&productID=%s{$branchParam}");
        if($module == 'bug'        && $method == 'view')        return helper::createLink('bug',   'browse',      "productID=%s&branch={$branchID}&extra=$extra");
        if($module == 'bug'        && $method == 'report')      return helper::createLink('bug',   'browse',      "productID=%s{$branchParam}");
        if($module == 'qa'         && $method == 'index')       return helper::createLink('bug',   'browse',      "productID=%s{$branchParam}");
        if($module == 'story'      && $method == 'report')      return helper::createLink($module, $method,       "productID=%s&branch={$branchID}&extra=$extra");
        if($module == 'testcase'   && $method == 'browse')      return helper::createLink($module, $method,       "productID=%s&branch={$branchID}" . ($extra ? "&browseType=$extra" : ''));
        if($module == 'testreport' && $method == 'create')      return helper::createLink($module, $method,       "objectID=&objectType=testtask&extra=%s");
        if($module == 'testtask'   && $method == 'browse')      return helper::createLink($module, $method,       "productID=%s&branch={$branchID}&extra={$extra}");
        if($module == 'testsuite'  && $method != 'create')      return helper::createLink('testsuite', 'browse',  "productID=%s");
        if($module == 'product'    && $method == 'project')     return helper::createLink($module, $method,       "status=all&productID=%s{$branchParam}");
        if($module == 'product'    && $method == 'dynamic')     return helper::createLink($module, $method,       "productID=%s&type=$extra");
        if($module == 'project'    && $method == 'testcase')    return helper::createLink($module, $method,       "projectID={$params[0]}&productID=%s&branch={$branchID}&browseType={$params[1]}");
        if($module == 'testtask'   && $method == 'browseunits') return helper::createLink($module, 'browseUnits', "productID=%s&browseType=newest&orderBy=id_desc&recTotal=0&recPerPage=0&pageID=1" . ($this->app->tab == 'project' ? "&projectID={$this->session->project}" : ''));
        if($module == 'testtask'   && $method == 'unitcases')   return helper::createLink($module, 'browseUnits', "productID=%s&browseType=newest&orderBy=id_desc&recTotal=0&recPerPage=0&pageID=1" . ($this->app->tab == 'project' ? "&projectID={$this->session->project}" : ''));

        if($module == 'execution'  && in_array($method, array('bug', 'testcase')))        return helper::createLink($module,    $method,  "executionID={$params[0]}&productID=%s{$branchParam}");
        if($module == 'product'    && in_array($method, array('doc', 'view')))            return helper::createLink($module,    $method,  "productID=%s");
        if($module == 'product'    && in_array($method, array('create', 'showimport')))   return helper::createLink($module,    'browse', "productID=%s&type=$extra");
        if($module == 'product'    && in_array($method, array('browse', 'index', 'all'))) return helper::createLink($module,    'browse', "productID=%s&branch={$branchID}&browseType=&param=0&$extra");
        if($module == 'ticket'     && in_array($method, array('browse', 'view', 'edit'))) return helper::createLink('ticket',   'browse', "browseType=byProduct&productID=%s");
        if($module == 'testreport' && in_array($method, array('edit', 'browse')))         return helper::createLink($module,    'browse', "objectID=%s");
        if($module == 'feedback'   && $this->config->vision == 'lite')                    return helper::createLink('feedback', 'browse', "browseType=byProduct&productID=%s");

        if($module == 'design')      return helper::createLink('design',      'browse', "productID=%s");
        if($module == 'execution')   return helper::createLink('execution',   $method,  "objectID=$extra&productID=%s");
        if($module == 'feedback')    return helper::createLink('feedback',    'admin',  "browseType=byProduct&productID=%s");
        if($module == 'programplan') return helper::createLink('programplan', 'browse', "projectID=%s&productID=%s&type=" . ($extra ? $extra : 'gantt'));
        if($module == 'project')     return helper::createLink('project',     $method,  "objectID=$extra&productID=%s");
        if($module == 'tree')        return helper::createLink('tree',        $method,  "productID=%s&view=story&currentModuleID=0{$branchParam}");
        if($module == 'ticket')      return helper::createLink('ticket',      $method,  'productID=%s');
        if($module == 'testtask')    return helper::createLink('testtask',    'browse', "productID=%s&branch={$branchID}");

        if($module == 'api'         || $module == 'doc')     return helper::createLink('doc',   'productSpace', "objectID=%s");
        if($module == 'productplan' || $module == 'release') return helper::createLink($module, $method,        "productID=%s{$branchParam}");
        if(in_array($module, array('productplan', 'release', 'roadmap')) && $method != 'create') return helper::createLink($module, 'browse', "productID=%s{$branchParam}");

        if($module == 'testcase' && in_array($method, array('groupcase', 'zerocase')) && $this->app->tab == 'project')
        {
            $projectID = $extra;
            if(str_contains($extra, 'projectID'))
            {
                parse_str($extra, $output);
                $projectID = zget($output, 'projectID', 0);
            }
            return helper::createLink($module, $method, "productID=%s&branch={$branchID}&groupBy=&projectID=$projectID") . "#app=project";
        }
        if($module == 'story' and $this->config->vision == 'or') return helper::createLink('story', 'create', "productID=%s&branch=0&moduleID=0&storyID=0&objectID=0&bugID=0&planID0&todoID=0&extra=&storyType=requirement");

        return helper::createLink($module, $method, "productID=%s{$branchParam}{$extraParam}");
    }

    /**
     * 刷新产品的统计信息。
     * Refresh stats info of products.
     *
     * @param  bool   $refreshAll
     * @access public
     * @return void
     */
    public function refreshStats(bool $refreshAll = false): void
    {
        $updateTime = zget($this->app->config->global, 'productStatsTime', '');
        $now        = helper::now();

        /*
         * If productStatsTime is before two weeks ago, refresh all products directly.
         * Else only refresh the latest products in action table.
         */
        $productActions = array();
        $products       = array();
        if($updateTime < date('Y-m-d', strtotime('-14 days')) || $refreshAll)
        {
            $products = $this->dao->select('id')->from(TABLE_PRODUCT)->fetchPairs('id');
        }
        else
        {
            $productActions = $this->dao->select('distinct product')->from(TABLE_ACTION)
                ->where('date')->ge($updateTime)
                ->andWhere('product')->notin(array(',0,', ',,'))
                ->fetchPairs('product');
            if(empty($productActions)) return;

            foreach($productActions as $productAction)
            {
                foreach(explode(',', trim($productAction, ',')) as $product) $products[$product] = $product;
            }
        }

        /* 1. Get summary of products to be refreshed. */
        $stats = $this->productTao->getProductStats($products);

        /* 2. Refresh stats to db. */
        foreach($stats as $productID=> $product)
        {
            $this->dao->update(TABLE_PRODUCT)->data($product)->where('id')->eq($productID)->exec();
        }

        /* 3. Update projectStatsTime in config. */
        $this->loadModel('setting')->setItem('system.common.global.productStatsTime', $now);
        $this->app->config->global->productStatsTime = $now;

        /* 4. Clear actions older than 30 days. */
        $this->loadModel('action')->cleanActions();
    }
}
