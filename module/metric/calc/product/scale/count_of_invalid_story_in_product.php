<?php
/**
 * 按产品统计的无效研发需求数。
 * .
 *
 * 范围：product
 * 对象：story
 * 目的：scale
 * 度量名称：按产品统计的无效研发需求数
 * 单位：个
 * 描述：产品中关闭原因为重复、不做、设计如此和已取消的研发需求个数求和 过滤已删除的研发需求 过滤已删除的产品
 * 度量库：
 * 收集方式：realtime
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_invalid_story_in_product extends baseCalc
{
    public $result = array();

    public function getStatement()
    {
        return $this->dao->select('t1.product,count(t1.id) as value')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.type')->eq('story')
            ->andWhere('t2.shadow')->eq(0)
            ->andWhere('t1.closedReason')->in('duplicate,willnotdo,bydesign,cancel')
            ->groupBy('t1.product')
            ->query();
    }

    public function calculate($row)
    {
        $this->result[] = $row;
    }

    public function getResult($options = array())
    {
        return $this->filterByOptions($this->result, $options);
    }
}
