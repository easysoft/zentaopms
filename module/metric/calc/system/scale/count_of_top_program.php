<?php
/**
 * 按系统统计的一级项目集总数。
 * Count of top program.
 *
 * 范围：system
 * 对象：program
 * 目的：scale
 * 度量名称：按系统统计的一级项目集总数
 * 单位：个
 * 描述：按系统统计的一级项目集总数反映了组织中不同战略目标的项目集数量及情况，用于评估组织的战略取向、优先事项、资源分配以及管理能力等关键方面，是组织实现长期成功的重要手段和路径。
 * 定义：所有一级项目集的个数求和;过滤已删除的项目集;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_top_program extends baseCalc
{
    public $dataset = 'getPrograms';

    public $fieldList = array('t1.id', 't1.grade');

    public $result = 0;

    public function calculate($data)
    {
        if($data->grade == 1) $this->result += 1;
    }

    public function getResult($options = array())
    {
        $records = array(array('value' => $this->result));
        return $this->filterByOptions($records, $options);
    }
}
