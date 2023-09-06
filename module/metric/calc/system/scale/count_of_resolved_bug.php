<?php
/**
 * 按全局统计的已解决Bug数。
 * Count of resolved bug.
 *
 * 范围：global
 * 对象：bug
 * 目的：scale
 * 度量名称：按全局统计的已解决Bug数
 * 单位：个
 * 描述：按全局统计的已解决Bug数是指已经被开发团队解决的Bug数量。这个度量项反映了系统或项目在一定时间内解决的Bug数量。已解决Bug总数越多可能代表开发团队解决问题的速度较快，但同时也需要关注解决的质量。
 * 定义：所有Bug个数求和;状态为已解决;过滤已删除的Bug;过滤已删除的产品;
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
class count_of_resolved_bug extends baseCalc
{
    public $dataset = 'getBugs';

    public $fieldList = array('t1.status');

    public $result = 0;

    public function calculate($row)
    {
        if($row->status == 'resolved') $this->result += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}
