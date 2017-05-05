<?php
/**
 * The edit file of case module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
<form method='post' class='form-condensed' enctype='multipart/form-data' target='hiddenwin' id='dataform'>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['testcase']);?> <strong><?php echo $case->id;?></strong></span>
    <strong><?php echo html::a($this->createLink('testcase', 'view', "caseID=$case->id"), $case->title, '', 'class="case-title"');?></strong>
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
        <div class='input-group'>
          <input type='hidden' id='color' name='color' data-provide='colorpicker' data-wrapper='input-group-btn fix-border-right' data-pull-menu-right='false' data-btn-tip='<?php echo $lang->case->colorTag ?>' value='<?php echo $case->color ?>' data-update-text='#title, .case-title'>
          <?php echo html::input('title', $case->title, 'class="form-control" autocomplete="off" placeholder="' . $lang->case->title . '"');?>
        </div>
      </div>
      <fieldset class='fieldset-pure'>
        <legend><?php echo $lang->testcase->precondition;?></legend>
        <div class='form-group'><?php echo html::textarea('precondition', $case->precondition, "rows='4' class='form-control'");?></div>
      </fieldset>
      <fieldset class='fieldset-pure'>
        <legend><?php echo $lang->testcase->steps;?></legend>
        <div class='form-group'>
          <table class='table table-form table-bordered'>
            <thead>
              <tr>
                <th class='w-40px'><?php echo $lang->testcase->stepID;?></th>
                <th width="45%"><?php echo $lang->testcase->stepDesc;?></th>
                <th><?php echo $lang->testcase->stepExpect;?></th>
                <th class='step-actions'><?php echo $lang->actions;?></th>
              </tr>
            </thead>
            <tbody id='steps' class='sortable' data-group-name='<?php echo $lang->testcase->groupName ?>'>
              <tr class='template step' id='stepTemplate'>
                <td class='step-id'></td>
                <td>
                  <div class='input-group'>
                    <span class='input-group-addon step-item-id'></span>
                    <textarea rows='1' class='form-control autosize step-steps' name='steps[]'></textarea>
                    <span class="input-group-addon step-type-toggle">
                      <input type='hidden' name='stepType[]' value='item' class='step-type'>
                      <label class="checkbox-inline"><input tabindex='-1' type="checkbox" class='step-group-toggle'> <?php echo $lang->testcase->group ?></label>
                    </span>
                  </div>
                </td>
                <td><textarea rows='1' class='form-control autosize step-expects' name='expects[]'></textarea></td>
                <td class='step-actions'>
                  <div class='btn-group'>
                    <button type='button' class='btn btn-step-add' tabindex='-1'><i class='icon icon-plus'></i></button>
                    <button type='button' class='btn btn-step-move' tabindex='-1'><i class='icon icon-move'></i></button>
                    <button type='button' class='btn btn-step-delete' tabindex='-1'><i class='icon icon-remove'></i></button>
                  </div>
                </td>
              </tr>
              <?php foreach($case->steps as $stepID => $step):?>
              <tr class='step'>
                <td class='step-id'></td>
                <td>
                  <div class='input-group'>
                    <span class='input-group-addon step-item-id'></span>
                    <?php echo html::textarea('steps[]', $step->desc, "rows='1' class='form-control autosize step-steps'") ?>
                    <span class='input-group-addon step-type-toggle'>
                      <?php if(!isset($step->type)) $step->type = 'step';?>
                      <input type='hidden' name='stepType[]' value='<?php echo $step->type;?>' class='step-type'>
                      <label class="checkbox-inline"><input tabindex='-1' tabindex='-1' type="checkbox" class='step-group-toggle'<?php if($step->type === 'group') echo ' checked' ?>> <?php echo $lang->testcase->group ?></label>
                    </span>
                  </div>
                </td>
                <td><?php echo html::textarea('expects[]', $step->expect, "rows='1' class='form-control autosize step-expects'") ?></td>
                <td class='step-actions'>
                  <div class='btn-group'>
                    <button type='button' class='btn btn-step-add' tabindex='-1'><i class='icon icon-plus'></i></button>
                    <button type='button' class='btn btn-step-move' tabindex='-1'><i class='icon icon-move'></i></button>
                    <button type='button' class='btn btn-step-delete' tabindex='-1'><i class='icon icon-remove'></i></button>
                  </div>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </fieldset>
      <fieldset class='fieldset-pure'>
        <legend><?php echo $lang->testcase->legendComment;?></legend>
        <div class='form-group'><?php echo html::textarea('comment', '',  "rows='5' class='form-control'");?></div>
      </fieldset>
      <fieldset class='fieldset-pure'>
        <legend><?php echo $lang->testcase->legendAttatch;?></legend>
        <div class='form-group'><?php echo $this->fetch('file', 'buildform');?></div>
      </fieldset>
      <div class='text-center mgb-20'>
        <?php echo html::hidden('lastEditedDate', $case->lastEditedDate);?>
        <?php echo html::submitButton();?>
        <input type='button' value='<?php echo $lang->testcase->buttonToList;?>' class='btn' onclick='location.href="<?php echo $isLibCase ? $this->createLink('testsuite', 'library', "libID=$libID") : $this->createLink('testcase', 'browse', "productID=$productID");?>"' />
      </div>
      <?php include '../../common/view/action.html.php';?>
    </div>
  </div>
  <div class='col-side'>
    <div class='main main-side'>
      <fieldset>
        <legend><?php echo $lang->testcase->legendBasicInfo;?></legend>
        <table class='table table-form' cellpadding='0' cellspacing='0'>
          <?php if($isLibCase):?>
          <tr>
            <th class='w-80px'><?php echo $lang->testcase->lib;?></th>
            <td>
              <div class='input-group'>
                <?php echo html::select('lib', $libraries, $libID , "onchange='loadLibModules(this.value)' class='form-control chosen'");?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->testcase->module;?></th>
            <td>
              <div class='input-group' id='moduleIdBox'>
              <?php 
              echo html::select('module', $moduleOptionMenu, $currentModuleID, "onchange='loadModuleRelated()' class='form-control chosen'");
              if(count($moduleOptionMenu) == 1)
              {
                  echo "<span class='input-group-addon'>";
                  echo html::a($this->createLink('tree', 'browse', "rootID=$libID&view=caselib&currentModuleID=0&branch=$case->branch"), $lang->tree->manage, '_blank');
                  echo '&nbsp; ';
                  echo html::a("javascript:loadLibModules($libID)", $lang->refresh);
                  echo '</span>';
              }
              ?>
              </div>
            </td>
          </tr>
          <?php else:?>
          <tr>
            <th class='w-80px'><?php echo $lang->testcase->product;?></th>
            <td>
              <div class='input-group'>
                <?php echo html::select('product', $products, $productID, "onchange='loadAll(this.value)' class='form-control chosen'");?>
                <?php if($this->session->currentProductType != 'normal') echo html::select('branch', $branches, $case->branch, "onchange='loadBranch();' class='form-control' style='width:65px'");?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->testcase->module;?></th>
            <td>
              <div class='input-group' id='moduleIdBox'>
              <?php 
              echo html::select('module', $moduleOptionMenu, $currentModuleID, "onchange='loadModuleRelated()' class='form-control chosen'");
              if(count($moduleOptionMenu) == 1)
              {
                  echo "<span class='input-group-addon'>";
                  echo html::a($this->createLink('tree', 'browse', "rootID=$productID&view=case&currentModuleID=0&branch=$case->branch"), $lang->tree->manage, '_blank');
                  echo '&nbsp; ';
                  echo html::a("javascript:loadProductModules($productID)", $lang->refresh);
                  echo '</span>';
              }
              ?>
              </div>
            </td>
          </tr>
          <?php endif;?>
          <?php if(!$isLibCase and $this->config->global->flow != 'onlyTest'):?>
          <tr>
            <th><?php echo $lang->testcase->story;?></th>
            <td class='text-left'><div id='storyIdBox'><?php echo html::select('story', $stories, $case->story, 'class=form-control chosen');?></div>
            </td>
          </tr>
          <?php endif;?>
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
            <td><?php echo html::input('keywords', $case->keywords, "class='form-control' autocomplete='off'");?></td>
          </tr>
          <?php if(!$isLibCase):?>
          <tr class='text-top'>
            <th><?php echo $lang->testcase->linkCase;?></th>
            <td>
              <?php echo html::a($this->createLink('testcase', 'linkCases', "caseID=$case->id", '', true), $lang->testcase->linkCases, '', "data-type='iframe' data-toggle='modal' data-width='95%'");?>
              <ul class='list-unstyled' id='linkCaseBox'>
              <?php
              if(isset($case->linkCaseTitles))
              {
                  foreach($case->linkCaseTitles as $linkCaseID => $linkCaseTitle)
                  {
                      echo '<li>';
                      echo html::a(inlink('view', "caseID=$linkCaseID"), "#$linkCaseID " . $linkCaseTitle, '_blank');
                      echo html::a("javascript:unlinkCase($case->id, $linkCaseID)", '<i class="icon-remove"></i>', '', "title='{$lang->unlink}' style='float:right'");
                      echo '</li>';
                  }
              }
              ?>
              </ul>
            </td>
          </tr>
          <?php endif;?>
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
