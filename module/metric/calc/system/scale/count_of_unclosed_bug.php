<?php
/**
 * 按系统统计的未关闭Bug数。
 * Count of unclosed bug.
 *
 * 范围：system
 * 对象：bug
 * 目的：scale
 * 度量名称：按系统统计的未关闭Bug数
 * 单位：个
 * 描述：按系统统计的未关闭Bug数是指当前仍然存在但未关闭的Bug数量。反映了组织在特定时间段内尚未解决的Bug数量，用于评估系统质量、问题管理、优先级和计划调整、资源管理以及过程改进等方面。通过跟踪和分析未关闭的Bug数，可以及时发现问题、优化问题处理流程、合理安排资源，并为团队的质量管理和持续改进提供依据。
 * 定义：复用：;按系统统计的Bug总数;按系统统计的已关闭Bug数;公式：;按系统统计的未关闭Bug数=按系统统计的Bug总数-按系统统计的已关闭Bug数;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_unclosed_bug extends baseCalc
{
    public $dataset = 'getBugs';

    public $fieldList = array('t1.status');

    public $result = 0;

    public function calculate($row)
    {
        if($row->status != 'closed') $this->result += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}
