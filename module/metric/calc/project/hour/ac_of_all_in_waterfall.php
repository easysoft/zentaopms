<?php
/**
 * 按瀑布项目统计的实际花费工时(AC)。
 * Ac of all in waterfall.
 *
 * 范围：project
 * 对象：effort
 * 目的：hour
 * 度量名称：按瀑布项目统计的实际花费工时(AC)
 * 单位：小时
 * 描述：按瀑布项目统计的实际花费工时指的是在瀑布项目管理方法中，实际花费的工时总数。这个度量项用于评估实际工作量和预计工作量之间的差异，有助于估计项目的真实进展情况。AC的值越接近EV，代表项目团队在任务执行方面表现得越好。
 * 定义：瀑布项目中所有日志记录的工时之和;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class ac_of_all_in_waterfall extends baseCalc
{
    public $dataset = 'getProjectEfforts';

    public $fieldList = array('t3.id as project', 't1.consumed', 't3.model');

    public $result = array();

    public function calculate($row)
    {
        $project = $row->project;
        $ac      = $row->consumed;
        $model   = $row->model;

        if($model != 'waterfall' && $model != 'waterfallplus') return false;

        if(!isset($this->result[$project])) $this->result[$project] = 0;
        $this->result[$project] += $ac;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
