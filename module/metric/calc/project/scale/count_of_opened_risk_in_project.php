<?php
/**
 * 按项目统计的开放的风险数。
 * Count of opened risk in project.
 *
 * 范围：project
 * 对象：risk
 * 目的：scale
 * 度量名称：按项目统计的开放的风险数
 * 单位：个
 * 描述：按项目统计的开放的风险数是指在项目管理中，正在被跟踪和管理的项目风险的数量。风险是项目中潜在的不确定事件或情况，可能对项目目标的达成产生负面影响。通过跟踪和管理项目风险，项目团队可以及时采取措施降低风险的概率和影响程度。
 * 定义：项目中风险的个数求和;状态为开放;过滤已删除的风险;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_opened_risk_in_project extends baseCalc
{
    public $dataset = 'getRisks';

    public $fieldList = array('t1.project', 't1.status');

    public $result = array();

    public function calculate($row)
    {
        $project = $row->project;
        $status  = $row->status;

        if(!isset($this->result[$project])) $this->result[$project] = 0;
        if($status == 'active') $this->result[$project] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
