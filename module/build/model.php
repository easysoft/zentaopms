<?php
declare(strict_types=1);
/**
 * The model file of build module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     build
 * @version     $Id: model.php 4970 2013-07-02 05:58:11Z wyd621@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php
class buildModel extends model
{
    /**
     * 通过版本ID获取版本信息。
     * Get build info.
     *
     * @param  int         $buildID
     * @param  bool        $setImgSize
     * @access public
     * @return object|bool
     */
    public function getByID(int $buildID, bool $setImgSize = false): object|false
    {
        $build = $this->dao->select('t1.*, t2.name as executionName, t3.name as productName, t3.type as productType')
            ->from(TABLE_BUILD)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
            ->where('t1.id')->eq((int)$buildID)
            ->fetch();
        if(!$build) return false;

        $build = $this->joinChildBuilds($build);
        $build = $this->loadModel('file')->replaceImgURL($build, 'desc');
        $build->files = $this->file->getByObject('build', $buildID);
        if($setImgSize) $build->desc = $this->file->setImgSize($build->desc);
        return $build;
    }

    /**
     * 通过版本ID列表获取版本信息。
     * Get builds by id list.
     *
     * @param  array  $idList
     * @access public
     * @return array
     */
    public function getByList(array $idList): array
    {
        return $this->dao->select('*')->from(TABLE_BUILD)->where('id')->in($idList)->fetchAll('id');
    }

    /**
     * 通过项目ID获取版本信息。
     * Get builds of a project.
     *
     * @param  int    $projectID
     * @param  string $type
     * @param  string $param
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getProjectBuilds(int $projectID = 0, string $type = 'all', string $param = '', string $orderBy = 't1.date_desc,t1.id_desc', object $pager = null): array
    {
        $builds = $this->dao->select('t1.*, t2.name as productName')
            ->from(TABLE_BUILD)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.project')->ne(0)
            ->beginIF($projectID)->andWhere('t1.project')->eq((int)$projectID)->fi()
            ->beginIF($type == 'product' && $param)->andWhere('t1.product')->eq((int)$param)->fi()
            ->beginIF($type == 'bysearch')->andWhere($param)->fi()
            ->beginIF($type == 'review')->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.reviewers)")->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');

        $executionIdList = array();
        foreach($builds as $build)
        {
            $build->builds = $this->getByList(explode(',', $build->builds));
            if(!empty($build->builds))
            {
                foreach($build->builds as $child)
                {
                    if(!isset($executionIdList[$child->execution])) $executionIdList[$child->execution] = $child->execution;
                }
            }
            if(!isset($executionIdList[$build->execution])) $executionIdList[$build->execution] = $build->execution;
        }
        $executions = $this->loadModel('execution')->getByIdList($executionIdList, 'all');

        foreach($builds as $build)
        {
            $build->executionDeleted = $build->execution ? $executions[$build->execution]->deleted : 0;
            $build->executionName    = $build->execution ? $executions[$build->execution]->name : '';
            if(!empty($build->builds))
            {
                foreach($build->builds as $child) $child->executionName = $child->execution ? $executions[$child->execution]->name : '';
            }
        }
        return $builds;
    }

    /**
     * 根据搜索条件获取项目版本。
     * Get builds of a project by search.
     *
     * @param  int    $projectID
     * @param  int    $queryID
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getProjectBuildsBySearch(int $projectID, int $queryID, string $orderBy = 't1.date_desc,t1.id_desc', object $pager = null): array
    {
        /* If there are saved query conditions, reset the session. */
        if((int)$queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('projectBuildQuery', $query->sql);
                $this->session->set('projectBuildForm', $query->form);
            }
        }
        if($this->session->projectBuildQuery == false) $this->session->set('projectBuildQuery', ' 1 = 1');

        $buildQuery = $this->session->projectBuildQuery;

        /* Distinguish between repeated fields. */
        $fields = array('id' => '`id`', 'name' => '`name`', 'product' => '`product`', 'desc' => '`desc`', 'project' => '`project`');
        foreach($fields as $field)
        {
            if(strpos($buildQuery, $field) !== false)
            {
                $buildQuery = str_replace($field, "t1." . $field, $buildQuery);
            }
        }

        return $this->getProjectBuilds($projectID, 'bysearch', (string)$buildQuery, $orderBy, $pager);
    }

    /**
     * 根据执行ID获取版本信息。
     * Get builds of a execution.
     *
     * @param  int    $executionID
     * @param  string $type        all|product|bysearch
     * @param  string $param       productID|buildQuery
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getExecutionBuilds(int $executionID, string $type = '', string $param = '', string $orderBy = 't1.date_desc,t1.id_desc', object $pager = null): array
    {
        return $this->dao->select('t1.*, t2.name as executionName, t3.name as productName')
            ->from(TABLE_BUILD)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
            ->where('t1.deleted')->eq(0)
            ->beginIF($executionID)->andWhere('t1.execution')->eq((int)$executionID)->fi()
            ->beginIF($type == 'product' && $param)->andWhere('t1.product')->eq((int)$param)->fi()
            ->beginIF($type == 'bysearch')->andWhere($param)->fi()
            ->beginIF($type == 'review')->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.reviewers)")->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 通过条件获取执行下版本列表。
     * Get builds of a execution by search.
     *
     * @param  int      $executionID
     * @param  int      $queryID
     * @param  object   $pager
     * @access public
     * @return object[]
     */
    public function getExecutionBuildsBySearch(int $executionID, int $queryID, object $pager = null): array
    {
        /* If there are saved query conditions, reset the session. */
        if($queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('executionBuildQuery', $query->sql);
                $this->session->set('executionBuildForm', $query->form);
            }
        }

        if($this->session->executionBuildQuery === false) $this->session->set('executionBuildQuery', ' 1 = 1');
        $buildQuery = $this->session->executionBuildQuery;

        /* Distinguish between repeated fields. */
        $fields = array('id' => '`id`', 'name' => '`name`', 'product' => '`product`', 'desc' => '`desc`');
        foreach($fields as $field)
        {
            if(strpos($this->session->executionBuildQuery, $field) !== false)
            {
                $buildQuery = str_replace($field, "t1." . $field, $buildQuery);
            }
        }

        return $this->getExecutionBuilds($executionID, 'bysearch', (string)$buildQuery, 't1.date_desc,t1.id_desc', $pager);
    }

    /**
     * 根据需求ID获取版本列表。
     * Get builds by story ID.
     *
     * @param  int    $storyID
     * @access public
     * @return array
     */
    public function getStoryBuilds(int $storyID): array
    {
        if(empty($storyID)) return array();

        return $this->dao->select('*')->from(TABLE_BUILD)
            ->where('deleted')->eq(0)
            ->andWhere("CONCAT(',', stories, ',')")->like("%,$storyID,%")
            ->orderBy('id_desc')
            ->fetchAll('id');
    }

    /**
     * 通过条件获取版本id:name的键值对。
     * Get build pairs by condition.
     *
     * @param  array      $productIdList
     * @param  string|int $branch
     * @param  string     $params       noempty|notrunk|noterminate|withbranch|hasproject|noDeleted|singled|noreleased|releasedtag, can be a set of them
     * @param  int        $objectID
     * @param  string     $objectType
     * @param  string     $buildIdList
     * @param  bool       $replace
     * @access public
     * @return array
     */
    public function getBuildPairs(array|int $productIdList, string|int $branch = 'all', string $params = 'noterminate, nodone', int $objectID = 0, string $objectType = 'execution', string $buildIdList = '', bool $replace = true): array
    {
        $sysBuilds = array();
        if(strpos($params, 'notrunk') === false) $sysBuilds = array('trunk' => $this->lang->trunk);

        $shadows        = $this->dao->select('shadow')->from(TABLE_RELEASE)->where('product')->in($productIdList)->fetchPairs('shadow', 'shadow'); // Get the buildID under the shadow product.
        $selectedBuilds = $this->buildTao->selectedBuildPairs($buildIdList, $productIdList, $params, $objectID, $objectType);
        $allBuilds      = $this->buildTao->fetchBuilds($productIdList, $params, $objectID, $objectType, $shadows);

        /* Set builds and filter done executions and terminate releases. */
        list($builds, $excludedReleaseIdList) = $this->setBuildDateGroup($allBuilds, $branch, $params);
        if(empty($builds) && empty($shadows)) return $sysBuilds + $selectedBuilds;

        /* if the build has been released and replace is true, replace build name with release name. */
        if($replace)
        {
            $releases = $this->dao->select('t1.id,t1.shadow,t1.product,t1.branch,t1.build,t1.name,t1.date,t3.name as branchName,t4.type as productType')->from(TABLE_RELEASE)->alias('t1')
                ->leftJoin(TABLE_BUILD)->alias('t2')->on('FIND_IN_SET(t2.id, t1.build)')
                ->leftJoin(TABLE_BRANCH)->alias('t3')->on('FIND_IN_SET(t3.id, t1.branch)')
                ->leftJoin(TABLE_PRODUCT)->alias('t4')->on('t1.product=t4.id')
                ->where('t1.product')->in($productIdList)
                ->beginIF(!empty($buildIdList))->andWhere('t2.id')->in($buildIdList)->fi()
                ->andWhere('t1.deleted')->eq(0)
                ->andWhere('t1.shadow')->ne(0)
                ->fetchAll('id');

            /* Get the buildID under the shadow product. */
            $shadows = $this->dao->select('shadow')->from(TABLE_RELEASE)->where('product')->in($productIdList)->fetchPairs('shadow', 'shadow');
            if($shadows)
            {
                /* Append releases of only shadow and not link build. */
                $releases += $this->dao->select('t1.id,t1.shadow,t1.product,t1.branch,t1.build,t1.name,t1.date,t2.name as branchName,t3.type as productType')->from(TABLE_RELEASE)->alias('t1')
                    ->leftJoin(TABLE_BRANCH)->alias('t2')->on('FIND_IN_SET(t2.id, t1.branch)')
                    ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product=t3.id')
                    ->where('t1.shadow')->in($shadows)
                    ->andWhere('t1.build')->eq(0)
                    ->andWhere('t1.deleted')->eq(0)
                    ->fetchAll('id');
            }
            $builds = $this->replaceNameWithRelease($allBuilds, $builds, $releases, $branch, $params, $excludedReleaseIdList);
        }

        krsort($builds);
        $buildPairs = array();
        foreach($builds as $childBuilds) $buildPairs += $childBuilds;
        return $sysBuilds + $buildPairs + $selectedBuilds;
    }

    /**
     * 根据版本日期分组设置版本信息。
     * Set build date group.
     *
     * @param  array      $allBuilds
     * @param  string|int $branch
     * @param  string     $params    nodone|noterminate|withexecution|withbranch
     * @access public
     * @return array
     */
    public function setBuildDateGroup(array $allBuilds, string|int $branch, string $params): array
    {
        $this->app->loadLang('branch');

        $deletedExecutions     = $this->dao->select('id, deleted')->from(TABLE_EXECUTION)->where('type')->eq('sprint')->andWhere('deleted')->eq('1')->fetchPairs();
        $branchPairs           = $this->dao->select('id,name')->from(TABLE_BRANCH)->fetchPairs();
        $builds                = array();
        $excludedReleaseIdList = array();
        foreach($allBuilds as $id => $build)
        {
            if($build->branch === '') $build->branch = 0;
            $isDone        = empty($build->releaseID) && strpos($params, 'nodone') !== false && $build->objectStatus === 'done';
            $isTerminate   = strpos($params, 'noterminate') !== false && $build->releaseStatus === 'terminate';
            $isDeleted     = strpos($params, 'withexecution') !== false && $build->execution && isset($deletedExecutions[$build->execution]);
            $isNotInBranch = $branch !== 'all' && strpos(",{$build->branch},", ",{$branch},") === false;
            if(in_array(true, array($isDone, $isTerminate, $isDeleted, $isNotInBranch)))
            {
                $excludedReleaseIdList[] = $build->releaseID;
                continue;
            }

            if($build->deleted == 1) $build->name .= ' (' . $this->lang->build->deleted . ')';
            if(!empty($build->branch))
            {
                $branchName = '';
                foreach(explode(',', $build->branch) as $buildBranch)
                {
                    if(empty($buildBranch))
                    {
                        $branchName .= $this->lang->branch->main;
                    }
                    else
                    {
                        $branchName .= isset($branchPairs[$buildBranch]) ? $branchPairs[$buildBranch] : '';
                    }
                    $branchName .= ',';
                }

                $branchName = trim($branchName, ',');
            }
            else
            {
                $branchName = $this->lang->branch->main;
            }

            $buildName = $build->name;
            if(strpos($params, 'withbranch') !== false && $build->productType != 'normal') $buildName = $branchName . '/' . $buildName;
            $builds[$build->date][$id] = $buildName;
        }

        return array($builds, $excludedReleaseIdList);
    }

    /**
     * 将版本名称替换为发布名称。
     * Replace the build name with release name.
     *
     * @param  array      $allBuilds
     * @param  array      $builds
     * @param  array      $releases
     * @param  string|int $branch
     * @param  string     $params                separate|noterminate|withbranch|releasetag|noreleased
     * @param  array      $excludedReleaseIdList
     * @access public
     * @return array
     */
    public function replaceNameWithRelease(array $allBuilds, array $builds, array $releases, string|int $branch, string $params, array $excludedReleaseIdList): array
    {
        $this->app->loadLang('branch');

        $branches = strpos($params, 'separate') === false ? "0,$branch" : $branch;
        foreach($releases as $release)
        {
            if(strpos($params, 'noterminate') !== false && in_array($release->id, $excludedReleaseIdList)) continue;

            if($branch !== 'all')
            {
                $inBranch = false;
                foreach(explode(',', trim($release->branch, ',')) as $branchID)
                {
                    if($branchID === '') continue;
                    if(strpos(",{$branches},", ",{$branchID},") !== false) $inBranch = true;
                }
                if(!$inBranch) continue;
            }

            /* Set release name based on the condition. */
            $releaseName = $release->name;
            $branchName  = $release->branchName ? $release->branchName : $this->lang->branch->main;
            if($release->productType != 'normal') $releaseName = (strpos($params, 'withbranch') !== false ? $branchName . '/' : '') . $releaseName;
            if(strpos($params, 'releasetag') !== false) $releaseName = $releaseName . " [{$this->lang->build->released}]";
            $builds[$release->date][$release->shadow] = $releaseName;

            foreach(explode(',', trim($release->build, ',')) as $buildID)
            {
                if(!isset($allBuilds[$buildID])) continue;
                $build = $allBuilds[$buildID];
                if(strpos($params, 'noreleased') !== false) unset($builds[$build->date][$buildID]);
            }
        }

        return $builds;
    }

    /**
     * 获取最后一次创建的版本信息。
     * Get the last build.
     *
     * @param  int          $executionID
     * @param  int          $projectID
     * @access public
     * @return object|false
     */
    public function getLast(int $executionID = 0, int $projectID = 0): object|false
    {
        return $this->dao->select('id, name')->from(TABLE_BUILD)
            ->where('deleted')->eq(0)
            ->andWhere('execution')->eq($executionID)
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->orderBy('date DESC,id DESC')
            ->fetch();
    }

    /**
     * 创建一个版本。
     * Create a build.
     *
     * @param  object $build
     * @access public
     * @return int|false
     */
    public function create(object $build): int|false
    {
        /* Integrated version merging branch. */
        if($this->post->isIntegrated == 'yes')
        {
            $build->execution = 0;
            $branchPairs    = $this->dao->select('branch')->from(TABLE_BUILD)->where('id')->in($build->builds)->fetchPairs();
            $relationBranch = array();
            foreach($branchPairs as $branches)
            {
                foreach(explode(',', $branches) as $branch)
                {
                    if(!isset($relationBranch[$branch])) $relationBranch[$branch] = $branch;
                }
            }
            if($relationBranch) $build->branch = implode(',', $relationBranch);
        }
        else
        {
            $product = $this->loadModel('product')->getByID((int)$build->product);
            if(!empty($product) && $product->type != 'normal'&& $this->post->branch === false)
            {
                $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);
                dao::$errors['branch'] = sprintf($this->lang->error->notempty, $this->lang->product->branch);
            }
        }
        if(dao::isError()) return false;

        /* Process and insert build data. */
        $build = $this->loadModel('file')->processImgURL($build, $this->config->build->editor->create['id'], $this->post->uid);
        $this->dao->insert(TABLE_BUILD)->data($build)
            ->autoCheck()
            ->batchCheck($this->config->build->create->requiredFields, 'notempty')
            ->check('name', 'unique', "product = {$build->product} AND branch = '{$build->branch}' AND deleted = '0'")
            ->checkFlow()
            ->exec();
        if(dao::isError()) return false;

        /* Set file linkage and score info. */
        $buildID = $this->dao->lastInsertID();
        $this->file->updateObjectID($this->post->uid, $buildID, 'build');
        $this->file->saveUpload('build', $buildID);
        $this->loadModel('score')->create('build', 'create', $buildID);
        $this->loadModel('action')->create('build', $buildID, 'opened');
        return $buildID;
    }

    /**
     * 更新一个版本。
     * Update a build.
     *
     * @param  int         $buildID
     * @param  object      $build
     * @access public
     * @return array|false
     */
    public function update(int $buildID, object $build): array|false
    {
        $oldBuild = $this->fetchByID($buildID);
        $product  = $this->loadModel('product')->getByID((int) $build->product);
        $branch   = $this->post->branch === false || $product->type == 'normal' ? 0 : $oldBuild->branch;
        if(empty($oldBuild->execution)) $build = $this->processBuildForUpdate($build, $oldBuild);

        $product = $this->loadModel('product')->getByID($build->product);
        if(!empty($product) && $product->type != 'normal' && !isset($_POST['branch']) && isset($_POST['product']))
        {
            $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);
            dao::$errors['branch'] = sprintf($this->lang->error->notempty, $this->lang->product->branch);
        }
        if(dao::isError()) return false;

        $build = $this->loadModel('file')->processImgURL($build, $this->config->build->editor->edit['id'], $this->post->uid);
        $this->dao->update(TABLE_BUILD)->data($build)
            ->autoCheck()
            ->batchCheck($this->config->build->edit->requiredFields, 'notempty')
            ->where('id')->eq($buildID)
            ->check('name', 'unique', "id != $buildID AND product = {$build->product} AND branch = '{$build->branch}' AND deleted = '0'")
            ->checkFlow()
            ->exec();
        if(dao::isError()) return false;

        if(isset($build->branch) && $oldBuild->branch != $build->branch) $this->dao->update(TABLE_RELEASE)->set('branch')->eq($build->branch)->where('build')->eq($buildID)->exec();
        if(dao::isError()) return false;

        $this->file->updateObjectID($this->post->uid, $buildID, 'build');
        return common::createChanges($oldBuild, $build);
    }

    /**
     * 更新关联Bug的解决原因。
     * Update linked bug to resolved.
     *
     * @param  object $build
     * @param  array  $bugIdList
     * @param  array  $resolvedByList
     * @access public
     * @return bool
     */
    public function updateLinkedBug(object $build, array $bugIdList = array(), array $resolvedByList = array()): bool
    {
        $bugs = empty($bugIdList) ? array() : $this->dao->select('*')->from(TABLE_BUG)->where('id')->in($bugIdList)->fetchAll();
        if(!$bugs) return false;

        $this->loadModel('action');
        $now = helper::now();
        foreach($bugs as $bug)
        {
            if($bug->status == 'resolved' || $bug->status == 'closed') continue;

            if(helper::isZeroDate($bug->activatedDate)) unset($bug->activatedDate);
            if(helper::isZeroDate($bug->closedDate))    unset($bug->closedDate);

            $bug->resolvedBy     = zget($resolvedByList, $bug->id, '');
            $bug->resolvedDate   = $now;
            $bug->status         = 'resolved';
            $bug->confirmed      = 1;
            $bug->assignedDate   = $now;
            $bug->assignedTo     = $bug->openedBy;
            $bug->lastEditedBy   = $this->app->user->account;
            $bug->lastEditedDate = $now;
            $bug->resolution     = 'fixed';
            $bug->resolvedBuild  = $build->id;
            $bug->deadline       = !empty($bug->deadline) ? $bug->deadline : null;
            $this->dao->update(TABLE_BUG)->data($bug)->where('id')->eq($bug->id)->exec();
            $this->action->create('bug', $bug->id, 'Resolved', '', 'fixed', $bug->resolvedBy);
        }

        return !dao::isError();
    }

    /**
     * 版本关联需求。
     * Link stories to a build.
     *
     * @param  int    $buildID
     * @param  array  $storyIdList
     * @access public
     * @return void
     */
    public function linkStory(int $buildID, array $storyIdList): bool
    {
        if(empty($storyIdList)) return false;

        $build = $this->getByID($buildID);
        foreach($storyIdList as $i => $storyID)
        {
            if(strpos(",{$build->stories},", ",{$storyID},") !== false) unset($storyIdList[$i]);
        }

        $build->stories .= ',' . implode(',', $storyIdList);
        $this->dao->update(TABLE_BUILD)->set('stories')->eq($build->stories)->where('id')->eq((int)$buildID)->exec();

        $this->loadModel('action');
        foreach($storyIdList as $storyID) $this->action->create('story', $storyID, 'linked2build', '', $buildID);
        $this->action->create('build', $buildID, 'linkstory', '', implode(',', $storyIdList));
        return !dao::isError();
    }

    /**
     * 解除需求关联。
     * Unlink story.
     *
     * @param  int    $buildID
     * @param  int    $storyID
     * @access public
     * @return bool
     */
    public function unlinkStory(int $buildID, int $storyID): bool
    {
        $build = $this->getByID($buildID);
        $build->stories = trim(str_replace(",$storyID,", ',', ",$build->stories,"), ',');
        if($build->stories) $build->stories = ',' . $build->stories;

        $this->dao->update(TABLE_BUILD)->set('stories')->eq($build->stories)->where('id')->eq($buildID)->exec();
        $this->loadModel('action')->create('story', $storyID, 'unlinkedfrombuild', '', $buildID, '', false);
        return !dao::isError();
    }

    /**
     * 批量解除需求关联。
     * Batch unlink story.
     *
     * @param  int    $buildID
     * @param  array  $storyIDList
     * @access public
     * @return bool
     */
    public function batchUnlinkStory(int $buildID, array $storyIDList): bool
    {
        if(empty($storyIDList)) return true;

        $build = $this->getByID($buildID);
        $build->stories = ",$build->stories,";
        foreach($storyIDList as $storyID) $build->stories = str_replace(",$storyID,", ',', $build->stories);
        $build->stories = trim($build->stories, ',');
        $this->dao->update(TABLE_BUILD)->set('stories')->eq($build->stories)->where('id')->eq((int)$buildID)->exec();

        $this->loadModel('action');
        foreach($storyIDList as $storyID) $this->action->create('story', (int)$storyID, 'unlinkedfrombuild', '', $buildID);
        return !dao::isError();
    }

    /**
     * 版本关联Bug。
     * Link bugs.
     *
     * @param  int    $buildID
     * @param  array  $bugIdList
     * @param  array  $resolvedList
     * @access public
     * @return bool
     */
    public function linkBug(int $buildID, array $bugIdList, array $resolvedList = array()): bool
    {
        $build = $this->getByID($buildID);
        if(!$build) return false;

        foreach($bugIdList as $i => $bugID)
        {
            if(strpos(",{$build->bugs},", ",{$bugID},") !== false) unset($bugIdList[$i]);
        }

        $build->bugs .= ',' . implode(',', $bugIdList);
        $this->updateLinkedBug($build, $bugIdList, $resolvedList);
        $this->dao->update(TABLE_BUILD)->set('bugs')->eq($build->bugs)->where('id')->eq((int)$buildID)->exec();

        $this->loadModel('action');
        foreach($bugIdList as $bugID) $this->action->create('bug', (int)$bugID, 'linked2bug', '', $buildID);

        return !dao::isError();
    }

    /**
     * 解除Bug跟版本的关联关系。
     * Unlink bug.
     *
     * @param  int    $buildID
     * @param  int    $bugID
     * @access public
     * @return void
     */
    public function unlinkBug(int $buildID, int $bugID): void
    {
        $build = $this->getByID($buildID);
        if(!$build) return;

        $build->bugs = trim(str_replace(",$bugID,", ',', ",$build->bugs,"), ',');
        if($build->bugs) $build->bugs = ',' . $build->bugs;

        $this->dao->update(TABLE_BUILD)->set('bugs')->eq($build->bugs)->where('id')->eq($buildID)->exec();
        $this->loadModel('action')->create('bug', $bugID, 'unlinkedfrombuild', '', $buildID, '', false);
    }

    /**
     * 批量解除Bug跟版本的关联关系。
     * Batch unlink bug.
     *
     * @param  int    $buildID
     * @param  array  $bugIdList
     * @access public
     * @return bool
     */
    public function batchUnlinkBug(int $buildID, array $bugIdList): bool
    {
        if(empty($bugIdList)) return true;

        $build = $this->getByID($buildID);
        if(!$build) return false;

        $build->bugs = ",$build->bugs,";
        foreach($bugIdList as $bugID) $build->bugs = str_replace(",$bugID,", ',', $build->bugs);
        $build->bugs = trim($build->bugs, ',');

        $this->dao->update(TABLE_BUILD)->set('bugs')->eq($build->bugs)->where('id')->eq((int)$buildID)->exec();

        $this->loadModel('action');
        foreach($bugIdList as $unlinkBugID) $this->action->create('bug', $unlinkBugID, 'unlinkedfrombuild', '', $buildID);

        return !dao::isError();
    }

    /**
     * 更新子版本关联的Bug。
     * Bugs and stories associated with child builds.
     *
     * @param  object  $build
     * @access public
     * @return object
     */
    public function joinChildBuilds(object $build): object
    {
        $build->allBugs    = $build->bugs;
        $build->allStories = $build->stories;

        $childBuilds = $this->getByList(explode(',', $build->builds));
        foreach($childBuilds as $childBuild)
        {
            if($childBuild->bugs)    $build->allBugs    .= ",{$childBuild->bugs}";
            if($childBuild->stories) $build->allStories .= ",{$childBuild->stories}";
        }

        $build->allBugs    = explode(',', $build->allBugs);
        $build->allBugs    = implode(',', array_unique(array_filter($build->allBugs)));
        $build->allStories = explode(',', $build->allStories);
        $build->allStories = implode(',', array_unique(array_filter($build->allStories)));

        return $build;
    }

    /**
     * 检查按钮是否可用。
     * Adjust the action is clickable.
     *
     * @param  object $buikd
     * @param  string $action
     * @param  string $module
     * @access public
     * @return bool
     */
    public static function isClickable(object $build, string $action, string $module = 'bug'): bool
    {
        $action = strtolower($action);
        if($module == 'testtask' && $action == 'create') return !$build->executionDeleted;

        return true;
    }

    /**
     * 构造详情页面的操作菜单。
     * Build operate menu for detail page.
     *
     * @param  object $build
     * @access public
     * @return array
     */
    public function buildOperateMenu(object $build): array
    {
        $menu         = array();
        $params       = "buildID={$build->id}";
        $canBeChanged = common::canBeChanged('build', $build);
        if($build->deleted || !$canBeChanged) return $menu;

        $moduleName = $this->app->tab == 'project' ? 'projectbuild' : 'build';

        if(common::hasPriv($moduleName, 'edit', $build))
        {
            $menu[] = array(
                'text'  => $this->lang->build->edit,
                'icon'  => 'edit',
                'url'   => helper::createLink($moduleName, 'edit', $params),
                'class' => 'btn ghost'
            );
        }

        if(common::hasPriv($moduleName, 'delete', $build))
        {
            $menu[] = array(
                'text'  => $this->lang->build->delete,
                'icon'  => 'trash',
                'url'   => helper::createLink($moduleName, 'delete', $params),
                'class' => 'btn ghost ajax-submit',
                'data-confirm' => $this->lang->build->confirmDelete
            );
        }

        return $menu;
    }

    /**
     * 为区块获取版本数据。
     * Get build's data for block.
     *
     * @param  int    $projectID
     * @param  string $orderBy
     * @param  int    $limit
     * @access public
     * @return object[]
     */
    public function getBuildBlockData(int $projectID = 0, string $orderBy = 'id_desc', int $limit = 10): array
    {
        return $this->dao->select('*')->from(TABLE_BUILD)
            ->where('deleted')->eq('0')
            ->beginIF(!$this->app->user->admin)->andWhere('execution')->in($this->app->user->view->sprints)->fi()
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->orderBy($orderBy)
            ->limit($limit)
            ->fetchAll();
    }

    /**
     * 根据权限生成列表中操作列按钮。
     * Build table action menu for build browse page.
     *
     * @param  object $build
     * @param  int    $executionID
     * @param  string $from        execution|projectbuild
     * @access public
     * @return array
     */
    public function buildActionList(object $build, int $executionID = 0, string $from = 'execution'): array
    {
        $actions     = array();
        $executionID = $executionID ? $executionID : (int)$build->execution;
        $execution   = $this->loadModel('execution')->fetchByID($executionID);

        $module = $from == 'projectbuild' ? 'projectbuild' : 'build';
        $build->executionDeleted = $execution ? $execution->deleted : 0;

        if(common::hasPriv($module, 'linkstory') && common::canBeChanged('build', $build)) $actions[] = $from == 'projectbuild' ? 'linkProjectStory' : 'linkStory';

        if(common::hasPriv('testtask', 'create')) $actions[] = $execution && $execution->deleted === '1' ? '-createTest' : 'createTest';

        $isNotKanban   = $from == 'execution' && !empty($execution->type) && $execution->type != 'kanban';
        $isFromProject = $from == 'projectbuild' || empty($execution->type) || $execution->type == 'kanban';
        if($isNotKanban && common::hasPriv('execution', 'bug')) $actions[] = 'viewBug';
        if($isFromProject && common::hasPriv($module, 'view'))  $actions[] = $from == 'projectbuild' ? 'projectBugList' : 'bugList';

        if(common::hasPriv($module, 'edit'))   $actions[] = $module . 'Edit';
        if(common::hasPriv($module, 'delete')) $actions[] = 'delete';

        return $actions;
    }

    /**
     * 处理版本编辑前没有执行的情况。
     * Process build for update when the build has no execution.
     *
     * @param  object $build
     * @param  object $oldBuild
     * @access public
     * @return object
     */
    public function processBuildForUpdate(object $build, object $oldBuild): object
    {
        if(!empty($oldBuild->execution)) return $build;

        $buildBranch = array();
        foreach(explode(',', trim($build->branch, ',')) as $branchID) $buildBranch[$branchID] = $branchID;

        /* Get delete builds. */
        $deleteBuilds = array();
        $newBuilds    = isset($build->builds) ? explode(',', $build->builds) : array();
        foreach($newBuilds as $oldBuildID)
        {
            if(empty($oldBuildID)) continue;
            if(!in_array($oldBuildID, $newBuilds)) $deleteBuilds[$oldBuildID] = $oldBuildID;
        }

        /* Delete the branch when the branch of the deleted build has no linked stories. */
        $storyBranches = $this->dao->select('branch')->from(TABLE_STORY)->where('id')->in($oldBuild->stories)->fetchPairs('branch');
        $branches      = $this->dao->select('branch')->from(TABLE_BUILD)->where('id')->in($newBuilds + $deleteBuilds)->fetchPairs();
        foreach($branches as $branch)
        {
            foreach(explode(',', $branch) as $branchID)
            {
                if(empty($branchID)) continue;
                if(in_array($branchID, $deleteBuilds) && isset($storyBranches[$branchID])) continue;
                if(in_array($branchID, $newBuilds)    && isset($buildBranch[$branchID]))   continue;

                if(in_array($branchID, $deleteBuilds)) unset($buildBranch[$branchID]);
                if(in_array($branchID, $newBuilds))    $buildBranch[$branchID] = $branchID;
            }
        }

        $build->branch = implode(',', $buildBranch);
        return $build;
    }

    /**
     * 获取版本关联的bug列表。
     * Get bug list of build.
     *
     * @param  string $bugIdList
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getBugList(string $bugIdList, string $orderBy = '', object $pager = null): array
    {
        return $this->dao->select('*')->from(TABLE_BUG)
            ->where('id')->in($bugIdList)
            ->andWhere('deleted')->eq(0)
            ->beginIF($orderBy)->orderBy($orderBy)->fi()
            ->page($pager)
            ->fetchAll();
    }

    /**
     * 获取版本关联的story列表。
     * Get story list of build.
     *
     * @param  string $storyIdList
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getStoryList(string $storyIdList, int $branch = 0, string $orderBy = '', object $pager = null): array
    {
        $stories = $this->dao->select("*, IF(`pri` = 0, {$this->config->maxPriValue}, `pri`) as priOrder")->from(TABLE_STORY)
            ->where('id')->in($storyIdList)
            ->andWhere('deleted')->eq(0)
            ->beginIF($orderBy)->orderBy($orderBy)->fi()
            ->page($pager)
            ->fetchAll('id');

        $stages = $this->dao->select('*')->from(TABLE_STORYSTAGE)->where('story')->in(array_keys($stories))->andWhere('branch')->eq($branch)->fetchPairs('story', 'stage');
        foreach($stages as $storyID => $stage) $stories[$storyID]->stage = $stage;

        return $stories;
    }
}
