<?php
class buildTest
{
    public function __construct()
    {
         global $tester;
         $this->objectModel = $tester->loadModel('build');
    }

    /**
     * function getByID by test
     *
     * @param  string $buildID
     * @param  bool   $setImgSize
     * @access public
     * @return object
     */
    public function getByIDTest(int $buildID, bool $setImgSize = false)
    {
        $objects = $this->objectModel->getByID($buildID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 通过版本ID列表获取版本信息。
     * Get builds by id list.
     *
     * @param  array     $idList
     * @param  int       $count
     * @access public
     * @return array|int
     */
    public function getByListTest(array $idList, int $count): array|int
    {
        $objects = $this->objectModel->getByList($idList);

        if(dao::isError()) return dao::getError();
        if($count == 1)  return count($objects);

        return $objects;
    }

    /**
     * function getProjectBuilds test by build
     *
     * @param  int       $count
     * @param  int       $projectID
     * @param  string    $type
     * @param  string    $param
     * @param  string    $orderBy
     * @param  string    $pager
     * @access public
     * @return array|int
     */
    public function getProjectBuildsTest(int $count, int $projectID, string $type = 'all', string $param = '', string $orderBy = 't1.date_desc,t1.id_desc', object $pager = null): array|int
    {
        $objects = $this->objectModel->getProjectBuilds($projectID, $type, $param, $orderBy, $pager);

        if(dao::isError()) return dao::getError();
        if($count == '1')  return count($objects);

        return $objects;
    }

    /**
     * function getProjectBuildsBySearch test by build
     *
     * @param  int       $count
     * @param  int       $projectID
     * @param  int       $queryID
     * @access public
     * @return array|int
     */
    public function getProjectBuildsBySearchTest(int $count, int $projectID, int $queryID): array|int
    {
        $objects = $this->objectModel->getProjectBuildsBySearch($projectID, $queryID);

        if(dao::isError()) return dao::getError();
        if($count == '1')  return count($objects);

        return $objects;
    }

    /**
     * function getExecutionBuilds test by build
     *
     * @param  int       $count
     * @param  int       $executionID
     * @param  string    $type
     * @param  string    $param
     * @param  string    $orderBy
     * @param  string    $pager
     * @access public
     * @return array|int
     */
    public function getExecutionBuildsTest(int $count, int $executionID, string $type = '', string $param = '', string $orderBy = 't1.date_desc,t1.id_desc', object $pager = null): array|int
    {
        $objects = $this->objectModel->getExecutionBuilds($executionID, $type, $param, $orderBy, $pager);

        if(dao::isError()) return dao::getError();
        if($count == '1')  return count($objects);

        return $objects;
    }

    /**
     * function getExecutionBuildsBySearch test by build
     *
     * @param  int       $count
     * @param  int       $executionID
     * @param  int       $queryID
     * @access public
     * @return array|int
     */
    public function getExecutionBuildsBySearchTest(int $count, int $executionID, int $queryID): array|int
    {
        $objects = $this->objectModel->getExecutionBuildsBySearch($executionID, $queryID);

        if(dao::isError()) return dao::getError();
        if($count == '1')  return count($objects);


        return $objects;
    }

    /**
     * Function getBuildPairs test by build
     *
     * @param  int    $count
     * @param  array  $products
     * @param  string $branch
     * @param  string $params
     * @param  int    $objectID
     * @param  string $objectType
     * @param  string $buildIdList
     * @param  string $replace
     * @access public
     * @return array
     */
    public function getBuildPairsTest($count, $products, $branch = 'all', $params = 'noterminate, nodone', $objectID = 0, $objectType = 'execution', $buildIdList = '', $replace = true)
    {
        $objects = $this->objectModel->getBuildPairs($products, $branch, $params, $objectID, $objectType, $buildIdList, $replace);

        if(dao::isError()) return dao::getError();
        if($count == '1')  return count($objects);

        return $objects;
    }

    /**
     * Function getLast test by build
     *
     * @param  string $executionID
     * @access public
     * @return array
     */
    public function getLastTest($executionID)
    {
        $objects = $this->objectModel->getLast($executionID);

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Function create test by build
     *
     * @param  array $param
     * @access public
     * @return array
     */
    public function createTest($param = array())
    {
        $toData = date('Y-m-d');
        $createFields = array(
            'product'  => '',
            'name'     => '',
            'builder'  => 'admin',
            'date'     => $toData,
            'scmPath'  => '',
            'filePath' => '',
            'desc'     => '',
            'branch'   => '',
            'project'  => 11,
        );

        foreach($param as $key => $value) $createFields[$key] = $value;

        $objectID = $this->objectModel->create((object)$createFields);

        if(dao::isError()) return dao::getError();

        $objects = $this->objectModel->getByID((int)$objectID);
        return $objects;
    }

    /**
     * Function update test by build
     *
     * @param  int   $buildID
     * @param  array $param
     * @access public
     * @return array
     */
    public function updateTest($buildID, $param = array())
    {
        $toData = date('Y-m-d');

        $createFields = array(
            'name'     => '',
            'builder'  => 'admin',
            'date'     => $toData,
            'scmPath'  => '',
            'filePath' => '',
            'desc'     => '',
            'branch'   => '',
            'product'  => 1,
        );

        foreach($param as $key => $value) $createFields[$key] = $value;

        $objects = $this->objectModel->update($buildID, (object)$createFields);
        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * Function updateLinkedBug test by build
     *
     * @param  int   $buildID
     * @param  array $param
     * @access public
     * @return array
     */
    public function updateLinkedBugTest(int $buildID, array $param = array()): array
    {
        $build     = $this->objectModel->getByID($buildID);
        $bugIdList = zget($param, 'bugs', array());

        $this->objectModel->updateLinkedBug($build, $bugIdList, zget($param, 'resolvedBy', array()));
        $objects = $this->objectModel->dao->select('*')->from(TABLE_BUG)->where('id')->in($bugIdList)->fetchAll('id');

        if(dao::isError()) return dao::getError();
        return $objects;
    }

    /**
     * Function linkStory test by build
     *
     * @param  int   $buildID
     * @param  array $param
     * @access public
     * @return array
     */
    public function linkStoryTest($buildID, $storyIdList = array())
    {
        $this->objectModel->linkStory($buildID, $storyIdList);
        $objects = $this->objectModel->dao->select('*')->from(TABLE_BUILD)->where('id')->in($buildID)->fetchAll('id');

        if(dao::isError()) return dao::getError();
        return $objects;
    }

    /**
     * Function unlinkStory test by build
     *
     * @param  int   $buildID
     * @param  array $stories
     * @param  int  $storyID
     * @access public
     * @return array
     */
    public function unlinkStoryTest($buildID, $stories = array(), $storyID = 0)
    {
        $this->objectModel->linkStory($buildID, $stories);
        $this->objectModel->unlinkStory($buildID, $storyID);
        $objects = $this->objectModel->dao->select('*')->from(TABLE_BUILD)->where('id')->in($buildID)->fetchAll('id');

        if(dao::isError()) return dao::getError();
        return $objects;
    }

    /**
     * Functtion batchUnlinkStory test by build
     *
     * @param  int $buildID
     * @param  array $stories
     * @access public
     * @return array
     */
    public function batchUnlinkStoryTest($buildID, $stories = array())
    {
        $this->objectModel->linkStory($buildID, $stories);
        $this->objectModel->batchUnlinkStory($buildID, $stories);
        $objects = $this->objectModel->dao->select('*')->from(TABLE_BUILD)->where('id')->in($buildID)->fetchAll('id');

        if(dao::isError()) return dao::getError();

        return $objects;
    }

    /**
     * 版本关联Bug。
     * Link bugs.
     *
     * @param  int   $buildID
     * @param  array $param
     * @access public
     * @return array
     */
    public function linkBugTest(int $buildID, array $bugs = array()): array
    {
        $oldBuild = $this->objectModel->getByID($buildID);
        $this->objectModel->linkBug($buildID, $bugs);

        if(!$oldBuild) return array();
        if(dao::isError()) return dao::getError();

        $build = $this->objectModel->getByID($buildID);
        return common::createChanges($oldBuild, $build);
    }

    /**
     * 解除Bug跟版本的关联关系。
     * Unlink bug.
     *
     * @param  int    $buildID
     * @param  int    $bugID
     * @access public
     * @return mixed
     */
    public function unlinkBugTest(int $buildID, int $bugID): mixed
    {
        $this->objectModel->unlinkBug($buildID, $bugID);
        $objects = $this->objectModel->dao->select('*')->from(TABLE_BUILD)->where('id')->in($buildID)->fetch();

        if(dao::isError()) return dao::getError();
        return $objects;
    }

    /**
     * 批量解除Bug跟版本的关联关系。
     * Batch unlink bugs.
     *
     * @param  int    $buildID
     * @param  array  $bugIdList
     * @access public
     * @return array
     */
    public function batchUnlinkBugTest(int $buildID, array $bugIdList): array
    {
        $this->objectModel->batchUnlinkBug($buildID, $bugIdList);
        $objects = $this->objectModel->dao->select('*')->from(TABLE_BUILD)->where('id')->in($buildID)->fetchAll('id');

        if(dao::isError()) return dao::getError();
        return $objects;
    }

    /**
     * Test get build's data for block.
     *
     * @param  int     $projectID
     * @param  string  $orderBy
     * @param  int     $limit
     * @param  bool    $adminUser
     * @access public
     * @return array
     */
    public function getBuildBlockDataTest(int $projectID = 0, string $orderBy = 'id_desc', int $limit = 10, bool $adminUser = true): array
    {
        global $tester;

        if(!$adminUser) $tester->app->user->admin = false;

        $build = $this->objectModel->getBuildBlockData($projectID, $orderBy, $limit);
        if(!$adminUser) $tester->app->user->admin = true;

        return $build;
    }

    /**
     * 根据版本日期分组设置版本信息。
     * Set build date group.
     *
     * @param  string $branch
     * @param  string $params
     * @access public
     * @return array
     */
    public function setBuildDateGroupTest(string $branch, string $params): array
    {
        $allBuilds = $this->objectModel->fetchBuilds(array(), '', 11, 'project');
        list($builds, $excludedReleaseIdList) = $this->objectModel->setBuildDateGroup($allBuilds, $branch, $params);

        return $builds;
    }

    /**
     * 将版本名称替换为发布名称。
     * Replace the build name with release name.
     *
     * @param  string $branch
     * @param  string $params
     * @access public
     * @return void
     */
    public function replaceNameWithReleaseTest(string $branch, string $params)
    {
        $allBuilds = $this->objectModel->fetchBuilds(array(), '', 11, 'project');
        list($builds, $excludedReleaseIdList) = $this->objectModel->setBuildDateGroup($allBuilds, $branch, $params);
        $releases = $this->objectModel->dao->select('t1.id,t1.shadow,t1.product,t1.branch,t1.build,t1.name,t1.date,t3.name as branchName,t4.type as productType')->from(TABLE_RELEASE)->alias('t1')
            ->leftJoin(TABLE_BUILD)->alias('t2')->on('FIND_IN_SET(t2.id, t1.build)')
            ->leftJoin(TABLE_BRANCH)->alias('t3')->on('FIND_IN_SET(t3.id, t1.branch)')
            ->leftJoin(TABLE_PRODUCT)->alias('t4')->on('t1.product=t4.id')
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t1.shadow')->ne(0)
            ->fetchAll('id');

        /* Get the buildID under the shadow product. */
        $shadows = $this->objectModel->dao->select('shadow')->from(TABLE_RELEASE)->fetchPairs('shadow', 'shadow');
        if($shadows)
        {
            /* Append releases of only shadow and not link build. */
            $releases += $this->objectModel->dao->select('t1.id,t1.shadow,t1.product,t1.branch,t1.build,t1.name,t1.date,t2.name as branchName,t3.type as productType')->from(TABLE_RELEASE)->alias('t1')
                ->leftJoin(TABLE_BRANCH)->alias('t2')->on('FIND_IN_SET(t2.id, t1.branch)')
                ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product=t3.id')
                ->where('t1.shadow')->in($shadows)
                ->andWhere('t1.build')->eq(0)
                ->andWhere('t1.deleted')->eq(0)
                ->fetchAll('id');
        }

        return $this->objectModel->replaceNameWithRelease($allBuilds, $builds, $releases, $branch, $params, $excludedReleaseIdList);
    }

    /**
     * Test processBuildForUpdate method.
     *
     * @param  int    $buildID
     * @param  int    $type
     * @access public
     * @return object
     */
    public function processBuildForUpdate($buildID, $type)
    {
        $oldBuild = $this->objectModel->getById($buildID);

        $build = new stdclass();
        $build->name   = $type;
        $build->branch = '';
        $build->builds = '';
        if($type != 'noBranch') $build->branch = '1';
        if($type != 'noBuild') $build->builds = '1,2,3';
        return $this->objectModel->processBuildForUpdate($build, $oldBuild);
    }

    /**
     * Is clickable
     *
     * @param  string     $model
     * @param  bool       $deleted
     * @access public
     * @return array|bool
     */
    public function isClickable(string $model = 'bug', bool $deleted = false): array|bool
    {
        $build = new stdclass();
        $build->executionDeleted = $deleted;
        $objectModels = $this->objectModel->isClickable($build, 'create', $model);
        if(dao::isError()) return dao::getError();

        return $objectModels;
    }

    /**
     * 根据状态和权限生成列表中操作列按钮。
     * Build table action menu for build browse page.
     *
     * @param  int    $buildID
     * @param  int    $executionID
     * @access public
     * @return string
     */
    public function buildActionListObject(int $buildID, int $executionID = 0): string
    {
        $build   = $this->objectModel->getByID($buildID);
        $actions = $this->objectModel->buildActionList($build, $executionID);
        return implode('|', $actions);
    }
}
