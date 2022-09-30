<?php
/**
 * The edit view of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: edit.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::import($jsRoot . 'misc/date.js');?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='prefix label-id'><strong><?php echo $execution->id;?></strong></span>
        <?php echo html::a($this->createLink('execution', 'view', 'execution=' . $execution->id), $execution->name, '_blank');?>
        <small><?php echo $lang->arrow . ' ' . $lang->execution->edit;?></small>
      </h2>
    </div>
    <?php if($config->systemMode == 'new') echo html::hidden('project', $project->id);?>
    <form class='load-indicator main-form form-ajax' method='post' target='hiddenwin' id='dataform'>
      <table class='table table-form'>
        <?php if($config->systemMode == 'new' and isset($project)):?>
        <?php if($project->model == 'scrum'):?>
        <tr>
          <th class='w-120px'><?php echo $lang->execution->projectName;?></th>
          <td><?php echo html::select('project', $allProjects, $execution->project, "class='form-control chosen' onchange='changeProject(this.value)' required");?></td><td></td>
        </tr>
        <?php elseif($project->model == 'kanban'):?>
        <?php echo html::hidden('project', $project->id);?>
        <?php endif;?>
        <?php endif;?>
        <tr>
          <th class='w-120px'><?php echo $lang->execution->name;?></th>
          <td><?php echo html::input('name', $execution->name, "class='form-control' required");?></td><td></td>
        </tr>
        <?php if(!isset($config->setCode) or $config->setCode == 1):?>
        <tr>
          <th><?php echo $lang->execution->code;?></th>
          <td><?php echo html::input('code', $execution->code, "class='form-control' required");?></td>
        </tr>
        <?php endif;?>
        <tr>
          <th id="dateRange"><?php echo $lang->execution->dateRange;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('begin', $execution->begin, "class='form-control form-date' onchange='computeWorkDays()' required placeholder='" . $lang->execution->begin . "'");?>
              <span class='input-group-addon fix-border'><?php echo $lang->execution->to;?></span>
              <?php echo html::input('end', $execution->end, "class='form-control form-date' onchange='computeWorkDays()' required placeholder='" . $lang->execution->end . "'");?>
              <div class='input-group-btn'>
                <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><?php echo $lang->execution->byPeriod;?> <span class='caret'></span></button>
                <ul class='dropdown-menu'>
                  <?php foreach ($lang->execution->endList as $key => $name):?>
                  <li><a href='javascript:computeEndDate("<?php echo $key;?>")'><?php echo $name;?></a></li>
                  <?php endforeach;?>
                </ul>
              </div>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->execution->days;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('days', $execution->days, "class='form-control'");?>
              <span class='input-group-addon'><?php echo $lang->execution->day;?></span>
            </div>
          </td>
        </tr>
        <?php if($execution->type != 'kanban'):?>
        <tr>
          <th><?php echo $lang->execution->type;?></th>
          <td>
          <?php
          if($execution->type != 'stage')
          {
              echo html::select('lifetime', $lang->execution->lifeTimeList, $execution->lifetime, "class='form-control' onchange='showLifeTimeTips()'");
          }
          else
          {
              echo html::select('attribute', $lang->stage->typeList, $execution->attribute, "class='chosen form-control'");
          }
          ?>
          </td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->execution->teamname;?></th>
          <td><?php echo html::input('team', $execution->team, "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->execution->status;?></th>
          <td><?php echo html::select('status', $lang->execution->statusList, $execution->status, "class='form-control chosen'");?></td>
        </tr>
        <tr>
          <th rowspan='2'><?php echo $lang->execution->owner;?></th>
          <td>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->execution->PO;?></span>
              <?php echo html::select('PO', $poUsers, $execution->PO, "class='form-control chosen'");?>
            </div>
          </td>
          <td>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->execution->QD;?></span>
              <?php echo html::select('QD', $qdUsers, $execution->QD, "class='form-control chosen'");?>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->execution->PM;?></span>
              <?php echo html::select('PM', $pmUsers, $execution->PM, "class='form-control chosen'");?>
            </div>
          </td>
          <td>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->execution->RD;?></span>
              <?php echo html::select('RD', $rdUsers, $execution->RD, "class='form-control chosen'");?>
            </div>
          </td>
        </tr>
        <?php if($execution->type == 'stage'):?>
        <tr>
          <th><?php echo $lang->stage->percent;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('percent', $execution->percent, "class='form-control'");?>
              <span class='input-group-addon'>%</span>
            </div>
          </td>
        </tr>
        <?php endif;?>
        <?php if(!in_array($execution->attribute, array('request', 'design', 'review'))): ?>
        <tr>
          <th><?php echo $lang->execution->manageProducts;?></th>
          <td class='text-left' id='productsBox' colspan="2">
          <?php $class = $execution->grade == 2 ? "readonly='readonly'" : '';?>
            <div class='row'>
              <?php $i = 0;?>
              <?php foreach($linkedProducts as $product):?>
              <?php $hasBranch = $product->type != 'normal' and isset($branchGroups[$product->id]);?>
              <?php foreach($linkedBranches[$product->id] as $branchID => $branch):?>
              <div class='col-sm-4'>
                <div class="input-group<?php if($hasBranch) echo ' has-branch';?>">
                  <?php echo html::select("products[$i]", $allProducts, $product->id, "class='form-control chosen' $class onchange='loadBranches(this)' data-last='" . $product->id . "' data-type='". $product->type ."'");?>
                  <span class='input-group-addon fix-border'></span>
                  <?php if($hasBranch) echo html::select("branch[$i]", $branchGroups[$product->id], $branchID, "class='form-control chosen' $class onchange=\"loadPlans('#products{$i}', this.value)\" data-last='" . $branchID . "'");?>
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
          <td id="plansBox" colspan="2">
            <div class='row'>
              <?php $i = 0;?>
              <?php if(empty($linkedProducts)):?>
              <div class="col-sm-4" id="plan0"><?php echo html::select("plans[][][]", $productPlans, '', "class='form-control chosen' multiple");?></div>
              <?php else:?>
              <?php foreach($linkedProducts as $product):?>
              <?php foreach($linkedBranches[$product->id] as $branchID => $branch):?>
              <?php $plans = isset($productPlans[$product->id][$branchID]) ? $productPlans[$product->id][$branchID] : array();?>
              <div class="col-sm-4" id="plan<?php echo $i;?>"><?php echo html::select("plans[{$product->id}][{$branchID}][]", $plans, $branches[$product->id][$branchID]->plan, "class='form-control chosen' multiple");?></div>
              <?php $i++;?>
              <?php endforeach;?>
              <?php endforeach;?>
              <?php endif;?>
            </div>
          </td>
        </tr>
        <?php else: ?>
        <?php echo html::hidden("products[]", key($linkedProducts));?>
        <?php endif; ?>
        <tr>
          <th><?php echo $lang->execution->team;?></th>
          <td colspan='2'><?php echo html::select('teamMembers[]', $users, array_keys($teamMembers), "class='form-control picker-select' multiple"); ?></td>
        </tr>
        <tr>
          <th><?php echo $lang->execution->desc;?></th>
          <td colspan='2'><?php echo html::textarea('desc', htmlSpecialString($execution->desc), "rows='6' class='form-control kindeditor' hidefocus='true'");?></td>
        </tr>
        <?php $this->printExtendFields($execution, 'table');?>
        <tr>
          <th><?php echo $lang->execution->acl;?></th>
          <?php $class = $execution->grade == 2 ? "disabled='disabled'" : '';?>
          <td colspan='2'><?php echo nl2br(html::radio('acl', $lang->execution->aclList, $execution->acl, "onclick='setWhite(this.value);' $class", 'block'));?></td>
        </tr>
        <tr class="<?php if($execution->acl == 'open') echo 'hidden';?>" id="whitelistBox">
          <th><?php echo $lang->whitelist;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::select('whitelist[]', $users, $execution->whitelist, 'class="form-control picker-select" multiple');?>
              <?php echo $this->fetch('my', 'buildContactLists', "dropdownName=whitelist");?>
            </div>
          </td>
        </tr>
        <tr><td colspan='3' class='text-center form-actions'><?php echo html::submitButton() . ' ' . html::backButton();?></td></tr>
      </table>
    </form>
  </div>
</div>
<?php js::set('weekend', $config->execution->weekend);?>
<?php js::set('errorSameProducts', $lang->execution->errorSameProducts);?>
<?php js::set('errorSameBranches', $lang->execution->errorSameBranches);?>
<?php js::set('unmodifiableProducts',$unmodifiableProducts);?>
<?php js::set('unmodifiableBranches', $unmodifiableBranches)?>
<?php js::set('linkedStoryIDList', $linkedStoryIDList)?>
<?php js::set('multiBranchProducts', $multiBranchProducts);?>
<?php js::set('tip', $lang->execution->notAllowRemoveProducts);?>
<?php js::set('confirmSync', $lang->execution->confirmSync);?>
<?php js::set('systemMode', $config->systemMode);?>
<?php include '../../common/view/footer.html.php';?>
