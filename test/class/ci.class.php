<?php
class ciTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('ci');
    }

    /**
     * Set menu.
     *
     * @access public
     * @return array
     */
    public function setMenuTest()
    {
        $_SESSION['repoID'] = 1;
        $this->objectModel->setMenu();

        global $lang;
        return $lang->devops->menu->code;
    }

    /**
     * Sync compile status.
     *
     * @param  int    $jobID
     * @access public
     * @return string
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

        $compile = $tester->dao->select('compile.*, job.engine,job.pipeline, pipeline.name as jenkinsName,job.server,pipeline.url,pipeline.account,pipeline.token,pipeline.password')
            ->from(TABLE_COMPILE)->alias('compile')
            ->leftJoin(TABLE_JOB)->alias('job')->on('compile.job=job.id')
            ->leftJoin(TABLE_PIPELINE)->alias('pipeline')->on('job.server=pipeline.id')
            ->where('compile.id')->eq($compileID)
            ->fetch();

        $this->objectModel->syncCompileStatus($compile, $notCompileMR);
        $compile = $tester->loadModel('compile')->getByID($compileID);

        if(dao::isError()) return dao::getError();
        return $compile;
    }
}
