<?php
/**
 * 按全局统计的研发需求交付率。
 * rate_of_delivered_story.
 *
 * 范围：global
 * 对象：story
 * 目的：rate
 * 度量名称：按全局统计的研发需求交付率
 * 单位：%
 * 描述：按全局统计的研发需求交付率表示按全局统计的已交付的研发需求数相对于按全局统计的有效研发需求数。这个度量项衡量了研发团队按时交付需求的能力。交付率越高，代表研发团队能够按时将需求交付给其他团队，实现产品的正常发布。
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
class rate_of_delivered_story extends baseCalc
{
    public $dataset = null;

    public $fieldList = array();

    //public funtion getStatement($dao)
    //{
    //}

    public function calculate($data)
    {
    }

    public function getResult($options = array())
    {
        return $this->filterByOptions($this->result, $options);
    }
}