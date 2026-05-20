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
     * @param  string $type
     * @access public
     * @return array
     */
    public function getList(int $spaceID = 0, int $repoID = 0, string $type = 'space', string $orderBy = 'id_desc'): array
    {
        $this->loadModel('space');
        if($spaceID && !$this->app->user->admin)
        {
            $space = $this->space->getByID($spaceID);
            if(empty($space->members) || !isset($space->members[$this->app->user->account])) return array();
        }

        $repos = array();
        if($type == 'all' && !$this->app->user->admin)
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
            ->beginIF($type != 'all')->andWhere('type')->eq($type)
            ->andWhere('spaceID')->eq($spaceID)
            ->beginIF($repoID && $type != 'all')->andWhere('repoID')->eq($repoID)->fi()
            ->beginIF($type == 'all' && !empty($repos))->andWhere('repoID')->in($repos)->fi()
            ->orderBy($orderBy)
            ->fetchAll('id');
    }

    /**
     * 获取制品库键值对。
     * Get artifact repo pairs.
     *
     * @param  int $spaceID
     * @param  int $repoID
     * @param  string $type
     * @access public
     * @return array
     */
    public function getPairs(string $type = '', string $format = '', string $account = ''): array
    {
        $spaceIdList = $repoIdList = array();
        if($account && $type == 'space')
        {
            $spaceIdList = $this->dao->select('id')->from(TABLE_SPACE)->alias('t1')
                ->leftJoin(TABLE_DEVOPSSPACEUSER)->alias('t2')->on('t1.id', 't2.space')
                ->where('t1.deleted')->eq(0)
                ->andWhere('(t1.acl')->eq('open')
                ->orWhere('t2.account')->eq($account)->markRight()
                ->fetchPairs('id');
        }

        if($account && $type == 'repo')
        {
            $repoIdList = $this->dao->select('id')->from(TABLE_REPO)->alias('t1')
                ->leftJoin(TABLE_DEVOPSREPOUSER)->alias('t2')->on('t1.id', 't2.repo')
                ->where('t1.deleted')->eq(0)
                ->andWhere('(t1.acl')->eq('open')
                ->orWhere('t2.account')->eq($account)->markRight()
                ->fetchPairs('id');
        }

        return $this->dao->select('id, name')->from(TABLE_ARTIFACT)
            ->where('deleted')->eq(0)
            ->beginIF($type)->andWhere('type')->eq($type)->fi()
            ->beginIF(!empty($spaceIdList))->andWhere('spaceID')->in($spaceIdList)->fi()
            ->beginIF(!empty($repoIdList))->andWhere('repoID')->in($repoIdList)->fi()
            ->beginIF($format)->andWhere('format')->eq($format)->fi()
            ->fetchPairs('id');
    }

    /**
     * 创建制品库。
     * create artifact repo.
     *
     * @param  object $data
     * @param  string $type
     * @access public
     * @return int|false
     */
    public function create(object $data, string $type): int|false
    {
        $check = '';
        if($type == 'space')  $check = "spaceID = {$data->spaceID} and repoID = 0";
        if($type == 'repo')   $check = "repoID = {$data->repoID}";
        if($type == 'system') $check = "spaceID = 0 and repoID = 0";

        $this->dao->insert(TABLE_ARTIFACT)->data($data)
            ->batchCheck($this->config->artifact->create->requiredFields, 'notempty')
            ->check('name', 'unique', $check)
            ->autoCheck()
            ->exec();
        if(dao::isError()) return false;

        $id = $this->dao->lastInsertID();
        return $id;
    }

    /**
     * 更新制品库。
     * update artifact repo.
     *
     * @param  int    $id
     * @param  object $data
     * @access public
     * @return bool
     */
    public function update(int $id, object $data): bool
    {
        $artifact = $this->fetchByID($id);
        if(empty($artifact)) return false;

        $check = 'id != ' . $id;
        if($artifact->type == 'space')  $check .= " and spaceID = {$artifact->spaceID}";
        if($artifact->type == 'repo')   $check .= " and repoID = {$artifact->repoID}";
        if($artifact->type == 'system') $check .= " and spaceID = 0 and repoID = 0";

        $this->dao->update(TABLE_ARTIFACT)->data($data)
            ->check('name', 'unique', $check)
            ->where('id')->eq($id)
            ->autoCheck()
            ->exec();

        return !dao::isError();
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
        $param['format']     = $artifact->format;
        $param['level']      = 'asset';
        $param['path']       = $path;
        $param['type']       = $artifact->type;
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
        $result  = json_decode(common::http(sprintf($apiRoot->url, "/artifacts/upload/{$artifact->format}"), $param, array(), $apiRoot->header, 'data', 'POST', 300));
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
        $params['entityID'] = $entityID;
        $params['page']     = is_null($pager) ? 1 : $pager->pageID;
        $params['pageSize'] = is_null($pager) ? 20 : $pager->recPerPage;
        $params['sort']     = $sort;
        $params['order']    = $order;
        $params['more']     = true;

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
            $asset->group      = isset($asset->metadata) ? $asset->metadata->group : '';
            $asset->name       = $asset->path;
            $asset->path       = $asset->group;
            $asset->version    = isset($asset->metadata) ? $asset->metadata->version : '';
            $asset->checkValue = empty($asset->checksum) ? '' : $asset->checksum->md5;
            $asset->size       = empty($asset->size)     ? 0 : round($asset->size / 1024, 2) . 'KB';
            $asset->artifactID = $artifactID;
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
            $comments = explode('|', $action->comment);
            if(empty($comments) || count($comments) < 2) return false;
            $entityID = 'asset.' . $action->objectID;
            list($artifactID, $name) = $comments;
        }
        else
        {
            $comments = explode('|', $action->comment);
            if(empty($comments) || count($comments) < 3) return false;
            list($artifactID, $name, $entityID) = $comments;
        }

        $result = $this->loadModel('gitfox')->request('/artifacts/recycle/restore', 'POST', array('entityIDs' => array($entityID)));
        if(empty($result) || empty($result->success)) return false;

        /* 在action表中更新action记录。 */
        /* Update action record in action table. */
        $this->dao->update(TABLE_ACTION)->set('extra')->eq(actionModel::BE_UNDELETED)->where('id')->eq($action->id)->exec();
        $this->loadModel('action')->create($action->objectType, $action->objectID, 'undeleted', '', $artifactID . '|' . $name . '|' . $entityID);
        return !dao::isError();
    }
}
