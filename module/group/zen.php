<?php
declare(strict_types=1);
/**
 * The zen file of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     group
 * @link        https://www.zentao.net
 */
class groupZen extends group
{
    /**
     * 有些权限在resource里添加了，但没有在packagemanager里同步更新，需要追加上
     * Append packages in resource.
     *
     * @access public
     * @return void
     */
    public function appendResourcePackages()
    {
        $allPrivs = array();
        foreach($this->config->group->package as $packageCode => $package)
        {
            if(empty($package->privs)) continue;

            foreach($package->privs as $privCode => $priv)
            {
                $allPrivs[$privCode] = $privCode;
            }
        }

        /* Privs in resource but not in package. */
        foreach($this->lang->resource as $module => $methodList)
        {
            foreach($methodList as $method => $methodLang)
            {
                if(isset($allPrivs[$module . '-' . $method])) continue;

                /* Create subset. */
                if(!isset($this->config->group->subset->$module))
                {
                    $this->config->group->subset->$module = new stdclass();
                    $this->config->group->subset->$module->order = 10000;
                    $this->config->group->subset->$module->nav   = $module;
                }

                /* Create subset. */
                $methodPackage = array('create' => 'manage', 'batchcreate' => 'manage', 'browse' => 'browse', 'view' => 'browse', 'delete' => 'delete', 'batchdelete' => 'delete', 'edit' => 'manage', 'batchedit' => 'manage');
                $packageName = isset($methodPackage[$method]) ? $methodPackage[$method] : 'other';
                $packageCode = $module . $packageName;
                if(!isset($this->config->group->package->$packageCode))
                {
                    $this->config->group->package->$packageCode = new stdclass();
                    $this->config->group->package->$packageCode->order  = 5;
                    $this->config->group->package->$packageCode->subset = $module;
                    $this->config->group->package->$packageCode->privs  = array();
                    $this->lang->group->package->$packageCode = $this->lang->group->package->$packageName;
                }

                $this->appendWorkflowMenu($packageCode, $module, $method);
            }
        }
    }

    /**
     * 工作流列表页面的菜单也需要拆分不同的权限，比如全部、未关闭、已完成
     * Append priv to package of workflow browse menu.
     *
     * @param  string $packageCode
     * @param  string $module
     * @param  string $method
     * @access protected
     * @return mixed
     */
    protected function appendWorkflowMenu(string $packageCode, string $module, string $method)
    {
        /* Browse action in workflow. */
        if(isset($this->lang->$module->menus) && $method == 'browse')
        {
            $this->config->group->package->$packageCode->privs["$module-$method"] = array('edition' => 'open,biz,max,ipd', 'vision' => 'rnd,or', 'order' => 5, 'depend' => array(), 'recommend' => array());

            foreach($this->lang->$module->menus as $flowMethod => $flowName)
            {
                $this->config->group->package->$packageCode->privs["$module-$flowMethod"] = array('edition' => 'open,biz,max,ipd', 'vision' => 'rnd,or', 'order' => 5, 'depend' => array("$module-$method"), 'recommend' => array());
            }
        }
        else
        {
            $this->config->group->package->$packageCode->privs["$module-$method"] = array('edition' => 'open,biz,max,ipd', 'vision' => 'rnd,or', 'order' => 5, 'depend' => array(), 'recommend' => array());
        }
    }
    /**
     * Manage priv by package or group.
     *
     * @param  int    $groupID
     * @param  string $nav
     * @param  string $version
     * @access public
     * @return void
     */
    protected function managePrivByGroup(int $groupID = 0, string $nav = '', string $version = ''): void
    {
        $this->group->sortResource();
        $group        = $this->group->getById($groupID);
        $groupPrivs   = $this->group->getPrivs($groupID);
        $versionPrivs = $this->group->getPrivsAfterVersion($version);

        /* Subsets . */
        $subsets = array();
        foreach($this->config->group->subset as $subsetName => $subset)
        {
            $subset->code        = $subsetName;
            $subset->allCount    = 0;
            $subset->selectCount = 0;

            $subsets[$subset->code] = $subset;
        }

        $selectPrivs = $this->group->getPrivListByGroup($groupID);
        $allPrivList = $this->group->getPrivListByNav($nav, $version);

        $selectedPrivList = array();
        $packages         = array();

        foreach($allPrivList as $privCode => $priv)
        {
            $subsetCode  = $priv->subset;
            $packageCode = $priv->package;
            if(!isset($packages[$subsetCode])) $packages[$subsetCode] = array();
            if(!isset($subsets[$subsetCode]))
            {
                $subset = new stdclass();
                $subset->code        = $subsetCode;
                $subset->allCount    = 0;
                $subset->selectCount = 0;

                $subsets[$subsetCode] = $subset;
            }

            if(!isset($packages[$subsetCode][$packageCode]))
            {
                $package = new stdclass();
                $package->allCount    = 0;
                $package->selectCount = 0;
                $package->subset      = $subsetCode;
                $package->privs       = array();

                $packages[$subsetCode][$packageCode] = $package;
            }

            $packages[$subsetCode][$packageCode]->privs[$privCode] = $priv;

            $packages[$subsetCode][$packageCode]->allCount ++;
            $subsets[$subsetCode]->allCount ++;

            if(isset($selectPrivs[$privCode]))
            {
                $packages[$subsetCode][$packageCode]->selectCount ++;
                $subsets[$subsetCode]->selectCount ++;
                $selectedPrivList[] = $privCode;
            }
        }

        $allPrivList     = array_keys($allPrivList);
        $relatedPrivData = $this->group->getRelatedPrivs($allPrivList, $selectedPrivList);

        $this->view->title            = $this->lang->company->common . $this->lang->colon . $group->name . $this->lang->colon . $this->lang->group->managePriv;
        $this->view->allPrivList      = $allPrivList;
        $this->view->selectedPrivList = $selectedPrivList;
        $this->view->relatedPrivData  = $relatedPrivData;

        $this->view->group      = $group;
        $this->view->groupPrivs = $groupPrivs;
        $this->view->groupID    = $groupID;
        $this->view->nav        = $nav;
        $this->view->version    = $version;
        $this->view->subsets    = $subsets;
        $this->view->packages   = $packages;
    }

    /**
     * Manage priv by module.
     *
     * @access public
     * @return void
     */
    protected function managePrivByModule()
    {
        $this->group->loadResourceLang();

        $subsets  = array();
        $packages = array();
        $privs    = array();

        /* Subsets in package. */
        foreach($this->config->group->package as $packageCode => $packageData)
        {
            if(!isset($packageData->privs)) continue;
            foreach($packageData->privs as $privCode => $priv)
            {
                list($moduleName, $methodName) = explode('-', $privCode);

                if(strpos(',' . $priv['edition'] . ',', ',' . $this->config->edition . ',') === false) continue;
                if(strpos(',' . $priv['vision'] . ',',  ',' . $this->config->vision . ',')  === false) continue;

                /* Remove privs unused in the edition. */
                if(!isset($this->lang->resource->$moduleName) || !isset($this->lang->resource->$moduleName->$methodName)) continue;

                $subset = $packageData->subset;
                if(!isset($subsets[$subset]))
                {
                    $subsets[$subset]  = isset($this->lang->$subset) && isset($this->lang->$subset->common) ? $this->lang->$subset->common : $subset;
                    $packages[$subset] = array();
                }

                $packages[$subset][$packageCode] = isset($this->lang->group->package->$packageCode) ? $this->lang->group->package->$packageCode : $packageCode;

                $privs[$privCode] = $privCode;
            }
        }

        $this->view->title    = $this->lang->company->common . $this->lang->colon . $this->lang->group->managePriv;
        $this->view->groups   = $this->group->getPairs();
        $this->view->subsets  = $subsets;
        $this->view->packages = $packages;
        $this->view->privs    = $this->group->getPrivByParents(key($subsets));
    }
}
