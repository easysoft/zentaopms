<?php
/**
 * 按产品统计的Bug修复率。
 * Rate of fixed bug in product.
 *
 * 范围：product
 * 对象：bug
 * 目的：rate
 * 度量名称：按产品统计的Bug修复率
 * 单位：%
 * 描述：按产品统计的Bug修复率是指按产品统计的修复Bug数相对于按产品统计的有效Bug数的比例。该度量项可以帮助我们了解开发团队对Bug修复的效率和质量，高的修复率可能说明Bug得到及时解决，产品质量得到有效保障。
 * 定义：复用：;按产品统计的修复Bug数;按产品统计的有效Bug数;公式：;按产品统计的Bug修复率=按产品统计的修复Bug数/按产品统计的有效Bug数;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class rate_of_fixed_bug_in_product extends baseCalc
{
    public $dataset = 'getBugs';

    public $fieldList = array('t1.product', 't1.resolution', 't1.status');

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->product])) $this->result[$row->product] = array('fixed' => 0, 'valid' => 0);

        if($row->resolution == 'fixed' and $row->status == 'closed') $this->result[$row->product]['fixed'] ++;
        if($row->resolution == 'fixed' or $row->resolution == 'postponed' or $row->status == 'active') $this->result[$row->product]['valid'] ++;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $productID => $bugInfo)
        {
            $records[] = array(
                'product' => $productID,
                'value'   => $bugInfo['valid'] ? round($bugInfo['fixed'] / $bugInfo['valid'], 4) : 0,
            );
        }
        return $this->filterByOptions($records, $options);
    }
}
