<?php
declare(strict_types = 1);

require_once dirname(__FILE__, 5) . '/test/lib/test.class.php';

class ciModelTest extends baseTest
{
    protected $moduleName = 'ci';
    protected $className  = 'model';

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
        if($module) $app->moduleName = $module;

        $resetLang = clone $lang->devops->menu;

        if(!$repoID) $_SESSION['repoID'] = 1;
        $this->instance->setMenu($repoID);

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
        return $this->instance->getCompileByID($compileID);
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
        $compile = $this->instance->dao->select('compile.*, job.engine,job.pipeline, pipeline.name as jenkinsName,job.server,pipeline.url,pipeline.account,pipeline.token,pipeline.password')
            ->from(TABLE_COMPILE)->alias('compile')
            ->leftJoin(TABLE_JOB)->alias('job')->on('compile.job=job.id')
            ->leftJoin(TABLE_PIPELINE)->alias('pipeline')->on('job.server=pipeline.id')
            ->where('compile.id')->eq($compileID)
            ->fetch();

        $this->instance->syncCompileStatus($compile, $MRID);
        $compile = $this->instance->loadModel('compile')->getByID($compileID);

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
        $compile = $this->instance->getCompileByID($compileID);
        $this->instance->syncGitlabTaskStatus($compile);

        if(dao::isError()) return dao::getError();
        return $this->instance->loadModel('job')->getByID($compile->job);
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
        $compile = $this->instance->getCompileByID($compileID);
        $this->instance->updateBuildStatus($compile, $status);

        if(dao::isError()) return dao::getError();

        if($check == 'mr')
        {
            return $this->instance->dao->select('*')->from(TABLE_MR)->where('compileID')->eq($compileID)->fetch();
        }

        return $this->instance->loadModel('compile')->getByID($compileID);
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
        $result = $this->instance->sendRequest($url, $data, $userPWD);
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
        $result = $this->instance->checkCompileStatus($compileID);
        if(dao::isError()) return dao::getError();

        if($compileID)
        {
            $compile = $this->instance->dao->select('*')->from(TABLE_COMPILE)->where('id')->eq($compileID)->fetch();
            return $compile ? $compile : false;
        }

        $compiles = $this->instance->dao->select('id, name, status')->from(TABLE_COMPILE)->where('deleted')->eq('0')->fetchAll('id');
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
        $compile = $this->instance->getCompileByID($compileID);
        if(!$compile) return false;

        $result = $this->instance->saveCompile($response, $compile, "{$compile->account}:{$compile->token}", $compile->url);
        if(dao::isError()) return dao::getError();

        $updatedCompile = $this->instance->getCompileByID($compileID);
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
        $this->instance->saveTestTaskForZtf('unit', $productID, 1, $taskID, $name);
        if($taskID) return $this->instance->loadModel('testtask')->getByID($taskID);

        return $this->instance->dao->select('*')->from(TABLE_TESTTASK)->orderBy('id_desc')->fetch();
    }

    /**
     * Test transformAnsiToHtml method.
     *
     * @param  string $text
     * @access public
     * @return string
     */
    public function transformAnsiToHtmlTest(string $text): string
    {
        $result = $this->instance->transformAnsiToHtml($text);
        if(dao::isError()) return dao::getError();

        return $result;
    }
}
