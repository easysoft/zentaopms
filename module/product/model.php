<?php
/**
 * The model file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id$
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

        /* init currentModule and currentMethod for report*/
        if($currentModule == 'story')  $currentModule = 'product';
        if($currentMethod == 'report') $currentMethod = 'browse';

        $selectHtml = $this->select($products, $productID, $currentModule, $currentMethod, $extra);
        foreach($this->lang->product->menu as $key => $menu)
        {
            $replace = $key == 'list' ? $selectHtml . $this->lang->arrow : $productID;
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
        /**
         * 1. if user selected by mouse, reload it. 
         * 2. if the user select by keyboard, save the event.keyCode, thus the switchProduct() can judge whether reload or not.
         * 3. if user press enter key in the select, reload it.
         * 4. if user click the go button, reload it.
         * */
        $switchCode  = "switchProduct($('#productID').val(), '$currentModule', '$currentMethod', '$extra');";
        $onchange    = "onchange=\"$switchCode\""; 
        $onkeypress  = "onkeypress=\"eventKeyCode=event.keyCode; if(eventKeyCode == 13) $switchCode\""; 
        $onclick     = "onclick=\"eventKeyCode = 13; $switchCode\""; 
        $selectHtml  = html::select('productID', $products, $productID, "tabindex=2 $onchange $onkeypress");
        $selectHtml .= html::commonButton($this->lang->go, "id='productSwitcher' tabindex=3 $onclick");
        return $selectHtml;
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
        $teamMembers = $this->getTeamMemberPairs($product);

        /* Private. */
        if($product->acl == 'private')
        {
            return isset($teamMembers[$this->app->user->account]);
        }

        /* Custom, check groups. */
        if($product->acl == 'custom')
        {
            if(isset($teamMembers[$this->app->user->account])) return true;
            $userGroups    = $this->loadModel('user')->getGroups($this->app->user->account);
            $productGroups = explode(',', $product->whitelist);
            foreach($userGroups as $groupID)
            {
                if(in_array($groupID, $productGroups)) return true;
            }
            return false;
        }
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
            ->beginIF($status != 'all')->andWhere('status')->in($status)->fi()
            ->beginIF($limit > 0)->limit($limit)->fi()
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
        $orderBy  = !empty($this->config->product->orderBy) ? $this->config->product->orderBy : 'code';
        $mode    .= $this->cookie->productMode;
        $products = $this->dao->select('*')
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
            ->stripTags('name,code')
             ->setIF($this->post->acl != 'custom', 'whitelist', '')
             ->setDefault('status', 'normal')
             ->setDefault('createdBy', $this->app->user->account)
             ->setDefault('createdDate', helper::now())
            ->join('whitelist', ',')
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
            ->stripTags('name,code')
            ->setIF($this->post->acl != 'custom', 'whitelist', '')
            ->join('whitelist', ',')
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
     * Get projects of a product in pairs.
     * 
     * @param  int    $productID 
     * @param  string $param    all|nodeleted
     * @access public
     * @return array
     */
    public function getProjectPairs($productID, $param = 'all')
    {
        $projects = array();
        $datas = $this->dao->select('t2.id, t2.name, t2.deleted')
            ->from(TABLE_PROJECTPRODUCT)->alias('t1')->leftJoin(TABLE_PROJECT)->alias('t2')
            ->on('t1.project = t2.id')
            ->where('t1.product')->eq((int)$productID)
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
        return $roadmap;
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
        $members[$product->QM] = $product->QM;
        $members[$product->RM] = $product->RM;
        $members[$product->createdBy] = $product->createdBy;

        $projects = $this->dao->select('project')->from(TABLE_PROJECTPRODUCT)->where('product')->eq($product->id)->fetchPairs();
        if(!$projects) return $members;
        $projectTeams = $this->dao->select('account')->from(TABLE_TEAM)->where('project')->in($projects)->fetchPairs();
        return array_merge($members, $projectTeams);
    }

    /**
     * Get product stats.
     * 
     * @access public
     * @return array
     */
    public function getStats()
    {
        $this->loadModel('report');
        $this->loadModel('story');

        $products = $this->getList(',normal');
        $stats    = array();

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
        
        foreach($products as $key => $product)
        {
            if($this->checkPriv($product))
            {
                if($product->status != 'closed')
                {
                    $product->stories = $stories[$product->id];
                    $product->plans   = isset($plans[$product->id])    ? $plans[$product->id]    : 0;
                    $product->releases= isset($releases[$product->id]) ? $releases[$product->id] : 0;

                    $stats[] = $product;
                }
            }
            else
            {
                unset($products[$key]);
            }
        }

        return $stats;
    }
}
