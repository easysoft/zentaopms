<?php
/**
 * The prjedit view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id
 * @link        http://www.zentao.net
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
<?php js::set('beginLetterParent', $lang->project->beginLetterParent);?>
<?php js::set('endGreaterParent', $lang->project->endGreaterParent);?>
<?php js::set('ignore', $lang->project->ignore);?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->project->edit;?></h2>
    </div>
    <form class='form-indicator main-form form-ajax' method='post' target='hiddenwin' id='dataform'>
      <table class='table table-form'>
        <?php if($project->model != 'kanban'):?>
        <tr>
          <th class='w-130px'><?php echo $lang->project->model;?></th>
          <td><?php echo html::select('model', $lang->project->modelList, $model, "class='form-control chosen' required $disableModel");?></td>
        </tr>
        <?php endif;?>
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
          <td><?php echo html::select('parent', $programList, $project->parent, "class='form-control chosen' $attr");?></td>
          <td colspan='2'></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->name;?></th>
          <td class="col-main"><?php echo html::input('name', $project->name, "class='form-control' required");?></td>
        </tr>
        <?php if(!isset($config->setCode) or $config->setCode == 1):?>
        <tr>
          <th><?php echo $lang->project->code;?></th>
          <td><?php echo html::input('code', $project->code, "class='form-control' required");?></td>
        </tr>
        <?php endif;?>
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
              <?php echo html::select('budgetUnit', $budgetUnitList, $project->budgetUnit, "class='form-control'");?>
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
          <?php $deltaValue = $project->end == LONG_TIME ? 999 : (strtotime($project->end) - strtotime($project->begin)) / 3600 / 24 + 1;?>
          <td id="endList" colspan='2'><?php echo html::radio('delta', $lang->project->endList , $deltaValue, "onclick='computeEndDate(this.value)'");?></td>
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
        <tr>
          <th><?php echo $lang->project->manageProducts;?></th>
          <td class='text-left' id='productsBox' colspan="3">
            <div class='row'>
              <?php $i = 0;?>
              <?php foreach($linkedProducts as $product):?>
              <?php $hasBranch = $product->type != 'normal' and isset($branchGroups[$product->id]);?>
              <?php foreach($linkedBranches[$product->id] as $branchID => $branch):?>
              <div class='col-sm-4' style="padding-right: 6px;">
                <div class="input-group<?php if($hasBranch) echo ' has-branch';?>">
                  <?php echo html::select("products[$i]", $allProducts, $product->id, "class='form-control chosen' onchange='loadBranches(this)' data-last='" . $product->id . "' data-type='" . $product->type . "'");?>
                  <span class='input-group-addon fix-border'></span>
                  <?php if($hasBranch) echo html::select("branch[$i]", $branchGroups[$product->id], $branchID, "class='form-control chosen' onchange=\"loadPlans('#products{$i}', this.value)\" data-last='" . $branchID . "'");?>
                </div>
              </div>
              <?php
              if(in_array($product->id, $unmodifiableProducts) and in_array($branchID, $unmodifiableBranches))
              {
                  echo html::hidden("products[$i]", $product->id);
                  echo html::hidden("branch[$i]", $branchID);
              }
              $i++;
              ?>
              <?php endforeach;?>
              <?php endforeach;?>
              <div class='col-sm-4 <?php if($projectID) echo 'required';?>' style="padding-right: 6px;">
                <div class='input-group'>
                  <?php echo html::select("products[$i]", $allProducts, '', "class='form-control chosen' onchange='loadBranches(this)'");?>
                  <span class='input-group-addon fix-border'></span>
                </div>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <th id='linkPlan'><?php echo $lang->execution->linkPlan;?></th>
          <td id="plansBox" colspan="3">
            <div class='row'>
              <?php $i = 0;?>
              <?php foreach($linkedProducts as $product):?>
                <?php foreach($linkedBranches[$product->id] as $branchID => $branch):?>
                <?php $plans = isset($productPlans[$product->id][$branchID]) ? $productPlans[$product->id][$branchID] : array();?>
                <div class="col-sm-4" id="plan<?php echo $i;?>" style="padding-right: 6px;"><?php echo html::select("plans[{$product->id}][{$branchID}][]", $plans, $branches[$product->id][$branchID]->plan, "class='form-control chosen' multiple");?></div>
                <?php $i++;?>
                <?php endforeach;?>
              <?php endforeach;?>
              <div class="col-sm-4" id="planDefault" style="padding-right: 6px;"><?php echo html::select("plans[0][0][]", array(), 0, "class='form-control chosen' multiple");?></div>
            </div>
          </td>
        </tr>
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
              if($disableModel == 'disabled' or $project->model == 'kanban') echo html::hidden('model', $project->model);
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
<div class="modal fade" id="promptBox">
  <div class="modal-dialog mw-600px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon icon-close"></i></button>
        <h4 class="modal-title"><?php printf($lang->project->changeProgram, $project->name);?></h4>
      </div>
      <div class="modal-body">
        <table class='table table-form' id='promptTable'>
          <thead>
            <tr>
              <th class='text-left'><?php echo $lang->project->multiLinkedProductsTip;?></th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
