<?php
/**
 * 按项目统计的严重程度为1、2级的Bug数。
 * Count of severity 1 and 2 bug in project.
 *
 * 范围：project
 * 对象：bug
 * 目的：scale
 * 度量名称：按项目统计的严重程度为1、2级的Bug数
 * 单位：个
 * 描述：按项目统计的严重程度为1、2级的Bug数是指在项目开发过程中发现的严重程度为1级和2级的Bug数量的总和。统计这些Bug的数量可以评估项目开发过程的质量和稳定性，同时也关注影响用户体验和功能完整性的问题。
 * 定义：复用： 按项目统计的严重程度为1级的Bug数、按项目统计的严重程度为2级的Bug数。公式： 按项目统计的严重程度为1、2级的Bug数=按项目统计的严重程度为1级的Bug数+按项目统计的严重程度为2级的Bug数
 *
 * @copyright Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    songchenxuan <songchenxuan@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_severe_bug_in_project extends baseCalc
{
    public $dataset = 'getProjectBugs';

    public $fieldList = array('t1.severity', 't1.project');

    public $result = array();

    public function calculate($data)
    {
        $severity = $data->severity;
        $project  = $data->project;

        if(!isset($this->result[$project])) $this->result[$project] = 0;

        if($severity == '1' || $severity == '2') $this->result[$project] += 1;
    }
}
