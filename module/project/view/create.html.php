<?php
/**
 * The create view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: create.html.php 4769 2013-05-05 07:24:21Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::import($jsRoot . 'misc/date.js');?>
<?php js::set('model', $model);?>
<?php js::set('programID', $programID);?>
<?php js::set('copyProjectID', $copyProjectID);?>
<?php js::set('weekend', $config->execution->weekend);?>
<?php js::set('errorSameProducts', $lang->project->errorSameProducts);?>
<?php js::set('longTime', $lang->project->longTime);?>
<?php $requiredFields = $config->project->create->requiredFields;?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <?php $createTitle = isset($this->config->maxVersion) ? $lang->project->create . ' - ' . zget($lang->project->modelList, $model, '') : $lang->project->create;?>
      <h2><?php echo $createTitle;?></h2>
      <div class="pull-right btn-toolbar">
        <button type='button' class='btn btn-link' data-toggle='modal' data-target='#copyProjectModal'><?php echo html::icon($lang->icons['copy'], 'muted') . ' ' . $lang->project->copy;?></button>
      </div>
    </div>
    <form class='form-indicator main-form form-ajax' method='post' target='hiddenwin' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th class='w-120px'><?php echo $lang->project->parent;?></th>
          <td><?php echo html::select('parent', $programList, $programID, "class='form-control chosen' onchange='setParentProgram(this.value)'");?></td>
          <td>
            <icon class='icon icon-help' data-toggle='popover' data-trigger='focus hover' data-placement='right' data-tip-class='text-muted popover-sm' data-content="<?php echo $lang->program->tips;?>"></icon>
          </td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->name;?></th>
          <td class="col-main"><?php echo html::input('name', $name, "class='form-control' required");?></td><td></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->PM;?></th>
          <td><?php echo html::select('PM', $pmUsers, '', "class='form-control chosen'" . (strpos($requiredFields, 'PM') !== false ? ' required' : ''));?></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->budget;?></th>
          <td>
            <div class='input-group'>
              <?php $placeholder = ($parentProgram and $parentProgram->budget != 0) ? 'placeholder=' . $lang->program->parentBudget . zget($lang->project->currencySymbol, $parentProgram->budgetUnit) . $availableBudget : '';?>
              <?php echo html::input('budget', '', "class='form-control' " . (strpos($requiredFields, 'budget') !== false ? 'required ' : '') . $placeholder);?>
              <?php if($parentProgram):?>
              <span class='input-group-addon'><?php echo zget($budgetUnitList, $parentProgram->budgetUnit);?></span>
              <?php else:?>
              <span class='input-group-addon'></span>
              <?php echo html::select('budgetUnit', $budgetUnitList, $config->project->defaultCurrency, "class='form-control'");?>
              <?php endif;?>
            </div>
          </td>
          <td>
            <div class='checkbox-primary'>
              <input type='checkbox' id='future' name='future' value='1' />
              <label for='future'><?php echo $lang->project->future;?></label>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->project->dateRange;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('begin', date('Y-m-d'), "class='form-control form-date' onchange='computeWorkDays();' placeholder='" . $lang->project->begin . "' required");?>
              <span class='input-group-addon'><?php echo $lang->project->to;?></span>
              <?php echo html::input('end', '', "class='form-control form-date' onchange='computeWorkDays();' placeholder='" . $lang->project->end . "' required");?>
            </div>
          </td>
          <td colspan='2'><?php echo html::radio('delta', $lang->project->endList , '', "onclick='computeEndDate(this.value)'");?></td>
        </tr>
        <?php if($model == 'scrum'):?>
        <tr id='daysBox'>
          <th><?php echo $lang->execution->days;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('days', '', "class='form-control'");?>
              <span class='input-group-addon'><?php echo $lang->execution->day;?></span>
            </div>
          </td><td></td><td></td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->project->manageProducts;?></th>
          <td class='text-left' id='productsBox' colspan="3">
            <div class='row'>
              <?php $i = 0;?>
              <?php foreach($products as $product):?>
              <div class="col-sm-4" style="margin-bottom: 10px; padding-right: 6px;">
                <?php $hasBranch = $product->type != 'normal' and isset($branchGroups[$product->id]);?>
                <div class="input-group<?php if($hasBranch) echo ' has-branch';?>">
                  <?php echo html::select("products[$i]", $allProducts, $product->id, "class='form-control chosen' onchange='loadBranches(this)' data-last='" . $product->id . "'");?>
                  <span class='input-group-addon fix-border'></span>
                  <?php if($hasBranch) echo html::select("branch[$i]", $branchGroups[$product->id], $product->branch, "class='form-control chosen' onchange=\"loadPlans('#products{$i}', this.value)\"");?>
                </div>
              </div>
              <?php $i++;?>
              <?php endforeach;?>
              <div class='col-sm-4 <?php if($programID) echo 'required';?>' style="padding-right: 6px;">
                <div class='input-group'>
                  <?php echo html::select("products[$i]", $allProducts, '', "class='form-control chosen' onchange='loadBranches(this)'");?>
                  <span class='input-group-addon'><?php echo html::checkBox('newProduct', $lang->project->addProduct, '', "onchange=addNewProduct(this);");?></span>
                </div>
              </div>
            </div>
          </td>
        </tr>
        <tr class='hidden'>
          <th><?php echo $lang->product->name;?></th>
          <td><?php echo html::input('productName', '', "class='form-control' required");?></td><td></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->execution->linkPlan;?></th>
          <td colspan="3" id="plansBox">
            <div class='row'>
              <?php if($copyProjectID):?>
              <?php $i = 0;?>
              <?php foreach($products as $product):?>
              <?php $plans = zget($productPlans, $product->id, array(0 => ''));?>
              <div class="col-sm-4" id="plan<?php echo $i;?>"><?php echo html::select("plans[" . $product->id . "]", $plans, '', "class='form-control chosen'");?></div>
              <?php $i++;?>
              <?php endforeach;?>
              <?php else:?>
              <div class="col-sm-4" id="plan0" style="padding-right: 6px;"><?php echo html::select("plans[]", '', '', "class='form-control chosen'");?></div>
              <?php endif;?>
            </div>
          </td>
        </tr>
        <?php $this->printExtendFields('', 'table');?>
        <tr>
          <th><?php echo $lang->project->desc;?></th>
          <td colspan='3'>
            <?php echo html::textarea('desc', '', "rows='6' class='form-control kindeditor' hidefocus='true'" . (strpos($requiredFields, 'desc') !== false ? ' required' : ''));?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->project->acl;?></th>
          <td colspan='3' class='aclBox'><?php echo nl2br(html::radio('acl', $lang->project->aclList, $acl, "onclick='setWhite(this.value);'", 'block'));?></td>
        </tr>
        <tr class="hidden" id="whitelistBox">
          <th><?php echo $lang->whitelist;?></th>
          <td><?php echo html::select('whitelist[]', $users, '', 'class="form-control chosen" multiple');?></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->auth;?></th>
          <td colspan='3'><?php echo html::radio('auth', $lang->project->authList, $auth, '', 'block');?></td>
        </tr>
        <tr>
          <td colspan='4' class='text-center form-actions'>
            <?php
              echo html::hidden('model', $model);
              echo html::submitButton();
              echo html::backButton();
            ?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<div class='modal fade modal-scroll-inside' id='copyProjectModal'>
  <div class='modal-dialog mw-900px'>
    <div class='modal-header'>
      <button type='button' class='close' data-dismiss='modal'><i class="icon icon-close"></i></button>
      <h4 class='modal-title' id='myModalLabel'>
        <?php echo $lang->project->copyTitle;?>
        <?php echo html::input('projectName', '', "class='form-control' placeholder={$lang->project->searchByName}");?>
      </h4>
    </div>
    <div class='modal-body'>
      <?php if(empty($copyProjects)):?>
      <div class='alert with-icon'>
        <i class='icon-exclamation-sign'></i>
        <div class='content'><?php echo $lang->project->copyNoProject;?></div>
      </div>
      <?php else:?>
      <div id='copyProjects' class='row'>
      <?php foreach ($copyProjects as $id => $name):?>
        <?php $active = ($copyProjectID == $id) ? ' active' : '';?>
        <div class='col-md-4 col-sm-6'><a href='javascript:;' data-id='<?php echo $id;?>' class='nobr <?php echo $active;?>'><?php echo html::icon($lang->icons['project'], 'text-muted') . ' ' . $name;?></a></div>
      <?php endforeach;?>
      </div>
      <?php endif;?>
    </div>
  </div>
</div>
<div id='projectAcl' class='hidden'>
  <?php echo nl2br(html::radio('acl', $lang->project->aclList, $acl, "onclick='setWhite(this.value);'", 'block'));?>
</div>
<div id='programAcl' class='hidden'>
  <?php echo nl2br(html::radio('acl', $lang->program->subAclList, $acl, "onclick='setWhite(this.value);'", 'block'));?>
</div>
<?php include '../../common/view/footer.html.php';?>
