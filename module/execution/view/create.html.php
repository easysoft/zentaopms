<?php
/**
 * The create view of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: create.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php if($app->openApp == 'execution'): ?>
<style>#heading {padding: 8px 0;}</style>
<?php endif;?>
<?php if(isset($tips)):?>
<?php $defaultURL = $config->systemMode == 'new' ? $this->createLink('project', 'execution', "status=all&projectID=$projectID") : $this->createLink('execution', 'task', 'executionID=' . $executionID);?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('isStage', false);?>
<body>
  <div class='modal-dialog mw-500px' id='tipsModal'>
    <div class='modal-header'>
      <a href='<?php echo $defaultURL;?>' class='close'><i class="icon icon-close"></i></a>
      <h4 class='modal-title' id='myModalLabel'><?php echo $lang->execution->tips;?></h4>
    </div>
    <div class='modal-body'>
    <?php echo $tips;?>
    </div>
  </div>
</body>
</html>
<?php exit;?>
<?php endif;?>

<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::import($jsRoot . 'misc/date.js');?>
<?php js::set('weekend', $config->execution->weekend);?>
<?php js::set('holders', $lang->execution->placeholder);?>
<?php js::set('errorSameProducts', $lang->execution->errorSameProducts);?>
<?php js::set('productID', empty($productID) ? 0 : $productID);?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo (($from == 'execution') and ($config->systemMode == 'new')) ? $lang->execution->createExec : $lang->execution->create;?></h2>
      <div class="pull-right btn-toolbar">
        <button type='button' class='btn btn-link' data-toggle='modal' data-target='#copyProjectModal'><?php echo html::icon($lang->icons['copy'], 'muted') . ' ' . ((($from == 'execution') and ($config->systemMode == 'new')) ? $lang->execution->copyExec : $lang->execution->copy);?></button>
      </div>
    </div>
    <form class='form-indicator main-form form-ajax' method='post' target='hiddenwin' id='dataform'>
      <table class='table table-form'>
        <?php if($config->systemMode == 'new'):?>
        <tr>
          <th class='w-120px'><?php echo $lang->execution->project;?></th>
          <td class="col-main"><?php echo html::select("project", $allProjects, $projectID, "class='form-control chosen' required onchange='refreshPage(this.value)'");?></td>
          <td></td><td></td>
        </tr>
        <?php endif;?>
        <tr>
          <th class='w-120px'><?php echo (($from == 'execution') and ($config->systemMode == 'new')) ? $lang->execution->execName : $lang->execution->name;?></th>
          <td class="col-main"><?php echo html::input('name', $name, "class='form-control' required");?></td>
          <td></td><td></td>
        </tr>
        <tr>
          <th><?php echo (($from == 'execution') and ($config->systemMode == 'new')) ? $lang->execution->execCode : $lang->execution->code;?></th>
          <td><?php echo html::input('code', $code, "class='form-control' required");?></td><td></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->execution->dateRange;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('begin', (isset($plan) && !empty($plan->begin) ? $plan->begin : date('Y-m-d')), "class='form-control form-date' onchange='computeWorkDays()' placeholder='" . $lang->execution->begin . "' required");?>
              <span class='input-group-addon'><?php echo $lang->execution->to;?></span>
              <?php echo html::input('end', (isset($plan) && !empty($plan->end) ? $plan->end : ''), "class='form-control form-date' onchange='computeWorkDays()' placeholder='" . $lang->execution->end . "' required");?>
            </div>
          </td>
          <td colspan='2'><?php echo html::radio('delta', $lang->execution->endList , '', "onclick='computeEndDate(this.value)'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->execution->days;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('days', (isset($plan) && !empty($plan->begin) ? helper::workDays($plan->begin, $plan->end) : ''), "class='form-control'");?>
              <span class='input-group-addon'><?php echo $lang->execution->day;?></span>
            </div>
          </td><td></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->execution->teamname;?></th>
          <td><?php echo html::input('team', $team, "class='form-control'");?></td><td></td><td></td>
        </tr>
        <tr>
          <th><?php echo (($from == 'execution') and ($config->systemMode == 'new')) ? $lang->execution->execType : $lang->execution->type;?></th>
          <td><?php echo html::select('lifetime', $lang->execution->lifeTimeList, '', "class='form-control' onchange='showLifeTimeTips()'"); ?></td>
          <td class='muted' colspan='2'><div id='lifeTimeTips'><?php echo $lang->execution->typeDesc;?></div></td>
        </tr>
        <tr class='hide'>
          <th><?php echo $lang->execution->status;?></th>
          <td><?php echo html::hidden('status', 'wait');?></td>
          <td></td>
          <td></td>
        </tr>
        <?php $this->printExtendFields('', 'table', 'columns=3');?>
        <tr>
          <th><?php echo $lang->execution->manageProducts;?></th>
          <td class='text-left' id='productsBox' colspan="3">
            <div class='row'>
              <?php $i = 0;?>
              <?php foreach($products as $product):?>
              <div class='col-sm-4'>
                <?php $hasBranch = $product->type != 'normal' and isset($branchGroups[$product->id]);?>
                <div class="input-group<?php if($hasBranch) echo ' has-branch';?>">
                  <?php echo html::select("products[$i]", $allProducts, $product->id, "class='form-control chosen' onchange='loadBranches(this)' data-last='" . $product->id . "'");?>
                  <span class='input-group-addon fix-border'></span>
                  <?php if($hasBranch) echo html::select("branch[$i]", $branchGroups[$product->id], $product->branch, "class='form-control chosen' onchange=\"loadPlans('#products{$i}', this.value)\"");?>
                </div>
              </div>
              <?php $i++;?>
              <?php endforeach;?>
              <div class='col-sm-4'>
                <div class="input-group">
                  <?php echo html::select("products[$i]", $allProducts, '', "class='form-control chosen' onchange='loadBranches(this)'");?>
                  <span class='input-group-addon fix-border'></span>
                </div>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->execution->linkPlan;?></th>
          <td colspan="3" id="plansBox">
            <div class='row'>
              <?php if(isset($plan) && !empty($plan->begin)):?>
              <div class="col-sm-4" id="plan0"><?php echo html::select("plans[" . $plan->product . "]", $productPlan, $plan->id, "class='form-control chosen'");?></div>
              <?php js::set('currentPlanID', $plan->id)?>
              <?php elseif($copyExecutionID):?>
              <?php $i = 0;?>
              <?php foreach($products as $product):?>
              <?php $plans = zget($productPlans, $product->id, array(0 => ''));?>
              <div class="col-sm-4" id="plan<?php echo $i;?>"><?php echo html::select("plans[" . $product->id . "]", $plans, '', "class='form-control chosen'");?></div>
              <?php $i++;?>
              <?php endforeach;?>
              <?php else:?>
              <div class="col-sm-4" id="plan0"><?php echo html::select("plans[]", $productPlan, '', "class='form-control chosen'");?></div>
              <?php js::set('currentPlanID', '')?>
              <?php endif;?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo (($from == 'execution') and ($config->systemMode == 'new')) ? $lang->execution->execDesc : $lang->execution->desc;?></th>
          <td colspan='3'>
            <?php echo $this->fetch('user', 'ajaxPrintTemplates', 'type=execution&link=desc');?>
            <?php echo html::textarea('desc', '', "rows='6' class='form-control kindeditor' hidefocus='true'");?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->execution->acl;?></th>
          <td colspan='3'><?php echo nl2br(html::radio('acl', $lang->execution->aclList, $acl, "onclick='setWhite(this.value);'", 'block'));?></td>
        </tr>
        <tr class="hidden" id="whitelistBox">
          <th><?php echo $lang->whitelist;?></th>
          <td><?php echo html::select('whitelist[]', $users, $whitelist, 'class="form-control chosen" multiple');?></td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td colspan='4' class='text-center form-actions'>
            <?php echo html::submitButton();?>
            <?php echo html::backButton();?>
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
      <h4 class='modal-title' id='myModalLabel'><?php echo $lang->execution->copyTitle;?></h4>
    </div>
    <div class='modal-body'>
      <?php if(count($executions) == 1):?>
      <div class='alert with-icon'>
        <i class='icon-exclamation-sign'></i>
        <div class='content'><?php echo $lang->execution->copyNoExecution;?></div>
      </div>
      <?php else:?>
      <div id='copyProjects' class='row'>
      <?php foreach ($executions as $id => $execution):?>
      <?php if(empty($id)):?>
      <?php if($copyExecutionID != 0):?>
      <div class='col-md-4 col-sm-6'><a href='javascript:;' data-id='' class='cancel'><?php echo html::icon($lang->icons['cancel']) . ' ' . $lang->execution->cancelCopy;?></a></div>
      <?php endif;?>
      <?php else: ?>
      <div class='col-md-4 col-sm-6'><a href='javascript:;' data-id='<?php echo $id;?>' class='nobr <?php echo ($copyExecutionID == $id) ? ' active' : '';?>'><?php echo html::icon($lang->icons[$execution->type], 'text-muted') . ' ' . $execution->name;?></a></div>
      <?php endif; ?>
      <?php endforeach;?>
      </div>
      <?php endif;?>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
