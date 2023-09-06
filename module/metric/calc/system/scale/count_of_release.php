<?php
/**
 * 按全局统计的发布总数。
 * Count of release.
 *
 * 范围：global
 * 对象：release
 * 目的：scale
 * 度量名称：按全局统计的发布总数
 * 单位：个
 * 描述：按全局统计的发布总数表示整个组织的总发布数量。该度量项反映了组织总共发布的产品或服务的数量，可以用于评估组织的产品发布活动和战略。
 * 定义：所有的发布个数求和;过滤已删除的发布;
 * 度量库：
 * 收集方式：realtime
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
