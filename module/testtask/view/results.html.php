<?php
/**
 * The runrun view file of testtask of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<h1>CASE#<?php echo $run->case->id. $lang->colon . $run->case->title;?></h1>
<?php foreach($results as $result):?>
<table class='table-1'>
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
