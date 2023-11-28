<?php
class projectreleaseTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('projectrelease');
    }

    /**
     * 测试获取项目发布列表。
     * Test get list of releases.
     *
     * @param  int          $projectID
     * @param  string       $type
     * @param  string       $orderBy
     * @access public
     * @return array|string
     */
    public function getListTest(int $projectID, string $type = 'all', string $orderBy = 't1.date_desc'): array|string
    {
        $objects = $this->objectModel->getList($projectID, $type, $orderBy);

        if(dao::isError()) return dao::getError();

        return implode(',', array_column($objects, 'id'));
    }

    /**
     * 测试获取项目的最新发布。
     * Test get last release.
     *
     * @param  int               $projectID
     * @access public
     * @return object|array|bool
     */
    public function getLastTest(int $projectID): object|array|bool
    {
        $object = $this->objectModel->getLast($projectID);

        if(dao::isError()) return dao::getError();

        return $object;
    }

    /**
     * 测试获取项目已经发布的版本。
     * Test get released builds from project.
     *
     * @param  int    $projectID
     * @access public
     * @return string|array
     */
    public function getReleasedBuildsTest($projectID): string|array
    {
        $objects = $this->objectModel->getReleasedBuilds($projectID);

        if(dao::isError()) return dao::getError();
        return implode(',', $objects);
    }

    /**
     * 测试项目发布信息，包括分支名称、版本信息等。
     * Test process release.
     *
     * @param  int               $releaseID
     * @access public
     * @return string|array
     */
    public function processReleaseTest(int $releaseID): string|array
    {
        global $tester;
        $release     = $tester->dao->findById($releaseID)->from(TABLE_RELEASE)->fetch();
        $branchGroup = $tester->loadModel('branch')->getByProducts(explode(',', $release->product));
        $builds      = $tester->dao->select("id, project, product, branch, execution, name, scmPath, filePath")->from(TABLE_BUILD)->where('id')->in($release->build)->fetchAll('id');
        $this->objectModel->processRelease($release, $branchGroup, $builds);

        if(dao::isError()) return dao::getError();

        $return = "project:{$release->project} branch:{$release->branch} build:{$release->build} branchName:{$release->branchName} buildInfos:" . implode(',', array_column($release->buildInfos, 'name'));
        return $return;
    }
}
