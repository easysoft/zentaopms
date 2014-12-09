<?php
/**
 * The edit file of case module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     case
 * @version     $Id: edit.html.php 5000 2013-07-03 08:20:57Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('lblDelete', $lang->testcase->deleteStep);?>
<?php js::set('lblBefore', $lang->testcase->insertBefore);?>
<?php js::set('lblAfter',  $lang->testcase->insertAfter);?>
<form class='form-condensed' method='post' enctype='multipart/form-data' target='hiddenwin' id='dataform'>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['testcase']);?> <strong><?php echo $case->id;?></strong></span>
    <strong><?php echo html::a($this->createLink('testcase', 'view', "caseID=$case->id"), $case->title);?></strong>
    <small><?php echo $lang->case->edit;?></small>
  </div>
  <div class='actions'>
    <?php echo html::submitButton($lang->save)?>
  </div>
</div>
<div class='row-table'>
  <div class='col-main'>
    <div class='main'>
      <div class='form-group'>
        <?php echo html::input('title', $case->title, 'class="form-control" placeholder="' . $lang->case->title . '"');?>
      </div>
      <fieldset class='fieldset-pure'>
        <legend><?php echo $lang->testcase->precondition;?></legend>
        <div class='form-group'><?php echo html::textarea('precondition', $case->precondition, "rows='4' class='form-control'");?></div>
      </fieldset>
      <fieldset class='fieldset-pure'>
        <legend><?php echo $lang->testcase->steps;?></legend>
        <div class='form-group'>
          <table class='table table-form'>
            <thead>
              <tr>
                <th class='w-40px'><?php echo $lang->testcase->stepID;?></th>
                <th><?php echo $lang->testcase->stepDesc;?></th>
                <th><?php echo $lang->testcase->stepExpect;?></th>
                <th class='w-100px'><?php echo $lang->actions;?></th>
              </tr>
            </thead>
            <?php
            foreach($case->steps as $stepID => $step)
            {
                $stepID += 1;
                echo "<tr id='row$stepID' class='text-center'>";
                echo "<td class='stepID strong'>$stepID</td>";
                echo '<td class="w-p50">' . html::textarea('steps[]', $step->desc, "rows='3' class='form-control'") . '</td>';
                echo '<td>' . html::textarea('expects[]', $step->expect, "rows='3' class='form-control'") . '</td>';
                echo "<td class='a-left w-100px'><div class='btn-group-vertical'>";
                echo "<input type='button' tabindex='-1' class='addbutton btn' onclick='preInsert($stepID)'  value='{$lang->testcase->insertBefore}' />";
                echo "<input type='button' tabindex='-1' class='addbutton btn' onclick='postInsert($stepID)' value='{$lang->testcase->insertAfter}'  /> ";
                echo "<input type='button' tabindex='-1' class='delbutton btn' onclick='deleteRow($stepID)'  value='{$lang->testcase->deleteStep}'   /> ";
                echo "</div></td>";
                echo '</tr>';
            }
            ?>
          </table>
        </div>
      </fieldset>
      <fieldset class='fieldset-pure'>
        <legend><?php echo $lang->testcase->legendComment;?></legend>
        <div class='form-group'><?php echo html::textarea('comment', '',  "rows='5' class='form-control'");?></div>
      </fieldset>
      <fieldset class='fieldset-pure'>
        <legend><?php echo $lang->testcase->legendAttatch;?></legend>
        <div class='form-group'><?php echo $this->fetch('file', 'buildform', 'filecount=2');?></div>
      </fieldset>
      <div class='text-center mgb-20'>
       <?php echo html::submitButton();?>
       <input type='button' value='<?php echo $lang->testcase->buttonToList;?>' class='btn' onclick='location.href="<?php echo $this->createLink('testcase', 'browse', "productID=$productID");?>"' />
      </div>
      <?php include '../../common/view/action.html.php';?>
    </div>
  </div>
  <div class='col-side'>
    <div class='main main-side'>
      <fieldset>
        <legend><?php echo $lang->testcase->legendBasicInfo;?></legend>
        <table class='table table-form' cellpadding='0' cellspacing='0'>
          <tr>
            <th class='w-80px'><?php echo $lang->testcase->product;?></th>
            <td><?php echo html::select('product', $products, $productID, "onchange=loadAll(this.value); class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->testcase->module;?></th>
            <td>
              <div class='input-group' id='moduleIdBox'>
                <?php echo html::select('module', $moduleOptionMenu, $currentModuleID, "onchange='loadModuleRelated()' class='form-control chosen'");?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->testcase->story;?></th>
            <td class='text-left'><div id='storyIdBox'><?php echo html::select('story', $stories, $case->story, 'class=form-control chosen');?></div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->testcase->type;?></th>
            <td><?php echo html::select('type', (array)$lang->testcase->typeList, $case->type, 'class=form-control');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->testcase->stage;?></th>
            <td><?php echo html::select('stage[]', $lang->testcase->stageList, $case->stage, "class='form-control chosen' multiple='multiple'");?></td>
          </tr>  
          <tr>
            <th><?php echo $lang->testcase->pri;?></th>
            <td><?php echo html::select('pri', (array)$lang->testcase->priList, $case->pri, 'class=form-control');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->testcase->status;?></th>
            <td><?php echo html::select('status', (array)$lang->testcase->statusList, $case->status, 'class=form-control');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->testcase->keywords;?></th>
            <td><?php echo html::input('keywords', $case->keywords, 'class=form-control');?></td>
          </tr>
          <tr>
            <th><?php echo $lang->testcase->linkCase;?></th>
            <td><?php echo html::input('linkCase', $case->linkCase, 'class=form-control');?></td>
          </tr>
        </table>
      </fieldset>
      <fieldset>
        <legend><?php echo $lang->testcase->legendOpenAndEdit;?></legend>
        <table class='table table-form'>
          <tr>
            <th class='w-80px'><?php echo $lang->testcase->openedBy;?></th>
            <td><?php echo $users[$case->openedBy] . $lang->at . $case->openedDate;?></td>
          </tr>
          <tr>
            <th><?php echo $lang->testcase->lblLastEdited;?></th>
            <td><?php if($case->lastEditedBy) echo $users[$case->lastEditedBy] . $lang->at . $case->lastEditedDate;?></td>
          </tr>
        </table>
      </fieldset>
    </div>
  </div>
</div>
</form>
<?php include '../../common/view/footer.html.php';?>
