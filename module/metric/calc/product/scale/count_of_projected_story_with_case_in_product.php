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
    public $dataset = 'getCasesWithStory';

    public $fieldList = array('t1.product', 't1.story');

    public $result = array();

    public function calculate($row)
    {
        $product = $row->product;
        $story   = $row->story;

        if(!isset($this->result[$product])) $this->result[$product] = array();
        $this->result[$product][$story] = $story;
    }

    public function getResult($options = array())
    {
        foreach($this->result as $product => $stories)
        {
            if(!is_array($stories)) continue;
            $this->result[$product] = count($stories);
        }
        $records = $this->getRecords(array('product', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
