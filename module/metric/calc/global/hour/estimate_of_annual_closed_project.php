<?php
/**
 * 按全局统计的年度关闭项目的任务预计工时数。
 * estimate_of_annual_closed_project.
 *
 * 范围：global
 * 对象：project
 * 目的：hour
 * 度量名称：按全局统计的年度关闭项目的任务预计工时数
 * 单位：个
 * 描述：按全局统计的年度关闭项目的任务预计工时数是指团队或组织在某年度预计需要花费的总工时数。该度量项可以用来评估团队或组织在任务完成方面的工时规划和估算准确性。较准确的年度完成任务预计工时数可以帮助团队更好地安排资源和时间，提高任务的完成效率和进度控制。
 * 定义：所有项目任务的预计工时数求和
项目状态为已关闭
关闭时间为某年
过滤已删除的项目
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
class estimate_of_annual_closed_project extends baseCalc
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