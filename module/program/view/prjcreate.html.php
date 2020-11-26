<?php
/**
 * The prjcreate view file of program module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     program
 * @version     $Id: prjcreate.html.php 4769 2013-05-05 07:24:21Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::import($jsRoot . 'misc/date.js');?>
<?php js::set('model', $model);?>
<?php js::set('programID', $programID);?>
<?php js::set('copyProjectID', $copyProjectID);?>
<?php js::set('from', $from);?>
<?php js::set('weekend', $config->project->weekend);?>
<?php js::set('errorSameProducts', $lang->program->errorSameProducts);?>
<?php $requiredFields = $config->program->PRJCreate->requiredFields;?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->program->PRJCreate . ' - ' . zget($lang->program->modelList, $model, '');?></h2>
      <div class="pull-right btn-toolbar">
        <button type='button' class='btn btn-link' data-toggle='modal' data-target='#copyProjectModal'><?php echo html::icon($lang->icons['copy'], 'muted') . ' ' . $lang->program->PRJCopy;?></button>
      </div>
    </div>
    <form class='form-indicator main-form form-ajax' method='post' target='hiddenwin' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th class='w-120px'><?php echo $lang->program->PGMParent;?></th>
          <td><?php echo html::select('parent', $programList, $programID, "class='form-control chosen' onchange='setParentProgram(this.value)'");?></td>
          <td>
            <icon class='icon icon-help' data-toggle='tooltip' data-placement='right' title=<?php echo $lang->program->PGMTips;?>></icon>
          </td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->PRJName;?></th>
          <td class="col-main"><?php echo html::input('name', $name, "class='form-control' required");?></td><td></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->PRJCode;?></th>
          <td><?php echo html::input('code', $code, "class='form-control' required");?></td><td></td><td></td>
        </tr>
        <tr class='hidden'>
          <th><?php echo $lang->product->name;?></th>
          <td><?php echo html::input('productName', '', "class='form-control' required");?></td><td></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->manageProducts;?></th>
          <td class='text-left' id='productsBox' colspan="3">
            <div class='row'>
              <?php $i = 0;?>
              <?php foreach($products as $product):?>
              <div class="col-sm-4">
                <?php $hasBranch = $product->type != 'normal' and isset($branchGroups[$product->id]);?>
                <div class="input-group<?php if($hasBranch) echo ' has-branch';?>">
                  <?php echo html::select("products[$i]", $allProducts, $product->id, "class='form-control chosen' onchange='loadBranches(this)' data-last='" . $product->id . "'");?>
                  <span class='input-group-addon fix-border'></span>
                  <?php if($hasBranch) echo html::select("branch[$i]", $branchGroups[$product->id], $product->branch, "class='form-control chosen' onchange=\"loadPlans('#products{$i}', this.value)\"");?>
                </div>
              </div>
              <?php $i++;?>
              <?php endforeach;?>
                <div class='col-sm-4 <?php if($programID) echo 'required';?>'>
                <div class='input-group'>
                  <?php echo html::select("products[$i]", $allProducts, '', "class='form-control chosen' onchange='loadBranches(this)'");?>
                  <span class='input-group-addon'><?php echo html::checkBox('newProduct', $lang->program->addProduct, '', "onchange=addNewProduct(this);");?></span>
                </div>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->project->linkPlan;?></th>
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
              <div class="col-sm-4" id="plan0"><?php echo html::select("plans[]", '', '', "class='form-control chosen'");?></div>
              <?php endif;?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->program->PRJPM;?></th>
          <td><?php echo html::select('PM', $pmUsers, '', "class='form-control chosen'" . (strpos($requiredFields, 'PM') !== false ? ' required' : ''));?></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->PRJBudget;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('budget', '', "class='form-control'" . (strpos($requiredFields, 'budget') !== false ? ' required' : ''));?>
              <span class='input-group-addon'></span>
              <?php echo html::select('budgetUnit', $lang->program->unitList, empty($parentProgram->budgetUnit) ? 'yuan' : $parentProgram->budgetUnit, "class='form-control'");?>
            </div>
          </td>
          <td class='muted'></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->dateRange;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('begin', date('Y-m-d'), "class='form-control form-date' onchange='computeWorkDays();' placeholder='" . $lang->program->begin . "' required");?>
              <span class='input-group-addon'><?php echo $lang->program->to;?></span>
              <?php echo html::input('end', '', "class='form-control form-date' onchange='computeWorkDays();' placeholder='" . $lang->program->end . "' required");?>
            </div>
          </td>
          <td colspan='2'><?php echo html::radio('delta', $lang->program->endList , '', "onclick='computeEndDate(this.value)'");?></td>
        </tr>
        <?php if($model == 'scrum'):?>
        <tr>
          <th><?php echo $lang->project->days;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('days', '', "class='form-control'");?>
              <span class='input-group-addon'><?php echo $lang->project->day;?></span>
            </div>
          </td><td></td><td></td>
        </tr>
        <?php endif;?>
        <?php $this->printExtendFields('', 'table');?>
        <tr>
          <th><?php echo $lang->program->PRJStoryConcept;?></th>
          <td>
            <?php echo html::select('storyConcept', $lang->custom->URSRList, zget($config->custom, 'URSRName', 1), "class='form-control chosen'");?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->program->PRJDesc;?></th>
          <td colspan='3'>
            <?php echo $this->fetch('user', 'ajaxPrintTemplates', 'type=project&link=desc');?>
            <?php echo html::textarea('desc', '', "rows='6' class='form-control kindeditor' hidefocus='true'" . (strpos($requiredFields, 'desc') !== false ? ' required' : ''));?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->program->auth;?></th>
          <td colspan='3'><?php echo html::radio('auth', $lang->program->PRJAuthList, $auth, '', 'block');?></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->acl;?></th>
          <td colspan='3' class='aclBox'><?php echo nl2br(html::radio('acl', $lang->program->PRJAclList, $acl, "onclick='setWhite(this.value);'", 'block'));?></td>
        </tr>
        <tr class="hidden" id="whitelistBox">
          <th><?php echo $lang->whitelist;?></th>
          <td><?php echo html::select('whitelist[]', $users, '', 'class="form-control chosen" multiple');?></td>
          <td></td>
          <td></td>
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
        <?php echo $lang->program->copyTitle;?> 
        <?php if($programID) $lang->program->PRJStandalone = zget($programList, $programID, $lang->noData);?>
        <span class="label label-primary label-outline"><?php echo $lang->program->PRJSource . $lang->program->PRJStandalone;?></span>
      </h4>
    </div>
    <div class='modal-body'>
      <?php if(empty($copyProjects)):?>
      <div class='alert with-icon'>
        <i class='icon-exclamation-sign'></i>
        <div class='content'><?php echo $lang->program->copyNoProject;?></div>
      </div>
      <?php else:?>
      <div id='copyProjects' class='row'>
      <?php foreach ($copyProjects as $id => $name):?>
      <?php if(empty($id)):?>
        <?php if($copyProjectID != 0):?>
        <div class='col-md-4 col-sm-6'><a href='javascript:;' data-id='' class='cancel'><?php echo html::icon($lang->icons['cancel']) . ' ' . $lang->project->cancelCopy;?></a></div>
        <?php endif;?>
      <?php else: ?>
      <div class='col-md-4 col-sm-6'><a href='javascript:;' data-id='<?php echo $id;?>' class='nobr <?php echo ($copyProjectID == $id) ? ' active' : '';?>'><?php echo html::icon($lang->icons['project'], 'text-muted') . ' ' . $name;?></a></div>
      <?php endif; ?>
      <?php endforeach;?>
      </div>
      <?php endif;?>
    </div>
  </div>
</div>
<div id='PRJAcl' class='hidden'>
  <?php echo nl2br(html::radio('acl', $lang->program->PRJAclList, $acl, "onclick='setWhite(this.value);'", 'block'));?>
</div>
<div id='PGMAcl' class='hidden'>
  <?php echo nl2br(html::radio('acl', $lang->program->PGMPRJAclList, $acl, "onclick='setWhite(this.value);'", 'block'));?>
</div>
<?php include '../../common/view/footer.html.php';?>
