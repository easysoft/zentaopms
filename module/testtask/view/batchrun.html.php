<?php
/**
 * The batch edit view of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     testcase
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<style type='text/css'>
form p{margin:0px;}
table td,th{border:1px solid #E4E4E4}
table .table-1{border:0px; margin:0px;}
table .table-1 td{border:0px;}
</style>
<form method='post' target='hiddenwin'>
  <table class='table-1 fixed'> 
    <caption><?php echo $lang->testtask->common . $lang->colon . $lang->testtask->batchRun;?></caption>
    <tr>
      <th class='w-id'><?php  echo $lang->idAB;?></th> 
      <th class='w-pri'><?php  echo $lang->priAB;?></th>
      <th class='w-100px'><?php echo $lang->testcase->module;?></th> 
      <th><?php echo $lang->testcase->title;?></th>
      <th class='w-150px'><?php echo $lang->testcase->result?></th>
      <th><?php echo $lang->testcase->stepDesc . '/' . $lang->testcase->stepExpect?></th>
    </tr>
    <?php foreach($cases as $caseID => $case):?>
    <tr class='a-center'>
      <td><?php echo $case->id . html::hidden("version[$case->id]", $case->version)?></td>
      <td><?php echo $lang->testcase->priList[$case->pri]?></td>
      <td align='left'><?php echo $moduleOptionMenu[$case->module]?></td>
      <td align='left'><?php echo $case->title?></td>
      <td><?php echo html::radio("results[$case->id]", $resultList, 'pass', "onchange='showAction(this.value,\".action$caseID\")'")?></td>
      <td>
        <table class='table-1'>
          <?php $i = 1;?>
          <?php foreach($steps[$caseID] as $stepID => $step):?>
          <tr>
            <td align='left' width='50%'><?php echo "<span title='$step->desc'>" . $i . "、" . $step->desc . '</span>'?></td>
            <td align='left' width='50%'><?php echo "<span title='$step->expect'>" . $lang->testcase->stepExpect . "：" . $step->expect . '</span>'?></td>
            <td class='hidden action<?php echo $caseID?>'><?php echo html::select("steps[$caseID][$stepID]", $lang->testcase->resultList, 'pass') . html::input("reals[$caseID][$stepID]", '', "class='text-2'");?></td>
          </tr>
          <?php $i++?>
          <?php endforeach?>
        </table>
      </td>
    </tr>  
    <?php endforeach;?>
    <tr><td colspan='6' class='a-center'><?php echo html::submitButton();?></td></tr>
  </table>
</form>
<script type='text/javascript'>
function showAction(value, obj)
{
    if(value == 'pass')
    {
        $(obj).addClass('hidden');
    }
    else
    {
        $(obj).removeClass('hidden');
    }
}
</script>
<?php include '../../common/view/footer.html.php';?>
