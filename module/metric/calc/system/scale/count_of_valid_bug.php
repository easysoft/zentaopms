<?php
/**
 * 按系统统计的有效Bug数。
 * Count of valid bug.
 *
 * 范围：system
 * 对象：bug
 * 目的：scale
 * 度量名称：按系统统计的有效Bug数
 * 单位：个
 * 描述：按系统统计的有效Bug数是指系统或项目中真正具有影响和价值的Bug数量。反映了一个系统或软件中有效的Bug数量。有效Bug是指经过验证和确认的真实问题，需要进行修复和解决的Bug。用于评估系统质量、问题管理、资源管理、过程改进以及用户满意度等方面。通过跟踪和分析有效Bug数，可以及时发现问题、优化问题处理流程、合理安排资源，并为团队的质量管理和持续改进提供依据，同时提升用户满意度和系统质量。
 * 定义：所有Bug个数求和;解决方案为已解决和延期处理;或状态为激活的Bug数;过滤已删除的Bug;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_valid_bug extends baseCalc
{
    public $dataset = 'getBugs';

    public $fieldList = array('t1.status', 't1.resolution');

    public $result = 0;

    public function calculate($row)
    {
        if($row->status == 'active' or $row->resolution == 'fixed' or $row->resolution == 'postponed') $this->result += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}
