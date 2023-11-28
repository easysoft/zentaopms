<?php
/**
 * 按系统统计的Bug修复率。
 * Rate of fixed bug.
 *
 * 范围：system
 * 对象：bug
 * 目的：rate
 * 度量名称：按系统统计的Bug修复率
 * 单位：%
 * 描述：按系统统计的Bug修复率是指已修复的Bug占相对于有效Bug数量的比例。反映了一个系统或软件中Bug修复的效率和速度，用于评估质量改进、故障管理、用户满意度、变更管理以及团队绩效评估与改进等方面。通过跟踪和分析Bug修复率，可以评估团队在修复Bug方面的效率和能力，及时发现和解决问题，提高系统的质量和可靠性。
 * 定义：复用：;按系统统计的已修复Bug数;按系统统计的有效Bug数;公式：;按系统统计的Bug修复率=按系统统计的已修复Bug数/按系统统计的有效Bug数;
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
