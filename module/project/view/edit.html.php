<?php
/**
 * The prjedit view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
<?php js::set('errorSameProducts', $lang->project->errorSameProducts);?>
<?php js::set('oldParent', $project->parent);?>
<?php js::set('projectID', $project->id);?>
<?php js::set('longTime', $lang->project->longTime);?>
<?php js::set('from', $from);?>
<?php js::set('unmodifiableProducts', $unmodifiableProducts)?>
<?php js::set('tip', $lang->project->notAllowRemoveProducts);?>
<?php js::set('linkedProjectsTip', $lang->project->linkedProjectsTip);?>
<?php $aclList = $project->parent ? $lang->program->subAclList : $lang->project->aclList;?>
<?php $requiredFields = $config->project->edit->requiredFields;?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->project->edit;?></h2>
    </div>
    <form class='form-indicator main-form form-ajax' method='post' target='hiddenwin' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th class='w-120px'><?php echo $lang->program->parent;?></th>
          <td><?php echo html::select('parent', $programList, $project->parent, "class='form-control chosen'");?></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->name;?></th>
          <td class="col-main"><?php echo html::input('name', $project->name, "class='form-control' required");?></td><td></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->PM;?></th>
          <td><?php echo html::select('PM', $PMUsers, $project->PM, "class='form-control chosen'" . (strpos($requiredFields, 'PM') !== false ? ' required' : ''));?></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->budget;?></th>
          <td>
            <div class='input-group'>
              <?php $placeholder = ($parentProgram and $parentProgram->budget != 0) ? 'placeholder=' . $lang->project->parentBudget . zget($lang->project->currencySymbol, $parentProgram->budgetUnit) . $availableBudget : '';?>
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
              <label for='future'><?php echo $lang->project->future;?></label>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->project->dateRange;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('begin', $project->begin, "class='form-control form-date' onchange='computeWorkDays();' placeholder='" . $lang->project->begin . "' required");?>
              <span class='input-group-addon'><?php echo $lang->project->to;?></span>
              <?php
                $disabledEnd = $project->end == LONG_TIME ? 'disabled' : '';
                $end         = $project->end == LONG_TIME ? $lang->project->longTime : $project->end;
                echo html::input('end', $end, "class='form-control form-date' onchange='computeWorkDays();' $disabledEnd placeholder='" . $lang->project->end . "' required");
              ?>
            </div>
          </td>
          <?php $deltaValue = $project->end == LONG_TIME ? 999 : (strtotime($project->end) - strtotime($project->begin)) / 3600 / 24 + 1;?>
          <td colspan='2'><?php echo html::radio('delta', $lang->project->endList , $deltaValue, "onclick='computeEndDate(this.value)'");?></td>
        </tr>
        <?php if($project->model == 'scrum'):?>
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
                  <?php echo html::select("products[$i]", $allProducts, $product->id, "class='form-control chosen' data-placeholder={$lang->project->errorNoProducts} onchange='loadBranches(this)' data-last='" . $product->id . "'");?>
                  <span class='input-group-addon fix-border'></span>
                  <?php if($hasBranch) echo html::select("branch[$i]", $branchGroups[$product->id], $product->branch, "class='form-control chosen' onchange=\"loadPlans('#products{$i}', this.value)\"");?>
                </div>
              </div>
              <?php if(in_array($product->id, $unmodifiableProducts)) echo html::hidden("products[$i]", $product->id);?>
              <?php $i++;?>
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
          <th><?php echo $lang->execution->linkPlan;?></th>
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
          <th><?php echo $lang->project->desc;?></th>
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
          <th><?php echo $lang->project->auth;?></th>
          <td colspan='3'><?php echo html::radio('auth', $lang->project->authList, $project->auth, '', 'block');?></td>
        </tr>
        <tr>
          <td colspan='4' class='text-center form-actions'>
            <?php
              echo html::hidden('model', $project->model);
              echo html::submitButton();
              $browseLink = $this->session->projectList ? $this->session->projectList : $this->createLink('project', 'browse');
              echo html::a($browseLink, $lang->goback, '', 'class="btn btn-back btn-wide"');
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
  <?php echo nl2br(html::radio('acl', $lang->program->subAclList, $project->acl, "onclick='setWhite(this.value);'", 'block'));?>
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
