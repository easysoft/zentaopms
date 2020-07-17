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
<?php js::set('caseID', $case->id);?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2>
      <span class='label label-id'><?php echo $case->id;?></span>
      <?php echo html::a($this->createLink('testcase', 'view', "caseID=$case->id"), $case->title, '', 'class="case-title"');?>
    </h2>
  </div>
  <form method='post' enctype='multipart/form-data' target='hiddenwin' id='dataform'>
    <div class='main-row'>
      <div class='main-col col-8'>
        <div class='cell'>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->testcase->title;?></div>
            <div class="detail-content">
              <div class="input-control has-icon-right">
                <?php echo html::input('title', $case->title, 'class="form-control" placeholder="' . $lang->case->title . '"');?>
                <div class="colorpicker">
                  <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                  <ul class="dropdown-menu clearfix">
                    <li class="heading"><?php echo $lang->testcase->colorTag;?><i class="icon icon-close"></i></li>
                  </ul>
                  <input type="hidden" class="colorpicker" id="color" name="color" value="<?php echo $case->color ?>" data-icon="color" data-wrapper="input-control-icon-right" data-update-color="#title"  data-provide="colorpicker">
                </div>
              </div>
            </div>
          </div>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->testcase->precondition;?></div>
            <div class='detail-content'><?php echo html::textarea('precondition', $case->precondition, "rows='2' class='form-control'");?></div>
          </div>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->testcase->steps;?></div>
            <div class='detail-content'>
              <table class='table table-form table-bordered'>
                <thead>
                  <tr>
                    <th class='w-50px'><?php echo $lang->testcase->stepID;?></th>
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
                          <div class='checkbox-primary'>
                            <input tabindex='-1' type="checkbox" class='step-group-toggle'>
                            <label><?php echo $lang->testcase->group ?></label>
                          </div>
                        </span>
                      </div>
                    </td>
                    <td><textarea rows='1' class='form-control autosize step-expects' name='expects[]'></textarea></td>
                    <td class='step-actions'>
                      <div class='btn-group'>
                        <button type='button' class='btn btn-step-add' tabindex='-1'><i class='icon icon-plus'></i></button>
                        <button type='button' class='btn btn-step-move' tabindex='-1'><i class='icon icon-move'></i></button>
                        <button type='button' class='btn btn-step-delete' tabindex='-1'><i class='icon icon-close'></i></button>
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
                          <div class='checkbox-primary'>
                            <input tabindex='-1' tabindex='-1' type="checkbox" class='step-group-toggle'<?php if($step->type === 'group') echo ' checked' ?> />
                            <label><?php echo $lang->testcase->group ?></label>
                          </div>
                        </span>
                      </div>
                    </td>
                    <td><?php echo html::textarea('expects[]', $step->expect, "rows='1' class='form-control autosize step-expects'") ?></td>
                    <td class='step-actions'>
                      <div class='btn-group'>
                        <button type='button' class='btn btn-step-add' tabindex='-1'><i class='icon icon-plus'></i></button>
                        <button type='button' class='btn btn-step-move' tabindex='-1'><i class='icon icon-move'></i></button>
                        <button type='button' class='btn btn-step-delete' tabindex='-1'><i class='icon icon-close'></i></button>
                      </div>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
          <?php $this->printExtendFields($case, 'div', 'position=left');?>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->testcase->legendComment;?></div>
            <div class='detail-content'><?php echo html::textarea('comment', '',  "rows='5' class='form-control'");?></div>
          </div>
          <div class="detail">
            <div class="detail-title"><?php echo $lang->files;?></div>
            <div class='detail-content'><?php echo $this->fetch('file', 'buildform');?></div>
          </div>
          <div class='text-center detail form-actions'>
            <?php echo html::hidden('lastEditedDate', $case->lastEditedDate);?>
            <?php echo html::submitButton(). html::backButton();;?>
          </div>
          <?php include '../../common/view/action.html.php';?>
        </div>
      </div>
      <div class='side-col col-4'>
        <div class='cell'>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->testcase->legendBasicInfo;?></div>
            <table class='table table-form' cellpadding='0' cellspacing='0'>
              <?php if($isLibCase):?>
              <tr>
                <th class='w-100px'><?php echo $lang->testcase->lib;?></th>
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
                      echo html::a($this->createLink('tree', 'browse', "rootID=$libID&view=caselib&currentModuleID=0&branch=$case->branch", '', true), $lang->tree->manage, '', "class='text-primary' data-toggle='modal' data-type='iframe' data-width='95%'");
                      echo '&nbsp; ';
                      echo html::a("javascript:void(0)", $lang->refresh, '', "class='refresh' onclick='loadLibModules($libID)'");
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
                      echo html::a($this->createLink('tree', 'browse', "rootID=$productID&view=case&currentModuleID=0&branch=$case->branch", '', true), $lang->tree->manage, '', "class='text-primary' data-toggle='modal' data-type='iframe' data-width='95%'");
                      echo '&nbsp; ';
                      echo html::a("javascript:void(0)", $lang->refresh, '', "class='refresh' onclick='loadProductModules($productID)'");

                      echo '</span>';
                  }
                  ?>
                  </div>
                </td>
              </tr>
              <?php endif;?>
              <?php if(!$isLibCase and $config->global->flow != 'onlyTest'):?>
              <tr>
                <th><?php echo $lang->testcase->story;?></th>
                <td class='text-left'><div id='storyIdBox'><?php echo html::select('story', $stories, $case->story, 'class=form-control chosen');?></div>
                </td>
              </tr>
              <?php endif;?>
              <tr>
                <th><?php echo $lang->testcase->type;?></th>
                <td><?php echo html::select('type', (array)$lang->testcase->typeList, $case->type, "class='form-control chosen'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->testcase->stage;?></th>
                <td><?php echo html::select('stage[]', $lang->testcase->stageList, $case->stage, "class='form-control chosen' multiple='multiple'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->testcase->pri;?></th>
                <td><?php echo html::select('pri', (array)$lang->testcase->priList, $case->pri, "class='form-control chosen'");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->testcase->status;?></th>
                <?php if(!$forceNotReview and $case->status == 'wait'):?>
                <td><?php echo $lang->testcase->statusList[$case->status];?>
                <?php else: ?>
                <td><?php echo html::select('status', (array)$lang->testcase->statusList, $case->status, "class='form-control chosen'");?></td>
                <?php endif; ?>
              </tr>
              <tr>
                <th><?php echo $lang->testcase->keywords;?></th>
                <td><?php echo html::input('keywords', $case->keywords, "class='form-control'");?></td>
              </tr>
              <?php if(!$isLibCase):?>
              <tr>
                <th><?php echo $lang->testcase->linkCase;?></th>
                <td><?php echo html::a($this->createLink('testcase', 'linkCases', "caseID=$case->id", '', true), $lang->testcase->linkCases, '', "data-type='iframe' data-toggle='modal' data-width='95%'");?></td>
              </tr>
              <tr>
                <th></th>
                <td>
                  <ul class='list-unstyled'>
                    <?php
                    if(isset($case->linkCaseTitles))
                    {
                        foreach($case->linkCaseTitles as $linkCaseID => $linkCaseTitle)
                        {
                            echo "<li><div class='checkbox-primary'>";
                            echo "<input type='checkbox' checked='checked' name='linkCase[]' value=$linkCaseID />";
                            echo "<label>#{$linkCaseID} {$linkCaseTitle}</label>";
                            echo '</div></li>';
                        }
                    }
                    ?>
                    <span id='linkCaseBox'></span>
                  </ul>
                </td>
              </tr>
              <?php endif;?>
            </table>
          </div>
          <?php $this->printExtendFields($case, 'div', 'position=right');?>
          <div class='detail'>
            <div class='detail-title'><?php echo $lang->testcase->legendOpenAndEdit;?></div>
            <table class='table table-form'>
              <tr>
                <th class='w-80px'><?php echo $lang->testcase->openedBy;?></th>
                <td><?php echo zget($users, $case->openedBy) . $lang->at . $case->openedDate;?></td>
              </tr>
              <tr>
                <th><?php echo $lang->testcase->lblLastEdited;?></th>
                <td><?php if($case->lastEditedBy) echo zget($users, $case->lastEditedBy) . $lang->at . $case->lastEditedDate;?></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
