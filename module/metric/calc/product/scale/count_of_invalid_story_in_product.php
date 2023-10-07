<?php
/**
 * 按产品统计的无效研发需求数。
 * Count of invalid story in product.
 *
 * 范围：product
 * 对象：story
 * 目的：scale
 * 度量名称：按产品统计的无效研发需求数
 * 单位：个
 * 描述：按产品统计的无效研发需求数是指产品中被判定为无效的研发需求的数量。这个度量项可以反映产品团队进行需求管理的有效性和能力。无效研发需求数越多，可能说明产品团队在需求管理中的团队协作能力较弱或对产品理解有偏差等。
 * 定义：产品中研发需求个数求和;关闭原因为重复、不做、设计如此和已取消;过滤已删除的研发需求;过滤已删除的产品;
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
            ->andWhere("NOT FIND_IN_SET('or', t1.vision)")
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
