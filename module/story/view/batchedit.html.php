<?php
/**
 * The batch edit view of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('dittoNotice', $this->lang->story->dittoNotice);?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['story']);?></span>
    <strong><small class='text-muted'><?php echo html::icon($lang->icons['batchEdit']);?></small> <?php echo $lang->story->common . $lang->colon . $lang->story->batchEdit;?></strong>
    <?php if($productName):?>
    <small class='text-muted'><?php echo html::icon($lang->icons['product']) . ' ' . $lang->story->product . $lang->colon . ' ' . $productName;?></small>
    <?php endif;?>
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
<form class='form-condensed' method='post' target='hiddenwin' action="<?php echo inLink('batchEdit', "from=storyBatchEdit")?>">
  <table class='table table-form table-fixed with-border'>
    <thead>
      <tr class='text-center'>
        <th class='w-40px'> <?php echo $lang->idAB;?></th> 
        <th class='w-150px<?php echo zget($hasFields, 'module', ' hidden')?>'><?php echo $lang->story->module;?></th>
        <th class='w-150px<?php echo zget($hasFields, 'plan', ' hidden')?>'><?php echo $lang->story->planAB;?></th>
        <th> <?php echo $lang->story->title;?> <span class='required'></span></th>
        <th class='w-50px<?php echo zget($hasFields, 'estimate', ' hidden')?>'> <?php echo $lang->story->estimateAB;?></th>
        <th class='w-70px<?php echo zget($hasFields, 'pri', ' hidden')?>'> <?php echo $lang->priAB;?></th>
        <th class='w-100px<?php echo zget($hasFields, 'assignedTo', ' hidden')?>'> <?php echo $lang->story->assignedTo;?></th>
        <th class='w-100px<?php echo zget($hasFields, 'source', ' hidden')?>'> <?php echo $lang->story->source;?></th>
        <th class='w-80px'> <?php echo $lang->story->status;?></th>
        <th class='w-100px<?php echo zget($hasFields, 'stage', ' hidden')?>'> <?php echo $lang->story->stageAB;?></th>
        <th class='w-130px<?php echo zget($hasFields, 'closedBy', ' hidden')?>'><?php echo $lang->story->closedBy;?></th>
        <th<?php echo zget($hasFields, 'closedReason', "class='hidden'")?>><?php echo $lang->story->closedReason;?></th>
        <th class='w-80px<?php echo zget($hasFields, 'keywords', ' hidden')?>'><?php echo $lang->story->keywords;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($storyIDList as $storyID):?>
      <?php
      if(!$productID)
      {
          $product = $this->product->getByID($stories[$storyID]->product);
          $modules = $this->tree->getOptionMenu($stories[$storyID]->product, $viewType = 'story', 0, $branch);
          foreach($modules as $moduleID => $moduleName) $modules[$moduleID] = '/' . $product->name . $moduleName;
          $modules['ditto'] = $this->lang->story->ditto;

          $productPlans          = $this->productplan->getPairs($stories[$storyID]->product, $branch);
          $productPlans['ditto'] = $this->lang->story->ditto;
      }
      ?>
      <tr class='text-center'>
        <td><?php echo $storyID . html::hidden("storyIDList[$storyID]", $storyID);?></td>
        <td class='text-left<?php echo zget($hasFields, 'module', ' hidden')?>' style='overflow:visible'>    <?php echo html::select("modules[$storyID]", $modules, $stories[$storyID]->module, "class='form-control chosen'");?></td>
        <td class='text-left<?php echo zget($hasFields, 'plan', ' hidden')?>' style='overflow:visible'>    <?php echo html::select("plans[$storyID]",   $productPlans, $stories[$storyID]->plan, "class='form-control chosen'");?></td>
        <td title='<?php echo $stories[$storyID]->title?>'><?php echo html::input("titles[$storyID]",   $stories[$storyID]->title, 'class=form-control'); ?></td>
        <td <?php echo zget($hasFields, 'estimate', "class='hidden'")?>><?php echo html::input("estimates[$storyID]", $stories[$storyID]->estimate, "class='form-control' autocomplete='off'"); ?></td>
        <td <?php echo zget($hasFields, 'pri', "class='hidden'")?>><?php echo html::select("pris[$storyID]",     $priList, $stories[$storyID]->pri, 'class=form-control');?></td>
        <td class='text-left<?php echo zget($hasFields, 'assignedTo', ' hidden')?>' style='overflow:visible'><?php echo html::select("assignedTo[$storyID]",     $users, $stories[$storyID]->assignedTo, "class='form-control chosen'");?></td>
        <td <?php echo zget($hasFields, 'source', "class='hidden'")?>><?php echo html::select("sources[$storyID]",  $sourceList, $stories[$storyID]->source, 'class=form-control');?></td>
        <td class='story-<?php echo $stories[$storyID]->status;?>'><?php echo $lang->story->statusList[$stories[$storyID]->status];?></td>
        <td <?php echo zget($hasFields, 'stage', "class='hidden'")?>><?php echo html::select("stages[$storyID]", $stageList, $stories[$storyID]->stage, 'class="form-control"' . ($stories[$storyID]->status == 'draft' ? ' disabled="disabled"' : ''));?></td>
        <td class='text-left<?php echo zget($hasFields, 'closedBy', ' hidden')?>' style='overflow:visible'><?php echo html::select("closedBys[$storyID]",     $users, $stories[$storyID]->closedBy, "class='form-control" . ($stories[$storyID]->status == 'closed' ? " chosen'" : "' disabled='disabled'"));?></td>

        <?php if($stories[$storyID]->status == 'closed'):?>
        <td <?php echo zget($hasFields, 'closedReason', "class='hidden'")?>>
          <table class='w-p100'>
            <tr>
              <td class='pd-0'>
                <?php echo html::select("closedReasons[$storyID]", $reasonList, $stories[$storyID]->closedReason, "class=form-control onchange=setDuplicateAndChild(this.value,$storyID) style='min-width: 70px'");?>
              </td>
              <td class='pd-0' id='<?php echo 'duplicateStoryBox' . $storyID;?>' <?php if($stories[$storyID]->closedReason != 'duplicate') echo "style='display:none'";?>>
              <?php echo html::input("duplicateStoryIDList[$storyID]", '', "class=form-control placeholder='{$lang->idAB}'");?>
              </td>
              <td class='pd-0' id='<?php echo 'childStoryBox' . $storyID;?>' <?php if($stories[$storyID]->closedReason != 'subdivided') echo "style='display:none'";?>>
              <?php echo html::input("childStoriesIDList[$storyID]", '', "class=form-control placeholder='{$lang->idAB}'");?>
              </td>
            </tr>
          </table>
        </td>
        <?php else:?>
        <td <?php echo zget($hasFields, 'closedReason', "class='hidden'")?>><?php echo html::select("closedReasons[$storyID]", $reasonList, $stories[$storyID]->closedReason, 'class="form-control" disabled="disabled"');?></td>
        <?php endif;?>
        <td <?php echo zget($hasFields, 'keywords', "class='hidden'")?>><?php echo html::input("keywords[$storyID]", $stories[$storyID]->keywords, 'class="form-control"');?></td>
      </tr>
      <?php endforeach;?>
      <?php if(isset($suhosinInfo)):?>
      <tr><td colspan='<?php echo count($hasFields) + 3;?>'><div id='suhosinInfo' class='alert alert-info'><?php echo $suhosinInfo;?></div></td></tr>
      <?php endif;?>
    </tbody>
    <tfoot>
      <tr><td colspan='<?php echo count($hasFields) + 3;?>' class='text-center'><?php echo html::submitButton();?></td></tr>
    </tfoot>
  </table>
</form>
<?php $customLink = $this->createLink('custom', 'ajaxSaveCustom', 'module=story&section=custom&key=batchedit')?>
<?php include '../../common/view/customfield.html.php';?>
<?php include '../../common/view/footer.html.php';?>
