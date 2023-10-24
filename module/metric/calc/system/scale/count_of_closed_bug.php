<?php
/**
 * 按系统统计的已关闭Bug总数。
 * Count of closed bug.
 *
 * 范围：system
 * 对象：bug
 * 目的：scale
 * 度量名称：按系统统计的已关闭Bug总数
 * 单位：个
 * 描述：按系统统计的已关闭Bug总数是指已经被关闭的Bug数量。反映了组织特定时间段内已关闭的Bug数量，用于评估系统质量、进度管理、资源管理、过程改进和绩效评估等方面。通过跟踪和分析已关闭的Bug总数，可以及时发现问题、改进开发过程、提高项目进度，并为团队绩效评估和优化提供依据。
 * 定义：所有Bug个数求和;状态为已关闭;过滤已删除的Bug;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_closed_bug extends baseCalc
{
    public $dataset = 'getBugs';

    public $fieldList = array('t1.status');

    public $result = 0;

    public function calculate($row)
    {
        if($row->status == 'closed') $this->result += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}
