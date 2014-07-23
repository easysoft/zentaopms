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
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['testcase']);?></span>
    <strong><small class='text-muted'><?php echo html::icon($lang->icons['batchEdit']);?></small> <?php echo $lang->testcase->common . $lang->colon . $lang->testcase->batchEdit;?></strong>
  </div>
</div>
<form class='form-condensed' method='post' target='hiddenwin' action="<?php echo inLink('batchEdit');?>">
  <table class='table table-form table-fixed'>
    <thead>
      <tr>
        <th class='w-50px'><?php  echo $lang->idAB;?></th> 
        <th class='w-70px'><?php  echo $lang->priAB;?></th>
        <th class='w-100px'><?php  echo $lang->statusAB;?></th>
        <th class='w-150px'><?php echo $lang->testcase->module;?></th> 
        <th><?php echo $lang->testcase->title;?></th>
        <th class='w-120px'><?php echo $lang->testcase->type;?></th>
        <th class='w-340px'><?php echo $lang->testcase->stage;?></th>
      </tr>
    </thead>

    <?php foreach($caseIDList as $caseID):?>
    <?php if(!$productID) $moduleOptionMenu = $this->tree->getOptionMenu($cases[$caseID]->product, $viewType = 'case', $startModuleID = 0); ?>
    <tr class='text-center'>
      <td><?php echo $caseID . html::hidden("caseIDList[$caseID]", $caseID);?></td>
      <td><?php echo html::select("pris[$caseID]",      $lang->testcase->priList, $cases[$caseID]->pri, 'class=form-control');?></td>
      <td><?php echo html::select("statuses[$caseID]",  (array)$lang->testcase->statusList, $cases[$caseID]->status, 'class=form-control');?></td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("modules[$caseID]",   $moduleOptionMenu, $cases[$caseID]->module, "class='form-control chosen'");?></td>
      <td><?php echo html::input("titles[$caseID]",     $cases[$caseID]->title, 'class=form-control'); echo "<span class='star'>*</span>";?></td>
      <td><?php echo html::select("types[$caseID]",     $lang->testcase->typeList, $cases[$caseID]->type, 'class=form-control');?></td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("stages[$caseID][]",  $lang->testcase->stageList, $cases[$caseID]->stage, "class='form-control chosen' multiple data-placeholder='{$lang->testcase->stage}'");?></td>
    </tr>  
    <?php endforeach;?>
    <?php if(isset($suhosinInfo)):?>
    <tr><td colspan='<?php echo $this->config->testcase->batchEdit->columns;?>'><div class='alert alert-info'><?php echo $suhosinInfo;?></div></td></tr>
    <?php endif;?>
    <tfoot>
      <tr><td colspan='<?php echo $this->config->testcase->batchEdit->columns;?>' class='text-center'><?php echo html::submitButton();?></td></tr>
    </tfoot>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
