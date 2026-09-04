<?php
/**
 * GitFox 的“应用”入口复用 product 模块，但落地页应为应用/产品列表。
 * The browse method of product module for the GitFox vision.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @package     product
 * @link        https://www.zentao.net
 */
helper::importControl('product');
class myProduct extends product
{
    /**
     * 浏览应用列表。
     * Browse the application list.
     *
     * @param  int     $productID
     * @param  string  $branch
     * @param  string  $browseType
     * @param  int     $param
     * @param  string  $storyType
     * @param  string  $orderBy
     * @param  int     $recTotal
     * @param  int     $recPerPage
     * @param  int     $pageID
     * @param  int     $projectID
     * @param  string  $from
     * @param  int     $blockID
     * @access public
     * @return void
     */
    public function browse(int $productID = 0, string $branch = 'all', string $browseType = '', int $param = 0, string $storyType = 'story', string $orderBy = '', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1, int $projectID = 0, string $from = 'product', int $blockID = 0)
    {
        if($this->config->vision != 'gitfox') return parent::browse($productID, $branch, $browseType, $param, $storyType, $orderBy, $recTotal, $recPerPage, $pageID, $projectID, $from, $blockID);

        $browseType = $browseType === '' ? 'noclosed' : strtolower($browseType);
        $orderBy    = $orderBy    === '' ? 'order_asc' : $orderBy;

        /* 搜索词优先使用 URL 参数，其次使用 cookie；有 URL 参数时同步回 cookie，保证模型过滤与后续分页一致。 */
        $keyword = $this->get->keyword;
        if($keyword === false) $keyword = $this->cookie->gitfoxProductKeyword;
        $keyword = $keyword === false ? '' : trim((string)$keyword);
        if($this->get->keyword !== false)
        {
            $this->cookie->set('gitfoxProductKeyword', $keyword);
            helper::setcookie('gitfoxProductKeyword', $keyword, $this->config->cookieLife, $this->config->webRoot, '', $this->config->cookieSecure, true);
        }

        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);
        $products = $this->product->getProductsInGitfox($browseType, $orderBy, $keyword, $pager);
        $repos    = $this->loadModel('repo')->getProductRepos();

        foreach($products as $product)
        {
            $product->desc     = strip_tags($product->desc);
            $product->repoName = '';
            if(!empty($repos[$product->id]))
            {
                foreach($repos[$product->id] as $repo)
                {
                    $product->repoName .= $repo->name . ',';
                }
            }
        }

        $this->view->title      = $this->lang->productCommon;
        $this->view->recTotal   = $pager->recTotal;
        $this->view->products   = $products;
        $this->view->users      = $this->user->getPairs('noletter');
        $this->view->keyword    = $keyword;
        $this->view->orderBy    = $orderBy;
        $this->view->browseType = $browseType;
        $this->view->pager      = $pager;
        $this->view->param      = $param;
        $this->view->recPerPage = $recPerPage;
        $this->view->pageID     = $pager->pageID;

        $this->display();
    }
}
