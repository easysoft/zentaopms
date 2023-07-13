<?php
/**
 * 按产品统计的研发需求规模数。
 * Scale of story in product.
 *
 * 范围：product
 * 对象：story
 * 目的：scale
 * 度量名称：按产品统计的研发需求规模数
 * 单位：sp/工时/功能点
 * 描述：产品中研发需求的规模数求和
 *       过滤已删除的研发需求
 *       过滤已删除的产品
 * 度量库：
 * 收集方式：realtime
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
    public $dataset = 'getStories';

    public $fieldList = array('t1.product', 't1.estimate');

    public $result = array();

    public function calculate($data)
    {
        $product    = $data->product;
        $estimate   = $data->estimate;

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
