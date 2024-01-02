<?php
/**
 * The control file of space module of QuCheng.
 *
 * @copyright Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   space
 * @version   $Id$
 * @link      https://www.qucheng.com
 */
class space extends control
{
    /**
     * Browse departments and users of a space.
     *
     * @param  int    $param
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
       @access public
     * @return void
     */
    public function browse($spaceID = null, $browseType = 'all', $orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        if(!commonModel::hasPriv('space', 'browse')) $this->loadModel('common')->deny('space', 'browse', false);
        $this->app->loadLang('instance');
        $this->loadModel('instance');
        $this->loadModel('store');

        $spaceType = $this->cookie->spaceType ? $this->cookie->spaceType : 'bycard';

        $space = null;
        if($spaceID)      $space = $this->space->getByID($spaceID);
        if(empty($space)) $space = $this->space->defaultSpace($this->app->user->account);

        $search = '';
        if(!empty($_POST))
        {
            $conditions = fixer::input('post')
                ->trim('search')
                ->setDefault('search', '')
                ->get();
            $search = $conditions->search;
        }

        $instances = $this->space->getSpaceInstances(0, $browseType, $search);
        foreach($instances as $instance)
        {
            $instance->externalID = 0;
            $instance->orgID      = $instance->id;
            $instance->type       = 'store';

            if(in_array($instance->appName, $this->config->space->zentaoApps))
            {
                $externalApp = $this->space->getExternalAppByApp($instance);
                if($externalApp) $instance->externalID = $externalApp->id;
            }
        }
        $maxID     = 0;
        $pipelines = array();
        if($browseType == 'all' || $browseType == 'running') $pipelines = $this->loadModel('pipeline')->getList('', 'id_desc');
        if(!empty($instances)) $maxID = max(array_keys($instances));
        foreach($pipelines as $key => $pipeline)
        {
            if($pipeline->createdBy == 'system') unset($pipelines[$key]);

            $pipeline->createdAt  = $pipeline->createdDate;
            $pipeline->appName    = $this->lang->space->appType[$pipeline->type];
            $pipeline->status     = 'running';
            $pipeline->type       = 'external';
            $pipeline->externalID = $pipeline->id;
            $pipeline->orgID      = $pipeline->id;
            $pipeline->id         = ++ $maxID;
        }
        $allInstances = array_merge($instances, $pipelines);

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

        $this->view->title        = $this->lang->space->common;
        $this->view->position[]   = $this->lang->space->common;
        $this->view->pager        = $pager;
        $this->view->orderBy      = $orderBy;
        $this->view->solutionID   = $solutionID;
        $this->view->browseType   = $browseType;
        $this->view->spaceType    = $spaceType;
        $this->view->instances    = (empty($allInstances) or empty($allInstances[$pageID - 1])) ? array() : $allInstances[$pageID - 1];
        $this->view->currentSpace = $space;
        $this->view->searchName   = $search;
        $this->view->users        = $this->loadModel('user')->getPairs('noclosed,noletter');
        $this->view->sortLink     = $this->createLink('space', 'browse', "spaceID=&browseType={$browseType}&orderBy={orderBy}&recTotal={$recTotal}&recPerPage={$recPerPage}");

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
        foreach($versionPairs as $k => $v) $versionItems[] = array('text' => $v, 'value' => $k);
        $cloudApp->versionList = $versionItems;

        return print(json_encode($cloudApp));
    }
}
