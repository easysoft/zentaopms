<?php
class product extends control
{
    public function browse(int $productID = 0, int $branch = 0, string $browseType = '', int $param = 0, string $storyType = 'story', string $orderBy = '', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1, int $projectID = 0, string $from = 'product', int $blockID = 0)
    {
        /* Load datatable and execution. */
        $this->loadModel('execution');
        $this->loadModel('user');

        $isProjectStory  = $this->app->rawModule == 'projectstory';
        $projectProducts = $this->loadModel('product')->getProducts($projectID);
        $productPlans    = $this->execution->getPlans(array_keys($projectProducts));

        $this->products = array();
        foreach($projectProducts as $productID => $product) $this->products[$productID] = $product->name;

        reset($projectProducts);
        if(empty($productID)) $productID = key($projectProducts);
        $product   = $projectProducts[$productID];

        $showBranch = $this->loadModel('branch')->showBranch($productID, 0, $projectID);

        /* Set menu. */
        $this->loadModel('project')->setMenu($projectID);

        $branches  = $this->loadModel('branch')->getList($productID, $projectID, 'all');
        $branch    = ($this->cookie->preBranch and $branch === 0 and isset($branches[$this->cookie->preBranch])) ? $this->cookie->preBranch : $branch;
        $branchID  = $branch;

        /* Lower browse type. */
        $browseType = strtolower($browseType);

        /* Set product, module and query. */
        setcookie('preProductID', $productID, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);
        setcookie('preBranch', $branch, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);

        if($this->cookie->preProductID != $productID or $this->cookie->preBranch != $branch)
        {
            $_COOKIE['storyModule'] = 0;
            setcookie('storyModule', 0, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
        }

        if($browseType == 'bymodule' or $browseType == '')
        {
            setcookie('storyModule', (int)$param, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
            if($this->app->tab == 'project') setcookie('storyModuleParam', (int)$param, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
            $_COOKIE['storyBranch'] = 'all';
            setcookie('storyBranch', 'all', 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
            if($browseType == '') setcookie('treeBranch', $branch, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
        }

        $cookieModule = $this->app->tab == 'project' ? $this->cookie->storyModuleParam : $this->cookie->storyModule;
        $moduleID = ($browseType == 'bymodule') ? (int)$param : (($browseType == 'bysearch') ? 0 : ($cookieModule ? $cookieModule : 0));
        $queryID  = ($browseType == 'bysearch') ? (int)$param : 0;

        /* Set moduleTree. */
        $createModuleLink = $storyType == 'story' ? 'createStoryLink' : 'createRequirementLink';
        if($browseType == '')
        {
            setcookie('treeBranch', $branch, 0, $this->config->webRoot, '', $this->config->cookieSecure, false);
            $browseType = 'unclosed';
        }
        else
        {
            $branch = $this->cookie->treeBranch ? $this->cookie->treeBranch : 0;
        }

        /* Process the order by field. */
        if(!$orderBy) $orderBy = $this->cookie->productStoryOrder ? $this->cookie->productStoryOrder : 'id_desc';
        setcookie('productStoryOrder', $orderBy, 0, $this->config->webRoot, '', $this->config->cookieSecure, true);

        /* Load pager. */
        $this->app->loadClass('pager', $static = true);
        if($this->app->getViewType() == 'xhtml') $recPerPage = 10;
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* Display of branch label. */
        $showBranch = $this->loadModel('branch')->showBranch($productID);

        $product = $this->product->getById($productID);
        $project = $projectID ? $this->loadModel('project')->getByID($projectID) : null;

        /* Get stories. */
        $stories = $this->productZen->getStories($projectID, $productID, $branchID, $moduleID, $param, $storyType, $browseType, $orderBy, $pager);

        /* Process the sql, get the conditon partion, save it to session. */
        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'story', ($browseType != 'bysearch' and $browseType != 'reviewbyme' and $this->app->rawModule != 'projectstory'));

        /* Save session. */
        $this->productZen->saveSession4Browse($product, $browseType);

        /* Build search form. */
        $this->productZen->buildSearchFormForBrowse($project, $projectID, $productID, $branch, $param, $storyType, $browseType, $isProjectStory, '', $blockID);

        $showModule  = !empty($this->config->datatable->productBrowse->showModule) ? $this->config->datatable->productBrowse->showModule : '';
        $productName = ($this->app->rawModule == 'projectstory' and empty($productID)) ? $this->lang->product->all : $this->products[$productID];

        /* Assign. */
        $this->view->title           = $productName . $this->lang->hyphen . $this->lang->product->browse;
        $this->view->moduleID        = $moduleID;
        $this->view->pager           = $pager;
        $this->view->orderBy         = $orderBy;
        $this->view->moduleTree      = $this->productZen->getModuleTree($projectID, $productID, $branch, $param, $storyType, $browseType);
        $this->view->param           = $param;
        $this->view->from            = $from;
        $this->view->blockID         = $blockID;

        $this->productZen->assignBrowseData($stories, $browseType, $storyType, $isProjectStory, $product, $project, $branch, $branchID);
    }
}
