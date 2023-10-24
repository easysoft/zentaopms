<?php
/**
 * 按产品统计的有效Bug数。
 * Count of effective bug in product.
 *
 * 范围：product
 * 对象：bug
 * 目的：scale
 * 度量名称：按产品统计的有效Bug数
 * 单位：个
 * 描述：按产品统计的有效Bug数是指产品中真正具有影响和价值的Bug数量。有效Bug通常是指导致产品不正常运行或影响用户体验的Bug。统计有效Bug数可以帮助评估产品的稳定性和质量，也可以评估测试人员之间的协作或对产品的了解程度。
 * 定义：产品中所有Bug个数求和;解决方案为已解决、延期处理或状态为激活;过滤已删除的Bug;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_effective_bug_in_product extends baseCalc
{
    public $dataset = 'getBugs';

    public $fieldList = array('t1.product', 't1.status', 't1.resolution');

    public $result = array();

    public function calculate($data)
    {
        $product = $data->product;
        if(!isset($this->result[$product])) $this->result[$product] = 0;

        $resolution = $data->resolution;
        $status     = $data->status;

        if($status == 'active' or $resolution == 'fixed' or $resolution == 'postponed') $this->result[$product] += 1;
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
