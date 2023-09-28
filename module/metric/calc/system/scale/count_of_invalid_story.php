<?php
/**
 * 按系统统计的无效研发需求数。
 * Count of invalid story.
 *
 * 范围：system
 * 对象：story
 * 目的：scale
 * 度量名称：按系统统计的无效研发需求数
 * 单位：个
 * 描述：按系统统计的无效的产品研发需求数量反映了组织在特定时间段内无效或被废弃的产品研发需求数量，用于评估组织的帮助组织评估需求管理效果、资源利用效率和需求准确性，提供学习和改进的机会。
 * 定义：所有的研发需求个数求和;关闭原因为重复、不做、设计如此和已取消;过滤已删除的研发需求;过滤已删除的产品;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_invalid_story extends baseCalc
{
    public $result = 0;

    public function getStatement()
    {
        return $this->dao->select('count(t1.id) as value')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t1.type')->eq('story')
            ->andWhere('t2.shadow')->eq(0)
            ->andWhere('t1.closedReason')->in('duplicate,willnotdo,bydesign')
            ->andWhere("NOT FIND_IN_SET('or', t1.vision)")
            ->query();
    }

    public function calculate($data)
    {
        $this->result = $data->value;
    }

    public function getResult($options = array())
    {
        $records = array(array('value' => $this->result));
        return $this->filterByOptions($records, $options);
    }
}
