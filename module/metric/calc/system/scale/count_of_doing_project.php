<?php
/**
 * 按系统统计的进行中项目数。
 * Count of doing project.
 *
 * 范围：system
 * 对象：project
 * 目的：scale
 * 度量名称：按系统统计的进行中项目数
 * 单位：个
 * 描述：按系统统计的进行中项目数是指系统中目前正在进行中的项目数量。这个度量项可以帮助团队了解当前正在进行的工作量和资源分配情况，以及项目的执行进度和效率。
 * 定义：所有的项目个数求和;状态为进行中;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_doing_project extends baseCalc
{
    public $dataset = 'getAllProjects';

    public $fieldList = array('t1.status');

    public $result = 0;

    public function calculate($row)
    {
        if($row->status == 'doing') $this->result ++;
    }

    public function getResult($options = array())
    {
        $records = array(array('value' => $this->result));
        return $this->filterByOptions($records, $options);
    }
}
