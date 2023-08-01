<?php
/**
 * 按全局统计的Bug修复率。
 * Rate of fixed bug.
 *
 * 范围：global
 * 对象：bug
 * 目的：rate
 * 度量名称：按全局统计的Bug修复率
 * 单位：%
 * 描述：按全局统计的Bug修复率是指已修复的Bug占相对于有效Bug数量的比例。这个度量项反映了系统或项目在解决Bug方面的成功率。Bug修复率越高说明开发团队在解决问题上的能力越强，同时也需要关注解决的质量。
 * 定义：复用：;按全局统计的已修复Bug数;按全局统计的有效Bug数;公式：;按全局统计的Bug修复率=按全局统计的已修复Bug数/按全局统计的有效Bug数;
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
class rate_of_fixed_bug extends baseCalc
{
    public $dataset = 'getBugs';

    public $fieldList = array('t1.status', 't1.resolution');

    public $result = array('fixed' => 0, 'valid' => 0);

    public function calculate($row)
    {
        if($row->status == 'closed' and $row->resolution == 'fixed') $this->result['fixed'] += 1;
        if($row->status == 'active' or $row->resolution == 'fixed' or $row->resolution == 'postponed') $this->result['valid'] += 1;
    }

    public function getResult($options = array())
    {
        $valid = $this->result['valid'];
        $fixed = $this->result['fixed'];
        $rate  = $valid == 0 ? 0 : round($fixed / $valid, 4);

        $records = array(array('value' => $rate));
        return $this->filterByOptions($records, $options);
    }
}
