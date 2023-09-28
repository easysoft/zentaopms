<?php
/**
 * 按产品统计的已立项研发需求数。
 * Count of projected story in product.
 *
 * 范围：product
 * 对象：story
 * 目的：scale
 * 度量名称：按产品统计的已立项研发需求数
 * 单位：个
 * 描述：按产品统计的已立项研发需求数是指产品中已关联进项目的研发需求数。该度量项表示产品中获得批准需要投入资源进行开发的需求数量。产品中较高的已立项研发需求数可能表示产品相关项目的规模越大。
 * 定义：产品中研发需求个数求和;过滤已删除的产品;过滤已删除的研发需求;研发需求被关联进项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_projected_story_in_product extends baseCalc
{
    public $result = array();

    public function getStatement()
    {
        return $this->dao->select("COUNT(DISTINCT t1.story) as 'value', t1.product")
            ->from(TABLE_PROJECTSTORY)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story=t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product=t3.id')
            ->where('t2.deleted')->eq('0')
            ->andWhere('t3.deleted')->eq('0')
            ->andWhere("NOT FIND_IN_SET('or', t2.vision)")
            ->groupBy('t1.product');
    }

    public function calculate($row)
    {
        $product = $row->product;
        $value   = $row->value;

        $this->result[$product] = $value;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('product', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
