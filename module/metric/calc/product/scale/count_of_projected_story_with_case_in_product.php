<?php
/**
 * 按产品统计的有用例的已立项研发需求数。
 * Count of projected story with case in product.
 *
 * 范围：product
 * 对象：story
 * 目的：scale
 * 度量名称：按产品统计的有用例的已立项研发需求数
 * 单位：个
 * 描述：按产品统计的有用例的已立项研发需求数是指产品中关联进项目且有用例的研发需求数量。该度量项反映了产品中对于已立项需求的测试用例编写情况。产品中较高的有用例的已立项研发需求数量可能表示需求测试用例覆盖度越高。
 * 定义：产品中研发需求个数求和;研发需求关联进项目;过滤已删除的产品;过滤已删除的研发需求;过滤没有用例的研发需求;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_projected_story_with_case_in_product extends baseCalc
{
    public $result = array();

    public function getStatement()
    {
        return $this->dao->select("COUNT(DISTINCT t1.story) as 'value', t1.product")
            ->from(TABLE_CASE)->alias('t0')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t1')->on('t1.story=t0.story')
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
