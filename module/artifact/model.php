<?php
declare(strict_types=1);

/**
 * The model file of artifact module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     artifact
 * @link        https://www.zentao.net
 */
class artifactModel extends model
{
    /**
     * 获取流水线列表。
     * Get pipeline list.
     *
     * @param  int $spaceID
     * @param  int $repoID
     * @param  string $scope
     * @param  ?object $pager
     * @access public
     * @return array
     */
    public function getList(int $spaceID = 0, int $repoID = 0, string $scope = 'space', string $orderBy = 'id_desc', ?object $pager = null): array
    {
        $this->loadModel('space');
        $space = $this->space->getByID($spaceID);
        if(!empty($space) && $space->acl != 'open' && !$this->app->user->admin)
        {
            if(empty($space->members) || !isset($space->members[$this->app->user->account])) return array();
        }

        $repos = array();
        if(!$repoID && !$this->app->user->admin)
        {
            $spaceRepos   = $this->space->getReposBySpace($spaceID);
            $privateRepos = $this->dao->select('repo')->from(TABLE_DEVOPSREPOUSER)
                ->where('account')->eq($this->app->user->account)
                ->fetchPairs();
            foreach($spaceRepos as $repo)
            {
                if($repo->acl == 'open')
                {
                    $repos[] = $repo->id;
                    continue;
                }
                if(!isset($privateRepos[$repo->id])) continue;
                $repos[] = $repo->id;
            }
        }
        return $this->dao->select('*')->from(TABLE_ARTIFACT)
            ->where('deleted')->eq(0)
            ->beginIF($scope != 'all')->andWhere('scope')->eq($scope)->fi()
            ->andWhere('spaceID')->eq($spaceID)
            ->beginIF($repoID && $scope != 'all')->andWhere('repoID')->eq($repoID)->fi()
            ->beginIF(!$repoID && !empty($repos))->andWhere('repoID')->in($repos)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 获取制品库键值对。
     * Get artifact repo pairs.
     *
     * @param  string $scope
     * @param  string $type
     * @param  string $account
     * @access public
     * @return array
     */
    public function getPairs(string $scope = '', string $type = '', string $account = ''): array
    {
        $spaceIdList = $repoIdList = array();
        if($account && $scope == 'space')
        {
            $spaceIdList = $this->dao->select('t1.id')->from(TABLE_SPACE)->alias('t1')
                ->leftJoin(TABLE_DEVOPSSPACEUSER)->alias('t2')->on('t1.id=t2.space')
                ->where('t1.deleted')->eq(0)
                ->andWhere('(t1.acl')->eq('open')
                ->orWhere('t2.account')->eq($account)->markRight()
                ->fetchPairs('id');
        }

        if($account && $scope == 'repo')
        {
            $repoIdList = $this->dao->select('t1.id')->from(TABLE_REPO)->alias('t1')
                ->leftJoin(TABLE_DEVOPSREPOUSER)->alias('t2')->on('t1.id=t2.repo')
                ->where('t1.deleted')->eq(0)
                ->andWhere('(t1.acl')->eq('open')
                ->orWhere('t2.account')->eq($account)->markRight()
                ->fetchPairs('id');
        }

        return $this->dao->select('id, name')->from(TABLE_ARTIFACT)
            ->where('deleted')->eq(0)
            ->beginIF($scope)->andWhere('scope')->eq($scope)->fi()
            ->beginIF(!empty($spaceIdList))->andWhere('spaceID')->in($spaceIdList)->fi()
            ->beginIF(!empty($repoIdList))->andWhere('repoID')->in($repoIdList)->fi()
            ->beginIF($type)->andWhere('type')->eq($type)->fi()
            ->fetchPairs('id');
    }

    /**
     * 获取制品库节点。
     * Get artifact nodes.
     *
     * @param  object $artifact
     * @param  string $path
     * @access public
     * @return array
     */
    public function getArtifactNodes(object $artifact, string $path): array
    {
        $nodes = array();
        if(empty($artifact->id)) return $nodes;

        $param = array();
        $param['artifactID'] = $artifact->id;
        $param['format']     = empty($artifact->format) ? $artifact->type : $artifact->format;
        $param['level']      = 'asset';
        $param['path']       = $path;
        $param['type']       = $artifact->scope;
        $param['spaceID']    = $artifact->spaceID;
        $param['repoID']     = $artifact->repoID;

        $nodes = $this->loadModel('gitfox')->request('/artifacts/nodes', 'POST', $param);
        if(dao::isError()) return array();

        return $nodes;
    }

    /**
     * 上传制品.
     * Upload artifact.
     *
     * @param  int $artifactID
     * @param  array $file
     * @param  string $path
     * @access public
     * @return bool|array|object
     */
    public function uploadArtifact(int $artifactID, array $file, string $path = ''): bool|array|object
    {
        $artifact = $this->fetchByID($artifactID);
        if(empty($artifact)) return false;

        $param = array();
        $param['artifactID'] = $artifactID;
        $param['name']       = pathinfo($file['name'], PATHINFO_BASENAME);
        $param['group']      = str_replace('/', '.', ltrim($path, '/'));
        $param['file']       = curl_file_create($file['tmp_name']);

        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $result  = json_decode(common::http(sprintf($apiRoot->url, "/artifacts/upload/{$artifact->type}"), $param, array(), $apiRoot->header, 'data', 'POST', 300));
        return $this->gitfox->getResponse($result);
    }

    /**
     * 获取制品列表。
     * Get artifact list.
     *
     * @param  string  $entityID
     * @param  int     $artifactID
     * @param  string  $orderBy
     * @param  ?object $pager
     * @access public
     * @return array
     */
    public function getAssetListByNodeID(string $entityID, int $artifactID = 0, string $orderBy = 'editedDate_desc', ?object $pager = null): array
    {
        if(!$entityID) return array();
        list($sort, $order) = explode('_', $orderBy);

        $params = array();
        $params['artifactID'] = (int)$artifactID;
        $params['entityID']   = $entityID;
        $params['page']       = is_null($pager) ? 1 : $pager->pageID;
        $params['pageSize']   = is_null($pager) ? 20 : $pager->recPerPage;
        $params['sort']       = $sort;
        $params['order']      = $order;
        $params['more']       = true;

        if(strpos($entityID, 'asset.') === 0)
        {
            $assetID = substr($entityID, 6);
            $asset   = $this->loadModel('gitfox')->request("/artifacts/assets/{$assetID}");

            $assetList = new stdclass();
            $assetList->data = array($asset);

            $assetList->pager = new stdclass();
            $assetList->pager->total    = 1;
            $assetList->pager->pageSize = $pager->recPerPage;
            $assetList->pager->page     = $pager->pageID;
        }
        else
        {
            $assetList = $this->loadModel('gitfox')->request('/artifacts/assets/list', 'POST', $params);
        }
        if(!empty($assetList) && !empty($assetList->pager) && !is_null($pager))
        {
            $pager->recTotal   = $assetList->pager->total;
            $pager->recPerPage = $assetList->pager->pageSize;
            $pager->pageID     = $assetList->pager->page;
        }
        if(empty($assetList) || empty($assetList->data)) return array();

        foreach($assetList->data as $asset)
        {
            $asset->group      = isset($asset->metadata) && !empty($asset->metadata->group) ? $asset->metadata->group : '';
            $asset->name       = $asset->path;
            $asset->path       = $asset->group;
            $asset->version    = isset($asset->metadata) ? $asset->metadata->version : '';
            $asset->checkValue = empty($asset->checksum) ? '' : $asset->checksum->md5;
            $asset->size       = empty($asset->size)     ? 0 : $this->parseArtifactSize((string)$asset->size);
            $asset->artifactID = $artifactID;
            $asset->package    = $asset->format == 'container' ? $asset->metadata->image : zget($asset, 'package');
            $asset->sysArch    = empty($asset->os) || empty($asset->arch) ? '' : $asset->os . '/' . $asset->arch;
        }
        return $assetList->data;
    }

    /**
     * 回收站恢复.
     * Restore artifact from recycle.
     *
     * @param  object $action
     * @access public
     * @return bool
     */
    public function restoreEntity(object $action): bool
    {
        if(empty($action->objectType)) return false;

        if($action->objectType == 'artifactasset')
        {
            $entityID = 'asset.' . $action->objectID;
            $name     = $action->comment;
        }
        else
        {
            $comments = explode('|', $action->comment);
            if(empty($comments) || count($comments) < 3) return false;
            list($artifactID, $name, $entityID) = $comments;
        }

        $result = $this->loadModel('gitfox')->request('/artifacts/recycle/restore', 'POST', array('entityID' => $entityID));
        if(dao::isError())
        {
            $error = dao::getError();
            $this->app->control->sendError(empty($error['apiMessage']) ? $error : $error['apiMessage']);
        }
        if(empty($result)) return false;

        /* 在action表中更新action记录。 */
        /* Update action record in action table. */
        $this->dao->update(TABLE_ACTION)->set('extra')->eq(actionModel::BE_UNDELETED)->where('id')->eq($action->id)->exec();
        $this->loadModel('action')->create($action->objectType, $action->objectID, 'undeleted', '', $name);
        return !dao::isError();
    }

    /**
     * 判断指定的action.
     * Check if the action is clickable.
     *
     * @param  object $artifact
     * @param  string $action
     * @access public
     * @return bool
     */
    public function isClickable(object $artifact, string $action): bool
    {
        $action = strtolower($action);
        if($action == 'downloadartifact') return !empty($artifact->format) && $artifact->format == 'file';
        if($action == 'copycmd')          return !empty($artifact->format) && $artifact->format == 'container';
        if($action == 'editartifact')     return !empty($artifact->format) && $artifact->format == 'file';
        if($action == 'moveartifact')     return !empty($artifact->format) && $artifact->format == 'file';

        return true;
    }

    /**
     * 通过产品ID获取制品库信息。
     * Get artifactrepo by product ID.
     *
     * @param  int    $productID
     * @access public
     * @return array
     */
    public function getReposByProduct(int $productID): array
    {
        $repoIdList = array();
        if(!$this->app->user->admin)
        {
            $repoIdList = $this->loadModel('repo')->getRepoPairs();
            if($repoIdList) $repoIdList = array_keys($repoIdList);
        }

        $artifactRepos = $this->dao->select('t1.*')->from(TABLE_ARTIFACT)->alias('t1')
            ->innerJoin(TABLE_REPO)->alias('t2')->on('t1.repoID = t2.id')
            ->andWhere("FIND_IN_SET({$productID}, t2.`product`)")
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.scope')->eq('repo')
            ->beginIf(!empty($repoIdList))->andWhere('t1.repoID')->in($repoIdList)->fi()
            ->fetchAll('id');

        return $artifactRepos;
    }

    /**
     * 获取制品列表。
     * Get artifact list.
     *
     * @param  array $assetIdList
     * @access public
     * @return array
     */
    public function getAssetByIdList(array $assetIdList = array()): array
    {
        return $this->dao->select('t1.*, t2.size')->from(TABLE_ARTIFACTASSET)->alias('t1')
            ->leftJoin(TABLE_ARTIFACTBLOBS)->alias('t2')->on('t1.id = t2.assetID')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.id')->in($assetIdList)
            ->fetchAll();
    }

    /**
     * 解析制品大小。
     * Parse artifact size.
     *
     * @param  string $size
     * @access public
     * @return string
     */
    public function parseArtifactSize(string $size): string
    {
        if(!$size) return '';
        if($size < 1024) return $size . 'B';
        if($size < 1024 * 1024) return round($size / 1024, 2) . 'KB';
        if($size < 1024 * 1024 * 1024) return round($size / 1024 / 1024, 2) . 'MB';
        return round($size / 1024 / 1024 / 1024, 2) . 'GB';
    }

    /**
     * 通过repoID获取制品库信息。
     * Get artifact repo by repo ID.
     *
     * @param  int $repoID
     * @access public
     * @return array
     */
    public function getByRepoID(int $repoID): array
    {
        return $this->dao->select('*')->from(TABLE_ARTIFACT)
            ->where('deleted')->eq(0)
            ->andWhere('scope')->eq('repo')
            ->andWhere('repoID')->eq($repoID)
            ->fetchAll('id');
    }
}
