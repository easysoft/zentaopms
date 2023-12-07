<?php
/**
 * The create view of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: create.html.php 4903 2013-06-26 05:32:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
include '../../common/view/header.html.php';
include '../../common/view/kindeditor.html.php';
include '../../common/view/datepicker.html.php';
js::set('holders', $lang->bug->placeholder);
js::set('page', 'create');
js::set('createRelease', $lang->release->create);
js::set('createBuild', $lang->build->create);
js::set('refresh', $lang->refreshIcon);
js::set('flow', $config->global->flow);
js::set('stepsRequired', $stepsRequired);
js::set('stepsNotEmpty', $lang->bug->stepsNotEmpty);
js::set('isStepsTemplate', $isStepsTemplate);
js::set('oldProjectID', $projectID);
js::set('blockID', $blockID);
js::set('moduleID', $moduleID);
js::set('tab', $this->app->tab);
js::set('requiredFields', $config->bug->create->requiredFields);
js::set('showFields', $showFields);
js::set('projectExecutionPairs', $projectExecutionPairs);
js::set('productID', $productID);
js::set('released', $lang->build->released);
if($this->app->tab == 'execution') js::set('objectID', zget($execution, 'id', ''));
if($this->app->tab == 'project')   js::set('objectID', $projectID);
?>
<div id="mainContent" class="main-content fade">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->bug->create;?></h2>
      <div class="pull-right btn-toolbar">
        <?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=bug&section=custom&key=createFields')?>
        <?php include '../../common/view/customfield.html.php';?>
      </div>
    </div>
    <?php
    foreach(explode(',', $config->bug->create->requiredFields) as $field)
    {
        if($field and strpos($showFields, $field) === false) $showFields .= ',' . $field;
    }
    ?>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th class='w-110px'><?php echo ($product->shadow and $this->app->tab != 'feedback') ? $lang->bug->module : $lang->bug->product;?></th>
            <td class="<?php if($product->shadow and $this->app->tab != 'feedback') echo 'hidden';?>">
              <div class='input-group'>
                <?php echo html::select('product', $products, $productID, "onchange='loadAll(this.value);' class='form-control chosen control-product'");?>
                <?php if($product->type != 'normal' and isset($products[$productID])):?>
                <?php  echo html::select('branch', $branches, $branch, "onchange='loadBranch()' class='form-control chosen control-branch'");?>
                <?php endif;?>
              </div>
            </td>
            <td>
              <div class='input-group' id='moduleIdBox'>
              <?php if(!$product->shadow or $this->app->tab == 'feedback'):?>
              <span class="input-group-addon"><?php echo $lang->bug->module?></span>
              <?php endif;?>
                <?php
                echo html::select('module', $moduleOptionMenu, $moduleID, "onchange='loadModuleRelated()' class='form-control chosen'");
                if(count($moduleOptionMenu) == 1)
                {
                    echo "<span class='input-group-addon'>";
                    echo html::a($this->createLink('tree', 'browse', "rootID=$productID&view=bug&currentModuleID=0&branch=$branch", '', true), $lang->tree->manage, '', "class='text-primary' data-toggle='modal' data-type='iframe' data-width='95%'");
                    echo '&nbsp; ';
                    echo html::a("javascript:void(0)", $lang->refreshIcon, '', "class='refresh' title='$lang->refresh' onclick='loadProductModules($productID)'");
                    echo '</span>';
                }
                ?>
              </div>
            </td>
          </tr>
          <?php $showExecution = (strpos(",$showFields,", ',execution,') !== false);?>
          <tr>
            <th><?php echo $lang->bug->project?></th>
            <td>
              <div class='table-row'>
                <div class='table-col' id='projectBox'>
                  <?php echo html::select('project', $projects, $projectID, "class='form-control chosen' onchange='loadProductExecutions({$productID}, this.value)'");?>
                </div>
                <?php $executionClass = !$showExecution ? 'hidden' : '';?>
                <div class="table-col executionBox <?php echo $executionClass;?>">
                  <div class='input-group' id='executionIdBox'>
                    <span class='input-group-addon fix-border' id='executionBox'><?php echo $projectModel == 'kanban' ? $lang->bug->kanban : $lang->bug->execution;?></span>
                    <?php echo html::select('execution', $executions, zget($execution, 'id', ''), "class='form-control chosen' onchange='loadExecutionRelated(this.value)'");?>
                  </div>
                </div>
              </div>
            </td>
            <td>
              <div class='input-group' id='buildBox'>
                <span class="input-group-addon"><?php echo $lang->bug->openedBuild?></span>
                <?php echo html::select('openedBuild[]', $builds, empty($buildID) ? '' : $buildID, "multiple=multiple class='picker-select form-control' data-items='" . count($builds) . "'");?>
                <span class='input-group-addon fix-border' id='buildBoxActions'></span>
                <div class='input-group-btn'><?php echo html::commonButton($lang->bug->allBuilds, "class='btn' id='all' data-toggle='tooltip' onclick='loadAllBuilds()'")?></div>
              </div>
            </td>
          </tr>
          <tr>
            <th><nobr><?php echo $lang->bug->lblAssignedTo;?></nobr></th>
            <td>
              <div class='input-group'>
                <?php echo html::select('assignedTo', $productMembers, $assignedTo, "class='form-control chosen'");?>
                <span class='input-group-btn'><?php echo html::commonButton($lang->bug->allUsers, "class='btn btn-default' onclick='loadAllUsers()' data-toggle='tooltip'");?></span>
              </div>
            </td>
          <?php $showDeadline = strpos(",$showFields,", ',deadline,') !== false;?>
          <?php $showNoticefeedbackBy = strpos(",$showFields,", ',noticefeedbackBy,') !== false;?>
          <?php if($showDeadline):?>
            <td id='deadlineTd'>
              <div class='input-group'>
                <span class='input-group-addon'><?php echo $lang->bug->deadline?></span>
                <span><?php echo html::input('deadline', $deadline, "class='form-control form-date'");?></span>
              </div>
            </td>
          </tr>
          <?php if($showNoticefeedbackBy):?>
          <tr>
            <th><nobr><?php echo $lang->bug->feedbackBy;?></nobr></th>
            <td><?php echo html::input('feedbackBy', isset($feedbackBy) ? $feedbackBy : '', "class='form-control'");?></td>
            <td id='notifyEmailTd'>
              <div class='input-group'>
                <span class='input-group-addon'><?php echo $lang->bug->notifyEmail?></span>
                <span><?php echo html::input('notifyEmail', isset($notifyEmail) ? $notifyEmail : '', "class='form-control'");?></span>
              </div>
            </td>
          </tr>
          <?php endif;?>
          <?php elseif($showNoticefeedbackBy):?>
            <td>
              <div class='input-group' id='feedback'>
              <span class="input-group-addon"><?php echo $lang->bug->feedbackBy?></span>
              <?php echo html::input('feedbackBy', $feedbackBy, "class='form-control'");?>
              <span class="input-group-addon"><?php echo $lang->bug->notifyEmail?></span>
              <?php echo html::input('notifyEmail', $notifyEmail, "class='form-control'");?>
              </div>
            </td>
          </tr>
          <?php endif;?>
          <?php $showOS      = strpos(",$showFields,", ',os,')      !== false;?>
          <?php $showBrowser = strpos(",$showFields,", ',browser,') !== false;?>
          <tr class='bug-type'>
            <th><?php echo $lang->bug->type;?></th>
            <td>
              <div class='table-row'>
                <div class='table-col' id='typeBox'>
                  <?php echo html::select('type', $lang->bug->typeList, $type, "class='form-control chosen'");?>
                </div>
                <?php if($showOS):?>
                <div class='table-col' id='osBox'>
                  <div class='input-group'>
                    <span class='input-group-addon fix-border'><?php echo $lang->bug->os?></span>
                    <?php echo html::select('os[]', $lang->bug->osList, $os, "class='form-control chosen' multiple");?>
                  </div>
                </div>
                <?php endif;?>
                <?php if($showBrowser):?>
                <div class='table-col'>
                  <div class='input-group'>
                    <span class='input-group-addon fix-border'><?php echo $lang->bug->browser?></span>
                    <?php echo html::select('browser[]', $lang->bug->browserList, $browser, "class='form-control chosen' multiple");?>
                  </div>
                </div>
                <?php endif;?>
              </div>
            </td>
          </tr>
          <?php if(isset($executionType) and $executionType == 'kanban'):?>
          <tr>
            <th><?php echo $lang->kanbancard->region;?></th>
            <td><?php echo html::select('region', $regionPairs, $regionID, "onchange='setLane(this.value)' class='form-control chosen'");?></td>
            <td>
              <div class='input-group'>
                <div class="input-group-addon"><?php echo $lang->kanbancard->lane;?></div>
                <?php echo html::select('lane', $lanePairs, $laneID, "class='form-control chosen'");?>
              </div>
            </td>
          </tr>
          <?php endif;?>
          <tr>
            <th><?php echo $lang->bug->title;?></th>
            <td colspan='2'>
              <div class="input-group title-group">
                <div class="input-control has-icon-right">
                  <?php echo html::input('title', $bugTitle, "class='form-control' required");?>
                  <div class="colorpicker">
                    <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                    <ul class="dropdown-menu clearfix">
                      <li class="heading"><?php echo $lang->story->colorTag;?><i class="icon icon-close"></i></li>
                    </ul>
                    <input type="hidden" class="colorpicker" id="color" name="color" value="" data-icon="color" data-wrapper="input-control-icon-right" data-update-color="#title"  data-provide="colorpicker">
                  </div>
                </div>
                <?php if(strpos(",$showFields,", ',severity,') !== false): // begin print severity selector ?>
                <span class="input-group-addon fix-border br-0"><?php echo $lang->bug->severity;?></span>
                <?php
                $hasCustomSeverity = false;
                foreach($lang->bug->severityList as $severityKey => $severityValue)
                {
                    if(empty($severityValue)) unset($lang->bug->severityList[$severityKey]);
                    if(!empty($severityKey) and (string)$severityKey != (string)$severityValue)
                    {
                        $hasCustomSeverity = true;
                        break;
                    }
                }
                ?>
                <?php if($hasCustomSeverity):?>
                <?php echo html::select('severity', (array)$lang->bug->severityList, $severity, "class='form-control'");?>
                <?php else: ?>
                <div class="input-group-btn pri-selector" data-type="severity">
                  <button type="button" class="btn dropdown-toggle br-0" data-toggle="dropdown">
                    <span class="pri-text"><span class="label-severity" data-severity="<?php echo $severity;?>" title="<?php echo $severity;?>"></span></span> &nbsp;<span class="caret"></span>
                  </button>
                  <div class='dropdown-menu pull-right'>
                    <?php echo html::select('severity', (array)$lang->bug->severityList, $severity, "class='form-control' data-provide='labelSelector' data-label-class='label-severity'");?>
                  </div>
                </div>
                <?php endif; ?>
                <?php endif; // end print severity selector ?>
                <?php if(strpos(",$showFields,", ',pri,') !== false): // begin print pri selector?>
                <span class="input-group-addon fix-border br-0"><?php echo $lang->bug->pri;?></span>
                <?php
                $hasCustomPri = false;
                foreach($lang->bug->priList as $priKey => $priValue)
                {
                    if(!empty($priKey) and (string)$priKey != (string)$priValue)
                    {
                        $hasCustomPri = true;
                        break;
                    }
                }
                $priList = $lang->bug->priList;
                if(end($priList)) unset($priList[0]);
                if(!isset($priList[$pri]))
                {
                    reset($priList);
                    $pri = key($priList);
                }
                ?>
                <?php if($hasCustomPri):?>
                <?php echo html::select('pri', (array)$priList, $pri, "class='form-control'");?>
                <?php else: ?>
                <div class="input-group-btn pri-selector" data-type="pri">
                  <button type="button" class="btn dropdown-toggle br-0" data-toggle="dropdown">
                    <span class="pri-text"><span class="label-pri label-pri-<?php echo empty($pri) ? '0' : $pri?>" title="<?php echo $pri?>"><?php echo $pri?></span></span> &nbsp;<span class="caret"></span>
                  </button>
                  <div class='dropdown-menu pull-right'>
                    <?php echo html::select('pri', (array)$priList, $pri, "class='form-control' data-provide='labelSelector' data-label-class='label-pri'");?>
                  </div>
                </div>
                <?php endif; ?>
                <?php endif; // end print pri selector ?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->bug->steps;?></th>
            <td colspan='2'>
              <?php echo $this->fetch('user', 'ajaxPrintTemplates', 'type=bug&link=steps');?>
              <?php echo html::textarea('steps', $steps, "rows='10' class='form-control'");?>
            </td>
          </tr>
          <?php
            $showStory = strpos(",$showFields,", ',story,') !== false;
            $showTask  = strpos(",$showFields,", ',task,')  !== false;
          ?>
          <?php if(($showStory or $showTask)):?>
          <tr>
            <th><?php echo ($showStory) ? $lang->bug->story : $lang->bug->task;?></th>
            <?php if($showStory):?>
            <td>
              <span id='storyIdBox'><?php echo html::select('story', empty($stories) ? '' : $stories, $storyID, "class='form-control chosen'");?></span>
            </td>
            <?php endif;?>
            <?php if($showTask):?>
            <td>
              <div class='input-group'>
                <?php if($showStory):?>
                <span class='input-group-addon task'><?php echo $lang->bug->task?></span>
                <?php endif;?>
                <?php echo html::select('task', '', $taskID, "class='form-control chosen'") . html::hidden('oldTaskID', $taskID);?>
              </div>
            </td>
            <?php endif;?>
          </tr>
          <?php endif;?>

          <?php
          $showMailto   = strpos(",$showFields,", ',mailto,')   !== false;
          $showKeywords = strpos(",$showFields,", ',keywords,') !== false;
          ?>
          <?php if($showMailto or $showKeywords):?>
          <?php $colspan = ($showMailto and $showKeywords) ? '' : "colspan='2'";?>
          <tr>
            <th><?php echo ($showMailto) ? $lang->bug->lblMailto : $lang->bug->keywords;?></th>
            <?php if($showMailto):?>
            <td>
              <div class='input-group' id='contactListGroup'>
                <?php
                echo html::select('mailto[]', $users, str_replace(' ', '', $mailto), "class='form-control picker-select' multiple");
                echo $this->fetch('my', 'buildContactLists');
                ?>
              </div>
            </td>
            <?php endif;?>
            <?php if($showKeywords):?>
            <td <?php echo $colspan?>>
              <div class='input-group'>
                <?php if($showMailto):?>
                <span class='input-group-addon' id='keywordsAddonLabel'><?php echo $lang->bug->keywords;?></span>
                <?php endif;?>
                <?php echo html::input('keywords', $keywords, "class='form-control'");?>
              </div>
            </td>
             <?php endif;?>
          </tr>
          <?php endif;?>
          <tr class='hide'>
            <th><?php echo $lang->bug->status;?></th>
            <td><?php echo html::hidden('status', 'active');?></td>
            <td><?php echo html::hidden('issueKey', $issueKey);?></td>
          </tr>
          <?php $this->printExtendFields('', 'table');?>
          <tr>
            <th><?php echo $lang->bug->files;?></th>
            <td colspan='2'>
              <?php
              if($caseID)
              {
                  echo $this->fetch('file', 'printFiles', array('files' => $resultFiles, 'fieldset' => 'false', 'object' => null, 'method' => 'edit', 'showDelete' => true, 'showEdit' => false));
                  echo html::hidden('resultFiles', implode(',', array_keys($resultFiles)));
              }
              if(isset($sourceFiles))
              {
                  echo $this->fetch('file', 'printFiles', array('files' => $sourceFiles, 'fieldset' => 'false', 'object' => null, 'method' => 'edit', 'showDelete' => true, 'showEdit' => false));
              }

              echo $this->fetch('file', 'buildform', 'fileCount=1&percent=0.85');
              ?>
            </td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3" class="text-center form-actions">
              <?php $browseLink = $this->session->bugList ? $this->session->bugList : $this->inlink('browse', "productID=$productID");?>
              <?php echo html::submitButton();?>
              <?php if($caseID) echo html::a($browseLink, $lang->goback, '', 'class="btn btn-wide"');?>
              <?php if(!$caseID and $gobackLink) echo html::a($gobackLink, $lang->goback, '', 'class="btn btn-wide"');?>
              <?php if(!$caseID and !$gobackLink) echo html::backButton();?>
              <?php echo html::hidden('case', (int)$caseID) . html::hidden('caseVersion', (int)$version);?>
              <?php echo html::hidden('result', (int)$runID) . html::hidden('testtask', empty($testtask) ? 0 : $testtask->id);?>
            </td>
          </tr>
        </tfoot>
      </table>
    </form>
  </div>
</div>
<?php if(empty($product->shadow)) js::set('bugModule', $lang->bug->module);?>
<?php js::set('bugExecution', $projectModel == 'kanban' ? $lang->bug->kanban : $lang->bug->execution);?>
<script>
$(function(){parent.$('body.hide-modal-close').removeClass('hide-modal-close');})
</script>
<?php if(isonlybody()):?>
<script>
$('#osBox').next('.table-col').find('span').removeClass('fix-border');
var browser = $('#osBox').next('.table-col').html();
$('#osBox').next('.table-col').remove();
$('#typeBox').closest('tr').append('<td>' + browser + '</td>');
</script>
<?php endif;?>
<?php include '../../ai/view/inputinject.html.php';?>
<?php include '../../common/view/footer.html.php';?>
