<?php
/**
 * The create view of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: create.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php $showExecutionExec = !empty($from) and ($from == 'execution' || $from == 'doc');?>
<?php if(isset($tips)):?>
<?php $defaultURL = $this->createLink('execution', 'task', "execution=$executionID");?>
<?php include '../../common/view/header.html.php';?>
<body>
  <div class='modal-dialog' id='newTipsModal'>
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
<?php helper::end();?>
<?php endif;?>

<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::import($jsRoot . 'misc/date.js');?>
<?php js::set('weekend', $config->execution->weekend);?>
<?php js::set('holders', $lang->execution->placeholder);?>
<?php js::set('errorSameProducts', $lang->execution->errorSameProducts);?>
<?php js::set('errorSameBranches', $lang->execution->errorSameBranches);?>
<?php js::set('productID', empty($productID) ? 0 : $productID);?>
<?php js::set('isStage', $isStage);?>
<?php js::set('copyExecutionID', $copyExecutionID);?>
<?php js::set('projectCommon', $lang->project->common);?>
<?php js::set('multiBranchProducts', $multiBranchProducts);?>
<?php js::set('projectID', $projectID);?>
<?php js::set('copyExecutionID', $copyExecutionID);?>
<?php js::set('cancelCopy', $lang->execution->cancelCopy);?>
<?php js::set('copyNoExecution', $lang->execution->copyNoExecution);?>
<?php js::set('model', isset($project->model) ? $project->model : '');?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $showExecutionExec ? $lang->execution->createExec : $lang->execution->create;?></h2>
      <div class="pull-right btn-toolbar">
        <button type='button' class='btn btn-link' data-toggle='modal' data-target='#copyProjectModal'><?php echo html::icon($lang->icons['copy'], 'muted') . ' ' . ($showExecutionExec ? $lang->execution->copyExec : $lang->execution->copy);?></button>
      </div>
    </div>
    <form class='form-indicator main-form form-ajax' method='post' target='hiddenwin' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th class='w-120px'><?php echo $lang->execution->projectName;?></th>
          <td class="col-main"><?php echo html::select("project", $allProjects, $projectID, "class='form-control chosen' required onchange='refreshPage(this.value)'");?></td>
          <td colspan='2'></td>
        </tr>
        <tr>
          <th class='w-120px'><?php echo $showExecutionExec ? $lang->execution->execName : $lang->execution->name;?></th>
          <td class="col-main"><?php echo html::input('name', $name, "class='form-control' required");?></td>
          <td colspan='2'></td>
        </tr>
        <?php if(!isset($config->setCode) or $config->setCode == 1):?>
        <tr>
          <th><?php echo $showExecutionExec ? $lang->execution->execCode : $lang->execution->code;?></th>
          <td><?php echo html::input('code', $code, "class='form-control' required");?></td><td></td><td></td>
        </tr>
        <?php endif;?>
        <tr>
          <th id='dateRange'><?php echo $lang->execution->dateRange;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('begin', (isset($plan) && !empty($plan->begin) ? $plan->begin : date('Y-m-d')), "class='form-control form-date' onchange='computeWorkDays()' placeholder='" . $lang->execution->begin . "' required");?>
              <span class='input-group-addon'><?php echo $lang->execution->to;?></span>
              <?php echo html::input('end', (isset($plan) && !empty($plan->end) ? $plan->end : ''), "class='form-control form-date' onchange='computeWorkDays()' placeholder='" . $lang->execution->end . "' required");?>
            </div>
          </td>
          <td id='dateRangeOption' colspan='2'><?php echo html::radio('delta', $lang->execution->endList , '', "onclick='computeEndDate(this.value)'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->execution->days;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('days', (isset($plan) && !empty($plan->begin) ? (helper::workDays($plan->begin, $plan->end) + 1) : ''), "class='form-control'");?>
              <span class='input-group-addon'><?php echo $lang->execution->day;?></span>
            </div>
          </td><td></td><td></td>
        </tr>
        <?php if(empty($project) or $project->model != 'kanban'):?>
        <tr>
          <th><?php echo $showExecutionExec ? $lang->execution->execType : $lang->execution->type;?></th>
          <td>
          <?php
          if($isStage)
          {
              echo html::select('attribute', $lang->stage->typeList, '', "class='form-control chosen'");
          }
          else
          {
              echo html::select('lifetime', $lang->execution->lifeTimeList, '', "class='form-control' onchange='showLifeTimeTips()'");
          }
          ?>
          </td>
          <td class='muted' colspan='2'><div id='lifeTimeTips'><?php echo $lang->execution->typeDesc;?></div></td>
        </tr>
        <?php endif;?>
        <?php if($isStage):?>
        <tr>
          <th><?php echo $lang->stage->percent;?></th>
          <td class='required'>
            <div class='input-group'>
              <?php echo html::input('percent', '', "class='form-control'");?>
              <span class='input-group-addon'>%</span>
            </div>
          </td>
        </tr>
        <?php endif;?>
        <tr class='hide'>
          <th><?php echo $lang->execution->status;?></th>
          <td><?php echo html::hidden('status', 'wait');?></td>
          <td></td>
          <td></td>
        </tr>
        <?php $this->printExtendFields('', 'table', 'columns=3');?>
        <?php $hidden = 'hide'?>
        <?php if(!empty($project->hasProduct)) $hidden = ''?>
        <?php if($products):?>
        <?php $i = 0;?>
        <?php foreach($products as $product):?>
        <tr class="<?php echo $hidden;?>">
          <th><?php echo $lang->project->manageProductPlan;?></th>
          <td class='text-left productsBox' colspan="3">
            <div class='row'>
              <div class="col-sm-6">
                <div class='table-row'>
                  <div class='table-col'>
                    <?php $hasBranch = $product->type != 'normal' and isset($branchGroups[$product->id]);?>
                    <div class='input-group <?php if($hasBranch) echo ' has-branch';?>'>
                      <span class='input-group-addon'><?php echo $lang->product->common;?></span> 
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
        <?php else:?>
        <tr class='<?php echo $hidden;?>'>
          <th id='productTitle'><?php echo $lang->project->manageProductPlan;?></th>
          <td class='text-left productsBox' colspan='3'>
            <div class='row'>
              <div class="col-sm-6">
                <div class='table-row'>
                  <div class='table-col'>
                    <div class='input-group'>
                      <span class='input-group-addon'><?php echo $lang->product->common;?></span>
                      <?php echo html::select("products[0]", $allProducts, '', "class='form-control chosen' onchange='loadBranches(this)'");?>
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
                  <?php echo html::select("plans[][]", $productPlan, '', "class='form-control chosen' multiple");?>
                  <div class='input-group-btn'>
                    <a href='javascript:;' onclick='addNewLine(this)' class='btn btn-link addLine'><i class='icon-plus'></i></a>
                    <a href='javascript:;' onclick='removeLine(this)' class='btn btn-link removeLine' style='visibility: hidden'><i class='icon-close'></i></a>
                  </div>
                </div>
              </div>
            </div>
          </td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->execution->teamname;?></th>
          <td><?php echo html::input('team', $team, "class='form-control'");?></td>
          <td colspan='2'></td>
        </tr>
        <tr>
          <th><?php echo $lang->execution->copyTeam;?></th>
          <td><?php echo html::select('teams', $teams, empty($copyExecution) ? $projectID : $copyExecutionID, "class='form-control chosen' data-placeholder='{$lang->execution->copyTeamTip}'"); ?></td>
        </tr>
        <tr>
          <th rowspan='2'><?php echo $lang->execution->owner;?></th>
          <td>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->execution->PO;?></span>
              <?php echo html::select('PO', $poUsers, empty($copyExecution) ? '' : $copyExecution->PO, "class='form-control chosen'");?>
            </div>
          </td>
          <td>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->execution->QD;?></span>
              <?php echo html::select('QD', $qdUsers, empty($copyExecution) ? '' : $copyExecution->QD, "class='form-control chosen'");?>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->execution->PM;?></span>
              <?php echo html::select('PM', $pmUsers, empty($copyExecution) ? '' : $copyExecution->PM, "class='form-control chosen'");?>
            </div>
          </td>
          <td>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->execution->RD;?></span>
              <?php echo html::select('RD', $rdUsers, empty($copyExecution) ? '' : $copyExecution->RD, "class='form-control chosen'");?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->execution->team;?></th>
          <td colspan='3'><?php echo html::select('teamMembers[]', $users, '', "class='form-control picker-select' multiple"); ?></td>
        </tr>
        <tr>
          <th><?php echo $showExecutionExec ? $lang->execution->execDesc : $lang->execution->desc;?></th>
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
          <td colspan='2'>
            <div class='input-group'>
              <?php echo html::select('whitelist[]', $users, $whitelist, 'class="form-control picker-select" multiple');?>
              <?php echo $this->fetch('my', 'buildContactLists', "dropdownName=whitelist");?>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan='4' class='text-center form-actions'>
            <?php echo html::submitButton();?>
            <?php echo $gobackLink ? html::a($gobackLink, $lang->goback, '', 'class="btn btn-wide"') : html::backButton();?>
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
      <div class='titleBox'><h4 class='modal-title' id='myModalLabel'><?php echo $lang->execution->copyTitle;?></h4></div>
      <div class='projectSelect'><?php echo html::select("project", $copyProjects, $projectID, "class='form-control chosen' required onchange='loadProjectExecutions(this.value)'");?></div>
    </div>
    <div class='modal-body'>
      <?php if(count($executions) == 1):?>
      <div class='alert with-icon'>
        <i class='icon-exclamation-sign'></i>
        <div class='content'><?php echo $lang->execution->copyNoExecution;?></div>
      </div>
      <?php else:?>
      <div id='copyProjects' class='row'>
      <?php if($projectID == 0) $executions = $copyExecutions;?>
      <?php foreach($executions as $id => $execution):?>
      <?php if(empty($id)):?>
      <?php if($copyExecutionID != 0):?>
      <div class='col-md-4 col-sm-6'><a href='javascript:;' data-id='' class='cancel'><?php echo html::icon($lang->icons['cancel']) . ' ' . $lang->execution->cancelCopy;?></a></div>
      <?php endif;?>
      <?php else:?>
      <?php if(empty($execution->multiple)) continue;?>
      <div class='col-md-4 col-sm-6'><a href='javascript:;' data-id='<?php echo $id;?>' class='nobr <?php echo ($copyExecutionID == $id) ? ' active' : '';?>'><?php echo html::icon($lang->icons[$execution->type], 'text-muted') . ' ' . $execution->name;?></a></div>
      <?php endif;?>
      <?php endforeach;?>
      </div>
      <?php endif;?>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
