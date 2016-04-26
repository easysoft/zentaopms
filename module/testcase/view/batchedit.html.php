<?php
/**
 * The batch edit view of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     testcase
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('dittoNotice', $this->lang->testcase->dittoNotice);?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['testcase']);?></span>
    <strong><small class='text-muted'><?php echo html::icon($lang->icons['batchEdit']);?></small> <?php echo $lang->testcase->common . $lang->colon . $lang->testcase->batchEdit;?></strong>
    <div class='actions'>
      <button type="button" class="btn btn-default" data-toggle="customModal"><i class='icon icon-cog'></i> </button>
    </div>
  </div>
</div>
<?php
$hasFields = array();
foreach(explode(',', $showFields) as $field)
{
    if($field)$hasFields[$field] = '';
}
?>
<form class='form-condensed' method='post' target='hiddenwin' action="<?php echo inLink('batchEdit');?>">
  <table class='table table-form table-fixed'>
    <thead>
      <tr>
        <th class='w-50px'><?php  echo $lang->idAB;?></th> 
        <th class='w-70px<?php echo zget($hasFields, 'pri', ' hidden')?>'>    <?php echo $lang->priAB;?></th>
        <th class='w-100px<?php echo zget($hasFields, 'status', ' hidden')?>'><?php echo $lang->statusAB;?></th>
        <th class='w-150px<?php echo zget($hasFields, 'module', ' hidden')?>'><?php echo $lang->testcase->module;?></th>
        <th><?php echo $lang->testcase->title;?></th>
        <th class='w-120px'><?php echo $lang->testcase->type;?></th>
        <th class='<?php echo zget($hasFields, 'precondition', 'hidden')?>'>    <?php echo $lang->testcase->precondition;?></th>
        <th class='w-100px<?php echo zget($hasFields, 'keywords', ' hidden')?>'><?php echo $lang->testcase->keywords;?></th>
        <th class='w-340px<?php echo zget($hasFields, 'stage', ' hidden')?>'>   <?php echo $lang->testcase->stage;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($caseIDList as $caseID):?>
      <?php
      if(!$productID)
      {
          $product = $this->product->getByID($cases[$caseID]->product);
          $modules = $this->tree->getOptionMenu($cases[$caseID]->product, $viewType = 'case', $startModuleID = 0);
          foreach($modules as $moduleID => $moduleName) $modules[$moduleID] = '/' . $product->name . $moduleName;
          $modules['ditto'] = $this->lang->testcase->ditto;
      }
      ?>
      <tr class='text-center'>
        <td><?php echo $caseID . html::hidden("caseIDList[$caseID]", $caseID);?></td>
        <td class='<?php echo zget($hasFields, 'pri', 'hidden')?>'>   <?php echo html::select("pris[$caseID]",     $priList, $cases[$caseID]->pri, 'class=form-control');?></td>
        <td class='<?php echo zget($hasFields, 'status', 'hidden')?>'><?php echo html::select("statuses[$caseID]", (array)$lang->testcase->statusList, $cases[$caseID]->status, 'class=form-control');?></td>
        <td class='text-left<?php echo zget($hasFields, 'module', ' hidden')?>' style='overflow:visible'><?php echo html::select("modules[$caseID]",  $modules,   $cases[$caseID]->module, "class='form-control chosen'");?></td>
        <td title='<?php echo $cases[$caseID]->title?>'><?php echo html::input("titles[$caseID]", $cases[$caseID]->title, 'class=form-control'); echo "<span class='star'>*</span>";?></td>
        <td><?php echo html::select("types[$caseID]", $typeList, $cases[$caseID]->type, 'class=form-control');?></td>
        <td class='<?php echo zget($hasFields, 'precondition', 'hidden')?>'><?php echo html::textarea("precondition[$caseID]", $cases[$caseID]->precondition, "rows='1' class='form-control autosize'")?></td>
        <td class='<?php echo zget($hasFields, 'keywords', 'hidden')?>'>    <?php echo html::input("keywords[$caseID]", $cases[$caseID]->keywords, "class='form-control'");?></td>
        <td class='text-left<?php echo zget($hasFields, 'stage', ' hidden')?>' style='overflow:visible'><?php echo html::select("stages[$caseID][]", $lang->testcase->stageList, $cases[$caseID]->stage, "class='form-control chosen' multiple data-placeholder='{$lang->testcase->stage}'");?></td>
      </tr>
      <?php endforeach;?>
      <?php if(isset($suhosinInfo)):?>
      <tr><td colspan='<?php echo count($hasFields) + 3;?>'><div class='alert alert-info'><?php echo $suhosinInfo;?></div></td></tr>
      <?php endif;?>
    </tbody>
    <tfoot>
      <tr><td colspan='<?php echo count($hasFields) + 3;?>' class='text-center'><?php echo html::submitButton();?></td></tr>
    </tfoot>
  </table>
</form>
<?php $customLink = $this->createLink('custom', 'ajaxSaveCustom', 'module=testcase&section=custom&key=batchedit')?>
<?php include '../../common/view/customfield.html.php';?>
<?php include '../../common/view/footer.html.php';?>
