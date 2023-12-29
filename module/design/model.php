<?php
declare(strict_types=1);
/**
 * The model file of design module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     design
 * @version     $Id: model.php 5107 2020-09-02 09:46:12Z tianshujie@easycorp.ltd $
 * @link        https://www.zentao.net
 */
class designModel extends model
{
    /**
     * 创建一个设计。
     * Create a design.
     *
     * @param  object   $design
     * @access public
     * @return bool|int
     */
    public function create(object $design): bool|int
    {
        $design = $this->loadModel('file')->processImgURL($design, 'desc', (string)$this->post->uid);
        $this->dao->insert(TABLE_DESIGN)->data($design)
            ->autoCheck()
            ->batchCheck($this->config->design->create->requiredFields, 'notempty')
            ->exec();

        if(dao::isError()) return false;

        $designID = $this->dao->lastInsertID();
        $this->file->updateObjectID($this->post->uid, $designID, 'design');
        $files = $this->file->saveUpload('design', $designID);

        $spec = new stdclass();
        $spec->design  = $designID;
        $spec->version = 1;
        $spec->name    = $design->name;
        $spec->desc    = $design->desc;
        $spec->files   = empty($files) ? '' : implode(',', array_keys($files));
        $this->dao->insert(TABLE_DESIGNSPEC)->data($spec)->exec();

        return $designID;
    }

    /**
     * 批量创建设计。
     * Batch create designs.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @param  array  $designs
     * @access public
     * @return bool
     */
    public function batchCreate(int $projectID = 0, int $productID = 0, array $designs = array()): bool
    {
        $this->loadModel('action');
        foreach($designs as $rowID => $design)
        {
            $design->product = $productID;
            $design->project = $projectID;
            $this->dao->insert(TABLE_DESIGN)->data($design)->autoCheck()->batchCheck($this->config->design->batchcreate->requiredFields, 'notempty')->exec();

            if(dao::isError())
            {
                foreach(dao::getError() as $field => $error) dao::$errors["{$field}[{$rowID}]"] = $error;
                return false;
            }

            $designID = $this->dao->lastInsertID();
            $this->action->create('design', $designID, 'Opened');
        }

        return true;
    }

    /**
     * 编辑一个设计。
     * Update a design.
     *
     * @param  int        $designID
     * @param  object     $design
     * @access public
     * @return bool|array
     */
    public function update(int $designID = 0, object $design = null): bool|array
    {
        $oldDesign = $this->getByID($designID);
        if(!$oldDesign) return false;

        $design = $this->loadModel('file')->processImgURL($design, 'desc', (string)$this->post->uid);
        $this->dao->update(TABLE_DESIGN)->data($design)->autoCheck()->batchCheck($this->config->design->edit->requiredFields, 'notempty')->where('id')->eq($designID)->exec();

        if(dao::isError()) return false;

        $this->file->updateObjectID($this->post->uid, $designID, 'design');
        $files         = $this->file->saveUpload('design', $designID);
        $designChanged = ($oldDesign->name != $design->name || $oldDesign->desc != $design->desc || !empty($files));
        if($designChanged)
        {
            $version = $oldDesign->version + 1;

            $spec = new stdclass();
            $spec->design  = $designID;
            $spec->version = $version;
            $spec->name    = $design->name;
            $spec->desc    = $design->desc;
            $spec->files   = empty($files) ? '' : implode(',', array_keys($files));
            $this->dao->insert(TABLE_DESIGNSPEC)->data($spec)->exec();

            $this->dao->update(TABLE_DESIGN)->set('version')->eq($version)->where('id')->eq($designID)->exec();
        }

        return common::createChanges($oldDesign, $design);
    }

    /**
     * 更新设计的指派人。
     * Update assign of design.
     *
     * @param  int        $designID
     * @param  object     $design
     * @access public
     * @return array|bool
     */
    public function assign(int $designID = 0, object $design = null): array|bool
    {
        $oldDesign = $this->getByID($designID);
        if(!$oldDesign) return false;

        $this->dao->update(TABLE_DESIGN)->data($design)->autoCheck()->where('id')->eq($designID)->exec();

        if(dao::isError()) return false;
        return common::createChanges($oldDesign, $design);
    }

    /**
     * 设计关联代码提交。
     * Design link commits.
     *
     * @param  int    $designID
     * @param  int    $repoID
     * @param  array  $revisions
     * @access public
     * @return bool
     */
    public function linkCommit(int $designID = 0, int $repoID = 0, array $revisions = array()): bool
    {
        $repo = $this->loadModel('repo')->getByID($repoID);
        if(!isset($repo->SCM)) return true;

        /* If the repo type is Gitlab, first store the commit log in the repohistory table and get the commit ID. */
        if($repo->SCM == 'Gitlab')
        {
            $logs = array();
            foreach($this->session->designRevisions as $commit)
            {
                if(in_array($commit->revision, $revisions))
                {
                    $log = new stdclass();
                    $log->committer = $commit->committer;
                    $log->revision  = $commit->revision;
                    $log->comment   = $commit->comment;
                    $log->time      = date('Y-m-d H:i:s', strtotime($commit->time));
                    $logs[] = $log;
                }
            }
            $this->repo->saveCommit($repoID, array('commits' => $logs), 0);
            $revisions = $this->dao->select('id')->from(TABLE_REPOHISTORY)->where('revision')->in($revisions)->andWhere('repo')->eq($repoID)->fetchPairs('id');
        }

        $this->designTao->updateLinkedCommits($designID, $repoID, $revisions);

        $oldCommit = $this->dao->findByID($designID)->from(TABLE_DESIGN)->fetch('commit');
        $revisions = implode(',', $revisions);
        $commit    = $oldCommit ? $oldCommit . ',' . $revisions : $revisions;

        $design = new stdclass();
        $design->commit     = $commit;
        $design->commitedBy = $this->app->user->account;
        $this->dao->update(TABLE_DESIGN)->data($design)->autoCheck()->where('id')->eq($designID)->exec();

        return !dao::isError();
    }

    /**
     * 设计解除代码提交关联。
     * Design unlink a commit.
     *
     * @param  int    $designID
     * @param  int    $commitID
     * @access public
     * @return bool
     */
    public function unlinkCommit(int $designID = 0, int $commitID = 0): bool
    {
        /* Delete linked commit in the relation table. */
        $this->dao->delete()->from(TABLE_RELATION)
            ->where('AType')->eq('design')
            ->andwhere('AID')->eq($designID)
            ->andwhere('BType')->eq('commit')
            ->andwhere('relation')->eq('completedin')
            ->beginIF(!empty($commitID))->andWhere('BID')->eq($commitID)->fi()
            ->exec();

        $this->dao->delete()->from(TABLE_RELATION)
            ->where('AType')->eq('commit')
            ->andwhere('BID')->eq($designID)
            ->andwhere('BType')->eq('design')
            ->andwhere('relation')->eq('completedfrom')
            ->beginIF(!empty($commitID))->andWhere('AID')->eq($commitID)->fi()
            ->exec();

        /* Update linked commit in the design table. */
        $commit = $this->dao->select('BID')->from(TABLE_RELATION)->where('AType')->eq('design')->andWhere('AID')->eq($designID)->andWhere('BType')->eq('commit')->andwhere('relation')->eq('completedin')->fetchAll('BID');
        $commit = implode(",", array_keys($commit));

        $this->dao->update(TABLE_DESIGN)->set('commit')->eq($commit)->where('id')->eq($designID)->exec();

        return !dao::isError();
    }

    /**
     * 通过ID获取设计信息。
     * Get design information by ID.
     *
     * @param  int        $designID
     * @access public
     * @return object|bool
     */
    public function getByID(int $designID = 0): object|bool
    {
        $design = $this->dao->select('*')->from(TABLE_DESIGN)->where('id')->eq($designID)->fetch();
        if(!$design) return false;

        $this->app->loadLang('product');
        $design->files       = $this->loadModel('file')->getByObject('design', $designID);
        $design->productName = $design->product ? $this->dao->findByID($design->product)->from(TABLE_PRODUCT)->fetch('name') : $this->lang->product->all;
        $design->project     = (int)$design->project;
        $design->product     = (int)$design->product;


        $design->commit = '';
        $relations = $this->loadModel('common')->getRelations('design', $designID, 'commit');
        foreach($relations as $relation) $design->commit .= html::a(helper::createLink('design', 'revision', "revisionID={$relation->BID}&projectID={$design->project}"), "#{$relation->BID}");

        return $this->loadModel('file')->replaceImgURL($design, 'desc');
    }

    /**
     * 获取设计 id=>value 的键值对数组。
     * Get design id=>value pairs.
     *
     * @param  int    $productID
     * @param  string $type      all|HLDS|DDS|DBDS|ADS
     * @access public
     * @return object
     */
    public function getPairs(int $productID = 0, string $type = 'all'): array
    {
        $designs = $this->dao->select('id, name')->from(TABLE_DESIGN)
            ->where('product')->eq($productID)
            ->andWhere('deleted')->eq(0)
            ->beginIF($type != 'all')->andWhere('type')->eq($type)->fi()
            ->fetchPairs();

        foreach($designs as $id => $name) $designs[$id] = $id . ':' . $name;
        return $designs;
    }

    /**
     * 获取设计变更后受影响的任务。
     * Get affected tasks after design changed.
     *
     * @param  int    $design
     * @access public
     * @return object
     */
    public function getAffectedScope(object $design = null): object
    {
        if(!isset($design->id)) return $design;

        /* Get affected tasks. */
        $design->tasks = $this->dao->select('*')->from(TABLE_TASK)
            ->where('deleted')->eq(0)
            ->andWhere('status')->ne('closed')
            ->andWhere('design')->eq($design->id)
            ->orderBy('id desc')->fetchAll();

        return $design;
    }

    /**
     * 获取设计列表数据。
     * Get design list.
     *
     * @param  int      $productID
     * @param  int      $projectID
     * @param  string   $type      all|bySearch|HLDS|DDS|DBDS|ADS
     * @param  int      $param
     * @param  string   $orderBy
     * @param  int      $pager
     * @access public
     * @return object[]
     */
    public function getList(int $projectID = 0, int $productID = 0, string $type = 'all', int $param = 0, string $orderBy = 'id_desc', object $pager = null): array
    {
        if($type == 'bySearch')
        {
            $designs = $this->getBySearch($projectID, $productID, $param, $orderBy, $pager);
        }
        else
        {
            $designs = $this->dao->select('*')->from(TABLE_DESIGN)
                ->where('deleted')->eq(0)
                ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
                ->beginIF($type != 'all')->andWhere('type')->in($type)->fi()
                ->beginIF($productID)->andWhere('product')->eq($productID)->fi()
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
        }

        return $designs;
    }

    /**
     * 获取设计关联的代码提交。
     * Get commit.
     *
     * @param  int         $designID
     * @param  object      $pager
     * @access public
     * @return object|bool
     */
    public function getCommit(int $designID = 0, object $pager = null): object|bool
    {
        $design = $this->dao->select('*')->from(TABLE_DESIGN)->where('id')->eq($designID)->fetch();
        if(!$design) return false;

        $design->project = (int)$design->project;
        $design->product = (int)$design->product;
        $design->commit  = $this->dao->select('*')->from(TABLE_REPOHISTORY)->where('id')->in($design->commit)->page($pager)->fetchAll('id');

        $this->loadModel('repo');
        foreach($design->commit as $commit)
        {
            $commit->originalComment = $commit->comment;
            $commit->comment         = $this->repo->replaceCommentLink($commit->comment);
        }

        return $design;
    }

    /**
     * 获取搜索后的设计列表数据。
     * Get designs by search.
     *
     * @param  int      $projectID
     * @param  int      $productID
     * @param  int      $queryID
     * @param  string   $orderBy
     * @param  object   $pager
     * @access public
     * @return object[]
     */
    public function getBySearch(int $projectID = 0, int $productID = 0, int $queryID = 0, string $orderBy = 'id_desc', object $pager = null): array
    {
        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('designQuery', $query->sql);
                $this->session->set('designForm', $query->form);
            }
            else
            {
                $this->session->set('designQuery', ' 1 = 1');
            }
        }
        else
        {
            if($this->session->designQuery === false) $this->session->set('designQuery', ' 1 = 1');
        }

        return $this->dao->select('*')->from(TABLE_DESIGN)
            ->where($this->session->designQuery)
            ->andWhere('deleted')->eq('0')
            ->andWhere('project')->eq($projectID)
            ->beginIF($productID)->andWhere('product')->eq($productID)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 通过ID获取提交记录。
     * Get commit by ID.
     *
     * @param  int         $revisionID
     * @access public
     * @return object|bool
     */
    public function getCommitByID(int $revisionID = 0): object|bool
    {
        return $this->dao->select('*')->from(TABLE_REPOHISTORY)->where('id')->eq($revisionID)->fetch();
    }
}
