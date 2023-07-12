<?php
/**
 * 按产品统计的严重程度为1、2级的Bug数。
 * .
 *
 * 范围：prod
 * 对象：Bug
 * 目的：scale
 * 度量名称：按产品统计的严重程度为1、2级的Bug数
 * 单位：个
 * 描述：复用：
按产品统计的严重程度为1级的Bug数
按产品统计的严重程度为2级的Bug数
公式：
按产品统计的严重程度为1、2级的Bug数=按产品统计的严重程度为1级的Bug数+按产品统计的严重程度为2级的Bug数
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
class count_of_severe_bug_in_product extends baseCalc
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