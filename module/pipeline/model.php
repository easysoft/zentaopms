<?php
declare(strict_types=1);
/**
 * The model file of pipeline module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     pipeline
 * @link        https://www.zentao.net
 */

class pipelineModel extends model
{
    /**
     * 根据id获取一条服务器记录。
     * Get a pipeline by id.
     *
     * @param  int    $id
     * @access public
     * @return object|false
     */
    public function getByID(int $id): object|false
    {
        $pipeline = $this->dao->select('*')->from(TABLE_PIPELINE)->where('id')->eq($id)->fetch();
        if($pipeline && !empty($pipeline->password)) $pipeline->password = base64_decode($pipeline->password);
        return $pipeline;
    }

    /**
     * 根据名称及类型获取一条流水线记录
     * Get a pipeline by name and type.
     *
     * @param  string $name
     * @param  string $type
     * @access public
     * @return object|false
     */
    public function getByNameAndType(string $name, string $type): object|false
    {
        return $this->dao->select('id')->from(TABLE_PIPELINE)->where('name')->eq($name)->andWhere('type')->eq($type)->fetch();
    }

    /**
     * 根据url获取渠成创建的代码库。
     * Get a pipeline by url which created by quickon.
     *
     * @param  string $url
     * @access public
     * @return object|false
     */
    public function getByUrl(string $url): object|false
    {
        return $this->dao->select('id')->from(TABLE_PIPELINE)->where('url')->eq($url)->andWhere('createdBy')->eq('system')->fetch();
    }

    /**
     * 获取服务器列表。
     * Get pipeline list.
     *
     * @param  string $type
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList(string $type = 'jenkins', string $orderBy = 'id_desc', object|null $pager = null): array
    {
        return $this->dao->select('*')->from(TABLE_PIPELINE)
            ->where('deleted')->eq('0')
            ->beginIF($type)->AndWhere('type')->in($type)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 获取服务器列表。
     * Get pipeline pairs.
     *
     * @param  string $type
     * @access public
     * @return array
     */
    public function getPairs(string $type = ''): array
    {
        return $this->dao->select('id,name')->from(TABLE_PIPELINE)
            ->where('deleted')->eq('0')
            ->beginIF($type)->AndWhere('type')->eq($type)->fi()
            ->orderBy('id')->fetchPairs('id', 'name');
    }

    /**
     * 创建服务器。
     * Create a server.
     *
     * @access public
     * @return bool
     */
    public function create(object $pipeline): int|false
    {
        $type = $pipeline->type;
        if($type == 'gitlab') $pipeline->url = rtrim($pipeline->url, '/');

        if(isset($pipeline->password)) $pipeline->password = base64_encode($pipeline->password);

        $this->dao->insert(TABLE_PIPELINE)->data($pipeline)
            ->batchCheck($this->config->pipeline->create->requiredFields, 'notempty')
            ->batchCheck("url", 'URL')
            ->check('name', 'unique', "`type` = '$type'")
            ->checkIF($type == 'jenkins', 'account', 'notempty')
            ->checkIF($type == 'jenkins' and !$pipeline->token, 'password', 'notempty')
            ->checkIF($type == 'jenkins' and !$pipeline->password, 'token', 'notempty')
            ->autoCheck()
            ->exec();
        if(dao::isError()) return false;

        return $this->dao->lastInsertId();
    }

    /**
     * 更新服务器。
     * Update a server.
     *
     * @param  int    $id
     * @access public
     * @return bool
     */
    public function update(int $id): bool
    {
        $pipeline = fixer::input('post')
            ->add('editedBy', $this->app->user->account)
            ->add('editedDate', helper::now())
            ->trim('url,token,account,password')
            ->skipSpecial('url,token,account,password')
            ->get();

        $type = $this->dao->select('type')->from(TABLE_PIPELINE)->where('id')->eq($id)->fetch('type');
        if($type == 'gitlab') $pipeline->url = rtrim($pipeline->url, '/');
        if(isset($pipeline->password)) $pipeline->password = base64_encode($pipeline->password);

        $this->dao->update(TABLE_PIPELINE)->data($pipeline)
            ->batchCheck($this->config->pipeline->edit->requiredFields, 'notempty')
            ->batchCheck("url", 'URL')
            ->check('name', 'unique', "`type` = '$type' and id <> $id")
            ->checkIF($type == 'jenkins', 'account', 'notempty')
            ->checkIF($type == 'jenkins' and !$pipeline->token, 'password', 'notempty')
            ->checkIF($type == 'jenkins' and !$pipeline->password, 'token', 'notempty')
            ->autoCheck()
            ->where('id')->eq($id)
            ->exec();

        return !dao::isError();
    }

    /**
     * 删除服务器。
     * Delete one record.
     *
     * @param  string $id   the id to be deleted
     * @param  string $type the action object
     * @access public
     * @return int|bool
     */
    public function deleteByObject(int $id, string $type = 'gitlab'): int|bool
    {
        if(in_array($type, array('gitlab', 'gitea', 'gogs')))
        {
            $repo = $this->dao->select('*')->from(TABLE_REPO)
                ->where('deleted')->eq('0')
                ->andWhere('SCM')->eq(ucfirst($type))
                ->andWhere('serviceHost')->eq($id)
                ->fetch();
            if($repo) return false;
        }
        elseif($type == 'sonarqube')
        {
            $job = $this->dao->select('id,name,repo,deleted')->from(TABLE_JOB)
                ->where('frame')->eq('sonarqube')
                ->andWhere('server')->eq($id)
                ->andWhere('deleted')->eq('0')
                ->fetch();
            if($job) return false;
        }
        $this->dao->update(TABLE_PIPELINE)->set('deleted')->eq(1)->where('id')->eq($id)->exec();
        $this->loadModel('action')->create($type, $id, 'deleted', '');

        $actionID = $this->dao->lastInsertID();
        return $actionID;
    }
}
