<?php
/**
 * 按产品统计的研发完毕研需规模的Bug密度。
 * Bug concentration of developed story in product.
 *
 * 范围：product
 * 对象：bug
 * 目的：scale
 * 度量名称：按产品统计的研发完毕研需规模的Bug密度
 * 单位：个
 * 描述：按产品统计的研发完毕研需规模的Bug密度表示按产品统计的有效Bug数相对于按产品统计的研发完成的研发需求规模数。该度量项反映了研发完毕的研需的质量表现，密度越低代表研发完毕的研需质量越高。
 * 定义：复用：;按产品统计的有效Bug数;按产品统计的研发完成的研发需求规模数;公式：;按产品统计的研发完成需求的Bug密度=按产品统计的有效Bug数/按产品统计的研发完成的研发需求规模数;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class bug_concentration_of_developed_story_in_product extends baseCalc
{
    public $result = array();

    public $reuse = true;

    public $reuseMetrics = array('bug' => 'count_of_effective_bug_in_product', 'estimate' => 'scale_of_developed_story_in_product');

    public $reuseRule = '{bug} / {estimate}';

    public function calculate($metrics)
    {
        $bugs      = $metrics['bug'];
        $estimates = $metrics['estimate'];
        if(empty($bugs) || empty($estimates)) return false;

        $all = array_merge($bugs, $estimates);

        $products = array_column($all, 'product', 'product');

        $bugs      = $this->generateUniqueKey($bugs);
        $estimates = $this->generateUniqueKey($estimates);

        foreach($products as $product)
        {
            $bug      = isset($bugs[$product]) ? $bugs[$product] : 0;
            $estimate = isset($estimates[$product]) ? $estimates[$product] : 0;

            if($estimate == 0) continue;
            $this->result[$product] = round($bug / $estimate, 4);
        }
    }

    public function getResult($options = null)
    {
        $records = array();
        foreach($this->result as $product => $value) $records[] = array('product' => $product, 'value' => $value);
        return $this->filterByOptions($records, $options);
    }

    public function generateUniqueKey($records)
    {
        $uniqueKeyRecords = array();
        foreach($records as $record)
        {
            $key = $record['product'];
            $uniqueKeyRecords[$key] = $record['value'];
        }

        return $uniqueKeyRecords;
    }
}
