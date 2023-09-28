<?php
/**
 * 按系统统计的年度添加用户数。
 * Count of annual created user.
 *
 * 范围：system
 * 对象：user
 * 目的：scale
 * 度量名称：按系统统计的年度添加用户数
 * 单位：个
 * 描述：按系统统计的年度新增人员数是指在一年内新增加到项目或系统中的人员数量。反映了系统或平台在一年内新增用户数量的指标，用于评估团队扩充和人员流动情况。年度新增人员数的增加可能意味着团队的增加或项目的扩大。
 * 定义：系统所有用户个数求和;添加时间为某年;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_created_user extends baseCalc
{
    public $dataset = 'getUsers';

    public $fieldList = array('t1.join');

    public $result = array();

    public function calculate($row)
    {
        $join = $row->join;
        if(empty($join)) return false;

        $year = substr($join, 0, 4);
        if($year == '0000') return false;

        if(!isset($this->result[$year])) $this->result[$year] = 0;
        $this->result[$year] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('year', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
