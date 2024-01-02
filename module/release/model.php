<?php
/**
 * The model file of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: model.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php
class releaseModel extends model
{
    /**
     * Get release by id.
     *
     * @param  int    $releaseID
     * @param  bool   $setImgSize
     * @access public
     * @return object
     */
    public function getByID($releaseID, $setImgSize = false)
    {
        $release = $this->dao->select('t1.*, t2.name as productName, t2.type as productType')
            ->from(TABLE_RELEASE)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->where('t1.id')->eq((int)$releaseID)
            ->orderBy('t1.id DESC')
            ->fetch();
        if(!$release) return false;

        $release->builds = $this->dao->select('id, branch, filePath, scmPath, name, execution, project')->from(TABLE_BUILD)->where('id')->in($release->build)->fetchAll();

        $release->branches = array();
        $branchIdList = explode(',', trim($release->branch, ','));
        foreach($branchIdList as $branchID) $release->branches[$branchID] = $branchID;

        $this->loadModel('file');
        $release = $this->file->replaceImgURL($release, 'desc');
        $release->files = $this->file->getByObject('release', $releaseID);
        if(empty($release->files))$release->files = $this->file->getByObject('build', $release->build);
        if($setImgSize) $release->desc = $this->file->setImgSize($release->desc);
        return $release;
    }

    /**
     * Get list of releases.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  string $type
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getList($productID, $branch = 'all', $type = 'all', $orderBy = 't1.date_desc', $pager = null)
    {
        $releases = $this->dao->select('t1.*, t2.name as productName, t2.type as productType')->from(TABLE_RELEASE)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->where('t1.deleted')->eq(0)
            ->beginIF($productID)->andWhere('t1.product')->eq((int)$productID)->fi()
            ->beginIF($branch !== 'all')->andWhere("FIND_IN_SET($branch, t1.branch)")->fi()
            ->beginIF($type != 'all' && $type != 'review')->andWhere('t1.status')->eq($type)->fi()
            ->beginIF($type == 'review')->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.reviewers)")->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();

        $builds = $this->dao->select("t1.id, t1.name, t1.project, t1.execution, IF(t2.name IS NOT NULL, t2.name, '') AS projectName, IF(t3.name IS NOT NULL, t3.name, '{$this->lang->trunk}') AS branchName")
            ->from(TABLE_BUILD)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->leftJoin(TABLE_BRANCH)->alias('t3')->on('t1.branch = t3.id')
            ->fetchAll('id');

        foreach($releases as $release)
        {
            $releaseBuilds = array();
            foreach(explode(',', $release->build) as $buildID)
            {
                if(!$buildID or !isset($builds[$buildID])) continue;

                $releaseBuilds[] = $builds[$buildID];
            }
            $release->builds = $releaseBuilds;

            $branchName = '';
            if($release->branch != 'normal')
            {
                foreach(explode(',', trim($release->branch, ',')) as $releaseBranch)
                {
                    $branchName .= $this->loadModel('branch')->getById($releaseBranch);
                    $branchName .= ',';
                }
                $branchName = trim($branchName, ',');
            }
            $release->branchName = empty($branchName) ? $this->lang->branch->main : $branchName;
        }

        return $releases;
    }

    /**
     * Get last release.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @access public
     * @return bool | object
     */
    public function getLast($productID, $branch = 0)
    {
        return $this->dao->select('id, name')->from(TABLE_RELEASE)
            ->where('product')->eq((int)$productID)
            ->beginIF($branch)->andWhere('branch')->eq($branch)->fi()
            ->orderBy('id DESC')
            ->limit(1)
            ->fetch();
    }

    /**
     * Get released builds from product.
     *
     * @param  int        $productID
     * @param  string|int $branch
     * @access public
     * @return void
     */
    public function getReleasedBuilds($productID, $branch = 'all')
    {
        $releases = $this->dao->select('branch,shadow,build')->from(TABLE_RELEASE)
            ->where('deleted')->eq(0)
            ->andWhere('product')->eq($productID)
            ->fetchAll();

        $buildIdList = array();
        foreach($releases as $release)
        {
            if($branch != 'all' and $branch !== '')
            {
                $inBranch = false;
                foreach(explode(',', trim($release->branch, ',')) as $branchID)
                {
                    if($branchID === '') continue;

                    if(strpos(",{$branch},", ",{$branchID},") !== false) $inBranch = true;
                }
                if(!$inBranch) continue;
            }

            $builds = explode(',', $release->build);
            $buildIdList   = array_merge($buildIdList, $builds);
            $buildIdList[] = $release->shadow;
        }
        return $buildIdList;
    }

    /**
     * Get story releases.
     *
     * @param  int    $storyID
     * @access public
     * @return array
     */
    public function getStoryReleases($storyID)
    {
        return $this->dao->select('*')->from(TABLE_RELEASE)
            ->where('deleted')->eq(0)
            ->andWhere("CONCAT(stories, ',')")->like("%,$storyID,%")
            ->orderBy('id_desc')
            ->fetchAll('id');
    }

    /**
     * Create a release.
     *
     * @param  int    $productID
     * @param  int    $branch
     * @param  int    $projectID
     * @access public
     * @return int
     */
    public function create($productID = 0, $branch = 0, $projectID = 0)
    {
        if(empty($projectID))
        {
            $product = $this->loadModel('product')->getById($productID);
            if($product->shadow)
            {
                $projectID = $this->dao->select('t2.id')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                    ->leftJoin(TABLE_PROJECT)->alias('t2')
                    ->on('t1.project=t2.id')
                    ->where('t1.product')->eq($productID)
                    ->andWhere('t2.type')->eq('project')
                    ->fetch('id');
            }
        }
        /* Init vars. */
        $productID = $this->post->product ? $this->post->product : (int)$productID;
        $branch    = $this->post->branch ? $this->post->branch : (int)$branch;

        /* Check build if build is required. */
        if(strpos($this->config->release->create->requiredFields, 'build') !== false and $this->post->build == false) return dao::$errors[] = sprintf($this->lang->error->notempty, $this->lang->release->build);

        $release = fixer::input('post')
            ->add('product', (int)$productID)
            ->add('branch',  (int)$branch)
            ->setIF($projectID, 'project', $projectID)
            ->setIF($this->post->build == false, 'build', 0)
            ->setDefault('stories', '')
            ->setDefault('bugs',    '')
            ->setDefault('createdBy',   $this->app->user->account)
            ->setDefault('createdDate', helper::now())
            ->join('build', ',')
            ->join('stories', ',')
            ->join('bugs', ',')
            ->join('mailto', ',')
            ->stripTags($this->config->release->editor->create['id'], $this->config->allowedTags)
            ->remove('allchecker,files,labels,uid,sync')
            ->get();

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
            $this->dao->insert(TABLE_BUILD)->data($shadowBuild)->exec();

            if(dao::isError()) return false;
            $release->shadow = $this->dao->lastInsertID();
        }

        if($release->build)
        {
            $builds = $this->dao->select('id,project,branch,builds,stories,bugs')->from(TABLE_BUILD)->where('id')->in($release->build)->fetchAll('id');
            $linkedBuilds = array();
            foreach($builds as $build)
            {
                $build->builds = trim($build->builds, ',');
                if(empty($build->builds)) continue;
                $linkedBuilds = array_merge($linkedBuilds, explode(',', $build->builds));
            }
            if($linkedBuilds) $builds += $this->dao->select('id,project,branch,builds,stories,bugs')->from(TABLE_BUILD)->where('id')->in($linkedBuilds)->fetchAll('id');
            $branches = array();
            foreach($builds as $build)
            {
                foreach(explode(',', $build->branch) as $buildBranch)
                {
                    if(!isset($branches[$buildBranch])) $branches[$buildBranch] = $buildBranch;
                }

                $projects[$build->project] = $build->project;

                if($this->post->sync)
                {
                    $build->stories = trim($build->stories, ',');
                    $build->bugs    = trim($build->bugs, ',');
                    if($build->stories)
                    {
                        $release->stories .= ',' . $build->stories;
                        $this->loadModel('story')->updateStoryReleasedDate($build->stories, $release->date);
                    }
                    if($build->bugs)    $release->bugs    .= ',' . $build->bugs;
                }
            }
            if($this->post->sync and $release->bugs)
            {
                $releaseBugs   = $this->loadModel('bug')->getReleaseBugs(array_keys($builds), $release->product, $release->branch);
                $release->bugs = join(',', array_intersect(explode(',', $release->bugs), array_keys($releaseBugs)));
            }

            $release->build   = ',' . trim($release->build, ',') . ',';
            $release->branch  = ',' . trim(implode(',', $branches), ',') . ',';
            $release->project = ',' . trim(implode(',', $projects), ',') . ',';
        }

        $release = $this->loadModel('file')->processImgURL($release, $this->config->release->editor->create['id'], $this->post->uid);
        $this->dao->insert(TABLE_RELEASE)->data($release)
            ->autoCheck()
            ->batchCheck($this->config->release->create->requiredFields, 'notempty')
            ->check('name', 'unique', "product = '{$release->product}' AND branch = '{$release->branch}' AND deleted = '0'")
            ->checkFlow();

        if(dao::isError())
        {
            if(!empty($release->shadow)) $this->dao->delete()->from(TABLE_BUILD)->where('id')->eq($release->shadow)->exec();
            return false;
        }

        $this->dao->exec();

        if(dao::isError())
        {
            if(!empty($release->shadow)) $this->dao->delete()->from(TABLE_BUILD)->where('id')->eq($release->shadow)->exec();
        }
        else
        {
            $releaseID = $this->dao->lastInsertID();
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

        return false;
    }

    /**
     * Update a release.
     *
     * @param  int    $releaseID
     * @access public
     * @return void
     */
    public function update($releaseID)
    {
        /* Init vars. */
        $releaseID  = (int)$releaseID;
        $oldRelease = $this->getById($releaseID);

        $release = fixer::input('post')->stripTags($this->config->release->editor->edit['id'], $this->config->allowedTags)
            ->setDefault('build', '')
            ->setIF($this->post->build == false, 'build', 0)
            ->setDefault('mailto', '')
            ->setDefault('deleteFiles', array())
            ->join('build', ',')
            ->join('mailto', ',')
            ->setIF(!$this->post->marker, 'marker', 0)
            ->cleanInt('product')
            ->remove('files,labels,allchecker,uid')
            ->get();

        $release = $this->loadModel('file')->processImgURL($release, $this->config->release->editor->edit['id'], $this->post->uid);

        /* update release project and branch */
        if($release->build)
        {
            $builds   = $this->dao->select('project, branch')->from(TABLE_BUILD)->where('id')->in($release->build)->fetchAll();
            $branches = array();
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
            ->check('name', 'unique', "id != '$releaseID' AND product = '{$release->product}' AND branch = '$branch' AND deleted = '0'")
            ->checkFlow()
            ->where('id')->eq((int)$releaseID)
            ->exec();
        if(!dao::isError())
        {
            $shadowBuild = array();
            if($release->name != $oldRelease->name)   $shadowBuild['name']   = $release->name;
            if($release->build != $oldRelease->build) $shadowBuild['builds'] = $release->build;
            if($release->date != $oldRelease->date)   $shadowBuild['date']   = $release->date;
            if($shadowBuild) $this->dao->update(TABLE_BUILD)->data($shadowBuild)->where('id')->eq($oldRelease->shadow)->exec();

            $this->file->processFile4Object('release', $oldRelease, $release);
            return common::createChanges($oldRelease, $release);
        }
    }

    /**
     * Get notify persons.
     *
     * @param  object $release
     * @access public
     * @return array
     */
    public function getNotifyPersons($release)
    {
        if(empty($release->notify)) return array();

        /* Init vars. */
        $notifyPersons = array();
        $managerFields = '';
        $notifyList    = explode(',', $release->notify);

        foreach($notifyList as $notify)
        {
            if($notify == 'PO' or $notify == 'QD' or $notify == 'feedback')
            {
                $managerFields .= $notify . ',';
            }
            elseif($notify == 'SC' and !empty($release->build))
            {
                $stories  = join(',', $this->dao->select('id,stories')->from(TABLE_BUILD)->where('id')->in($release->build)->fetchPairs('id', 'stories'));
                $stories .= ',' . $this->dao->select('stories')->from(TABLE_RELEASE)->where('id')->eq($release->id)->fetch('stories');
                $stories  = trim(str_replace(',,', ',', $stories), ',');

                if(empty($stories)) continue;

                $openedByList   = $this->dao->select('openedBy')->from(TABLE_STORY)->where('id')->in($stories)->fetchPairs();
                $notifyPersons += $openedByList;
            }
            elseif(($notify == 'ET' or $notify == 'PT') and !empty($release->build))
            {
                $type     = $notify == 'ET' ? 'execution' : 'project';
                $table    = $notify == 'ET' ? TABLE_BUILD : TABLE_RELEASE;
                $objectID = $notify == 'ET' ? $release->build : $release->id;
                $members  = $this->dao->select('t2.account')->from($table)->alias('t1')
                    ->leftJoin(TABLE_TEAM)->alias('t2')->on("t1.$type=t2.root")
                    ->where('t1.id')->in($objectID)
                    ->andWhere('t2.type')->eq($type)
                    ->fetchPairs();

                if(empty($members)) continue;

                $notifyPersons += $members;
            }
            elseif($notify == 'CT' and !empty($release->mailto))
            {
                $notifyPersons += explode(',', trim($release->mailto, ','));
            }
        }

        if(!empty($managerFields))
        {
            $managerFields = trim($managerFields, ',');
            $managerUsers  = $this->dao->select($managerFields)->from(TABLE_PRODUCT)->where('id')->eq($release->product)->fetch();
            foreach($managerUsers as $account)
            {
                if(!isset($notifyPersons[$account])) $notifyPersons[$account] = $account;
            }
        }

        return $notifyPersons;
    }

    /**
     * Link stories
     *
     * @param  int    $releaseID
     * @access public
     * @return void
     */
    public function linkStory($releaseID)
    {
        $release = $this->getByID($releaseID);
        $product = $this->loadModel('product')->getByID($release->product);

        foreach($this->post->stories as $i => $storyID)
        {
            if(strpos(",{$release->stories},", ",{$storyID},") !== false) unset($_POST['stories'][$i]);
        }

        $this->loadModel('story')->updateStoryReleasedDate($release->stories, $release->date);
        $release->stories .= ',' . join(',', $this->post->stories);
        $this->dao->update(TABLE_RELEASE)->set('stories')->eq($release->stories)->where('id')->eq((int)$releaseID)->exec();

        if($release->stories)
        {
            $this->loadModel('story');
            $this->loadModel('action');
            foreach($this->post->stories as $storyID)
            {
                /* Reset story stagedBy field for auto compute stage. */
                $this->dao->update(TABLE_STORY)->set('stagedBy')->eq('')->where('id')->eq($storyID)->exec();
                if($product->type != 'normal') $this->dao->update(TABLE_STORYSTAGE)->set('stagedBy')->eq('')->where('story')->eq($storyID)->andWhere('branch')->eq($release->branch)->exec();

                $this->story->setStage($storyID);

                $this->action->create('story', $storyID, 'linked2release', '', $releaseID);
            }
        }
    }

    /**
     * Unlink story
     *
     * @param  int    $releaseID
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function unlinkStory($releaseID, $storyID)
    {
        $release = $this->getByID($releaseID);
        $release->stories = trim(str_replace(",$storyID,", ',', ",$release->stories,"), ',');
        $this->dao->update(TABLE_RELEASE)->set('stories')->eq($release->stories)->where('id')->eq((int)$releaseID)->exec();
        $this->loadModel('action')->create('story', $storyID, 'unlinkedfromrelease', '', $releaseID);

        $this->loadModel('story')->setStage($storyID);
    }

    /**
     * Batch unlink story.
     *
     * @param  int    $releaseID
     * @access public
     * @return void
     */
    public function batchUnlinkStory($releaseID)
    {
        $storyList = $this->post->storyIdList;
        if(empty($storyList)) return true;

        $release = $this->getByID($releaseID);
        $release->stories = ",$release->stories,";
        foreach($storyList as $storyID) $release->stories = str_replace(",$storyID,", ',', $release->stories);
        $release->stories = trim($release->stories, ',');
        $this->dao->update(TABLE_RELEASE)->set('stories')->eq($release->stories)->where('id')->eq((int)$releaseID)->exec();

        $this->loadModel('action');
        $this->loadModel('story');
        foreach($this->post->storyIdList as $unlinkStoryID)
        {
            $this->action->create('story', $unlinkStoryID, 'unlinkedfromrelease', '', $releaseID);
            $this->story->setStage($unlinkStoryID);
        }
    }

    /**
     * Link bugs.
     *
     * @param  int    $releaseID
     * @param  string $type
     * @access public
     * @return void
     */
    public function linkBug($releaseID, $type = 'bug')
    {
        $release = $this->getByID($releaseID);

        $field = $type == 'bug' ? 'bugs' : 'leftBugs';

        foreach($this->post->bugs as $i => $bugID)
        {
            if(strpos(",{$release->$field},", ",{$bugID},") !== false) unset($_POST['bugs'][$i]);
        }

        $release->$field .= ',' . join(',', $this->post->bugs);
        $this->dao->update(TABLE_RELEASE)->set($field)->eq($release->$field)->where('id')->eq((int)$releaseID)->exec();

        $this->loadModel('action');
        foreach($this->post->bugs as $bugID) $this->action->create('bug', $bugID, 'linked2release', '', $releaseID);
    }

    /**
     * Unlink bug.
     *
     * @param  int    $releaseID
     * @param  int    $bugID
     * @param  string $type
     * @access public
     * @return void
     */
    public function unlinkBug($releaseID, $bugID, $type = 'bug')
    {
        $release = $this->getByID($releaseID);
        $field = $type == 'bug' ? 'bugs' : 'leftBugs';
        $release->{$field} = trim(str_replace(",$bugID,", ',', ",{$release->$field},"), ',');
        $this->dao->update(TABLE_RELEASE)->set($field)->eq($release->$field)->where('id')->eq((int)$releaseID)->exec();
        $this->loadModel('action')->create('bug', $bugID, 'unlinkedfromrelease', '', $releaseID);
    }

    /**
     * Batch unlink bug.
     *
     * @param  int    $releaseID
     * @param  string $type
     * @access public
     * @return void
     */
    public function batchUnlinkBug($releaseID, $type = 'bug')
    {
        $bugList = $this->post->unlinkBugs;
        if(empty($bugList)) return true;

        $release = $this->getByID($releaseID);
        $field   = $type == 'bug' ? 'bugs' : 'leftBugs';
        $release->$field = ",{$release->$field},";
        foreach($bugList as $bugID) $release->$field = str_replace(",$bugID,", ',', $release->$field);
        $release->$field = trim($release->$field, ',');
        $this->dao->update(TABLE_RELEASE)->set($field)->eq($release->$field)->where('id')->eq((int)$releaseID)->exec();

        $this->loadModel('action');
        foreach($this->post->unlinkBugs as $unlinkBugID) $this->action->create('bug', $unlinkBugID, 'unlinkedfromrelease', '', $releaseID);
    }

    /**
     * Change status.
     *
     * @param  int    $releaseID
     * @param  string $status
     * @access public
     * @return bool
     */
    public function changeStatus($releaseID, $status)
    {
        $this->dao->update(TABLE_RELEASE)->set('status')->eq($status)->where('id')->eq($releaseID)->exec();
        return !dao::isError();
    }

    /**
     * Judge btn is clickable or not.
     *
     * @param  int    $release
     * @param  string $action
     * @static
     * @access public
     * @return bool
     */
    public static function isClickable($release, $action)
    {
        $action = strtolower($action);

        if($action == 'notify') return $release->bugs or $release->stories;
        return true;
    }

    /**
     * Send mail to release related users.
     *
     * @param  int    $releaseID
     * @access public
     * @return void
     */
    public function sendmail($releaseID)
    {
        if(empty($releaseID)) return;
        $this->app->loadConfig('mail');

        /* Load module and get vars. */
        $users   = $this->loadModel('user')->getPairs('noletter');
        $release = $this->getByID($releaseID);
        $suffix  = empty($release->product) ? '' : ' - ' . $this->loadModel('product')->getById($release->product)->name;
        $subject = 'Release #' . $release->id . ' ' . $release->name . $suffix;

        $stories  = $this->dao->select('*')->from(TABLE_STORY)->where('id')->in($release->stories)->andWhere('deleted')->eq(0)->fetchAll('id');
        $bugs     = $this->dao->select('*')->from(TABLE_BUG)->where('id')->in($release->bugs)->andWhere('deleted')->eq(0)->fetchAll();
        $leftBugs = $this->dao->select('*')->from(TABLE_BUG)->where('id')->in($release->leftBugs)->andWhere('deleted')->eq(0)->fetchAll();

        /* Get mail content. */
        $modulePath = $this->app->getModulePath($appName = '', 'release');
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
     * Get toList and ccList.
     *
     * @param  object    $release
     * @access public
     * @return bool|array
     */
    public function getToAndCcList($release)
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
     * Send mail to feedback user.
     *
     * @param  object $release
     * @param  string $subject
     * @access public
     * @return void
     */
    public function sendMail2Feedback($release, $subject)
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

        if(!empty($toList))
        {
            $storyNames  = implode(',', $storyNames);
            $bugNames    = implode(',', $bugNames);
            $mailContent = sprintf($this->lang->release->mailContent, $release->name);
            if($storyNames) $mailContent .= sprintf($this->lang->release->storyList, $storyNames);
            if($bugNames)   $mailContent .= sprintf($this->lang->release->bugList,   $bugNames);
            $this->loadModel('mail')->send(implode(',', $toList), $subject, $mailContent, '', false, $emails);
        }
    }

    /**
     * Build release action menu.
     *
     * @param  object $release
     * @param  string $type
     * @access public
     * @return string
     */
    public function buildOperateMenu($release, $type = 'view')
    {
        $function = 'buildOperate' . ucfirst($type) . 'Menu';
        return $this->$function($release);
    }

    /**
     * Build release view action menu.
     *
     * @param  object $release
     * @access public
     * @return string
     */
    public function buildOperateViewMenu($release)
    {
        $canBeChanged = common::canBeChanged('release', $release);
        if($release->deleted || !$canBeChanged || isonlybody()) return '';

        $menu   = '';
        $params = "releaseID=$release->id";

        if(common::hasPriv('release', 'changeStatus', $release))
        {
            $changedStatus = $release->status == 'normal' ? 'terminate' : 'normal';
            $menu .= html::a(inlink('changeStatus', "releaseID=$release->id&status=$changedStatus"), '<i class="icon-' . ($release->status == 'normal' ? 'pause' : 'play') . '"></i> ' . $this->lang->release->changeStatusList[$changedStatus], 'hiddenwin', "class='btn btn-link' title='{$this->lang->release->changeStatusList[$changedStatus]}'");
        }

        $menu .= "<div class='divider'></div>";
        $menu .= $this->buildFlowMenu('release', $release, 'view', 'direct');
        $menu .= "<div class='divider'></div>";

        $editClickable   = $this->buildMenu('release', 'edit',   $params, $release, 'view', '', '', '', '', '', '', false);
        $deleteClickable = $this->buildMenu('release', 'delete', $params, $release, 'view', '', '', '', '', '', '', false);
        if(common::hasPriv('release', 'edit')   and $editClickable)   $menu .= html::a(helper::createLink('release', 'edit', $params), "<i class='icon-common-edit icon-edit'></i> " . $this->lang->edit, '', "class='btn btn-link' title='{$this->lang->edit}'");
        if(common::hasPriv('release', 'delete') and $deleteClickable) $menu .= html::a(helper::createLink('release', 'delete', $params), "<i class='icon-common-delete icon-trash'></i> " . $this->lang->delete, '', "class='btn btn-link' title='{$this->lang->delete}' target='hiddenwin'");

        return $menu;
    }

    /**
     * Build release browse action menu.
     *
     * @param  object $release
     * @access public
     * @return string
     */
    public function buildOperateBrowseMenu($release)
    {
        $canBeChanged = common::canBeChanged('release', $release);
        if(!$canBeChanged) return '';

        $menu          = '';
        $params        = "releaseID=$release->id";
        $changedStatus = $release->status == 'normal' ? 'terminate' : 'normal';

        if(common::hasPriv('release', 'linkStory')) $menu .= html::a(inlink('view', "$params&type=story&link=true"), '<i class="icon-link"></i> ', '', "class='btn' title='{$this->lang->release->linkStory}'");
        if(common::hasPriv('release', 'linkBug'))   $menu .= html::a(inlink('view', "$params&type=bug&link=true"),   '<i class="icon-bug"></i> ',  '', "class='btn' title='{$this->lang->release->linkBug}'");
        $menu .= $this->buildMenu('release', 'changeStatus', "$params&status=$changedStatus", $release, 'browse', $release->status == 'normal' ? 'pause' : 'play', 'hiddenwin', '', '', '',$this->lang->release->changeStatusList[$changedStatus]);
        $menu .= $this->buildMenu('release', 'edit',   "release=$release->id", $release, 'browse');
        $menu .= $this->buildMenu('release', 'notify', "release=$release->id", $release, 'browse', 'bullhorn', '', 'iframe', true);
        $clickable = $this->buildMenu('release', 'delete', "release=$release->id", $release, 'browse', '', '', '', '', '', '', false);

        if(common::hasPriv('release', 'delete', $release))
        {
            $deleteURL = helper::createLink('release', 'delete', "releaseID=$release->id&confirm=yes");
            $class = 'btn';
            if(!$clickable) $class .= ' disabled';
            $menu .= html::a("javascript:ajaxDelete(\"$deleteURL\", \"releaseList\", confirmDelete)", '<i class="icon-trash"></i>', '', "class='{$class}' title='{$this->lang->release->delete}'");
        }

        return $menu;
    }
}
