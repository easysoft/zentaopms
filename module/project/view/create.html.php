<?php
/**
 * The create view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: create.html.php 4769 2013-05-05 07:24:21Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::import($jsRoot . 'misc/date.js');?>
<?php js::set('LONG_TIME', LONG_TIME);?>
<?php js::set('systemMode', $config->systemMode);?>
<?php js::set('model', $model);?>
<?php js::set('programID', $programID);?>
<?php js::set('copyProjectID', $copyProjectID);?>
<?php js::set('weekend', $config->execution->weekend);?>
<?php js::set('errorSameProducts', $lang->project->errorSameProducts);?>
<?php js::set('errorSameBranches', $lang->project->errorSameBranches);?>
<?php js::set('errorSamePlans', $lang->project->errorSamePlans);?>
<?php js::set('longTime', $lang->project->longTime);?>
<?php js::set('multiBranchProducts', $multiBranchProducts);?>
<?php js::set('selectedProductID', $productID);?>
<?php js::set('selectedBranchID', $branchID);?>
<?php js::set('productName', $lang->product->name);?>
<?php js::set('manageProductPlan', $lang->project->manageProductPlan);?>
<?php js::set('budgetOverrun', $lang->project->budgetOverrun);?>
<?php js::set('currencySymbol', $lang->project->currencySymbol)?>
<?php js::set('parentBudget', $lang->project->parentBudget);?>
<?php js::set('beginLessThanParent', $lang->project->beginLessThanParent);?>
<?php js::set('endGreatThanParent', $lang->project->endGreatThanParent);?>
<?php js::set('ignore', $lang->project->ignore);?>
<?php $requiredFields = $config->project->create->requiredFields;?>
<?php js::set('requiredFields', $requiredFields);?>
<?php js::set('copyType', '');?>
<?php js::set('nameTips', $lang->project->copyProject->nameTips);?>
<?php js::set('codeTips', $lang->project->copyProject->codeTips);?>
<?php js::set('endTips', $lang->project->copyProject->endTips);?>
<?php js::set('daysTips', $lang->project->copyProject->daysTips);?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <?php $createTitle = $lang->project->create . ' - ' . zget($lang->project->modelList, $model, '');?>
      <h2><?php echo $createTitle;?></h2>
      <?php if(!commonModel::isTutorialMode()): ?>
      <div class="pull-right btn-toolbar">
      <?php if($config->edition != 'max' or $model == 'kanban'):?>
        <button type='button' class='btn btn-link' data-toggle='modal' data-target='#copyProjectModal'><?php echo html::icon($lang->icons['copy'], 'muted') . ' ' . $lang->project->copy;?></button>
      <?php else: ?>
        <button type='button' class='btn btn-link open-btn' data-toggle='modal' data-target='#maxCopyProjectModal'><?php echo html::icon($lang->icons['copy'], 'muted') . ' ' . $lang->project->copy;?></button>
      <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
    <form class='form-indicator main-form form-ajax' method='post' target='hiddenwin' id='dataform'>
      <table class='table table-form'>
        <tr class='<?php echo !empty($globalDisableProgram) ? 'hidden' : '';?>'>
          <th class='w-130px'><?php echo $lang->project->parent;?></th>
          <?php $disabled = ($this->app->tab == 'product' and $productID) ? 'disabled' : '';?>
          <td><?php echo html::select('parent', $programList, $programID, "class='form-control chosen' data-lastSelected=$programID onchange='setParentProgram(this.value)' $disabled");?></td>
          <?php if($disabled) echo html::hidden('parent', $programID);?>
          <td>
            <icon class='icon icon-help' data-toggle='popover' data-trigger='focus hover' data-placement='right' data-tip-class='text-muted popover-sm' data-content="<?php echo $lang->program->tips;?>"></icon>
          </td>
          <td></td>
        </tr>
        <tr>
          <th class='w-130px'><?php echo $lang->project->name;?></th>
          <td class="col-main"><?php echo html::input('name', $copyProjectID ? $copyProject->name : '', "class='form-control' required");?></td>
          <td></td>
        </tr>
        <?php if(isset($config->setCode) and $config->setCode == 1):?>
        <tr>
          <th><?php echo $lang->project->code;?></th>
          <td><?php echo html::input('code', $copyProjectID ? $copyProject->code : '', "class='form-control' required");?></td>
        </tr>
        <?php endif;?>
        <?php if($model == 'scrum' or $model == 'kanban'):?>
        <tr>
          <th><?php echo $lang->project->multiple;?></th>
          <td colspan='3'>
            <?php
            echo nl2br(html::radio('multiple', $lang->project->multipleList, '1', $copyProjectID ? 'disabled' : ''));
            if($copyProjectID) echo html::hidden('multiple', $copyProject->multiple);
            ?>
          </td>
        </tr>
        <?php endif;?>
        <tr>
          <th id='projectType'><?php echo $lang->project->type;?></th>
          <td>
            <?php
            echo html::radio('hasProduct', $lang->project->projectTypeList, '1', $copyProjectID ? 'disabled' : '');
            if($copyProjectID) echo html::hidden('hasProduct', $copyProject->hasProduct);
            ?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->project->PM;?></th>
          <td><?php echo html::select('PM', $pmUsers, '', "class='form-control chosen'" . (strpos($requiredFields, 'PM') !== false ? ' required' : ''));?></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->budget;?></th>
          <td>
            <div id='budgetBox' class='input-group'>
              <?php $placeholder = ($parentProgram and $parentProgram->budget != 0) ? 'placeholder="' . $lang->program->parentBudget . zget($lang->project->currencySymbol, $parentProgram->budgetUnit) . $availableBudget . '"' : '';?>
              <?php echo html::input('budget', '', "class='form-control' onchange='budgetOverrunTips()' maxlength='10' " . (strpos($requiredFields, 'budget') !== false ? 'required ' : '') . $placeholder);?>
              <?php if($parentProgram):?>
              <span class='input-group-addon'><?php echo zget($budgetUnitList, $parentProgram->budgetUnit);?></span>
              <?php else:?>
              <span class='input-group-addon'></span>
              <?php echo html::select('budgetUnit', $budgetUnitList, $config->project->defaultCurrency, "class='form-control'");?>
              <?php endif;?>
            </div>
          </td>
          <td class='futureBox'>
            <div class="checkbox-primary c-future <?php echo strpos($requiredFields, 'budget') !== false ? 'hidden' : '';?>">
              <input type='checkbox' id='future' name='future' value='1' />
              <label for='future'><?php echo $lang->project->future;?></label>
            </div>
          </td>
        </tr>
        <tr>
          <th id="dateRange"><?php echo $lang->project->dateRange;?></th>
          <td>
            <div id='dateBox' class='input-group'>
              <?php echo html::input('begin', date('Y-m-d'), "class='form-control form-date' onchange='computeWorkDays();' placeholder='" . $lang->project->begin . "' required");?>
              <span class='input-group-addon'><?php echo $lang->project->to;?></span>
              <?php echo html::input('end', '', "class='form-control form-date' onchange='computeWorkDays();' placeholder='" . $lang->project->end . "' required");?>
            </div>
          </td>
          <td id="endList" colspan='2'><?php echo html::radio('delta', $lang->project->endList, '', "onclick='computeEndDate(this.value)'");?></td>
        </tr>
        <tr id='daysBox'>
          <th><?php echo $lang->execution->days;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('days', '', "class='form-control'");?>
              <span class='input-group-addon'><?php echo $lang->execution->day;?></span>
            </div>
          </td><td></td><td></td>
        </tr>
        <?php if(!empty($products)):?>
        <?php $i = 0;?>
        <?php foreach($products as $product):?>
        <tr>
          <th id='productTitle'><?php if($i == 0) echo $lang->project->manageProductPlan;?></th>
          <td class='text-left productsBox' colspan="3">
            <div class='row'>
              <div class="col-sm-6">
                <div class='table-row'>
                  <div class='table-col'>
                    <?php $hasBranch = $product->type != 'normal' and isset($branchGroups[$product->id]);?>
                    <div class='input-group required <?php if($hasBranch) echo ' has-branch';?>'>
                      <span class='input-group-addon'><?php echo $lang->productCommon;?></span>
                      <?php echo html::select("products[$i]", $allProducts, $product->id, "class='form-control chosen' onchange='loadBranches(this)' data-last='" . $product->id . "' data-type='" . $product->type . "'");?>
                    </div>
                  </div>
                  <div class='table-col <?php if(!$hasBranch) echo 'hidden';?>'>
                    <div class='input-group required'>
                      <span class='input-group-addon fix-border'><?php echo $lang->project->branch;?></span>
                      <?php $branchIdList = join(',', $product->branches);?>
                      <?php echo html::select("branch[$i][]", isset($branchGroups[$product->id]) ? $branchGroups[$product->id] : array(), $branchIdList, "class='form-control chosen' multiple onchange=\"loadPlans('#products{$i}', this)\"");?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class='input-group' <?php echo "id='plan$i'";?>>
                  <span class='input-group-addon'><?php echo $lang->product->plan;?></span>
                  <?php echo html::select("plans[$product->id][]", isset($copyProject->productPlans[$product->id]) ? $copyProject->productPlans[$product->id] : array(), $product->plans, "class='form-control chosen' multiple");?>
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
        <?php else:?>
        <tr>
          <th id='productTitle'><?php echo $lang->project->manageProductPlan;?></th>
          <td class='text-left productsBox' colspan='3'>
            <div class='row'>
              <div class="col-sm-6">
                <div class='table-row'>
                  <div class='table-col'>
                    <div class='input-group required'>
                      <span class='input-group-addon'><?php echo $lang->productCommon;?></span>
                      <?php echo html::select("products[0]", $allProducts, '', "class='form-control chosen' onchange='loadBranches(this)'");?>
                      <?php if(common::hasPriv('product', 'create')):?>
                      <span class='input-group-addon newProduct'>
                        <?php echo html::checkBox('newProduct', $lang->project->addProduct, '', "onchange=addNewProduct(this);");?>
                        <div><icon class='icon icon-help' data-toggle='popover' data-trigger='focus hover' data-placement='left' data-tip-class='text-muted popover-sm' data-content="<?php echo $lang->project->productTip;?>"></icon></div>
                      </span>
                      <?php endif;?>
                    </div>
                  </div>
                  <div class='table-col hidden'>
                    <div class='input-group required'>
                      <span class='input-group-addon fix-border'><?php echo $lang->project->branch;?></span>
                      <?php echo html::select("branch", '', '', "class='form-control chosen' multiple");?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class='input-group' id='plan0'>
                  <span class='input-group-addon'><?php echo $lang->product->plan;?></span>
                  <?php echo html::select("plans[][]", '', '', "class='form-control chosen' multiple");?>
                  <div class='input-group-btn'>
                    <a href='javascript:;' onclick='addNewLine(this)' class='btn btn-link addLine'><i class='icon-plus'></i></a>
                    <a href='javascript:;' onclick='removeLine(this)' class='btn btn-link removeLine' style='visibility: hidden'><i class='icon-close'></i></a>
                  </div>
                </div>
              </div>
            </div>
            <div class="input-group addProduct hidden">
              <?php echo html::input('productName', '', "class='form-control'");?>
              <span class='input-group-addon required'><?php echo html::checkBox('newProduct', $lang->project->addProduct, '', "onchange=addNewProduct(this);");?></span>
            </div>
          </td>
        </tr>
        <?php endif;?>
        <?php if($model == 'waterfall' or $model == 'waterfallplus'):?>
        <tr class='hide stageBy'>
          <th><?php echo $lang->project->stageBy;?></th>
          <td>
            <?php echo html::radio('stageBy', $lang->project->stageByList, '0');?>
            <icon class='icon icon-help' data-toggle='popover' data-trigger='focus hover' data-placement='right' data-tip-class='text-muted popover-sm' data-content="<?php echo $lang->project->stageByTips;?>"></icon>
          </td>
        </tr>
        <?php endif;?>
        <?php if($model == 'kanban'):?>
        <tr>
          <th><?php echo $lang->execution->team;?></th>
          <td colspan='3'><?php echo html::select('teamMembers[]', $users, '', "class='form-control picker-select' multiple"); ?></td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->project->desc;?></th>
          <td colspan='3'>
            <?php echo html::textarea('desc', '', "rows='6' class='form-control kindeditor' hidefocus='true'" . (strpos($requiredFields, 'desc') !== false ? ' required' : ''));?>
          </td>
        </tr>
        <?php $this->printExtendFields(isset($project) ? $project : '', 'table', 'columns=3');?>
        <tr>
          <th><?php echo $lang->project->acl;?></th>
          <td colspan='3' class='aclBox'><?php echo nl2br(html::radio('acl', $lang->project->aclList, $copyProjectID ? $copyProject->acl : 'private', "onclick='setWhite(this.value);'", 'block'));?></td>
        </tr>
        <tr class="hidden" id="whitelistBox">
          <th><?php echo $lang->whitelist;?></th>
          <td colspan='2'>
            <div class='input-group'>
              <?php echo html::select('whitelist[]', $users, '', 'class="form-control picker-select" multiple');?>
              <?php echo $this->fetch('my', 'buildContactLists', "dropdownName=whitelist");?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->project->auth;?></th>
          <td colspan='3'><?php echo html::radio('auth', $lang->project->authList, $copyProjectID ? $copyProject->auth : 'extend', '', 'block');?></td>
        </tr>
        <tr>
          <td colspan='4' class='text-center form-actions'>
            <?php
              if($copyProjectID) echo html::hidden('hasProduct', $hasProduct);
              echo html::hidden('model', $model);
              echo html::submitButton();
              echo $gobackLink ? html::a($gobackLink, $lang->goback, '', 'class="btn btn-wide"') : html::backButton();
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
      <?php foreach($copyProjects as $id => $name):?>
        <?php $active = ($copyProjectID == $id) ? ' active' : '';?>
        <div class='col-md-4 col-sm-6'><a href='javascript:;' data-id='<?php echo $id;?>' class='nobr <?php echo $active;?>'><?php echo html::icon($lang->icons['project'], 'text-muted') . ' ' . $name;?></a></div>
      <?php endforeach;?>
      </div>
      <?php endif;?>
    </div>
  </div>
</div>
<div id='projectAcl' class='hidden'>
  <?php echo nl2br(html::radio('acl', $lang->project->aclList, $copyProjectID ? $copyProject->acl : 'private', "onclick='setWhite(this.value);'", 'block'));?>
</div>
<div id='programAcl' class='hidden'>
  <?php echo nl2br(html::radio('acl', $lang->project->subAclList, $copyProjectID ? $copyProject->acl : 'private', "onclick='setWhite(this.value);'", 'block'));?>
</div>
<?php include '../../common/view/footer.html.php';?>
