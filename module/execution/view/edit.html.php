<?php
/**
 * The edit view of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
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
<?php js::set('isWaterfall', (isset($project) and ($project->model == 'waterfall' or $project->model == 'waterfallplus')));?>
<?php js::set('executionAttr', $execution->attribute);?>
<?php js::set('manageProductsLang', $lang->project->manageProducts);?>
<?php js::set('manageProductPlanLang', $lang->project->manageProductPlan);?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='prefix label-id'><strong><?php echo $execution->id;?></strong></span>
        <?php echo html::a($this->createLink('execution', 'view', 'execution=' . $execution->id), $execution->name, '_blank');?>
        <small><?php echo $lang->arrow . ' ' . $lang->execution->edit;?></small>
      </h2>
    </div>
    <?php echo html::hidden('project', $project->id);?>
    <form class='load-indicator main-form form-ajax' method='post' target='hiddenwin' id='dataform'>
      <table class='table table-form'>
        <?php if(isset($project)):?>
        <?php if($project->model == 'scrum'):?>
        <tr>
          <th class='c-projectName'><?php echo $lang->execution->projectName;?></th>
          <td><?php echo html::select('project', $allProjects, $execution->project, "class='form-control chosen' onchange='changeProject(this.value)' required");?></td><td></td>
        </tr>
        <?php elseif($project->model == 'kanban'):?>
        <?php echo html::hidden('project', $project->id);?>
        <?php elseif($project->model == 'agileplus'):?>
        <tr>
          <th class='c-method'><?php echo $lang->execution->method;?></th>
          <td><?php echo zget($lang->execution->typeList, $execution->type);?></td><td></td>
        </tr>
        <?php elseif($app->tab == 'project' and $project->model == 'waterfallplus'):?>
        <tr>
          <th class='c-name'><?php echo $lang->programplan->parent;?></th>
          <td><?php echo html::select('parent', $parentStageList, $execution->parent, "class='form-control chosen '");?></td><td></td>
        </tr>
        <?php endif;?>
        <?php endif;?>
        <tr>
          <th class='c-name'><?php echo $lang->execution->name;?></th>
          <td><?php echo html::input('name', $execution->name, "class='form-control' required");?></td><td></td>
        </tr>
        <?php if(isset($config->setCode) and $config->setCode == 1):?>
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
        <?php if(in_array($project->model, array('waterfall', 'waterfallplus'))):?>
        <tr>
          <th><?php echo $lang->stage->type;?></th>
          <td>
          <?php echo $enableOptionalAttr ? html::select('attribute', $project->model == 'ipd' ? $lang->stage->ipdTypeList : $lang->stage->typeList, $execution->attribute, "class='form-control chosen'") : zget($lang->stage->typeList, $execution->attribute); ?>
          </td>
          <td>
            <icon class='icon icon-help' data-toggle='popover' data-trigger='focus hover' data-placement='right' data-tip-class='text-muted popover-sm' data-content="<?php echo $lang->execution->typeTip;?>"></icon>
          </td>
        </tr>
        <?php elseif($execution->type != 'kanban' and $project->model != 'ipd'):?>
        <tr>
          <th><?php echo $lang->execution->type;?></th>
          <td>
          <?php echo html::select('lifetime', $lang->execution->lifeTimeList, $execution->lifetime, "class='form-control' onchange='showLifeTimeTips()'"); ?>
          </td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->execution->teamName;?></th>
          <td><?php echo html::input('team', $execution->team, "class='form-control'");?></td>
        </tr>
        <?php if($project->model != 'ipd'):?>
        <tr>
          <th><?php echo $lang->execution->status;?></th>
          <td><?php echo html::select('status', $lang->execution->statusList, $execution->status, "class='form-control chosen'");?></td>
        </tr>
        <?php endif;?>
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
        <?php if($execution->type == 'stage' and isset($config->setPercent) and $config->setPercent == 1):?>
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
        <?php if($project->model != 'waterfall' and $project->model != 'waterfallplus'): ?>
        <?php $hidden = 'hide'?>
        <?php if(!empty($project->hasProduct)) $hidden = ''?>
        <?php if(!empty($project) and !empty($project->hasProduct) and $linkedProducts):?>
        <?php $i = 0;?>
        <?php foreach($linkedProducts as $product):?>
        <tr class="<?php echo $hidden;?>">
          <th id='productTitle'><?php if($i == 0) echo $lang->project->manageProductPlan;?></th>
          <td class='text-left productsBox' colspan="3">
            <div class='row'>
              <div class="col-sm-6 productBox">
                <div class='table-row'>
                  <div class='table-col'>
                    <?php $hasBranch = $product->type != 'normal' and isset($branchGroups[$product->id]);?>
                    <div class='input-group <?php if($hasBranch) echo ' has-branch';?>'>
                      <span class='input-group-addon'><?php echo $lang->productCommon;?></span>
                      <?php $disabled = ($execution->type == 'stage' and $execution->stageBy == 'project') ? "disabled='disabled'" : '';?>
                      <?php echo html::select("products[$i]", $allProducts, $product->id, "class='form-control chosen' $disabled onchange='loadBranches(this)' data-last='" . $product->id . "' data-type='" . $product->type . "'");?>
                      <?php if($execution->type == 'stage' and $execution->stageBy == 'project') echo html::hidden("products[$i]", $product->id);?>
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
              <div class="col-sm-6 planBox">
                <div class='input-group' <?php echo "id='plan$i'";?>>
                  <span class='input-group-addon'><?php echo $lang->product->plan;?></span>
                  <?php echo html::select("plans[$product->id][]", isset($productPlans[$product->id]) ? $productPlans[$product->id] : array(), $product->plans, "class='form-control chosen' multiple");?>
                  <?php if(!($execution->type == 'stage' and $execution->stageBy == 'project')):?>
                  <div class='input-group-btn'>
                    <a href='javascript:;' onclick='addNewLine(this)' class='btn btn-link addLine'><i class='icon-plus'></i></a>
                    <a href='javascript:;' onclick='removeLine(this)' class='btn btn-link removeLine' <?php if($i == 0) echo "style='visibility: hidden'";?>><i class='icon-close'></i></a>
                  </div>
                  <?php endif;?>
                </div>
              </div>
            </div>
          </td>
        </tr>
        <?php $i ++;?>
        <?php endforeach;?>
        <?php elseif(!empty($project) and empty($project->hasProduct)):?>
        <tr>
          <th><?php echo $lang->execution->linkPlan;?></th>
          <td id="plansBox">
            <?php $planProductID = current(array_keys($linkedProducts));?>
            <?php echo html::select("plans[$planProductID][]", isset($productPlans[$planProductID]) ? $productPlans[$planProductID] : array(), isset($linkedProducts[$planProductID]) ? $linkedProducts[$planProductID]->plans : '', "class='form-control chosen' multiple");?>
            <?php echo html::hidden("products[]", $planProductID);?>
            <?php echo html::hidden("branch[0][0]", '0');?>
          </td>
        </tr>
        <?php else:?>
        <tr class='<?php echo $hidden;?>'>
          <th id='productTitle'><?php echo $lang->project->manageProductPlan;?></th>
          <td class='text-left productsBox' colspan='3'>
            <div class='row'>
              <div class="col-sm-6 productBox">
                <div class='table-row'>
                  <div class='table-col'>
                    <div class='input-group'>
                      <span class='input-group-addon'><?php echo $lang->productCommon;?></span>
                      <?php echo html::select("products[0]", $allProducts, '', "class='form-control chosen' onchange='loadBranches(this)'");?>
                    </div>
                  </div>
                  <div class='table-col hidden'>
                    <div class='input-group required'>
                      <span class='input-group-addon fix-border'><?php echo $lang->product->branchName['branch'];?></span>
                      <?php echo html::select("branch", '', '', "class='form-control chosen' multiple");?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 planBox">
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
          </td>
        </tr>
        <?php endif; ?>
        <?php elseif(!empty($project) and !empty($project->hasProduct)):?>
        <?php echo html::hidden("products[]", key($linkedProducts));?>
        <?php echo html::hidden("branch", json_encode(array_values($linkedBranches)));?>
        <?php $i = 0;?>
        <?php foreach($linkedProducts as $product):?>
        <tr>
          <th id="productTitle"><?php if($i == 0) echo $lang->project->manageProductPlan;?></th>
          <td class='text-left productsBox' colspan="3">
            <div class='row'>
              <div class="col-sm-6 productBox">
                <div class='table-row'>
                  <div class='table-col'>
                    <?php $hasBranch = $product->type != 'normal' and isset($branchGroups[$product->id]);?>
                    <div class='input-group <?php if($hasBranch) echo ' has-branch';?>'>
                      <span class='input-group-addon'><?php echo $lang->productCommon;?></span>
                      <?php $disabled = ($project->model == 'waterfall' or $project->model == 'waterfallplus') ? "disabled='disabled'" : '';?>
                      <?php echo html::select("products[$i]", $allProducts, $product->id, "class='form-control chosen' $disabled onchange='loadBranches(this)' data-last='" . $product->id . "' data-type='" . $product->type . "'");?>
                      <?php if($execution->type == 'stage' and $project->stageBy == 'product') echo html::hidden("products[$i]", $product->id);?>
                    </div>
                  </div>
                  <div class='table-col <?php if(!$hasBranch) echo 'hidden'; if($disabled) echo ' disabledBranch'?>'>
                    <div class='input-group required'>
                      <span class='input-group-addon fix-border'><?php echo $lang->project->branch;?></span>
                      <?php $branchIdList = isset($product->branches) ? join(',', $product->branches) : '';?>
                      <?php echo html::select("branch[$i][]", isset($branchGroups[$product->id]) ? $branchGroups[$product->id] : array(), $branchIdList, "class='form-control chosen' multiple onchange=\"loadPlans('#products{$i}', this)\"");?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 planBox">
                <div class='input-group' <?php echo "id='plan$i'";?>>
                  <span class='input-group-addon'><?php echo $lang->product->plan;?></span>
                  <?php echo html::select("plans[$product->id][]", isset($productPlans[$product->id]) ? $productPlans[$product->id] : array(), isset($product->plans) ? $product->plans : '', "class='form-control chosen' multiple");?>
                </div>
              </div>
            </div>
          </td>
        </tr>
        <?php $i ++;?>
        <?php endforeach;?>
        <?php else:?>
        <?php echo html::hidden("products[]", key($linkedProducts));?>
        <?php echo html::hidden("branch", json_encode(array_values($linkedBranches)));?>
        <?php endif;?>
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
<?php js::set('confirmSync', $lang->execution->confirmSync);?>
<?php js::set('allProducts', $allProducts);?>
<?php js::set('branchGroups', $branchGroups);?>
<?php js::set('projectID', $execution->project);?>
<?php js::set('unLinkProductTip', $lang->project->unLinkProductTip);?>
<?php include '../../common/view/footer.html.php';?>
