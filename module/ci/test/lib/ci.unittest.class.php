<?php
class ciTest
{
    private $objectModel;
    private $objectZen;

    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('ci');
        $tester->app->setModuleName('ci');
        
        // Load ci classes
        include_once dirname(dirname(dirname(__FILE__))) . '/model.php';
        include_once dirname(dirname(dirname(__FILE__))) . '/control.php';
        include_once dirname(dirname(dirname(__FILE__))) . '/zen.php';
        $this->objectZen   = new ReflectionClass('ciZen');
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

    /**
     * Test getCompileByID method.
     *
     * @param  int    $compileID
     * @access public
     * @return object|false
     */
    public function getCompileByIdTest(int $compileID): object|false
    {
        return $this->objectModel->getCompileByID($compileID);
    }

    /**
     * Sync compile status.
     *
     * @param  int    $jobID
     * @param  int    $MRID
     * @access public
     * @return object|array
     */
    public function syncCompileStatusTest(int $compileID, int $MRID = 0): object|array
    {
        $compile = $this->objectModel->dao->select('compile.*, job.engine,job.pipeline, pipeline.name as jenkinsName,job.server,pipeline.url,pipeline.account,pipeline.token,pipeline.password')
            ->from(TABLE_COMPILE)->alias('compile')
            ->leftJoin(TABLE_JOB)->alias('job')->on('compile.job=job.id')
            ->leftJoin(TABLE_PIPELINE)->alias('pipeline')->on('job.server=pipeline.id')
            ->where('compile.id')->eq($compileID)
            ->fetch();

        $this->objectModel->syncCompileStatus($compile, $MRID);
        $compile = $this->objectModel->loadModel('compile')->getByID($compileID);

        if(dao::isError()) return dao::getError();
        return $compile;
    }

    /**
     * Sync gitlab task status.
     *
     * @param  int    $jobID
     * @access public
     * @return object|array
     */
    public function syncGitlabTaskStatusTest(int $compileID): object|array
    {
        $compile = $this->objectModel->getCompileByID($compileID);
        $this->objectModel->syncGitlabTaskStatus($compile);

        if(dao::isError()) return dao::getError();
        return $this->objectModel->loadModel('job')->getByID($compile->job);
    }

    /**
     * Update build status.
     *
     * @param  int    $compileID
     * @param  string $status
     * @param  string $check
     * @access public
     * @return object|array
     */
    public function updateBuildStatusTest(int $compileID, string $status, string $check = 'job'): object|array
    {
        $compile = $this->objectModel->getCompileByID($compileID);
        $this->objectModel->updateBuildStatus($compile, $status);

        if(dao::isError()) return dao::getError();

        if($check == 'mr')
        {
            return $this->objectModel->dao->select('*')->from(TABLE_MR)->where('compileID')->eq($compileID)->fetch();
        }

        return $this->objectModel->loadModel('compile')->getByID($compileID);
    }

    /**
     * Send request.
     *
     * @param  string $url
     * @param  string $data
     * @param  string $userPWD
     * @access public
     * @return int
     */
    public function sendRequestTest(string $url, object $data, string $userPWD): int
    {
        $result = $this->objectModel->sendRequest($url, $data, $userPWD);
        return $result ? 1 : 0;
    }

    /**
     * Test checkCompileStatus method.
     *
     * @param  int    $compileID
     * @access public
     * @return object|array|bool
     */
    public function checkCompileStatusTest(int $compileID): object|array|bool
    {
        $result = $this->objectModel->checkCompileStatus($compileID);
        if(dao::isError()) return dao::getError();

        if($compileID)
        {
            $compile = $this->objectModel->dao->select('*')->from(TABLE_COMPILE)->where('id')->eq($compileID)->fetch();
            return $compile ? $compile : false;
        }

        $compiles = $this->objectModel->dao->select('id, name, status')->from(TABLE_COMPILE)->where('deleted')->eq('0')->fetchAll('id');
        return $compiles ? $compiles : $result;
    }

    /**
     * Test saveCompile method.
     *
     * @param  int    $compileID
     * @param  string $response
     * @access public
     * @return mixed
     */
    public function saveCompileTest(int $compileID, string $response): mixed
    {
        $compile = $this->objectModel->getCompileByID($compileID);
        if(!$compile) return false;

        $result = $this->objectModel->saveCompile($response, $compile, "{$compile->account}:{$compile->token}", $compile->url);
        if(dao::isError()) return dao::getError();

        $updatedCompile = $this->objectModel->getCompileByID($compileID);
        return array(
            'result' => $result,
            'status' => $updatedCompile->status ?? '',
            'logs' => $updatedCompile->logs ?? '',
            'hasLog' => !empty($updatedCompile->logs)
        );
    }

    /**
     * Test saveTestTaskForZtf method.
     *
     * @param  int    $productID
     * @param  int    $taskID
     * @param  string $name
     * @access public
     * @return object|false
     */
    public function saveTestTaskForZtfTest(int $productID, int $taskID = 0, string $name = ''): object|false
    {
        $this->objectModel->saveTestTaskForZtf('unit', $productID, 1, $taskID, $name);
        if($taskID) return $this->objectModel->loadModel('testtask')->getByID($taskID);

        return $this->objectModel->dao->select('*')->from(TABLE_TESTTASK)->orderBy('id_desc')->fetch();
    }

    /**
     * Test getProductIdAndJobID method.
     *
     * @param  array  $params
     * @param  object $post
     * @access public
     * @return array
     */
    public function getProductIdAndJobIDTest(array $params, object $post): array
    {
        $method = $this->objectZen->getMethod('getProductIdAndJobID');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->objectZen->newInstance(), [$params, $post]);
        if(dao::isError()) return dao::getError();

        return $result;
    }

    /**
     * Test parseZtfResult method.
     *
     * @param  object $post
     * @param  int    $taskID
     * @param  int    $productID
     * @param  int    $jobID
     * @param  int    $compileID
     * @access public
     * @return bool
     */
    public function parseZtfResultTest(object $post, int $taskID, int $productID, int $jobID, int $compileID): bool
    {
        $method = $this->objectZen->getMethod('parseZtfResult');
        $method->setAccessible(true);
        $result = $method->invokeArgs($this->objectZen->newInstance(), [$post, $taskID, $productID, $jobID, $compileID]);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}
