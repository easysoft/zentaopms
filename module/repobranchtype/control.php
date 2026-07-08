<?php
declare(strict_types=1);

use zin\set;

/**
 * The control file of repobranchtype module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      DaoGang Li <lidaogang@chandao.com>
 * @package     repobranchtype
 * @link        https://www.zentao.net
 */
class repobranchtype extends control
{
    /**
     * 浏览分支类型。
     * Browse branch type.
     *
     * @param  int    $repoID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse(int $repoID = 0, string $orderBy = 'id_asc', int $recTotal = 0, int $recPerPage = 20, int $pageID = 1)
    {
        /* 设置菜单。 */
        if($repoID)
        {
            $this->loadModel('ci')->setMenu($repoID);
        }
        else
        {
            $this->loadModel('space')->setMenu();
        }

        $repo = $this->loadModel('repo')->getByID($repoID);
        if($repo === false) $repo = null;
        $gitfoxID = $repo ? (int)zget($repo, 'id', 0) : 0;

        /* 创建分页对象。 */
        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        /* 获取分支类型列表。 */
        $branchTypeList = $this->repobranchtype->getBranchTypeList($gitfoxID, '', '', '', $orderBy, $pager);

        /* 处理前缀显示。 */
        $branchTypeList = $this->repobranchtypeZen->buildPrefixesDisplay($branchTypeList);

        $this->view->title          = $this->lang->repobranchtype->browse;
        $this->view->repoID         = $repoID;
        $this->view->repo           = $repo;
        $this->view->branchTypeList = $branchTypeList;
        $this->view->orderBy        = $orderBy;
        $this->view->pager          = $pager;

        $this->display();
    }

    /**
     * 创建代码库分支类型。
     * Create a branch type.
     *
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function create(int $repoID = 0)
    {
        /* 设置菜单。 */
        if($repoID)
        {
            $this->loadModel('ci')->setMenu($repoID);
        }
        else
        {
            $this->loadModel('space')->setMenu(0);
        }

        $repo = $this->loadModel('repo')->getByID($repoID);
        if($repo === false) $repo = null;

        if($_POST)
        {
            $formData = form::data($this->config->repobranchtype->form->create)->get();

            /* 验证分支类型数据。 */
            $this->repobranchtypeZen->validateBranchType($formData);
            if(dao::isError()) return $this->sendError(dao::getError());

            /* 调用 model 方法创建分支类型。 */
            $repoID = $repo ? (int)zget($repo, 'id', 0) : 0;
            $result   = $this->repobranchtype->apiCreateBranchType($repoID, $formData);
            if(!$result)
            {
                if(dao::isError()) return $this->sendError(dao::getError());
                return $this->sendError($this->lang->fail);
            }

            $link = $this->createLink('repobranchtype', 'browse', "repoID=$repoID");
            $this->loadModel('action')->create('repobranchtype', $result->id, 'createbranchtype');
            return $this->send(array('result' => 'success', 'message' => $this->lang->repobranchtype->tips->createSuccess, 'load' => $link));
        }

        $this->view->title  = $this->lang->repobranchtype->create;
        $this->view->repoID = $repoID;
        $this->view->repo   = $repo;

        $this->display();
    }

    /**
     * 编辑代码库分支类型。
     * Edit a branch type.
     *
     * @param  int    $repoID
     * @param  int    $typeID
     * @access public
     * @return void
     */
    public function edit(int $repoID = 0, int $typeID = 0)
    {
        /* 设置菜单。 */
        if($repoID) $this->loadModel('ci')->setMenu($repoID);

        $repo = $this->loadModel('repo')->getByID($repoID);
        if($repo === false) $repo = null;

        /* 获取分支类型详情。 */
        $branchType = $this->repobranchtype->getBranchTypeByID($typeID);
        if(empty($branchType))
        {
            return $this->send(array('result' => 'fail', 'message' => $this->lang->repobranchtype->error->notExists));
        }

        if($_POST)
        {
            $formData = form::data($this->config->repobranchtype->form->edit)->get();

            /* 验证分支类型数据。 */
            $this->repobranchtypeZen->validateBranchType($formData, $typeID);
            if(dao::isError()) return $this->sendError(dao::getError());

            /* 调用 model 方法更新分支类型。 */
            $result = $this->repobranchtype->apiUpdateBranchType($repo, $typeID, $formData);
            if(!$result)
            {
                if(dao::isError()) return $this->sendError(dao::getError());
                return $this->sendError($this->lang->fail);
            }

            $link = $this->createLink('repobranchtype', 'browse', "repoID=$repoID");
            $this->loadModel('action')->create('repobranchtype', $typeID, 'edited');
            return $this->send(array('result' => 'success', 'message' => $this->lang->repobranchtype->tips->updateSuccess, 'load' => $link));
        }

        /* 解析前缀为数组。 */
        $prefixes = $branchType->prefixes;
        if(empty($prefixes)) $prefixes = array('');

        $this->view->title      = $this->lang->repobranchtype->edit;
        $this->view->repoID     = $repoID;
        $this->view->repo       = $repo;
        $this->view->branchType = $branchType;
        $this->view->prefixes   = $prefixes;

        $this->display();
    }

    /**
     * 删除代码库分支类型。
     * Delete a branch type.
     *
     * @param  int    $repoID
     * @param  int    $typeID
     * @access public
     * @return void
     */
    public function delete(int $repoID = 0, int $typeID = 0)
    {
        /* 设置菜单。 */
        if($repoID) $this->loadModel('ci')->setMenu($repoID);

        $repo = $this->loadModel('repo')->getByID($repoID);
        if($repo === false) $repo = null;

        /* 获取分支类型信息。 */
        $branchType = $this->repobranchtype->getBranchTypeByID($typeID);
        if(!$branchType) return $this->sendError($this->lang->repobranchtype->error->notExists);

        /* 调用 API 删除分支类型。 */
        $result = $this->repobranchtype->apiDeleteBranchType($repo, $typeID);
        if(dao::isError())
        {
            $errorMessage = dao::getError(true);
            return $this->sendError(is_array($errorMessage) ? implode("\n", $errorMessage) : $errorMessage);
        }
        if(!$result) return $this->sendError($this->lang->fail);

        $link = $this->createLink('repobranchtype', 'browse', "repoID=$repoID");
        $this->loadModel('action')->create('repobranchtype', $typeID, 'deleted');
        return $this->send(array('result' => 'success', 'message' => $this->lang->deleteSuccess, 'load' => $link));
    }

    /**
     * 导入分支类型。
     * Import branch type.
     *
     * @param  int    $repoID
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function import(int $repoID = 0, string $orderBy = 'id_asc', int $recTotal = 0, int $recPerPage = 100, int $pageID = 1)
    {
        /* 设置菜单。 */
        if($repoID) $this->loadModel('ci')->setMenu($repoID);

        $repo = $this->loadModel('repo')->getByID($repoID);
        if($repo === false) $repo = null;

        if(!empty($_POST))
        {
            $branchTypeIDs = $this->post->branchTypes;
            $result        = $this->repobranchtype->importBranchTypes($repo, $branchTypeIDs);
            if(!$result)
            {
                $isError = dao::isError();
                $errorMessage = $isError ? dao::getError(true) : $this->lang->fail;
                return $this->sendError(is_array($errorMessage) ? implode("\n", $errorMessage) : $errorMessage);
            }

            return $this->send(array('result' => 'success', 'message' => $this->lang->repobranchtype->tips->importSuccess, 'load' => $this->createLink('repobranchtype', 'browse', "repoID=$repoID")));
        }

        /* 获取当前 repo 已有的分支类型（用于排除）。 */
        $repoID         = $repo ? (int)zget($repo, 'id', 0) : 0;
        $branchTypeList = $this->repobranchtype->getBranchTypeList($repoID, '', '', '', $orderBy);
        $existingKeys   = helper::arrayColumn($branchTypeList, 'key');

        /* 获取全局模板列表（repoID=0），并排除已存在的分支类型。 */
        $tempBranchTypeList = $this->repobranchtype->getBranchTypeList(0, '', '', '', $orderBy);
        $tempBranchTypeList = array_filter($tempBranchTypeList, function($branchType) use ($existingKeys) { return !in_array($branchType->key, $existingKeys); });

        /* 创建分页对象，基于过滤后的数据进行分页。 */
        $this->app->loadClass('pager', true);
        $filteredTotal = count($tempBranchTypeList);
        $pager         = new pager($filteredTotal, $recPerPage, $pageID);

        /* 对过滤后的数据进行手动分页。 */
        $offset             = ($pager->pageID - 1) * $pager->recPerPage;
        $tempBranchTypeList = array_slice($tempBranchTypeList, $offset, $pager->recPerPage);

        /* 处理前缀显示。 */
        $tempBranchTypeList = $this->repobranchtypeZen->buildPrefixesDisplay($tempBranchTypeList);

        $this->view->title          = $this->lang->repobranchtype->import;
        $this->view->repoID         = $repoID;
        $this->view->repo           = $repo;
        $this->view->branchTypeList = $tempBranchTypeList;
        $this->view->orderBy        = $orderBy;
        $this->view->pager          = $pager;

        $this->display();
    }
}
