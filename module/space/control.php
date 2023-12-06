<?php
declare(strict_types=1);
/**
 * The control file of space module of ZenTaoPMS.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   space
 * @link      https://www.zentao.net
 */
class space extends control
{
    /**
     * DevOps应用列表。
     * Browse departments and users of a space.
     *
     * @param  int    $spaceID
     * @param  string $browseType
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
       @access public
     * @return void
     */
    public function browse(int $spaceID = 0, string $browseType = 'all', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        if(!commonModel::hasPriv('space', 'browse')) $this->loadModel('common')->deny('space', 'browse', false);

        $space = null;
        if($spaceID)      $space = $this->space->getByID($spaceID);
        if(empty($space)) $space = $this->space->defaultSpace($this->app->user->account);
        $allInstances = $this->spaceZen->getSpaceInstances($browseType);

        /* Data sort. */
        list($order, $sort) = explode('_', $orderBy);
        $createdColumn  = helper::arrayColumn($allInstances, $order == 'id' ? 'createdAt' : $order);
        array_multisort($createdColumn, $sort == 'desc' ? SORT_DESC : SORT_ASC, $allInstances);

        /* Pager. */
        $this->app->loadClass('pager', true);
        $recTotal = count($allInstances);
        $pager    = new pager($recTotal, $recPerPage, $pageID);
        $allInstances = array_chunk($allInstances, $pager->recPerPage);

        $solutionID = 0;
        $solution   = $this->loadModel('solution')->getLastSolution();
        if($solution && $solution->status == 'installing') $solutionID = $solution->id;

        $this->view->title      = $this->lang->space->common;
        $this->view->pager      = $pager;
        $this->view->orderBy    = $orderBy;
        $this->view->solutionID = $solutionID;
        $this->view->browseType = $browseType;
        $this->view->instances  = (empty($allInstances) or empty($allInstances[$pageID - 1])) ? array() : $allInstances[$pageID - 1];
        $this->view->users      = $this->loadModel('user')->getPairs('noclosed,noletter');

        $this->display();
    }

    /**
     * 创建一个应用。
     * Create a application.
     *
     * @param  int    $appID
     * @access public
     * @return void
     */
    public function createApplication(int $appID = 0)
    {
        if(!commonModel::hasPriv('instance', 'manage')) $this->loadModel('common')->deny('instance', 'manage', false);

        $this->app->loadLang('sonarqube');
        $this->app->loadLang('jenkins');

        $apps       = array();
        $defaultApp = '';
        if($this->config->inQuickon)
        {
            $pagedApps = $this->loadModel('store')->searchApps('', '', array(), 1, 10000);
            foreach($pagedApps->apps as $app)
            {
                if(strpos($app->name, 'zentao') === 0 || strpos($app->name, 'zdoo') === 0 || strpos($app->name, 'xuanxuan') === 0) continue;
                if(!$appID && $app->alias == 'GitLab') $defaultApp = $app->id;

                $apps[$app->id] = $app->alias;
            }

            $mysqlList   = $this->loadModel('cne')->sharedDBList('mysql');
            $pgList      = $this->cne->sharedDBList('postgresql');
            $versionList = $this->store->getVersionPairs($appID);
            $cloudApp    = $this->loadModel('store')->getAppInfo($appID);
            $showDb      = !empty($cloudApp) && ((!empty($cloudApp->dependencies->mysql) && $mysqlList) || (!empty($cloudApp->dependencies->postgresql) && $pgList));

            $this->view->pgList      = $pgList;
            $this->view->mysqlList   = $mysqlList;
            $this->view->versionList = $versionList;
            $this->view->showDb      = $showDb;
            $this->view->thirdDomain = $this->loadModel('instance')->randThirdDomain();
        }

        $this->view->apps       = $apps;
        $this->view->defaultApp = $defaultApp;
        $this->view->title      = $this->lang->space->install;
        $this->view->appID      = $appID;
        $this->display();
    }

    /**
     * 获取应用商店app信息。
     * Get a store app info.
     *
     * @param  int    $appID
     * @access public
     * @return void
     */
    public function getStoreAppInfo(int $appID)
    {
        if(!commonModel::hasPriv('space', 'browse')) $this->loadModel('common')->deny('space', 'browse', false);

        $cloudApp     = $this->loadModel('store')->getAppInfo($appID);
        $versionPairs = $this->store->getVersionPairs($appID);

        $versionItems = array();
        foreach($versionPairs as $code => $version) $versionItems[] = array('text' => $version, 'value' => $code);
        $cloudApp->versionList = $versionItems;

        echo json_encode($cloudApp);
    }
}
