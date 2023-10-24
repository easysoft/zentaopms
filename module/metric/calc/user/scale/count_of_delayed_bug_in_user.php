<?php
/**
 * 按人员统计的延期Bug数。
 * Count of delayed bug in user.
 *
 * 范围：user
 * 对象：bug
 * 目的：scale
 * 度量名称：按人员统计的延期Bug数
 * 单位：个
 * 描述：按人员统计的延期Bug数是指每个人延期修复Bug总量。该度量项可以帮助我们了解每个人对已解决的Bug进行确认与关闭的速度和效率。
 * 定义：截止当前时间;统计每个人延期Bug数的求和;过滤已删除的Bug;过滤已删除的产品;
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
class count_of_delayed_bug_in_user extends baseCalc
{
    public $result = array();

    public function getStatement()
    {
        $longlife = $this->dao->select('value')
            ->from(TABLE_CONFIG)
            ->where('module')->eq('bug')
            ->andWhere('key')->eq('longlife')
            ->fetch('value');

        if(!$longlife) $longlife = 7;

        return $this->dao->select('t1.assignedTo as user, count(t1.id) as value')->from(TABLE_BUG)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t2.shadow')->eq(0)
            ->andWhere('t1.status')->eq('active')
            ->andWhere('t1.assignedDate')->notZeroDatetime()
            ->andWhere('DATEDIFF(CURDATE(), assignedDate)')->gt($longlife)
            ->groupBy('t1.assignedTo')
            ->query();
    }

    public function calculate($row)
    {
        $user  = $row->user;
        $value = $row->value;

        $this->result[$user] = $value;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('user', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
