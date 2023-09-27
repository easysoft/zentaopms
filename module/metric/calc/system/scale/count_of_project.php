<?php
/**
 * 按系统统计的项目总数。
 * Count of project.
 *
 * 范围：system
 * 对象：project
 * 目的：scale
 * 度量名称：按系统统计的项目总数
 * 单位：个
 * 描述：按系统统计的项目总数是指目前系统内的总项目数量。这个度量项可以帮助团队了解当前的项目规模和工作量，并作为项目管理的基础数据之一。
 * 定义：所有的项目个数求和;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_project extends baseCalc
{
    public $dataset = 'getAllProjects';

    public $fieldList = array('t1.id');

    public $result = 0;

    public function calculate($row)
    {
        $this->result += 1;
    }

    public function getResult($options = array())
    {
        $records = array(array('value' => $this->result));
        return $this->filterByOptions($records, $options);
    }
}
