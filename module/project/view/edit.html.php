<?php
/**
 * The prjedit view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::import($jsRoot . 'misc/date.js');?>
<?php js::set('weekend', $config->execution->weekend);?>
<?php js::set('LONG_TIME', LONG_TIME);?>
<?php js::set('errorSameProducts', $lang->project->errorSameProducts);?>
<?php js::set('errorSameBranches', $lang->project->errorSameBranches);?>
<?php js::set('errorSamePlans', $lang->project->errorSamePlans);?>
<?php js::set('oldParent', $project->parent);?>
<?php js::set('projectID', $project->id);?>
<?php js::set('longTime', $lang->project->longTime);?>
<?php js::set('unmodifiableProducts', $unmodifiableProducts)?>
<?php js::set('unmodifiableBranches', $unmodifiableBranches)?>
<?php js::set('unmodifiableMainBranches', $unmodifiableMainBranches)?>
<?php js::set('tip', $lang->project->notAllowRemoveProducts);?>
<?php js::set('linkedProjectsTip', $lang->project->linkedProjectsTip);?>
<?php js::set('multiBranchProducts', $multiBranchProducts);?>
<?php $aclList = $project->parent ? $lang->project->subAclList : $lang->project->aclList;?>
<?php $requiredFields = $config->project->edit->requiredFields;?>
<?php js::set('requiredFields', $requiredFields);?>
<?php js::set('budget', $project->budget);?>
<?php js::set('budgetOverrun', $lang->project->budgetOverrun);?>
<?php js::set('currencySymbol', $lang->project->currencySymbol)?>
<?php js::set('parentBudget', $lang->project->parentBudget);?>
<?php js::set('beginLessThanParent', $lang->project->beginLessThanParent);?>
<?php js::set('endGreatThanParent', $lang->project->endGreatThanParent);?>
<?php js::set('ignore', $lang->project->ignore);?>
<?php js::set('allProducts', $allProducts);?>
<?php js::set('branchGroups', $branchGroups);?>
<?php js::set('unLinkProductTip', $lang->project->unLinkProductTip);?>
<?php js::set('model', $project->model);?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->project->edit;?></h2>
    </div>
    <form class='form-indicator main-form form-ajax' method='post' target='hiddenwin' id='dataform'>
      <table class='table table-form'>
        <?php if($project->model != 'kanban' or $disableModel == ''):?>
        <tr>
          <th class='w-130px'><?php echo $lang->project->model;?></th>
          <td><?php echo html::select('model', $lang->project->modelList, $model, "class='form-control chosen' required $disableModel");?></td>
        </tr>
        <?php endif;?>
        <?php if(empty($globalDisableProgram)):?>
        <tr>
          <th class='w-120px'><?php echo $lang->project->parent;?></th>
          <?php
          $attr = '';
          if(!isset($programList[$project->parent]))
          {
              echo html::hidden('parent', $project->parent);
              $attr        = 'disabled';
              $programList = array($project->parent => $program->name);
          }
          ?>
          <td><?php echo html::select('parent', $programList, $project->parent, "class='form-control chosen' data-lastSelected='{$project->parent}' $attr");?></td>
          <td colspan='2'></td>
        </tr>
        <?php endif;?>
        <tr>
          <th class='w-120px'><?php echo $lang->project->name;?></th>
          <td class="col-main"><?php echo html::input('name', $project->name, "class='form-control' required");?></td>
          <td colspan='2'></td>
        </tr>
        <?php if(isset($config->setCode) and $config->setCode == 1):?>
        <tr>
          <th><?php echo $lang->project->code;?></th>
          <td><?php echo html::input('code', $project->code, "class='form-control' required");?></td>
        </tr>
        <?php endif;?>
        <?php if($model != 'waterfall' and $model != 'agileplus' and $model != 'waterfallplus'):?>
        <tr>
          <th><?php echo $lang->project->multiple;?></th>
          <td colspan='3'><?php echo nl2br(html::radio('multiple', $lang->project->multipleList, $project->multiple, 'disabled'));?></td>
        </tr>
        <?php endif;?>
        <tr>
          <th id='projectType'><?php echo $lang->project->type;?></th>
          <td colspan='3'><?php echo nl2br(html::radio('hasProduct', $lang->project->projectTypeList, $project->hasProduct, 'disabled'));?></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->PM;?></th>
          <td><?php echo html::select('PM', $PMUsers, $project->PM, "class='form-control chosen'" . (strpos($requiredFields, 'PM') !== false ? ' required' : ''));?></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->budget;?></th>
          <td>
            <div id='budgetBox' class='input-group'>
              <?php $placeholder = ($parentProgram and $parentProgram->budget != 0) ? 'placeholder="' . $lang->project->parentBudget . zget($lang->project->currencySymbol, $parentProgram->budgetUnit) . $availableBudget . '"' : '';?>
              <?php echo html::input('budget', $project->budget != 0 ? $project->budget : '', "class='form-control' onchange='budgetOverrunTips($project->id)' maxlength='10' " . (strpos($requiredFields, 'budget') !== false ? 'required ' : '') . ($project->budget == 0 ? 'disabled ' : '') . $placeholder);?>
              <?php if($parentProgram):?>
              <span class='input-group-addon'><?php echo zget($budgetUnitList, $project->budgetUnit);?></span>
              <?php else:?>
              <span class='input-group-addon'></span>
              <?php echo html::select('budgetUnit', $budgetUnitList, $project->budgetUnit, "class='form-control w-80px'");?>
              <?php endif;?>
            </div>
          </td>
          <td class='futureBox'>
            <div class='checkbox-primary'>
              <input type='checkbox' id='future' name='future' value='1' <?php if($project->budget == 0) echo 'checked';?> />
              <label for='future'><?php echo $lang->project->future;?></label>
            </div>
          </td>
        </tr>
        <tr>
          <th id="dateRange"><?php echo $lang->project->dateRange;?></th>
          <td>
            <div id='dateBox' class='input-group'>
              <?php echo html::input('begin', $project->begin, "class='form-control form-date' onchange='computeWorkDays();' placeholder='" . $lang->project->begin . "' required");?>
              <span class='input-group-addon'><?php echo $lang->project->to;?></span>
              <?php
                $end = $project->end == LONG_TIME ? $lang->project->longTime : $project->end;
                echo html::input('end', $end, "class='form-control form-date' onchange='computeWorkDays();' placeholder='" . $lang->project->end . "' required");
              ?>
            </div>
          </td>
          <?php
          /* Remove LONG_TIME item when no multiple project. */
          if(empty($project->multiple)) unset($lang->project->endList[999]);
          $deltaValue = $project->end == LONG_TIME ? 999 : (strtotime($project->end) - strtotime($project->begin)) / 3600 / 24 + 1;
          ?>
          <td id="endList" colspan='2'><?php echo html::radio('delta', $lang->project->endList, $deltaValue, "onclick='computeEndDate(this.value)'");?></td>
        </tr>
        <tr id='daysBox' <?php if($project->end == LONG_TIME) echo "class='hidden'";?>>
          <th><?php echo $lang->project->days;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('days', $project->days, "class='form-control'");?>
              <span class='input-group-addon'><?php echo $lang->execution->day;?></span>
            </div>
          </td>
          <td></td>
          <td></td>
        </tr>
        <?php if($project->hasProduct and $this->config->vision != 'lite'):?>
        <?php $i = 0;?>
        <?php foreach($linkedProducts as $product):?>
        <tr>
          <th><?php if($i == 0) echo $lang->project->manageProductPlan;?></th>
          <td class='text-left productsBox' colspan="3">
            <div class='row'>
              <div class="col-sm-6">
                <div class='table-row'>
                  <div class='table-col'>
                    <?php $hasBranch = $product->type != 'normal' and isset($branchGroups[$product->id]);?>
                    <div class='input-group <?php if($hasBranch) echo ' has-branch';?>'>
                      <span class='input-group-addon'><?php echo $lang->productCommon;?></span>
                      <?php echo html::select("products[$i]", $allProducts, $product->id, "class='form-control chosen' onchange='loadBranches(this)' data-last='" . $product->id . "' data-type='" . $product->type . "'");?>
                    </div>
                  </div>
                  <div class='table-col <?php if(!$hasBranch) echo 'hidden';?>'>
                    <div class='input-group required'>
                      <span class='input-group-addon fix-border'><?php echo $lang->product->branchName['branch'];?></span>
                      <?php $branchIdList = join(',', $product->branches);?>
                      <?php echo html::select("branch[$i][]", isset($branchGroups[$product->id]) ? $branchGroups[$product->id] : array(), $branchIdList, "class='form-control chosen' multiple onchange=\"loadPlans('#products{$i}', this)\"");?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class='input-group' <?php echo "id='plan$i'";?>>
                  <span class='input-group-addon'><?php echo $lang->product->plan;?></span>
                  <?php echo html::select("plans[$product->id][]", isset($productPlans[$product->id]) ? $productPlans[$product->id] : array(), $product->plans, "class='form-control chosen' multiple");?>
                  <div class='input-group-btn'>
                    <a href='javascript:;' onclick='addNewLine(this)' class='btn btn-link addLine'><i class='icon-plus'></i></a>
                    <a href='javascript:;' onclick='removeLine(this)' class='btn btn-link removeLine' <?php if($i == 0) echo "style='visibility: hidden'";?>><i class='icon-close'></i></a>
                  </div>
                </div>
              </div>
            </div>
          </td>
        </tr>
        <?php $i ++;?>
        <?php endforeach;?>
        <?php endif;?>
        <?php if($project->model == 'waterfall' or $project->model == 'waterfallplus'):?>
        <?php $class    = ($project->stageBy == 'product' and count($linkedProducts) < 2) ? 'hide' : '';?>
        <?php $disabled = !empty($executions) ? "disabled='disabled'" : '';?>
        <tr class='<?php echo $class;?> stageBy'>
          <th><?php echo $lang->project->stageBy;?></th>
          <td colspan='3'>
            <?php echo html::radio('stageBy', $lang->project->stageByList, $project->stageBy == 'project', $disabled);?>
            <icon class='icon icon-help' data-toggle='popover' data-trigger='focus hover' data-placement='right' data-tip-class='text-muted popover-sm' data-content="<?php echo $lang->project->stageByTips;?>"></icon>
          </td>
        </tr>
        <?php endif;?>
        <?php if($project->model == 'kanban'):?>
        <tr>
          <th><?php echo $lang->execution->team;?></th>
          <td colspan='2'><?php echo html::select('teamMembers[]', $users, array_keys($teamMembers), "class='form-control picker-select' multiple"); ?></td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->project->desc;?></th>
          <td colspan='3'>
            <?php echo html::textarea('desc', $project->desc, "rows='6' class='form-control kindeditor' hidefocus='true'" . (strpos($requiredFields, 'desc') !== false ? ' required' : ''));?>
          </td>
        </tr>
        <?php $this->printExtendFields($project, 'table', 'columns=3');?>
        <tr>
          <th><?php echo $lang->project->acl;?></th>
          <td colspan='3' class='aclBox'><?php echo nl2br(html::radio('acl', $aclList, $project->acl, "onclick='setWhite(this.value);'", 'block'));?></td>
        </tr>
        <tr class="<?php if($project->acl == 'open') echo 'hidden';?>" id="whitelistBox">
          <th><?php echo $lang->whitelist;?></th>
          <td colspan='2'>
            <div class='input-group'>
              <?php echo html::select('whitelist[]', $users, $project->whitelist, 'class="form-control picker-select" multiple');?>
              <?php echo $this->fetch('my', 'buildContactLists', "dropdownName=whitelist");?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->project->auth;?></th>
          <td colspan='3'><?php echo html::radio('auth', $lang->project->authList, $project->auth, '', 'block');?></td>
        </tr>
        <tr>
          <td colspan='4' class='text-center form-actions'>
            <?php
              if($disableModel == 'disabled') echo html::hidden('model', $project->model);
              echo html::submitButton();
              if(!isonlybody()) echo html::backButton();
            ?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<div id='projectAcl' class='hidden'>
  <?php echo nl2br(html::radio('acl', $lang->project->aclList, $project->acl == 'project' ? 'private' : 'open', "onclick='setWhite(this.value);'", 'block'));?>
</div>
<div id='programAcl' class='hidden'>
  <?php echo nl2br(html::radio('acl', $lang->project->subAclList, $project->acl, "onclick='setWhite(this.value);'", 'block'));?>
</div>
<?php include '../../common/view/footer.html.php';?>
