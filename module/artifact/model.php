<?php
declare(strict_types=1);

use function zin\img;

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
     * 获取制品库目录结构。
     * Get artifact folder structure.
     *
     * @param  object $artifact
     * @param  string $path
     * @param  string $selectPath
     * @access public
     * @return array
     */
    public function getArtifactTreeData(object $artifact, string $path = '/', string $selectPath = '', int $spaceID = 0, int $repoID = 0, $type = 'space'): array
    {
        $items = array();
        $nodes = $this->getArtifactNodes($artifact, $path);
        if(empty($nodes)) return $items;

        $items = array();
        if(empty($selectNode)) $selectNode = array();
        foreach($nodes as $node)
        {
            if(!isset($node->metadata)) continue;
            $path = helper::safe64Encode($node->path);
            $item = new stdclass();
            $item->id     = $path;
            $item->name   = $node->name;
            $item->text   = $node->name;
            $item->path   = $node->path;
            $item->format = $node->format;
            $item->kind   = $node->metadata->type == 'group' ? 'dir' : 'file';
            $item->active = $node->path == $selectPath;

            $item->url = helper::createLink('artifact', 'view', "artifactID={$artifact->id}&spaceID={$spaceID}&repoID={$repoID}&type={$type}&selectPath={$path}");
            if($item->kind == 'dir')
            {
                $baseSelectPath = helper::safe64Encode($selectPath);
                $item->items = array('url' => helper::createLink('artifact', 'ajaxGetFolders', "artifactID={$artifact->id}&path={$path}&selectPath={$baseSelectPath}&spaceID={$spaceID}&repoID={$repoID}&type={$type}"));
            }
            $item->actions = array
            (
                array
                (
                    'key'      => 'more',
                    'icon'     => 'ellipsis-v',
                    'hint'     => $this->lang->more,
                    'type'     => 'dropdown',
                    'dropdown' => array
                    (
                        'items' => array
                        (
                            array('key' => 'createDir', 'data-toggle' => 'modal', 'text' => $this->lang->artifact->createDir, 'url' => helper::createLink('artifact', 'createDir', "artifactID={$artifact->id}&path={$path}&isSubDir=0&spaceID={$spaceID}&repoID={$repoID}&type={$type}")),
                        )
                    )
                )
            );
            $items[] = $item;
        }

        return $items;
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
}
