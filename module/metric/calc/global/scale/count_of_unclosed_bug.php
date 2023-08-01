<?php
/**
 * 按全局统计的未关闭Bug数。
 * Count of unclosed bug.
 *
 * 范围：global
 * 对象：bug
 * 目的：scale
 * 度量名称：按全局统计的未关闭Bug数
 * 单位：个
 * 描述：按全局统计的未关闭Bug数是指当前仍然存在但未关闭的Bug数量。这个度量项反映了系统或项目中仍然存在的待处理问题数量。较高的未关闭Bug数可能需要关注Bug解决的效率和质量。
 * 定义：复用：;按全局统计的Bug总数;按全局统计的已关闭Bug数;公式：;按全局统计的未关闭Bug数=按全局统计的Bug总数-按全局统计的已关闭Bug数;
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
