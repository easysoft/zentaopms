<?php
/**
 * 按全局统计的已完成研发需求规模数。
 * Scale of finished story.
 *
 * 范围：global
 * 对象：story
 * 目的：scale
 * 度量名称：按全局统计的已完成研发需求规模数
 * 单位：个
 * 描述：按全局统计的已完成研发需求规模数表示已经完成的研发需求的规模总数。该度量项反映了组织已经完成的研发需求的规模总数，可以用于评估组织的研发需求规模管理和成果。
 * 定义：所有的研发需求规模数求和;关闭原因为已完成;过滤已删除的研发需求;
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
class scale_of_finished_story extends baseCalc
{
    public $dataset = 'getAllDevStories';

    public $fieldList = array('t1.estimate', 't1.closedReason');

    public $result = 0;

    public function calculate($row)
    {
        if(empty($row->estimate)) return null;

        if($row->closedReason == 'done') $this->result += $row->estimate;
    }

    public function getResult($options = array())
    {
        return $this->filterByOptions($this->result, $options);
    }
}
