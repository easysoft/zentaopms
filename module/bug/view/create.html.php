<?php
/**
 * The create view of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
js::set('refresh', $lang->refresh);
js::set('confirmDeleteTemplate', $lang->bug->confirmDeleteTemplate);
?>
<div id="mainContent" class="main-content">
  <div class="center-block">
    <div class="main-header">
      <h2><?php echo $lang->bug->create;?></h2>
      <div class="pull-right btn-toolbar">
        <?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=bug&section=custom&key=createFields')?>
        <?php include '../../common/view/customfield.html.php';?>
      </div>
    </div>
    <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
      <table class="table table-form">
        <tbody>
          <tr>
            <th><?php echo $lang->bug->product;?></th>
            <td>
              <div class='input-group'>
                <?php echo html::select('product', $products, $productID, "onchange='loadAll(this.value);' class='form-control chosen control-product' autocomplete='off'");?>
                <?php if($this->session->currentProductType != 'normal'):?>
                <?php  echo html::select('branch', $branches, $branch, "onchange='loadBranch()' class='form-control chosen control-branch'");?>
                <?php endif;?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->bug->module;?></th>
            <td>
              <div class='input-group' id='moduleIdBox'>
                <?php
                echo html::select('module', $moduleOptionMenu, $moduleID, "onchange='loadModuleRelated()' class='form-control chosen'");
                if(count($moduleOptionMenu) == 1)
                {
                    echo "<span class='input-group-addon'>";
                    echo html::a($this->createLink('tree', 'browse', "rootID=$productID&view=bug&currentModuleID=0&branch=$branch", '', true), $lang->tree->manage, '', "class='text-primary' data-toggle='modal' data-type='iframe' data-width='95%'");
                    echo '&nbsp; ';
                    echo html::a("javascript:void(0)", $lang->refresh, '', "class='refresh' onclick='loadProductModules($productID)'");
                    echo '</span>';
                }
                ?>
              </div>
            </td>
          </tr>
          <?php $showProject = (strpos(",$showFields,", ',project,') !== false && $config->global->flow != 'onlyTest');?>
          <?php if($showProject):?>
          <tr>
            <th><?php echo $lang->bug->project;?></th>
            <td><div id='projectIdBox'><?php echo html::select('project', $projects, $projectID, "class='form-control chosen' onchange='loadProjectRelated(this.value)' autocomplete='off'");?></div></td>
          </tr>
          <?php endif;?>
          <tr>
            <th><?php echo $lang->bug->openedBuild?></th>
            <td>
              <div class='input-group' id='buildBox'>
                <?php echo html::select('openedBuild[]', $builds, $buildID, "size=4 multiple=multiple class='chosen form-control'");?>
                <span class='input-group-addon fix-border' id='buildBoxActions'></span>
                <div class='input-group-btn'><?php echo html::commonButton($lang->bug->allBuilds, "class='btn' data-toggle='tooltip' onclick='loadAllBuilds()'")?></div>
              </div>
            </td>
          </tr>
          <tr>
            <th><nobr><?php echo $lang->bug->lblAssignedTo;?></nobr></th>
            <td>
              <?php echo html::select('assignedTo', $projectMembers, $assignedTo, "class='form-control chosen'");?>
            </td>
            <td>
              <a href='javascript:loadAllUsers();' class='btn btn-link'><?php echo $lang->bug->allUsers;?></a>
            </td>
          </tr>
          <?php $showDeadline = strpos(",$showFields,", ',deadline,') !== false;?>
          <?php if($showDeadline):?>
          <tr>
            <th><?php echo $lang->bug->deadline?></th>
            <td><?php echo html::input('deadline', $deadline, "class='form-control form-date'");?></td>
          </tr>
          <?php endif;?>

          <?php if(strpos(",$showFields,", ',severity,') !== false):?>
          <tr>
            <th><?php echo $lang->bug->severity;?></th>
            <td>
              <?php
              $hasCustomSeverity = false;
              foreach($lang->bug->severityList as $severityKey => $severityValue)
              {
                  if(!empty($severityKey) and (string)$severityKey != (string)$severityValue)
                  {
                      $hasCustomSeverity = true;
                      break;
                  }
              }
              ?>
              <?php if($hasCustomSeverity):?>
              <?php echo html::select('severity', (array)$lang->bug->severityList, $severity, "class='form-control chosen'");?>
              <?php else: ?>
              <?php echo html::select('severity', (array)$lang->bug->severityList, $severity, "class='form-control' data-provide='labelSelector' data-label-class='label-severity'");?>
              <?php endif; ?>
            </td>
          </tr>
          <?php endif;?>
          <?php if(strpos(",$showFields,", ',pri,') !== false):?>
          <tr>
            <th><?php echo $lang->bug->pri;?></th>
            <td>
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
              if(end($priList))
              {
                  unset($priList[0]);
                  $priList[0] = '';
              }
              ?>
              <?php if($hasCustomPri):?>
              <?php echo html::select('pri', (array)$priList, $pri, "class='form-control chosen'");?>
              <?php else: ?>
              <?php echo html::select('pri', (array)$priList, $pri, "class='form-control' data-provide='labelSelector'");?>
              <?php endif; ?>
            </td>
          </tr>
          <?php endif;?>
          <?php if($config->global->flow != 'onlyTest' && $showProject):?>
          <?php $showOS      = strpos(",$showFields,", ',os,')      !== false;?>
          <?php $showBrowser = strpos(",$showFields,", ',browser,') !== false;?>
          <tr>
            <th><?php echo $lang->bug->type;?></th>
            <td colspan='2'>
              <div class='input-group' id='bugTypeInputGroup'>
                <?php
                /* Remove the unused types. */
                unset($lang->bug->typeList['designchange']);
                unset($lang->bug->typeList['newfeature']);
                unset($lang->bug->typeList['trackthings']);
                echo html::select('type', $lang->bug->typeList, $type, "class='form-control chosen'");
                ?>
                <?php if($showOS):?>
                <span class='input-group-addon fix-border'><?php echo $lang->bug->os?></span>
                <?php echo html::select('os', $lang->bug->osList, $os, "class='form-control chosen'");?>
                <?php endif;?>
                <?php if($showBrowser):?>
                <span class='input-group-addon fix-border'><?php echo $lang->bug->browser?></span>
                <?php echo html::select('browser', $lang->bug->browserList, $browser, "class='form-control chosen'");?>
                <?php endif;?>
              </div>
            </td>
          </tr>
          <?php endif;?>
          <tr>
            <th><?php echo $lang->bug->title;?></th>
            <td colspan='2'>
              <div class="input-control has-icon-right">
                <?php echo html::input('title', $bugTitle, "class='form-control' autocomplete='off' required");?>
                <div class="colorpicker">
                  <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
                  <ul class="dropdown-menu clearfix">
                    <li class="heading"><?php echo $lang->story->colorTag;?><i class="icon icon-close"></i></li>
                  </ul>
                  <input type="hidden" class="colorpicker" id="color" name="color" value="" data-icon="color" data-wrapper="input-control-icon-right" data-update-color="#title"  data-provide="colorpicker">
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->bug->steps;?></th>
            <td colspan='2'>
              <div id='tplBoxWrapper'>
                <div class='btn-toolbar'>
                  <div class='btn-group'>
                    <button id='saveTplBtn' type='button' class='btn btn-mini' data-toggle='saveTplModal'><?php echo $lang->bug->saveTemplate?></button>
                    <button type='button' class='btn btn-mini dropdown-toggle' data-toggle='dropdown'><?php echo $lang->bug->applyTemplate?> <span class='caret'></span></button>
                    <ul id='tplBox' class='dropdown-menu pull-right'>
                      <?php echo $this->fetch('bug', 'buildTemplates');?>
                    </ul>
                  </div>
                </div>
              </div>
              <?php echo html::textarea('steps', $steps, "rows='10' class='form-control'");?>
            </td>
          </tr>
          <?php
          $showStory = strpos(",$showFields,", ',story,') !== false;
          $showTask  = strpos(",$showFields,", ',task,')  !== false;
          ?>
          <?php if($showStory and $config->global->flow != 'onlyTest'):?>
          <tr>
            <th><?php echo $lang->bug->story;?></th>
            <td colspan='2'><div id='storyIdBox' class='input-group'><?php echo html::select('story', empty($stories) ? '' : $stories, $storyID, "class='form-control chosen'");?></div></td>
          </tr>
          <?php endif;?>
          <?php if($showTask and $config->global->flow != 'onlyTest'):?>
          <tr>
            <th><?php echo $lang->bug->task;?></th>
            <td colspan='2'><div id='taskIdBox' class='input-group'> <?php echo html::select('task', '', $taskID, "class='form-control chosen'") . html::hidden('oldTaskID', $taskID);?></div></td>
          </tr>
          <?php endif;?>
          <?php
          $showMailto   = strpos(",$showFields,", ',mailto,')   !== false;
          $showKeywords = strpos(",$showFields,", ',keywords,') !== false;
          ?>
          <?php if($showMailto):?>
          <tr>
            <th><?php echo $lang->bug->lblMailto;?></th>
            <td colspan='2'>
              <div class='input-group' id='contactListGroup'>
              <?php
              echo html::select('mailto[]', $users, str_replace(' ', '', $mailto), "class='form-control chosen' multiple");
              echo $this->fetch('my', 'buildContactLists');
              ?>
              </div>
            </td>
          </tr>
          <?php endif;?>
          <?php if($showKeywords):?>
          <tr>
            <th><?php echo $lang->bug->keywords;?></th>
            <td colspan='2'><?php echo html::input('keywords', $keywords, "class='form-control'");?></td>
          </tr>
          <?php endif;?>
          <tr>
            <th><?php echo $lang->bug->files;?></th>
            <td colspan='2'><?php echo $this->fetch('file', 'buildform', 'fileCount=1&percent=0.85');?></td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3" class="text-center form-actions">
              <?php echo html::submitButton('', '', 'btn btn-wide btn-primary');?>
              <?php echo html::backButton('', '', 'btn btn-wide');?>
              <?php echo html::hidden('case', (int)$caseID) . html::hidden('caseVersion', (int)$version);?>
              <?php echo html::hidden('result', (int)$runID) . html::hidden('testtask', (int)$testtask);?>
            </td>
          </tr>
        </tfoot>
      </table>
    </form>
  </div>
</div>
<div class="modal fade" id="saveTplModal" tabindex="-1" role="dialog">
  <div class="modal-dialog w-600px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><i class="icon icon-close"></i></button>
        <h4 class="modal-title"><?php echo $lang->bug->setTemplateTitle;?></h4>
      </div>
      <div class="modal-body">
        <div class='input-group'>
          <?php echo html::input('title', '' , "class='form-control'")?>
          <?php if(common::hasPriv('bug', 'setPublic')):?>
          <span class='input-group-addon'><?php echo html::checkbox('public', array('1' => $lang->public))?></span>
          <?php endif;?>
          <span class='input-group-btn'><?php echo html::submitButton()?></span>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
