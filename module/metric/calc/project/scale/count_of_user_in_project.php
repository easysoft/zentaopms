<?php
/**
 * 按项目统计的人员总数。
 * Count of user in project.
 *
 * 范围：project
 * 对象：user
 * 目的：scale
 * 度量名称：按项目统计的人员总数
 * 单位：个
 * 描述：按项目统计的人员总数是指参与项目的全部人员的数量。这个度量项用于了解项目团队的规模和组成，对项目资源的分配和管理起到重要作用。
 * 定义：项目中团队成员个数求和;过滤已移除的人员;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_user_in_project extends baseCalc
{
    public $result = array();

    public function getStatement()
    {
        return $this->dao->select('t3.id as project, COUNT(DISTINCT account) as value')
            ->from(TABLE_TEAM)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t2.id=t1.root')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t3.id=t2.project')
            ->where('t1.type')->eq('execution')
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t3.deleted')->eq(0)
            ->andWhere("NOT FIND_IN_SET('or', t3.vision)")
            ->andWhere("NOT FIND_IN_SET('lite', t3.vision)")
            ->groupBy('t3.id')
            ->query();
    }

    public function calculate($row)
    {
        $project = $row->project;
        $value   = $row->value;
        $this->result[$project] = $value;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
