<?php
/**
 * 按全局统计的有效研发需求数。
 * Count of valid story.
 *
 * 范围：global
 * 对象：story
 * 目的：scale
 * 度量名称：按全局统计的有效研发需求数
 * 单位：个
 * 描述：按全局统计的有效研发需求数是指被确认为有效的研发需求数量。有效需求指的是符合产品策略和目标，可以实施并且对用户有价值的需求。通过对有效需求的统计，可以帮助团队评估产品需求的质量和重要性，并进行优先级排序和资源分配。较高的有效需求数量通常表示产品的功能和特性满足了用户和市场的期望，有利于实现产品的成功交付和用户满意度。
 * 定义：复用：;按全局统计的无效研发需求数;按全局统计的研发需求总数;公式：;按全局统计的有效研发需求数=按全局统计的研发需求总数-按全局统计的无效研发需求数;
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
class count_of_valid_story extends baseCalc
{
    public $result = 0;

    public function getStatement()
    {
        return $this->dao->select('count(t1.id) as value')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.shadow')->eq(0)
            ->andWhere('t1.type')->eq('story')
            ->andWhere('t1.closedReason')->notin('duplicate,willnotdo,bydesign,cancel')
            ->query();
    }

    public function calculate($row)
    {
        $this->result = $row->value;
    }

    public function getResult($options = array())
    {
        $records = array(array('value' => $this->result));
        return $this->filterByOptions($records, $options);
    }
}
