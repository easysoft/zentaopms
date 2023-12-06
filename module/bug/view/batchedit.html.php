<?php
/**
 * The batch edit view of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     bug
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::set('dittoNotice', $this->lang->bug->notice->productDitto);?>
<?php js::set('showFields', $showFields);?>
<?php js::set('requiredFields', $config->bug->edit->requiredFields);?>
<div id='mainContent' class='main-content fade'>
  <div class='main-header'>
    <h2><?php echo $lang->bug->common . $lang->colon . $lang->bug->batchEdit;?></h2>
    <div class="pull-right btn-toolbar">
      <?php $customLink = $this->createLink('custom', 'ajaxSaveCustomFields', 'module=bug&section=custom&key=batchEditFields')?>
      <?php include '../../common/view/customfield.html.php';?>
    </div>
  </div>

  <?php if(isset($suhosinInfo)):?>
  <div class='alert alert-info'><?php echo $suhosinInfo;?></div>
  <?php else:?>
  <?php
  $visibleFields  = array();
  $requiredFields = array();
  foreach(explode(',', $showFields) as $field)
  {
      if($field) $visibleFields[$field] = '';
  }
  foreach(explode(',', $config->bug->edit->requiredFields) as $field)
  {
      if($field)
      {
          $requiredFields[$field] = '';
          if(strpos(",{$config->bug->list->customBatchEditFields},", ",{$field},") !== false) $visibleFields[$field] = '';
      }
  }
  $columns = count($visibleFields) + 2;
  ?>
  <form class='main-form' method='post' target='hiddenwin' action="<?php echo inLink('batchEdit', "productID=$productID")?>" id='batchEditForm'>
    <div class="table-responsive">
      <table class='table table-form'>
        <thead>
          <tr>
            <th class='c-id'><?php echo $lang->idAB;?></th>
            <th class='c-type<?php echo zget($visibleFields, 'type', ' hidden') . zget($requiredFields, 'type', '', ' required');?>'><?php echo $lang->bug->type;?></th>
            <th class='c-severity<?php echo zget($visibleFields, 'severity', ' hidden') . zget($requiredFields, 'severity', '', ' required');?>'><?php echo $lang->bug->severity;?></th>
            <th class='c-pri<?php echo zget($visibleFields, 'pri', ' hidden') . zget($requiredFields, 'pri', '', ' required');?>'><?php echo $lang->bug->pri;?></th>
            <th class="required <?php if(count($visibleFields) >= 8) echo ' c-title';?>"><?php echo $lang->bug->title;?></th>
            <?php if($branchProduct):?>
            <th class='c-branch'><?php echo $lang->bug->branch;?></th>
            <?php endif;?>
            <th class='c-module<?php echo zget($requiredFields, 'module', '', ' required');?>'> <?php echo $lang->bug->module;?></th>
            <th class='c-plan<?php echo zget($visibleFields, 'productplan', ' hidden') . zget($requiredFields, 'productplan', '', ' required');?>'><?php echo $lang->bug->plan;?></th>
            <th class='c-assigned<?php echo zget($visibleFields, 'assignedTo', ' hidden') . zget($requiredFields, 'assignedTo', '', ' required');?>'><?php echo $lang->bug->assignedTo;?></th>
            <th class='c-date<?php echo zget($visibleFields, 'deadline', ' hidden') . zget($requiredFields, 'deadline', '', ' required');?>'><?php echo $lang->bug->deadline;?></th>
            <th class='c-os<?php echo zget($visibleFields, 'os', ' hidden') . zget($requiredFields, 'os', '', ' required');?>'><?php echo $lang->bug->os;?></th>
            <th class='c-browser<?php echo zget($visibleFields, 'browser', ' hidden') . zget($requiredFields, 'browser', '', ' required');?>'><?php echo $lang->bug->browser;?></th>
            <th class='c-keywords<?php echo zget($visibleFields, 'keywords', ' hidden') . zget($requiredFields, 'keywords', '', ' required');?>'><?php echo $lang->bug->keywords;?></th>
            <th class='c-user<?php echo zget($visibleFields, 'resolvedBy', ' hidden')?>'><?php echo $lang->bug->abbr->resolvedBy;?></th>
            <th class='c-resolution<?php echo zget($visibleFields, 'resolution', ' hidden')?>'><?php echo $lang->bug->abbr->resolution;?></th>
            <?php
            $extendFields = $this->bug->getFlowExtendFields();
            foreach($extendFields as $extendField) echo "<th class='c-extend'>{$extendField->name}</th>";
            ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach($bugs as $bugID => $bug):?>
          <tr>
            <td><?php echo $bugID . html::hidden("bugIDList[$bugID]", $bugID);?></td>
            <td <?php echo zget($visibleFields, 'type', "class='hidden'")?>><?php echo html::select("types[$bugID]", $typeList, $bug->type, 'class=form-control');?></td>
            <td <?php echo zget($visibleFields, 'severity', "class='hidden'")?>><?php echo html::select("severities[$bugID]", $severityList, $bug->severity, 'class=form-control');?></td>
            <td <?php echo zget($visibleFields, 'pri', "class='hidden'")?>><?php echo html::select("pris[$bugID]", $priList, $bug->pri, 'class=form-control');?></td>
            <td style='overflow:visible' title='<?php echo $bug->title?>'>
              <div class='input-group'>
                <div class="input-control has-icon-right">
                  <?php echo html::input("titles[$bugID]", $bug->title, "class='form-control' style='color:{$bug->color}'");?>
                  <div class="colorpicker">
                    <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar" style="background:<?php echo $bug->color;?>"></span><i class="ic"></i></button>
                    <ul class="dropdown-menu clearfix">
                      <li class="heading"><?php echo $lang->story->colorTag;?><i class="icon icon-close"></i></li>
                    </ul>
                    <?php echo html::hidden("colors[$bugID]", $bug->color, "data-provide='colorpicker' data-icon='color' data-wrapper='input-control-icon-right'  data-update-color='#titles\\[{$bugID}\\]'");?>
                  </div>
                </div>
              <div>
            </td>
            <?php if($branchProduct):?>
            <td style='overflow:visible'>
              <?php $disabled = (isset($productList) and $productList[$bug->product]->type == 'normal') ? "disabled='disabled'" : '';?>
              <?php echo html::select("branches[$bugID]", !empty($disabled) ? array() : $branchTagOption[$bug->product], $bug->branch, "class='form-control picker-select' data-drop-width='auto' $disabled onchange='setBranchRelated(this.value, $bug->product, $bug->id)'");?>
            </td>
            <?php endif;?>
            <td><?php echo html::select("modules[$bugID]", isset($modules[$bug->product][$bug->branch]) ? $modules[$bug->product][$bug->branch] : array(0 => '/'), $bug->module, "class='form-control picker-select' data-drop-width='auto'");?></td>
            <td class='<?php echo zget($visibleFields, 'productplan', ' hidden')?>' style='overflow:visible'><?php echo html::select("plans[$bugID]", $bug->plans, $bug->plan, "class='form-control picker-select' data-drop-width='auto'");?></td>
            <?php
            $assignedToList = array();
            if($app->tab == 'project' or $app->tab == 'execution')
            {
                if($bug->execution)
                {
                    $assignedToList = array('' => '', 'ditto' => $this->lang->bug->ditto) + $executionMembers[$bug->execution];
                }
                elseif($bug->project)
                {
                    $assignedToList = array('' => '', 'ditto' => $this->lang->bug->ditto) + $projectMembers[$bug->project];
                }
                else
                {
                    $assignedToList = $productMembers[$bug->product][$bug->branch];
                    if(empty($assignedToList))
                    {
                        $assignedToList = $users;
                        unset($assignedToList['closed']);
                    }
                }
            }
            else
            {
                $assignedToList = $users;
                unset($assignedToList['closed']);
            }
            ?>
            <td class='<?php echo zget($visibleFields, 'assignedTo', ' hidden')?>' style='overflow:visible'><?php echo $bug->status == 'closed' ? html::input("assignedTos[$bugID]", ucfirst($bug->assignedTo), 'class=form-control disabled') : html::select("assignedTos[$bugID]", $assignedToList, $bug->assignedTo, "class='form-control picker-select' data-drop-width='135px'");?></td>
            <td class='<?php echo zget($visibleFields, 'deadline', ' hidden')?>' style='overflow:visible'><?php echo html::input("deadlines[$bugID]", helper::isZeroDate($bug->deadline) ? '' : $bug->deadline, "class='form-control form-date'");?></td>
            <td <?php echo zget($visibleFields, 'os', "class='hidden'")?>><?php echo html::select("os[$bugID][]", $lang->bug->osList, $bug->os, 'class="form-control chosen" multiple');?></td>
            <td <?php echo zget($visibleFields, 'browser', "class='hidden'")?>><?php echo html::select("browsers[$bugID][]", $lang->bug->browserList, $bug->browser, 'class="form-control chosen" multiple');?></td>
            <td <?php echo zget($visibleFields, 'keywords', "class='hidden'")?>><?php echo html::input("keywords[$bugID]", $bug->keywords, 'class=form-control');?></td>
            <td class='<?php echo zget($visibleFields, 'resolvedBy', ' hidden')?>' style='overflow:visible'><?php echo html::select("resolvedBys[$bugID]", $users, $bug->resolvedBy, "class='form-control picker-select' data-drop-width='auto'");?></td>
            <td <?php echo zget($visibleFields, 'resolution', "class='hidden'")?>>
              <table class='table-borderless table no-margin table-form'>
                <tr>
                  <td class='pd-0'><?php echo html::select("resolutions[$bugID]", $resolutionList, $bug->resolution, "class='form-control' onchange=setDuplicate(this.value,$bugID)");?></td>
                  <td class='pd-0 w-p50' id='<?php echo 'duplicateBox' . $bugID;?>' <?php if($bug->resolution != 'duplicate') echo "style='display:none'";?>>
                    <?php
                    $productBugs = isset($productBugList[$bug->product]) && isset($productBugList[$bug->product][$bug->branch]) ? $productBugList[$bug->product][$bug->branch] : array();
                    if(isset($productBugs[$bug->id])) unset($productBugs[$bug->id]);
                    ?>
                    <?php echo html::select("duplicates[$bugID]", $productBugs, $bug->duplicate, "class='form-control' placeholder='{$lang->bug->placeholder->duplicate}'");?>
                  </td>
                </tr>
              </table>
            </td>
            <?php foreach($extendFields as $extendField) echo "<td" . (($extendField->control == 'select' or $extendField->control == 'multi-select') ? " style='overflow:visible'" : '') . ">" . $this->loadModel('flow')->getFieldControl($extendField, $bug, $extendField->field . "[{$bugID}]") . "</td>";?>
          </tr>
          <?php if(isset($this->config->moreLinks["assignedTos[$bugID]"])) unset($this->config->moreLinks["assignedTos[$bugID]"]);?>
          <?php endforeach;?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan='<?php echo $branchProduct ? $columns : ($columns - 1);?>' class='text-center form-actions'>
              <?php echo html::submitButton();?>
              <?php echo $this->app->tab == 'product' ? html::a($this->session->bugList, $lang->goback, '', "class='btn btn-back btn-wide'") : html::backButton();?>
            </td>
          </tr>
        </tfoot>
      </table>
    </div>
  </form>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
