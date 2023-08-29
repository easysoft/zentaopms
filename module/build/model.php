<?php
/**
 * The model file of build module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     build
 * @version     $Id: model.php 4970 2013-07-02 05:58:11Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class buildModel extends model
{
    /**
     * Get build info.
     *
     * @param  int    $buildID
     * @param  bool   $setImgSize
     * @access public
     * @return object|bool
     */
    public function getByID($buildID, $setImgSize = false)
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
     * Get by ID list.
     *
     * @param  array $idList
     * @access public
     * @return array
     */
    public function getByList($idList)
    {
        return $this->dao->select('*')->from(TABLE_BUILD)->where('id')->in($idList)->fetchAll('id');
    }

    /**
     * Get builds of a project.
     *
     * @param  int    $projectID
     * @param  string $type
     * @param  int    $param
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getProjectBuilds($projectID = 0, $type = 'all', $param = 0, $orderBy = 't1.date_desc,t1.id_desc', $pager = null)
    {
        return $this->dao->select('t1.*, t2.name as executionName, t2.id as executionID, t2.deleted as executionDeleted, t3.name as productName')
            ->from(TABLE_BUILD)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution = 0 or t1.execution = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.project')->ne(0)
            ->andWhere('t2.deleted')->eq(0)
            ->beginIF($projectID)->andWhere('t1.project')->eq((int)$projectID)->fi()
            ->beginIF($type == 'product' and $param)->andWhere('t1.product')->eq($param)->fi()
            ->beginIF($type == 'bysearch')->andWhere($param)->fi()
            ->beginIF($type == 'review')->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.reviewers)")->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get builds of a project by search.
     *
     * @param  int    $projectID
     * @param  int    $queryID
     * @access public
     * @return array
     */
    public function getProjectBuildsBySearch($projectID, $queryID)
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
            if(strpos($this->session->projectBuildQuery, $field) !== false)
            {
                $buildQuery = str_replace($field, "t1." . $field, $buildQuery);
            }
        }
        if(strpos($this->session->projectBuildQuery, 'execution') !== false) $buildQuery = str_replace('`execution`', 't2.`id`', $buildQuery);

        return $this->getProjectBuilds($projectID, 'bysearch', $buildQuery);
    }

    /**
     * Get builds of a execution.
     *
     * @param  int        $executionID
     * @param  string     $type      all|product|bysearch
     * @param  int|string $param     productID|buildQuery
     * @param  string     $orderBy
     * @param  object     $pager
     * @access public
     * @return array
     */
    public function getExecutionBuilds($executionID, $type = '', $param = '', $orderBy = 't1.date_desc,t1.id_desc', $pager = null)
    {
        return $this->dao->select('t1.*, t2.name as executionName, t3.name as productName')
            ->from(TABLE_BUILD)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
            ->where('t1.deleted')->eq(0)
            ->beginIF($executionID)->andWhere('t1.execution')->eq((int)$executionID)->fi()
            ->beginIF($type == 'product' and $param)->andWhere('t1.product')->eq($param)->fi()
            ->beginIF($type == 'bysearch')->andWhere($param)->fi()
            ->beginIF($type == 'review')->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.reviewers)")->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get builds of a execution by search.
     *
     * @param  int    $executionID
     * @param  int    $queryID
     * @access public
     * @return array
     */
    public function getExecutionBuildsBySearch($executionID, $queryID)
    {
        /* If there are saved query conditions, reset the session. */
        if((int)$queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('executionBuildQuery', $query->sql);
                $this->session->set('executionBuildForm', $query->form);
            }
        }

        if($this->session->executionBuildQuery == false) $this->session->set('executionBuildQuery', ' 1 = 1');
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

        return $this->getExecutionBuilds($executionID, 'bysearch', $buildQuery);
    }

    /**
     * Filter linked stories or bugs builds.
     *
     * @param  array $buildIdList
     * @access public
     * @return array
     */
    public function filterLinked($buildIdList)
    {
        $linkeds   = array();
        $buildList = $this->getByList($buildIdList);
        foreach($buildList as $build)
        {
            if(!$build->execution && !empty($build->builds))
            {
                $childBuilds = $this->getByList($build->builds);
                foreach($childBuilds as $childBuild)
                {
                    $childBuild->stories = trim($childBuild->stories, ',');
                    $childBuild->bugs    = trim($childBuild->bugs, ',');

                    if($childBuild->stories) $build->stories .= ',' . $childBuild->stories;
                    if($childBuild->bugs)    $build->bugs    .= ',' . $childBuild->bugs;
                }
            }

            if(!empty($build->stories) or !empty($build->bugs)) $linkeds[$build->id] = $build->id;
        }

        return $linkeds;
    }

    /**
     * Get story builds.
     *
     * @param  int    $storyID
     * @access public
     * @return array
     */
    public function getStoryBuilds($storyID)
    {
        return $this->dao->select('*')->from(TABLE_BUILD)
            ->where('deleted')->eq(0)
            ->andWhere("CONCAT(stories, ',')")->like("%,$storyID,%")
            ->orderBy('id_desc')
            ->fetchAll('id');
    }

    /**
     * Get builds in pairs.
     *
     * @param int|array  $products
     * @param string|int $branch
     * @param string     $params   noempty|notrunk|noterminate|withbranch|hasproject|noDeleted|singled|noreleased|releasedtag, can be a set of them
     * @param string|int $objectID
     * @param string     $objectType
     * @param int|array  $buildIdList
     * @param bool       $replace
     * @access public
     * @return array
     */
    public function getBuildPairs($products, $branch = 'all', $params = 'noterminate, nodone', $objectID = 0, $objectType = 'execution', $buildIdList = '', $replace = true)
    {
        $sysBuilds      = array();
        $selectedBuilds = array();
        if(strpos($params, 'noempty') === false) $sysBuilds = array('' => '');
        if(strpos($params, 'notrunk') === false) $sysBuilds = $sysBuilds + array('trunk' => $this->lang->trunk);
        $productIdList = is_array($products) ? array_keys($products) : $products;
        if($buildIdList)
        {
            $buildIdList = str_replace('trunk', '0', $buildIdList);
            $selectedBuilds = $this->dao->select('id, name')->from(TABLE_BUILD)
                ->where('id')->in($buildIdList)
                ->beginIF($products and $products != 'all')->andWhere('product')->in($productIdList)->fi()
                ->beginIF($objectType === 'execution' and $objectID)->andWhere('execution')->eq($objectID)->fi()
                ->beginIF($objectType === 'project' and $objectID)->andWhere('project')->eq($objectID)->fi()
                ->beginIF(strpos($params, 'hasdeleted') === false)->andWhere('deleted')->eq(0)->fi()
                ->fetchPairs();
        }
        $branchPairs = $this->dao->select('id,name')->from(TABLE_BRANCH)->fetchPairs();

        $shadows   = $this->dao->select('shadow')->from(TABLE_RELEASE)->where('product')->in($productIdList)->fetchPairs('shadow', 'shadow');
        $branchs   = strpos($params, 'separate') === false ? "0,$branch" : $branch;
        $allBuilds = $this->dao->select('t1.id, t1.name, t1.branch, t1.execution, t1.date, t1.deleted, t2.status as objectStatus, t3.id as releaseID, t3.status as releaseStatus, t4.type as productType')->from(TABLE_BUILD)->alias('t1')
            ->beginIF($objectType === 'execution')->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution = t2.id')->fi()
            ->beginIF($objectType === 'project')->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')->fi()
            ->leftJoin(TABLE_RELEASE)->alias('t3')->on("FIND_IN_SET(t1.id,t3.build)")
            ->leftJoin(TABLE_PRODUCT)->alias('t4')->on('t1.product = t4.id')
            ->where('1=1')
            ->beginIf(!empty($shadows))->andWhere('t1.id')->notIN($shadows)->fi()
            ->beginIF(strpos($params, 'hasdeleted') === false)->andWhere('t1.deleted')->eq(0)->fi()
            ->beginIF(strpos($params, 'hasproject') !== false)->andWhere('t1.project')->ne(0)->fi()
            ->beginIF(strpos($params, 'singled') !== false)->andWhere('t1.execution')->ne(0)->fi()
            ->beginIF($products and $products != 'all')->andWhere('t1.product')->in($productIdList)->fi()
            ->beginIF($objectType === 'execution' and $objectID)->andWhere('t1.execution')->eq($objectID)->fi()
            ->beginIF($objectType === 'project' and $objectID)->andWhere('t1.project')->eq($objectID)->fi()
            ->orderBy('t1.date desc, t1.id desc')->fetchAll('id');

        $deletedExecutions = $this->dao->select('id, deleted')->from(TABLE_EXECUTION)->where('type')->eq('sprint')->andWhere('deleted')->eq('1')->fetchPairs();

        /* Set builds and filter done executions and terminate releases. */
        $builds      = array();
        $buildIdList = array();
        $this->app->loadLang('branch');
        foreach($allBuilds as $id => $build)
        {
            if($build->branch === '') $build->branch = 0;
            if(empty($build->releaseID) and (strpos($params, 'nodone') !== false) and ($build->objectStatus === 'done')) continue;
            if((strpos($params, 'noterminate') !== false) and ($build->releaseStatus === 'terminate')) continue;
            if((strpos($params, 'withexecution') !== false) and $build->execution and isset($executions[$build->execution])) continue;
            if($branch !== 'all' and strpos(",{$build->branch},", ",{$branch},") === false) continue;

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
            if(strpos($params, 'withbranch') !== false and $build->productType != 'normal') $buildName = $branchName . '/' . $buildName;

            $buildIdList[$id] = $id;
            $builds[$build->date][$id] = $buildName;
        }

        if(empty($builds) and empty($shadows)) return $sysBuilds + $selectedBuilds;

        /* if the build has been released and replace is true, replace build name with release name. */
        if($replace)
        {
            $releases = $this->dao->select('t1.id,t1.shadow,t1.product,t1.branch,t1.build,t1.name,t1.date,t3.name as branchName,t4.type as productType')->from(TABLE_RELEASE)->alias('t1')
                ->leftJoin(TABLE_BUILD)->alias('t2')->on('FIND_IN_SET(t2.id, t1.build)')
                ->leftJoin(TABLE_BRANCH)->alias('t3')->on('FIND_IN_SET(t3.id, t1.branch)')
                ->leftJoin(TABLE_PRODUCT)->alias('t4')->on('t1.product=t4.id')
                ->where('t2.id')->in($buildIdList)
                ->andWhere('t1.product')->in($productIdList)
                ->andWhere('t1.deleted')->eq(0)
                ->andWhere('t1.shadow')->ne(0)
                ->fetchAll('id');
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
            foreach($releases as $release)
            {
                if($branch !== 'all')
                {
                    $inBranch = false;
                    foreach(explode(',', trim($release->branch, ',')) as $branchID)
                    {
                        if($branchID === '') continue;
                        if(strpos(",{$branchs},", ",{$branchID},") !== false) $inBranch = true;
                    }
                    if(!$inBranch) continue;
                }

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
        }

        krsort($builds);
        $buildPairs = array();
        foreach($builds as $date => $childBuilds) $buildPairs += $childBuilds;

        return $sysBuilds + $buildPairs + $selectedBuilds;
    }

    /**
     * Get last build.
     *
     * @param  int    $executionID
     * @param  int    $projectID
     * @access public
     * @return bool | object
     */
    public function getLast($executionID = 0, $projectID = 0)
    {
        return $this->dao->select('id, name')->from(TABLE_BUILD)
            ->where('deleted')->eq(0)
            ->andWhere('execution')->eq((int)$executionID)
            ->beginIF($projectID)->andWhere('project')->eq((int)$projectID)->fi()
            ->orderBy('date DESC,id DESC')
            ->limit(1)
            ->fetch();
    }

    /**
     * Create a build
     *
     * @access public
     * @return void
     */
    public function create()
    {
        $build = new stdclass();
        $build->stories = '';
        $build->bugs    = '';

        $build = fixer::input('post')
            ->setDefault('project,execution,product,branch,artifactRepoID', 0)
            ->setDefault('builds,stories,bugs', '')
            ->cleanInt('product')
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->stripTags($this->config->build->editor->create['id'], $this->config->allowedTags)
            ->join('builds', ',')
            ->join('branch', ',')
            ->remove('resolvedBy,allchecker,files,labels,isIntegrated,uid,isArtifactRepo')
            ->get();

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

        $product = $this->loadModel('product')->getByID($build->product);
        if(!empty($product) and $product->type != 'normal' and $this->post->isIntegrated == 'no' and !isset($_POST['branch']))
        {
            $this->lang->product->branch = sprintf($this->lang->product->branch, $this->lang->product->branchName[$product->type]);
            dao::$errors['branch'] = sprintf($this->lang->error->notempty, $this->lang->product->branch);
        }

        if(dao::isError()) return false;

        $build = $this->loadModel('file')->processImgURL($build, $this->config->build->editor->create['id'], $this->post->uid);
        $this->dao->insert(TABLE_BUILD)->data($build)
            ->autoCheck()
            ->batchCheck($this->config->build->create->requiredFields, 'notempty')
            ->check('name', 'unique', "product = {$build->product} AND branch = '{$build->branch}' AND deleted = '0'")
            ->checkFlow()
            ->exec();

        if(!dao::isError())
        {
            $buildID = $this->dao->lastInsertID();
            $this->file->updateObjectID($this->post->uid, $buildID, 'build');
            $this->file->saveUpload('build', $buildID);
            $this->loadModel('score')->create('build', 'create', $buildID);
            return $buildID;
        }
    }

    /**
     * Update a build.
     *
     * @param  int    $buildID
     * @access public
     * @return void
     */
    public function update($buildID)
    {
        $buildID    = (int)$buildID;
        $oldBuild   = $this->dao->select('*')->from(TABLE_BUILD)->where('id')->eq($buildID)->fetch();
        $newProduct = $this->dao->select('id,type')->from(TABLE_PRODUCT)->where('id')->eq($_POST['product'])->fetchPairs();
        $branch     = (!isset($_POST['branch']) or $newProduct == 'normal') ? 0 : $oldBuild->branch;
        $build      = fixer::input('post')->stripTags($this->config->build->editor->edit['id'], $this->config->allowedTags)
            ->add('id', $buildID)
            ->setDefault('branch', $branch)
            ->setDefault('product', $oldBuild->product)
            ->setDefault('builds', '')
            ->cleanInt('product,execution')
            ->join('builds', ',')
            ->join('branch', ',')
            ->remove('allchecker,resolvedBy,files,labels,uid')
            ->get();

        if(empty($oldBuild->execution))
        {
            $buildBranch = array();
            foreach(explode(',', trim($build->branch, ',')) as $branchID) $buildBranch[$branchID] = $branchID;

            /* Get delete builds. */
            $deleteBuilds = array();
            foreach(explode(',', $oldBuild->builds) as $oldBuildID)
            {
                if(empty($oldBuildID)) continue;
                if(strpos(",$newBuilds,", ",$oldBuildID,") === false) $deleteBuilds[$oldBuildID] = $oldBuildID;
            }

            /* Delete the branch when the branch of the deleted build has no linked stories. */
            $deleteBuildBranches = $this->dao->select('branch')->from(TABLE_BUILD)->where('id')->in($deleteBuilds)->fetchPairs();
            $linkedStoryBranches = $this->dao->select('branch')->from(TABLE_STORY)->where('id')->in($oldBuild->stories)->fetchPairs('branch');
            foreach($deleteBuildBranches as $deleteBuildBranch)
            {
                foreach(explode(',', $deleteBuildBranch) as $deleteBuildBranchID)
                {
                    if(empty($deleteBuildBranchID) or isset($linkedStoryBranches[$deleteBuildBranchID])) continue;
                    unset($buildBranch[$deleteBuildBranchID]);
                }
            }

            /* Add branch of new builds. */
            $newBuilds        = isset($build->builds) ? $build->builds : '';
            $newBuildBranches = $this->dao->select('branch')->from(TABLE_BUILD)->where('id')->in($newBuilds)->fetchPairs();
            foreach($newBuildBranches as $newBuildBranch)
            {
                foreach(explode(',', $newBuildBranch) as $newBuildBranchID)
                {
                    if(empty($newBuildBranchID)) continue;
                    if(!isset($buildBranch[$newBuildBranchID])) $buildBranch[$newBuildBranchID] = $newBuildBranchID;
                }
            }

            $build->branch = implode(',', $buildBranch);
        }

        $product = $this->loadModel('product')->getByID($build->product);
        if(!empty($product) and $product->type != 'normal' and !isset($_POST['branch']) and isset($_POST['product']))
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
        if(isset($build->branch) and $oldBuild->branch != $build->branch) $this->dao->update(TABLE_RELEASE)->set('branch')->eq($build->branch)->where('build')->eq($buildID)->exec();
        if(!dao::isError())
        {
            $this->file->updateObjectID($this->post->uid, $buildID, 'build');
            return common::createChanges($oldBuild, $build);
        }
    }

    /**
     * Update linked bug to resolved.
     *
     * @param  object    $build
     * @access public
     * @return void
     */
    public function updateLinkedBug($build)
    {
        $bugs = empty($build->bugs) ? '' : $this->dao->select('*')->from(TABLE_BUG)->where('id')->in($build->bugs)->fetchAll();
        $data = fixer::input('post')->get();
        $now  = helper::now();

        $resolvedPairs = array();
        if(isset($_POST['bugs']))
        {
            foreach($data->bugs as $key => $bugID)
            {
                if(isset($_POST['resolvedBy'][$bugID])) $resolvedPairs[$bugID] = $data->resolvedBy[$bugID];
            }
        }

        $this->loadModel('action');
        if(!$bugs) return false;
        foreach($bugs as $bug)
        {
            if($bug->status == 'resolved' or $bug->status == 'closed') continue;

            $bug->resolvedBy     = $resolvedPairs[$bug->id];
            $bug->resolvedDate   = $now;
            $bug->status         = 'resolved';
            $bug->confirmed      = 1;
            $bug->assignedDate   = $now;
            $bug->assignedTo     = $bug->openedBy;
            $bug->lastEditedBy   = $this->app->user->account;
            $bug->lastEditedDate = $now;
            $bug->resolution     = 'fixed';
            $bug->resolvedBuild  = $build->id;
            $this->dao->update(TABLE_BUG)->data($bug)->where('id')->eq($bug->id)->exec();
            $this->action->create('bug', $bug->id, 'Resolved', '', 'fixed', $bug->resolvedBy);
        }
    }

    /**
     * Link stories
     *
     * @param  int    $buildID
     * @access public
     * @return void
     */
    public function linkStory($buildID)
    {
        $build = $this->getByID($buildID);

        foreach($this->post->stories as $i => $storyID)
        {
            if(strpos(",{$build->stories},", ",{$storyID},") !== false) unset($_POST['stories'][$i]);
        }

        $build->stories .= ',' . join(',', $this->post->stories);
        $this->dao->update(TABLE_BUILD)->set('stories')->eq($build->stories)->where('id')->eq((int)$buildID)->exec();

        $this->loadModel('action');
        foreach($this->post->stories as $storyID) $this->action->create('story', $storyID, 'linked2build', '', $buildID);
        $this->action->create('build', $buildID, 'linkstory', '', implode(',', $this->post->stories));
    }

    /**
     * Unlink story
     *
     * @param  int    $buildID
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function unlinkStory($buildID, $storyID)
    {
        $build = $this->getByID($buildID);
        $build->stories = trim(str_replace(",$storyID,", ',', ",$build->stories,"), ',');
        if($build->stories) $build->stories = ',' . $build->stories;

        $this->dao->update(TABLE_BUILD)->set('stories')->eq($build->stories)->where('id')->eq((int)$buildID)->exec();
        $this->loadModel('action')->create('story', $storyID, 'unlinkedfrombuild', '', $buildID, '', false);
    }

    /**
     * Batch unlink story.
     *
     * @param  int    $buildID
     * @access public
     * @return void
     */
    public function batchUnlinkStory($buildID)
    {
        $storyList = $this->post->unlinkStories;
        if(empty($storyList)) return true;

        $build = $this->getByID($buildID);
        $build->stories = ",$build->stories,";
        foreach($storyList as $storyID) $build->stories = str_replace(",$storyID,", ',', $build->stories);
        $build->stories = trim($build->stories, ',');
        $this->dao->update(TABLE_BUILD)->set('stories')->eq($build->stories)->where('id')->eq((int)$buildID)->exec();

        $this->loadModel('action');
        foreach($this->post->unlinkStories as $unlinkStoryID) $this->action->create('story', $unlinkStoryID, 'unlinkedfrombuild', '', $buildID);
    }

    /**
     * Link bugs.
     *
     * @param  int    $buildID
     * @access public
     * @return void
     */
    public function linkBug($buildID)
    {
        $build = $this->getByID($buildID);

        foreach($this->post->bugs as $i => $bugID)
        {
            if(strpos(",{$build->bugs},", ",{$bugID},") !== false) unset($_POST['bugs'][$i]);
        }

        $build->bugs .= ',' . join(',', $this->post->bugs);
        $this->updateLinkedBug($build);
        $this->dao->update(TABLE_BUILD)->set('bugs')->eq($build->bugs)->where('id')->eq((int)$buildID)->exec();

        $this->loadModel('action');
        foreach($this->post->bugs as $bugID) $this->action->create('bug', $bugID, 'linked2bug', '', $buildID);
    }

    /**
     * Unlink bug.
     *
     * @param  int    $buildID
     * @param  int    $bugID
     * @access public
     * @return void
     */
    public function unlinkBug($buildID, $bugID)
    {
        $build = $this->getByID($buildID);
        $build->bugs = trim(str_replace(",$bugID,", ',', ",$build->bugs,"), ',');
        if($build->bugs) $build->bugs = ',' . $build->bugs;

        $this->dao->update(TABLE_BUILD)->set('bugs')->eq($build->bugs)->where('id')->eq((int)$buildID)->exec();
        $this->loadModel('action')->create('bug', $bugID, 'unlinkedfrombuild', '', $buildID, '', false);
    }

    /**
     * Batch unlink bug.
     *
     * @param  int    $buildID
     * @access public
     * @return void
     */
    public function batchUnlinkBug($buildID)
    {
        $bugList = $this->post->unlinkBugs;
        if(empty($bugList)) return true;

        $build = $this->getByID($buildID);
        $build->bugs = ",$build->bugs,";
        foreach($bugList as $bugID) $build->bugs = str_replace(",$bugID,", ',', $build->bugs);
        $build->bugs = trim($build->bugs, ',');
        $this->dao->update(TABLE_BUILD)->set('bugs')->eq($build->bugs)->where('id')->eq((int)$buildID)->exec();

        $this->loadModel('action');
        foreach($this->post->unlinkBugs as $unlinkBugID) $this->action->create('bug', $unlinkBugID, 'unlinkedfrombuild', '', $buildID);
    }

    /**
     * Bugs and stories associated with child builds.
     *
     * @param  object  $build
     * @access public
     * @return object
     */
    public function joinChildBuilds($build)
    {
        $build->allBugs    = $build->bugs;
        $build->allStories = $build->stories;

        $childBuilds = $this->dao->select('id,name,bugs,stories')->from(TABLE_BUILD)->where('id')->in($build->builds)->fetchAll();
        foreach($childBuilds as $childBuild)
        {
            if($childBuild->bugs)    $build->allBugs    .= ",{$childBuild->bugs}";
            if($childBuild->stories) $build->allStories .= ",{$childBuild->stories}";
        }

        $build->allBugs    = explode(',', $build->allBugs);
        $build->allBugs    = join(',', array_unique(array_filter($build->allBugs)));
        $build->allStories = explode(',', $build->allStories);
        $build->allStories = join(',', array_unique(array_filter($build->allStories)));

        return $build;
    }

    /**
     * Adjust the action is clickable.
     *
     * @param  string $bug
     * @param  string $action
     * @param  string $module
     * @access public
     * @return void
     */
    public static function isClickable($object, $action, $module = 'bug')
    {
        $action = strtolower($action);

        if($module == 'testtask' and $action == 'create') return !$object->executionDeleted;

        return true;
    }

    /**
     * Build action menu.
     *
     * @param  object $build
     * @param  string $type
     * @param  string $extra
     * @access public
     * @return string
     */
    public function buildOperateMenu($build, $type = 'view', $extra = '')
    {
        $extraParams = array();
        if($extra) parse_str($extra, $extraParams);

        $menu   = '';
        $params = "buildID=$build->id";

        global $app;
        $tab = $app->tab;

        $module = $this->app->tab == 'project' ? 'projectbuild' : 'build';
        if($type == 'browse')
        {
            $executionID = $tab == 'execution' ? $extraParams['executionID'] : $build->execution;
            $execution   = $this->loadModel('execution')->getByID($executionID);
            $build->executionDeleted = $execution ? $execution->deleted : 0;

            $testtaskApp = (!empty($execution->type) and $execution->type == 'kanban') ? 'data-app="qa"' : "data-app='{$tab}'";

            if(common::hasPriv($module, 'linkstory') and common::canBeChanged('build', $build)) $menu .= $this->buildMenu($module, 'view', "{$params}&type=story&link=true", $build, $type, 'link', '', '', '', "data-app={$tab}", $this->lang->build->linkStory);

            $title = ($execution and $execution->deleted === '1') ? $this->lang->build->notice->createTest : '';
            $menu .= $this->buildMenu('testtask', 'create', "product=$build->product&execution={$executionID}&build=$build->id&projectID=$build->project", $build, $type, 'bullhorn', '', '', '', $testtaskApp, $title);

            if($tab == 'execution' and !empty($execution->type) and $execution->type != 'kanban') $menu .= $this->buildMenu('execution', 'bug', "execution={$extraParams['executionID']}&productID={$extraParams['productID']}&branchID=all&orderBy=status&build=$build->id", $build, $type, '', '', '', '', $this->lang->execution->viewBug);
            if($tab == 'project' or empty($execution->type) or $execution->type == 'kanban')      $menu .= $this->buildMenu($module, 'view', "{$params}&type=generatedBug", $build, $type, 'bug', '', '', '', "data-app='$tab'", $this->lang->project->bug);

            $menu .= $this->buildMenu($module, 'edit',   $params, $build, $type);
            $menu .= $this->buildMenu($module, 'delete', $params, $build, $type, 'trash', 'hiddenwin');
        }
        else
        {
            $canBeChanged = common::canBeChanged('build', $build);
            if($build->deleted || !$canBeChanged) return '';

            $menu .= $this->buildFlowMenu('build', $build, 'view', 'direct');

            $editClickable   = $this->buildMenu($module, 'edit',   $params, $build, $type, '', '', '', '', '', '', false);
            $deleteClickable = $this->buildMenu($module, 'delete', $params, $build, $type, '', '', '', '', '', '', false);
            if(common::hasPriv($module, 'edit')   and $editClickable)   $menu .= html::a(helper::createLink($module, 'edit',   $params), "<i class='icon-common-edit icon-edit'></i> "    . $this->lang->edit,   '', "class='btn btn-link' title='{$this->lang->build->edit}' data-app='{$app->tab}'");
            if(common::hasPriv($module, 'delete') and $deleteClickable) $menu .= html::a(helper::createLink($module, 'delete', $params), "<i class='icon-common-delete icon-trash'></i> " . $this->lang->delete, '', "class='btn btn-link' title='{$this->lang->build->delete}' target='hiddenwin' data-app='{$app->tab}'");
        }

        return $menu;
    }
}
