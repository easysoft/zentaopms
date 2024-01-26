<?php
/**
 * 按项目统计的人员总数。
 * Count of user in project.
 *
 * 范围：project
 * 对象：user
 * 目的：scale
 * 度量名称：按项目统计的人员总数
 * 单位：个
 * 描述：按项目统计的人员总数是指参与项目的全部人员的数量。这个度量项用于了解项目团队的规模和组成，对项目资源的分配和管理起到重要作用。
 * 定义：项目中团队成员个数求和;过滤已移除的人员;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_user_in_project extends baseCalc
{
    public $dataset = 'getTeamMembers';

    public $fieldList = array('t3.id as project', 't1.account');

    public $result = array();

    public function calculate($row)
    {
        $project = $row->project;
        $account = $row->account;

        if(!isset($this->result[$project])) $this->result[$project] = array();
        $this->result[$project][$account] = $account;
    }

    public function getResult($options = array())
    {
        foreach($this->result as $project => $users)
        {
            if(!is_array($users)) continue;
            $this->result[$project] = count($users);
        }
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
