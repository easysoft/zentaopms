<?php
/**
 * 按产品统计的严重程度为2级的Bug数。
 * Count of severity 2 bug in product.
 *
 * 范围：product
 * 对象：bug
 * 目的：scale
 * 度量名称：按产品统计的严重程度为2级的Bug数
 * 单位：个
 * 描述：按产品统计的严重程度为2级的Bug数是指在产品开发过程中发现的、对产品功能或性能产生较大影响的Bug数量。这些Bug可能会给用户带来不便或影响产品的某些功能。统计这些Bug的数量可以帮助评估产品的稳定性和可靠性。
 * 定义：产品的Bug个数求和;严重程度为2级;过滤已删除的Bug;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_severity_2_bug_in_product extends baseCalc
{
    public $dataset = 'getBugs';

    public $fieldList = array('t1.severity', 't1.product');

    public $result = array();

    public function calculate($data)
    {
        $severity = $data->severity;
        $product  = $data->product;

        if(!isset($this->result[$product])) $this->result[$product] = 0;

        if($severity == '2') $this->result[$product] += 1;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $product => $value)
        {
            $records[] = array('product' => $product, 'value' => $value);
        }

        return $this->filterByOptions($records, $options);
    }
}
