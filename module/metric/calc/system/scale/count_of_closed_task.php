<?php
/**
 * 按系统统计的已关闭任务数。
 * Count of closed task.
 *
 * 范围：system
 * 对象：task
 * 目的：scale
 * 度量名称：按系统统计的已关闭任务数
 * 单位：个
 * 描述：按系统统计的已关闭任务数是指团队或组织已经关闭的任务总量。该度量项可以用来评估项目或团队的运营情况和任务管理效果。较高的已关闭任务总数可能表明团队在任务管理方面表现出较好的能力，同时也可以释放资源和优先处理其他任务。
 * 定义：所有的任务个数求和;状态为已关闭;过滤已删除的任务;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_closed_task extends baseCalc
{
    public $dataset = 'getTasks';

    public $fieldList = array('t1.status');

    public $result = 0;

    public function calculate($row)
    {
        if($row->status == 'closed') $this->result ++;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}
