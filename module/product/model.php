<?php
/**
 * The model file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
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
     * Set menu. 
     * 
     * @param  array  $products 
     * @param  int    $productID 
     * @param  string $extra 
     * @access public
     * @return void
     */
    public function setMenu($products, $productID, $extra = '')
    {
        /* Has access privilege?. */
        if($products and !isset($products[$productID]) and !$this->checkPriv($this->getById($productID)))
        {
            echo(js::alert($this->lang->product->accessDenied));
            die(js::locate('back'));
        }

        $currentModule = $this->app->getModuleName();
        $currentMethod = $this->app->getMethodName();

        /* init currentModule and currentMethod for report and story. */
        if($currentModule == 'story' and $currentMethod != 'create' and $currentMethod != 'batchcreate') $currentModule = 'product';
        if($currentMethod == 'report') $currentMethod = 'browse';

        $selectHtml = $this->select($products, $productID, $currentModule, $currentMethod, $extra);
        foreach($this->lang->product->menu as $key => $menu)
        {
            $replace = $key == 'list' ? $selectHtml : $productID;
            common::setMenuVars($this->lang->product->menu, $key, $replace);
        }
    }

    /**
     * Create the select code of products. 
     * 
     * @param  array     $products 
     * @param  int       $productID 
     * @param  string    $currentModule 
     * @param  string    $currentMethod 
     * @param  string    $extra 
     * @access public
     * @return string
     */
    public function select($products, $productID, $currentModule, $currentMethod, $extra = '')
    {
        if(!$productID) return;

        setCookie("lastProduct", $productID, $this->config->cookieLife, $this->config->webRoot);
        $currentProduct = $this->getById($productID);
        $output = "<a id='currentItem' href=\"javascript:showDropMenu('product', '$productID', '$currentModule', '$currentMethod', '$extra')\">{$currentProduct->name} <span class='icon-caret-down'></span></a><div id='dropMenu'></div>";
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
        if($productID > 0) $this->session->set('product', (int)$productID);
        if($productID == 0 and $this->cookie->lastProduct)    $this->session->set('product', (int)$this->cookie->lastProduct);
        if($productID == 0 and $this->session->product == '') $this->session->set('product', key($products));
        if(!isset($products[$this->session->product])) $this->session->set('product', key($products));
        return $this->session->product;
    }

    /**
     * Check privilege.
     * 
     * @param  int    $product 
     * @access public
     * @return bool
     */
    public function checkPriv($product)
    {
        /* Is admin? */
        $account = ',' . $this->app->user->account . ',';
        if(strpos($this->app->company->admins, $account) !== false) return true; 

        /* Product is open, return true. */
        if($product->acl == 'open') return true;

        /* Get team members. */
        $privProducts = $this->getPrivProducts();
        return isset($privProducts[$product->id]) ? true : false;
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
        return $this->dao->findById($productID)->from(TABLE_PRODUCT)->fetch();
    }

    /**
     * Get products.
     * 
     * @param  string $status 
     * @param  int    $limit 
     * @access public
     * @return array
     */
    public function getList($status = 'all', $limit = 0)
    {
        return $this->dao->select('*')->from(TABLE_PRODUCT)
            ->where('deleted')->eq(0)
            ->beginIF($status = 'noclosed')->andWhere('status')->ne('closed')->fi()
            ->beginIF($status != 'all' and $status != 'noclosed')->andWhere('status')->in($status)->fi()
            ->beginIF($limit > 0)->limit($limit)->fi()
            ->orderBy('code')
            ->fetchAll('id');
    }

    /**
     * Get product pairs. 
     * 
     * @param  string $mode 
     * @return array
     */
    public function getPairs($mode = '')
    {
        $orderBy  = !empty($this->config->product->orderBy) ? $this->config->product->orderBy : 'isClosed';
        $mode    .= $this->cookie->productMode;
        $products = $this->dao->select('*,  IF(INSTR(" closed", status) < 2, 0, 1) AS isClosed')
            ->from(TABLE_PRODUCT)
            ->where('deleted')->eq(0)
            ->beginIF(strpos($mode, 'noclosed') !== false)->andWhere('status')->ne('closed')->fi()
            ->orderBy($orderBy)
            ->fetchAll();
        $pairs = array();
        foreach($products as $product)
        {
            if($this->checkPriv($product))
            {

                if(strpos($mode, 'nocode') === false and $product->code)
                {
                    $firstChar = strtoupper(substr($product->code, 0, 1));
                    if(ord($firstChar) < 127) $product->name =  $firstChar . ':' . $product->name;
                }

                $pairs[$product->id] = $product->name;
            }
        }
        return $pairs;
    }

    /**
     * Get products by project. 
     * 
     * @param  int    $projectID 
     * @access public
     * @return array
     */
    public function getProductsByProject($projectID)
    {
        return $this->dao->select('t1.product, t2.name')
            ->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')
            ->on('t1.product = t2.id')
            ->where('t1.project')->eq($projectID)
            ->fetchPairs();
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
     * Create a product.
     * 
     * @access public
     * @return int
     */
    public function create()
    {
        $product = fixer::input('post')
             ->setIF($this->post->acl != 'custom', 'whitelist', '')
             ->setDefault('status', 'normal')
             ->setDefault('createdBy', $this->app->user->account)
             ->setDefault('createdDate', helper::now())
             ->setDefault('createdVersion', $this->config->version)
            ->join('whitelist', ',')
            ->stripTags($this->config->product->editor->create['id'], $this->config->allowedTags)
            ->get();
        $this->dao->insert(TABLE_PRODUCT)
            ->data($product)
            ->autoCheck()
            ->batchCheck('name,code', 'notempty')
            ->check('name', 'unique')
            ->check('code', 'unique')
            ->exec();
        return $this->dao->lastInsertID();
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
        $oldProduct = $this->getById($productID);
        $product = fixer::input('post')
            ->setIF($this->post->acl != 'custom', 'whitelist', '')
            ->join('whitelist', ',')
            ->stripTags($this->config->product->editor->edit['id'], $this->config->allowedTags)
            ->get();
        $this->dao->update(TABLE_PRODUCT)
            ->data($product)
            ->autoCheck()
            ->batchCheck('name,code', 'notempty')
            ->check('name', 'unique', "id != $productID")
            ->check('code', 'unique', "id != $productID")
            ->where('id')->eq($productID)
            ->exec();
        if(!dao::isError()) return common::createChanges($oldProduct, $product);
    }

    /**
     * Batch update products.
     * 
     * @access public
     * @return void
     */
    public function batchUpdate()
    {
        $products   = array();
        $allChanges = array();
        foreach($this->post->productIDList as $productID)
        {
            $products[$productID] = new stdClass();
            $products[$productID]->name   = $this->post->names[$productID];
            $products[$productID]->code   = $this->post->codes[$productID];
            $products[$productID]->PO     = $this->post->POs[$productID];
            $products[$productID]->QD     = $this->post->QDs[$productID];
            $products[$productID]->RD     = $this->post->RDs[$productID];
            $products[$productID]->status = $this->post->statuses[$productID];
        }

        foreach($products as $productID => $product)
        {
            $oldProduct = $this->getById($productID);
            $this->dao->update(TABLE_PRODUCT)
                ->data($product)
                ->autoCheck()
                ->batchCheck($this->config->product->edit->requiredFields , 'notempty')
                ->check('name', 'unique', "id != $productID")
                ->check('code', 'unique', "id != $productID")
                ->where('id')->eq($productID)
                ->exec();
            if(dao::isError()) die(js::error('product#' . $productID . dao::getError(true)));
            $allChanges[$productID] = common::createChanges($oldProduct, $product);
        }
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
        $now        = helper::now();
        $product= fixer::input('post')
            ->setDefault('status', 'closed')
            ->remove('comment')->get();

        $this->dao->update(TABLE_PRODUCT)->data($product)
            ->autoCheck()
            ->where('id')->eq((int)$productID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldProduct, $product);
    }

    /**
     * Get projects of a product in pairs.
     * 
     * @param  int    $productID 
     * @param  string $param    all|nodeleted
     * @access public
     * @return array
     */
    public function getProjectPairs($productID, $param = 'all')
    {
        $projectList  = array_keys($this->loadModel('project')->getPairs());
        $projects = array();
        $datas = $this->dao->select('t2.id, t2.name, t2.deleted')
            ->from(TABLE_PROJECTPRODUCT)->alias('t1')->leftJoin(TABLE_PROJECT)->alias('t2')
            ->on('t1.project = t2.id')
            ->where('t1.product')->eq((int)$productID)
            ->andWhere('t2.id')->in($projectList)
            ->orderBy('t1.project desc')
            ->fetchAll();

        foreach($datas as $data)
        {
            if($param == 'nodeleted' and $data->deleted) continue;
            $projects[$data->id] = $data->name;
        }
        $projects = array('' => '') +  $projects;
        return $projects;
    }

    /**
     * Get roadmap of a proejct
     * 
     * @param  int    $productID 
     * @access public
     * @return array
     */
    public function getRoadmap($productID)
    {
        $plans    = $this->loadModel('productplan')->getList($productID);
        $releases = $this->loadModel('release')->getList($productID);
        $roadmap  = array();
        if(is_array($releases)) $releases = array_reverse($releases);
        if(is_array($plans))    $plans    = array_reverse($plans);
        foreach($releases as $release)
        {
            $year = substr($release->date, 0, 4);
            $roadmap[$year][] = $release;
        }
        foreach($plans as $plan)
        {
            if($plan->end != '0000-00-00' and strtotime($plan->end) - time() <= 0) continue;
            $year = substr($plan->end, 0, 4);
            $roadmap[$year][] = $plan;
        }

        ksort($roadmap);

        /* Get last 5 roadmap. */
        $lastKeys    = array_slice(array_keys($roadmap), -5);
        $lastRoadmap = array();
        foreach($lastKeys as $key) $lastRoadmap[$key] = $roadmap[$key];

        return $lastRoadmap;
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
                ->fetchGroup('product', 'project');
        }
        if(empty($teams))
        {
            $teams = $this->dao->select('t1.project, t1.account')->from(TABLE_TEAM)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
                ->where('t2.deleted')->eq(0)
                ->fetchGroup('project', 'account');
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
     * @access public
     * @return object|bool
     */
    public function getStatByID($productID)
    {
        $product = $this->getById($productID);
        if(!$this->checkPriv($product)) return false;
        $stories = $this->dao->select('product, status, count(status) AS count')->from(TABLE_STORY)->where('deleted')->eq(0)->andWhere('product')->eq($productID)->groupBy('product, status')->fetchAll('status');
        /* Padding the stories to sure all status have records. */
        foreach(array_keys($this->lang->story->statusList) as $status)
        {
            $stories[$status] = isset($stories[$status]) ? $stories[$status]->count : 0;
        }

        $plans    = $this->dao->select('count(*) AS count')->from(TABLE_PRODUCTPLAN)->where('deleted')->eq(0)->andWhere('product')->eq($productID)->andWhere('end')->gt(helper::now())->fetch();
        $builds   = $this->dao->select('count(*) AS count')->from(TABLE_BUILD)->where('deleted')->eq(0)->andWhere('product')->eq($productID)->fetch();
        $cases    = $this->dao->select('count(*) AS count')->from(TABLE_CASE)->where('deleted')->eq(0)->andWhere('product')->eq($productID)->fetch();
        $bugs     = $this->dao->select('count(*) AS count')->from(TABLE_BUG)->where('deleted')->eq(0)->andWhere('product')->eq($productID)->fetch();
        $docs     = $this->dao->select('count(*) AS count')->from(TABLE_DOC)->where('deleted')->eq(0)->andWhere('product')->eq($productID)->fetch();
        $releases = $this->dao->select('count(*) AS count')->from(TABLE_RELEASE)->where('deleted')->eq(0)->andWhere('product')->eq($productID)->fetch();
        $projects = $this->dao->select('count("t1.*") AS count')->from(TABLE_PROJECTPRODUCT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t2.deleted')->eq(0)
            ->andWhere('t1.product')->eq($productID)
            ->fetch();

        $product->stories  = $stories;
        $product->plans    = $plans    ? $plans->count : 0;
        $product->releases = $releases ? $releases->count : 0;
        $product->builds   = $builds   ? $builds->count : 0;
        $product->cases    = $cases    ? $cases->count : 0;
        $product->projects = $projects ? $projects->count : 0;
        $product->bugs     = $bugs     ? $bugs->count : 0;
        $product->docs     = $docs     ? $docs->count : 0;

        return $product;
    }

    /**
     * Get product stats.
     * 
     * @param  string $orderBy 
     * @param  int    $pager 
     * @access public
     * @return array 
     */
    public function getStats($orderBy = 'code_asc', $pager = null)
    {
        $this->loadModel('report');
        $this->loadModel('story');
        $this->loadModel('bug');

        $products = $this->getList(',normal');
        foreach($products as $productID => $product)
        {
            if(!$this->checkPriv($product)) unset($products[$productID]);
        }
        $products = $this->dao->select('*')->from(TABLE_PRODUCT)
            ->where('id')->in(array_keys($products))
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        $stats = array();
        $stories = $this->dao->select('product, status, count(status) AS count')
            ->from(TABLE_STORY)
            ->where('deleted')->eq(0)
            ->andWhere('product')->in(array_keys($products))
            ->groupBy('product, status')
            ->fetchGroup('product', 'status');

        /* Padding the stories to sure all products have records. */
        $emptyStory = array_keys($this->lang->story->statusList);
        foreach(array_keys($products) as $productID)
        {
            if(!isset($stories[$productID])) $stories[$productID] = $emptyStory;
        }

        /* Padding the stories to sure all status have records. */
        foreach($stories as $key => $story)
        {
            foreach(array_keys($this->lang->story->statusList) as $status)
            {
                $story[$status] = isset($story[$status]) ? $story[$status]->count : 0;
            }
            $stories[$key] = $story;
        }

        $plans = $this->dao->select('product, count(*) AS count')
            ->from(TABLE_PRODUCTPLAN)
            ->where('deleted')->eq(0)
            ->andWhere('product')->in(array_keys($products))
            ->andWhere('end')->gt(helper::now())
            ->groupBy('product')
            ->fetchPairs();

        $releases = $this->dao->select('product, count(*) AS count')
            ->from(TABLE_RELEASE)
            ->where('deleted')->eq(0)
            ->andWhere('product')->in(array_keys($products))
            ->groupBy('product')
            ->fetchPairs();

        $bugs = $this->dao->select('product,count(*) AS conut')
          ->from(TABLE_BUG)
          ->where('deleted')->eq(0)
          ->andWhere('product')->in(array_keys($products))
          ->groupBy('product')
          ->fetchPairs(); 
       $unResolved  = $this->dao->select('product,count(*) AS count')
              ->from(TABLE_BUG)
              ->where('status')->eq('active')
              ->andwhere('deleted')->eq(0)
              ->andWhere('product')->in(array_keys($products))
              ->groupBy('product')
              ->fetchPairs();
        $assignToNull = $this->dao->select('product,count(*) AS count')
            ->from(TABLE_BUG)
             ->where('AssignedTo')->eq('')
            ->andwhere('deleted')->eq(0)
            ->andWhere('product')->in(array_keys($products))
            ->groupBy('product')
            ->fetchPairs();
        foreach($products as $key => $product)
        {
            if($product->status != 'closed')
            {
                $product->stories = $stories[$product->id];
                $product->plans   = isset($plans[$product->id])    ? $plans[$product->id]    : 0;
                $product->releases= isset($releases[$product->id]) ? $releases[$product->id] : 0;

                $product->bugs = isset($bugs[$product->id]) ? $bugs[$product->id] : 0;
                $product->unResolved = isset($unResolved[$product->id]) ? $unResolved[$product->id] : 0;
                $product->assignToNull = isset($assignToNull[$product->id]) ? $assignToNull[$product->id] : 0;
                $stats[] = $product;
            }
        }

        return $stats;
    }

    public function getPrivProducts()
    {
        $account = ',' . $this->app->user->account . ',';
        static $products;
        if($products === null)
        {
            $groupSql = '';
            if(isset($this->app->user->groups))
            {
                foreach($this->app->user->groups as $group) $groupSql .= "INSTR(CONCAT(',', t1.whitelist, ','), ',$group,') > 0 OR ";
            }
            $groupSql = !empty($groupSql) ? '(' . substr($groupSql, 0, strlen($groupSql) - 4) . ')' : '1 != 1';

            $products = $this->dao->select('distinct t1.id')->from(TABLE_PRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROJECTPRODUCT)->alias('t2')->on('t1.id = t2.product')
                ->leftJoin(TABLE_TEAM)->alias('t3')->on('t2.project = t3.project')
                ->leftJoin(TABLE_PROJECT)->alias('t4')->on('t2.project = t4.id')
                ->beginIF(strpos($this->app->company->admins, $account) !== false)->where('t1.deleted')->eq(0)->fi()
                ->beginIF(strpos($this->app->company->admins, $account) === false)
                ->where('t1.acl')->eq('open')
                ->orWhere("(t1.acl = 'custom' AND $groupSql)")
                ->orWhere('t1.PO')->eq($this->app->user->account)
                ->orWhere('t1.QD')->eq($this->app->user->account)
                ->orWhere('t1.RD')->eq($this->app->user->account)
                ->orWhere('t1.createdBy')->eq($this->app->user->account)
                ->orWhere('t3.account')->eq($this->app->user->account)
                ->andWhere('t1.deleted')->eq(0)
                ->andWhere('t4.deleted')->eq(0)
                ->fi()
                ->fetchAll('id');
        }
        return $products;
    }

    /**
     * Get the summary of product's stories.
     * 
     * @param  array    $stories 
     * @access public
     * @return string.
     */
    public function summary($stories)
    {
        $totalEstimate = 0.0;
        $storyIdList   = array();

        foreach($stories as $key => $story)
        {
            $totalEstimate += $story->estimate;
            /* When the status is not closed or closedReason is done or postponed then add cases rate..*/
            if(
                $story->status != 'closed' or
                ($story->status == 'closed' and ($story->closedReason == 'done' or $story->closedReason == 'postponed'))
            )
            {
                $storyIdList[] = $story->id;
            }
        }

        $cases = $this->dao->select('DISTINCT story')->from(TABLE_CASE)->where('story')->in($storyIdList)->fetchAll();
        $rate  = count($stories) == 0 ? 0 : round(count($cases) / count($stories), 2);

        return sprintf($this->lang->product->storySummary, count($stories), $totalEstimate, $rate * 100 . "%");
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
     * Create the link from module,method,extra
     * 
     * @param  string  $module 
     * @param  string  $method 
     * @param  mix     $extra 
     * @access public
     * @return void
     */
    public function getProductLink($module, $method, $extra)
    {
        $link = '';
        if(strpos('product,roadmap,bug,testcase,testtask,story', $module) !== false)
        {
            if($module == 'product' && $method == 'project')
            {
                $link = helper::createLink($module, $method, "status=all&productID=%s");
            }
            elseif($module == 'product' && $method == 'index')
            {
                $link = helper::createLink($module, $method, "locate=no&productID=%s");
            }
            else
            {
                $link = helper::createLink($module, $method, "productID=%s");
            }
        }
        else if($module == 'productplan' || $module == 'release')
        {
            if($method != 'browse' && $method != 'create') $method = 'browse';
            $link = helper::createLink($module, $method, "productID=%s");
        }
        else if($module == 'tree')
        {
            $link = helper::createLink($module, $method, "productID=%s&type=$extra");
        }
        return $link;
    }
}
