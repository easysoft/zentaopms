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
        $this->dao->insert(TABLE_DESIGN)->data($design, 'docVersions')
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

        $stories = is_array($_POST['story']) ? $this->loadModel('story')->getByList($this->post->story) : array();

        foreach($designs as $rowID => $design)
        {
            $design->product   = $productID;
            $design->project   = $projectID;
            $design->createdBy = $this->app->user->account;
            if(!empty($stories[$design->story])) $design->storyVersion = $stories[$design->story]->version;
            $this->dao->insert(TABLE_DESIGN)->data($design)->autoCheck()->batchCheck($this->config->design->batchcreate->requiredFields, 'notempty')->exec();

            if(dao::isError())
            {
                foreach(dao::getError() as $field => $error) dao::$errors["{$field}[{$rowID}]"] = $error;
                return false;
            }

            $designID = $this->dao->lastInsertID();
            if($this->config->edition != 'open' && !empty($design->story))
            {
                if(!isset($stories[$design->story])) continue;

                $relation = new stdClass();
                $relation->AID      = $design->story;
                $relation->AType    = $stories[$design->story]->type;
                $relation->relation = 'generated';
                $relation->BID      = $designID;
                $relation->BType    = 'design';
                $relation->product  = 0;
                $this->dao->replace(TABLE_RELATION)->data($relation)->exec();
            }

            $this->action->create('design', $designID, 'created');
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
    public function update(int $designID = 0, ?object $design = null): bool|array
    {
        $oldDesign = $this->getByID($designID);
        if(!$oldDesign) return false;

        $design = $this->loadModel('file')->processImgURL($design, 'desc', (string)$this->post->uid);
        $this->dao->update(TABLE_DESIGN)->data($design, 'deleteFiles,renameFiles,files,docs,oldDocs,docVersions')->autoCheck()->batchCheck($this->config->design->edit->requiredFields, 'notempty')->where('id')->eq($designID)->exec();

        if(dao::isError()) return false;

        $this->file->processFileDiffsForObject('design', $oldDesign, $design);
        $addedFiles = empty($design->addedFiles) ? '' : implode(',', array_keys($design->addedFiles)) . ',';
        $designFiles = $oldDesign->files = implode(',', array_keys($oldDesign->files));
        foreach($design->deleteFiles as $fileID) $designFiles = str_replace(",$fileID,", ',', ",$designFiles,");
        $files = $addedFiles . trim($designFiles, ',');

        $designChanged = ($oldDesign->name != $design->name || $oldDesign->desc != $design->desc || !empty($files));
        if($designChanged)
        {
            $version = $oldDesign->version + 1;

            $spec = new stdclass();
            $spec->design  = $designID;
            $spec->version = $version;
            $spec->name    = $design->name;
            $spec->desc    = $design->desc;
            $spec->files   = empty($files) ? '' : $files;
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
    public function assign(int $designID = 0, ?object $design = null): array|bool
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
        if(in_array($repo->SCM, $this->config->repo->notSyncSCM))
        {
            $logs = array();
            foreach($this->session->designRevisions as $commit)
            {
                if(in_array($commit->revision, $revisions))
                {
                    $log = new stdclass();
                    $log->committer = $commit->committer;
                    $log->revision  = $commit->revision;
                    $log->comment   = isset($commit->comment) ? $commit->comment : '';
                    $log->time      = date('Y-m-d H:i:s', strtotime($commit->time));
                    $logs[] = $log;
                }
            }
            $this->repo->saveCommit($repoID, array('commits' => $logs), 0);
        }
        $revisions = $this->dao->select('id')->from(TABLE_REPOHISTORY)->where('revision')->in($revisions)->andWhere('repo')->eq($repoID)->fetchPairs('id');

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
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getDesign();

        $design = $this->dao->select('*')->from(TABLE_DESIGN)->where('id')->eq($designID)->fetch();
        if(!$design) return false;

        $this->app->loadLang('product');
        $design->files       = $this->loadModel('file')->getByObject('design', $designID);
        $design->productName = $design->product ? $this->dao->findByID($design->product)->from(TABLE_PRODUCT)->fetch('name') : $this->lang->product->all;
        $design->project     = (int)$design->project;
        $design->product     = (int)$design->product;

        $revisions = $this->dao->select('id,revision')->from(TABLE_REPOHISTORY)->where('id')->in($design->commit)->fetchPairs('id', 'revision');

        $design->commit = '';
        $relations = $this->loadModel('common')->getRelations('design', $designID, 'commit');
        foreach($relations as $relation)
        {
            $revision = zget($revisions, $relation->BID, '');
            $design->commit .= html::a(helper::createLink('design', 'revision', "revisionID={$relation->BID}&projectID={$design->project}"), "# {$revision}", '', "title='{$revision}' class='flex clip'");
        }

        if($design->story > 0)
        {
            $storyInfo = $this->loadModel('story')->fetchByID((int)$design->story);
            $design->storyInfo   = $storyInfo;
            $design->needConfirm = $storyInfo->version != $design->storyVersion;
        }

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
    public function getAffectedScope(?object $design = null): object
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
     * @param  int|array $productID
     * @param  int|array $projectID
     * @param  string    $type      all|bySearch|HLDS|DDS|DBDS|ADS
     * @param  int       $param
     * @param  string    $orderBy
     * @param  int       $pager
     * @access public
     * @return object[]
     */
    public function getList(int|array $projectID = 0, int|array $productID = 0, string $type = 'all', int $param = 0, string $orderBy = 'id_desc', ?object $pager = null): array
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getDesigns();

        if($type == 'bySearch')
        {
            $designs = $this->getBySearch($projectID, $productID, $param, $orderBy, $pager);
        }
        else
        {
            $designs = $this->dao->select('*')->from(TABLE_DESIGN)
                ->where('deleted')->eq(0)
                ->beginIF($projectID)->andWhere('project')->in($projectID)->fi()
                ->beginIF($type != 'all')->andWhere('type')->in($type)->fi()
                ->beginIF($productID)->andWhere('product')->in(is_numeric($productID) ? "0,$productID" : array_merge($productID, array(0)))->fi()
                ->orderBy($orderBy)
                ->page($pager)
                ->fetchAll('id');
            $stories = $this->loadModel('story')->getByList(array_column($designs, 'story'));
            foreach($designs as $designID => $design)
            {
                if(isset($stories[$design->story]))
                {
                    $storyInfo = $stories[$design->story];
                    $designs[$designID]->needConfirm = $storyInfo->version != $design->storyVersion;
                }
            }
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
    public function getCommit(int $designID = 0, ?object $pager = null): object|bool
    {
        $design = $this->dao->select('*')->from(TABLE_DESIGN)->where('id')->eq($designID)->fetch();
        if(!$design) return false;

        $design->project = (int)$design->project;
        $design->product = (int)$design->product;
        $design->commit  = $this->dao->select('*')->from(TABLE_REPOHISTORY)->where('id')->in($design->commit)->page($pager)->fetchAll('id', false);

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
    public function getBySearch(int $projectID = 0, int $productID = 0, int $queryID = 0, string $orderBy = 'id_desc', ?object $pager = null): array
    {
        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('designQuery', $query->sql);
                $this->session->set('designForm', $query->form);
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
            ->beginIF($productID)->andWhere('product')->in(is_numeric($productID) ? "0,$productID" : array_merge($productID, array(0)))->fi()
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

    /**
     * 获取设计关联的提交数据。
     * Get the commit data for the associated designs
     * @param  int       $repoID
     * @param  array     $revisions
     * @access public
     * @return bool
     */
    public function getLinkedCommits(int $repoID, array $revisions): array
    {
        return $this->dao->select('t1.revision,t3.id AS id,t3.name AS title')
            ->from(TABLE_REPOHISTORY)->alias('t1')
            ->leftJoin(TABLE_RELATION)->alias('t2')->on("t2.relation='completedin' AND t2.BType='commit' AND t2.BID=t1.id")
            ->leftJoin(TABLE_DESIGN)->alias('t3')->on("t2.AType='design' AND t2.AID=t3.id")
            ->where('t1.revision')->in($revisions)
            ->andWhere('t1.repo')->eq($repoID)
            ->andWhere('t3.id')->notNULL()
            ->orderBy('id')
            ->fetchGroup('revision', 'id');
    }

    /**
     * 确认设计的需求变更。
     * Confirm story change of design.
     *
     * @param  int    $designID
     * @access public
     * @return bool
     */
    public function confirmStoryChange(int $designID)
    {
        $design = $this->fetchByID($designID);
        if($design)
        {
            $story  = $this->loadModel('story')->fetchByID((int)$design->story);
            if($story) $this->dao->update(TABLE_DESIGN)->set('storyVersion')->eq($story->version)->where('id')->eq($designID)->exec();
        }
        return dao::isError();
    }

    /**
     * 判断当前动作是否可以点击。
     * Adjust the action is clickable.
     *
     * @param  object    $object
     * @param  string    $action
     * @access public
     * @return bool
     */
    public static function isClickable(object $object, string $action): bool
    {
        $action = strtolower($action);
        if($action == 'confirmstorychange') return !empty($object->needConfirm);
        return true;
    }
}
