<?php
/**
 * 按系统统计的里程碑发布总数。
 * Count of marker release.
 *
 * 范围：system
 * 对象：release
 * 目的：scale
 * 度量名称：按系统统计的里程碑发布总数
 * 单位：个
 * 描述：按系统统计的产品里程碑发布数量反映了组织在特定时间段内达到的产品开发里程碑数量，用于评估组织的产品开发进展情况和重要的产品节点。
 * 定义：所有的里程碑发布个数求和;过滤已删除的发布;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_marker_release extends baseCalc
{
    public $dataset = 'getReleases';

    public $fieldList = array('t1.marker');

    public $result = 0;

    public function calculate($data)
    {
        if($data->marker == 1) $this->result += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}
