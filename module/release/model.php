<?php
declare(strict_types=1);
/**
 * The model file of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: model.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.net
 */
?>
<?php
class releaseModel extends model
{
    /**
     * 通过ID获取发布信息。
     * Get release information by ID.
     *
     * @param  int          $releaseID
     * @param  bool         $setImgSize
     * @access public
     * @return object|false
     */
    public function getByID(int $releaseID, bool $setImgSize = false): object|false
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getRelease();

        $release = $this->dao->select('t1.*, t2.name as productName, t2.type as productType')
            ->from(TABLE_RELEASE)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->where('t1.id')->eq((int)$releaseID)
            ->orderBy('t1.id DESC')
            ->fetch();
        if(!$release) return false;

        $release->builds  = $this->dao->select('id, branch, filePath, scmPath, name, execution, project')->from(TABLE_BUILD)->where('id')->in($release->build)->fetchAll();
        $release->project = trim($release->project, ',');
        $release->branch  = trim($release->branch, ',');
        $release->build   = trim($release->build, ',');

        $release->branches = array();
        $branchIdList = explode(',', trim($release->branch, ','));
        foreach($branchIdList as $branchID) $release->branches[$branchID] = $branchID;

        $this->loadModel('file');
        $release = $this->file->replaceImgURL($release, 'desc');
        $release->files = $this->file->getByObject('release', $releaseID);
        if(!empty($release->builds) && is_array($release->builds))
        {
            foreach($release->builds as $build) $release->files = array_merge($release->files, $this->file->getByObject('build', (int)$build->id));
        }
        if($setImgSize) $release->desc = $this->file->setImgSize($release->desc);

        $release->isInclude = false;
        if($this->app->rawMethod == 'edit')
        {
            if($this->dao->select('id')->from(TABLE_RELEASE)->where("FIND_IN_SET($releaseID, `releases`)")->fetch()) $release->isInclude = true;
            if(!$release->isInclude)
            {
                $deployID = $this->dao->select('t1.deploy')->from(TABLE_DEPLOYPRODUCT)->alias('t1')
                    ->leftJoin(TABLE_DEPLOY)->alias('t2')->on('t1.deploy = t2.id')
                    ->where('t1.`release`')->eq($releaseID)
                    ->andWhere('t2.deleted')->eq(0)
                    ->fetch();
                if($deployID) $release->isInclude = true;
            }
        }

        return $release;
    }

    /**
     * 获取发布列表。
     * Get release list.
     *
     * @param  array  $idList
     * @access public
     * @return array
     */
    public function getPairs(array $idList = array()): array
    {
        return $this->dao->select('id, name')->from(TABLE_RELEASE)
            ->where('deleted')->eq(0)
            ->beginIF($idList)->andWhere('id')->in($idList)->fi()
            ->fetchPairs();
    }

    /**
     * 通过条件获取发布列表信息。
     * Get release list information by condition.
     *
     * @param  array  $idList
     * @param  int    $includeRelease
     * @param  bool   $showRelated
     * @access public
     * @return array
     */
    public function getListByCondition(array $idList = array(), int $includeRelease = 0, bool $showRelated = false): array
    {
        $releases = $this->dao->select('*')->from(TABLE_RELEASE)
            ->where('deleted')->eq(0)
            ->beginIF($idList)->andWhere('id')->in($idList)->fi()
            ->beginIF($includeRelease)->andWhere("FIND_IN_SET($includeRelease, `releases`)")->fi()
            ->fetchAll('id', false);
        if(!$showRelated) return $releases;

        $projectIdList = '';
        $productIdList = [];
        foreach($releases as $release)
        {
            $projectIdList .= trim($release->project, ',') . ',';
            $productIdList[$release->product] = $release->product;
        }
        $projectPairs = $this->dao->select('id,name')->from(TABLE_PROJECT)->where('id')->in($projectIdList)->fetchPairs();

        $builds = $this->dao->select("t1.id, t1.name, t1.branch, t1.project, t1.execution, IF(t2.name IS NOT NULL, t2.name, '') AS projectName")
            ->from(TABLE_BUILD)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where("product")->in($productIdList)
            ->fetchAll('id');
        if(!empty($builds))
        {
            $branches = [];
            foreach($builds as $build)
            {
                foreach(explode(',', $build->branch) as $branchID) $branches[(int)$branchID] = (int)$branchID;
            }
            $branches = $this->dao->select('id,name')->from(TABLE_BRANCH)->where('id')->in($branches)->fetchPairs('id');
            foreach($builds as $build)
            {
                $branchNames = [];
                foreach(explode(',', $build->branch) as $branchID)
                {
                    $branchNames[] = isset($branches[$branchID]) ? $branches[$branchID] : $this->lang->trunk;
                }
                $build->branchName = implode(',', $branchNames);
            }
        }

        $this->loadModel('branch');
        foreach($releases as $release)
        {
            $releaseBuilds = array();
            foreach(explode(',', $release->build) as $buildID)
            {
                if(!$buildID || !isset($builds[$buildID])) continue;
                $releaseBuilds[$buildID] = $builds[$buildID];
            }
            $release->builds = $releaseBuilds;

            $branchName = array();
            foreach(explode(',', trim($release->branch, ',')) as $releaseBranch) $branchName[] = $releaseBranch === '0' ? $this->lang->branch->main : $this->branch->getByID($releaseBranch);
            $branchName = implode(',', $branchName);

            $release->branchName = empty($branchName) ? $this->lang->branch->main : $branchName;

            $release->projectName = array();
            foreach(explode(',', trim($release->project, ',')) as $projectID) $release->projectName[$projectID] = zget($projectPairs, $projectID, '');
            $release->projectName = implode(' ', $release->projectName);
        }
        return $releases;
    }

    /**
     * 获取发布列表信息。
     * Get release list information.
     *
     * @param  int      $productID
     * @param  string   $branch
     * @param  string   $type         all|review|bySearch|normal|terminate
     * @param  string   $orderBy
     * @param  string   $releaseQuery
     * @param  object   $pager
     * @access public
     * @return object[]
     */
    public function getList(int $productID, string|int $branch = 'all', string $type = 'all', string $orderBy = 't1.date_desc', string $releaseQuery = '', ?object $pager = null): array
    {
        if(common::isTutorialMode()) return $this->loadModel('tutorial')->getReleases();

        $releases = $this->dao->select('t1.*, t2.name as productName, t2.type as productType')->from(TABLE_RELEASE)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->where('t1.deleted')->eq(0)
            ->beginIF($productID)->andWhere('t1.product')->eq((int)$productID)->fi()
            ->beginIF($branch !== 'all')->andWhere("FIND_IN_SET($branch, t1.branch)")->fi()
            ->beginIF(!in_array($type, array('all', 'review', 'bySearch')))->andWhere('t1.status')->eq($type)->fi()
            ->beginIF($type == 'review')->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.reviewers)")->fi()
            ->beginIF($type == 'bySearch')->andWhere($releaseQuery)->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id', false);

        $projectIdList = '';
        foreach($releases as $release) $projectIdList .= trim($release->project, ',') . ',';
        $projectPairs = $this->dao->select('id,name')->from(TABLE_PROJECT)->where('id')->in($projectIdList)->fetchPairs();

        $builds = $this->dao->select("t1.id, t1.name, t1.branch, t1.project, t1.execution, IF(t2.name IS NOT NULL, t2.name, '') AS projectName")
            ->from(TABLE_BUILD)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where("product")->eq($productID)
            ->fetchAll('id');
        if(!empty($builds))
        {
            $branches = [];
            foreach($builds as $build)
            {
                foreach(explode(',', $build->branch) as $branchID) $branches[(int)$branchID] = (int)$branchID;
            }
            $branches = $this->dao->select('id,name')->from(TABLE_BRANCH)->where('id')->in($branches)->fetchPairs('id');
            foreach($builds as $build)
            {
                $branchNames = [];
                foreach(explode(',', $build->branch) as $branchID)
                {
                    $branchNames[] = isset($branches[$branchID]) ? $branches[$branchID] : $this->lang->trunk;
                }
                $build->branchName = implode(',', $branchNames);
            }
        }

        $this->loadModel('branch');
        foreach($releases as $release)
        {
            $releaseBuilds = array();
            foreach(explode(',', $release->build) as $buildID)
            {
                if(!$buildID || !isset($builds[$buildID])) continue;
                $releaseBuilds[] = $builds[$buildID];
            }
            $release->builds = $releaseBuilds;

            $branchName = array();
            if($release->productType != 'normal')
            {
                foreach(explode(',', trim($release->branch, ',')) as $releaseBranch) $branchName[] = $releaseBranch === '0' ? $this->lang->branch->main : $this->branch->getByID($releaseBranch);
                $branchName = implode(',', $branchName);
            }
            $release->branchName = empty($branchName) ? $this->lang->branch->main : $branchName;

            $release->projectName = array();
            foreach(explode(',', trim($release->project, ',')) as $projectID) $release->projectName[$projectID] = zget($projectPairs, $projectID, '');
            $release->projectName = implode(' ', $release->projectName);
        }
        return $releases;
    }

    /**
     * 获取产品下的最新创建的发布。
     * Get last release.
     *
     * @param  int          $productID
     * @param  int          $branch
     * @access public
     * @return object|false
     */
    public function getLast(int $productID, int $branch = 0): object|false
    {
        return $this->dao->select('id, name')->from(TABLE_RELEASE)
            ->where('product')->eq($productID)
            ->beginIF($branch)->andWhere('branch')->eq($branch)->fi()
            ->orderBy('id DESC')
            ->fetch();
    }

    /**
     * 获取指定应用下的所有发布。
     * Get releases by system.
     *
     * @param  array $systemList
     * @param  int   $filterRelease
     * @access public
     * @return array
     */
    public function getListBySystem(array $systemList, int $filterRelease = 0): array
    {
        return $this->dao->select('*')->from(TABLE_RELEASE)
            ->where('deleted')->eq(0)
            ->andWhere('system')->in($systemList)
            ->beginIF($filterRelease)->andWhere('id')->ne($filterRelease)->fi()
            ->orderBy('id DESC')
            ->fetchAll('id');
    }

    /**
     * 获取产品下发布的版本ID列表。
     * Get released builds from product.
     *
     * @param  int    $productID
     * @param  string $branch
     * @access public
     * @return array
     */
    public function getReleasedBuilds(int $productID, string $branch = 'all'): array
    {
        $releases = $this->dao->select('branch,shadow,build')->from(TABLE_RELEASE)
            ->where('deleted')->eq(0)
            ->andWhere('product')->eq($productID)
            ->fetchAll();

        $buildIdList = array();
        foreach($releases as $release)
        {
            if($branch != 'all' && $branch !== '')
            {
                $inBranch = false;
                foreach(explode(',', trim($release->branch, ',')) as $branchID)
                {
                    if($branchID === '') continue;

                    if(strpos(",{$branch},", ",{$branchID},") !== false) $inBranch = true;
                }
                if(!$inBranch) continue;
            }

            $builds        = explode(',', $release->build);
            $buildIdList   = array_merge($buildIdList, $builds);
            $buildIdList[] = $release->shadow;
        }
        return $buildIdList;
    }

    /**
     * 获取关联给定需求的发布。
     * Get releases by story id.
     *
     * @param  int    $storyID
     * @access public
     * @return array
     */
    public function getStoryReleases(int $storyID): array
    {
        if(empty($storyID)) return array();
        return $this->dao->select('*')->from(TABLE_RELEASE)
            ->where('deleted')->eq(0)
            ->andWhere("CONCAT(',', stories, ',')")->like("%,$storyID,%")
            ->orderBy('id_desc')
            ->fetchAll('id');
    }

    /**
     * 获取发布列表统计信息。
     * Get summary info of release browse page.
     *
     * @param  array  $releases
     * @param  int    $type
     * @access public
     * @return string
     */
    public function getPageSummary(array $releases, string $type): string
    {
        if($type != 'all') return sprintf($this->lang->release->pageSummary, count($releases));

        $totalNormal    = 0;
        $totalTerminate = 0;
        foreach($releases as $release)
        {
            if($release->status == 'normal') $totalNormal ++;
            if($release->status == 'terminate') $totalTerminate ++;
        }
        return sprintf($this->lang->release->pageAllSummary, count($releases), $totalNormal, $totalTerminate);
    }

    /**
     * 创建一个发布。
     * Create a release.
     *
     * @param  object    $release
     * @param  bool      $isSync
     * @access public
     * @return int|false
     */
    public function create(object $release, bool $isSync): int|false
    {
        /* Auto create shadow build. */
        if($release->name)
        {
            $shadowBuild = new stdclass();
            $shadowBuild->product      = $release->product;
            $shadowBuild->branch       = $release->branch;
            $shadowBuild->project      = isset($release->project) ? (int)$release->project : 0;
            $shadowBuild->builds       = $release->build;
            $shadowBuild->name         = $release->name;
            $shadowBuild->date         = $release->date;
            $shadowBuild->createdBy    = $this->app->user->account;
            $shadowBuild->createdDate  = helper::now();
        }

        if($release->build) $release = $this->processReleaseForCreate($release, $isSync);
        $release = $this->loadModel('file')->processImgURL($release, $this->config->release->editor->create['id'], (string)$this->post->uid);

        if($release->status == 'wait')   $this->config->release->create->requiredFields = str_replace(',releasedDate', '', $this->config->release->create->requiredFields);
        if($release->status == 'normal') $this->config->release->create->requiredFields = str_replace(',date', '', $this->config->release->create->requiredFields);

        $this->dao->insert(TABLE_RELEASE)->data($release)
            ->autoCheck()
            ->batchCheck($this->config->release->create->requiredFields, 'notempty')
            ->check('name', 'unique', "`system` = '{$release->system}' AND `deleted` = '0'")
            ->checkFlow();

        if(dao::isError()) return false;

        $this->dao->exec();
        $releaseID = $this->dao->lastInsertID();

        if(isset($shadowBuild))
        {
            $this->dao->insert(TABLE_BUILD)->data($shadowBuild)->exec();
            if(dao::isError()) return false;

            $release->shadow = $this->dao->lastInsertID();
            $this->dao->update(TABLE_RELEASE)->data(array('shadow' => $release->shadow))->where('id')->eq($releaseID)->exec();
        }
        $this->file->updateObjectID($this->post->uid, $releaseID, 'release');
        $this->file->saveUpload('release', $releaseID);
        $this->loadModel('score')->create('release', 'create', $releaseID);

        /* Set stage to released. */
        if($release->stories)
        {
            $this->loadModel('story');
            $this->loadModel('action');

            $storyIdList = array_unique(array_filter(explode(',', $release->stories)));
            foreach($storyIdList as $storyID)
            {
                $storyID = (int)$storyID;
                $this->story->setStage($storyID);
                $this->action->create('story', $storyID, 'linked2release', '', $releaseID);
            }
        }

        if($release->system) $this->loadModel('system')->setSystemRelease($release->system, $releaseID, $release->createdDate);

        $this->processRelated($releaseID, $release);

        return $releaseID;
    }

    /**
     * 处理待创建的发布字段。
     * Process release fields for create.
     *
     * @param  object $release
     * @param  bool   $isSync
     * @access public
     * @return object
     */
    public function processReleaseForCreate(object $release, bool $isSync): object
    {
        $this->loadModel('story');
        $this->loadModel('bug');

        $builds       = $this->dao->select('id,project,branch,builds,stories,bugs')->from(TABLE_BUILD)->where('id')->in($release->build)->fetchAll('id');
        $linkedBuilds = array();
        foreach($builds as $build)
        {
            $build->builds = trim($build->builds, ',');
            if(empty($build->builds)) continue;

            $linkedBuilds = array_merge($linkedBuilds, explode(',', $build->builds));
        }
        if($linkedBuilds) $builds += $this->dao->select('id,project,branch,builds,stories,bugs')->from(TABLE_BUILD)->where('id')->in($linkedBuilds)->fetchAll('id');

        $branches = array();
        $projects = array();
        foreach($builds as $build)
        {
            foreach(explode(',', $build->branch) as $buildBranch)
            {
                if(!isset($branches[$buildBranch])) $branches[$buildBranch] = $buildBranch;
            }

            $projects[$build->project] = $build->project;

            if($isSync)
            {
                $build->stories = trim($build->stories, ',');
                $build->bugs    = trim($build->bugs, ',');
                if($build->stories)
                {
                    $release->stories .= ',' . $build->stories;
                    $this->story->updateStoryReleasedDate($build->stories, $release->date);
                }

                if($build->bugs) $release->bugs .= ',' . $build->bugs;
            }
        }

        $release->build   = ',' . trim($release->build, ',') . ',';
        $release->branch  = ',' . trim(implode(',', $branches), ',') . ',';
        $release->project = ',' . trim(implode(',', $projects), ',') . ',';

        return $release;
    }

    /**
     * 更新一个发布。
     * Update a release.
     *
     * @param  object      $release
     * @param  object      $oldRelease
     * @access public
     * @return array|false
     */
    public function update($release, $oldRelease): array|false
    {
        $release = $this->loadModel('file')->processImgURL($release, $this->config->release->editor->edit['id'], (string)$this->post->uid);

        /* update release project and branch */
        if($release->build)
        {
            $builds   = $this->dao->select('project, branch')->from(TABLE_BUILD)->where('id')->in($release->build)->fetchAll();
            $branches = array();
            $projects = array();
            foreach($builds as $build)
            {
                foreach(explode(',', $build->branch) as $buildBranch)
                {
                    if(!isset($branches[$buildBranch])) $branches[$buildBranch] = $buildBranch;
                }
                $projects[$build->project] = $build->project;
            }
            $release->build   = ',' . trim($release->build, ',') . ',';
            $release->branch  = ',' . trim(implode(',', $branches), ',') . ',';
            $release->project = ',' . trim(implode(',', $projects), ',') . ',';
        }

        if($release->status == 'wait') $release->releasedDate = null;

        $this->dao->update(TABLE_RELEASE)->data($release, 'deleteFiles,renameFiles,files')
            ->autoCheck()
            ->batchCheck($this->config->release->edit->requiredFields, 'notempty')
            ->check('name', 'unique', "`id` != '{$oldRelease->id}' AND `system` = '{$release->system}' AND `deleted` = '0'")
            ->checkFlow()
            ->where('id')->eq($oldRelease->id)
            ->exec();

        if(dao::isError()) return false;

        if($oldRelease->status != $release->status && $release->status == 'normal') $this->setStoriesStage($oldRelease->id);

        $shadowBuild = array();
        if($release->name != $oldRelease->name)   $shadowBuild['name']   = $release->name;
        if($release->build != $oldRelease->build) $shadowBuild['builds'] = $release->build;
        if($release->date != $oldRelease->date)   $shadowBuild['date']   = $release->date;
        if($shadowBuild) $this->dao->update(TABLE_BUILD)->data($shadowBuild)->where('id')->eq($oldRelease->shadow)->exec();

        $this->file->processFileDiffsForObject('release', $oldRelease, $release);

        $this->processRelated($oldRelease->id, $release);

        $release = $this->file->replaceImgURL($release, 'desc');
        return common::createChanges($oldRelease, $release);
    }

    /**
     * 获取通知的人员。
     * Get notify persons.
     *
     * @param  object $release
     * @access public
     * @return array
     */
    public function getNotifyPersons(object $release): array
    {
        if(empty($release->notify)) return array();

        /* Get notify users. */
        $notifyPersons = array();
        $managerFields = '';
        $notifyList    = explode(',', $release->notify);
        foreach($notifyList as $notify)
        {
            if($notify == 'PO' || $notify == 'QD' || $notify == 'feedback')
            {
                $managerFields .= $notify . ',';
            }
            elseif($notify == 'SC' && !empty($release->build))
            {
                $stories  = implode(',', $this->dao->select('id,stories')->from(TABLE_BUILD)->where('id')->in($release->build)->fetchPairs('id', 'stories'));
                $stories .= ',' . $this->dao->select('stories')->from(TABLE_RELEASE)->where('id')->eq($release->id)->fetch('stories');
                $stories  = trim(str_replace(',,', ',', $stories), ',');
                if(empty($stories)) continue;

                $openedByList   = $this->dao->select('openedBy')->from(TABLE_STORY)->where('id')->in($stories)->fetchPairs();
                $notifyPersons += $openedByList;
            }
            elseif($notify == 'ET' && !empty($release->build))
            {
                $releaseBuilds = $this->dao->select('id, builds')->from(TABLE_BUILD)->where('id')->in($release->build)->fetchAll('id');

                $allBuilds = array_keys($releaseBuilds);
                foreach($releaseBuilds as $releaseBuild)
                {
                    if(empty($releaseBuild->builds)) continue;

                    $allBuilds = array_merge($allBuilds, explode(',', $releaseBuild->builds));
                }

                $members = $this->dao->select('t2.account')->from(TABLE_BUILD)->alias('t1')
                    ->leftJoin(TABLE_TEAM)->alias('t2')->on('t2.root = t1.execution')
                    ->where('t1.id')->in(array_filter(array_unique($allBuilds)))
                    ->andWhere('t2.type')->eq('execution')
                    ->fetchPairs();
                if(empty($members)) continue;

                $notifyPersons += $members;
            }
            elseif($notify == 'PT' && !empty($release->build))
            {
                $members = $this->dao->select('t2.account')->from(TABLE_RELEASE)->alias('t1')
                    ->leftJoin(TABLE_TEAM)->alias('t2')->on("FIND_IN_SET(t2.root, t1.project)")
                    ->where('t1.id')->eq($release->id)
                    ->andWhere('t2.type')->eq('project')
                    ->fetchPairs();
                if(empty($members)) continue;

                $notifyPersons += $members;
            }
            elseif($notify == 'CT' && !empty($release->mailto))
            {
                $notifyPersons += explode(',', trim($release->mailto, ','));
            }
        }

        if(empty($managerFields)) return $notifyPersons;

        $managerFields = trim($managerFields, ',');
        $managerUsers  = $this->dao->select($managerFields)->from(TABLE_PRODUCT)->where('id')->eq($release->product)->fetch();
        foreach($managerUsers as $account)
        {
            if(!isset($notifyPersons[$account])) $notifyPersons[$account] = $account;
        }
        return $notifyPersons;
    }

    /**
     * 发布批量关联需求。
     * Link stories to a release.
     *
     * @param  int    $releaseID
     * @param  array  $stories
     * @access public
     * @return bool
     */
    public function linkStory(int $releaseID, array $stories): bool
    {
        $release = $this->getByID($releaseID);
        if(!$release) return false;

        foreach($stories as $i => $storyID)
        {
            if(strpos(",{$release->stories},", ",{$storyID},") !== false) unset($stories[$i]);
        }

        $this->loadModel('story')->updateStoryReleasedDate($release->stories, $release->date);
        $release->stories .= ',' . implode(',', $stories);
        $this->dao->update(TABLE_RELEASE)->set('stories')->eq($release->stories)->where('id')->eq($releaseID)->exec();

        if($release->stories)
        {
            $this->loadModel('action');

            $product = $this->loadModel('product')->getByID($release->product);
            foreach($stories as $storyID)
            {
                /* Reset story stagedBy field for auto compute stage. */
                $storyID = (int)$storyID;
                $this->dao->update(TABLE_STORY)->set('stagedBy')->eq('')->where('id')->eq($storyID)->exec();
                if($product->type != 'normal') $this->dao->update(TABLE_STORYSTAGE)->set('stagedBy')->eq('')->where('story')->eq($storyID)->andWhere('branch')->eq($release->branch)->exec();

                if($release->status == 'normal') $this->story->setStage($storyID);

                $this->action->create('story', $storyID, 'linked2release', '', $releaseID);
            }

            $this->updateRelated($releaseID, 'story', $release->stories);
        }

        return !dao::isError();
    }

    /**
     * 移除关联的需求。
     * Unlink a story.
     *
     * @param  int    $releaseID
     * @param  int    $storyID
     * @access public
     * @return bool
     */
    public function unlinkStory(int $releaseID, int $storyID): bool
    {
        $release = $this->getByID($releaseID);
        if(!$release) return false;

        $release->stories = trim(str_replace(",$storyID,", ',', ",$release->stories,"), ',');
        $this->dao->update(TABLE_RELEASE)->set('stories')->eq($release->stories)->where('id')->eq((int)$releaseID)->exec();

        $this->loadModel('action')->create('story', $storyID, 'unlinkedfromrelease', '', $releaseID);
        $this->loadModel('story')->setStage($storyID);

        $this->deleteRelated($releaseID, 'story', $storyID);

        return !dao::isError();
    }

    /**
     * 批量解除发布跟需求的关联。
     * Batch unlink story.
     *
     * @param  int    $releaseID
     * @param  array  $storyIdList
     * @access public
     * @return bool
     */
    public function batchUnlinkStory(int $releaseID, array $storyIdList): bool
    {
        if(empty($storyIdList)) return true;

        $release = $this->getByID($releaseID);
        if(!$release) return false;

        $release->stories = ",$release->stories,";
        foreach($storyIdList as $storyID) $release->stories = str_replace(",$storyID,", ',', $release->stories);
        $release->stories = trim($release->stories, ',');
        $this->dao->update(TABLE_RELEASE)->set('stories')->eq($release->stories)->where('id')->eq((int)$releaseID)->exec();

        $this->loadModel('action');
        foreach($storyIdList as $unlinkStoryID)
        {
            $unlinkStoryID = (int)$unlinkStoryID;
            $this->action->create('story', $unlinkStoryID, 'unlinkedfromrelease', '', $releaseID);
            $this->loadModel('story')->setStage($unlinkStoryID);
        }

        $this->deleteRelated($releaseID, 'story', $storyIdList);

        return !dao::isError();
    }

    /**
     *
     * 发布批量关联Bug。
     * Link bugs.
     *
     * @param  int    $releaseID
     * @param  string $type      bug|leftBug
     * @param  array  $bugs
     * @access public
     * @return bool
     */
    public function linkBug(int $releaseID, string $type = 'bug', array $bugs = array()): bool
    {
        $release = $this->getByID($releaseID);
        if(!$release) return false;

        $field = $type == 'bug' ? 'bugs' : 'leftBugs';
        foreach($bugs as $i => $bugID)
        {
            if(strpos(",{$release->$field},", ",{$bugID},") !== false) unset($bugs[$i]);
        }

        $release->$field .= ',' . implode(',', $bugs);
        $this->dao->update(TABLE_RELEASE)->set($field)->eq($release->$field)->where('id')->eq($releaseID)->exec();

        $this->loadModel('action');
        foreach($bugs as $bugID) $this->action->create('bug', (int)$bugID, 'linked2release', '', $releaseID);

        $this->updateRelated($releaseID, $type, $release->$field);

        return !dao::isError();
    }

    /**
     * 移除关联的Bug。
     * Unlink bug.
     *
     * @param  int    $releaseID
     * @param  int    $bugID
     * @param  string $type      bug|leftBug
     * @access public
     * @return bool
     */
    public function unlinkBug(int $releaseID, int $bugID, string $type = 'bug'): bool
    {
        $release = $this->getByID($releaseID);
        if(!$release) return false;

        $field = $type == 'bug' ? 'bugs' : 'leftBugs';
        $release->{$field} = trim(str_replace(",$bugID,", ',', ",{$release->$field},"), ',');
        $this->dao->update(TABLE_RELEASE)->set($field)->eq($release->$field)->where('id')->eq($releaseID)->exec();
        $this->loadModel('action')->create('bug', $bugID, 'unlinkedfromrelease', '', $releaseID);

        $this->deleteRelated($releaseID, $type, $bugID);

        return !dao::isError();
    }

    /**
     * 批量解除发布跟Bug的关联。
     * Batch unlink bug.
     *
     * @param  int    $releaseID
     * @param  string $type      bug|leftBug
     * @param  array  $bugIdList
     * @access public
     * @return bool
     */
    public function batchUnlinkBug(int $releaseID, string $type = 'bug', array $bugIdList = array()): bool
    {
        if(empty($bugIdList)) return true;

        $release = $this->getByID($releaseID);
        if(!$release) return false;

        $field = $type == 'bug' ? 'bugs' : 'leftBugs';
        $release->$field = ",{$release->$field},";
        foreach($bugIdList as $bugID) $release->$field = str_replace(",$bugID,", ',', $release->$field);
        $release->$field = trim($release->$field, ',');
        $this->dao->update(TABLE_RELEASE)->set($field)->eq($release->$field)->where('id')->eq($releaseID)->exec();

        $this->loadModel('action');
        foreach($bugIdList as $unlinkBugID) $this->action->create('bug', (int)$unlinkBugID, 'unlinkedfromrelease', '', $releaseID);

        $this->deleteRelated($releaseID, $type, $bugIdList);

        return !dao::isError();
    }

    /**
     * 激活/停止维护发布。
     * Change status.
     *
     * @param  int    $releaseID
     * @param  string $status       normal|fail|terminate
     * @param  string $releasedDate
     * @access public
     * @return bool
     */
    public function changeStatus(int $releaseID, string $status, string $releasedDate = ''): bool
    {
        $this->dao->update(TABLE_RELEASE)
             ->set('status')->eq($status)
             ->beginIF($releasedDate)->set('releasedDate')->eq($releasedDate)->fi()
             ->where('id')->eq($releaseID)
             ->exec();

        if($status == 'normal') $this->setStoriesStage($releaseID);
        return !dao::isError();
    }

    /**
     * 判断按钮是否可点击。
     * Judge btn is clickable or not.
     *
     * @param  object $release
     * @param  string $action
     * @access public
     * @return bool
     */
    public static function isClickable(object $release, string $action): bool
    {
        if(!empty($release->deleted)) return false;

        global $app;
        if($app->rawMethod == 'browse' && !empty($release->releases) && $action == 'view') return false;

        $action = strtolower($action);

        if($action == 'notify')  return ($release->bugs || $release->stories) && $release->status == 'normal';
        if($action == 'play')    return $release->status == 'terminate';
        if($action == 'pause')   return $release->status == 'normal';
        if($action == 'publish') return $release->status == 'wait' || $release->status == 'fail';

        if(!empty($release->releases) && ($action == 'linkstory' || $action == 'linkbug')) return false;
        return true;
    }

    /**
     * 发送邮件给相关用户。
     * Send mail to release related users.
     *
     * @param  int    $releaseID
     * @access public
     * @return void
     */
    public function sendmail(int $releaseID): void
    {
        if(empty($releaseID)) return;
        $this->app->loadConfig('mail');

        /* Load module and get vars. */
        $release = $this->getByID($releaseID);
        $suffix  = empty($release->product) ? '' : ' - ' . $this->loadModel('product')->getByID($release->product)->name;
        $subject = 'Release #' . $release->id . ' ' . $release->name . $suffix;

        $stories  = $this->dao->select('*')->from(TABLE_STORY)->where('id')->in($release->stories)->andWhere('deleted')->eq(0)->fetchAll('id');
        $bugs     = $this->dao->select('*')->from(TABLE_BUG)->where('id')->in($release->bugs)->andWhere('deleted')->eq(0)->fetchAll();
        $leftBugs = $this->dao->select('*')->from(TABLE_BUG)->where('id')->in($release->leftBugs)->andWhere('deleted')->eq(0)->fetchAll();

        /* Get mail content. */
        $modulePath = $this->app->getModulePath('', 'release');
        $oldcwd     = getcwd();
        $viewFile   = $modulePath . 'ui/mail.html.php';
        chdir($modulePath . 'view');
        if(file_exists($modulePath . 'ext/ui/mail.html.php'))
        {
            $viewFile = $modulePath . 'ext/ui/mail.html.php';
            chdir($modulePath . 'ext/view');
        }
        ob_start();
        include $viewFile;
        foreach(glob($modulePath . 'ext/ui/mail.*.html.hook.php') as $hookFile) include $hookFile;
        $mailContent = ob_get_contents();
        ob_end_clean();
        chdir($oldcwd);

        if(strpos(",{$release->notify},", ',FB,') !== false) $this->sendMail2Feedback($release, $subject);

        /* Get the sender. */
        $sendUsers = $this->getNotifyList($release);
        if(!$sendUsers) return;

        list($toList, $ccList) = $sendUsers;

        /* Send it. */
        $this->loadModel('mail')->send($toList, $subject, $mailContent, $ccList);
    }

    /**
     * 获取发送邮件的人员。
     * Get notify list.
     *
     * @param  object      $release
     * @access public
     * @return false|array
     */
    public function getNotifyList(object $release): false|array
    {
        /* Set toList and ccList. */
        $toList = $this->app->user->account;
        $ccList = $release->mailto . ',';

        /* Get notifiy persons. */
        $notifyPersons = array();
        if(!empty($release->notify)) $notifyPersons = $this->getNotifyPersons($release);

        foreach($notifyPersons as $account)
        {
            if(strpos($ccList, ",{$account},") === false) $ccList .= ",$account,";
        }

        $ccList = trim($ccList, ',');
        if(empty($toList))
        {
            if(empty($ccList)) return false;
            if(strpos($ccList, ',') === false)
            {
                $toList = $ccList;
                $ccList = '';
            }
            else
            {
                $commaPos = strpos($ccList, ',');
                $toList   = substr($ccList, 0, $commaPos);
                $ccList   = substr($ccList, $commaPos + 1);
            }
        }

        return array($toList, $ccList);
    }

    /**
     * 获取通过动作触发的邮件通知人员。
     * Get toList and ccList.
     *
     * @param  object    $release
     * @param  string    $actionType
     * @access public
     * @return bool|array
     */
    public function getToAndCcList(object $release, string $actionType = ''): bool|array
    {
        /* Set toList and ccList. */
        $toList = $release->createdBy;
        $ccList = isset($release->mailto) ? str_replace(' ', '', trim($release->mailto, ',')) : '';

        $product = $this->loadModel('product')->fetchByID($release->product);
        if($product) $ccList .= ',' . $product->PO . ',' . $product->RD;

        if(empty($toList))
        {
            if(empty($ccList)) return false;
            if(strpos($ccList, ',') === false)
            {
                $toList = $ccList;
                $ccList = '';
            }
            else
            {
                $commaPos = strpos($ccList, ',');
                $toList   = substr($ccList, 0, $commaPos);
                $ccList   = substr($ccList, $commaPos + 1);
            }
        }

        return array($toList, $ccList);
    }

    /**
     * 发送邮件给反馈用户。
     * Send mail to feedback user.
     *
     * @param  object $release
     * @param  string $subject
     * @access public
     * @return void
     */
    public function sendMail2Feedback(object $release, string $subject): void
    {
        if(!$release->stories && !$release->bugs) return;

        $stories = explode(',', trim($release->stories, ','));
        $bugs    = explode(',', trim($release->bugs, ','));

        $storyNotifyList = $this->dao->select('id,title,notifyEmail')->from(TABLE_STORY)
            ->where('id')->in($stories)
            ->andWhere('notifyEmail')->ne('')
            ->fetchGroup('notifyEmail', 'id');

        $bugNotifyList = $this->dao->select('id,title,notifyEmail')->from(TABLE_BUG)
            ->where('id')->in($bugs)
            ->andWhere('notifyEmail')->ne('')
            ->fetchGroup('notifyEmail', 'id');

        /* Get notify email and object name. */
        $toList     = array();
        $emails     = array();
        $storyNames = array();
        $bugNames   = array();
        foreach($storyNotifyList as $notifyEmail => $storyList)
        {
            $email = new stdClass();
            $email->account  = $notifyEmail;
            $email->email    = $notifyEmail;
            $email->realname = '';

            $emails[$notifyEmail] = $email;
            $toList[$notifyEmail] = $notifyEmail;

            foreach($storyList as $story) $storyNames[] = $story->title;
        }
        foreach($bugNotifyList as $notifyEmail => $bugList)
        {
            $email = new stdClass();
            $email->account  = $notifyEmail;
            $email->email    = $notifyEmail;
            $email->realname = '';

            $emails[$notifyEmail] = $email;
            $toList[$notifyEmail] = $notifyEmail;

            foreach($bugList as $bug) $bugNames[] = $bug->title;
        }

        if(empty($toList)) return;

        $storyNames  = implode(',', $storyNames);
        $bugNames    = implode(',', $bugNames);
        $mailContent = sprintf($this->lang->release->mailContent, $release->name);
        if($storyNames) $mailContent .= sprintf($this->lang->release->storyList, $storyNames);
        if($bugNames)   $mailContent .= sprintf($this->lang->release->bugList,   $bugNames);
        $this->loadModel('mail')->send(implode(',', $toList), $subject, $mailContent, '', false, $emails);
    }

    /**
     * 构造发布详情页面的操作按钮。
     * Build release view action menu.
     *
     * @param  object $release
     * @access public
     * @return array
     */
    public function buildOperateViewMenu(object $release): array
    {
        $canBeChanged = common::canBeChanged('release', $release);
        if($release->deleted || !$canBeChanged || isInModal()) return array();

        $menu   = array();
        $params = "releaseID={$release->id}";

        if(common::hasPriv('release', 'changeStatus', $release))
        {
            $changedStatus = $release->status == 'normal' ? 'terminate' : 'normal';

            $menu[] = array(
                'text'         => $this->lang->release->changeStatusList[$changedStatus],
                'icon'         => $release->status == 'normal' ? 'pause' : 'play',
                'url'          => helper::createLink($this->app->rawModule, 'changeStatus', "{$params}&status={$changedStatus}"),
                'class'        => 'btn ghost ajax-submit',
                'data-confirm' => $release->status == 'normal' ? $this->lang->release->confirmTerminate : $this->lang->release->confirmActivate
            );
        }

        if(common::hasPriv('release', 'edit'))
        {
            $menu[] = array(
                'text'  => $this->lang->edit,
                'icon'  => 'edit',
                'url'   => helper::createLink($this->app->rawModule, 'edit', $params),
                'class' => 'btn ghost'
            );
        }

        if(common::hasPriv('release', 'delete'))
        {
            $menu[] = array(
                'text'         => $this->lang->delete,
                'icon'         => 'trash',
                'url'          => helper::createLink($this->app->rawModule, 'delete', $params),
                'class'        => 'btn ghost ajax-submit',
                'data-confirm' => $this->lang->release->confirmDelete
            );
        }

        return $menu;
    }

    /**
     * 获取未删除的发布数量。
     * Get count of the releases.
     *
     * @param  string $type all|milestone
     * @access public
     * @return int
     */
    public function getReleaseCount(string $type = 'all'): int
    {
        return $this->dao->select('COUNT(t1.id) as releaseCount')->from(TABLE_RELEASE)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.shadow')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('t1.product')->in($this->app->user->view->products)->fi()
            ->beginIF($type == 'milestone')->andWhere('t1.marker')->eq(1)->fi()
            ->fetch('releaseCount');
    }

    /**
     * 获取发布列表区块的数据。
     * Get the data for the release list block.
     *
     * @param  int      $projectID
     * @param  string   $orderBy
     * @param  int      $limit
     * @access public
     * @return object[]
     */
    public function getReleasesBlockData(int $projectID = 0, $orderBy = 'id_desc', int $limit = 0): array
    {
        return $this->dao->select('*')->from(TABLE_RELEASE)
            ->where('deleted')->eq(0)
            ->andWhere('shadow')->eq(0)
            ->beginIF($projectID)->andWhere('project')->eq($projectID)->fi()
            ->beginIF(!$this->app->user->admin)->andWhere('product')->in($this->app->user->view->products)->fi()
            ->orderBy($orderBy)
            ->limit($limit)
            ->fetchAll();
    }

    /**
     * 获取产品下的发布列表信息。
     * Get the release list information under the product.
     *
     * @param  array  $productIdList
     * @access public
     * @return array
     */
    public function getGroupByProduct(array $productIdList = array()): array
    {
        return $this->dao->select('*')->from(TABLE_RELEASE)
            ->where('deleted')->eq(0)
            ->andWhere('status')->eq('normal')
            ->beginIF(!empty($productIdList))->andWhere('product')->in($productIdList)->fi()
            ->fetchGroup('product');
    }

    /**
     * 通过产品ID列表获取产品下近期的发布列表。
     * statisticRecentReleases
     *
     * @param  array  $productIdList
     * @param  string $date
     * @param  string $orderBy
     * @access public
     * @return object[]
     */
    public function statisticRecentReleases(array $productIdList, $date = '', $orderBy = 'date_asc'): array
    {
        return $this->dao->select('*')->from(TABLE_RELEASE)
            ->where('deleted')->eq('0')
            ->andWhere('product')->in($productIdList)
            ->beginIF($date)->andWhere('date')->lt($date)->fi()
            ->orderBy($orderBy)
            ->fetchAll('product');
    }

    /**
     * 根据发布状态和权限生成列表中操作列按钮。
     * Build table action menu for release browse page.
     *
     * @param  object $release
     * @access public
     * @return array
     */
    public function buildActionList(object $release): array
    {
        $actions      = array();
        $canBeChanged = common::canBeChanged('release', $release);
        if(!$canBeChanged) return $actions;

        if(common::hasPriv('release', 'linkStory'))    $actions[] = 'linkStory';
        if(common::hasPriv('release', 'linkBug'))      $actions[] = 'linkBug';
        if(common::hasPriv('release', 'changeStatus')) $actions[] = $release->status == 'normal' ? 'pause' : 'play';
        if(common::hasPriv('release', 'edit'))         $actions[] = 'edit';
        if(common::hasPriv('release', 'notify'))       $actions[] = 'notify';
        if(common::hasPriv('release', 'delete'))       $actions[] = 'delete';

        return $actions;
    }

    /**
     * 获取发布关联的需求列表。
     * Get the story list linked with the release.
     *
     * @param  string $storyIdList
     * @param  string $branch
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getStoryList(string $storyIdList, string|int $branch, string $orderBy = '', ?object $pager = null): array
    {
        $stories = $this->dao->select("t1.*,t2.id as buildID, t2.name as buildName, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder")->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_BUILD)->alias('t2')->on("FIND_IN_SET(t1.id, t2.stories)")
            ->where('t1.id')->in($storyIdList)
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF($orderBy)->orderBy($orderBy)->fi()
            ->fetchAll('id');

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'story', false);

        if(!empty($pager))
        {
            $pager->recTotal = count($stories);
            $stories = array_chunk($stories, $pager->recPerPage);
            $stories = empty($stories) ? $stories : $stories[$pager->pageID - 1];
        }

        $stages = $this->dao->select('*')->from(TABLE_STORYSTAGE)->where('story')->in($storyIdList)->andWhere('branch')->in($branch)->fetchPairs('story', 'stage');
        foreach($stories as $index => $story)
        {
            if(isset($stages[$story->id])) $stories[$index]->stage = $stages[$story->id];
        }

        return $stories;
    }

    /**
     * 获取发布关联的Bug列表。
     * Get the bug list linked with the release.
     *
     * @param  string $bugIdList
     * @param  string $orderBy
     * @param  object $pager
     * @param  string $type      linked|left
     * @access public
     * @return array
     */
    public function getBugList(string $bugIdList,  string $orderBy = '', ?object $pager = null, string $type = 'linked'): array
    {
        $bugs = array();

        if($bugIdList)
        {
            $bugs = $this->dao->select("*, IF(`severity` = 0, {$this->config->maxPriValue}, `severity`) as severityOrder")->from(TABLE_BUG)
                ->where('id')->in($bugIdList)
                ->andWhere('deleted')->eq(0)
                ->beginIF($orderBy)->orderBy($orderBy)->fi()
                ->page($pager)
                ->fetchAll();

            $this->loadModel('common')->saveQueryCondition($this->dao->get(), $type == 'linked' ? 'linkedBug' : 'leftBugs');
        }

        return $bugs;
    }

    /**
     * 删除发布。
     * Delete a release.
     *
     * @param  string $table
     * @param  int    $releaseID
     * @access public
     * @return bool
     */
    public function delete(string $table, int $releaseID): bool
    {
        $release = $this->fetchByID($releaseID);
        if(!$release) return false;

        parent::delete(TABLE_RELEASE, $releaseID);

        if($release->shadow) $this->dao->update(TABLE_BUILD)->set('deleted')->eq(1)->where('id')->eq($release->shadow)->exec();

        $builds = $this->dao->select('*')->from(TABLE_BUILD)->where('id')->in($release->build)->fetchAll();
        foreach($builds as $build)
        {
            if(empty($build->execution) && $build->createdDate == $release->createdDate) parent::delete(TABLE_BUILD, $build->id);
        }

        return !dao::isError();
    }

    /*
     * 当发布的状态变为正常时，设置需求的阶段。
     * Set the stage of the stories when the release status is normal.
     *
     * @param  int $releaseID
     * @access public
     * @return void
     */
    public function setStoriesStage(int $releaseID): void
    {
        $release = $this->getByID($releaseID);
        if(!$release) return;

        $storyIdList = array_unique(array_filter(explode(',', $release->stories)));
        if(empty($storyIdList)) return;

        /* Reset story stagedBy field for auto compute stage. */
        $this->dao->update(TABLE_STORY)->set('stagedBy')->eq('')->where('id')->in($storyIdList)->exec();

        $this->loadModel('story');
        foreach($storyIdList as $storyID) $this->story->setStage((int)$storyID);
    }

    /**
     * 处理发布关联的对象。
     * Process the related objects of the release.
     *
     * @param  int    $releaseID
     * @param  object $release
     * @access public
     * @return void
     */
    public function processRelated(int $releaseID, object $release): void
    {
        if(!empty($release->project))  $this->updateRelated($releaseID, 'project', $release->project);
        if(!empty($release->build))    $this->updateRelated($releaseID, 'build',   $release->build);
        if(!empty($release->branch))   $this->updateRelated($releaseID, 'branch',  $release->branch);
        if(!empty($release->releases)) $this->updateRelated($releaseID, 'release', $release->releases);
        if(!empty($release->stories))  $this->updateRelated($releaseID, 'story',   $release->stories);
        if(!empty($release->bugs))     $this->updateRelated($releaseID, 'bug',     $release->bugs);
        if(!empty($release->leftBugs)) $this->updateRelated($releaseID, 'leftBug', $release->leftBugs);
    }

    /**
     * 更新发布关联的对象。
     * Update the related objects of the release.
     *
     * @param  int              $releaseID
     * @param  string           $objectType
     * @param  int|string|array $objectIdList
     * @access public
     * @return bool
     */
    public function updateRelated(int $releaseID, string $objectType, int|string|array $objectIdList): bool
    {
        if(empty($objectIdList)) return false;

        if(is_int($objectIdList))    $objectIdList = [$objectIdList];
        if(is_string($objectIdList)) $objectIdList = explode(',', $objectIdList);
        if(!is_array($objectIdList)) return false;
        if(empty($objectIdList)) return false;

        $objectIdList = array_unique(array_filter($objectIdList));

        $this->dao->delete()->from(TABLE_RELEASERELATED)->where('release')->eq($releaseID)->andWhere('objectType')->eq($objectType)->exec();

        $related = new stdClass();
        $related->release    = $releaseID;
        $related->objectType = $objectType;
        foreach($objectIdList as $objectID)
        {
            $related->objectID = $objectID;
            $this->dao->insert(TABLE_RELEASERELATED)->data($related)->exec();
        }

        return !dao::isError();
    }

    /**
     * 删除发布关联的对象。
     * Delete the related objects of the release.
     *
     * @param  int              $releaseID
     * @param  string           $objectType
     * @param  int|string|array $objectIdList
     * @access public
     * @return bool
     */
    public function deleteRelated(int $releaseID, string $objectType, int|string|array $objectIdList): bool
    {
        if(empty($objectIdList)) return false;

        if(is_string($objectIdList)) $objectIdList = explode(',', $objectIdList);
        if(!is_int($objectIdList) && !is_array($releaseID)) return false;
        if(empty($objectIdList)) return false;

        if(is_array($objectIdList)) $objectIdList = array_unique(array_filter($objectIdList));

        $this->dao->delete()->from(TABLE_RELEASERELATED)->where('release')->eq($releaseID)->andWhere('objectType')->eq($objectType)->andWhere('objectID')->in($objectIdList)->exec();
        return !dao::isError();
    }

    /**
     * 处理发布列表展示数据。
     * Process release list display data.
     *
     * @param  array  $releaseList
     * @param  array  $childReleases
     * @param  bool   $addActionsAndBuildLink
     * @access public
     * @return array
     */
    public function processReleaseListData(array $releaseList, array $childReleases = array(), bool $addActionsAndBuildLink = true): array
    {
        $releases = array();
        $this->loadModel('project');
        $this->loadModel('execution');
        foreach($releaseList as $release)
        {
            $buildCount = count($release->builds);

            $release->rowID   = $release->id;
            $release->rowspan = $buildCount;
            if($addActionsAndBuildLink) $release->actions = $this->buildActionList($release);

            $releases = array_merge($releases, $this->processReleaseBuilds($release, $addActionsAndBuildLink));

            if(empty($release->releases)) continue;

            foreach(explode(',', $release->releases) as $childID)
            {
                if(isset($childReleases[$childID]))
                {
                    $child = clone $childReleases[$childID];
                    $child = current($this->processReleaseListData(array($child)));
                    $child->rowID  = "{$release->id}-{$childID}";
                    $child->parent = $release->id;
                    $releases = array_merge($releases, $this->processReleaseBuilds($child, $addActionsAndBuildLink));
                }
            }
        }

        return $releases;
    }

    /**
     * 处理发布构建信息。
     * Process release builds.
     *
     * @param  object $release
     * @param  bool   $addActionsAndBuildLink
     * @access public
     * @return array
     */
    public function processReleaseBuilds(object $release, bool $addActionsAndBuildLink): array
    {
        $releases = array();

        if(!empty($release->builds))
        {
            foreach($release->builds as $build)
            {
                $releaseInfo = clone $release;

                if($addActionsAndBuildLink)
                {
                    $moduleName   = $build->execution ? 'build' : 'projectbuild';
                    $canClickable = false;
                    if($moduleName == 'projectbuild' && $this->project->checkPriv((int)$build->project)) $canClickable = true;
                    if($moduleName == 'build' && $this->execution->checkPriv((int)$build->execution))    $canClickable = true;
                    $build->link = $canClickable ? helper::createLink($moduleName, 'view', "buildID={$build->id}") : '';
                }

                $releaseInfo->build       = $build;
                $releaseInfo->projectName = $build->projectName;

                $releases[] = $releaseInfo;
            }
        }
        else
        {
            $releases[] = $release;
        }

        return $releases;
    }
}
