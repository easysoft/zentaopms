<?php
/**
 * 按人员统计的待处理反馈数。
 * Count of assigned feedback in user.
 *
 * 范围：user
 * 对象：feedback
 * 目的：scale
 * 度量名称：按人员统计的待处理反馈数
 * 单位：个
 * 描述：按人员统计的待处理反馈数表示每个人待处理的反馈数量之和。反映了每个人需要处理的反馈数量上的规模。该数值越大，说明需要投入越多的时间处理反馈。
 * 定义：所有反馈个数求和;指派给为某人;过滤已删除的反馈;过滤已删除产品的反馈;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_assigned_feedback_in_user extends baseCalc
{
    public $dataset = 'getAllFeedbacks';

    public $fieldList = array('t1.assignedTo');

    public $result = array();

    public function singleQuery()
    {
        $select = "`assignedTo` as `user`, count(`assignedTo`) as `value`";
        return $this->dao->select($select)->from($this->getSingleSql())
            ->where('`assignedTo` IS NOT NULL')
            ->andWhere('`assignedTo`')->ne('closed')
            ->groupBy('`assignedTo`')
            ->fetchAll();
    }

    public function calculate($row)
    {
        $assignedTo = $row->assignedTo;

        if(empty($assignedTo) || $assignedTo == 'closed') return false;

        if(!isset($this->result[$assignedTo])) $this->result[$assignedTo] = 0;
        $this->result[$assignedTo] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('user', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
