<?php
/**
 * 按项目统计的开放的问题数。
 * Count of opened issue in project.
 *
 * 范围：project
 * 对象：issue
 * 目的：scale
 * 度量名称：按项目统计的开放的问题数
 * 单位：个
 * 描述：按项目统计的开放的问题数指的是在项目管理中，正在被跟踪和解决的项目问题的数量。问题是指在项目执行过程中遇到的障碍、困难或需要解决的事项。通过跟踪和解决项目问题，可以避免问题的积累和对项目目标的影响。
 * 定义：项目中问题的个数求和;状态为开放;过滤已删除的问题;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_opened_issue_in_project extends baseCalc
{
    public $dataset = 'getIssues';

    public $fieldList = array('t1.project', 't1.status');

    public $result = array();

    public function calculate($row)
    {
        $project = $row->project;
        $status  = $row->status;

        if(!isset($this->result[$project])) $this->result[$project] = 0;
        if(in_array($status, array('active', 'confirmed', 'unconfirmed'))) $this->result[$project] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
