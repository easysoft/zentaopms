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
        if(empty($release->files))$release->files = $this->file->getByObject('build', (int)$release->build);
        if($setImgSize) $release->desc = $this->file->setImgSize($release->desc);

        return $release;
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
    public function getList(int $productID, string $branch = 'all', string $type = 'all', string $orderBy = 't1.date_desc', string $releaseQuery = '', object $pager = null): array
    {
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
            ->fetchAll();

        $builds = $this->dao->select("t1.id, t1.name, t1.project, t1.execution, IF(t2.name IS NOT NULL, t2.name, '') AS projectName, IF(t3.name IS NOT NULL, t3.name, '{$this->lang->trunk}') AS branchName")
            ->from(TABLE_BUILD)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->leftJoin(TABLE_BRANCH)->alias('t3')->on('t1.branch = t3.id')
            ->fetchAll('id');

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

            $branchName = '';
            if($release->productType != 'normal')
            {
                foreach(explode(',', trim($release->branch, ',')) as $releaseBranch)
                {
                    $branchName .= $releaseBranch === '0' ? $this->lang->branch->main : $this->branch->getByID($releaseBranch);
                    $branchName .= ',';
                }
                $branchName = trim($branchName, ',');
            }
            $release->branchName = empty($branchName) ? $this->lang->branch->main : $branchName;
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
            $shadowBuild->builds       = $release->build;
            $shadowBuild->name         = $release->name;
            $shadowBuild->date         = $release->date;
            $shadowBuild->createdBy    = $this->app->user->account;
            $shadowBuild->createdDate  = helper::now();
        }

        if($release->build) $release = $this->processReleaseForCreate($release, $isSync);
        $release = $this->loadModel('file')->processImgURL($release, $this->config->release->editor->create['id'], $this->post->uid);

        $this->dao->insert(TABLE_RELEASE)->data($release)
            ->autoCheck()
            ->batchCheck($this->config->release->create->requiredFields, 'notempty')
            ->check('name', 'unique', "product = '{$release->product}' AND branch = '{$release->branch}' AND deleted = '0'")
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

            $storyIDList = array_filter(explode(',', $release->stories));
            foreach($storyIDList as $storyID)
            {
                $this->story->setStage($storyID);
                $this->action->create('story', $storyID, 'linked2release', '', $releaseID);
            }
        }

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
        if($isSync && $release->bugs)
        {
            $releaseBugs   = $this->bug->getReleaseBugs(array_keys($builds), $release->product, $release->branch);
            $release->bugs = implode(',', array_intersect(explode(',', $release->bugs), array_keys($releaseBugs)));
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
        $release = $this->loadModel('file')->processImgURL($release, $this->config->release->editor->edit['id'], $this->post->uid);

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

        $this->dao->update(TABLE_RELEASE)->data($release, 'deleteFiles')
            ->autoCheck()
            ->batchCheck($this->config->release->edit->requiredFields, 'notempty')
            ->check('name', 'unique', "id != '{$oldRelease->id}' AND product = '{$release->product}' AND branch = '{$release->branch}' AND deleted = '0'")
            ->checkFlow()
            ->where('id')->eq($oldRelease->id)
            ->exec();

        if(dao::isError()) return false;

        $shadowBuild = array();
        if($release->name != $oldRelease->name)   $shadowBuild['name']   = $release->name;
        if($release->build != $oldRelease->build) $shadowBuild['builds'] = $release->build;
        if($release->date != $oldRelease->date)   $shadowBuild['date']   = $release->date;
        if($shadowBuild) $this->dao->update(TABLE_BUILD)->data($shadowBuild)->where('id')->eq($oldRelease->shadow)->exec();

        $this->file->processFile4Object('release', $oldRelease, $release);
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
            elseif(($notify == 'ET' || $notify == 'PT') && !empty($release->build))
            {
                $table   = $notify == 'ET' ? TABLE_BUILD : TABLE_RELEASE;
                $members = $this->dao->select('t2.account')->from($table)->alias('t1')
                    ->leftJoin(TABLE_TEAM)->alias('t2')->on("t1.$type=t2.root")
                    ->where('t1.id')->in($notify == 'ET' ? $release->build : $release->id)
                    ->andWhere('t2.type')->eq($notify == 'ET' ? 'execution' : 'project')
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
                $this->dao->update(TABLE_STORY)->set('stagedBy')->eq('')->where('id')->eq($storyID)->exec();
                if($product->type != 'normal') $this->dao->update(TABLE_STORYSTAGE)->set('stagedBy')->eq('')->where('story')->eq($storyID)->andWhere('branch')->eq($release->branch)->exec();

                $this->story->setStage($storyID);

                $this->action->create('story', $storyID, 'linked2release', '', $releaseID);
            }
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
            $this->action->create('story', $unlinkStoryID, 'unlinkedfromrelease', '', $releaseID);
            $this->loadModel('story')->setStage($unlinkStoryID);
        }

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

        $field   = $type == 'bug' ? 'bugs' : 'leftBugs';
        foreach($bugs as $i => $bugID)
        {
            if(strpos(",{$release->$field},", ",{$bugID},") !== false) unset($bugs[$i]);
        }

        $release->$field .= ',' . implode(',', $bugs);
        $this->dao->update(TABLE_RELEASE)->set($field)->eq($release->$field)->where('id')->eq($releaseID)->exec();

        $this->loadModel('action');
        foreach($bugs as $bugID) $this->action->create('bug', $bugID, 'linked2release', '', $releaseID);

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
        foreach($bugIdList as $unlinkBugID) $this->action->create('bug', $unlinkBugID, 'unlinkedfromrelease', '', $releaseID);

        return !dao::isError();
    }

    /**
     * 激活/停止维护发布。
     * Change status.
     *
     * @param  int    $releaseID
     * @param  string $status    normal|terminate
     * @access public
     * @return bool
     */
    public function changeStatus(int $releaseID, string $status): bool
    {
        $this->dao->update(TABLE_RELEASE)->set('status')->eq($status)->where('id')->eq($releaseID)->exec();
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
        $action = strtolower($action);

        if($action == 'notify') return $release->bugs || $release->stories;
        if($action == 'play')   return $release->status == 'terminate';
        if($action == 'pause')  return $release->status == 'normal';
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
        $viewFile   = $modulePath . 'view/sendmail.html.php';
        chdir($modulePath . 'view');
        if(file_exists($modulePath . 'ext/view/sendmail.html.php'))
        {
            $viewFile = $modulePath . 'ext/view/sendmail.html.php';
            chdir($modulePath . 'ext/view');
        }
        ob_start();
        include $viewFile;
        foreach(glob($modulePath . 'ext/view/sendmail.*.html.hook.php') as $hookFile) include $hookFile;
        $mailContent = ob_get_contents();
        ob_end_clean();
        chdir($oldcwd);

        if(strpos(",{$release->notify},", ',FB,') !== false) $this->sendMail2Feedback($release, $subject);

        /* Get the sender. */
        $sendUsers = $this->getToAndCcList($release);
        if(!$sendUsers) return;

        list($toList, $ccList) = $sendUsers;

        /* Send it. */
        $this->loadModel('mail')->send($toList, $subject, $mailContent, $ccList);
    }

    /**
     * 获取发送邮件的人员。
     * Get toList and ccList.
     *
     * @param  object      $release
     * @access public
     * @return false|array
     */
    public function getToAndCcList(object $release): false|array
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
     * 通过产品ID列表获取产品下发布。
     * Get releases of the product through the product ID list.
     *
     * @param  array  $productIdList
     * @access public
     * @return array
     */
    public function getPairsByProduct(array $productIdList): array
    {
        return $this->dao->select('id,name')->from(TABLE_RELEASE)
            ->where('product')->in($productIdList)
            ->andWhere('deleted')->eq('0')
            ->fetchPairs();
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
    public function getStoryList(string $storyIdList, string $branch, string $orderBy = '', object $pager = null): array
    {
        $stories = $this->dao->select("t1.*,t2.id as buildID, t2.name as buildName, IF(t1.`pri` = 0, {$this->config->maxPriValue}, t1.`pri`) as priOrder")->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_BUILD)->alias('t2')->on("FIND_IN_SET(t1.id, t2.stories)")
            ->where('t1.id')->in($storyIdList)
            ->andWhere('t1.deleted')->eq(0)
            ->beginIF(!empty($storyIdList))->groupBy('t1.id')->fi()
            ->beginIF($orderBy)->orderBy($orderBy)->fi()
            ->page($pager, 't1.id')
            ->fetchAll('id');

        $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'story', false);

        $stages = $this->dao->select('*')->from(TABLE_STORYSTAGE)->where('story')->in(array_keys($stories))->andWhere('branch')->in($branch)->fetchPairs('story', 'stage');
        foreach($stages as $storyID => $stage) $stories[$storyID]->stage = $stage;

        return $stories;
    }

    /**
     * 获取发布关联的Bug列表。
     * Get the bug list linked with the release.
     *
     * @param  string $bugIdList
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getBugList(string $bugIdList,  string $orderBy = '', object $pager = null): array
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

            $this->loadModel('common')->saveQueryCondition($this->dao->get(), 'leftBugs');
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
}
