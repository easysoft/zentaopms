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

    /**
    * Test update a release.
    *
    * @param  int    $releaseID
    * @param  string $name
    * @param  string $date
    * @access public
    * @return array
    */
    public function updateTest($releaseID, $name = '', $date = '')
    {
        global $app;
        $app->loadConfig('release');

        $updateFields['name']    = $name;
        $updateFields['build']   = 1;
        $updateFields['date']    = $date;
        $updateFields['status']  = 'normal';
        $updateFields['desc']    = '';
        $updateFields['labels']  = array();
        $updateFields['files']   = array();
        $updateFields['uid']     = '62450877d0a27';
        $updateFields['product'] = 1;
        foreach($updateFields as $field => $defaultValue) $_POST[$field] = $defaultValue;

        $objects = $this->objectModel->update($releaseID);
        if($objects == array()) $objects = '没有数据更新';
        unset($_POST);

        if(dao::isError()) return dao::getError();

        return $objects;
    }
}
