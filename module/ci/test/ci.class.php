<?php
class ciTest
{
    private $objectModel;

    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('ci');
    }

    /**
     * Set menu.
     *
     * @param  int    $repoID
     * @param  string $module
     * @access public
     * @return array
     */
    public function setMenuTest(int $repoID = 0, string $module = '')
    {
        global $app, $lang;
        $app->moduleName = $module;

        $resetLang = clone $lang->devops->menu;

        if(!$repoID) $_SESSION['repoID'] = 1;
        $this->objectModel->setMenu($repoID);

        $result = $lang->devops->menu;
        $lang->devops->menu = $resetLang;
        return $result;
    }

    public function getCompileByID($compileID)
    {
        global $tester;
        return $tester->dao->select('compile.*, job.engine,job.pipeline, pipeline.name as jenkinsName,job.server,pipeline.url,pipeline.account,pipeline.token,pipeline.password')
            ->from(TABLE_COMPILE)->alias('compile')
            ->leftJoin(TABLE_JOB)->alias('job')->on('compile.job=job.id')
            ->leftJoin(TABLE_PIPELINE)->alias('pipeline')->on('job.server=pipeline.id')
            ->where('compile.id')->eq($compileID)
            ->fetch();
    }

    /**
     * Sync compile status.
     *
     * @param  int    $jobID
     * @access public
     * @return string|object
     */
    public function syncCompileStatusTest($jobID)
    {
        global $tester;
        $tester->loadModel('job')->exec($jobID);
        $compileID = $tester->dao->select('id')->from(TABLE_COMPILE)->orderBy('id_desc')->fetch('id');

        $notCompileMR = $tester->dao->select('id,jobID')
            ->from(TABLE_MR)
            ->where('jobID')->gt(0)
            ->andWhere('compileStatus')->eq('created')
            ->fetchPairs();

        $compile = $this->getCompileByID($compileID);

        $this->objectModel->syncCompileStatus($compile, $notCompileMR);
        $compile = $tester->loadModel('compile')->getByID($compileID);

        if(dao::isError()) return dao::getError();
        return $compile;
    }

    /**
     * Sync gitlab task status.
     *
     * @param  int    $jobID
     * @access public
     * @return string|bool
     */
    public function syncGitlabTaskStatusTest($jobID)
    {
        global $tester;
        $tester->loadModel('job')->exec($jobID);
        $compileID = $tester->dao->select('id')->from(TABLE_COMPILE)->orderBy('id_desc')->fetch('id');
        $compile   = $this->getCompileByID($compileID);

        $this->objectModel->syncGitlabTaskStatus($compile);

        if(dao::isError()) return dao::getError();
        return true;
    }

    /**
     * Update build status.
     *
     * @param  int    $compileID
     * @param  string $status
     * @access public
     * @return string|object
     */
    public function updateBuildStatusTest($compileID, $status)
    {
        $compile = $this->getCompileByID($compileID);
        $this->objectModel->updateBuildStatus($compile, $status);

        if(dao::isError()) return dao::getError();

        global $tester;
        $compile = $tester->loadModel('compile')->getByID($compileID);
        return $compile;
    }

    /**
     * Send request.
     *
     * @param  string $url
     * @param  array  $data
     * @param  string $userPWD
     * @access public
     * @return string
     */
    public function sendRequestTest($url, $data, $userPWD)
    {
        $result = $this->objectModel->sendRequest($url, $data, $userPWD);
        return $result ? 1 : 0;
    }
}
