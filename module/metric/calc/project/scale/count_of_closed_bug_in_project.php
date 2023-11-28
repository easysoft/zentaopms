<?php
/**
 * 按项目统计的已关闭Bug数。
 * Count of closed bug in project.
 *
 * 范围：project
 * 对象：bug
 * 目的：scale
 * 度量名称：按项目统计的已关闭Bug数
 * 单位：个
 * 描述：按项目统计的已关闭Bug总数是指已经被关闭的Bug数量。这个度量项反映了项目中已经关闭的缺陷数量。已关闭Bug总数的增加说明项目进行了持续的改进和修复工作。
 * 定义：项目中Bug个数求和;状态为已关闭;过滤已删除的Bug;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_closed_bug_in_project extends baseCalc
{
    public $dataset = 'getProjectBugs';

    public $fieldList = array('t1.project', 't1.status');

    public $result = array();

    public function calculate($row)
    {
        $project = $row->project;
        $status  = $row->status;

        if(!isset($this->result[$project])) $this->result[$project] = 0;
        if($status == 'closed') $this->result[$project] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
