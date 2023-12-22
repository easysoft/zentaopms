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
     * Get product module menu.
     *
     * @param  array  $products
     * @param  int    $productID
     * @param  string $extra
     * @param  string $branch
     * @param  string $module
     * @param  string $moduleType
     *
     * @access public
     * @return string
     */
    public function getModuleNav($products, $productID, $extra, $branch, $module = 0, $moduleType = '')
    {
        $currentModule = $this->app->getModuleName();
        $currentMethod = $this->app->getMethodName();

        /* init currentModule and currentMethod for report and story. */
        if($currentModule == 'story')
        {
            $storyMethods = ",track,create,batchcreate,batchclose,";
            if(strpos($storyMethods, "," . $currentMethod . ",") === false) $currentModule = 'product';
            if($currentMethod == 'view' || $currentMethod == 'change' || $currentMethod == 'review') $currentMethod = 'browse';
        }
        if($currentMethod == 'report') $currentMethod = 'browse';

        $selectHtml = $this->select($products, $productID, $currentModule, $currentMethod, $extra, $branch, $module, $moduleType);

        $pageNav  = '';
        $isMobile = $this->app->viewType == 'mhtml';
        if($isMobile)
        {
            $pageNav  = html::a(helper::createLink('product', 'index'), $this->lang->product->index) . $this->lang->colon;
            $pageNav .= $selectHtml;
        }
        else
        {
            $pageNav = $selectHtml;
        }

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
    public function select($products, $productID, $currentModule, $currentMethod, $extra = '', $branch = '', $module = 0, $moduleType = '', $withBranch = true)
    {
        $isQaModule = (strpos(',project,execution,', ",{$this->app->tab},") !== false and strpos(',bug,testcase,testtask,ajaxselectstory,', ",{$this->app->rawMethod},") !== false and isset($products[0])) ? true : false;
        $isFeedbackModel = strpos(',feedback,', ",{$this->app->tab},") !== false ? true : false;
        if(count($products) <= 2 and isset($products[0]))
        {
            unset($products[0]);
            $productID = key($products);
        }

        if(empty($products)) return;

        if($this->app->tab == 'project' and strpos(',zeroCase,browseUnits,groupCase,', ",$currentMethod,") !== false) $isQaModule = true;

        $this->app->loadLang('product');
        if(!$isQaModule and !$productID and !$isFeedbackModel)
        {
            unset($this->lang->product->menu->settings['subMenu']->branch);
            return;
        }
        $isMobile = $this->app->viewType == 'mhtml';

        $productID = $productID == 'all' ? 0 : $productID;
        setcookie("lastProduct", $productID, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);
        if($productID) $currentProduct = $this->getById($productID);

        if($isQaModule and $this->app->tab == 'project')
        {
            if($this->app->tab == 'project')   $extra = strpos(',testcase,groupCase,zeroCase,', ",$currentMethod,") !== false ? $extra : $this->session->project;
            if($this->app->tab == 'execution') $extra = $this->session->execution;
        }

        if($isQaModule and !$productID)
        {
            $currentProduct = new stdclass();
            $currentProduct->name = $products[$productID];
            $currentProduct->type = 'normal';
        }
        if($isFeedbackModel and !$productID)
        {
            $currentProduct = new stdclass();
            $currentProduct->name = isset($products[$productID]) ? $products[$productID] : current($products);
            $currentProduct->type = 'normal';
        }
        $this->session->set('currentProductType', $currentProduct->type);

        $output = '';
        if(!empty($products))
        {
            $moduleName = 'product';
            if($isQaModule) $moduleName = 'bug';
            if($isFeedbackModel) $moduleName = 'feedback';
            $dropMenuLink = helper::createLink($moduleName, 'ajaxGetDropMenu', "objectID=$productID&module=$currentModule&method=$currentMethod&extra=$extra");
            $output  = "<div class='btn-group angle-btn'><div class='btn-group'><button data-toggle='dropdown' type='button' class='btn btn-limit' id='currentItem' title='{$currentProduct->name}' style='width: 90%'><span class='text'>{$currentProduct->name}</span> <span class='caret'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$dropMenuLink'>";
            $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
            $output .= "</div></div>";
            if($isMobile) $output = "<a id='currentItem' href=\"javascript:showSearchMenu('product', '$productID', '$currentModule', '$currentMethod', '$extra')\"><span class='text'>{$currentProduct->name}</span> <span class='icon-caret-down'></span></a><div id='currentItemDropMenu' class='hidden affix enter-from-bottom layer'></div>";

            if($currentProduct->type == 'normal' || !$withBranch) unset($this->lang->product->menu->settings['subMenu']->branch);
            if($currentProduct->type != 'normal' && $currentModule != 'programplan' && $withBranch)
            {
                $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$currentProduct->type]);
                $this->lang->product->menu->settings['subMenu']->branch = str_replace('@branch@', $this->lang->product->branch, $this->lang->product->menu->settings['subMenu']->branch);

                $branches   = $this->loadModel('branch')->getPairs($productID, 'all');
                $branchName = $branches[$branch];
                if(!$isMobile)
                {
                    $params       = explode(',', $extra);
                    $dropMenuLink = helper::createLink('branch', 'ajaxGetDropMenu', "objectID=$productID&branch=$branch&module=$currentModule&method=$currentMethod&extra={$params[0]}");
                    $output .= "<div class='btn-group'><button id='currentBranch' data-toggle='dropdown' type='button' class='btn btn-limit' title='{$branchName}' style='width: 90%'>{$branchName} <span class='caret'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$dropMenuLink'>";
                    $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
                    $output .= "</div></div>";
                }
                else
                {
                    $output .= "<a id='currentBranch' href=\"javascript:showSearchMenu('branch', '$productID', '$currentModule', '$currentMethod', '$extra')\">{$branchName} <span class='icon-caret-down'></span></a><div id='currentBranchDropMenu' class='hidden affix enter-from-bottom layer'></div>";
                }
            }

            if(!$isMobile) $output .= '</div>';
        }

        return $output;
    }

    /**
     * Save the product id user last visited to session.
     *
     * @param  int   $productID
     * @param  array $products
     * @access public
     * @return int
     */
    public function saveState($productID, $products)
    {
        if(defined('TUTORIAL')) return $productID;

        if($productID == 0 and $this->cookie->preProductID and isset($products[$this->cookie->preProductID])) $productID = $this->cookie->preProductID;
        if($productID == 0 and $this->session->product == '') $productID = (int)key($products);
        $this->session->set('product', (int)$productID, $this->app->tab);

        if(!isset($products[$this->session->product]))
        {
            $product = $this->getById($productID);

            if(empty($product) or $product->deleted == 1) $productID = (int)key($products);
            $this->session->set('product', (int)$productID, $this->app->tab);
            if($productID && strpos(",{$this->app->user->view->products},", ",{$productID},") === false)
            {
                $productID = (int)key($products);
                $this->session->set('product', (int)$productID, $this->app->tab);
                $this->accessDenied($this->lang->product->accessDenied);
            }
        }

        setcookie('preProductID', (int)$productID, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);

        if($this->cookie->preProductID != $this->session->product)
        {
            $this->cookie->set('preBranch', 0);
            setcookie('preBranch', 0, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);
        }
        return $this->session->product;
    }

    /**
     * Check privilege.
     *
     * @param  int    $product
     * @access public
     * @return bool
     */
    public function checkPriv($productID)
    {
        return !empty($productID) && ($this->app->user->admin || (strpos(",{$this->app->user->view->products},", ",{$productID},") !== false));
    }

    /**
     * Show accessDenied response.
     *
     * @param  string  $tips
     * @access private
     * @return void
     */
    public function accessDenied($tips)
    {
        if(defined('TUTORIAL')) return true;

        echo(js::alert($tips));

        $loginLink = $this->config->requestType == 'GET' ? "?{$this->config->moduleVar}=user&{$this->config->methodVar}=login" : "user{$this->config->requestFix}login";

        echo js::locate(helper::createLink('product', 'all'));
    }

    /**
     * Get product by id.
     *
     * @param  int    $productID
     * @access public
     * @return object
     */
    public function getById($productID)
    {
        $productID = (int)$productID;
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getProduct();
        $product = $this->dao->findById($productID)->from(TABLE_PRODUCT)->fetch();
        if(!$product) return false;

        return $this->loadModel('file')->replaceImgURL($product, 'desc');
    }

    /**
     * Get by idList.
     *
     * @param  array    $productIDList
     * @access public
     * @return array
     */
    public function getByIdList($productIDList)
    {
        return $this->dao->select('*')->from(TABLE_PRODUCT)->where('id')->in($productIDList)->fetchAll('id');
    }

    /**
     * Get products.
     *
     * @param  int        $programID
     * @param  string     $mode         all|noclosed|involved|review|feedback
     * @param  int        $limit
     * @param  int        $line
     * @param  string|int $shadow       all | 0 | 1
     * @param  string     $fields       * or fieldList, such as id,name,program
     * @access public
     * @return array
     */
    public function getList($programID = 0, $mode = 'all', $limit = 0, $line = 0, $shadow = 0, $fields = '*')
    {
        $fields = explode(',', $fields);
        $fields = trim(implode(',t1.', $fields), ',');

        $products = $this->dao->select("DISTINCT t1.$fields,t2.order")->from(TABLE_PRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROGRAM)->alias('t2')->on('t1.program = t2.id')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t3')->on('t3.product = t1.id')
            ->leftJoin(TABLE_TEAM)->alias('t4')->on("t4.root = t3.project and t4.type='project'")
            ->where('t1.deleted')->eq(0)
            ->beginIF($shadow !== 'all')->andWhere('t1.shadow')->eq((int)$shadow)->fi()
            ->beginIF($programID)->andWhere('t1.program')->eq($programID)->fi()
            ->beginIF($line > 0)->andWhere('t1.line')->eq($line)->fi()
            ->beginIF(strpos($mode, 'feedback') === false && !$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->products)->fi()
            ->andWhere("CONCAT(',', t1.vision, ',')")->like("%,{$this->config->vision},%")->fi()
            ->beginIF(strpos($mode, 'noclosed') !== false)->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF(in_array($mode, array_keys($this->lang->story->statusList)))->andWhere('t1.status')->in($mode)->fi()
            ->beginIF($mode == 'involved')
            ->andWhere('t1.PO', true)->eq($this->app->user->account)
            ->orWhere('t1.QD')->eq($this->app->user->account)
            ->orWhere('t1.RD')->eq($this->app->user->account)
            ->orWhere('t1.createdBy')->eq($this->app->user->account)
            ->orWhere('t4.account')->eq($this->app->user->account)
            ->markRight(1)
            ->fi()
            ->beginIF($mode == 'review')
            ->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.reviewers)")
            ->andWhere('t1.reviewStatus')->eq('doing')
            ->fi()
            ->orderBy('t2.order_asc, t1.line_desc, t1.order_asc')
            ->beginIF($limit > 0)->limit($limit)->fi()
            ->fetchAll('id');

        return $products;
    }

    /**
     * Get list by search.
     *
     * @param  int    $queryID
     * @access public
     * @return array
     */
    public function getListBySearch($queryID = 0)
    {
        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('productQuery', $query->sql);
                $this->session->set('productForm', $query->form);
            }
            else
            {
                $this->session->set('productQuery', ' 1 = 1');
            }
        }
        else
        {
            if($this->session->productQuery == false) $this->session->set('productQuery', ' 1 = 1');
        }

        $productQuery = $this->session->productQuery;
        $productQuery = preg_replace('/`(\w+)`/', 't1.`$1`', $productQuery);

        $products = $this->dao->select('t1.id as id,t1.*')->from(TABLE_PRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROGRAM)->alias('t2')->on('t1.program = t2.id')
            ->where($productQuery)
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.shadow')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('t1.id')->in($this->app->user->view->products)->fi()
            ->andWhere("FIND_IN_SET('{$this->config->vision}', t1.vision)")
            ->orderBy('t1.order_asc')
            ->fetchAll('id');

        return $products;
    }

    /**
     * Get product pairs.
     *
     * @param  string       $mode
     * @param  string       $programID
     * @param  string|array $append
     * @param  string|int   $shadow         all | 0 | 1
     * @return array
     */
    public function getPairs($mode = '', $programID = 0, $append = '', $shadow = 0)
    {
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getProductPairs();

        if(!empty($append) and is_array($append)) $append = implode(',', $append);

        $views   = empty($append) ? $this->app->user->view->products : $this->app->user->view->products . ",$append";
        $orderBy = !empty($this->config->product->orderBy) ? $this->config->product->orderBy : 'isClosed';
        /* Order by program. */
        return $this->dao->select("t1.*,  IF(INSTR(' closed', t1.status) < 2, 0, 1) AS isClosed")->from(TABLE_PRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROGRAM)->alias('t2')->on('t1.program = t2.id')
            ->where('t1.deleted')->eq(0)
            ->beginIF($programID)->andWhere('t1.program')->eq($programID)->fi()
            ->beginIF(strpos($mode, 'noclosed') !== false)->andWhere('t1.status')->ne('closed')->fi()
            ->beginIF(!$this->app->user->admin && $this->config->vision != 'lite' && strpos($mode, 'all') === false)->andWhere('t1.id')->in($views)->fi()
            ->beginIF($shadow !== 'all')->andWhere('t1.shadow')->eq((int)$shadow)->fi()
            ->beginIF($this->config->vision != 'or')->andWhere("FIND_IN_SET('{$this->config->vision}', t1.vision)")->fi()
            ->orderBy("$orderBy, t2.order_asc, t1.line_desc, t1.order_asc")
            ->fetchPairs('id', 'name');
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
    public function getProductPairsByProject($projectID = 0, $status = 'all', $append = '', $noDeleted = true)
    {
        $products = empty($projectID) ? $this->getList(0, 'all', 0, 0, 'all', 'id,name,deleted') : $this->getProducts($projectID, $status, '', true, $append, $noDeleted);
        $pairs    = array();
        if(!empty($products))
        {
            foreach($products as $product) $pairs[$product->id] = $product->deleted ? $product->name . "({$this->lang->product->deleted})" : $product->name;
        }

        return $pairs;
    }

    /**
     * Get product pairs by project model.
     *
     * @param  string $model all|scrum|waterfall
     * @access public
     * @return array
     */
    public function getPairsByProjectModel($model)
    {
        return $this->dao->select('t3.id as id, t3.name as name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product=t3.id')
            ->where('t3.deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('t3.id')->in($this->app->user->view->products)->fi()
            ->andWhere("FIND_IN_SET('{$this->config->vision}', t3.vision)")
            ->beginIF($model != 'all')->andWhere('t2.model')->eq($model)
            ->fetchPairs('id', 'name');
    }

    /**
     * Get products by project.
     *
     * @param  int          $projectID
     * @param  string       $status         all|noclosed
     * @param  string       $orderBy
     * @param  bool         $withBranch
     * @param  string|array $append
     * @param  bool         $noDeleted
     * @access public
     * @return array
     */
    public function getProducts($projectID = 0, $status = 'all', $orderBy = '', $withBranch = true, $append = '', $noDeleted = true)
    {
        if(defined('TUTORIAL'))
        {
            if(!$withBranch) return $this->loadModel('tutorial')->getProductPairs();
            return $this->loadModel('tutorial')->getExecutionProducts();
        }

        if(is_string($projectID) and strpos($projectID, 'all') !== false) $projectID = str_replace('all', 0, $projectID);

        if(!empty($append) and is_array($append)) $append = implode(',', $append);

        $views           = empty($append) ? $this->app->user->view->products : $this->app->user->view->products . ",$append";
        $projectProducts = $this->dao->select("t1.branch, t1.plan, t2.*")
            ->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->where('1=1')
            ->beginIF($noDeleted)->andWhere('t2.deleted')->eq(0)->fi()
            ->beginIF(!empty($projectID))->andWhere('t1.project')->in($projectID)->fi()
            ->beginIF(!$this->app->user->admin and $this->config->vision != 'lite')->andWhere('t2.id')->in($views)->fi()
            ->andWhere("FIND_IN_SET('{$this->config->vision}', t2.vision)")
            ->beginIF(strpos($status, 'noclosed') !== false)->andWhere('t2.status')->ne('closed')->fi()
            ->orderBy($orderBy . 't2.order asc')
            ->fetchAll();

        $products = array();
        foreach($projectProducts as $product)
        {
            if(!$withBranch)
            {
                $products[$product->id] = $product->name;
                continue;
            }

            if(!isset($products[$product->id]))
            {
                $products[$product->id] = $product;
                $products[$product->id]->branches = array();
                $products[$product->id]->plans    = array();
            }
            $products[$product->id]->branches[$product->branch] = $product->branch;
            if($product->plan) $products[$product->id]->plans[$product->plan] = $product->plan;

            unset($product->branch);
            unset($product->plan);
        }

        return $products;
    }

    /**
     * Get product id by project.
     *
     * @param  int    $projectID
     * @param  bool   $isFirst
     * @access public
     * @return array
     */
    public function getProductIDByProject($projectID, $isFirst = true)
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
     * @return object
     */
    public function getShadowProductByProject($projectID)
    {
        return $this->dao->select('products.*')->from(TABLE_PRODUCT)->alias('products')
            ->leftJoin(TABLE_PROJECTPRODUCT)->alias('relations')->on('products.id = relations.product')
            ->where('products.shadow')->eq(1)
            ->andWhere('relations.project')->eq($projectID)
            ->fetch();
    }

    /**
     * Get grouped products.
     *
     * @access public
     * @return void
     */
    public function getStatusGroups()
    {
        $products = $this->dao->select('id, name, status')->from(TABLE_PRODUCT)->where('deleted')->eq(0)->fetchGroup('status');
    }

    /**
     * Get ordered products.
     *
     * @param  string     $status
     * @param  int        $num
     * @param  int        $projectID
     * @param  string|int $shadow       all | 0 | 1
     * @access public
     * @return array
     */
    public function getOrderedProducts($status, $num = 0, $projectID = 0, $shadow = 0)
    {
        $products = array();
        if($projectID)
        {
            $pairs    = $this->getProducts($projectID, $status == 'normal' ? 'noclosed' : '');
            $products = $this->getByIdList(array_keys($pairs));
        }
        else
        {
            $products = $this->getList('', $status, $num, 0, $shadow);
        }

        if(empty($products)) return $products;

        $lines       = $this->getLinePairs();
        $productList = array();

        foreach($lines as $id => $name)
        {
            foreach($products as $key => $product)
            {
                if($product->line == $id)
                {
                    if(in_array($this->config->systemMode, array('ALM', 'PLM'))) $product->name = $name . '/' . $product->name;
                    $productList[] = $product;
                    unset($products[$key]);
                }
            }
        }

        $productList = array_merge($productList, $products);
        $products    = $mineProducts = $otherProducts = $closedProducts = array();
        foreach($productList as $product)
        {
            if(!$this->app->user->admin and !$this->checkPriv($product->id)) continue;
            if($product->status == 'normal' and $product->PO == $this->app->user->account)
            {
                $mineProducts[$product->id] = $product;
            }
            elseif($product->status == 'normal' and $product->PO != $this->app->user->account)
            {
                $otherProducts[$product->id] = $product;
            }
            elseif($product->status == 'closed')
            {
                $closedProducts[$product->id] = $product;
            }
        }
        $products = $mineProducts + $otherProducts + $closedProducts;

        if(empty($num)) return $products;
        return array_slice($products, 0, $num, true);
    }

    /**
     * Get Multi-branch product pairs.
     *
     * @param  int    $programID
     * @access public
     * @return array
     */
    public function getMultiBranchPairs($programID = 0)
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
     * @param  array  $appendIDList
     * @access public
     * @return array
     */
    public function getProductsGroupByProgram($appendIDList = array())
    {
        $views = $this->app->user->view->products;
        if(!empty($appendIDList)) $views .= ',' . implode(',', $appendIDList);

        $products = $this->dao->select("t1.id, t1.name, t1.program, t2.name AS programName")->from(TABLE_PRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.program = t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere("FIND_IN_SET('{$this->config->vision}', t1.vision)")
            ->andWhere('t1.shadow')->eq((int)0)
            ->andWhere('t1.status')->ne('closed')
            ->beginIF(!$this->app->user->admin)->andWhere('t1.id')->in($views)->fi()
            ->orderBy('program')
            ->fetchGroup('program');

        $productsGroupByProgram = array();
        foreach($products as $program => $programProducts)
        {
            foreach($programProducts as $product) $productsGroupByProgram[$program][$product->id] = $product->programName . '/' . $product->name;
        }

        return $productsGroupByProgram;
    }

    /*
     * Get product switcher.
     *
     * @param  int         $productID
     * @param  string      $extra
     * @param  int|string  $branch
     * @access public
     * @return void
     */
    public function getSwitcher($productID = 0, $extra = '', $branch = '')
    {
        $currentModule = $this->app->moduleName;
        $currentMethod = $this->app->methodName;

        /* Init currentModule and currentMethod for report and story. */
        if($currentModule == 'story')
        {
            $storyMethods = ",create,batchcreate,batchclose,";
            if(strpos($storyMethods, "," . $currentMethod . ",") === false) $currentModule = 'product';
            if($currentMethod == 'view' or $currentMethod == 'change' or $currentMethod == 'review' or $currentMethod == 'edit') $currentMethod = 'browse';
        }
        if($currentModule == 'testcase' and strpos(',view,edit,', ",$currentMethod,") !== false) $currentMethod = 'browse';
        if($currentModule == 'bug' and $currentMethod == 'edit') $currentMethod = 'browse';
        if($currentMethod == 'report') $currentMethod = 'browse';

        $currentProductName = $this->lang->productCommon;
        if($productID)
        {
            $currentProduct     = $this->getById($productID);
            $currentProductName = $currentProduct->name;
            $this->session->set('currentProductType', $currentProduct->type);
        }

        $fromModule   = $this->app->tab == 'qa' ? 'qa' : '';
        $dropMenuLink = helper::createLink($this->app->tab == 'qa' ? 'product' : $this->app->tab, 'ajaxGetDropMenu', "objectID=$productID&module=$currentModule&method=$currentMethod&extra=$extra&from=$fromModule");

        if($this->app->viewType == 'mhtml' and $productID) return $this->getModuleNav(array($productID => $currentProductName), $productID, $extra, $branch);

        $output  = "<div class='btn-group header-btn' id='swapper'><button data-toggle='dropdown' type='button' class='btn' id='currentItem' title='{$currentProductName}'><span class='text'>{$currentProductName}</span> <span class='caret' style='margin-bottom: -1px'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$dropMenuLink'>";
        $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
        $output .= "</div></div>";

        $notNormalProduct = (isset($currentProduct->type) and $currentProduct->type != 'normal');
        if($notNormalProduct)
        {
            $isShowBranch = false;
            if($currentModule == 'product' and $currentMethod == 'track') $isShowBranch = true;
            if($currentModule == 'tree' and $currentMethod == 'browse') $isShowBranch = true;
            if($currentModule == 'product' and strpos($this->config->product->showBranchMethod, $currentMethod) !== false) $isShowBranch = true;
            if($this->app->tab == 'qa' and strpos(',testsuite,testreport,testtask,', ",$currentModule,") === false) $isShowBranch = true;
            if($this->app->tab == 'qa' and $currentModule == 'testtask' and strpos(',create,edit,browseunits,importunitresult,unitcases,', ",$currentMethod,") === false) $isShowBranch = true;
            if($currentModule == 'testcase' and $currentMethod == 'showimport') $isShowBranch = false;
            if($currentModule == 'release' and strpos(',browse,create,', $currentMethod) !== false) $isShowBranch = true;
            if($isShowBranch)
            {
                $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$currentProduct->type]);
                $branches     = $this->loadModel('branch')->getPairs($productID, 'all');
                $branchName   = isset($branches[$branch]) ? $branches[$branch] : $branches[0];
                $dropMenuLink = helper::createLink('branch', 'ajaxGetDropMenu', "objectID=$productID&branch=$branch&module=$currentModule&method=$currentMethod&extra=$extra");

                $output .= "<div class='btn-group header-btn'><button id='currentBranch' data-toggle='dropdown' type='button' class='btn'><span class='text'>{$branchName}</span> <span class='caret' style='margin-bottom: -1px'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$dropMenuLink'>";
                $output .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
                $output .= "</div></div>";
            }
        }

        return $output;
    }

    /**
     * Create a product.
     *
     * @access public
     * @return int
     */
    public function create()
    {
        $product = fixer::input('post')
            ->callFunc('name', 'trim')
            ->setDefault('status', 'normal')
            ->setDefault('PMT', '')
            ->setDefault('line', 0)
            ->setDefault('createdBy', $this->app->user->account)
            ->setDefault('createdDate', helper::now())
            ->setDefault('createdVersion', $this->config->version)
            ->setDefault('vision', $this->config->vision)
            ->cleanINT('program,line')
            ->setIF($this->post->acl == 'open', 'whitelist', '')
            ->setIF(!isset($_POST['whitelist']), 'whitelist', '')
            ->stripTags($this->config->product->editor->create['id'], $this->config->allowedTags)
            ->join('whitelist', ',')
            ->join('reviewer', ',')
            ->join('PMT', ',')
            ->remove('uid,newLine,lineName,contactListMenu')
            ->get();

        $this->lang->error->unique = $this->lang->error->repeat;
        $product = $this->loadModel('file')->processImgURL($product, $this->config->product->editor->create['id'], $this->post->uid);

        /* Lean mode relation defaultProgram. */
        $programID = isset($product->program) ? (int)$product->program : 0;
        if($this->config->systemMode == 'light')
        {
            $programID = $this->config->global->defaultProgram;
            $product->program = $this->config->global->defaultProgram;
        }

        $this->dao->insert(TABLE_PRODUCT)->data($product)->autoCheck()
            ->batchCheck($this->config->product->create->requiredFields, 'notempty')
            ->checkIF(!empty($product->name), 'name', 'unique', "`program` = $programID and `deleted` = '0'")
            ->checkIF(!empty($product->code), 'code', 'unique', "`deleted` = '0'")
            ->checkFlow()
            ->exec();

        if(!dao::isError())
        {
            $productID = $this->dao->lastInsertID();

            if(!empty($_POST['lineName']))
            {
                /* Create product line. */
                $maxOrder = $this->dao->select("max(`order`) as maxOrder")->from(TABLE_MODULE)->where('type')->eq('line')->fetch('maxOrder');
                $maxOrder = $maxOrder ? $maxOrder + 10 : 0;

                $line = new stdClass();
                $line->type   = 'line';
                $line->parent = 0;
                $line->grade  = 1;
                $line->name   = $this->post->lineName;
                $line->root   = in_array($this->config->systemMode, array('ALM', 'PLM')) ? $product->program : 0;
                $line->order  = $maxOrder;

                $lines = $this->dao->select('name')->from(TABLE_MODULE)->where('type')->eq('line')->andWhere('root')->eq($line->root)->andWhere('name')->eq($line->name)->fetch();
                if(!empty($lines))
                {
                    dao::$errors['lineName'] = sprintf($this->lang->product->nameIsDuplicated, $line->name);
                    return false;
                }
                $this->dao->insert(TABLE_MODULE)->data($line)->exec();

                if(!dao::isError())
                {
                    $lineID = $this->dao->lastInsertID();
                    $path   = ",$lineID,";

                    $this->dao->update(TABLE_MODULE)->set('path')->eq($path)->where('id')->eq($lineID)->exec();

                    $this->dao->update(TABLE_PRODUCT)->set('line')->eq($lineID)->where('id')->eq($productID)->exec();
                }
            }

            $this->file->updateObjectID($this->post->uid, $productID, 'product');
            $this->dao->update(TABLE_PRODUCT)->set('`order`')->eq($productID * 5)->where('id')->eq($productID)->exec();

            $whitelist = explode(',', $product->whitelist);
            $this->loadModel('personnel')->updateWhitelist($whitelist, 'product', $productID);
            if($product->acl != 'open') $this->loadModel('user')->updateUserView($productID, 'product');

            /* Create doc lib. */
            $this->app->loadLang('doc');
            $lib = new stdclass();
            $lib->product   = $productID;
            $lib->name      = $this->lang->doclib->main['product'];
            $lib->type      = 'product';
            $lib->main      = '1';
            $lib->acl       = 'default';
            $lib->vision    = $this->config->vision;
            $lib->addedBy   = $this->app->user->account;
            $lib->addedDate = helper::now();
            $this->dao->insert(TABLE_DOCLIB)->data($lib)->exec();

            $productSettingList = isset($this->config->global->productSettingList) ? json_decode($this->config->global->productSettingList, true) : array();
            if($productSettingList)
            {
                $productSettingList[] = $productID;
                $this->loadModel('setting')->setItem('system.common.global.productSettingList', json_encode($productSettingList));
            }

            return $productID;
        }
    }

    /**
     * Update a product.
     *
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function update($productID)
    {
        $productID  = (int)$productID;
        $oldProduct = $this->dao->findById($productID)->from(TABLE_PRODUCT)->fetch();

        $product = fixer::input('post')
            ->add('id', $productID)
            ->callFunc('name', 'trim')
            ->setDefault('line', 0)
            ->setDefault('whitelist', '')
            ->setDefault('reviewer', '')
            ->setDefault('PMT', '')
            ->cleanINT('program,line')
            ->join('whitelist', ',')
            ->join('reviewer', ',')
            ->join('PMT', ',')
            ->stripTags($this->config->product->editor->edit['id'], $this->config->allowedTags)
            ->remove('uid,changeProjects,contactListMenu')
            ->get();

        $this->lang->error->unique = $this->lang->error->repeat;
        $product   = $this->loadModel('file')->processImgURL($product, $this->config->product->editor->edit['id'], $this->post->uid);
        $programID = isset($product->program) ? $product->program : $oldProduct->program;
        $this->dao->update(TABLE_PRODUCT)->data($product)->autoCheck()
            ->batchCheck($this->config->product->edit->requiredFields, 'notempty')
            ->checkIF(!empty($product->name), 'name', 'unique', "id != $productID and `program` = $programID and `deleted` = '0'")
            ->checkIF(!empty($product->code), 'code', 'unique', "id != $productID and `deleted` = '0'")
            ->checkFlow()
            ->where('id')->eq($productID)
            ->exec();

        if(!dao::isError())
        {
            $this->file->updateObjectID($this->post->uid, $productID, 'product');
            $whitelist = explode(',', $product->whitelist);
            $this->loadModel('personnel')->updateWhitelist($whitelist, 'product', $productID);
            if($product->acl != 'open') $this->loadModel('user')->updateUserView($productID, 'product');
            if($product->type == 'normal' and $oldProduct->type != 'normal') $this->loadModel('branch')->unlinkBranch4Project($productID);
            if($product->type != 'normal' and $oldProduct->type == 'normal') $this->loadModel('branch')->linkBranch4Project($productID);

            return common::createChanges($oldProduct, $product);
        }
    }

    /**
     * Batch update products.
     *
     * @access public
     * @return array
     */
    public function batchUpdate()
    {
        $products    = array();
        $allChanges  = array();
        $data        = fixer::input('post')->get();
        $oldProducts = $this->getByIdList($this->post->productIDList);
        $nameList    = array();

        $extendFields = $this->getFlowExtendFields();
        foreach($data->productIDList as $productID)
        {
            $productName = $data->names[$productID];

            $productID = (int)$productID;
            $products[$productID] = new stdClass();
            if(in_array($this->config->systemMode, array('ALM', 'PLM')) and isset($data->programs[$productID])) $products[$productID]->program = (int)$data->programs[$productID];
            if(in_array($this->config->systemMode, array('ALM', 'PLM')) and isset($data->lines[$productID]))    $products[$productID]->line    = (int)$data->lines[$productID];
            $products[$productID]->name    = $productName;
            $products[$productID]->PO      = $data->POs[$productID];
            $products[$productID]->QD      = $data->QDs[$productID];
            $products[$productID]->RD      = $data->RDs[$productID];
            $products[$productID]->type    = $data->types[$productID];
            $products[$productID]->status  = $data->statuses[$productID];
            $products[$productID]->desc    = strip_tags($this->post->descs[$productID], $this->config->allowedTags);
            $products[$productID]->acl     = $data->acls[$productID];
            $products[$productID]->id      = $productID;

            foreach($extendFields as $extendField)
            {
                $products[$productID]->{$extendField->field} = $this->post->{$extendField->field}[$productID];
                if(is_array($products[$productID]->{$extendField->field})) $products[$productID]->{$extendField->field} = join(',', $products[$productID]->{$extendField->field});

                $products[$productID]->{$extendField->field} = htmlSpecialString($products[$productID]->{$extendField->field});
            }
        }
        if(dao::isError()) return print(js::error(dao::getError()));

        $unlinkProducts = array();
        $linkProducts   = array();
        $this->lang->error->unique = $this->lang->error->repeat;
        foreach($products as $productID => $product)
        {
            $oldProduct = $oldProducts[$productID];
            if(in_array($this->config->systemMode, array('ALM', 'PLM'))) $programID  = !isset($product->program) ? $oldProduct->program : zget($product, 'program', 0);

            $this->dao->update(TABLE_PRODUCT)
                ->data($product)
                ->autoCheck()
                ->batchCheck($this->config->product->edit->requiredFields , 'notempty')
                ->checkIF((!empty($product->name) and in_array($this->config->systemMode, array('ALM', 'PLM'))), 'name', 'unique', "id != $productID and `program` = $programID and `deleted` = '0'")
                ->checkFlow()
                ->where('id')->eq($productID)
                ->exec();
            if(dao::isError()) return print(js::error('product#' . $productID . dao::getError(true)));

            /* When acl is open, white list set empty. When acl is private,update user view. */
            if($product->acl == 'open') $this->loadModel('personnel')->updateWhitelist(array(), 'product', $productID);
            if($product->acl != 'open') $this->loadModel('user')->updateUserView($productID, 'product');
            if($product->type == 'normal' and $oldProduct->type != 'normal') $unlinkProducts[] = $productID;
            if($product->type != 'normal' and $oldProduct->type == 'normal') $linkProducts[] = $productID;

            $allChanges[$productID] = common::createChanges($oldProduct, $product);
        }

        if(!empty($unlinkProducts)) $this->loadModel('branch')->unlinkBranch4Project($unlinkProducts);
        if(!empty($linkProducts)) $this->loadModel('branch')->linkBranch4Project($linkProducts);

        return $allChanges;
    }

    /**
     * Close product.
     *
     * @param  int    $productID.
     * @access public
     * @return void
     */
    public function close($productID)
    {
        $oldProduct = $this->getById($productID);
        $product    = fixer::input('post')
            ->add('id', $productID)
            ->setDefault('status', 'closed')
            ->setDefault('closedDate', helper::today())
            ->stripTags($this->config->product->editor->close['id'], $this->config->allowedTags)
            ->remove('comment')
            ->get();

        $product = $this->loadModel('file')->processImgURL($product, $this->config->product->editor->close['id'], $this->post->uid);
        $this->dao->update(TABLE_PRODUCT)->data($product)
            ->autoCheck()
            ->checkFlow()
            ->where('id')->eq((int)$productID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldProduct, $product);
    }

    /**
     * Activate a product.
     *
     * @param  int    $productID.
     * @access public
     * @return bool | array
     */
    public function activate($productID)
    {
        $oldProduct = $this->getById($productID);
        $product    = (object)array('status' => 'normal');
        if($this->config->vision == 'or') $product->status = 'wait';

        $this->dao->update(TABLE_PRODUCT)->data($product)->where('id')->eq((int)$productID)->exec();

        if(dao::isError()) return false;

        return common::createChanges($oldProduct, $product);
    }

    /**
     * Manage line.
     *
     * @access public
     * @return void
     */
    public function manageLine()
    {
        $oldLines = $this->getLines();
        $data     = fixer::input('post')->get();

        /* When there are products under the line, the program cannot be modified  */
        if(in_array($this->config->systemMode, array('ALM', 'PLM')))
        {
            foreach($oldLines as $oldLine)
            {
                $oldLineID = 'id' . $oldLine->id;
                if($data->programs[$oldLineID] != $oldLine->root)
                {
                    $product = $this->dao->select('*')->from(TABLE_PRODUCT)->where('line')->eq($oldLine->id)->fetch();
                    if(!empty($product)) return print(js::error($this->lang->product->changeLineError));
                }
            }
        }

        $line = new stdClass();
        $line->type   = 'line';
        $line->parent = 0;
        $line->grade  = 1;

        $maxOrder = $this->dao->select("max(`order`) as maxOrder")->from(TABLE_MODULE)->where('type')->eq('line')->fetch('maxOrder');
        $maxOrder = $maxOrder ? $maxOrder : 0;

        $lines = array();
        foreach($data->modules as $id => $name)
        {
            if(empty($name)) continue;
            if(in_array($this->config->systemMode, array('ALM', 'PLM')) and empty($data->programs[$id]))
            {
                dao::$errors[] = $this->lang->product->programEmpty;
                return false;
            }

            $programID = $data->programs[$id];
            if(!isset($lines[$programID])) $lines[$programID] = array();
            if(in_array($name, $lines[$programID]))
            {
                dao::$errors[] = sprintf($this->lang->product->nameIsDuplicate, $name);
                return false;
            }
            $lines[$programID][] = $name;
        }

        foreach($data->modules as $id => $name)
        {
            if(empty($name)) continue;

            $line->name = strip_tags(trim($name));
            $line->root = $data->programs[$id];

            if(is_numeric($id))
            {
                $maxOrder += 10;
                $line->order = $maxOrder;

                $this->dao->insert(TABLE_MODULE)->data($line)->exec();
                $lineID = $this->dao->lastInsertID();
                $path   = ",$lineID,";
                $this->dao->update(TABLE_MODULE)->set('path')->eq($path)->where('id')->eq($lineID)->exec();
            }
            else
            {
                $lineID = str_replace('id', '', $id);
                $this->dao->update(TABLE_MODULE)->data($line)->where('id')->eq($lineID)->exec();
            }
        }
    }

    /**
     * Get stories.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $browseType
     * @param  int    $queryID
     * @param  int    $moduleID
     * @param  string $type requirement|story
     * @param  string $sort
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getStories($productID, $branch, $browseType, $queryID, $moduleID, $type = 'story', $sort = 'id_desc', $pager = null)
    {
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getStories();

        $this->loadModel('story');

        /* Set modules and browse type. */
        $modules    = $moduleID ? $this->loadModel('tree')->getAllChildID($moduleID) : '0';

        $browseType = $browseType == 'bybranch' ? 'bymodule' : $browseType;
        $browseType = ($browseType == 'bymodule' and $this->session->storyBrowseType and $this->session->storyBrowseType != 'bysearch') ? $this->session->storyBrowseType : $browseType;

        /* Get stories by browseType. */
        $stories = array();
        if($browseType == 'unclosed')
        {
            $unclosedStatus = $this->lang->story->statusList;
            unset($unclosedStatus['closed']);
            $stories = $this->story->getProductStories($productID, $branch, $modules, array_keys($unclosedStatus), $type, $sort, true, '', $pager);
        }
        if($browseType == 'unplan')         $stories = $this->story->getByPlan($productID, $queryID, $modules, '', $type, $sort, $pager);
        if($browseType == 'allstory')       $stories = $this->story->getProductStories($productID, $branch, $modules, 'all', $type, $sort, true, '', $pager);
        if($browseType == 'bymodule')       $stories = $this->story->getProductStories($productID, $branch, $modules, 'all', $type, $sort, true, '', $pager);
        if($browseType == 'bysearch')       $stories = $this->story->getBySearch($productID, $branch, $queryID, $sort, '', $type, '', '', $pager);
        if($browseType == 'assignedtome')   $stories = $this->story->getByAssignedTo($productID, $branch, $modules, $this->app->user->account, $type, $sort, $pager);
        if($browseType == 'openedbyme')     $stories = $this->story->getByOpenedBy($productID, $branch, $modules, $this->app->user->account, $type, $sort, $pager);
        if($browseType == 'reviewedbyme')   $stories = $this->story->getByReviewedBy($productID, $branch, $modules, $this->app->user->account, $type, $sort, $pager);
        if($browseType == 'reviewbyme')     $stories = $this->story->getByReviewBy($productID, $branch, $modules, $this->app->user->account, $type, $sort, $pager);
        if($browseType == 'closedbyme')     $stories = $this->story->getByClosedBy($productID, $branch, $modules, $this->app->user->account, $type, $sort, $pager);
        if($browseType == 'draftstory')     $stories = $this->story->getByStatus($productID, $branch, $modules, 'draft', $type, $sort, $pager);
        if($browseType == 'activestory')    $stories = $this->story->getByStatus($productID, $branch, $modules, 'active', $type, $sort, $pager);
        if($browseType == 'changingstory')  $stories = $this->story->getByStatus($productID, $branch, $modules, 'changing', $type, $sort, $pager);
        if($browseType == 'reviewingstory') $stories = $this->story->getByStatus($productID, $branch, $modules, 'reviewing', $type, $sort, $pager);
        if($browseType == 'launchedstory')  $stories = $this->story->getByStatus($productID, $branch, $modules, 'launched', $type, $sort, $pager);
        if($browseType == 'developingstory')$stories = $this->story->getByStatus($productID, $branch, $modules, 'developing', $type, $sort, $pager);
        if($browseType == 'willclose')      $stories = $this->story->get2BeClosed($productID, $branch, $modules, $type, $sort, $pager);
        if($browseType == 'closedstory')    $stories = $this->story->getByStatus($productID, $branch, $modules, 'closed', $type, $sort, $pager);
        if($browseType == 'assignedbyme')   $stories = $this->story->getByAssignedBy($productID, $branch, $modules, $this->app->user->account, $type, $sort, $pager);
        if($browseType == 'fromfeedback')   $stories = $this->story->getFeedbackStories($productID, $branch, $modules, $type, $sort, $pager);

        return $stories;
    }

    /**
     * Batch get story stage.
     *
     * @param  array  $stories.
     * @access public
     * @return array
     */
    public function batchGetStoryStage($stories)
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
     * @param  int    $branch
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function buildSearchForm($productID, $products, $queryID, $actionURL, $branch = 0, $projectID = 0)
    {
        $productIdList = ($this->app->tab == 'project' and empty($productID)) ? array_keys($products) : $productID;
        $branchParam   = ($this->app->tab == 'project' and empty($productID)) ? '' : $branch;
        $projectID     = ($this->app->tab == 'project' and empty($projectID)) ? $this->session->project : $projectID;

        $this->config->product->search['actionURL'] = $actionURL;
        $this->config->product->search['queryID']   = $queryID;
        $this->config->product->search['params']['plan']['values'] = $this->loadModel('productplan')->getPairs($productIdList, (empty($branchParam) or $branchParam == 'all') ? '' : $branchParam);

        $product = ($this->app->tab == 'project' and empty($productID)) ? $products : array();
        if(empty($product) and isset($products[$productID])) $product = array($productID => $products[$productID]);

        $this->config->product->search['params']['product']['values'] = $product + array('all' => $this->lang->product->allProduct);

        $this->config->product->search['params']['stage']['values'] = array('' => '') + $this->lang->story->stageList;

        /* Get modules. */
        $this->loadModel('tree');
        if($this->app->tab == 'project')
        {
            if($productID)
            {
                $modules          = array();
                $branchList       = $this->loadModel('branch')->getPairs($productID, '', $projectID);
                $branchModuleList = $this->tree->getOptionMenu($productID, 'story', 0, array_keys($branchList));
                foreach($branchModuleList as $branchID => $branchModules) $modules = array_merge($modules, $branchModules);
            }
            else
            {
                $moduleList  = array();
                $modules     = array('/');
                $branchGroup = $this->loadModel('execution')->getBranchByProduct(array_keys($products), $projectID, '');
                foreach($products as $productID => $productName)
                {
                    if(isset($branchGroup[$productID]))
                    {
                        $branchModuleList = $this->tree->getOptionMenu($productID, 'story', 0, array_keys($branchGroup[$productID]));
                        foreach($branchModuleList as $branchID => $branchModules)
                        {
                            if(is_array($branchModules)) $moduleList += $branchModules;
                        }
                    }
                    else
                    {
                        $moduleList = $this->tree->getOptionMenu($productID, 'story', 0, $branch);
                    }

                    foreach($moduleList as $moduleID => $moduleName)
                    {
                        if(empty($moduleID)) continue;
                        $modules[$moduleID] = $productName . $moduleName;
                    }
                }
            }
        }
        else
        {
            $modules = $this->tree->getOptionMenu($productID, 'story', 0, $branch);
        }
        $this->config->product->search['params']['module']['values'] = array('' => '') + $modules;

        $productInfo   = $this->getById($productID);

        if(!$productID or $productInfo->type == 'normal' or $this->app->tab == 'assetlib')
        {
            unset($this->config->product->search['fields']['branch']);
            unset($this->config->product->search['params']['branch']);
        }
        else
        {
            $this->config->product->search['fields']['branch'] = sprintf($this->lang->product->branch, $this->lang->product->branchName[$productInfo->type]);
            $this->config->product->search['params']['branch']['values']  = array('' => '', '0' => $this->lang->branch->main) + $this->loadModel('branch')->getPairs($productID, 'noempty');
        }

        if(!empty($productInfo->shadow)) unset($this->config->product->search['fields']['product']);

        $this->loadModel('search')->setSearchParams($this->config->product->search);
    }

    /**
     * Build search form for all method of product module.
     *
     * @param  int       $queryID
     * @param  string    $actionURL
     * @access public
     * @return void
     */
    public function buildProductSearchForm($queryID, $actionURL)
    {
        $this->config->product->all->search['queryID']   = $queryID;
        $this->config->product->all->search['actionURL'] = $actionURL;

        if(in_array($this->config->systemMode, array('ALM', 'PLM')))
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
     * @param  array  $productIDList
     * @param  int    $branch
     * @param  int    $appendProject
     * @param  string $status all|closed|unclosed
     * @access public
     * @return array
     */
    public function getProjectPairsByProductIDList($productIDList, $branch = 0, $appendProject = 0, $status = '')
    {
        $projectParis = array();
        foreach($productIDList as $productID)
        {
            $projects     = $this->getProjectPairsByProduct($productID, $branch, $appendProject, $status);
            $projectParis = $projectParis + $projects;
        }

        return $projectParis;
    }

    /**
     * Get project pairs by product.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $appendProject
     * @param  string $status all|closed|unclosed
     * @param  string $param  multiple|
     * @access public
     * @return array
     */
    public function getProjectPairsByProduct($productID, $branch = 0, $appendProject = 0, $status = '', $param = '')
    {
        $product = $this->getById($productID);
        if(empty($product)) return array();

        return $this->dao->select('t2.id, t2.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.product')->eq($productID)
            ->beginIF($status == 'closed')->andWhere('t2.status')->ne('closed')->fi()
            ->beginIF(strpos($param, 'multiple') !== false)->andWhere('t2.multiple')->ne('0')->fi()
            ->andWhere('t2.type')->eq('project')
            ->beginIF(!$this->app->user->admin)->andWhere('t2.id')->in($this->app->user->view->projects)->fi()
            ->beginIF($product->type != 'normal' and $branch !== '' and $branch != 'all')->andWhere('t1.branch')->in($branch)->fi()
            ->andWhere('t2.deleted')->eq('0')
            ->beginIF($appendProject)->orWhere('t2.id')->in($appendProject)->fi()
            ->orderBy('order_asc')
            ->fetchPairs('id', 'name');
    }

    /**
     * Get project list by product.
     *
     * @param  int    $productID
     * @param  string $browseType
     * @param  int    $branch
     * @param  int    $involved
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getProjectListByProduct($productID, $browseType = 'all', $branch = 0, $involved = 0, $orderBy = 'order_desc', $pager = null)
    {
        $branch = $branch ? $branch : 0;
        $projectList = $this->dao->select('DISTINCT t2.*')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->leftJoin(TABLE_TEAM)->alias('t3')->on('t2.id=t3.root')
            ->leftJoin(TABLE_STAKEHOLDER)->alias('t4')->on('t2.id=t4.objectID')
            ->where('t1.product')->eq($productID)
            ->andWhere('t2.type')->eq('project')
            ->beginIF($this->cookie->involved or $involved)->andWhere('t3.type')->eq('project')->fi()
            ->beginIF($browseType == 'undone')->andWhere('t2.status')->in('wait,doing')->fi()
            ->beginIF(strpos(",all,undone,", ",$browseType,") === false)->andWhere('t2.status')->eq($browseType)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t2.id')->in($this->app->user->view->projects)->fi()
            ->beginIF($this->cookie->involved or $involved)
            ->andWhere('t2.openedBy', true)->eq($this->app->user->account)
            ->orWhere('t2.PM')->eq($this->app->user->account)
            ->orWhere('t3.account')->eq($this->app->user->account)
            ->orWhere('(t4.user')->eq($this->app->user->account)
            ->andWhere('t4.deleted')->eq(0)
            ->markRight(1)
            ->orWhere("CONCAT(',', t2.whitelist, ',')")->like("%,{$this->app->user->account},%")
            ->markRight(1)
            ->fi()
            ->beginIF($branch !== 'all')->andWhere('t1.branch')->in($branch)->fi()
            ->andWhere('t2.deleted')->eq('0')
            ->orderBy($orderBy)
            ->page($pager, 't2.id')
            ->fetchAll('id');

        /* Determine how to display the name of the program. */
        $programList = $this->loadModel('program')->getParentPairs('', 'all');
        foreach($projectList as $id => $project)
        {
            $projectList[$id]->programName = $project->parent ? zget($programList, $project->parent, '') : '';
            $projectList[$id]->programName = preg_replace('/\//', '', $projectList[$id]->programName, 1);
        }

        return $projectList;
    }

    /**
     * Get project stats by product.
     *
     * @param  int       $productID
     * @param  string    $browseType
     * @param  int       $branch
     * @param  int       $involved
     * @param  string    $orderBy
     * @param  object    $pager
     * @access public
     * @return array
     */
    public function getProjectStatsByProduct($productID, $browseType = 'all', $branch = 0, $involved = 0, $orderBy = 'order_desc', $pager = null)
    {
        $projects = $this->getProjectListByProduct($productID, $browseType, $branch, $involved, $orderBy, $pager);
        if(empty($projects)) return array();

        $projectKeys = array_keys($projects);
        $stats       = array();

        /* Process projects. */
        foreach($projects as $key => $project)
        {
            if($project->end == '0000-00-00') $project->end = '';

            /* Judge whether the project is delayed. */
            if($project->status != 'done' and $project->status != 'closed' and $project->status != 'suspended')
            {
                $delay = helper::diffDate(helper::today(), $project->end);
                if($delay > 0) $project->delay = $delay;
            }

            $stats[] = $project;
        }
        return $stats;
    }

    /**
     * Get executions by product and project.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $orderBy
     * @param  int    $projectID
     * @param  string $mode stagefilter or empty
     * @access public
     * @return array
     */
    public function getExecutionPairsByProduct($productID, $branch = 0, $orderBy = 'id_asc', $projectID = 0, $mode = '')
    {
        if(empty($productID)) return array();

        $projects     = $this->loadModel('project')->getByIdList($projectID);
        $hasWaterfall = false;
        foreach($projects as $project)
        {
            if(in_array($project->model, array('waterfall', 'waterfallplus'))) $hasWaterfall = true;
        }
        $orderBy = $hasWaterfall ? 't2.begin_asc,t2.id_asc' : 't2.begin_desc,t2.id_desc';

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
            ->orderBy($orderBy)
            ->fetchAll('id');

        /* Only show leaf executions. */
        $allExecutions = $this->dao->select('id,name,attribute,parent')->from(TABLE_EXECUTION)
            ->where('type')->notin(array('program', 'project'))
            ->andWhere('deleted')->eq('0')
            ->fetchAll('id');
        $parents = array();
        foreach($allExecutions as $exec) $parents[$exec->parent] = true;

        if($projectID) $executions = $this->loadModel('execution')->resetExecutionSorts($executions);

        $executionPairs = array('0' => '');
        foreach($executions as $execID=> $execution)
        {
            if(isset($parents[$execID])) continue; // Only show leaf.
            if(strpos($mode, 'stagefilter') !== false and in_array($execution->attribute, array('request', 'design', 'review'))) continue; // Some stages of waterfall not need.

            if(empty($execution->multiple))
            {
                $this->app->loadLang('project');
                $executionPairs[$execution->id] = $execution->projectName . "({$this->lang->project->disableExecution})";
            }
            else
            {
                $paths = array_slice(explode(',', trim($execution->path, ',')), 1);
                $executionName = $projectID != 0 ? '' : $execution->projectName;
                foreach($paths as $path)
                {
                    if(!isset($allExecutions[$path])) continue;
                    $executionName .= '/' . $allExecutions[$path]->name;
                }

                $executionPairs[$execID] = $executionName;
            }
        }

        return $executionPairs;
    }

    /**
     * Get execution pairs by product.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $projectID
     * @param  string $mode stagefilter or empty
     * @access public
     * @return array
     */
    public function getAllExecutionPairsByProduct($productID, $branch = 0, $projectID = 0, $mode = '')
    {
        if(empty($productID)) return array();
        $executions = $this->dao->select('t2.id,t2.project,t2.name,t2.grade,t2.parent,t2.attribute')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.product')->eq($productID)
            ->andWhere('t2.type')->in('stage,sprint,kanban')
            ->beginIF($branch and $branch != 'all')->andWhere('t1.branch')->in($branch)->fi()
            ->beginIF($projectID)->andWhere('t2.project')->eq($projectID)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('t2.id')->in($this->app->user->view->sprints)->fi()
            ->andWhere('t2.deleted')->eq('0')
            ->orderBy('id_desc')
            ->fetchAll('id');

        $projectIdList = array();
        foreach($executions as $id => $execution) $projectIdList[$execution->project] = $execution->project;

        $executionPairs = array(0 => '');
        $projectPairs   = $this->loadModel('project')->getPairsByIdList($projectIdList, 'all');
        foreach($executions as $id => $execution)
        {
            if($execution->grade == 2 && isset($executions[$execution->parent]))
            {
                $execution->name = $projectPairs[$execution->project] . '/' . $executions[$execution->parent]->name . '/' . $execution->name;
                $executions[$execution->parent]->children[$id] = $execution->name;
                unset($executions[$id]);
            }
        }

        if($projectID) $executions = $this->loadModel('execution')->resetExecutionSorts($executions);
        foreach($executions as $execution)
        {
            if(strpos($mode, 'stagefilter') !== false and in_array($execution->attribute, array('request', 'design', 'review'))) continue;

            if(isset($execution->children))
            {
                $executionPairs = $executionPairs + $execution->children;
                continue;
            }

            /* Some stage of waterfall not need.*/
            if(isset($projectPairs[$execution->project])) $executionPairs[$execution->id] = $projectPairs[$execution->project] . '/' . $execution->name;
        }

        return $executionPairs;
    }

    /**
     * Get roadmap of a proejct.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $count
     * @access public
     * @return array
     */
    public function getRoadmap($productID, $branch = 0, $count = 0)
    {
        $plans    = $this->loadModel('productplan')->getList($productID, $branch);
        $releases = $this->loadModel('release')->getList($productID, $branch);
        $roadmap  = array();
        $total    = 0;

        $parents      = array();
        $orderedPlans = array();
        foreach($plans as $planID => $plan)
        {
            if($plan->parent == '-1')
            {
                $parents[$planID] = $plan->title;
                unset($plans[$planID]);
                continue;
            }
            if((!helper::isZeroDate($plan->end) and $plan->end < date('Y-m-d')) or $plan->end == '2030-01-01') continue;
            $orderedPlans[$plan->end][] = $plan;
        }

        krsort($orderedPlans);
        foreach($orderedPlans as $plans)
        {
            krsort($plans);
            foreach($plans as $plan)
            {
                if($plan->parent > 0 and isset($parents[$plan->parent])) $plan->title = $parents[$plan->parent] . ' / ' . $plan->title;

                $year = substr($plan->end, 0, 4);
                $branchIdList = explode(',', trim($plan->branch, ','));
                $branchIdList = array_unique($branchIdList);
                foreach($branchIdList as $branchID)
                {
                    if($branchID === '') continue;
                    $roadmap[$year][$branchID][] = $plan;
                }
                $total++;

                if($count > 0 and $total >= $count) return $this->processRoadmap($roadmap, $branch);
            }
        }

        $orderedReleases = array();
        foreach($releases as $release) $orderedReleases[$release->date][] = $release;

        krsort($orderedReleases);
        foreach($orderedReleases as $releases)
        {
            krsort($releases);
            foreach($releases as $release)
            {
                $year = substr($release->date, 0, 4);
                $branchIdList = explode(',', trim($release->branch, ','));
                $branchIdList = array_unique($branchIdList);
                foreach($branchIdList as $branchID)
                {
                    if($branchID === '') continue;
                    $roadmap[$year][$branchID][] = $release;
                }
                $total++;

                if($count > 0 and $total >= $count) return $this->processRoadmap($roadmap, $branch);
            }
        }

        if($count > 0) return $this->processRoadmap($roadmap, $branch);

        $groupRoadmap = array();
        foreach($roadmap as $year => $branchRoadmaps)
        {
            foreach($branchRoadmaps as $branch => $roadmaps)
            {
                $totalData = count($roadmaps);
                $rows      = ceil($totalData / 8);
                $maxPerRow = ceil($totalData / $rows);

                $groupRoadmap[$year][$branch] = array_chunk($roadmaps, $maxPerRow);
                foreach($groupRoadmap[$year][$branch] as $row => $rowRoadmaps) krsort($groupRoadmap[$year][$branch][$row]);
            }
        }

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

            foreach($groupRoadmap[$key] as $branchRoadmaps) $lastRoadmap['total'] += (count($branchRoadmaps, 1) - count($branchRoadmaps));
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
    public function processRoadmap($roadmapGroups, $branch)
    {
        $newRoadmap = array();
        foreach($roadmapGroups as $year => $branchRoadmaps)
        {
            foreach($branchRoadmaps as $branchID => $roadmaps)
            {
                if($branch != $branchID) continue;
                foreach($roadmaps as $roadmap) $newRoadmap[] = $roadmap;
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
     * Get product stat by id
     *
     * @param  int    $productID
     * @param  string $storyType
     * @access public
     * @return object|bool
     */
    public function getStatByID($productID, $storyType = 'story')
    {
        if(!$this->checkPriv($productID)) return false;

        $product = $this->getById($productID);
        if(empty($product)) return false;

        $stories = $this->dao->select('product, status, count(status) AS count')->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->andWhere('type')->eq($storyType)
            ->andWhere('product')->eq($productID)
            ->groupBy('product, status')
            ->fetchAll('status');

        /* Padding the stories to sure all status have records. */
        foreach(array_keys($this->lang->story->statusList) as $status)
        {
            $stories[$status] = isset($stories[$status]) ? $stories[$status]->count : 0;
        }

        $plans    = $this->dao->select('count(*) AS count')->from(TABLE_PRODUCTPLAN)->where('deleted')->eq(0)->andWhere('product')->eq($productID)->andWhere('end')->gt(helper::now())->fetch();
        $builds   = $this->dao->select('count(*) AS count')->from(TABLE_BUILD)->where('product')->eq($productID)->andWhere('deleted')->eq(0)->fetch();
        $cases    = $this->dao->select('count(*) AS count')->from(TABLE_CASE)->where('product')->eq($productID)->andWhere('deleted')->eq(0)->fetch();
        $bugs     = $this->dao->select('count(*) AS count')->from(TABLE_BUG)->where('product')->eq($productID)->andWhere('deleted')->eq(0)->fetch();
        $docs     = $this->dao->select('count(*) AS count')->from(TABLE_DOC)->where('product')->eq($productID)->andWhere('deleted')->eq(0)->fetch();
        $releases = $this->dao->select('count(*) AS count')->from(TABLE_RELEASE)->where('deleted')->eq(0)->andWhere('product')->eq($productID)->fetch();
        $projects = $this->dao->select('count(*) AS count')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t2.deleted')->eq(0)
            ->andWhere('t1.product')->eq($productID)
            ->andWhere('t2.type')->eq('project')
            ->fetch();

        $executions = $this->dao->select('count(*) AS count')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t2.deleted')->eq(0)
            ->andWhere('t1.product')->eq($productID)
            ->andWhere('t2.type')->in('sprint,stage,kanban')
            ->fetch();

        $product->stories    = $stories;
        $product->plans      = $plans      ? $plans->count : 0;
        $product->releases   = $releases   ? $releases->count : 0;
        $product->builds     = $builds     ? $builds->count : 0;
        $product->cases      = $cases      ? $cases->count : 0;
        $product->projects   = $projects   ? $projects->count : 0;
        $product->executions = $executions ? $executions->count : 0;
        $product->bugs       = $bugs       ? $bugs->count : 0;
        $product->docs       = $docs       ? $docs->count : 0;

        $closedTotal = $this->dao->select('count(id) AS count')->from(TABLE_STORY)->where('deleted')->eq(0)->andWhere('product')->eq($productID)->andWhere('status')->eq('closed')->fetch('count');
        $allTotal    = $this->dao->select('count(id) AS count')->from(TABLE_STORY)->where('deleted')->eq(0)->andWhere('product')->eq($productID)->fetch('count');
        $product->progress = empty($closedTotal) ? 0 : round($closedTotal / $allTotal * 100, 1);

        return $product;
    }

    /**
     * Refresh stats info of products.
     *
     * @param  bool $refreshAll
     * @access public
     * @return void
     */
    public function refreshStats($refreshAll = false)
    {
        $updateTime = zget($this->app->config->global, 'productStatsTime', '');
        $now        = helper::now();

        /*
         * If productStatsTime is before two weeks ago, refresh all products directly.
         * Else only refresh the latest products in action table.
         */
        $products = array();
        if($updateTime < date('Y-m-d', strtotime('-14 days')) or $refreshAll)
        {
            $products = $this->dao->select('id')->from(TABLE_PRODUCT)->fetchPairs('id');
        }
        else
        {
            $productActions = $this->dao->select('product')->from(TABLE_ACTION)
                ->where('date')->ge($updateTime)
                ->andWhere('product')->notin(array(',0,', ',,'))
                ->fetchPairs('product');
            if(empty($productActions)) return;

            foreach($productActions as $productAction)
            {
                foreach(explode(',', trim($productAction, ',')) as $product) $products[$product] = $product;
            }
            $products = array_filter($products);
        }
        if(empty($products)) return;

        /* 1. Get summary and members of products to be refreshed. */
        $stories = $this->dao->select('product, status, `closedReason`, count(id) AS c')
            ->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->beginIF(!empty($products))->andWhere('product')->in($products)->fi()
            ->andWhere('type')->eq('story')
            ->groupBy('product, status, `closedReason`')
            ->fetchAll();
        $productStories = array();
        foreach($stories as $story)
        {
            if(!isset($productStories[$story->product])) $productStories[$story->product] = array('draft' => 0, 'active' => 0, 'changing' => 0, 'reviewing' => 0, 'finished' => 0, 'closed' => 0, 'total' => 0);
            if($story->status == 'draft')     $productStories[$story->product]['draft']     += $story->c;
            if($story->status == 'active')    $productStories[$story->product]['active']    += $story->c;
            if($story->status == 'changing')  $productStories[$story->product]['changing']  += $story->c;
            if($story->status == 'reviewing') $productStories[$story->product]['reviewing'] += $story->c;
            if($story->status == 'closed')    $productStories[$story->product]['closed']    += $story->c;
            $productStories[$story->product]['total'] += $story->c;

            if($story->status == 'closed' && $story->closedReason == 'done') $productStories[$story->product]['finished'] += $story->c;
        }

        $bugs = $this->dao->select('product,status,resolution, count(id) AS c')
            ->from(TABLE_BUG)
            ->where('deleted')->eq(0)
            ->beginIF(!empty($products))->andWhere('product')->in($products)->fi()
            ->groupBy('product, status, resolution')
            ->fetchAll();
        $productBugs = array();
        foreach($bugs as $bug)
        {
            if(!isset($productBugs[$bug->product])) $productBugs[$bug->product] = array('unresolved' => 0, 'fixed' => 0, 'closed' => 0, 'total' => 0);
            if($bug->status == 'active' || ($bug->status != 'closed' && $bug->resolution == 'postponed')) $productBugs[$bug->product]['unresolved'] += $bug->c;

            if($bug->status == 'closed' && $bug->resolution == 'fixed') $productBugs[$bug->product]['fixed']  += $bug->c;
            if($bug->status == 'closed')                                $productBugs[$bug->product]['closed'] += $bug->c;
            $productBugs[$bug->product]['total'] += $bug->c;
        }

        $plans = $this->dao->select('product, count(id) AS c')
            ->from(TABLE_PRODUCTPLAN)
            ->where('deleted')->eq(0)
            ->beginIF(!empty($products))->andWhere('product')->in($products)->fi()
            ->andWhere('end')->gt(helper::now())
            ->groupBy('product')
            ->fetchPairs();

        $releases = $this->dao->select('product, count(id) AS c')
            ->from(TABLE_RELEASE)
            ->where('deleted')->eq(0)
            ->beginIF(!empty($products))->andWhere('product')->in($products)->fi()
            ->groupBy('product')
            ->fetchPairs();

        /* If products is empty, get all products. */
        if(empty($products)) $products = $this->dao->select('id')->from(TABLE_PRODUCT)->where('deleted')->eq(0)->fetchPairs();

        $stats = array();
        foreach($products as $productID)
        {
            $product = new stdclass();

            $product->draftStories     = isset($productStories[$productID]) ? $productStories[$productID]['draft']     : 0;
            $product->activeStories    = isset($productStories[$productID]) ? $productStories[$productID]['active']    : 0;
            $product->changingStories  = isset($productStories[$productID]) ? $productStories[$productID]['changing']  : 0;
            $product->reviewingStories = isset($productStories[$productID]) ? $productStories[$productID]['reviewing'] : 0;
            $product->closedStories    = isset($productStories[$productID]) ? $productStories[$productID]['closed']    : 0;
            $product->finishedStories  = isset($productStories[$productID]) ? $productStories[$productID]['finished']  : 0;
            $product->totalStories     = isset($productStories[$productID]) ? $productStories[$productID]['total']     : 0;

            $product->unresolvedBugs = isset($productBugs[$productID]) ? $productBugs[$productID]['unresolved'] : 0;
            $product->closedBugs     = isset($productBugs[$productID]) ? $productBugs[$productID]['closed']     : 0;
            $product->fixedBugs      = isset($productBugs[$productID]) ? $productBugs[$productID]['fixed']      : 0;
            $product->totalBugs      = isset($productBugs[$productID]) ? $productBugs[$productID]['total']      : 0;

            $product->plans        = isset($plans[$productID])    ? $plans[$productID]    : 0;
            $product->releases     = isset($releases[$productID]) ? $releases[$productID] : 0;

            $stats[$productID] = $product;
        }

        /* 2. Refresh stats to db. */
        foreach($stats as $productID => $product) $this->dao->update(TABLE_PRODUCT)->data($product)->where('id')->eq($productID)->exec();

        /* 3. Update projectStatsTime in config. */
        $this->loadModel('setting')->setItem('system.common.global.productStatsTime', $now);
        $this->app->config->global->productStatsTime = $now;

        /* 4. Clear actions older than 30 days. */
        $this->loadModel('action')->cleanActions();
    }

    /**
     * Get product stats.
     *
     * @param string $orderBy
     * @param mixed $pager
     * @param string $status
     * @param int $line
     * @param string $storyType
     * @param int $programID
     * @param int $param
     * @param int $shadow
     * @access public
     * @return void
     */
    public function getStats($orderBy = 'order_asc', $pager = null, $status = 'noclosed', $line = 0, $storyType = 'story', $programID = 0, $param = 0, $shadow = 0)
    {
        $this->loadModel('story');
        $this->loadModel('bug');

        $products = $status == 'bySearch' ? $this->getListBySearch($param) : $this->getList($programID, $status, $limit = 0, $line, $shadow, 'id');
        if(empty($products)) return array();

        $productKeys = array_keys($products);
        if($orderBy == 'program_asc')
        {
            $products = $this->dao->select('t1.id as id, t1.*')->from(TABLE_PRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROGRAM)->alias('t2')->on('t1.program = t2.id')
                ->where('t1.id')->in($productKeys)
                ->orderBy('t2.order_asc, t1.line_desc, t1.order_asc')
                ->page($pager)
                ->fetchAll('id');
        }
        else
        {
            $products = $this->dao->select('*')->from(TABLE_PRODUCT)
                ->where('id')->in($productKeys)
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }

        /* Recalculate productKeys after paging. */
        $productKeys = array_keys($products);

        $linePairs = $this->getLinePairs();
        foreach($products as $product) $product->lineName = zget($linePairs, $product->line, '');

        if(empty($programID))
        {
            $programKeys = array(0 => 0);
            foreach($products as $product) $programKeys[] = $product->program;
            $programs = $this->dao->select('id,name,PM')->from(TABLE_PROGRAM)
                ->where('id')->in(array_unique($programKeys))
                ->fetchAll('id');

            foreach($products as $product)
            {
                $product->programName = isset($programs[$product->program]) ? $programs[$product->program]->name : '';
                $product->programPM   = isset($programs[$product->program]) ? $programs[$product->program]->PM : '';
            }
        }

        $productStories = $this->dao->select("product,
            COUNT(CASE WHEN status = 'closed' AND closedReason = 'done' THEN 1 END) AS finishClosed,
            COUNT(CASE WHEN status = 'launched' THEN 1 END) AS launched,
            COUNT(CASE WHEN status = 'developing' THEN 1 END) AS developing")
            ->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->groupBy('product')
            ->fetchAll('product');

        foreach($products as $productID => $product)
        {
            $productStory = isset($productStories[$productID]) ? $productStories[$productID] : null;

            $products[$productID]->finishClosedStories = $productStory ? $productStory->finishClosed : 0;
            $products[$productID]->launchedStories     = $productStory ? $productStory->launched : 0;
            $products[$productID]->developingStories   = $productStory ? $productStory->developing : 0;
        }

        return $products;
    }

    /**
     * Get stats for product kanban.
     *
     * @access public
     * @return array
     */
    public function getStats4Kanban()
    {
        $date = date('Y-m-d');
        $this->loadModel('program');

        $productList = $this->getList();
        $programList = $this->program->getTopPairs('', '', true);
        $projectList = $this->program->getProjectStats(0, 'doing');

        $projectProduct = $this->dao->select('t1.product,t1.project,t2.parent,t2.path')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
            ->where('t1.product')->in(array_keys($productList))
            ->andWhere('t1.project')->in($this->app->user->view->projects)
            ->andWhere('t2.type')->eq('project')
            ->andWhere('t2.status')->eq('doing')
            ->andWhere('t2.deleted')->eq('0')
            ->fetchGroup('product', 'project');

        if(in_array($this->config->systemMode, array('ALM', 'PLM')) and !$this->config->product->showAllProjects)
        {
            foreach($projectProduct as $productID => $projects)
            {
                if(!isset($productList[$productID])) continue;
                $product = $productList[$productID];
                foreach($projects as $projectID => $project)
                {
                    if($project->parent != $product->program and strpos($project->path, ",{$product->program},") !== 0) unset($projectProduct[$productID][$projectID]);
                }
            }
        }

        $planList = $this->dao->select('id,product,title,parent,begin,end')->from(TABLE_PRODUCTPLAN)
            ->where('product')->in(array_keys($productList))
            ->andWhere('deleted')->eq(0)
            ->andWhere('end')->ge($date)
            ->andWhere('parent')->ne(-1)
            ->orderBy('begin desc')
            ->fetchGroup('product', 'id');

        $executionList = $this->dao->select('t1.product as productID,t2.*')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.project=t2.id')
            ->where('type')->in('stage,sprint,kanban')
            ->andWhere('t2.project')->in(array_keys($projectList))
            ->beginIF(!$this->app->user->admin)->andWhere('t1.project')->in($this->app->user->view->sprints)->fi()
            ->andWhere('t1.product')->in(array_keys($productList))
            ->andWhere('status')->eq('doing')
            ->andWhere('multiple')->ne('0')
            ->andWhere('deleted')->eq('0')
            ->orderBy('id_desc')
            ->fetchGroup('project', 'id');

        $projectLatestExecutions = array();
        $latestExecutionList     = array();
        $today                   = helper::today();
        foreach($executionList as $projectID => $executions)
        {
            foreach($executions as $executionID => &$execution)
            {
                /* Judge whether the execution is delayed. */
                if($execution->status != 'done' and $execution->status != 'closed' and $execution->status != 'suspended')
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

        $releaseList = $this->dao->select('id,product,name,marker')->from(TABLE_RELEASE)
            ->where('deleted')->eq('0')
            ->andWhere('product')->in(array_keys($productList))
            ->andWhere('status')->eq('normal')
            ->fetchGroup('product', 'id');

        /* Convert predefined HTML entities to characters. */
        $statsData = array('programList' => $programList, 'productList' => $productList, 'planList' => $planList, 'projectList' => $projectList, 'executionList' => $executionList, 'projectProduct' => $projectProduct, 'projectLatestExecutions' => $projectLatestExecutions, 'hourList' => $hourList, 'releaseList' => $releaseList);
        $statsData = $this->covertHtmlSpecialChars($statsData);

        return $statsData;
    }

    /**
     * Get product line pairs.
     *
     * @param  int    $programID
     * @access public
     * @return array
     */
    public function getLinePairs($programID = 0)
    {
        return $this->dao->select('id,name')->from(TABLE_MODULE)
            ->where('type')->eq('line')
            ->beginIF($programID)->andWhere('root')->eq($programID)->fi()
            ->andWhere('deleted')->eq(0)
            ->fetchPairs();
    }

    /*
     * Get all lines.
     * @access public
     * @return array
     */
    public function getLines()
    {
        return $this->dao->select('*')->from(TABLE_MODULE)->where('type')->eq('line')->andWhere('deleted')->eq(0)->orderBy('`order`')->fetchAll();
    }

    /**
     * Get the summary of product's stories.
     *
     * @param  array    $stories
     * @param  string   $storyType  story|requirement
     * @access public
     * @return string.
     */
    public function summary($stories, $storyType = 'story')
    {
        $totalEstimate = 0.0;
        $storyIdList   = array();

        $rateCount = 0;
        $allCount  = 0;
        foreach($stories as $key => $story)
        {
            if(!empty($story->type) && $story->type != $storyType) continue;

            $totalEstimate += $story->estimate;
            /* When the status is not closed or closedReason is done or postponed then add cases rate..*/
            if(
                empty($story->children) and
                ($story->status != 'closed' or
                ($story->status == 'closed' and ($story->closedReason == 'done' or $story->closedReason == 'postponed')))
            )
            {
                $storyIdList[] = $story->id;
                $rateCount ++;
            }

            $allCount ++;
            if(!empty($story->children))
            {
                foreach($story->children as $child)
                {
                    if($child->type != $storyType) continue;

                    if(
                        $child->status != 'closed' or
                        ($child->status == 'closed' and ($child->closedReason == 'done' or $child->closedReason == 'postponed'))
                    )
                    {
                        $storyIdList[] = $child->id;
                        $rateCount ++;
                    }
                    $allCount ++;
                }
            }
        }

        $cases = $this->dao->select('story')->from(TABLE_CASE)->where('story')->in($storyIdList)->andWhere('deleted')->eq(0)->fetchAll('story');
        $rate  = count($stories) == 0 || $rateCount == 0 ? 0 : round(count($cases) / $rateCount, 2);

        $storyCommon = $this->lang->SRCommon;
        if($storyType == 'requirement') $storyCommon = $this->lang->URCommon;
        if($storyType == 'story')       $storyCommon = $this->lang->SRCommon;

        return sprintf($this->lang->product->storySummary, $allCount,  $storyCommon, $totalEstimate, $rate * 100 . "%");
    }

    /**
     * Statistics program data.
     *
     * @param  object $productStats
     * @access public
     * @return array
     */
    public function statisticProgram($productStats)
    {
        if(defined('TUTORIAL')) return $this->loadModel('tutorial')->getProductStats();

        $productStructure = array();

        foreach($productStats as $product)
        {
            $productStructure[$product->program][$product->line]['products'][$product->id] = $product;
            if($product->line and $this->config->vision != 'or')
            {
                /* Line name. */
                $productStructure[$product->program][$product->line]['lineName'] = $product->lineName;
                $productStructure[$product->program][$product->line] = $this->statisticData('line', $productStructure, $product);
            }

            if($product->program and $this->config->vision != 'or')
            {
                /* Init vars. */
                /* Program name. */
                $productStructure[$product->program]['programName'] = $product->programName;
                $productStructure[$product->program]['programPM']   = $product->programPM;
                $productStructure[$product->program] = $this->statisticData('program', $productStructure, $product);
            }
        }
        return $productStructure;
    }

    /**
     * Statistic product data.
     *
     * @param  string $type
     * @param  array  $productStructure
     * @param  object $product
     * @access public
     * @return void
     */
    public function statisticData($type = 'program', $productStructure = array(), $product = null)
    {
        if(empty($productStructure)) return $productStructure;

        /* Init vars. */
        $data = $type == 'program' ? $productStructure[$product->program] : $productStructure[$product->program][$product->line];
        foreach($this->config->product->statisticFields as $key => $fields)
        {
            foreach($fields as $field)
            {
                if(!isset($data[$field])) $data[$field] = 0;
                $data[$field] += $product->$field;
            }
        }
        return $data;
    }

    /**
     * Judge an action is clickable or not.
     *
     * @param  object $product
     * @param  string $action
     * @access public
     * @return void
     */
    public static function isClickable($product, $action)
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

            if($product->status != 'closed') $menu .= $this->buildMenu('product', 'close', $params, $product, $type, '', '', 'iframe', true, "data-app='product'");
            if($product->status == 'closed') $menu .= $this->buildMenu('product', 'activate', $params, $product, $type, '', '', 'iframe', true, "data-app='product'");
            $menu .= "<div class='divider'></div>";

            $menu .= $this->buildMenu('product', 'edit', $params, $product, $type);
        }
        elseif($type == 'browse')
        {
            $menu .= $this->buildMenu('product', 'edit', $params, $product, $type);
            if($product->status != 'closed') $menu .= $this->buildMenu('product', 'close', $params, $product, $type, '', '', 'iframe', true, "data-app='product'");
            if($product->status == 'closed') $menu .= $this->buildMenu('product', 'activate', $params, $product, $type, '', '', 'iframe', true, "data-app='product'");
        }

        $menu .= $this->buildMenu('product', 'delete', $params, $product, $type, 'trash', 'hiddenwin');

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
        if(strpos(',programplan,product,bug,testcase,testtask,story,qa,testsuite,testreport,build,projectrelease,projectstory,', ',' . $module . ',') !== false)
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
            elseif($module == 'story' and $this->config->vision == 'or')
            {
                $link = helper::createLink('story', 'create', "productID=%s&branch=0&moduleID=0&storyID=0&objectID=0&bugID=0&planID0&todoID=0&extra=&storyType=requirement");
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
            elseif($module == 'testreport' and strpos('create,edit,browse', $method) !== false)
            {
                $vars   = ($method == 'edit' or $method == 'browse') ? "objectID=%s" : "objectID=&objectType=testtask&extra=%s";
                $method = $method == 'edit' ? 'browse' : $method;
                $link   = helper::createLink($module, $method, $vars);
            }
            else
            {
                $link = helper::createLink($module, $method, "productID=%s" . ($branch ? "&branch=%s" : '&branch=0') . ($extra ? "&extras=$extra" : ''));
            }
        }
        else if($module == 'productplan' || $module == 'release' || $module == 'roadmap')
        {
            if($method != 'browse' && $method != 'create') $method = 'browse';
            $link = helper::createLink($module, $method, "productID=%s" . ($branch ? "&branch=%s" : ''));
        }
        else if($module == 'tree')
        {
            $link = helper::createLink($module, $method, "productID=%s&type=$extra&currentModuleID=0" . ($branch ? "&branch=%s" : ''));
        }
        else if($module == 'branch')
        {
            $link = helper::createLink($module, $method, "productID=%s");
        }
        else if($module == 'doc' or $module == 'api')
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
            if($this->config->vision == 'lite')
            {
                return helper::createLink('feedback', 'browse', "browseType=byProduct&productID=%s");
            }

            return helper::createLink('feedback', 'admin', "browseType=byProduct&productID=%s");
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
        $linkHtml = strpos('programplan', $module) !== false ? sprintf($link, $projectID, $productID) : sprintf($link, $productID);
        return $linkHtml;
    }

    /**
     * get the latest project of the product.
     *
     * @param  int     $productID
     * @access public
     * @return object
     */
    public function getLatestProject($productID)
    {
        return $this->dao->select('t2.id, t2.name')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.product')->eq((int)$productID)
            ->andWhere('t2.status')->ne('closed')
            ->andWhere('t2.deleted')->eq('0')
            ->orderBy('t2.begin desc')
            ->limit(1)
            ->fetch();
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
     *
     * Set menu.
     *
     * @param int         $productID
     * @param int|string  $branch
     * @param int         $module
     * @param string      $moduleType
     * @param string      $extra
     *
     * @access public
     * @return void
     */
    public function setMenu($productID, $branch = '', $module = 0, $moduleType = '', $extra = '')
    {
        if(!$this->app->user->admin and strpos(",{$this->app->user->view->products},", ",$productID,") === false and $productID != 0 and !defined('TUTORIAL')) return $this->accessDenied($this->lang->product->accessDenied);

        $product = $this->getByID($productID);

        $params = array('branch' => $branch);
        common::setMenuVars('product', $productID, $params);
        if(!$product) return;

        $this->lang->switcherMenu = $this->getSwitcher($productID, $extra, $branch);

        if($product->type == 'normal')
        {
            unset($this->lang->product->menu->settings['subMenu']->branch);
        }
        else
        {
            $branchLink = $this->lang->product->menu->settings['subMenu']->branch['link'];
            $this->lang->product->menu->settings['subMenu']->branch['link'] = str_replace('@branch@', $this->lang->product->branchName[$product->type], $branchLink);
            $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);
        }

        if(strpos($extra, 'requirement') !== false) unset($this->lang->product->moreSelects['willclose']);
    }

    /**
     * Convert predefined HTML entities to characters
     *
     * @param  array $statsData
     * @return array
     */
    public function covertHtmlSpecialChars($statsData)
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
                foreach($data as $productID => $plan)
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
}
