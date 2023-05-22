<?php
/**
 * The model file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: model.php 5118 2013-07-12 07:41:41Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class productModel extends model
{
    /**
     * 获取移动端1.5级导航。
     * Get product drop menu in mobile.
     *
     * @param  array      $products
     * @param  int        $productID
     * @param  string     $extra
     * @param  string|int $branch
     *
     * @access public
     * @return string
     */
    public function getDropMenu4Mobile(array $products, int $productID, string $extra = '', string|int $branch = ''): string
    {
        list($locateModule, $locateMethod) = $this->productTao->computeLocate4DropMenu();

        $selectHtml = $this->select($products, $productID, $locateModule, $locateMethod, $extra, $branch);
        $pageNav    = html::a(helper::createLink('product', 'index'), $this->lang->product->index) . $this->lang->colon;
        $pageNav   .= $selectHtml;

        return $pageNav;
    }

    /**
     * Create the select code of products.
     *
     * @param  array       $products
     * @param  int         $productID
     * @param  string      $currentModule
     * @param  string      $currentMethod
     * @param  string      $extra
     * @param  int|string  $branch
     * @param  int         $module
     * @param  string      $moduleType
     * @param  bool        $withBranch      true|false
     *
     * @access public
     * @return string
     */
    public function select(array $products, int $productID, string $currentModule, string $currentMethod, string $extra = '', string|int $branch = '', bool $withBranch = true): string
    {
        /* 处理数据。*/
        if(isset($products[0])) unset($products[0]);
        if(empty($products)) return '';
        if(!isset($products[$productID])) $productID = (int)key($products);

        /* 检测当前页面是否在项目或执行的测试二级导航中。*/
        $isQaModule = (strpos(',project,execution,', ",{$this->app->tab},") !== false and stripos(',bug,testcase,testtask,ajaxselectstory,', ",{$this->app->rawMethod},") !== false) ? true : false;
        if($this->app->tab == 'project' and stripos(',zeroCase,browseUnits,groupCase,', ",$currentMethod,") !== false) $isQaModule = true;
        if($isQaModule)
        {
            if($this->app->tab == 'project')   $extra = strpos(',testcase,groupCase,zeroCase,', ",$currentMethod,") !== false ? $extra : $this->session->project;
            if($this->app->tab == 'execution') $extra = $this->session->execution;
        }

        /* 查询产品数据。*/
        $product = $this->getByID($productID);
        $this->session->set('currentProductType', $product->type);

        /* 生成异步获取下拉菜单的链接。*/
        $moduleName = $isQaModule ? 'bug' : 'product';
        if($this->app->tab == 'feedback') $moduleName = 'feedback';
        $dropMenuLink = helper::createLink($moduleName, 'ajaxGetDropMenu', "objectID=$productID&module=$currentModule&method=$currentMethod&extra=$extra");

        /* 构建移动端产品1.5级导航代码。 */
        if($this->app->viewType == 'mhtml')
        {
            $output  = "<a id='currentItem' href=\"javascript:showSearchMenu('product', '$productID', '$currentModule', '$currentMethod', '$extra')\"><span class='text'>{$product->name}</span> <span class='icon-caret-down'></span></a><div id='currentItemDropMenu' class='hidden affix enter-from-bottom layer'></div>";
            $output .= $this->productTao->getBranchDropMenu4Select($product, $branch, $currentModule, $currentMethod, $extra, $withBranch);
            return $output;
        }

        /* 构建PC端产品1.5级导航代码。 */
        $output  = "<div class='btn-group angle-btn'><div class='btn-group'><button data-toggle='dropdown' type='button' class='btn btn-limit' id='currentItem' title='{$product->name}' style='width: 90%'><span class='text'>{$product->name}</span> <span class='caret'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$dropMenuLink'>";
        $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div></div></div>';
        $output .= $this->productTao->getBranchDropMenu4Select($product, $branch, $currentModule, $currentMethod, $extra, $withBranch);
        $output .= '</div>';

        return $output;
    }

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
        if(empty($productID)) return false;

        /* Is admin? */
        if($this->app->user->admin) return true;
        return (strpos(",{$this->app->user->view->products},", ",{$productID},") !== false);
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
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getProduct();
        $product = $this->dao->findById($productID)->from(TABLE_PRODUCT)->fetch();
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
            ->andWhere('t1.vision')->eq($this->config->vision)->fi()
            ->orderBy('t1.order_asc')
            ->fetchAll('id');
    }

    /**
     * Get product pairs.
     *
     * @param  string       $mode      all|noclosed
     * @param  int          $programID
     * @param  string|array $append
     * @param  string|int   $shadow    all | 0 | 1
     * @return int[]
     */
    public function getPairs(string $mode = '', int $programID = 0, string|array $append = '', string|int $shadow = 0): array
    {
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getProductPairs();
        return $this->productTao->fetchPairs($mode, $programID, $append, $shadow);
    }

    /**
     * Get product pairs by project.
     *
     * @param  int          $projectID
     * @param  string       $status   all|noclosed
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
            ->andWhere('t3.vision')->eq($this->config->vision)->fi()
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
        if(defined('TUTORIAL'))
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
     * Get shadow products by project id.
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
                if($this->config->systemMode == 'ALM') $product->name = $lineName . '/' . $product->name;
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
     * Get Multi-branch product pairs.
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
     * Get products group by program.
     *
     * @access public
     * @return array
     */
    public function getProductsGroupByProgram(): array
    {
        $products = $this->productTao->getList(0, 'noclosed');

        $programIdList = array_unique(array_column($products, 'program'));
        $programs      = $this->loadModel('program')->getPairsByList($programIdList);

        $productGroup = array();
        foreach($products as $product) $productGroup[$product->program][$product->id] = zget($programs, $product->program, '') . '/' . $product->name;

        return $productGroup;
    }

    /*
     * 获取1.5级导航数据。
     * Get product switcher.
     *
     * @param  int         $productID
     * @param  string      $extra
     * @param  string|int  $branch
     * @access public
     * @return string
     */
    public function getSwitcher(int $productID = 0, string $extra = '', string|int $branch = ''): string
    {
        /* 获取产品名称，产品类型。 */
        $currentProduct     = new stdclass();
        $currentProductName = $this->lang->productCommon;
        if($productID)
        {
            $currentProduct     = $this->getByID($productID);
            $currentProductName = $currentProduct->name;
            $this->session->set('currentProductType', $currentProduct->type);
        }

        if($this->app->viewType == 'mhtml') return $this->getDropMenu4Mobile(array($productID => $currentProductName), $productID, $extra, $branch);

        /* Init locateModule and locateMethod for report and story. */
        list($locateModule, $locateMethod) = $this->productTao->computeLocate4DropMenu();

        /* 生成异步获取产品下拉菜单的链接。*/
        $fromModule     = $this->app->tab == 'qa' ? 'qa' : '';
        $dropMenuModule = $this->app->tab == 'qa' ? 'product' : $this->app->tab;
        $dropMenuLink   = helper::createLink($dropMenuModule, 'ajaxGetDropMenu', "objectID=$productID&module=$locateModule&method=$locateMethod&extra=$extra&from=$fromModule");

        /* 构建产品1.5级导航数据。 */
        $output  = "<div class='btn-group header-btn' id='swapper'><button data-toggle='dropdown' type='button' class='btn' id='currentItem' title='{$currentProductName}'><span class='text'>{$currentProductName}</span> <span class='caret' style='margin-bottom: -1px'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$dropMenuLink'>";
        $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
        $output .= "</div></div>";
        $output .= $this->productTao->getBranchDropMenu4Switch($currentProduct, $branch, $locateModule, $locateMethod, $extra);

        return $output;
    }

    /**
     * Create a product.
     * 直接用对象数据创建产品
     *
     * @param  object  $product
     * @param  string  $lineName
     * @access public
     * @return int|false
     */
    public function create(object $product, string $lineName = ''): int|false
    {
        $this->lang->error->unique = $this->lang->error->repeat;
        $this->dao->insert(TABLE_PRODUCT)->data($product)->autoCheck()
            ->checkIF(!empty($product->name), 'name', 'unique', "`program` = {$product->program} and `deleted` = '0'")
            ->checkIF(!empty($product->code), 'code', 'unique', "`deleted` = '0'")
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
        $this->loadModel('file')->updateObjectID($this->post->uid, $productID, 'product');
        $this->productTao->createMainLib($productID);
        if($product->whitelist)     $this->loadModel('personnel')->updateWhitelist(explode(',', $product->whitelist), 'product', $productID);
        if($product->acl != 'open') $this->loadModel('user')->updateUserView($productID, 'product');

        return $productID;
    }

    /**
     * Update a product.
     *
     * @param  int    $productID
     * @param  object $product
     * @access public
     * @return array|false
     */
    public function update(int $productID, object $product): array|false
    {
        $oldProduct = $this->dao->findById($productID)->from(TABLE_PRODUCT)->fetch();

        $this->lang->error->unique = $this->lang->error->repeat;
        $result = $this->productTao->doUpdate($product, $productID, (int)$product->program);
        if(!$result) return false;

        /* Update objectID field of file recode, that upload by editor. */
        $this->loadModel('file')->updateObjectID($this->post->uid, $productID, 'product');

        $whitelist = explode(',', $product->whitelist);
        $this->loadModel('personnel')->updateWhitelist($whitelist, 'product', $productID);
        if($oldProduct->acl != $product->acl and $product->acl != 'open') $this->loadModel('user')->updateUserView($productID, 'product');

        if($product->type == 'normal' and $oldProduct->type != 'normal') $this->loadModel('branch')->unlinkBranch4Project($productID);
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
            if($this->config->systemMode == 'ALM') $programID = (int)zget($product, 'program', $oldProduct->program);

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
     * Close product.
     *
     * @param  int    $productID
     * @param  object $product    must have status field.
     * @param  string $comment
     * @access public
     * @return array|false
     */
    public function close(int $productID, object $product, string $comment = ''): array|false
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
     * Activate a product.
     *
     * @param  int    $productID.
     * @access public
     * @return array|false
     */
    public function activate(int $productID): array|false
    {
        $oldProduct = $this->getByID($productID);
        $product    = (object)array('status' => 'normal');

        $this->dao->update(TABLE_PRODUCT)->data($product)->where('id')->eq($productID)->exec();
        if(dao::isError()) return false;

        return common::createChanges($oldProduct, $product);
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
        return array('result' => 'success', 'message' => $this->lang->saveSuccess, 'load' => true);
    }

    /**
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
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getStories();

        /* Set modules and browse type. */
        $modules    = $moduleID ? $this->loadModel('tree')->getAllChildID($moduleID) : '0';
        $browseType = $browseType == 'bybranch' ? 'bymodule' : $browseType;
        $browseType = ($browseType == 'bymodule' and $this->session->storyBrowseType and $this->session->storyBrowseType != 'bysearch') ? $this->session->storyBrowseType : $browseType;

        /* Get stories by browseType. */
        $stories = array();
        if(!isset($this->story)) $this->loadModel('story');

        if($browseType == 'unplan')         return $this->story->getByPlan($productID, $queryID, $modules, '', $type, $sort, $pager);
        if($browseType == 'allstory')       return $this->story->getProductStories($productID, $branch, $modules, 'all', $type, $sort, true, '', $pager);
        if($browseType == 'bymodule')       return $this->story->getProductStories($productID, $branch, $modules, 'all', $type, $sort, true, '', $pager);
        if($browseType == 'bysearch')       return $this->story->getBySearch($productID, $branch, $queryID, $sort, '', $type, '', $pager);
        if($browseType == 'assignedtome')   return $this->story->getByAssignedTo($productID, $branch, $modules, $this->app->user->account, $type, $sort, $pager);
        if($browseType == 'openedbyme')     return $this->story->getByOpenedBy($productID, $branch, $modules, $this->app->user->account, $type, $sort, $pager);
        if($browseType == 'reviewedbyme')   return $this->story->getByReviewedBy($productID, $branch, $modules, $this->app->user->account, $type, $sort, $pager);
        if($browseType == 'reviewbyme')     return $this->story->getByReviewBy($productID, $branch, $modules, $this->app->user->account, $type, $sort, $pager);
        if($browseType == 'closedbyme')     return $this->story->getByClosedBy($productID, $branch, $modules, $this->app->user->account, $type, $sort, $pager);
        if($browseType == 'draftstory')     return $this->story->getByStatus($productID, $branch, $modules, 'draft', $type, $sort, $pager);
        if($browseType == 'activestory')    return $this->story->getByStatus($productID, $branch, $modules, 'active', $type, $sort, $pager);
        if($browseType == 'changingstory')  return $this->story->getByStatus($productID, $branch, $modules, 'changing', $type, $sort, $pager);
        if($browseType == 'reviewingstory') return $this->story->getByStatus($productID, $branch, $modules, 'reviewing', $type, $sort, $pager);
        if($browseType == 'willclose')      return $this->story->get2BeClosed($productID, $branch, $modules, $type, $sort, $pager);
        if($browseType == 'closedstory')    return $this->story->getByStatus($productID, $branch, $modules, 'closed', $type, $sort, $pager);
        if($browseType == 'assignedbyme')   return $this->story->getByAssignedBy($productID, $branch, $modules, $this->app->user->account, $type, $sort, $pager);

        if($browseType == 'unclosed')
        {
            $unclosedStatus = $this->lang->story->statusList;
            unset($unclosedStatus['closed']);
            return $this->story->getProductStories($productID, $branch, $modules, array_keys($unclosedStatus), $type, $sort, true, '', $pager);
        }

        return $stories;
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
     * Build search form.
     *
     * @param  int    $productID
     * @param  array  $products
     * @param  int    $queryID
     * @param  int    $actionURL
     * @param  string $branch
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function buildSearchForm(int $productID, array $products, int $queryID, string $actionURL, string $branch = '', int $projectID = 0): void
    {
        $productIdList = ($this->app->tab == 'project' and empty($productID)) ? array_keys($products) : array($productID);
        $branchParam   = ($this->app->tab == 'project' and empty($productID)) ? '' : $branch;
        $projectID     = ($this->app->tab == 'project' and empty($projectID)) ? $this->session->project : $projectID;

        $this->config->product->search['actionURL'] = $actionURL;
        $this->config->product->search['queryID']   = $queryID;
        $this->config->product->search['params']['plan']['values'] = $this->loadModel('productplan')->getPairs($productIdList, (empty($branchParam) or $branchParam == 'all') ? '' : $branchParam);

        $product = ($this->app->tab == 'project' and empty($productID)) ? $products : array();
        if(empty($product) and isset($products[$productID])) $product = array($productID => $products[$productID]);

        $this->config->product->search['params']['product']['values'] = $product + array('all' => $this->lang->product->allProduct);

        if($productID)
        {
            $productInfo = $this->getByID($productID);
            if(!empty($productInfo->shadow)) unset($this->config->product->search['fields']['product']);
            if($productInfo->type == 'normal' or $this->app->tab == 'assetlib')
            {
                unset($this->config->product->search['fields']['branch']);
                unset($this->config->product->search['params']['branch']);
            }
            else
            {
                $this->config->product->search['fields']['branch'] = sprintf($this->lang->product->branch, $this->lang->product->branchName[$productInfo->type]);
                $this->config->product->search['params']['branch']['values']  = array('' => '', '0' => $this->lang->branch->main) + $this->loadModel('branch')->getPairs($productID, 'noempty');
            }
        }

        /* Get modules. */
        $this->config->product->search['params']['module']['values'] = array('' => '') + $this->productTao->getModulesForSearchForm($productID, $products, $branch, $projectID);

        $this->loadModel('search')->setSearchParams($this->config->product->search);
    }

    /**
     * Build search form for all method of product module.
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

        if($this->config->systemMode == 'ALM')
        {
            $programPairs = $this->loadModel('program')->getTopPairs('', 'noclosed');
            $this->config->product->all->search['params']['program']['values'] = array('' => '') + $programPairs;

            $linePairs = $this->getLinePairs();
            $this->config->product->all->search['params']['line']['values'] = array('' => '') + $linePairs;
        }

        $this->loadModel('search')->setSearchParams($this->config->product->all->search);
    }

    /**
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
            ->where('(1=1')
            ->andWhere('t1.product')->eq($productID)
            ->beginIF($status == 'noclosed')->andWhere('t2.status')->ne('closed')->fi()
            ->beginIF(strpos($param, 'multiple') !== false)->andWhere('t2.multiple')->ne('0')->fi()
            ->andWhere('t2.type')->eq('project')
            ->beginIF(!$this->app->user->admin)->andWhere('t2.id')->in($this->app->user->view->projects)->fi()
            ->beginIF($product->type != 'normal' and $branch !== '' and $branch != 'all')->andWhere('t1.branch')->in($branch)->fi()
            ->andWhere('t2.deleted')->eq('0')
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

        $projectKeys = array_keys($projects);

        /* Get all tasks and compute totalEstimate, totalConsumed, totalLeft, progress according to them. */
        $tasks = $this->dao->select('id, project, estimate, consumed, `left`, status, closedReason')
            ->from(TABLE_TASK)
            ->where('project')->in($projectKeys)
            ->andWhere('parent')->lt(1)
            ->andWhere('deleted')->eq(0)
            ->fetchGroup('project', 'id');
        $hours = $this->loadModel('program')->computeProjectHours($tasks);

        /* Get the number of project teams. */
        $teams = $this->dao->select('t1.root,t1.account')->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account=t2.account')
            ->where('t1.root')->in($projectKeys)
            ->andWhere('t1.type')->eq('project')
            ->andWhere('t2.deleted')->eq(0)
            ->fetchGroup('root', 'account');

        return $this->program->appendStatToProjects($projects, 'hours,teamCount', array('hours' => $hours, 'teams' => $teams));
    }

    /**
     * Get executions by product and project.
     *
     * @param  int    $productID
     * @param  string $branch
     * @param  string $projectID
     * @param  string $mode      stagefilter|noclosed|multiple
     * @access public
     * @return array
     */
    public function getExecutionPairsByProduct(int $productID, string $branch = '', string $projectID = '0', string $mode = ''): array
    {
        if(empty($productID)) return array();

        $projects     = $this->loadModel('project')->getByIdList($projectID);
        $hasWaterfall = false;
        foreach($projects as $project)
        {
            if(in_array($project->model, array('waterfall', 'waterfallplus'))) $hasWaterfall = true;
        }
        $waterFallOrderBy = $hasWaterfall ? 't2.begin_asc,t2.id_asc' : 't2.begin_desc,t2.id_desc';

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
            ->andWhere('t2.deleted')->eq('0')
            ->orderBy($waterFallOrderBy)
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
        list($groupRoadmap, $return) = $this->productTao->getGroupRoadmapData($productID, $branch, $count);
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
     * Get team members of a product from projects.
     *
     * @param  object   $product
     * @access public
     * @return array
     */
    public function getTeamMemberPairs($product)
    {
        $members[$product->PO] = $product->PO;
        $members[$product->QD] = $product->QD;
        $members[$product->RD] = $product->RD;
        $members[$product->createdBy] = $product->createdBy;

        /* Set projects and teams as static thus we can only query sql one times. */
        static $projects, $teams;
        if(empty($projects))
        {
            $projects = $this->dao->select('t1.project, t1.product')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
                ->where('t2.deleted')->eq(0)
                ->andWhere('t2.type')->eq('project')
                ->fetchGroup('product', 'project');
        }
        if(empty($teams))
        {
            $teams = $this->dao->select('t1.root, t1.account')->from(TABLE_TEAM)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.root = t2.id')
                ->where('t2.deleted')->eq(0)
                ->andWhere('t1.type')->eq('project')
                ->fetchGroup('root', 'account');
        }

        if(!isset($projects[$product->id])) return $members;
        $productProjects = $projects[$product->id];

        $projectTeams = array();
        foreach(array_keys($productProjects) as $projectID) $projectTeams = array_merge($projectTeams, array_keys($teams[$projectID]));

        return array_flip(array_merge($members, $projectTeams));
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
        $product->stories    = $this->productTao->getStoryStatusCountByID($productID, $storyType);
        $product->plans      = $this->productTao->getStatCountByID(TABLE_PRODUCTPLAN,    $productID);
        $product->releases   = $this->productTao->getStatCountByID(TABLE_BUILD,          $productID);
        $product->builds     = $this->productTao->getStatCountByID(TABLE_CASE,           $productID);
        $product->cases      = $this->productTao->getStatCountByID(TABLE_BUG,            $productID);
        $product->projects   = $this->productTao->getStatCountByID(TABLE_DOC,            $productID);
        $product->executions = $this->productTao->getStatCountByID(TABLE_RELEASE,        $productID);
        $product->bugs       = $this->productTao->getStatCountByID(TABLE_PROJECTPRODUCT, $productID);
        $product->docs       = $this->productTao->getStatCountByID('executions',         $productID);
        $product->progress   = $this->productTao->getStatCountByID('progress',           $productID);

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
        if(empty($productIdList)) return array();

        $this->loadModel('story');

        /* Get stats data. */
        $appendProgram = $programID == 0;
        $products      = $this->productTao->getStatsProducts($productIdList, $appendProgram, $orderBy, $pager);

        $finishClosedStory    = $this->story->getFinishClosedTotal();
        $unclosedStory        = $this->story->getUnClosedTotal();
        $plans                = $this->productTao->getPlansTODO($productIdList);
        $releases             = $this->productTao->getReleasesTODO($productIdList);
        $bugs                 = $this->productTao->getBugsTODO($productIdList);
        $unResolved           = $this->productTao->getUnResolvedTODO($productIdList);
        $fixedBugs            = $this->productTao->getFixedBugsTODO($productIdList);
        $closedBugs           = $this->productTao->getClosedBugsTODO($productIdList);
        $thisWeekBugs         = $this->productTao->getThisWeekBugsTODO($productIdList);
        $assignToNull         = $this->productTao->getAssignToNullTODO($productIdList);
        list($stories, $reqs) = $this->productTao->getStatsStoriesAndRequirements($productIdList, $storyType);
        $executionCountPairs  = $this->productTao->getExecutionCountPairs($productIdList);
        $coveragePairs        = $this->productTao->getCaseCoveragePairs($productIdList);

        /* Render statistic result to each product. */
        $stats = array();
        foreach($products as $productID => $product)
        {
            $product->stories                 = $stories[$product->id];
            $product->stories['finishClosed'] = isset($finishClosedStory[$product->id]) ? $finishClosedStory[$product->id] : 0;
            $product->stories['unclosed']     = isset($unclosedStory[$product->id])     ? $unclosedStory[$product->id]     : 0;

            $product->requirements = $reqs[$product->id];
            $product->plans        = isset($plans[$product->id])               ? $plans[$product->id]               : 0;
            $product->releases     = isset($releases[$product->id])            ? $releases[$product->id]            : 0;
            $product->bugs         = isset($bugs[$product->id])                ? $bugs[$product->id]                : 0;
            $product->unResolved   = isset($unResolved[$product->id])          ? $unResolved[$product->id]          : 0;
            $product->closedBugs   = isset($closedBugs[$product->id])          ? $closedBugs[$product->id]          : 0;
            $product->fixedBugs    = isset($fixedBugs[$product->id])           ? $fixedBugs[$product->id]           : 0;
            $product->thisWeekBugs = isset($thisWeekBugs[$product->id])        ? $thisWeekBugs[$product->id]        : 0;
            $product->assignToNull = isset($assignToNull[$product->id])        ? $assignToNull[$product->id]        : 0;
            $product->executions   = isset($executionCountPairs[$product->id]) ? $executionCountPairs[$product->id] : 0;
            $product->coverage     = isset($coveragePairs[$product->id])       ? $coveragePairs[$product->id]       : 0;

            /* Calculate product progress. */
            $closedTotal       = $product->stories['closed'] + $product->requirements['closed'];
            $allTotal          = array_sum($product->stories) + array_sum($product->requirements);
            $product->progress = empty($closedTotal) ? 0 : round($closedTotal / $allTotal * 100, 1);

            $stats[$productID] = $product;
        }

        return $stats;
    }

    /**
     * 获取看板页面的产品统计信息。
     * Get stats for product kanban.
     *
     * @access public
     * @return array
     */
    public function getStats4Kanban(): array
    {
        $this->loadModel('program');

        /* Get base data. */
        $productList    = $this->getList();
        $programList    = $this->program->getTopPairs('', '', true);
        $projectList    = $this->program->getProjectStats(0, 'doing');

        $productIdList  = array_keys($productList);
        $projectIdList  = array_keys($projectList);
        $projectProduct = $this->productTao->getProjectProductList($productList);
        $planList       = $this->productTao->getPlanList($productIdList);
        $executionList  = $this->productTao->getExecutionList($projectIdList, $productIdList);
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

        $hourList = $this->loadModel('project')->computerProgress($latestExecutionList);

        /* Build result. */
        $statsData = array(
            $programList,
            $productList,
            $planList,
            $projectList,
            $executionList,
            $projectProduct,
            $projectLatestExecutions,
            $hourList,
            $releaseList
        );
        /* Convert predefined HTML entities to characters. */
        $statsData = $this->convertHtmlSpecialChars($statsData);

        return $statsData;
    }

    /**
     * Get product line pairs.
     *
     * @param  int    $programID
     * @access public
     * @return int[]
     */
    public function getLinePairs(int $programID = 0): array
    {
        return $this->dao->select('id,name')->from(TABLE_MODULE)
            ->where('type')->eq('line')
            ->beginIF($programID)->andWhere('root')->eq($programID)->fi()
            ->andWhere('deleted')->eq(0)
            ->fetchPairs('id', 'name');
    }

    /*
     * Get all lines.
     *
     * @param  array $programIdList
     * @access public
     * @return array
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
        foreach($stories as $story)
        {
            if(!empty($story->type) && $story->type != $storyType) continue;

            $totalEstimate += $story->estimate;
            $allCount ++;

            /* When the status is not closed or closedReason is done or postponed then add cases rate..*/
            if(empty($story->children))
            {
                if($story->status != 'closed' or ($story->status == 'closed' and in_array($story->closedReason, ['done', 'postponed'])))
                {
                    $storyIdList[] = $story->id;
                    $rateCount ++;
                }

                continue;
            }

            foreach($story->children as $child)
            {
                if($child->type != $storyType) continue;

                if($story->status != 'closed' or ($story->status == 'closed' and in_array($story->closedReason, ['done', 'postponed'])))
                {
                    $storyIdList[] = $child->id;
                    $rateCount ++;
                }
                $allCount ++;
            }
        }

        $casesCount = $this->productTao->getStoryCasesCount($storyIdList);
        $rate       = empty($stories) || $rateCount == 0 ? 0 : round($casesCount / $rateCount, 2);

        $storyCommon = $this->lang->SRCommon;
        if($storyType == 'requirement') $storyCommon = $this->lang->URCommon;
        if($storyType == 'story')       $storyCommon = $this->lang->SRCommon;

        return sprintf($this->lang->product->storySummary, $allCount,  $storyCommon, $totalEstimate, $rate * 100 . "%");
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
     * Build operate menu.
     *
     * @param  object $product
     * @param  string $type
     * @access public
     * @return string
     */
    public function buildOperateMenu($product, $type = 'view')
    {
        $menu   = '';
        $params = "product=$product->id";

        if($type == 'view')
        {
            $menu .= "<div class='divider'></div>";
            $menu .= $this->buildFlowMenu('product', $product, $type, 'direct');
            $menu .= "<div class='divider'></div>";

            $menu .= $this->buildMenu('product', 'close', $params, $product, $type, '', '', 'iframe', true, "data-app='product'");
            $menu .= "<div class='divider'></div>";
        }

        $menu .= $this->buildMenu('product', 'edit', $params, $product, $type);

        if($type == 'view') $menu .= $this->buildMenu('product', 'delete', $params, $product, $type, 'trash', 'hiddenwin');

        return $menu;
    }

    /**
     * Create the link from module,method,extra,branch.
     *
     * @param  string  $module
     * @param  string  $method
     * @param  string  $extra
     * @param  bool    $branch
     * @access public
     * @return void
     */
    public function getProductLink($module, $method, $extra, $branch = false)
    {
        $link = '';
        if(strpos(',programplan,product,roadmap,bug,testcase,testtask,story,qa,testsuite,testreport,build,projectrelease,projectstory,', ',' . $module . ',') !== false)
        {
            if($module == 'product' && $method == 'project')
            {
                $link = helper::createLink($module, $method, "status=all&productID=%s" . ($branch ? "&branch=%s" : ''));
            }
            elseif($module == 'product' && ($method == 'doc' or $method == 'view'))
            {
                $link = helper::createLink($module, $method, "productID=%s");
            }
            elseif($module == 'product' && $method == 'dynamic')
            {
                $link = helper::createLink($module, $method, "productID=%s&type=$extra");
            }
            elseif($module == 'product' && ($method == 'create' or $method == 'showimport'))
            {
                $link = helper::createLink($module, 'browse', "productID=%s&type=$extra");
            }
            elseif($module == 'qa' && $method == 'index')
            {
                $link = helper::createLink('bug', 'browse', "productID=%s" . ($branch ? "&branch=%s" : ''));
            }
            elseif($module == 'product' && ($method == 'browse' or $method == 'index' or $method == 'all'))
            {
                $link = helper::createLink($module, 'browse', "productID=%s" . ($branch ? "&branch=%s" : '&branch=0') . "&browseType=&param=0&$extra");
            }
            elseif($module == 'programplan')
            {
                $extra = $extra ? $extra : 'gantt';
                $link  = helper::createLink($module, 'browse', "projectID=%s&productID=%s&type=$extra");
            }
            elseif($module == 'story' && $method == 'report')
            {
                $link = helper::createLink($module, 'report', "productID=%s" . ($branch ? "&branch=%s" : '&branch=0') . "&extra=$extra");
            }
            elseif($module == 'testtask')
            {
                $extra = $method != 'browse' ? '' : "&extra=$extra";
                if(strtolower($method) == 'browseunits')
                {
                    $methodName = 'browseUnits';
                    $param      = '&browseType=newest&orderBy=id_desc&recTotal=0&recPerPage=0&pageID=1';
                    $param     .= $this->app->tab == 'project' ? "&projectID={$this->session->project}" : '';
                }
                else
                {
                    $methodName = 'browse';
                    $param      = ($branch ? "&branch=%s" : '&branch=0') . $extra;
                }

                $link = helper::createLink($module, $methodName, "productID=%s" . $param);
            }
            elseif($module == 'bug' && $method == 'view')
            {
                $link = helper::createLink('bug', 'browse', "productID=%s" . ($branch ? "&branch=%s" : '&branch=0') . "&extra=$extra");
            }
            elseif($module == 'testsuite' && !in_array($method, array('browse', 'create')))
            {
                $link = helper::createLink('testsuite', 'browse', "productID=%s");
            }
            elseif($module == 'testcase' and in_array($method, array('groupCase', 'zeroCase')) and $this->app->tab == 'project')
            {
                $projectID = $extra;
                if(strpos($extra, 'projecID') !== false)
                {
                    parse_str($extra, $output);
                    $projectID = isset($output['projectID']) ? $output['projectID'] : 0;
                }
                $link = helper::createLink($module, $method, "productID=%s&branch=" . ($branch ? "%s" : 'all') . "&groupBy=&projectID=$projectID") . "#app=project";
            }
            elseif($module == 'testcase' and $method == 'browse')
            {
                $link = helper::createLink('testcase', 'browse', "productID=%s" . ($branch ? "&branch=%s" : '&branch=all') . "&browseType=$extra");
            }
            elseif($module == 'testreport' and ($method == 'create' or $method == 'edit'))
            {
                $vars   = $method == 'edit' ? "objectID=%s" : "objectID=&objectType=testtask&extra=%s";
                $method = $method == 'edit' ? 'browse' : $method;
                $link   = helper::createLink($module, $method, $vars);
            }
            else
            {
                $link = helper::createLink($module, $method, "productID=%s" . ($branch ? "&branch=%s" : ''));
            }
        }
        elseif($module == 'productplan' || $module == 'release')
        {
            if($method != 'browse' && $method != 'create') $method = 'browse';
            $link = helper::createLink($module, $method, "productID=%s" . ($branch ? "&branch=%s" : ''));
        }
        elseif($module == 'tree')
        {
            $link = helper::createLink($module, $method, "productID=%s&type=$extra&currentModuleID=0" . ($branch ? "&branch=%s" : ''));
        }
        elseif($module == 'branch')
        {
            $link = helper::createLink($module, $method, "productID=%s");
        }
        elseif($module == 'doc' or $module == 'api')
        {
            $link = helper::createLink('doc', 'productSpace', "objectID=%s");
        }
        elseif($module == 'design')
        {
            return helper::createLink('design', 'browse', "productID=%s");
        }
        elseif(strpos(',project,execution,', ",$module,") !== false and $method == 'bug')
        {
            $params = explode(',', $extra);
            return helper::createLink($module, $method, "projectID={$params[0]}&productID=%s" . ($branch ? "&branch=%s" : ''));
        }
        elseif($module == 'project' and $method == 'testcase')
        {
            $params = explode(',', $extra);
            return helper::createLink('project', 'testcase', "projectID={$params[0]}&productID=%s&branch=" . ($branch ? "%s" : '0') . "&browseType={$params[1]}");
        }
        elseif($module == 'execution' and $method == 'testcase')
        {
            $params = explode(',', $extra);
            return helper::createLink('execution', 'testcase', "executionID={$params[0]}&productID=%s" . ($branch ? "&branch=%s" : ''));
        }
        elseif($module == 'project' or $module == 'execution')
        {
            $objectID = $module == 'project' ? 'projectID' : 'executionID';
            return helper::createLink($module, $method, "$objectID=$extra&productID=%s");
        }
        elseif($module == 'feedback')
        {
            if($this->config->vision == 'rnd')
            {
                return helper::createLink('feedback', 'admin', "browseType=byProduct&productID=%s");
            }
            elseif($this->config->vision == 'lite')
            {
                return helper::createLink('feedback', 'browse', "browseType=byProduct&productID=%s");
            }
        }
        elseif($module == 'ticket')
        {
            $params = "productID=%s";
            if(strpos('browse,view,edit', $method) !== false)
            {
                $method = 'browse';
                $params = "browseType=byProduct&productID=%s";
            }
            return helper::createLink('ticket', $method, $params);
        }

        return $link;
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
        return strpos('programplan', $module) !== false ? sprintf($link, $projectID, $productID) : sprintf($link, $productID);
    }

    /**
     * Change the projects set of the program.
     *
     * @param  int   $productID
     * @param  array $singleLinkProjects
     * @param  array $multipleLinkProjects
     * @access public
     * @return void
     */
    public function updateProjects($productID, $singleLinkProjects = array(), $multipleLinkProjects = array())
    {
        $programID = $_POST['program'];
        foreach($singleLinkProjects as $projectID => $projectName)
        {
            if($projectName)
            {
                $this->dao->update(TABLE_PROJECT)
                    ->set('parent')->eq($programID)
                    ->set('path')->eq(',' . $programID . ',' . $projectID . ',')
                    ->where('id')->eq($projectID)
                    ->exec();
            }
        }

        foreach($multipleLinkProjects as $projectID => $projectName)
        {
            if(strpos($_POST['changeProjects'], ',' . $projectID . ',') !== false)
            {
                $this->dao->delete()->from(TABLE_PROJECTPRODUCT)
                    ->where('project')->eq($projectID)
                    ->andWhere('product')->ne($productID)
                    ->exec();

                $this->dao->update(TABLE_PROJECT)
                    ->set('parent')->eq($programID)
                    ->set('path')->eq(',' . $programID . ',' . $projectID . ',')
                    ->where('id')->eq($projectID)
                    ->exec();

                $this->loadModel('action')->create('project', $projectID, 'Managed', '', $productID);
            }
        }
    }

    /**
     * Convert predefined HTML entities to characters
     *
     * @param  array $statsData
     * @return array
     */
    public function convertHtmlSpecialChars($statsData)
    {
        if(empty($statsData)) return array();

        foreach($statsData as $key => $data)
        {
            if($key == 'productList' || $key == 'projectList')
            {
                !empty($data) && array_map(function($item)
                {
                    return $item->name = htmlspecialchars_decode($item->name, ENT_QUOTES);
                },
                $data);
            }

            if($key == 'planList')
            {
                foreach($data as $plan)
                {
                    !empty($plan) && array_map(function($planItem)
                    {
                        return $planItem->title = htmlspecialchars_decode($planItem->title, ENT_QUOTES);
                    },
                    $plan);
                }
            }
        }

        return $statsData;
    }

    public function buildRows($productStructure, $params = array())
    {
        $programLines = zget($params, 'programLines', array());
        $rows         = array();
        foreach($productStructure as $programID => $program)
        {
            if($programID and $this->config->systemMode == 'ALM') $rows[] = $this->buildRowData($programID, $program, 'program', $params);

            if(isset($programLines[$programID]))
            {
                foreach($programLines[$programID] as $lineID => $lineName)
                {
                    if(!isset($program[$lineID]))
                    {
                        $program[$lineID] = array();
                        $program[$lineID]['product']  = '';
                        $program[$lineID]['lineName'] = $lineName;
                    }
                }
            }

            foreach($program as $lineID => $line)
            {
                $showLine = (isset($line['lineName']) and $this->config->systemMode == 'ALM');
                if($showLine)
                {
                    $params['parent'] = 'program_' . $programID;
                    $rows[] = $this->buildRowData($lineID, $line, 'line', $params);
                }

                if(isset($line['products']) and is_array($line['products']))
                {
                    foreach($line['products'] as $productID => $product)
                    {
                        $params['parent'] = $showLine ? 'line_' . $lineID : 'program_' . $programID;
                        $rows[] = $this->buildRowData($productID, $product, 'product', $params);
                    }
                }
            }
        }

        return $rows;
    }

    public function buildRowData($id, $data, $type = 'program', $params = array())
    {
        $users       = zget($params, 'users', array());
        $usersAvatar = zget($params, 'usersAvatar', array());

        $row = new stdclass();
        $row->id = $id;
        if($type == 'program') $row->id = 'program_' . $id;
        if($type == 'line')    $row->id = 'line_' . $id;

        $row->name = '';
        if($type == 'program') $row->name = zget($data, 'programName', '');
        if($type == 'line')    $row->name = zget($data, 'lineName', '');
        if($type == 'product') $row->name = common::hasPriv('product', 'browse') ? html::a(helper::createLink('product', 'browse', 'productID=' . $id), $data->name) : $data->name;

        $row->draftStories     = zget($data, 'draftStories', 0);
        $row->activeStories    = zget($data, 'activeStories', 0);
        $row->changingStories  = zget($data, 'changingStories', 0);
        $row->reviewingStories = zget($data, 'reviewingStories', 0);
        $row->unResolvedBugs   = zget($data, 'unResolvedBugs', 0);
        $row->plans            = zget($data, 'plans', 0);
        $row->releases         = zget($data, 'releases', 0);

        $totalStories = zget($data, 'finishClosedStories', 0) + zget($data, 'unclosedStories', 0);
        $totalBugs    = zget($data, 'unResolvedBugs', 0)      + zget($data, 'fixedBugs', 0);
        $row->storyCompleteRate = $totalStories == 0 ? 0 : (round(zget($data, 'finishClosedStories', 0) / $totalStories, 3) * 100) . '%';
        $row->bugFixedRate      = $totalBugs == 0 ? 0 : (round(zget($data, 'fixedBugs', 0) / $totalBugs, 3) * 100) . '%';
        $row->actions           = $type == 'product' ? $this->buildOperateMenu($data, 'list') : '';
        $row->parent            = $type == 'program' ? '' : zget($params, 'parent', '');
        $row->type              = $type;

        if($type == 'product')
        {
            $row->draftStories     = $data->stories['draft'];
            $row->activeStories    = $data->stories['active'];
            $row->changingStories  = $data->stories['changing'];
            $row->reviewingStories = $data->stories['reviewing'];
            $row->unResolvedBugs   = $data->unResolved;

            $totalStories = $data->stories['finishClosed'] + $data->stories['unclosed'];
            $totalBugs    = $data->unResolved + $data->fixedBugs;
            $row->storyCompleteRate = $totalStories == 0 ? 0 : (round($data->stories['finishClosed'] / $totalStories, 3) * 100) . '%';
            $row->bugFixedRate      = $totalBugs == 0 ? 0 : (round($data->fixedBugs / $totalBugs, 3) * 100) . '%';

        }

        $row->PO       = '';
        $row->POAvatar = '';
        if(($type == 'program' and !empty($data['programPM'])) or ($type == 'product' and !empty($data->PO)))
        {
            if($type == 'program') $PO = $data['programPM'];
            if($type == 'product') $PO = $data->PO;
            $row->PO       = zget($users, $PO);
            $row->POAvatar = zget($usersAvatar, $PO);
        }

        return $row;
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
    public function saveVisitState(int $productID, array $products): int
    {
        if(defined('TUTORIAL')) return $productID;

        $productID = $this->getAccessableProductID($productID, $products);

        $this->session->set('product', $productID, $this->app->tab);

        helper::setcookie('preProductID', (string)$productID);

        /* If preProductID changed, then reset preBranch. */
        if($this->cookie->preProductID != $this->session->product)
        {
            $this->cookie->set('preBranch', 0);
            helper::setcookie('preBranch', '0');
        }

        return $productID;
    }

    /**
     * 获取可访问的产品ID。
     * Get accessable product ID.
     *
     * @param  int       $productID
     * @param  array     $products
     * @access protected
     * @return int
     */
    protected function getAccessableProductID(int $productID, array $products): int
    {
        if(empty($productID))
        {
            if($this->cookie->preProductID and isset($products[$this->cookie->preProductID])) $productID = $this->cookie->preProductID;
            if(empty($this->session->product)) $productID = (int)key($products);
        }

        if(isset($products[$productID])) return $productID;

        /* 产品ID已经被删除，不存在于产品列表中。*/
        /* Product ID does not exsit in products list, it may be deleted. */
        /* Confirm if product exist. */
        $product = $this->product->getByID($productID);
        if(empty($product) or $product->deleted == 1) $productID = (int)key($products);

        /* If product is invisible for current user, respond access denied message. */
        if($productID && !$this->checkPriv($productID))
        {
            $productID = (int)key($products);
            $this->accessDenied($this->lang->product->accessDenied);
        }

        return $productID;
    }

    /**
     * 输出访问被拒绝提示信息。
     * Show accessDenied response.
     *
     * @param  string  $tips
     * @access private
     * @return void
     */
    public function accessDenied(string $tips)
    {
        if(defined('TUTORIAL')) return true;

        echo js::alert($tips);

        if(!$this->server->http_referer) return print(js::locate(helper::createLink('product', 'index')));

        $loginLink = $this->config->requestType == 'GET' ? "?{$this->config->moduleVar}=user&{$this->config->methodVar}=login" : "user{$this->config->requestFix}login";
        if(strpos($this->server->http_referer, $loginLink) !== false) return print(js::locate(helper::createLink('product', 'index')));

        echo js::locate('back');
    }

    /**
     * 设置导航菜单。
     * Set menu.
     *
     * @param  int         $productID
     * @param  string|int  $branch      all|''|int
     * @param  string      $extra
     * @access public
     * @return void
     */
    public function setMenu(int $productID = 0, string|int $branch = '', string $extra = '')
    {
        if(!defined('TUTORIAL') and $productID != 0 and !$this->checkPriv($productID)) return $this->accessDenied($this->lang->product->accessDenied);

        /* 用真实数据替换导航配置的占位符，并删除无用配置项。 */
        $params = array('branch' => $branch);
        common::setMenuVars('product', $productID, $params);
        if(strpos($extra, 'requirement') !== false) unset($this->lang->product->moreSelects['willclose']);

        $product = $this->getByID($productID);
        if(!$product) return;

        /* 设置1.5级导航数据。*/
        $this->lang->switcherMenu = $this->getSwitcher($productID, $extra, $branch);

        /* 设置导航中分支的显示数据。*/
        /* 如果产品类型是正常的，隐藏导航中分支的显示。*/
        if($product->type == 'normal')
        {
            unset($this->lang->product->menu->settings['subMenu']->branch);
            return;
        }

        /* 如果产品类型是多分支、多平台的，将真实数据替换@branch@匹配符。*/
        $branchLink = $this->lang->product->menu->settings['subMenu']->branch['link'];
        $this->lang->product->menu->settings['subMenu']->branch['link'] = str_replace('@branch@', $this->lang->product->branchName[$product->type], $branchLink);
        $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);
    }

    /**
     * 删除产品。
     * Delete product by ID.
     *
     * @param  int       $productID
     * @access protected
     * @return void
     */
    protected function deleteByID(int $productID): void
    {
        $this->delete(TABLE_PRODUCT, $productID);
        $this->dao->update(TABLE_DOCLIB)->set('deleted')->eq(1)->where('product')->eq($productID)->exec();
    }
}
