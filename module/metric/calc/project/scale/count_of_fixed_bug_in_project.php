<?php
/**
 * 按项目统计的已修复Bug数。
 * Count of fixed bug in project.
 *
 * 范围：project
 * 对象：bug
 * 目的：scale
 * 度量名称：按项目统计的已修复Bug数
 * 单位：个
 * 描述：按项目统计的已修复Bug数是指解决方案为已解决并且状态为已关闭的Bug数量。这个度量项反映了项目解决的问题数量。已修复Bug数的可以评估开发团队在Bug解决方面的工作效率。
 * 定义：项目中Bug的个数求和;解决方案为已解决;状态为已关闭;过滤已删除的Bug;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    songchenxuan <songchenxuan@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_fixed_bug_in_project extends baseCalc
{
    public $dataset = 'getProjectBugs';

    public $fieldList = array('t1.project', 't1.status', 't1.resolution');

    public $result = array();

    public function calculate($data)
    {
        $project = $data->project;
        if(!isset($this->result[$project])) $this->result[$project] = 0;

        if($data->status == 'closed' and $data->resolution == 'fixed') $this->result[$project] += 1;
    }

    public function getResult($options = null)
    {
        $records = array();
        foreach($this->result as $project => $value)
        {
            $records[] = array('project' => $project, 'value' => $value);
        }

        return $this->filterByOptions($records, $options);
    }
}
