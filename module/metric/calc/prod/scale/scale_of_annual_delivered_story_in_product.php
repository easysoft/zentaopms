<?php
/**
 * 按产品统计的年度已交付研发需求规模数。
 * .
 *
 * 范围：prod
 * 对象：story
 * 目的：scale
 * 度量名称：按产品统计的年度已交付研发需求规模数
 * 单位：sp/工时
 * 描述：产品中所处阶段为已发布且发布时间为某年某月或关闭原因为已完成且关闭时间为某年某月的研发需求规模数求和
过滤已删除的研发需求
过滤已删除的产品
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
class scale_of_annual_delivered_story_in_product extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    //public function getStatement()
    //{
    //}

    //public function calculate($data)
    //{
    //}

    //public function getResult()
    //{
    //}
}