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
     * @param  int    $projectID
     * @param  string $type
     * @access public
     * @return array|string
     */
    public function getListTest(int $projectID, string $type = 'all'): array|string
    {
        $objects = $this->objectModel->getList($projectID, $type);

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
}
