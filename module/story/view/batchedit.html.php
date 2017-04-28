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
<?php if(isset($suhosinInfo)):?>
<div id='suhosinInfo' class='alert alert-info'><?php echo $suhosinInfo;?></div>
<?php else:?>
<?php
$visibleFields = array();
foreach(explode(',', $showFields) as $field)
{
    if($field)$visibleFields[$field] = '';
}
?>
<form class='form-condensed' method='post' target='hiddenwin' action="<?php echo inLink('batchEdit', "from=storyBatchEdit")?>">
  <table class='table table-form table-fixed with-border'>
    <thead>
      <tr class='text-center'>
        <th class='w-40px'> <?php echo $lang->idAB;?></th> 
        <?php if($branchProduct):?>
        <th class='w-150px<?php echo zget($visibleFields, 'branch', ' hidden')?>'><?php echo $lang->story->branch;?></th>
        <?php endif;?>
        <th class='w-150px<?php echo zget($visibleFields, 'module', ' hidden')?>'><?php echo $lang->story->module;?></th>
        <th class='w-150px<?php echo zget($visibleFields, 'plan', ' hidden')?>'><?php echo $lang->story->planAB;?></th>
        <th> <?php echo $lang->story->title;?> <span class='required'></span></th>
        <th class='w-50px<?php echo zget($visibleFields, 'estimate', ' hidden')?>'> <?php echo $lang->story->estimateAB;?></th>
        <th class='w-70px<?php echo zget($visibleFields, 'pri', ' hidden')?>'> <?php echo $lang->priAB;?></th>
        <th class='w-100px<?php echo zget($visibleFields, 'assignedTo', ' hidden')?>'> <?php echo $lang->story->assignedTo;?></th>
        <th class='w-100px<?php echo zget($visibleFields, 'source', ' hidden')?>'> <?php echo $lang->story->source;?></th>
        <th class='w-80px'> <?php echo $lang->story->status;?></th>
        <th class='w-100px<?php echo zget($visibleFields, 'stage', ' hidden')?>'> <?php echo $lang->story->stageAB;?></th>
        <th class='w-130px<?php echo zget($visibleFields, 'closedBy', ' hidden')?>'><?php echo $lang->story->closedBy;?></th>
        <th class='w-100px<?php echo zget($visibleFields, 'closedReason', ' hidden')?>'> <?php echo $lang->story->closedReason;?></th>
        <th class='w-80px<?php echo zget($visibleFields, 'keywords', ' hidden')?>'><?php echo $lang->story->keywords;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($storyIDList as $storyID):?>
      <?php
      if(!isset($stories[$storyID])) continue;
      if(!$productID)
      {
          $product = $this->product->getByID($stories[$storyID]->product);

          $branches = $product->type == 'normal' ? array('' => '') : $this->loadModel('branch')->getPairs($product->id);
          if($product->type != 'normal')
          {
              foreach($branches as $branchID => $branchName) $branches[$branchID] = '/' . $product->name . '/' . $branchName;
              $branches = array('ditto' => $this->lang->story->ditto) + $branches;
          }

          $modules = $this->tree->getOptionMenu($stories[$storyID]->product, $viewType = 'story', 0, $stories[$storyID]->branch);
          foreach($modules as $moduleID => $moduleName) $modules[$moduleID] = '/' . $product->name . $moduleName;
          $modules = array('ditto' => $this->lang->story->ditto) + $modules;

          $productPlans = $this->productplan->getPairs($stories[$storyID]->product, $branch);
          $productPlans = array('' => '', 'ditto' => $this->lang->story->ditto) + $productPlans;
      }
      ?>
      <tr class='text-center'>
        <td><?php echo $storyID . html::hidden("storyIDList[$storyID]", $storyID);?></td>
        <?php if($branchProduct):?>
        <td class='text-left<?php echo zget($visibleFields, 'branch', ' hidden')?>' style='overflow:visible'>
          <?php $branchProductID = $productID ? $productID : $product->id;?>
          <?php $disabled        = (isset($product) and $product->type == 'normal') ? "disabled='disabled'" : '';?>
          <?php echo html::select("branches[$storyID]", $branches, $stories[$storyID]->branch, "class='form-control chosen' onchange='loadBranches($branchProductID, this.value, $storyID);' $disabled");?>
        </td>
        <?php endif;?>
        <td class='text-left<?php echo zget($visibleFields, 'module', ' hidden')?>' style='overflow:visible'>    <?php echo html::select("modules[$storyID]", $modules, $stories[$storyID]->module, "class='form-control chosen'");?></td>
        <td class='text-left<?php echo zget($visibleFields, 'plan', ' hidden')?>' style='overflow:visible'>    <?php echo html::select("plans[$storyID]",   $productPlans, $stories[$storyID]->plan, "class='form-control chosen'");?></td>
        <td style='overflow:visible' title='<?php echo $stories[$storyID]->title?>'>
          <div class='input-group'>
          <?php echo html::hidden("colors[$storyID]", $stories[$storyID]->color, "data-provide='colorpicker' data-wrapper='input-group-btn fix-border-right' data-pull-menu-right='false' data-btn-tip='{$lang->story->colorTag}' data-update-text='#titles\\[{$storyID}\\]'");?>
          <?php echo html::input("titles[$storyID]", $stories[$storyID]->title, "class='form-control' autocomplete='off'"); ?>
          </div>
        </td>
        <td <?php echo zget($visibleFields, 'estimate', "class='hidden'")?>><?php echo html::input("estimates[$storyID]", $stories[$storyID]->estimate, "class='form-control' autocomplete='off'"); ?></td>
        <td <?php echo zget($visibleFields, 'pri', "class='hidden'")?>><?php echo html::select("pris[$storyID]",     $priList, $stories[$storyID]->pri, 'class=form-control');?></td>
        <td class='text-left<?php echo zget($visibleFields, 'assignedTo', ' hidden')?>' style='overflow:visible'><?php echo html::select("assignedTo[$storyID]",     $users, $stories[$storyID]->assignedTo, "class='form-control chosen'");?></td>
        <td <?php echo zget($visibleFields, 'source', "class='hidden'")?>><?php echo html::select("sources[$storyID]",  $sourceList, $stories[$storyID]->source, 'class=form-control');?></td>
        <td class='story-<?php echo $stories[$storyID]->status;?>'><?php echo $lang->story->statusList[$stories[$storyID]->status];?></td>
        <td <?php echo zget($visibleFields, 'stage', "class='hidden'")?>><?php echo html::select("stages[$storyID]", $stageList, $stories[$storyID]->stage, 'class="form-control"' . ($stories[$storyID]->status == 'draft' ? ' disabled="disabled"' : ''));?></td>
        <td class='text-left<?php echo zget($visibleFields, 'closedBy', ' hidden')?>' style='overflow:visible'><?php echo html::select("closedBys[$storyID]",     $users, $stories[$storyID]->closedBy, "class='form-control" . ($stories[$storyID]->status == 'closed' ? " chosen'" : "' disabled='disabled'"));?></td>

        <?php if($stories[$storyID]->status == 'closed'):?>
        <td <?php echo zget($visibleFields, 'closedReason', "class='hidden'")?>>
          <table class='w-p100'>
            <tr>
              <td class='pd-0'>
                <?php echo html::select("closedReasons[$storyID]", $reasonList, $stories[$storyID]->closedReason, "class=form-control onchange=setDuplicateAndChild(this.value,$storyID) style='min-width: 70px'");?>
              </td>
              <td class='pd-0' id='<?php echo 'duplicateStoryBox' . $storyID;?>' <?php if($stories[$storyID]->closedReason != 'duplicate') echo "style='display:none'";?>>
              <?php echo html::input("duplicateStoryIDList[$storyID]", '', "class='form-control' placeholder='{$lang->idAB}' autocomplete='off'");?>
              </td>
              <td class='pd-0' id='<?php echo 'childStoryBox' . $storyID;?>' <?php if($stories[$storyID]->closedReason != 'subdivided') echo "style='display:none'";?>>
              <?php echo html::input("childStoriesIDList[$storyID]", '', "class='form-control' placeholder='{$lang->idAB}' autocomplete='off'");?>
              </td>
            </tr>
          </table>
        </td>
        <?php else:?>
        <td <?php echo zget($visibleFields, 'closedReason', "class='hidden'")?>><?php echo html::select("closedReasons[$storyID]", $reasonList, $stories[$storyID]->closedReason, 'class="form-control" disabled="disabled"');?></td>
        <?php endif;?>
        <td <?php echo zget($visibleFields, 'keywords', "class='hidden'")?>><?php echo html::input("keywords[$storyID]", $stories[$storyID]->keywords, 'class="form-control" autocomplete="off"');?></td>
      </tr>
      <?php endforeach;?>
    </tbody>
    <tfoot>
      <tr><td colspan='<?php echo count($visibleFields) + 3;?>' class='text-center'><?php echo html::submitButton();?></td></tr>
    </tfoot>
  </table>
</form>
<?php endif;?>
<?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=story&section=custom&key=batchEditFields')?>
<?php include '../../common/view/customfield.html.php';?>
<?php include '../../common/view/footer.html.php';?>
