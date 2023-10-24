<?php
/**
 * 按项目统计的Bug总数。
 * Count of bug in project.
 *
 * 范围：project
 * 对象：bug
 * 目的：scale
 * 度量名称：按项目统计的Bug总数
 * 单位：个
 * 描述：按项目统计的Bug总数是指在项目中发现的所有Bug的数量。这个度量项反映了项目的整体Bug质量情况。Bug总数越多可能代表项目的代码质量存在问题，需要进行进一步的解决和改进。
 * 定义：项目中Bug个数求和;过滤已删除的Bug;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_bug_in_project extends baseCalc
{
    public $dataset = 'getProjectBugs';

    public $fieldList = array('t1.project');

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->project])) $this->result[$row->project] = 0;
        $this->result[$row->project] ++;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
