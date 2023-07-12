<?php
/**
 * 按产品统计的研发需求完成率。
 * .
 *
 * 范围：product
 * 对象：story
 * 目的：rate
 * 度量名称：按产品统计的研发需求完成率
 * 单位：%
 * 描述：复用：
按产品统计的已完成研发需求数
按产品统计的无效研发需求数
按产品统计的研发需求总数
公式：
按产品统计的研发需求完成率=按产品统计的已完成研发需求数/（按产品统计的研发需求总数-按产品统计的无效研发需求数）*100%
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
class rate_of_finish_story_in_product extends baseMetric
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