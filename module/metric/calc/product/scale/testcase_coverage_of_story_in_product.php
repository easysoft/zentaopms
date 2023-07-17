<?php
/**
 * 按产品统计的已立项研发需求的用例覆盖率
 *
 * 范围：product
 * 对象：story
 * 目的：scale
 * 度量名称：按产品统计的已立项研发需求的用例覆盖率
 * 单位：个
 * 描述：公式： 按产品统计的已立项研发需求的用例覆盖率=按产品统计的有用例的已立项研发需求数/按产品统计的已立项的研发需求数 过滤已删除的研发需求 过滤已删除的产品
 * 度量库：
 * 收集方式：realtime
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class testcase_coverage_of_story_in_product extends baseCalc
{
    public $result = array();

    public $idList = array();

    public function getStatement()
    {
       return $this->dao->select('t1.id,t1.product,t3.id as caseID')->from(TABLE_STORY)->alias('t1')
          ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product=t2.id')
          ->leftJoin(TABLE_CASE)->alias('t3')->on('t1.id=t3.story')
          ->where('t1.stage')->eq('projected')
          ->andWhere('t1.deleted')->eq(0)
          ->andWhere('t2.deleted')->eq(0)
          ->query();
    }

    public function calculate($row)
    {
       if(in_array($row->id, $this->idList)) return;

       if(!isset($this->result[$row->product])) $this->result[$row->product] = array('total' => 0, 'haveCase' => 0);

       $this->result[$row->product]['total'] ++;
       if($row->caseID !== null) $this->result[$row->product]['haveCase'] ++;

       $this->idList[] = $row->id;
    }

    public function getResult($options = array())
    {
       $records = array();
       foreach($this->result as $productID => $result)
       {
           $records[] = array(
               'product' => $productID,
               'value'    => $result['total'] ? round($result['haveCase'] / $result['total'], 2) : 0,
           );
       }

       return $this->filterByOptions($records, $options);
    }
}
