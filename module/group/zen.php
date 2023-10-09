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

        /* Subsets in resource but not in package. */
        $this->group->sortResource();
        foreach($this->lang->resource as $module => $methodList)
        {
            foreach($methodList as $method => $methodLang)
            {
                if(isset($privs["$module-$method"])) continue;

                if(!isset($subsets[$module]))
                {
                    $subsets[$module]  = isset($this->lang->$module) && isset($this->lang->$module->common) ? $this->lang->$module->common : $module;
                    $packages[$module] = array('other' => $this->lang->group->other);
                }
            }
        }

        $this->view->title    = $this->lang->company->common . $this->lang->colon . $this->lang->group->managePriv;
        $this->view->groups   = $this->group->getPairs();
        $this->view->subsets  = $subsets;
        $this->view->packages = $packages;
        $this->view->privs    = $this->group->getPrivByParents(key($subsets));
    }
}
