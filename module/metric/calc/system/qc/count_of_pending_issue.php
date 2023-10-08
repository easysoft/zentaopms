<?php
/**
 * 按系统统计代码库待处理问题总数。
 * Count of pending issue.
 *
 * 范围：system
 * 对象：codebase
 * 目的：qc
 * 度量名称：按系统统计代码库待处理问题总数
 * 单位：无
 * 描述：系统统计的代码库待处理问题总数是指所有代码库中尚未解决的问题数量的统计，它反映了代码库的健康状况和存在的潜在问题数量，通过对问题总数的监控和分析，可以及时发现并解决和解决问题，提高软件开发过程的效率和质量。
 * 定义：所有代码库的未关闭代码问题个数求和;不统计删除的问题;不统计删除的代码库里的问题;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_pending_issue extends baseCalc
{
    public $dataset = 'getRepoIssues';

    public $fieldList = array('t1.resolvedBy');

    public $result = 0;

    public function calculate($row)
    {
        if(empty($row->resolvedBy)) $this->result += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}
