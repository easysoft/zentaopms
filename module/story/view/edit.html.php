<?php
/**
 * The edit view file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include './header.html.php';?>
<form method='post' enctype='multipart/form-data' target='hiddenwin' id='dataform'>
<div id='titlebar'>
  <div id='main'>STORY #<?php echo $story->id . $lang->colon . $story->title;?></div>
  <div><?php echo html::submitButton()?></div>
</div>

<table class='cont-rt5'>
  <tr valign='top'>
    <td>
      <fieldset>
        <legend><?php echo $lang->story->legendSpec;?></legend>
        <div class='content'><?php echo $story->spec;?></div>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->story->verify;?></legend>
        <div><?php echo htmlspecialchars($story->verify);?></div>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->story->comment;?></legend>
        <?php echo html::textarea('comment', '', "rows='5' class='area-1'");?>
      </fieldset>
      <div class='a-center'>
        <?php 
        echo html::submitButton();
        echo html::linkButton($lang->goback, $app->session->storyList ? $app->session->storyList : inlink('view', "storyID=$story->id"));
        ?>
      </div>
      <?php include '../../common/view/action.html.php';?>
    </td>
    <td class='divider'></td>
    <td class='side'>
      <fieldset>
        <legend><?php echo $lang->story->legendBasicInfo;?></legend>
        <table class='table-1'>
          <tr>
            <td class='rowhead w-p20'><?php echo $lang->story->product;?></td>
            <td><?php echo html::select('product', $products, $story->product, 'class="select-1" onchange="loadProduct(this.value)";');?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->story->module;?></td>
            <td><span id='moduleIdBox'><?php echo html::select('module', $moduleOptionMenu, $story->module, 'class="select-1"');?></span></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->story->plan;?></td>
            <td><span id='planIdBox'><?php echo html::select('plan', $plans, $story->plan, 'class=select-1');?></span></td>
          </tr>
          <tr>
            <th class='rowhead'><?php echo $lang->story->source;?></th>
            <td><?php echo html::select('source', $lang->story->sourceList, $story->source, 'class=select-1');?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->story->status;?></td>
            <td><?php echo $lang->story->statusList[$story->status];?></td>
          </tr>
          <?php if($story->status != 'draft'):?>
          <tr>
            <td class='rowhead'><?php echo $lang->story->stage;?></td>
            <td><?php echo html::select('stage', $lang->story->stageList, $story->stage, 'class=select-1');?></td>
          </tr>
          <?php endif;?>
          <tr>
            <td class='rowhead'><?php echo $lang->story->pri;?></td>
            <td><?php echo html::select('pri', $lang->story->priList, $story->pri, 'class=select-1');?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->story->estimate;?></td>
            <td><?php echo html::input('estimate', $story->estimate, "class='text-1'");?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->story->keywords;?></td>
            <td><?php echo html::input('keywords', $story->keywords, 'class=text-1');?></td>
          </tr>
        </table>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->story->legendLifeTime;?></legend>
        <table class='table-1'>
          <tr>
            <td class='rowhead w-p20'><?php echo $lang->story->openedBy;?></td>
            <td><?php echo $users[$story->openedBy];?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->story->assignedTo;?></td>
            <td><?php echo html::select('assignedTo', $users, $story->assignedTo, 'class="select-1"');?></td>
          </tr>
          <?php if($story->reviewedBy):?>
          <tr>
            <td class='rowhead'><?php echo $lang->story->reviewedBy;?></td>
            <td><?php echo html::textarea('reviewedBy', $story->reviewedBy, 'class="area-1"');?></td>
          </tr>
          <?php endif;?>
          <?php if($story->status == 'closed'):?>
          <tr>
            <td class='rowhead'><?php echo $lang->story->closedBy;?></td>
            <td><?php echo html::select('closedBy', $users, $story->closedBy, 'class="select-1"');?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->story->closedReason;?></td>
            <td><?php echo html::select('closedReason', $lang->story->reasonList, $story->closedReason, 'class="select-1"');?></td>
          </tr>
          <?php endif;?>
        </table>
      </fieldset>

      <fieldset>
        <legend><?php echo $lang->story->legendMisc;?></legend>
        <table class='table-1'>
          <tr>
            <td class='rowhead w-p20'><?php echo $lang->story->duplicateStory;?></td>
            <td><?php echo html::input('duplicateStory', $story->duplicateStory, "class='text-1'");?></td>
          </tr>
          <tr>
            <td class='rowhead w-p20'><?php echo $lang->story->linkStories;?></td>
            <td><?php echo html::input('linkStories', $story->linkStories, "class='text-1'");?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->story->childStories;?></td>
            <td><?php echo html::input('childStories', $story->childStories, "class='text-1'");?></td>
          </tr>
          <tr>
            <td class='rowhead'><?php echo $lang->story->mailto;?></td>
            <td><?php echo html::textarea('mailto', $story->mailto, "class='area-1'");?></td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
</table>
</form>
<?php include '../../common/view/footer.html.php';?>
