<?php
declare(strict_types=1);
/**
 * The zen file of ci module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     ci
 * @link        https://www.zentao.net
 */
class ciZen extends ci
{
    /**
     * 根据ztf提供数据获取产品ID和构建ID。
     * Get productID and jobID by ztf data.
     *
     * @param  array     $params
     * @param  object    $post
     * @access protected
     * @return array
     */
    protected function getProductIdAndJobID(array $params, object $post): array
    {
        $testType  = $post->testType;
        $compileID = zget($params, 'compile', 0);
        $productID = zget($post, 'productId', 0);
        $jobID     = 0;
        if($compileID)
        {
            $compile = $this->ci->getCompileByID($compileID);
            $jobID   = $compile->job;
            if(empty($productID)) $productID = $compile->product;
            if(!in_array($compile->status, array('success', 'fail', 'create_fail', 'timeout'))) $this->ci->syncCompileStatus($compile);
        }

        /* Get productID from caseResult when productID is null. */
        if(empty($productID) && $testType == 'func')
        {
            $caseResult = $post->funcResult;
            $firstCase  = array_shift($caseResult);
            $productID  = $firstCase->productId;
            if(empty($productID) && !empty($firstCase->id))
            {
                $case = $this->loadModel('testcase')->fetchByID($firstCase->id);
                if($case) $productID = $case->product;
            }
        }

        return array($productID, $jobID);
    }

    /**
     * 根据ZTF提供数据解析结果。
     * Parse result by ztf data.
     *
     * @param  object    $post
     * @param  int       $taskID
     * @param  int       $productID
     * @param  int       $jobID
     * @param  int       $compileID
     * @access protected
     * @return bool
     */
    protected function parseZtfResult(object $post, int $taskID, int $productID, int $jobID, int $compileID): bool
    {
        $this->loadModel('testtask');
        $frame    = zget($post, 'testFrame', 'junit');
        $testType = $post->testType;
        /* Build data from case results. */
        if($testType == 'unit')
        {
            $data = $this->testtask->parseZTFUnitResult($post->unitResult, $frame, $productID, $jobID, $compileID);
        }
        elseif($testType == 'func')
        {
            $data = $this->testtask->parseZTFFuncResult($post->funcResult, $frame, $productID, $jobID, $compileID);
        }

        $this->testtask->processAutoResult($taskID, $productID, $data['suites'], $data['cases'], $data['results'], $data['suiteNames'], $data['caseTitles'], $testType);
        return !dao::isError();
    }
}
