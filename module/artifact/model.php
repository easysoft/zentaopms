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
     * @param  int $space
     * @param  int $repoID
     * @param  string $type
     * @access public
     * @return array
     */
    public function getList(int $space = 0, int $repoID = 0, string $type = 'space', string $orderBy = 'id_desc'): array
    {
        return $this->dao->select('*')->from(TABLE_ARTIFACT)
            ->where('deleted')->eq(0)
            ->beginIF($type != 'all')->andWhere('type')->eq($type)
            ->andWhere('spaceID')->eq($space)
            ->beginIF($repoID)->andWhere('repoID')->eq($repoID)->fi()
            ->orderBy($orderBy)
            ->fetchAll('id');
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
     * @return bool
     */
    public function uploadArtifact(int $artifactID, array $file, string $path = ''): bool
    {
        $artifact = $this->fetchByID($artifactID);
        if(empty($artifact)) return false;

        $param = array();
        $param['artifactID'] = $artifactID;
        $param['group']      = str_replace('/', '.', ltrim($path, '/'));
        $param['file']       = curl_file_create($file['tmp_name']);

        $apiRoot = $this->loadModel('gitfox')->getApiRoot();
        $result  = json_decode(common::http(sprintf($apiRoot->url, "/artifacts/upload/{$artifact->format}"), $param, array(), $apiRoot->header, 'data', 'POST', 300));
        $result  = $this->gitfox->getResponse($result);
        return !dao::isError();
    }
}
