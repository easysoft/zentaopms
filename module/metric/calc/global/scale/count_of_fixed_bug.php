<?php
/**
 * 按全局统计的已修复Bug数。
 * Count of fixed bug.
 *
 * 范围：global
 * 对象：bug
 * 目的：scale
 * 度量名称：按全局统计的已修复Bug数
 * 单位：个
 * 描述：按全局统计的已修复Bug数是指解决并关闭的Bug数量。这个度量项反映了系统或项目解决的问题数量。已修复Bug数的增加说明开发团队在Bug解决方面的工作效率较高。
 * 定义：所有Bug个数求和;状态为已关闭;解决方案为已解决;过滤已删除的Bug;过滤已删除的产品;
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
class count_of_fixed_bug extends baseCalc
{
    public $dataset = 'getBugs';

    public $fieldList = array('t1.status', 't1.resolution');

    public $result = 0;

    public function calculate($row)
    {
        if($row->status == 'closed' and $row->resolution == 'fixed') $this->result += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}
