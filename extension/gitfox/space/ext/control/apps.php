<?php
/**
 * GitFox 空间下的应用列表：由 space 模块承载，复用应用模块的列表配置。
 * The application list of a space in the GitFox vision.
 *
 * @copyright   Copyright 2009-2026 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @package     space
 * @link        https://www.zentao.net
 */
helper::importControl('space');
class mySpace extends space
{
    /**
     * 空间下的应用列表。
     * Browse applications of a space.
     *
     * @param  int    $spaceID
     * @param  string $keyword
     * @param  string $browseType
     * @param  string $orderBy
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function apps(int $spaceID = 0, string $keyword = '', string $browseType = 'noclosed', string $orderBy = 'id_desc', int $recPerPage = 20, int $pageID = 1)
    {
        $this->loadModel('space')->setMenu($spaceID);
        $this->app->loadLang('product');
        $this->app->loadConfig('product');

        $keyword    = trim($keyword);
        $browseType = strtolower($browseType);

        /* 空间下的应用：空间内代码库所关联的产品。*/
        $productIDList = $this->loadModel('space')->getProductsBySpace($spaceID);
        $products      = empty($productIDList) ? array() : $this->loadModel('product')->getByIdList($productIDList);

        $repos = $this->loadModel('repo')->getProductRepos();
        foreach($products as $productID => $product)
        {
            /* 关键字按应用名称过滤，与应用模块列表保持一致。*/
            if($keyword !== '' && stripos((string)$product->name, $keyword) === false)
            {
                unset($products[$productID]);
                continue;
            }

            /* 未关闭 / 已关闭筛选，与应用模块列表一致。*/
            if($browseType == 'noclosed' && $product->status == 'closed')
            {
                unset($products[$productID]);
                continue;
            }
            if($browseType == 'closed' && $product->status != 'closed')
            {
                unset($products[$productID]);
                continue;
            }

            $product->desc     = strip_tags((string)$product->desc);
            $product->repoName = '';
            if(!empty($repos[$product->id]))
            {
                foreach($repos[$product->id] as $repo) $product->repoName .= $repo->name . ',';
            }
            $product->repoName = rtrim($product->repoName, ',');
        }

        list($order, $sort) = explode('_', $orderBy);
        $orderList = array();
        foreach($products as $product) $orderList[] = zget($product, $order, 0);
        if($orderList) array_multisort($orderList, $sort == 'desc' ? SORT_DESC : SORT_ASC, $products);

        $this->app->loadClass('pager', true);
        $pager   = new pager(count($products), $recPerPage, $pageID);
        $chunked = array_chunk($products, (int)$pager->recPerPage);
        if($chunked && !isset($chunked[$pageID - 1])) $pageID = 1;

        $this->view->title      = $this->lang->productCommon;
        $this->view->appList    = empty($chunked) ? array() : $chunked[$pageID - 1];
        $this->view->users      = $this->loadModel('user')->getPairs('noletter');
        $this->view->keyword    = $keyword;
        $this->view->browseType = $browseType;
        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->spaceID    = $spaceID;

        $this->display();
    }
}
