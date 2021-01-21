<?php
/**
 * The prjedit view file of program module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     program
 * @version     $Id
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::import($jsRoot . 'misc/date.js');?>
<?php js::set('weekend', $config->project->weekend);?>
<?php js::set('errorSameProducts', $lang->program->errorSameProducts);?>
<?php js::set('oldParent', $project->parent);?>
<?php js::set('projectID', $project->id);?>
<?php js::set('PGMChangeTips', $lang->program->PGMChangeTips);?>
<?php js::set('longTime', $lang->program->PRJLongTime);?>
<?php js::set('from', $from);?>
<?php js::set('notRemoveProducts', $notRemoveProducts)?>
<?php js::set('tip', $lang->program->notAllowRemoveProducts);?>
<?php $aclList = $project->parent ? $lang->program->PGMPRJAclList : $lang->program->PRJAclList;?>
<?php $requiredFields = $config->program->PRJEdit->requiredFields;?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->program->PRJEdit;?></h2>
    </div>
    <form class='form-indicator main-form form-ajax' method='post' target='hiddenwin' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th class='w-120px'><?php echo $lang->program->PGMParent;?></th>
          <td><?php echo html::select('parent', $programList, $programID != $project->parent ? $programID : $project->parent, "class='form-control chosen' onchange='setParentProgram(this.value)'");?></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->PRJName;?></th>
          <td class="col-main"><?php echo html::input('name', $project->name, "class='form-control' required");?></td><td></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->PRJPM;?></th>
          <td><?php echo html::select('PM', $PMUsers, $project->PM, "class='form-control chosen'" . (strpos($requiredFields, 'PM') !== false ? ' required' : ''));?></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->PRJBudget;?></th>
          <td>
            <div class='input-group'>
              <?php $placeholder = ($parentProgram and $parentProgram->budget != 0) ? 'placeholder=' . $lang->program->PGMParentBudget . zget($lang->program->currencySymbol, $parentProgram->budgetUnit) . $remainBudget : '';?>
              <?php echo html::input('budget', $project->budget != 0 ? $project->budget : '', "class='form-control' " . (strpos($requiredFields, 'budget') !== false ? 'required ' : '') . ($project->budget == 0 ? 'disabled ' : '') . $placeholder);?>
              <?php if($parentProgram):?>
              <span class='input-group-addon'><?php echo zget($budgetUnitList, $parentProgram->budgetUnit);?></span>
              <?php else:?>
              <span class='input-group-addon'></span>
              <?php echo html::select('budgetUnit', $budgetUnitList, $project->budgetUnit, "class='form-control'");?>
              <?php endif;?>
            </div>
          </td>
          <td>
            <div class='checkbox-primary'>
              <input type='checkbox' id='future' name='future' value='1' <?php if($project->budget == 0) echo 'checked';?> />
              <label for='future'><?php echo $lang->program->future;?></label>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->program->dateRange;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('begin', $project->begin, "class='form-control form-date' onchange='computeWorkDays();' placeholder='" . $lang->program->begin . "' required");?>
              <span class='input-group-addon'><?php echo $lang->program->to;?></span>
              <?php
                $disabledEnd = $project->end == LONG_TIME ? 'disabled' : '';
                $end         = $project->end == LONG_TIME ? $lang->program->PRJLongTime : $project->end;
                echo html::input('end', $end, "class='form-control form-date' onchange='computeWorkDays();' $disabledEnd placeholder='" . $lang->program->end . "' required");
              ?>
            </div>
          </td>
          <?php $deltaValue = $project->end == LONG_TIME ? 999 : '';?>
          <td colspan='2'><?php echo html::radio('delta', $lang->program->endList , $deltaValue, "onclick='computeEndDate(this.value)'");?></td>
        </tr>
        <?php if($project->model == 'scrum'):?>
        <tr>
          <th><?php echo $lang->project->days;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('days', $project->days, "class='form-control'");?>
              <span class='input-group-addon'><?php echo $lang->project->day;?></span>
            </div>
          </td>
          <td></td>
          <td></td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->project->manageProducts;?></th>
          <td class='text-left' id='productsBox' colspan="3">
            <div class='row'>
              <?php $i = 0;?>
              <?php foreach($linkedProducts as $product):?>
              <div class='col-sm-4' style="padding-right: 6px;">
                <?php $hasBranch = $product->type != 'normal' and isset($branchGroups[$product->id]);?>
                <div class="input-group<?php if($hasBranch) echo ' has-branch';?>">
                  <?php echo html::select("products[$i]", $allProducts, $product->id, "class='form-control chosen' data-placeholder={$lang->program->errorNoProducts} onchange='loadBranches(this)' data-last='" . $product->id . "'");?>
                  <span class='input-group-addon fix-border'></span>
                  <?php if($hasBranch) echo html::select("branch[$i]", $branchGroups[$product->id], $product->branch, "class='form-control chosen' onchange=\"loadPlans('#products{$i}', this.value)\"");?>
                </div>
              </div>
              <?php $i++;?>
              <?php endforeach;?>
              <div class='col-sm-4 <?php if($programID) echo 'required';?>' style="padding-right: 6px;">
                <div class='input-group'>
                  <?php echo html::select("products[$i]", $allProducts, '', "class='form-control chosen' onchange='loadBranches(this)'");?>
                  <span class='input-group-addon fix-border'></span>
                </div>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->project->linkPlan;?></th>
          <td id="plansBox" colspan="3">
            <div class='row'>
              <?php $i = 0;?>
              <?php foreach($linkedProducts as $product):?>
                <?php $plans = zget($productPlans, $product->id, array(0 => ''));?>
                <div class="col-sm-4" id="plan<?php echo $i;?>" style="padding-right: 6px;"><?php echo html::select("plans[" . $product->id . "]", $plans, $product->plan, "class='form-control chosen'");?></div>
                <?php $i++;?>
              <?php endforeach;?>
              <div class="col-sm-4" id="planDefault" style="padding-right: 6px;"><?php echo html::select("plans[0]", array(), 0, "class='form-control chosen'");?></div>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->program->PRJDesc;?></th>
          <td colspan='3'>
            <?php echo html::textarea('desc', $project->desc, "rows='6' class='form-control kindeditor' hidefocus='true'" . (strpos($requiredFields, 'desc') !== false ? ' required' : ''));?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->project->acl;?></th>
          <td colspan='3' class='aclBox'><?php echo nl2br(html::radio('acl', $aclList, $project->acl, "onclick='setWhite(this.value);'", 'block'));?></td>
        </tr>
        <tr class="<?php if($project->acl == 'open') echo 'hidden';?>" id="whitelistBox">
          <th><?php echo $lang->whitelist;?></th>
          <td><?php echo html::select('whitelist[]', $users, $project->whitelist, 'class="form-control chosen" multiple');?></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->auth;?></th>
          <td colspan='3'><?php echo html::radio('auth', $lang->program->PRJAuthList, $project->auth, '', 'block');?></td>
        </tr>
        <tr>
          <td colspan='4' class='text-center form-actions'>
            <?php
              echo html::hidden('model', $project->model);
              echo html::submitButton();
              echo html::backButton();
            ?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<div id='PRJAcl' class='hidden'>
  <?php echo nl2br(html::radio('acl', $lang->program->PRJAclList, $project->acl == 'program' ? 'private' : 'open', "onclick='setWhite(this.value);'", 'block'));?>
</div>
<div id='PGMAcl' class='hidden'>
  <?php echo nl2br(html::radio('acl', $lang->program->PGMPRJAclList, $project->acl, "onclick='setWhite(this.value);'", 'block'));?>
</div>
<?php include '../../common/view/footer.html.php';?>
