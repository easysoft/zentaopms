<?php
/**
 * 按产品统计的研发需求评审通过率。
 * Rate of approved story in product.
 *
 * 范围：product
 * 对象：story
 * 目的：qc
 * 度量名称：按产品统计的研发需求评审通过率
 * 单位：%
 * 描述：按产品统计的研发需求评审通过率表示产品中通过评审的研发需求（不需要评审研发需求的与需要评审并通过的研发需求）相对于评审过的研发需求（不需要评审的研发需求与有评审结果的研发需求数）的比例。该度量项反映了需求评审过程中的成功率。
 * 定义：按产品统计的所有研发需求评审通过率=（按产品统计的不需要评审的研发需求数+评审结果确认通过的研发需求数）/（按产品统计的不需要评审的研发需求数+有评审结果的研发需求数）;过滤已删除的研发需求;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class rate_of_approved_story_in_product extends baseCalc
{
    public $result = array();

    public function getStatement()
    {
        return $this->dao->select("COUNT(t1.result) as total, SUM(IF(result='pass', 1, 0)) as pass, t2.product")
            ->from(TABLE_STORYREVIEW)->alias('t1')
            ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story=t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t2.product=t3.id')
            ->where('t2.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0)
            ->andWhere('t2.type')->eq('story')
            ->andWhere('t3.shadow')->eq(0)
            ->andWhere("NOT FIND_IN_SET('or', t2.vision)")
            ->groupBy('t2.product')
            ->query();
    }

    public function calculate($row)
    {
        $total   = $row->total;
        $pass    = $row->pass;
        $product = $row->product;

        $this->result[$product] = $total == 0 ? 0 : round($pass / $total, 4);
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('product', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
