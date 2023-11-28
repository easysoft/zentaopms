<?php
/**
 * 按产品统计的研发需求规模总数。
 * Scale of story in product.
 *
 * 范围：product
 * 对象：story
 * 目的：scale
 * 度量名称：按产品统计的研发需求规模总数
 * 单位：工时
 * 描述：按产品统计的研发需求规模总数表示产品种所有研发需求的总规模。这个度量项可以反映团队需进行研发工作的规模，可以用于评估产品团队的研发需求规模管理和成果。
 * 定义：产品中研发需求的规模数求和;过滤父研发需求;过滤已删除的研发需求;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class scale_of_story_in_product extends baseCalc
{
    public $dataset = 'getDevStories';

    public $fieldList = array('t1.product', 't1.estimate', 't1.parent');

    public $result = array();

    public function calculate($data)
    {
        $product    = $data->product;
        $estimate   = $data->estimate;
        $parent     = $data->parent;

        if($parent == '-1') return false;

        if(!isset($this->result[$product])) $this->result[$product] = 0;

        $this->result[$product] += $estimate;
    }

    public function getResult($options = null)
    {
        $records = array();
        foreach($this->result as $product => $value)
        {
            $records[] = array('product' => $product, 'value' => $value);
        }

        return $this->filterByOptions($records, $options);
    }
}
