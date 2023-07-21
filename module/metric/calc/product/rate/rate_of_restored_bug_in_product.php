<?php
/**
 * 按产品统计的Bug修复率。
 * rate_of_restored_bug_in_product.
 *
 * 范围：product
 * 对象：Bug
 * 目的：rate
 * 度量名称：按产品统计的Bug修复率
 * 单位：%
 * 描述：产品中修复的Bug数相对于产品有效Bug数的比例
 * 度量库：
 * 收集方式：realtime
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class rate_of_restored_bug_in_product extends baseCalc
{
    public $dataset = 'getBugs';

    public $fieldList = array('t1.product', 't1.resolution', 't1.status');

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
