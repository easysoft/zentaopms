<?php
/**
 * 按系统统计的有效研发需求数。
 * Count of valid story.
 *
 * 范围：system
 * 对象：story
 * 目的：scale
 * 度量名称：按系统统计的有效研发需求数
 * 单位：个
 * 描述：按系统统计的有效的产品研发需求数量反映了组织在特定时间段内有效的产品研发需求数量，用于评估组织的评估需求质量、市场适应性、研发投资回报和竞争力。
 * 定义：复用：;按系统统计的无效研发需求数;按系统统计的研发需求总数;公式：;按系统统计的有效研发需求数=按系统统计的研发需求总数-按系统统计的无效研发需求数;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_valid_story extends baseCalc
{
    public $result = 0;

    public function getStatement()
    {
        return $this->dao->select('count(t1.id) as value')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.shadow')->eq(0)
            ->andWhere('t1.type')->eq('story')
            ->andWhere('t1.closedReason')->notin('duplicate,willnotdo,bydesign,cancel')
            ->andWhere("NOT FIND_IN_SET('or', t1.vision)")
            ->query();
    }

    public function calculate($row)
    {
        $this->result = $row->value;
    }

    public function getResult($options = array())
    {
        $records = array(array('value' => $this->result));
        return $this->filterByOptions($records, $options);
    }
}
