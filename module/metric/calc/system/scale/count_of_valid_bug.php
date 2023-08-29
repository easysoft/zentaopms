<?php
/**
 * 按全局统计的有效Bug数。
 * Count of valid bug.
 *
 * 范围：global
 * 对象：bug
 * 目的：scale
 * 度量名称：按全局统计的有效Bug数
 * 单位：个
 * 描述：按全局统计的有效Bug数是指系统或项目中真正具有影响和价值的Bug数量。有效Bug通常是指导致系统不正常运行或影响用户体验的Bug。统计有效Bug数可以帮助评估系统或项目的稳定性和质量也可以评估测试人员之前的协作或对产品的了解程度。
 * 定义：所有Bug个数求和;解决方案为已解决和延期处理;或状态为激活的Bug数;过滤已删除的Bug;过滤已删除的产品;
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
