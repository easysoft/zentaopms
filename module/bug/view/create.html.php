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
include '../../common/view/form.html.php';
include '../../common/view/kindeditor.html.php';
include '../../common/view/datepicker.html.php';
js::set('holders', $lang->bug->placeholder);
js::set('page', 'create');
js::set('createRelease', $lang->release->create);
js::set('createBuild', $lang->build->create);
js::set('refresh', $lang->refresh);
js::set('confirmDeleteTemplate', $lang->bug->confirmDeleteTemplate);
?>
<div class='container mw-1400px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon($lang->icons['bug']);?></span>
      <strong><small class='text-muted'><?php echo html::icon($lang->icons['create']);?></small> <?php echo $lang->bug->create;?></strong>
    </div>
    <div class='actions'>
      <button type='button' class='btn btn-default' data-toggle='customModal'><i class='icon icon-cog'></i></button>
    </div>
  </div>
  <form class='form-condensed' method='post' enctype='multipart/form-data' id='dataform' data-type='ajax'>
    <table class='table table-form'> 
      <tr>
        <th class='w-110px'><?php echo $lang->bug->product;?></th>
        <td class='w-p45-f'>
          <div class='input-group'>
            <?php echo html::select('product', $products, $productID, "onchange='loadAll(this.value);' class='form-control chosen' autocomplete='off'");?>
            <?php if($this->session->currentProductType != 'normal') echo html::select('branch', $branches, $branch, "onchange='loadBranch()' class='form-control' style='width:120px'");?>
          </div>
        </td>
        <td>
          <div class='input-group' id='moduleIdBox'>
            <span class="input-group-addon"><?php echo $lang->bug->module?></span>
            <?php
            echo html::select('module', $moduleOptionMenu, $moduleID, "onchange='loadModuleRelated()' class='form-control chosen'");
            if(count($moduleOptionMenu) == 1)
            {
                echo "<span class='input-group-addon'>";
                echo html::a($this->createLink('tree', 'browse', "rootID=$productID&view=bug&currentModuleID=0&branch=$branch"), $lang->tree->manage, '_blank');
                echo '&nbsp; ';
                echo html::a("javascript:loadProductModules($productID)", $lang->refresh);
                echo '</span>';
            }
            ?>
          </div>
        </td>
        <td></td>
      </tr>
      <?php $showProject = (strpos(",$showFields,", ',project,') !== false && $this->config->global->flow != 'onlyTest');?>
      <tr>
        <th><?php echo ($showProject) ? $lang->bug->project : (($this->config->global->flow == 'onlyTest') ? $lang->bug->type : $lang->bug->openedBuild);?></th>

        <?php if(!$showProject):?>
        <?php $showOS      = strpos(",$showFields,", ',os,')      !== false;?>
        <?php $showBrowser = strpos(",$showFields,", ',browser,') !== false;?>
        <td>
          <div class='input-group' id='bugTypeInputGroup'>
            <?php
            /* Remove the unused types. */
            unset($lang->bug->typeList['designchange']);
            unset($lang->bug->typeList['newfeature']);
            unset($lang->bug->typeList['trackthings']);
            echo html::select('type', $lang->bug->typeList, $type, "class='form-control'");
            ?>
            <?php if($showOS):?>
            <span class='input-group-addon fix-border'><?php echo $lang->bug->os?></span>
            <?php echo html::select('os', $lang->bug->osList, $os, "class='form-control'");?>
            <?php endif;?>
            <?php if($showBrowser):?>
            <span class='input-group-addon fix-border'><?php echo $lang->bug->browser?></span>
            <?php echo html::select('browser', $lang->bug->browserList, $browser, "class='form-control'");?>
            <?php endif;?>
          </div>
        </td>
        <?php endif;?>
        <?php if($showProject):?>
        <td><span id='projectIdBox'><?php echo html::select('project', $projects, $projectID, "class='form-control chosen' onchange='loadProjectRelated(this.value)' autocomplete='off'");?></span></td>
        <?php endif;?>
        <td>
          <div class='input-group'>
            <?php if($showProject or $this->config->global->flow == 'onlyTest'):?>
            <span class='input-group-addon'><?php echo $lang->bug->openedBuild?></span>
            <?php endif;?>
            <span id='buildBox'><?php echo html::select('openedBuild[]', $builds, $buildID, "size=4 multiple=multiple class='chosen form-control'");?></span>
            <span class='input-group-addon fix-border' id='buildBoxActions'></span>
            <span class='input-group-btn'><?php echo html::commonButton($lang->bug->allBuilds, "class='btn btn-default' data-toggle='tooltip' onclick='loadAllBuilds()'")?></span>
          </div>
        </td>
      </tr>
      <tr>
        <th><nobr><?php echo $lang->bug->lblAssignedTo;?></nobr></th>
        <td>
          <div class='input-group'>
            <span id='assignedToBox'><?php echo html::select('assignedTo', $projectMembers, $assignedTo, "class='form-control chosen'");?></span>
            <span class='input-group-btn'><?php echo html::commonButton($lang->bug->allUsers, "class='btn btn-default' onclick='loadAllUsers()' data-toggle='tooltip'");?></span>
          </div>
        </td>
        <?php $showDeadline = strpos(",$showFields,", ',deadline,') !== false;?>
        <?php if($showDeadline):?>
        <td>
          <div class='input-group'>
            <span class='input-group-addon'><?php echo $lang->bug->deadline?></span>
            <span><?php echo html::input('deadline', $deadline, "class='form-control form-date'");?></span>
          </div>
        </td>
        <?php endif;?>
      </tr>
      <?php if($this->config->global->flow != 'onlyTest'):?>
      <?php $showOS      = strpos(",$showFields,", ',os,')      !== false;?>
      <?php $showBrowser = strpos(",$showFields,", ',browser,') !== false;?>
      <tr>
        <th><?php echo $lang->bug->type;?></th>
        <td>
          <div class='input-group' id='bugTypeInputGroup'>
            <?php
            /* Remove the unused types. */
            unset($lang->bug->typeList['designchange']);
            unset($lang->bug->typeList['newfeature']);
            unset($lang->bug->typeList['trackthings']);
            echo html::select('type', $lang->bug->typeList, $type, "class='form-control'");
            ?>
            <?php if($showOS):?>
            <span class='input-group-addon fix-border'><?php echo $lang->bug->os?></span>
            <?php echo html::select('os', $lang->bug->osList, $os, "class='form-control'");?>
            <?php endif;?>
            <?php if($showBrowser):?>
            <span class='input-group-addon fix-border'><?php echo $lang->bug->browser?></span>
            <?php echo html::select('browser', $lang->bug->browserList, $browser, "class='form-control'");?>
            <?php endif;?>
          </div>
        </td>
      </tr>
      <?php endif;?>
      <tr>
        <th><?php echo $lang->bug->title;?></th>
        <td colspan='2'>
          <div class='row-table'>
            <div class='col-table w-p100'>
              <div class='input-group w-p100'>
                <input type='hidden' id='color' name='color' data-provide='colorpicker' data-wrapper='input-group-btn' data-pull-menu-right='false' data-btn-tip='<?php echo $lang->bug->colorTag ?>' data-update-text='#title'>
                <?php echo html::input('title', $bugTitle, "class='form-control'");?>
              </div>
            </div>
            <?php $showSeverity = strpos(",$showFields,", ',severity,') !== false;?>
            <?php $showPri      = strpos(",$showFields,", ',pri,')      !== false;?>
            <?php if($showSeverity or $showPri):?>
            <?php $widthClass = (!$showSeverity or !$showPri) ? 'w-100px' : 'w-230px';?>
            <div class='col-table <?php echo $widthClass?>'>
              <div class='input-group'>
                <?php if($showSeverity):?>
                <span class='input-group-addon fix-border br-0'><?php echo $lang->bug->severity;?></span>
                <?php
                $hasCustomSeverity = false;
                foreach($lang->bug->severityList as $severityKey => $severityValue)
                {
                    if($severityKey != $severityValue)
                    {
                        $hasCustomSeverity = true;
                        break;
                    }
                }
                ?>
                <?php if($hasCustomSeverity):?>
                <?php echo html::select('severity', (array)$lang->bug->severityList, $severity, "class='form-control minw-80px'");?> 
                <?php else: ?>
                <div class='input-group-btn dropdown-pris' data-prefix='severity'>
                  <button type='button' class='btn dropdown-toggle br-0' data-toggle='dropdown'>
                    <span class='pri-text'></span> &nbsp;<span class='caret'></span>
                  </button>
                  <ul class='dropdown-menu pull-right'></ul>
                  <?php echo html::select('severity', (array)$lang->bug->severityList, $severity, "class='hide'");?>
                </div>
                <?php endif; ?>
                <?php endif;?>
                <?php if($showPri):?>
                <span class='input-group-addon fix-border br-0'><?php echo $lang->bug->pri;?></span>
                <?php
                $hasCustomPri = false;
                foreach($lang->bug->priList as $priKey => $priValue)
                {
                    if($priKey != $priValue)
                    {
                        $hasCustomPri = true;
                        break;
                    }
                }
                ?>
                <?php if($hasCustomPri):?>
                <?php echo html::select('pri', (array)$lang->bug->priList, '', "class='form-control minw-80px'");?> 
                <?php else: ?>
                <div class='input-group-btn dropdown-pris'>
                  <button type='button' class='btn dropdown-toggle br-0' data-toggle='dropdown'>
                    <span class='pri-text'></span> &nbsp;<span class='caret'></span>
                  </button>
                  <ul class='dropdown-menu pull-right'></ul>
                  <?php echo html::select('pri', $lang->bug->priList, '', "class='hide'");?>
                </div>
                <?php endif;?>
                <?php endif;?>
              </div>
            </div>
            <?php endif;?>
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
      <?php if(($showStory or $showTask) and $this->config->global->flow != 'onlyTest'):?>
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
            <span class='input-group-addon'><?php echo $lang->bug->task?></span>
            <?php endif;?>
            <span id='taskIdBox'> <?php echo html::select('task', '', $taskID, "class='form-control chosen'");?></span>
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
          echo html::select('mailto[]', $users, str_replace(' ', '', $mailto), "class='form-control chosen' multiple");
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
      <tr>
        <th><?php echo $lang->bug->files;?></th>
        <td colspan='2'><?php echo $this->fetch('file', 'buildform', 'fileCount=1&percent=0.85');?></td>
      </tr>
      <tr>
        <td></td>
        <td colspan='2'>
          <?php
          echo html::submitButton() . html::backButton();
          echo html::hidden('case', (int)$caseID) . html::hidden('caseVersion', (int)$version);
          echo html::hidden('result', (int)$runID) . html::hidden('testtask', (int)$testtask);
          ?>
        </td>
      </tr>
    </table>
  </form>
</div>
<div class="modal fade" id="saveTplModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog w-600px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
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
<?php js::set('bugModule', $lang->bug->module);?>
<?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=bug&section=custom&key=createFields');?>
<?php include '../../common/view/customfield.html.php';?>
<?php include '../../common/view/footer.html.php';?>
