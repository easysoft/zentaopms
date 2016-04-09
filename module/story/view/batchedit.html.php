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
  </div>
</div>
<form class='form-condensed' method='post' target='hiddenwin' action="<?php echo inLink('batchEdit', "from=storyBatchEdit")?>">
  <table class='table table-form table-fixed'>
    <thead>
      <tr class='text-center'>
        <th class='w-30px'> <?php echo $lang->idAB;?></th> 
        <th class='w-200px'><?php echo $lang->story->module;?></th>
        <th class='w-200px'><?php echo $lang->story->planAB;?></th>
        <th> <?php echo $lang->story->title;?> <span class='required'></span></th>
        <th class='w-50px'> <?php echo $lang->story->estimateAB;?></th>
        <th class='w-70px'> <?php echo $lang->priAB;?></th>
        <th class='w-100px'> <?php echo $lang->story->source;?></th>
        <th class='w-80px'> <?php echo $lang->story->status;?></th>
        <th class='w-100px'> <?php echo $lang->story->stageAB;?></th>
        <th class='w-130px'><?php echo $lang->story->closedBy;?></th>
        <th><?php echo $lang->story->closedReason;?></th>
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
        <td class='text-left' style='overflow:visible'>    <?php echo html::select("modules[$storyID]", $modules, $stories[$storyID]->module, "class='form-control chosen'");?></td>
        <td class='text-left' style='overflow:visible'>    <?php echo html::select("plans[$storyID]",   $productPlans, $stories[$storyID]->plan, "class='form-control chosen'");?></td>
        <td title='<?php echo $stories[$storyID]->title?>'><?php echo html::input("titles[$storyID]",   $stories[$storyID]->title, 'class=form-control'); ?></td>
        <td><?php echo html::input("estimates[$storyID]", $stories[$storyID]->estimate, "class='form-control' autocomplete='off'"); ?></td>
        <td><?php echo html::select("pris[$storyID]",     $priList, $stories[$storyID]->pri, 'class=form-control');?></td>
        <td><?php echo html::select("sources[$storyID]",  $sourceList, $stories[$storyID]->source, 'class=form-control');?></td>
        <td class='story-<?php echo $stories[$storyID]->status;?>'><?php echo $lang->story->statusList[$stories[$storyID]->status];?></td>

        <?php if($stories[$storyID]->status != 'draft'):?>
        <td><?php echo html::select("stages[$storyID]", $stageList, $stories[$storyID]->stage, 'class=form-control');?></td>
        <?php else:?>
        <td><?php echo html::select("stages[$storyID]", $stageList, $stories[$storyID]->stage, 'class="form-control" disabled="disabled"');?></td>
        <?php endif;?>

        <?php if($stories[$storyID]->status == 'closed'):?>
        <td class='text-left' style='overflow:visible'><?php echo html::select("closedBys[$storyID]",     $users, $stories[$storyID]->closedBy, "class='form-control chosen'");?></td>
        <?php else:?>
        <td class='text-left'><?php echo html::select("closedBys[$storyID]",     $users, $stories[$storyID]->closedBy, 'class="form-control" disabled="disabled"');?></td>
        <?php endif;?>

        <?php if($stories[$storyID]->status == 'closed'):?>
        <td>
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
        <td><?php echo html::select("closedReasons[$storyID]", $reasonList, $stories[$storyID]->closedReason, 'class="form-control" disabled="disabled"');?></td>
        <?php endif;?>
      </tr>
      <?php endforeach;?>
      <?php if(isset($suhosinInfo)):?>
      <tr><td colspan='<?php echo $this->config->story->batchEdit->columns;?>'><div id='suhosinInfo' class='alert alert-info'><?php echo $suhosinInfo;?></div></td></tr>
      <?php endif;?>
    </tbody>
    <tfoot>
      <tr><td colspan='<?php echo $this->config->story->batchEdit->columns;?>' class='text-center'><?php echo html::submitButton();?></td></tr>
    </tfoot>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
