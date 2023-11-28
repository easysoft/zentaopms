<?php
/**
 * 按系统统计的发布总数。
 * Count of release.
 *
 * 范围：system
 * 对象：release
 * 目的：scale
 * 度量名称：按系统统计的发布总数
 * 单位：个
 * 描述：按系统统计的产品发布数量反映了组织在特定时间段内发布的产品版本数量，用于评估组织的产品开发效率、市场适应能力和产品组合优化，并提供绩效评估和学习机会。
 * 定义：所有的发布个数求和;过滤已删除的发布;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_release extends baseCalc
{
    public $dataset = 'getReleases';

    public $fieldList = array('t1.id');

    public $result = 0;

    public function calculate($data)
    {
        $this->result += 1;
    }

    public function getResult($options = array())
    {
        $records = array(array('value' => $this->result));
        return $this->filterByOptions($records, $options);
    }
}
