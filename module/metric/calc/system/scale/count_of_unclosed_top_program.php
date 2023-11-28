<?php
/**
 * 按系统统计的未关闭一级项目集数。
 * Count of unclosed top program.
 *
 * 范围：system
 * 对象：program
 * 目的：scale
 * 度量名称：按系统统计的未关闭一级项目集数
 * 单位：个
 * 描述：按系统统计的未关闭一级项目集数反映了系统中不同战略目标的项目集数量及情况，用于评估组织进行中的项目集战略目标的进展和挑战。
 * 定义：复用：;按系统统计的一级项目集总数;按系统统计的已关闭一级项目集数;公式：按系统统计的未关闭一级项目集数=按系统统计的一级项目集总数-按系统统计的已关闭一级项目集数;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_unclosed_top_program extends baseCalc
{
    public $dataset = 'getTopPrograms';

    public $fieldList = array('status');

    public $result = 0;

    public function calculate($row)
    {
        if($row->status != 'closed') $this->result ++;
    }

    public function getResult($options = array())
    {
        $records = array(array('value' => $this->result));
        return $this->filterByOptions($records, $options);
    }
}
