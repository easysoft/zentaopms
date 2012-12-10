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
<form method='post' target='hiddenwin' action="<?php echo $this->inLink('batchEdit', "from=testcaseBatchEdit");?>">
  <table class='table-1 fixed'> 
    <caption><?php echo $lang->testcase->common . $lang->colon . $lang->testcase->batchEdit;?></caption>
    <tr>
      <th class='w-30px'><?php  echo $lang->idAB;?></th> 
      <th class='w-50px'><?php  echo $lang->priAB;?></th>
      <th class='w-70px'><?php  echo $lang->statusAB;?></th>
      <th class='w-140px'><?php echo $lang->testcase->module;?></th> 
      <th><?php echo $lang->testcase->title;?></th>
      <th class='w-100px'><?php echo $lang->testcase->type;?></th>
      <th class='w-340px'><?php echo $lang->testcase->stage;?></th>
    </tr>
    <?php foreach($editedCases as $case):?>
    <tr class='a-center'>
      <td><?php echo $case->id . html::hidden("caseIDList[$case->id]", $case->id);?></td>
      <td><?php echo html::select("pris[$case->id]",      $lang->testcase->priList, $case->pri, 'class=select-1');?></td>
      <td><?php echo html::select("statuses[$case->id]",  (array)$lang->testcase->statusList, $case->status, 'class=select-1');?></td>
      <td><?php echo html::select("modules[$case->id]",   $moduleOptionMenu, $case->module, "class='select-1'");?></span></td>
      <td><?php echo html::input("titles[$case->id]",     $case->title, 'class=text-1'); echo "<span class='star'>*</span>";?></td>
      <td><?php echo html::select("types[$case->id]",     $lang->testcase->typeList, $case->type, 'class=select-1');?></td>
      <td>
      <?php 
      foreach($lang->testcase->stageListAB as $key => $stage)
      {
          if(in_array($key, explode(',', $case->stage)))
          {
              echo "<input type='checkbox' name='stages[$case->id][$key]' checked='checked' value='$key'/>$stage "; 
          }
          else
          {
              echo "<input type='checkbox' name='stages[$case->id][$key]' value='$key'/>$stage "; 
          }
      }
      ?>
      </td>
    </tr>  
    <?php endforeach;?>
    <?php if(isset($suhosinInfo)):?>
    <tr><td colspan='7'><div class='f-left blue'><?php echo $suhosinInfo;?></div></td></tr>
    <?php endif;?>
    <tr><td colspan='7' class='a-center'><?php echo html::submitButton();?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
