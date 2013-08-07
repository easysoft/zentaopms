<?php
/**
 * The batch edit view of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     testcase
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<form method='post' target='hiddenwin' action="<?php echo inLink('batchEdit');?>">
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
    <?php foreach($caseIDList as $caseID):?>
    <?php if(!$productID) $moduleOptionMenu = $this->tree->getOptionMenu($cases[$caseID]->product, $viewType = 'case', $startModuleID = 0); ?>
    <tr class='a-center'>
      <td><?php echo $caseID . html::hidden("caseIDList[$caseID]", $caseID);?></td>
      <td><?php echo html::select("pris[$caseID]",      $lang->testcase->priList, $cases[$caseID]->pri, 'class=select-1');?></td>
      <td><?php echo html::select("statuses[$caseID]",  (array)$lang->testcase->statusList, $cases[$caseID]->status, 'class=select-1');?></td>
      <td><?php echo html::select("modules[$caseID]",   $moduleOptionMenu, $cases[$caseID]->module, "class='select-1'");?></span></td>
      <td><?php echo html::input("titles[$caseID]",     $cases[$caseID]->title, 'class=text-1'); echo "<span class='star'>*</span>";?></td>
      <td><?php echo html::select("types[$caseID]",     $lang->testcase->typeList, $cases[$caseID]->type, 'class=select-1');?></td>
      <td class='a-left' style='overflow:visible'><?php echo html::select("stages[$caseID][]",  $lang->testcase->stageList, $cases[$caseID]->stage, "class='select-1 chosen' multiple data-placeholder='{$lang->testcase->stage}'");?></td>
    </tr>  
    <?php endforeach;?>
    <?php if(isset($suhosinInfo)):?>
    <tr><td colspan='<?php echo $this->config->testcase->batchEdit->columns;?>'><div class='f-left blue'><?php echo $suhosinInfo;?></div></td></tr>
    <?php endif;?>
    <tr><td colspan='<?php echo $this->config->testcase->batchEdit->columns;?>' class='a-center'><?php echo html::submitButton();?></td></tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
