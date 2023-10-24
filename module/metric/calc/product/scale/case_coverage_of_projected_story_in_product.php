<?php
/**
 * 按产品统计的已立项研发需求的用例覆盖率。
 * Case coverage of projected story in product.
 *
 * 范围：product
 * 对象：story
 * 目的：scale
 * 度量名称：按产品统计的已立项研发需求的用例覆盖率
 * 单位：个
 * 描述：按产品统计的已立项研发需求的用例覆盖率是指产品中已立项研发需求的用例覆盖程度。用例覆盖率可以衡量产品团队对于已立项需求的测试计划和测试用例编写的完整度。较高的用例覆盖率可能表示产品团队有较完整的测试计划。
 * 定义：复用：;按产品统计的已立项研发需求数;按产品统计的有用例的已立项研发需求数;公式：;按产品统计的已立项研发需求用例覆盖率=按产品统计的有用例的已立项研发需求数/按产品统计的已立项研发需求数;过滤已删除的研发需求;过滤已删除的产品;过滤已删除的用例;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class case_coverage_of_projected_story_in_product extends baseCalc
{
    public $idList = array();

    public $result = array();

    public function getStatement()
    {
        $caseQuery = $this->dao->select('story, count(DISTINCT id) as case_count')->from(TABLE_CASE)
            ->groupBy('story')
            ->get();

        return $this->dao->select('t1.product, COUNT(t1.id) as total, SUM(CASE WHEN t3.case_count > 0 THEN 1 ELSE 0 END) as hasCase')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->leftJoin("($caseQuery)")->alias('t3')->on('t1.id=t3.story')
            ->where('t1.stage')->eq('projected')
            ->andWhere('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.shadow')->eq(0)
            ->andWhere("NOT FIND_IN_SET('or', t1.vision)")
            ->groupBy('t1.product')
            ->query();
    }

    public function calculate($row)
    {
        $product = $row->product;
        $total   = $row->total;
        $hasCase = $row->hasCase;

        $rate = $total ? round($hasCase / $total, 2) : 0;
        $this->result[$product] = $rate;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('product', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
