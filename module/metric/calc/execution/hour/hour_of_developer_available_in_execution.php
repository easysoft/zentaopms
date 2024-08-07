<?php
/**
 * 按执行统计的开发人员可用工时
 * Hour of developer available in execution
 *
 * 范围：execution
 * 对象：user
 * 目的：hour
 * 度量名称：按执行统计的开发人员可用工时
 * 单位：小时
 * 描述：按执行统计的开发人员可用工时是指执行团队中角色为研发的可用工时之和。该度量项反映了团队中开发人员能够投入在本迭代的时间，有助于计算执行团队的工作负载。
 * 定义：执行团队成员每日可用工时*可用工日，人员职位为研发，过滤已删除的执行，过滤已删除的项目。
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class hour_of_developer_available_in_execution extends baseCalc
{
    public $dataset = 'getTeamMembers';

    public $fieldList = array('t2.id', 't1.days', 't1.hours', 't4.role');

    public $result = array();

    public function calculate($row)
    {
    }

    public function getResult($options = array())
    {
        return $this->filterByOptions($this->getRecords(array('execution', 'value')), $options);
    }
}
