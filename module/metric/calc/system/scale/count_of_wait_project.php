<?php
/**
 * 按系统统计的未开始项目数。
 * Count of wait project.
 *
 * 范围：system
 * 对象：project
 * 目的：scale
 * 度量名称：按系统统计的未开始项目数
 * 单位：个
 * 描述：按系统统计的未开始项目数是指系统中目前未开始的项目数量。这个度量项可以帮助团队了解当前需要启动的项目数量和未来的项目规划。
 * 定义：所有的项目个数求和;状态为未开始;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_wait_project extends baseCalc
{
    public $dataset = 'getAllProjects';

    public $fieldList = array('t1.status');

    public $result = 0;

    public function calculate($row)
    {
        if($row->status == 'wait') $this->result ++;
    }

    public function getResult($options = array())
    {
        $records = array(array('value' => $this->result));
        return $this->filterByOptions($records, $options);
    }
}
