<?php
/**
 * The model file of artifactrepo module of QuCheng.
 *
 * @copyright Copyright 2021-2022 北京渠成软件有限公司(BeiJing QuCheng Software Co,LTD, www.qucheng.com)
 * @license   ZPL (http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author    Jianhua Wang <wangjianhua@easycorp.ltd>
 * @package   artifactrepo
 * @version   $Id$
 * @link      https://www.qucheng.com
 */
class artifactrepoModel extends model
{
    /**
     * 根据id获取制品库。
     * Get artifactrepo by id.
     *
     * @param  int    $artifactRepoID
     * @access public
     * @return object
     */
    public function getByID(int $artifactRepoID)
    {
        $artifactRepo =  $this->dao->select('t1.*, t2.url, t2.name as serverName')->from(TABLE_ARTIFACTREPO)->alias('t1')
            ->leftJoin(TABLE_PIPELINE)->alias('t2')->on('t1.serverID = t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.id')->eq($artifactRepoID)
            ->fetch();
        $artifactRepo->url .= '/repository/' . $artifactRepo->repoName;

        return $artifactRepo;
    }

    /**
     * 获取制品库列表。
     * Get artifactrepo repo list.
     *
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($orderBy = 'id_desc', $pager = null)
    {
        $artifactRepos = $this->dao->select('t1.*, t2.id AS pipelineID, t2.url')->from(TABLE_ARTIFACTREPO)->alias('t1')
            ->leftJoin(TABLE_PIPELINE)->alias('t2')->on('t1.serverID = t2.id')
            ->where('t1.deleted')->eq(0)
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
        foreach($artifactRepos as $repo)
        {
            $repo->url .= '/repository/' . $repo->repoName . '/';
        }

        return $artifactRepos;
    }

    /**
     * 获取服务器上仓库列表。
     * Get server repos.
     *
     * @param  int    $serverID
     * @access public
     * @return array
     */
    public function getServerRepos(int $serverID): array
    {
        $server = $this->loadModel('pipeline')->getByID($serverID);
        if(!$server) return array();

        if($server->type == 'nexus')
        {
            $url  = $server->url . '/service/rest/v1/repositorySettings';
            $auth = "{$server->account}:{$server->password}";

            $response = common::http($url, '', array(CURLOPT_USERPWD => $auth), array(), 'data', 'POST', 10, true);
            $data = array('result' => $response[1] == 200, 'data' => json_decode($response['body']));
            return $data;
        }

        return array();
    }

    /**
     * 创建一个制品库。
     * Create a artifact repo.
     *
     * @param  object $repo
     * @access public
     * @return false|int
     */
    public function create(object $artifactRepo): false|int
    {
        $this->dao->insert(TABLE_ARTIFACTREPO)->data($artifactRepo)
            ->check('name', 'unique', "name = '{$artifactRepo->name}'")
            ->check('repoName', 'unique', "serverID = {$artifactRepo->serverID} and repoName = '{$artifactRepo->repoName}'")
            ->autoCheck()
            ->exec();

        if(dao::isError()) return false;
        return $this->dao->lastInsertID();
    }

    /**
     * 更新一个制品库。
     * Update a artifact repo.
     *
     * @param  object $artifactRepo
     * @param  int    $artifactRepoID
     * @access public
     * @return void
     */
    public function update(object $artifactRepo, $artifactRepoID): array|false
    {
        $oldArtifactRepo = $this->getByID($artifactRepoID);

        $this->dao->update(TABLE_ARTIFACTREPO)->data($artifactRepo)
            ->autoCheck()
            ->check('name', 'unique', "name = '{$artifactRepo->name}' and id != {$artifactRepoID}")
            ->where('id')->eq($artifactRepoID)
            ->exec();
        if(dao::isError()) return false;

        $changes = common::createChanges($oldArtifactRepo, $artifactRepo);
        if($changes)
        {
            $actionID = $this->loadModel('action')->create('artifactRepo', $artifactRepoID, 'edited');
            $this->action->logHistory($actionID, $changes);
        }

        return $changes;
    }

    public function getReposByProduct(int $productID)
    {
        $artifactRepos = $this->dao->select('t1.*, t2.id AS pipelineID, t2.url')->from(TABLE_ARTIFACTREPO)->alias('t1')
            ->leftJoin(TABLE_PIPELINE)->alias('t2')->on('t1.serverID = t2.id')
            ->where('products')->like("%,{$productID},%")
            ->andWhere('t1.deleted')->eq(0)
            ->fetchAll('id');
        foreach($artifactRepos as $repo)
        {
            $repo->url .= '/repository/' . $repo->repoName;
        }

        return $artifactRepos;
    }
}
