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
    public $dataset = 'getDevStoriesWithReview';

    public $fieldList = array('t2.product', 't1.result');

    public $result = array();

    public function calculate($row)
    {
        $result = $row->result;
        $product = $row->product;

        if(!isset($this->result[$product])) $this->result[$product] = array('total' => 0, 'pass' => 0);

        $this->result[$product]['total'] += 1;
        if($result == 'pass') $this->result[$product]['pass'] += 1;
    }

    public function getResult($options = array())
    {
        foreach($this->result as $product => $rate)
        {
            if(!isset($rate['total']) || !isset($rate['pass'])) continue;
            $pass  = $rate['pass'];
            $total = $rate['total'];

            $this->result[$product] = $total == 0 ? 0 : round($pass / $total, 4);
        }
        $records = $this->getRecords(array('product', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
