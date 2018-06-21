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
<div class='main-content' id='mainContent'>
<div class='main-header'>
  <h2>
    <?php echo $lang->story->common . $lang->colon . $lang->story->batchEdit;?>
  </h2>
  <div class='pull-right btn-toolbar'>
    <?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=story&section=custom&key=batchEditFields')?>
    <?php include '../../common/view/customfield.html.php';?>
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
<form method='post' target='hiddenwin' action="<?php echo inLink('batchEdit', "from=storyBatchEdit")?>" id="batchEditForm">
  <div class="table-responsive">
    <table class='table table-form'>
      <thead>
        <tr>
          <th class='w-40px'> <?php echo $lang->idAB;?></th> 
          <?php if($branchProduct):?>
          <th class='w-120px<?php echo zget($visibleFields, 'branch', ' hidden')?>'><?php echo $lang->story->branch;?></th>
          <?php endif;?>
          <th class='w-120px'><?php echo $lang->story->module;?></th>
          <th class='w-150px<?php echo zget($visibleFields, 'plan', ' hidden')?>'><?php echo $lang->story->planAB;?></th>
          <th class='w-150px required'><?php echo $lang->story->title;?></th>
          <th class='w-50px<?php   echo zget($visibleFields, 'estimate', ' hidden')?>'> <?php echo $lang->story->estimateAB;?></th>
          <th class='w-70px<?php   echo zget($visibleFields, 'pri', ' hidden')?>'> <?php echo $lang->priAB;?></th>
          <th class='w-100px<?php  echo zget($visibleFields, 'assignedTo', ' hidden')?>'> <?php echo $lang->story->assignedTo;?></th>
          <th class='w-100px<?php  echo zget($visibleFields, 'source', ' hidden')?>'> <?php echo $lang->story->source;?></th>
          <th class='w-80px'><?php echo $lang->story->status;?></th>
          <th class='w-100px<?php  echo zget($visibleFields, 'stage', ' hidden')?>'> <?php echo $lang->story->stageAB;?></th>
          <th class='w-130px<?php  echo zget($visibleFields, 'closedBy', ' hidden')?>'><?php echo $lang->story->closedBy;?></th>
          <th class='w-100px<?php  echo zget($visibleFields, 'closedReason', ' hidden')?>'> <?php echo $lang->story->closedReason;?></th>
          <th class='w-80px<?php   echo zget($visibleFields, 'keywords', ' hidden')?>'><?php echo $lang->story->keywords;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($stories as $storyID => $story):?>
        <?php
        if(!$productID)
        {
            $product = $this->product->getByID($story->product);

            $branches = $product->type == 'normal' ? array('' => '') : $this->loadModel('branch')->getPairs($product->id);
            if($product->type != 'normal')
            {
                foreach($branches as $branchID => $branchName) $branches[$branchID] = '/' . $product->name . '/' . $branchName;
                $branches = array('ditto' => $this->lang->story->ditto) + $branches;
            }

            $modules = $this->tree->getOptionMenu($story->product, $viewType = 'story', 0, $story->branch);
            foreach($modules as $moduleID => $moduleName) $modules[$moduleID] = '/' . $product->name . $moduleName;
            $modules = array('ditto' => $this->lang->story->ditto) + $modules;

            $productPlans = $this->productplan->getPairs($story->product, $branch);
            $productPlans = array('' => '', 'ditto' => $this->lang->story->ditto) + $productPlans;
        }
        ?>
        <tr>
          <td><?php echo $storyID . html::hidden("storyIDList[$storyID]", $storyID);?></td>
          <?php if($branchProduct):?>
          <td class='text-left<?php echo zget($visibleFields, 'branch', ' hidden')?>'>
            <?php $branchProductID = $productID ? $productID : $product->id;?>
            <?php $disabled        = (isset($product) and $product->type == 'normal') ? "disabled='disabled'" : '';?>
            <?php echo html::select("branches[$storyID]", $branches, $story->branch, "class='form-control chosen' onchange='loadBranches($branchProductID, this.value, $storyID);' $disabled");?>
          </td>
          <?php endif;?>
          <td class='text-left<?php echo zget($visibleFields, 'module')?>'>
            <?php echo html::select("modules[$storyID]", $modules, $story->module, "class='form-control chosen'");?>
          </td>
          <td class='text-left<?php echo zget($visibleFields, 'plan', ' hidden')?>'>
            <?php echo html::select("plans[$storyID]", $productPlans, $story->plan, "class='form-control chosen'");?>
          </td>
          <!-- <td title='<?php echo $story->title?>'>
            <div class='input-group'>
            <?php echo html::hidden("colors[$storyID]", $story->color, "data-provide='colorpicker' data-wrapper='input-group-btn fix-border-right' data-pull-menu-right='false' data-btn-tip='{$lang->story->colorTag}' data-update-text='#titles\\[{$storyID}\\]'");?>
            <?php echo html::input("titles[$storyID]", $story->title, "class='form-control' autocomplete='off'"); ?>
            </div>
          </td> -->
          <td title='<?php echo $story->title?>'>
            <div class="input-group">
              <div class="input-control has-icon-right">
                <?php echo html::input("titles[$storyID]", $story->title, "class='form-control input-story-title' autocomplete='off'"); ?>

                <div class="colorpicker">
                  <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                  <ul class="dropdown-menu clearfix">
                    <li class="heading"><?php echo $lang->story->colorTag;?><i class="icon icon-close"></i></li>
                  </ul>
                  <?php echo html::hidden("colors[$storyID]", $story->color, "class='colorpicker' data-wrapper='input-control-icon-right' data-icon='color' data-btn-tip='{$lang->story->colorTag}' data-update-color='#titles\\[{$storyID}\\]'");?>
                  <!-- <input type="hidden" class="colorpicker" id="color$id" name="color[$id]" value="" data-icon="color" data-wrapper="input-control-icon-right" data-update-color="#title$id"> -->
                </div>
              </div>
            </div>
          </td>
          
          <td <?php echo zget($visibleFields, 'estimate', "class='hidden'")?>><?php echo html::input("estimates[$storyID]", $story->estimate, "class='form-control' autocomplete='off'"); ?></td>
          <td <?php echo zget($visibleFields, 'pri', "class='hidden'")?>><?php echo html::select("pris[$storyID]",     $priList, $story->pri, 'class=form-control');?></td>
          <td class='text-left<?php echo zget($visibleFields, 'assignedTo', ' hidden')?>'><?php echo html::select("assignedTo[$storyID]",     $users, $story->assignedTo, "class='form-control chosen'");?></td>
          <td <?php echo zget($visibleFields, 'source', "class='hidden'")?>><?php echo html::select("sources[$storyID]",  $sourceList, $story->source, 'class=form-control');?></td>
          <td class='story-<?php echo $story->status;?>'><?php echo $lang->story->statusList[$story->status];?></td>
          <td <?php echo zget($visibleFields, 'stage', "class='hidden'")?>><?php echo html::select("stages[$storyID]", $stageList, $story->stage, 'class="form-control"' . ($story->status == 'draft' ? ' disabled="disabled"' : ''));?></td>
          <td class='text-left<?php echo zget($visibleFields, 'closedBy', ' hidden')?>'><?php echo html::select("closedBys[$storyID]",     $users, $story->closedBy, "class='form-control" . ($story->status == 'closed' ? " chosen'" : "' disabled='disabled'"));?></td>

          <?php if($story->status == 'closed'):?>
          <td <?php echo zget($visibleFields, 'closedReason', "class='hidden'")?>>
            <table class='w-p100'>
              <tr>
                <td class='pd-0'>
                  <?php echo html::select("closedReasons[$storyID]", $reasonList, $story->closedReason, "class=form-control onchange=setDuplicateAndChild(this.value,$storyID) style='min-width: 70px'");?>
                </td>
                <td class='pd-0' id='<?php echo 'duplicateStoryBox' . $storyID;?>' <?php if($story->closedReason != 'duplicate') echo "style='display:none'";?>>
                <?php echo html::input("duplicateStoryIDList[$storyID]", '', "class='form-control' placeholder='{$lang->idAB}' autocomplete='off'");?>
                </td>
                <td class='pd-0' id='<?php echo 'childStoryBox' . $storyID;?>' <?php if($story->closedReason != 'subdivided') echo "style='display:none'";?>>
                <?php echo html::input("childStoriesIDList[$storyID]", '', "class='form-control' placeholder='{$lang->idAB}' autocomplete='off'");?>
                </td>
              </tr>
            </table>
          </td>
          <?php else:?>
          <td <?php echo zget($visibleFields, 'closedReason', "class='hidden'")?>><?php echo html::select("closedReasons[$storyID]", $reasonList, $story->closedReason, 'class="form-control" disabled="disabled"');?></td>
          <?php endif;?>
          <td <?php echo zget($visibleFields, 'keywords', "class='hidden'")?>><?php echo html::input("keywords[$storyID]", $story->keywords, 'class="form-control" autocomplete="off"');?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan='<?php echo count($visibleFields) + ($branchProduct ? 3 : 2);?>' class='text-center form-actions'>
            <?php echo html::submitButton('', '', 'btn btn-wide btn-primary');?>
            <?php echo html::backButton('', '', 'btn btn-wide');?>
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
</form>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
