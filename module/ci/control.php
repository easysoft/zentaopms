<?php
/**
 * The control file of ci module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: ${FILE_NAME} 5144 2020/1/8 8:10 下午 chenqi@cnezsoft.com $
 * @link        http://www.zentao.net
 */
class ci extends control
{
    /**
     * ci constructor.
     * @param string $moduleName
     * @param string $methodName
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->ci->setMenu();
    }

    /**
     * Init compile queue.
     *
     * @access public
     * @return void
     */
    public function initQueue()
    {
        $scheduleJobs = $this->loadModel('job')->getListByTriggerType('schedule');

        $week = date('w');
        $this->loadModel('compile');
        foreach($scheduleJobs as $job)
        {
            if(strpos($job->atDay, $week) !== false) $this->compile->createByJob($job->id, $job->atTime, 'atTime');
        }
        echo 'success';
    }

    /**
     * Exec compile.
     *
     * @access public
     * @return void
     */
    public function exec()
    {
        $compiles = $this->loadModel('compile')->getUnexecutedList();
        foreach($compiles as $compile)
        {
            if($compile->atTime and date('H:i') < $compile->atTime) continue;
            $this->compile->exec($compile);
        }
        echo 'success';
    }

    /**
     * Send a request to jenkins or gitlab to check build status.
     *
     * @param  int    $compileID
     * @access public
     * @return void
     */
    public function checkCompileStatus($compileID = 0)
    {
        $this->ci->checkCompileStatus($compileID);

        if(dao::isError())
        {
            echo json_encode(dao::getError());
            return true;
        }

        echo 'success';
    }

    /**
     * Commit result from ztf.
     *
     * @access public
     * @return void
     */
    public function commitResult()
    {
        /* Get post data. */
        $post = file_get_contents('php://input');
        $post = json_decode($post);

        $testType   = $post->testType;
        $productID  = zget($post, 'productId', 0);
        $taskID     = zget($post, 'taskId', 0);
        $zentaoData = zget($post, 'zentaoData', '');
        $frame      = zget($post, 'testFrame', 'junit');

        /* Get compileID and jobID. */
        parse_str($zentaoData, $params);
        $this->loadModel('testtask');
        $compileID = zget($params, 'compile', 0);
        $jobID     = 0;
        if($compileID)
        {
            $compile = $this->dao->select('t1.*, t2.pipeline,t2.product,t2.frame,t3.name as jenkinsName,t3.url,t3.account,t3.token,t3.password')->from(TABLE_COMPILE)->alias('t1')
                ->leftJoin(TABLE_JOB)->alias('t2')->on('t1.job=t2.id')
                ->leftJoin(TABLE_PIPELINE)->alias('t3')->on('t2.server=t3.id')
                ->where('t1.id')->eq($compileID)
                ->fetch();

            $jobID = $compile->job;
            if(empty($productID)) $productID = $compile->product;
            if($compile->status != 'success' and $compile->status != 'fail' and $compile->status != 'create_fail' and $compile->status != 'timeout')
            {
                $this->ci->syncCompileStatus($compile);
            }
        }

        /* Get productID from caseResult when productID is null. */
        if(empty($productID) and $testType == 'func')
        {
            $caseResult = $post->funcResult;
            $firstCase  = array_shift($caseResult);
            $productID  = $firstCase->productId;
            if(empty($productID) and !empty($firstCase->id))
            {
                $productID = $this->dao->findById($firstCase->id)->from(TABLE_CASE)->fetch('product');
            }
        }
        if(empty($productID)) return print(json_encode(array('result' => 'fail', 'message' => 'productID is not found')));

        /* Get testtaskID or create testtask. */
        if(!empty($taskID))
        {
            $testtask  = $this->testtask->getById($taskID);
            $this->dao->update(TABLE_TESTTASK)->set('auto')->eq(strtolower($testType))->where('id')->eq($taskID)->exec();
            $productID = $testtask->product;
        }
        else
        {
            $lastProject = $this->dao->select('t2.id,t2.project')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project=t2.id')
                ->where('t1.product')->eq($productID)
                ->andWhere('t2.deleted')->eq(0)
                ->andWhere('t2.project')->ne('0')
                ->orderBy('t2.id desc')
                ->limit(1)
                ->fetch();

            $testtask = new stdclass();
            $testtask->product     = $productID;
            $testtask->name        = !empty($post->name) ? $post->name : sprintf($this->lang->testtask->titleOfAuto, date('Y-m-d H:i:s'));
            $testtask->owner       = $this->app->user->account;
            $testtask->project     = $lastProject->project;
            $testtask->execution   = $lastProject->id;
            $testtask->build       = 'trunk';
            $testtask->auto        = strtolower($testType);
            $testtask->begin       = date('Y-m-d');
            $testtask->end         = date('Y-m-d', time() + 24 * 3600);
            $testtask->status      = 'done';
            $testtask->createdBy   = $this->app->user->account;
            $testtask->createdDate = helper::now();

            $this->dao->insert(TABLE_TESTTASK)->data($testtask)->exec();
            $taskID = $this->dao->lastInsertId();
            $this->loadModel('action')->create('testtask', $taskID, 'opened');
        }

        if($compileID) $this->dao->update(TABLE_COMPILE)->set('testtask')->eq($taskID)->where('id')->eq($compileID)->exec();

        /* Build data from case results. */
        if($testType == 'unit')
        {
            $data = $this->testtask->parseZTFUnitResult($post->unitResult, $frame, $productID, $jobID, $compileID);
        }
        elseif($testType == 'func')
        {
            $data = $this->testtask->parseZTFFuncResult($post->funcResult, $frame, $productID, $jobID, $compileID);
        }

        $taskID = $this->testtask->processAutoResult($taskID, $productID, $data['suites'], $data['cases'], $data['results'], $data['suiteNames'], $data['caseTitles'], $testType);

        return print(json_encode(array('result' => 'success')));
    }
}
