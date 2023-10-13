<?php
/**
 * 按产品统计的有效研发需求数。
 * Count of valid story in product.
 *
 * 范围：product
 * 对象：story
 * 目的：scale
 * 度量名称：按产品统计的有效研发需求数
 * 单位：个
 * 描述：按产品统计的有效研发需求数是指在在产品中被确认为有效的研发需求数量。有效需求指的是符合产品策略和目标，可以实施并且对用户有价值的需求。较高的有效研发需求数通常表示产品的功能和特性满足了用户和市场的期望，有利于实现产品的成功交付和用户满意度。
 * 定义：复用：;按产品统计的研发需求总数;按产品统计的无效研发需求数;公式：;按产品统计的有效研发需求数=按产品统计的研发需求总数-按产品统计的无效研发需求数;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_valid_story_in_product extends baseCalc
{
    public $result = array();

    public function getStatement()
    {
        return $this->dao->select('t1.product,t1.id')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.shadow')->eq(0)
            ->andWhere('t1.type')->eq('story')
            ->andWhere('t1.closedReason')->notin('duplicate,willnotdo,bydesign,cancel')
            ->andWhere("NOT FIND_IN_SET('or', t1.vision)")
            ->andWhere("NOT FIND_IN_SET('or', t2.vision)")
            ->query();
    }

    public function calculate($row)
    {
        if(!isset($this->result[$row->product])) $this->result[$row->product] = 0;
        $this->result[$row->product] += 1;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $product => $value) $records[] = array('product' => $product, 'value' => $value);
        return $this->filterByOptions($records, $options);
    }
}
