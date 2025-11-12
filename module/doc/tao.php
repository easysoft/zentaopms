<?php
declare(strict_types=1);
/**
 * The tao file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
class docTao extends docModel
{
    /**
     * 获取编辑过的文档ID列表。
     * Get the list of doc id list that have been edited.
     *
     * @access protected
     * @return array
     */
    protected function getEditedDocIdList(): array
    {
        return $this->dao->select('objectID')->from(TABLE_ACTION)
            ->where('objectType')->eq('doc')
            ->andWhere('action')->in('edited')
            ->andWhere('actor')->eq($this->app->user->account)
            ->andWhere('vision')->eq($this->config->vision)
            ->fetchPairs();
    }

    /**
     * 获取已排序的执行数据。
     * Get ordered executions.
     *
     * @param  int       $append
     * @access protected
     * @return array
     */
    protected function getOrderedExecutions(int $append = 0): array
    {
        $myObjects    = $normalObjects = $closedObjects = array();
        $projectPairs = $this->dao->select('id,name')->from(TABLE_PROJECT)->where('type')->eq('project')->fetchPairs('id');
        $executions   = $this->dao->select('*')->from(TABLE_EXECUTION)
            ->where('deleted')->eq(0)
            ->andWhere('type')->in('sprint,stage')
            ->andWhere('multiple')->eq('1')
            ->andWhere('vision')->eq($this->config->vision)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($this->app->user->view->sprints)->fi()
            ->beginIF($append)->orWhere('id')->eq($append)->fi()
            ->orderBy('order_asc')
            ->fetchAll('id');

        foreach($executions as $id => $execution)
        {
            if($execution->type == 'stage' && $execution->grade != 1)
            {
                $parentExecutions = $this->dao->select('id,name')->from(TABLE_EXECUTION)->where('id')->in(trim($execution->path, ','))->andWhere('type')->in('stage,sprint')->orderBy('grade')->fetchPairs();
                $execution->name  = implode('/', $parentExecutions);
            }
            $execution->name = zget($projectPairs, $execution->project) . ' / ' . $execution->name;

            if($execution->status != 'done' && $execution->status != 'closed' && $execution->PM == $this->app->user->account)
            {
                $myObjects[$id] = $execution->name;
            }
            elseif($execution->status != 'done' && $execution->status != 'closed' && !($execution->PM == $this->app->user->account))
            {
                $normalObjects[$id] = $execution->name;
            }
            elseif(in_array($execution->status, array('done', 'closed')))
            {
                $closedObjects[$id] = $execution->name;
            }
        }

        return array($myObjects, $normalObjects, $closedObjects);
    }

    /**
     * 获取关联产品的数据。
     * Get the data of the linked product.
     *
     * @param  int       $productID
     * @param  string    $userView
     * @access protected
     * @return array
     */
    protected function getLinkedProductData(int $productID, string $userView = ''): array
    {
        $storyIdList = $this->dao->select('id')->from(TABLE_STORY)
            ->where('product')->eq($productID)
            ->andWhere('deleted')->eq('0')
            ->beginIF(!$this->app->user->admin)->andWhere('product')->in($userView)->fi()
            ->get();

        $epicIdList = $this->dao->select('id')->from(TABLE_STORY)
            ->where('product')->eq($productID)
            ->andWhere('deleted')->eq('0')
            ->andWhere('type')->eq('epic')
            ->beginIF(!$this->app->user->admin)->andWhere('product')->in($userView)->fi()
            ->fetchPairs();

        $requirementIdList = $this->dao->select('id')->from(TABLE_STORY)
            ->where('product')->eq($productID)
            ->andWhere('deleted')->eq('0')
            ->andWhere('type')->eq('requirement')
            ->beginIF(!$this->app->user->admin)->andWhere('product')->in($userView)->fi()
            ->fetchPairs();

        $planIdList = $this->dao->select('id')->from(TABLE_PRODUCTPLAN)
            ->where('product')->eq($productID)
            ->andWhere('deleted')->eq('0')
            ->beginIF(!$this->app->user->admin)->andWhere('product')->in($userView)->fi()
            ->get();

        $releasePairs = $this->dao->select('id')->from(TABLE_RELEASE)
            ->where('product')->eq($productID)
            ->andWhere('deleted')->eq('0')
            ->beginIF(!$this->app->user->admin)->andWhere('product')->in($userView)->fi()
            ->fetchPairs('id');

        $casePairs = $this->dao->select('id')->from(TABLE_CASE)
            ->where('product')->eq($productID)
            ->andWhere('deleted')->eq('0')
            ->beginIF(!$this->app->user->admin)->andWhere('product')->in($userView)->fi()
            ->fetchPairs('id');

        $resultPairs = $this->dao->select('id')->from(TABLE_TESTRESULT)->where('case')->in($casePairs)->fetchPairs('id', 'id');

        return array($storyIdList, $epicIdList, $requirementIdList, $planIdList, $releasePairs, $casePairs, $resultPairs);
    }

    /**
     * 获取我创建的文档。
     * Get docs created by me.
     *
     * @param  array     $hasPrivDocIdList
     * @param  string    $sort
     * @param  object    $pager
     * @access protected
     * @return array
     */
    protected function getOpenedDocs(array $hasPrivDocIdList, string $sort, object $pager = null): array
    {
        return $this->dao->select('t1.*, t2.name as libName, t2.type as objectType')->from(TABLE_DOC)->alias('t1')
            ->leftJoin(TABLE_DOCLIB)->alias('t2')->on("t1.lib=t2.id")
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.lib')->ne(0)
            ->andWhere('t1.id')->in($hasPrivDocIdList)
            ->beginIF($this->config->doc->notArticleType)->andWhere('t1.type')->notIN($this->config->doc->notArticleType)->fi()
            ->andWhere('t1.addedBy')->eq($this->app->user->account)
            ->andWhere('t1.vision')->in($this->config->vision)
            ->andWhere('t1.templateType')->eq('')
            ->orderBy($sort)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 获取我编辑过的文档。
     * Get the docs that I have edited.
     *
     * @param  string    $sort
     * @param  object    $pager
     * @access protected
     * @return array
     */
    protected function getEditedDocs(string $sort, object $pager = null): array
    {
        $docIdList = $this->dao->select('objectID')->from(TABLE_ACTION)
            ->where('objectType')->eq('doc')
            ->andWhere('actor')->eq($this->app->user->account)
            ->andWhere('action')->eq('edited')
            ->andWhere('vision')->eq($this->config->vision)
            ->fetchAll('objectID');

        return $this->dao->select('t1.*, t2.name as libName, t2.type as objectType')->from(TABLE_DOC)->alias('t1')
            ->leftJoin(TABLE_DOCLIB)->alias('t2')->on("t1.lib=t2.id")
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.id')->in(array_keys($docIdList))
            ->andWhere('t1.lib')->ne(0)
            ->andWhere('t1.vision')->in($this->config->vision)
            ->beginIF($this->config->doc->notArticleType)->andWhere('t1.type')->notIN($this->config->doc->notArticleType)->fi()
            ->orderBy($sort)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 获取按照编辑时间倒序排序的文档。
     * Get the docs ordered by edited date.
     *
     * @param  array     $hasPrivDocIdList
     * @param  array     $allLibIDList
     * @param  object    $pager
     * @access protected
     * @return array
     */
    protected function getOrderedDocsByEditedDate(array $hasPrivDocIdList, array $allLibIDList, object $pager = null): array
    {
        return $this->dao->select('*')->from(TABLE_DOC)
            ->where('deleted')->eq(0)
            ->andWhere('id')->in($hasPrivDocIdList)
            ->andWhere('templateType')->eq('')
            ->beginIF($this->config->doc->notArticleType)->andWhere('type')->notIN($this->config->doc->notArticleType)->fi()
            ->andWhere('lib')->in($allLibIDList)
            ->andWhere('vision')->in($this->config->vision)
            ->orderBy('editedDate_desc')
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 获取我收藏的文档。
     * Get the docs that I have collected.
     *
     * @param  array     $hasPrivDocIdList
     * @param  string    $sort
     * @param  object    $pager
     * @access protected
     * @return array
     */
    protected function getCollectedDocs(array $hasPrivDocIdList, string $sort, object $pager = null): array
    {
        return $this->dao->select('t1.*')->from(TABLE_DOC)->alias('t1')
            ->leftJoin(TABLE_DOCACTION)->alias('t2')->on("t1.id=t2.doc AND t2.action='collect'")
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.lib')->ne(0)
            ->andWhere('t1.templateType')->eq('')
            ->andWhere('t1.id')->in($hasPrivDocIdList)
            ->beginIF($this->config->doc->notArticleType)->andWhere('t1.type')->notIN($this->config->doc->notArticleType)->fi()
            ->andWhere('t2.actor')->eq($this->app->user->account)
            ->andWhere('t1.vision')->in($this->config->vision)
            ->orderBy($sort)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 获取空间。
     * Get space pairs.
     *
     * @param  string     $type
     * @access protected
     * @return array
     */
    protected function getSpacePairs(string $type): array
    {
        $objectLibs = $this->dao->select('*')->from(TABLE_DOCLIB)
            ->where('type')->eq($type)
            ->andWhere('deleted')->eq(0)
            ->andWhere('parent')->eq(0)
            ->andWhere('vision')->eq($this->config->vision)
            ->fetchAll();

        $pairs = array();
        foreach($objectLibs as $lib)
        {
            if($this->checkPrivLib($lib)) $pairs[$lib->id] = $lib->name;
        }

        return $pairs;
    }

    /**
     * 更新文档。
     * Update document.
     *
     * @param  int       $docID
     * @param  object    $doc
     * @param  bool      $basicInfoChanged
     * @access protected
     * @return void
     */
    public function doUpdateDoc(int $docID, object $doc, bool $basicInfoChanged = false)
    {
        $this->dao->update(TABLE_DOC)->data($doc)->autoCheck()->where('id')->eq($docID)->exec();

        if(empty($doc->parent) && $basicInfoChanged)
        {
            $childData = new stdclass();
            $childData->module = $doc->module;
            $childData->lib    = $doc->lib;
            $childData->acl    = $doc->acl;
            if(isset($doc->groups)) $childData->groups = $doc->groups;
            if(isset($doc->users))  $childData->users  = $doc->users;

            $this->dao->update(TABLE_DOC)->data($childData)->where("FIND_IN_SET('{$docID}', `path`)")->exec();
        }
    }

    /**
     * 插入文档库。
     * Insert doclib.
     *
     * @param  object    $lib
     * @param  string    $requiredFields
     * @access protected
     * @return int|false
     */
    protected function doInsertLib(object $lib, string $requiredFields = ''): int|false
    {
        $this->dao->insert(TABLE_DOCLIB)->data($lib, 'spaceName')->autoCheck()
            ->batchCheck($requiredFields, 'notempty')
            ->exec();

        if(dao::isError()) return false;
        return $this->dao->lastInsertID();
    }

    /**
     * Filter deleted documents.
     *
     * @param  array     $docs
     * @access protected
     * @return array
     */
    protected function filterDeletedDocs(array $docs): array
    {
        $deletedDocs = array();
        foreach($docs as $docID => $doc)
        {
            if($doc->deleted == '0') continue;

            unset($docs[$docID]);
            $deletedDocs[$doc->id] = $doc->path;
        }

        foreach($deletedDocs as $deletedPath)
        {
            foreach($docs as $docID => $doc)
            {
                if($deletedPath && strpos($doc->path, $deletedPath) !== false) unset($docs[$docID]);
            }
        }

        return $docs;
    }
}
