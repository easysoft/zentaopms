<?php
/**
 * 按全局统计的年度已关闭项目投入总人天。
 * hour_of_annual_closed_project.
 *
 * 范围：global
 * 对象：project
 * 目的：hour
 * 度量名称：按全局统计的年度已关闭项目投入总人天
 * 单位：人天
 * 描述：按全局统计的年度关闭项目投入总人天是指在某年度关闭项目的团队总共投入的工作天数。该度量项可以用来评估项目的人力资源投入情况。投入总人天的增加可能意味着项目投入的工作时间和资源的增加。
 * 定义：复用：
按全局统计的年度关闭项目消耗工时数
公式：
按全局统计的年度关闭项目投入总人天=按全局统计的年度已关闭项目任务的消耗工时数/后台配置的每天可用工时
 * 度量库：
 * 收集方式：realtime
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class hour_of_annual_closed_project extends baseCalc
{
    public $dataset = null;

    public $fieldList = array();

    //public funtion getStatement($dao)
    //{
    //}

    public function calculate($data)
    {
    }

    public function getResult($options = array())
    {
        return $this->filterByOptions($this->result, $options);
    }
}