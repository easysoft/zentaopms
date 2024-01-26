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
    public $dataset = 'getDevStoriesWithProject';

    public $fieldList = array('t2.id as product', 't3.story');

    public $result = array();

    public function calculate($row)
    {
        $product = $row->product;
        $story   = $row->story;

        if(empty($story)) return false;

        if(!isset($this->result[$product])) $this->result[$product] = 0;
        $this->result[$product] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('product', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
