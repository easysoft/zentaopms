<?php
/**
 * The create view of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: create.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php if(isset($tips)):?>
<?php $defaultURL = $this-> createLink('project', 'task', 'projectID=' . $projectID);?>
<?php include '../../common/view/header.lite.html.php';?>
<body>
  <div class='modal-dialog mw-500px' id='tipsModal'>
    <div class='modal-header'>
      <a href='<?php echo $defaultURL;?>' class='close'><i class="icon icon-close"></i></a>
      <h4 class='modal-title' id='myModalLabel'><?php echo $lang->project->tips;?></h4>
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
<?php js::set('weekend', $config->project->weekend);?>
<?php js::set('holders', $lang->project->placeholder);?>
<?php js::set('errorSameProducts', $lang->project->errorSameProducts);?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->project->create;?></h2>
      <div class="pull-right btn-toolbar">
        <button type='button' class='btn btn-link' data-toggle='modal' data-target='#copyProjectModal'><?php echo html::icon($lang->icons['copy'], 'muted') . ' ' . $lang->project->copy;?></button>
      </div>
    </div>
    <form class='form-indicator main-form form-ajax' method='post' target='hiddenwin' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th class='w-120px'><?php echo $lang->project->name;?></th>
          <td class="col-main"><?php echo html::input('name', $name, "class='form-control' required");?></td>
          <td></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->code;?></th>
          <td><?php echo html::input('code', $code, "class='form-control' required");?></td><td></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->dateRange;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('begin', (isset($plan) && !empty($plan->begin) ? $plan->begin : date('Y-m-d')), "class='form-control form-date' onchange='computeWorkDays()' placeholder='" . $lang->project->begin . "' required");?>
              <span class='input-group-addon'><?php echo $lang->project->to;?></span>
              <?php echo html::input('end', (isset($plan) && !empty($plan->end) ? $plan->end : ''), "class='form-control form-date' onchange='computeWorkDays()' placeholder='" . $lang->project->end . "' required");?>
            </div>
          </td>
          <td colspan='2'><?php echo html::radio('delta', $lang->project->endList , '', "onclick='computeEndDate(this.value)'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->days;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('days', (isset($plan) && !empty($plan->begin) ? helper::workDays($plan->begin, $plan->end) : ''), "class='form-control'");?>
              <span class='input-group-addon'><?php echo $lang->project->day;?></span>
            </div>
          </td><td></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->teamname;?></th>
          <td><?php echo html::input('team', $team, "class='form-control'");?></td><td></td><td></td>
        </tr>
        <?php if(!$isSprint):?>
        <tr>
          <th><?php echo $lang->project->type;?></th>
          <td><?php echo html::select('type', $lang->project->typeList, '', "class='form-control' onchange='showTypeTips()'");?></td>
          <td class='muted' colspan='2'><div class='type-tips'><?php echo $lang->project->typeDesc;?></div></td>
        </tr>
        <?php endif;?>
        <tr class='hide'>
          <th><?php echo $lang->project->status;?></th>
          <td><?php echo html::hidden('status', 'wait');?></td>
          <td></td>
          <td></td>
        </tr>
        <?php $this->printExtendFields('', 'table');?>
        <tr <?php if($config->global->flow == 'onlyTask') echo "class='hidden'";?>>
          <th><?php echo $lang->project->manageProducts;?></th>
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
                <div class='input-group'>
                  <?php echo html::select("products[$i]", $allProducts, '', "class='form-control chosen' onchange='loadBranches(this)'");?>
                  <span class='input-group-addon fix-border'></span>
                </div>
              </div>
            </div>
          </td>
        </tr>
        <tr <?php if($config->global->flow == 'onlyTask') echo "class='hidden'";?>>
          <th><?php echo $lang->project->linkPlan;?></th>
          <td colspan="3" id="plansBox">
            <div class='row'>
              <?php if(isset($plan) && !empty($plan->begin)):?>
              <div class="col-sm-4" id="plan0"><?php echo html::select("plans[" . $plan->product . "]", $productPlan, $plan->id, "class='form-control chosen'");?></div>
              <?php js::set('currentPlanID', $plan->id)?>
              <?php elseif($copyProjectID):?>
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
          <th><?php echo $lang->project->desc;?></th>
          <td colspan='3'>
            <?php echo $this->fetch('user', 'ajaxPrintTemplates', 'type=project&link=desc');?>
            <?php echo html::textarea('desc', '', "rows='6' class='form-control kindeditor' hidefocus='true'");?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->project->acl;?></th>
          <td colspan='3'><?php echo nl2br(html::radio('acl', $lang->project->aclList, $acl, "onclick='setWhite(this.value);'", 'block'));?></td>
        </tr>
        <tr id='whitelistBox' <?php if($acl != 'custom') echo "class='hidden'";?>>
          <th><?php echo $lang->project->whitelist;?></th>
          <td colspan='3'><?php echo html::checkbox('whitelist', $groups, $whitelist, '', '', 'inline');?></td>
        </tr>
        <tr>
          <td colspan='4' class='text-center form-actions'>
            <?php echo html::submitButton();?>
            <?php echo html::backButton();?>
            <?php if($isSprint) echo html::hidden('type', 'sprint');?>
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
      <h4 class='modal-title' id='myModalLabel'><?php echo $lang->project->copyTitle;?></h4>
    </div>
    <div class='modal-body'>
      <?php if(count($projects) == 1):?>
      <div class='alert with-icon'>
        <i class='icon-exclamation-sign'></i>
        <div class='content'><?php echo $lang->project->copyNoProject;?></div>
      </div>
      <?php else:?>
      <div id='copyProjects' class='row'>
      <?php foreach ($projects as $id => $name):?>
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
<?php include '../../common/view/footer.html.php';?>
