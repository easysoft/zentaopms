<?php
/**
 * 按产品统计的年度新增Bug数。
 * Count of annual created bug in product.
 *
 * 范围：product
 * 对象：Bug
 * 目的：scale
 * 度量名称：按产品统计的年度新增Bug数
 * 单位：个
 * 描述：产品中创建时间为某年的Bug的个数求和
 *       过滤已删除的Bug
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
class count_of_annual_created_bug_in_product extends baseCalc
{
    public $dataset = 'getBugs';

    public $fieldList = array('t1.openedDate', 't1.product');

    public $result = array();

    public function calculate($data)
    {
        $product    = $data->product;
        $openedDate = $data->openedDate;
        if(empty($openedDate)) return;

        $year = substr($openedDate, 0, 4);
        if($year == '0000') return;

        if(!isset($this->result[$product])) $this->result[$product] = array();
        if(!isset($this->result[$product][$year])) $this->result[$product][$year] = 0;
        $this->result[$product][$year] += 1;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $product => $years)
        {
            foreach($years as $year => $value)
            {
                $records[] = array('product' => $product, 'year' => $year, 'value' => $value);
            }
        }

        return $this->filterByOptions($records, $options);
    }
}
