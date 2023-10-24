<?php
/**
 * 按系统统计的激活Bug数。
 * Count of activated bug.
 *
 * 范围：system
 * 对象：bug
 * 目的：scale
 * 度量名称：按系统统计的激活Bug数
 * 单位：个
 * 描述：按系统统计的激活Bug数是指当前尚未解决的Bug数量。这个度量项反映了系统或项目当前存在的待解决问题数量。激活Bug总数越多可能代表系统或项目的稳定性较低，需要加强Bug解决的速度和质量。
 * 定义：所有Bug个数求和;状态为激活;过滤已删除的Bug;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_activated_bug extends baseCalc
{
    public $dataset = 'getBugs';

    public $fieldList = array('t1.status');

    public $result = 0;

    public function calculate($row)
    {
        if($row->status == 'active') $this->result += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}
