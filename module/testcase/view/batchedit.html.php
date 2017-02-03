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
$visibleFields = array();
foreach(explode(',', $showFields) as $field)
{
    if($field)$visibleFields[$field] = '';
}
?>
<form class='form-condensed' method='post' target='hiddenwin' action="<?php echo inLink('batchEdit');?>">
  <table class='table table-form table-fixed'>
    <thead>
      <tr>
        <th class='w-50px'><?php  echo $lang->idAB;?></th> 
        <th class='w-70px<?php echo zget($visibleFields, 'pri', ' hidden')?>'>    <?php echo $lang->priAB;?></th>
        <th class='w-100px<?php echo zget($visibleFields, 'status', ' hidden')?>'><?php echo $lang->statusAB;?></th>
        <th class='w-150px<?php echo zget($visibleFields, 'module', ' hidden')?>'><?php echo $lang->testcase->module;?></th>
        <th><?php echo $lang->testcase->title;?></th>
        <th class='w-120px'><?php echo $lang->testcase->type;?></th>
        <th class='<?php echo zget($visibleFields, 'precondition', 'hidden')?>'>    <?php echo $lang->testcase->precondition;?></th>
        <th class='w-100px<?php echo zget($visibleFields, 'keywords', ' hidden')?>'><?php echo $lang->testcase->keywords;?></th>
        <th class='w-340px<?php echo zget($visibleFields, 'stage', ' hidden')?>'>   <?php echo $lang->testcase->stage;?></th>
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
          $modules = array('ditto' => $this->lang->testcase->ditto) + $modules;
      }
      ?>
      <tr class='text-center'>
        <td><?php echo $caseID . html::hidden("caseIDList[$caseID]", $caseID);?></td>
        <td class='<?php echo zget($visibleFields, 'pri', 'hidden')?>'>   <?php echo html::select("pris[$caseID]",     $priList, $cases[$caseID]->pri, 'class=form-control');?></td>
        <td class='<?php echo zget($visibleFields, 'status', 'hidden')?>'><?php echo html::select("statuses[$caseID]", (array)$lang->testcase->statusList, $cases[$caseID]->status, 'class=form-control');?></td>
        <td class='text-left<?php echo zget($visibleFields, 'module', ' hidden')?>' style='overflow:visible'><?php echo html::select("modules[$caseID]",  $modules,   $cases[$caseID]->module, "class='form-control chosen'");?></td>
        <td style='overflow:visible' title='<?php echo $cases[$caseID]->title?>'>
          <div class='input-group'>
          <?php echo html::hidden("colors[$caseID]", $cases[$caseID]->color, "data-provide='colorpicker' data-wrapper='input-group-btn fix-border-right' data-pull-menu-right='false' data-btn-tip='{$lang->testcase->colorTag}' data-update-text='#titles\\[{$caseID}\\]'");?>
          <?php echo html::input("titles[$caseID]", $cases[$caseID]->title, "class='form-control' autocomplete='off'"); echo "<span class='star'>*</span>";?>
          </div>
        </td>
        <td><?php echo html::select("types[$caseID]", $typeList, $cases[$caseID]->type, 'class=form-control');?></td>
        <td class='<?php echo zget($visibleFields, 'precondition', 'hidden')?>'><?php echo html::textarea("precondition[$caseID]", $cases[$caseID]->precondition, "rows='1' class='form-control autosize'")?></td>
        <td class='<?php echo zget($visibleFields, 'keywords', 'hidden')?>'>    <?php echo html::input("keywords[$caseID]", $cases[$caseID]->keywords, "class='form-control' autocomplete='off'");?></td>
        <td class='text-left<?php echo zget($visibleFields, 'stage', ' hidden')?>' style='overflow:visible'><?php echo html::select("stages[$caseID][]", $lang->testcase->stageList, $cases[$caseID]->stage, "class='form-control chosen' multiple data-placeholder='{$lang->testcase->stage}'");?></td>
      </tr>
      <?php endforeach;?>
      <?php if(isset($suhosinInfo)):?>
      <tr><td colspan='<?php echo count($visibleFields) + 3;?>'><div class='alert alert-info'><?php echo $suhosinInfo;?></div></td></tr>
      <?php endif;?>
    </tbody>
    <tfoot>
      <tr><td colspan='<?php echo count($visibleFields) + 3;?>' class='text-center'><?php echo html::submitButton();?></td></tr>
    </tfoot>
  </table>
</form>
<?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=testcase&section=custom&key=batchEditFields')?>
<?php include '../../common/view/customfield.html.php';?>
<?php include '../../common/view/footer.html.php';?>
