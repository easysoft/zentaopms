<?php
/**
 * 按系统统计的代码库总数。
 * Count of codebase.
 *
 * 范围：system
 * 对象：code
 * 目的：scale
 * 度量名称：按系统统计的代码库总数
 * 单位：个
 * 描述：按系统统计的代码库总数是指整个研发团队中维护的所有代码库的总数量。通过统计代码库总数可以了解团队的代码库规模和复杂性。
 * 定义：所有代码库的个数求和，不统计已删除;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_codebase extends baseCalc
{
    public $dataset = 'getRepos';

    public $fieldList = array('id');

    public $result = 0;

    public function calculate($row)
    {
        $this->result += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}
