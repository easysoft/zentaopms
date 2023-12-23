<?php
declare(strict_types=1);
/**
 * The control file of store module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   store
 * @link      https://www.zentao.net
 */
class store extends control
{
    /**
     * Contruct function, load cne model.
     *
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->loadModel('cne');
        $this->app->loadLang('instance');
    }

    /**
     * 应用市场列表。
     * Index page.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        $this->locate($this->createLink('store', 'browse'));
    }

    /**
     * 应用市场应用列表。
     * Browse departments and users of a store.
     *
     * @param  string $sortType
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse(string $sortType = 'create_time', int $recPerPage = 0, int $pageID = 1)
    {
        if(!commonModel::hasPriv('space', 'browse')) $this->loadModel('common')->deny('space', 'browse', false);
        if(empty($recPerPage)) $recPerPage = $this->cookie->pagerStoreBrowse ? $this->cookie->pagerStoreBrowse : 12;

        $keyword        = '';
        $postCategories = array();
        if(!empty($_POST))
        {
            $pageID = 1;
            $conditions = fixer::input('post')
                ->setDefault('keyword', '')
                ->setDefault('categories', array())
                ->get();
            $keyword        = $conditions->keyword;
            $postCategories = $conditions->categories;
        }

        $pagedApps = $this->store->searchApps($sortType, $keyword, $postCategories, $pageID, $recPerPage);

        $this->app->loadClass('pager', true);
        $pager = pager::init($pagedApps->total, $recPerPage, $pageID);

        $pagedCategories = $this->store->getCategories();
        $categories      = array_combine(helper::arrayColumn($pagedCategories->categories, 'id'), helper::arrayColumn($pagedCategories->categories, 'alias'));

        $this->view->title          = $this->lang->store->common;
        $this->view->cloudApps      = $pagedApps->apps;
        $this->view->installedApps  = $this->storeZen->getInstalledApps();
        $this->view->categories     = $categories;
        $this->view->postCategories = $postCategories;
        $this->view->keyword        = $keyword;
        $this->view->sortType       = $sortType;
        $this->view->pager          = $pager;

        $this->display();
    }

    /**
     * 展示应用详情。
     * Show app detail.
     *
     * @param  int    $appID
     * @param  int    $pageID
     * @param  int    $recPerPage
     * @access public
     * @return void
     */
    public function appView(int $appID, int $pageID = 1, int $recPerPage = 20)
    {
        if(!commonModel::hasPriv('space', 'browse')) $this->loadModel('common')->deny('space', 'browse', false);

        $appInfo = $this->store->getAppInfo($appID, true);

        $dynamicResult = $this->store->appDynamic($appInfo, $pageID, $recPerPage);
        $articles = array();
        $totalArticle = 0;
        if(!empty($dynamicResult))
        {
            $articles     = $dynamicResult->articles;
            $totalArticle = $dynamicResult->recTotal;
        }
        $this->view->dynamicArticles = $articles;

        $this->app->loadClass('pager', true);
        $pager = pager::init($totalArticle, $recPerPage, $pageID);
        $this->view->pager = $pager;

        $this->view->title        = $appInfo->alias;
        $this->view->position[]   = $appInfo->alias;
        $this->view->cloudApp     = $appInfo;
        $this->view->components   = null; // Hide custom installation in version 1.0. If want, opened by: $this->store->getAppSettings($appID);

        $this->display();
    }
}
