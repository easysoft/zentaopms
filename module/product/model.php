<?php
/**
 * The model file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
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
        $currentModule = $this->app->getModuleName();
        $currentMethod = $this->app->getMethodName();

        /* init currentModule and currentMethod for report*/
        if($currentModule == 'story')  $currentModule = 'product';
        if($currentMethod == 'report') $currentMethod = 'browse';

        $selectHtml = html::select('productID', $products, $productID, "onchange=\"switchProduct(this.value, '$currentModule', '$currentMethod', '$extra');\"");
        foreach($this->lang->product->menu as $key => $menu)
        {
            $replace = $key == 'list' ? $selectHtml . $this->lang->arrow : $productID;
            common::setMenuVars($this->lang->product->menu, $key, $replace);
        }
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
     * @access public
     * @return array
     */
    public function getPairs()
    {
        $mode = $this->cookie->productMode ? $this->cookie->productMode : 'noclosed';
        $products = $this->dao->select('*')
            ->from(TABLE_PRODUCT)
            ->where('deleted')->eq(0)
            ->beginIF($mode == 'noclosed')->andWhere('status')->ne('closed')->fi()
            ->fetchAll();
        $pairs = array();
        foreach($products as $product)
        {
            if($this->checkPriv($product)) $pairs[$product->id] = $product->name;
        }
        return $pairs;
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
     * @access public
     * @return array
     */
    public function getProjectPairs($productID)
    {
        $projects = $this->dao->select('t2.id, t2.name')
            ->from(TABLE_PROJECTPRODUCT)->alias('t1')->leftJoin(TABLE_PROJECT)->alias('t2')
            ->on('t1.project = t2.id')
            ->where('t1.product')->eq((int)$productID)
            ->orderBy('t1.project desc')
            ->fetchPairs();
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
     * @param  int    $counts 
     *
     * @access public
     * @return array
     */
    public function getStats($counts)
    {
        $this->loadModel('report');
        $this->loadModel('story');

        $products = $this->getList('normal');
        $i = 1;
        foreach($products as $key => $product)
        {
            if($this->checkPriv($product))
            {
                if($i <= $counts)
                {
                    $this->session->set('storyReport', "product = '{$product->id}' AND deleted = '0'");
                    $dataXML = $this->report->createSingleXML($this->story->getDataOfStorysPerStatus($product->id), $this->lang->story->report->options->graph);
                    $charts[$product->id] = $this->report->createJSChart('pie2d', $dataXML, 'auto', 210);
                    $i ++;
                }
            }
            else
            {
                unset($products[$key]);
            }
        }

        return array('products' => $products, 'charts' => $charts);
    }
}
