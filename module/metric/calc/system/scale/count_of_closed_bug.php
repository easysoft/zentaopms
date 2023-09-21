<?php
/**
 * 按全局统计的已关闭Bug总数。
 * Count of closed bug.
 *
 * 范围：global
 * 对象：bug
 * 目的：scale
 * 度量名称：按全局统计的已关闭Bug总数
 * 单位：个
 * 描述：按全局统计的已关闭Bug总数是指已经被关闭的Bug数量。这个度量项反映了系统或项目中已经关闭的缺陷数量。已关闭Bug总数的增加说明项目进行了持续的改进和修复工作。
 * 定义：所有Bug个数求和;状态为已关闭;过滤已删除的Bug;过滤已删除的产品;
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
