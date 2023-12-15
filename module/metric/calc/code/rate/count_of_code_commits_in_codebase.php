<?php
/**
 * 按代码库统计代码提交次数。
 * Count of code commits in codebase.
 *
 * 范围：code
 * 对象：commit
 * 目的：rate
 * 度量名称：按代码库统计代码提交次数
 * 单位：个
 * 描述：按代码库统计的代码提交次数是指代码库中的全部代码提交（Commit）操作的数量。代码提交次数反映了代码库的开发活动频率和代码更新情况，可以评估开发团队的工作量和迭代速度。
 * 定义：代码库中代码提交次数相加。;不包含已删除。;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_code_commits_in_codebase extends baseCalc
{
    public $dataset = 'getRepos';

    public $fieldList = array('id', 'commits');

    public $result = array();

    public function calculate($row)
    {
        $this->result[$row->id] = $row->commits;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('code', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
