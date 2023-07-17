<?php
/**
 * 按全局统计的年度关闭项目的任务消耗工时数。
 * consume_of_annual_closed_project.
 *
 * 范围：global
 * 对象：project
 * 目的：hour
 * 度量名称：按全局统计的年度关闭项目的任务消耗工时数
 * 单位：h
 * 描述：按全局统计的年度关闭项目的任务消耗工时数是指在某年度关闭的项目中团队或组织实际花费的总工时数，用于完成任务。该度量项可以用来评估团队或组织在任务执行过程中的工时投入情况和对资源的利用效率。较高的年度关闭项目的任务消耗工时数可能需要审查工作流程和资源分配，以提高工作效率和进度控制。
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
class consume_of_annual_closed_project extends baseCalc
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