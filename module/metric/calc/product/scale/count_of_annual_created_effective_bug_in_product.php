<?php
/**
 * 按产品统计的年度新增有效Bug数。
 * Count of annual created effective bug in product.
 *
 * 范围：product
 * 对象：bug
 * 目的：scale
 * 度量名称：按产品统计的年度新增有效Bug数
 * 单位：个
 * 描述：按产品统计的年度新增有效Bug数是指产品在某年度新发现的真正具有影响和价值的Bug数量。有效Bug通常是指导致产品不正常运行或影响用户体验的Bug。统计有效Bug数可以帮助评估产品的稳定性和质量也可以评估测试人员之前的协作或对产品的了解程度。
 * 定义：产品中Bug个数求和;创建时间为某年;解决方案为已解决和延期处理或者状态为激活;过滤已删除的Bug;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_created_effective_bug_in_product extends baseCalc
{
    public $dataset = 'getBugs';

    public $fieldList = array('t1.product', 't1.status', 't1.resolution', 't1.openedDate');

    public $result = array();

    public function calculate($data)
    {
        $openedDate = $data->openedDate;

        if(empty($openedDate)) return false;
        $year = substr($openedDate, 0 ,4);
        if($year == '0000') return false;

        $product = $data->product;
        if(!isset($this->result[$product])) $this->result[$product] = array();
        if(!isset($this->result[$product][$year])) $this->result[$product][$year] = 0;

        $resolution = $data->resolution;
        $status     = $data->status;

        if($status == 'active' or $resolution == 'fixed' or $resolution == 'postponed') $this->result[$product][$year] += 1;
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
