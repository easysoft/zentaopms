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
include '../../common/view/header.lite.html.php';
include '../../common/view/kindeditor.html.php';
include '../../common/view/datepicker.html.php';
?>
<style>
body{padding-bottom:0px}
#openedBuild_chosen.chosen-container .chosen-drop {min-width: 100px;}
#module_chosen.chosen-container .chosen-drop{min-width: 100px;}
</style>
<div id="mainContent" class="main-content fade" style='padding-bottom:0px'>
  <form class="load-indicator main-form form-ajax" method='post' enctype='multipart/form-data' id='dataform'>
    <table class="table table-form">
      <tbody>
        <tr>
          <th class='w-70px'><?php echo $lang->bug->product;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::select('product', $products, $productID, "onchange='loadAll(this.value);' class='form-control chosen control-product' autocomplete='off'");?>
              <?php if($this->session->currentProductType != 'normal'):?>
              <?php  echo html::select('branch', $branches, $branch, "onchange='loadBranch()' class='form-control chosen control-branch'");?>
              <?php endif;?>
            </div>
          </td>
          <td>
            <div class='input-group' id='moduleIdBox'>
            <span class="input-group-addon"><?php echo $lang->bug->module?></span>
              <?php echo html::select('module', $moduleOptionMenu, $moduleID, "onchange='loadModuleRelated()' class='form-control chosen'"); ?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->bug->project;?></th>
          <td><span id='projectIdBox'><?php echo html::select('project', $projects, $projectID, "class='form-control chosen' onchange='loadProjectRelated(this.value)' autocomplete='off'");?></span></td>
          <td>
            <div class='input-group' id='buildBox'>
              <span class="input-group-addon"><?php echo $lang->bug->openedBuild?></span>
              <?php echo html::select('openedBuild[]', $builds, $buildID, "size=4 multiple=multiple class='chosen form-control'");?>
            </div>
          </td>
        </tr>
        <tr>
          <th><nobr><?php echo $lang->bug->lblAssignedTo;?></nobr></th>
          <td>
            <div class='input-group'>
              <?php echo html::select('assignedTo', $projectMembers, $assignedTo, "class='form-control chosen'");?>
            </div>
          </td>
          <td>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->bug->deadline?></span>
              <span><?php echo html::input('deadline', $deadline, "class='form-control form-date'");?></span>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->bug->type;?></th>
          <td>
              <?php
              /* Remove the unused types. */
              unset($lang->bug->typeList['designchange']);
              unset($lang->bug->typeList['newfeature']);
              unset($lang->bug->typeList['trackthings']);
              echo html::select('type', $lang->bug->typeList, $type, "class='form-control chosen'");
              ?>
          </td>
          <td>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->bug->os?></span>
              <?php echo html::select('os', $lang->bug->osList, $os, "class='form-control chosen'");?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->bug->browser;?></th>
          <td> <?php echo html::select('browser', $lang->bug->browserList, $browser, "class='form-control chosen'");?> </td>
        </tr>
        <tr>
          <th><?php echo $lang->bug->title;?></th>
          <td colspan='2'>
            <div class="input-group title-group">
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
              <span class="input-group-addon fix-border br-0"><?php echo $lang->bug->severity;?></span>
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
              <?php endif; // end print severity selector ?>
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
              if(end($priList))
              {
                  unset($priList[0]);
                  $priList[0] = '';
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
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->bug->steps;?></th>
          <td colspan='2'> <?php echo html::textarea('steps', $steps, "rows='10' class='form-control'");?> </td>
        </tr>
        <tr>
          <th><?php echo $lang->bug->story;?></th>
          <td>
            <span id='storyIdBox'><?php echo html::select('story', empty($stories) ? '' : $stories, $storyID, "class='form-control chosen'");?></span>
          </td>
          <td>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->bug->task?></span>
              <?php echo html::select('task', '', $taskID, "class='form-control chosen'") . html::hidden('oldTaskID', $taskID);?>
            </div>
          </td>
        </tr>

        <tr>
          <th><?php echo $lang->bug->lblMailto;?></th>
          <td>
            <div class='input-group' id='contactListGroup'>
            <?php
            echo html::select('mailto[]', $users, str_replace(' ', '', $mailto), "class='form-control chosen' multiple");
            echo $this->fetch('my', 'buildContactLists');
            ?>
            </div>
          </td>
          <td <?php echo $colspan?>>
            <div class='input-group'>
              <span class='input-group-addon' id='keywordsAddonLabel'><?php echo $lang->bug->keywords;?></span>
              <?php echo html::input('keywords', $keywords, "class='form-control'");?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->bug->files;?></th>
          <td colspan='2'><?php echo $this->fetch('file', 'buildform', 'fileCount=1&percent=0.85');?></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3" class="text-center form-actions">
            <?php echo html::submitButton();?>
            <?php echo html::backButton();?>
            <?php echo html::hidden('case', (int)$caseID) . html::hidden('caseVersion', (int)$version);?>
            <?php echo html::hidden('result', (int)$runID) . html::hidden('testtask', (int)$testtask);?>
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
