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
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.product', 't3.case_count');

    public $result = array();

    public function calculate($row)
    {
        $product = $row->product;
        $case    = $row->case_count;

        if(!isset($this->result[$product])) $this->result[$product] = array('total' => 0, 'hasCase' => 0);

        $this->result[$product]['total']   += 1;
        $this->result[$product]['hasCase'] += $case > 0 ? 1 : 0;
    }

    public function getResult($options = array())
    {
        foreach($this->result as $product => $rate)
        {
            if(!isset($rate['total']) || !isset($rate['hasCase'])) continue;
            $total = $rate['total'];
            $hasCase = $rate['hasCase'];

            $this->result[$product] = $total ? round($hasCase / $total, 2) : 0;
        }
        $records = $this->getRecords(array('product', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
