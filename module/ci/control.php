<?php
declare(strict_types=1);
/**
 * The control file of ci module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @link        https://www.zentao.net
 */
class ci extends control
{
    /**
     * ci constructor.
     * @param string $moduleName
     * @param string $methodName
     */
    public function __construct(string $moduleName = '', string $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->ci->setMenu();
    }

    /**
     * 初始化构建队列。
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
     * 执行构建。
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
            if($compile->atTime && date('H:i') < $compile->atTime) continue;
            $this->compile->exec($compile);
        }

        echo 'success';
    }

    /**
     * 向jenkins或gitlab发送请求以检查构建状态。
     * Send a request to jenkins or gitlab to check build status.
     *
     * @param  int    $compileID
     * @access public
     * @return void
     */
    public function checkCompileStatus(int $compileID = 0)
    {
        $this->ci->checkCompileStatus($compileID);
        if(dao::isError()) return $this->sendError(dao::getError());

        return $this->send(array('result' => 'success', 'load' => $this->createLink('compile', 'logs', "compileID={$compileID}")));
    }

    /**
     * 检查ztf的提交结果。
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

        /* Get compileID and jobID. */
        parse_str($zentaoData, $params);
        $compileID = zget($params, 'compile', 0);
        $jobID     = 0;
        list($productID, $jobID) = $this->ciZen->getProductIdAndJobID($params, $post);
        if(empty($productID)) return print(json_encode(array('result' => 'fail', 'message' => 'productID is not found')));

        /* Get testtaskID or create testtask. */
        $this->ci->saveTestTaskForZtf($testType, $productID, $compileID, $taskID, $post->name);
        $this->ciZen->parseZtfResult($post, $taskID, $jobID, $compileID);
        echo json_encode(array('result' => 'success'));
    }
}
