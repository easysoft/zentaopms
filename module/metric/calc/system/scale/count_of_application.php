<?php
/**
 * 按系统统计应用总数。
 * Count of application.
 *
 * 范围：system
 * 对象：application
 * 目的：scale
 * 度量名称：按系统统计应用总数
 * 单位：无
 * 描述：按系统统计的应用总数是指在禅道DevOps平台中使用的全部应用总数。
 * 定义：所有安装的应用个数求和;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_application extends baseCalc
{
    public $result = 0;

    public function getStatement()
    {
        $count = (int)$this->dao->select('count(*) as count')->from(TABLE_INSTANCE)->where('deleted')->eq(0)->fetch('count');

        return $this->dao->select("count(*) as count, {$count} as instanceCount")->from(TABLE_PIPELINE)
            ->where('deleted')->eq(0)
            ->query();
    }

    public function calculate($row)
    {
        $this->result = $row->count + $row->instanceCount;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}
