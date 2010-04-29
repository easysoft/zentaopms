<?php
/**
 * The runrun view file of testtask of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright 2009-2010 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     testtask
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<style>body{background:white}</style>
<div class='yui-d0'>
  <h1>CASE#<?php echo $run->case->id. $lang->colon . $run->case->title;?></h1>
  <?php foreach($results as $result):?>
  <table class='table-1 bd-1px'>
  <caption>RESULT#<?php echo $result->id . ' ' . $result->date . " <span class='$result->caseResult'>" . $lang->testcase->resultList[$result->caseResult] . '</span>';?></caption>
    <tr>
      <th class='w-30px'><?php echo $lang->testcase->stepID;?></th>
      <th class='w-p40'><?php echo $lang->testcase->stepDesc;?></th>
      <th class='w-p20'><?php echo $lang->testcase->stepExpect;?></th>
      <th><?php echo $lang->testcase->result;?></th>
      <th class='w-p20'><?php echo $lang->testcase->real;?></th>
    </tr>
    <?php foreach($run->case->steps as $key => $step):?>
    <?php $stepResult = (object)$result->stepResults[$step->id];?>
    <tr>
      <th><?php echo $key + 1;?></th>
      <td><?php echo nl2br($step->desc);?></td>
      <td><?php echo nl2br($step->expect);?></td>
      <td class='<?php echo $stepResult->result;?> a-center'><?php echo $lang->testcase->resultList[$stepResult->result];?></td>
      <td><?php echo $stepResult->real;?></td>
    </tr>
    <?php if($stepResult->result == 'blocked' or $stepResult->result == 'fail') break;?>
    <?php endforeach;?>
  </table>
  <?php endforeach;?>
</div>
