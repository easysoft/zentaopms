<?php
/**
 * 按产品统计的有效Bug数。
 * .
 *
 * 范围：prod
 * 对象：bug
 * 目的：scale
 * 度量名称：按产品统计的有效Bug数
 * 单位：个
 * 描述：有效Bug数=方案为已解决的Bug数+方案为延期处理的Bug数+激活的Bug数
过滤已删除的Bug
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
class count_of_effective_bug_in_product extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    //public function getStatement($dao)
    //{
    //}

    //public function calculate($data)
    //{
    //}

    //public function getResult()
    //{
    //}
}