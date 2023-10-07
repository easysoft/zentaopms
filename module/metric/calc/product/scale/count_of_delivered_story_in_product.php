<?php
/**
 * 按产品统计的已交付研发需求数。
 * Count of delivered story in product.
 *
 * 范围：product
 * 对象：story
 * 目的：scale
 * 度量名称：按产品统计的已交付研发需求数
 * 单位：个
 * 描述：按产品统计的已交付研发需求数表示已交付给用户的研发需求的数量。该度量项反映了产品中已发布或关闭原因为已完成的研发需求的数量，可以用于评估产品的研发需求交付能力。
 * 定义：产品中研发需求个数求和;所处阶段为已发布或关闭原因为已完成;过滤已删除的研发需求;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_delivered_story_in_product extends baseCalc
{
    public $result = array();

    public function getStatement()
    {
        return $this->dao->select('t1.product,count(t1.id) as value')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.shadow')->eq(0)
            ->andWhere("NOT FIND_IN_SET('or', t1.vision)")
            ->andWhere('t1.stage', true)->eq('released')
            ->orWhere('t1.closedReason')->eq('done')
            ->markRight(1)
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
