<?php
/**
 * 按系统统计的所有层级已挂起项目集数。
 * Count of suspended program.
 *
 * 范围：system
 * 对象：program
 * 目的：scale
 * 度量名称：按系统统计的所有层级已挂起项目集数
 * 单位：个
 * 描述：按系统统计的所有层级已挂起项目集数反映了系统内因为某种原因暂时中止或搁置的项目集数量，用于评估组织项目集层面的风险和不确定性。
 * 定义：所有项目集的个数求和;状态为已挂起;过滤已删除的项目集;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_suspended_program extends baseCalc
{
    public $dataset = 'getPrograms';

    public $fieldList = array('status');

    public $result = 0;

    public function calculate($row)
    {
        if($row->status == 'suspended') $this->result += 1;
    }

    public function getResult($options = array())
    {
        $records = array(array('value' => $this->result));
        return $this->filterByOptions($records, $options);
    }
}
