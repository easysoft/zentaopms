<?php
/**
 * 按产品统计的研发需求评审通过率。
 * rate_of_approved_story_in_product.
 *
 * 范围：product
 * 对象：story
 * 目的：qc
 * 度量名称：按产品统计的研发需求评审通过率
 * 单位：%
 * 描述：产品中不需要评审的与评审通过的研发需求数相对于不需要评审的与有评审结果的需求数的比例
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
class rate_of_approved_story_in_product extends baseCalc
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