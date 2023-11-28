<?php
/**
 * 按系统统计的已解决Bug数。
 * Count of resolved bug.
 *
 * 范围：system
 * 对象：bug
 * 目的：scale
 * 度量名称：按系统统计的已解决Bug数
 * 单位：个
 * 描述：按系统统计的已解决Bug数是指已经被开发团队解决的Bug数量。反映了组织在特定时间段内已解决的Bug数量，用于评估系统质量、用户满意度、资源管理、过程改进和绩效评估等方面。通过跟踪和分析已解决的Bug数，可以及时发现问题、改进开发过程、提高用户满意度，并为团队绩效评估和优化提供依据。
 * 定义：所有Bug个数求和;状态为已解决;过滤已删除的Bug;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_resolved_bug extends baseCalc
{
    public $dataset = 'getBugs';

    public $fieldList = array('t1.status');

    public $result = 0;

    public function calculate($row)
    {
        if($row->status == 'resolved') $this->result += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}
