<?php
/**
 * The edit view of program module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     program
 * @version     $Id: create.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::set('LONG_TIME', LONG_TIME);?>
<?php js::set('page', $this->app->getMethodName());?>
<?php js::set('weekend', $config->execution->weekend);?>
<?php js::set('longTime', $lang->program->longTime);?>
<?php js::set('currencySymbol', $lang->project->currencySymbol);?>
<?php js::set('PGMParentBudget', $lang->program->parentBudget);?>
<?php js::set('parentBudget', $lang->program->parentBudget);?>
<?php js::set('future', $lang->project->future);?>
<?php js::set('programList', $programList);?>
<?php js::set('budgetUnitList', $budgetUnitList);?>
<?php js::set('oldBudgetUnit', $program->budgetUnit);?>
<?php js::set('exRateNotEmpty', sprintf($lang->error->notempty, $lang->program->exchangeRate));?>
<?php js::set('exRateNum', sprintf($lang->error->float, $lang->program->exchangeRate));?>
<?php js::set('exRateNotNegative', $lang->program->exRateNotNegative);?>
<?php js::set('programID', $program->id);?>
<?php js::set('budgetOverrun', $lang->project->budgetOverrun);?>
<?php js::set('currencySymbol', $lang->project->currencySymbol)?>
<?php js::set('parentBudget', $lang->program->parentBudget);?>
<?php js::set('beginLessThanParent', $lang->program->beginLessThanParent);?>
<?php js::set('endGreatThanParent', $lang->program->endGreatThanParent);?>
<?php js::set('beginGreatEqualChild', $lang->program->beginGreatEqualChild);?>
<?php js::set('endLessThanChild', $lang->program->endLessThanChild);?>
<?php js::set('ignore', $lang->program->ignore);?>
<?php $aclList = $program->parent ? $lang->program->subAclList : $lang->program->aclList;?>
<?php $requiredFields = $config->program->edit->requiredFields;?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->program->edit;?></h2>
    </div>
    <form class='form-indicator main-form form-ajax' method='post' target='hiddenwin' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th class='w-120px'><?php echo $lang->program->parent;?></th>
          <td class="col-main"><?php echo html::select('parent', $parents, $program->parent, "class='form-control chosen' onchange=setBudgetTipsAndAclList(this.value)");?></td>
          <td></td><td></td>
        </tr>
        <tr>
          <th class='w-120px'><?php echo $lang->program->name;?></th>
          <td class="col-main"><?php echo html::input('name', $program->name, "class='form-control' required");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->PM;?></th>
          <td><?php echo html::select('PM', $pmUsers, $program->PM, "class='form-control chosen'" . (strpos($requiredFields, 'PM') !== false ? ' required' : ''));?></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->budget;?></th>
          <td>
            <div id='budgetBox' class='input-group'>
              <?php $placeholder = ($parentProgram and $parentProgram->budget != 0) ? 'placeholder="' . $lang->program->parentBudget . zget($lang->project->currencySymbol, $parentProgram->budgetUnit) . $availableBudget . '"' : '';?>
              <?php echo html::input('budget', $program->budget != 0 ? $program->budget : '', "class='form-control' onchange='budgetOverrunTips()' maxlength='10' " . (strpos($requiredFields, 'budget') !== false ? 'required ' : '') . ($program->budget == 0 ? 'disabled ' : '') . $placeholder);?>
              <?php if($parentProgram):?>
              <span class='input-group-addon'><?php echo zget($budgetUnitList, $program->budgetUnit);?></span>
              <?php else:?>
              <span class='input-group-addon'></span>
              <?php echo html::select('budgetUnit', $budgetUnitList, $program->budgetUnit, "class='form-control'");?>
              <?php echo html::hidden('syncPRJUnit', 'false');?>
              <?php echo html::hidden('exchangeRate', '');?>
              <?php endif;?>
            </div>
          </td>
          <td class='futureBox'>
            <div class='checkbox-primary future w-70px'>
              <input type='checkbox' id='future' name='future' value='1' <?php if($program->budget == 0) echo 'checked';?> />
              <label for='future'><?php echo $lang->project->future;?></label>
            </div>
          </td>
        </tr>
        <tr>
          <th id="dateRange"><?php echo $lang->project->dateRange;?></th>
          <td>
            <div id='dateBox' class='input-group'>
              <?php echo html::input('begin', $program->begin, "class='form-control form-date' onchange='outOfDateTip();' placeholder='" . $lang->project->begin . "' required");?>
              <span class='input-group-addon'><?php echo $lang->project->to;?></span>
              <?php
                $end = $program->end == LONG_TIME ? $lang->program->longTime : $program->end;
                echo html::input('end', $end, "class='form-control form-date' onchange='outOfDateTip();' placeholder='" . $lang->project->end . "' required");
              ?>
            </div>
          </td>
          <?php $endValue = $program->end == LONG_TIME ? 999 : (strtotime($program->end) - strtotime($program->begin)) / 3600 / 24 + 1;?>
          <td id="endList" colspan='2'><?php echo html::radio('delta', $lang->program->endList , $endValue, "onclick='computeEndDate(this.value)'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->realBegan;?></th>
          <td><?php echo html::input('realBegan', helper::isZeroDate($program->realBegan) ? '' : $program->realBegan, "class='form-control form-date'");?></td>
        </tr>
        <?php $this->printExtendFields($program, 'table');?>
        <tr>
          <th><?php echo $lang->program->desc;?></th>
          <td colspan='3'>
            <?php echo html::textarea('desc', $program->desc, "rows='6' class='form-control kindeditor' hidefocus='true'" . (strpos($requiredFields, 'desc') !== false ? ' required' : ''));?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->project->acl;?></th>
          <td colspan='3' class='aclBox'><?php echo nl2br(html::radio('acl', $aclList, $program->acl, "onclick='setWhite(this.value);'", 'block'));?></td>
        </tr>
        <tr class="<?php if($program->acl == 'open') echo 'hidden';?>" id="whitelistBox">
          <th><?php echo $lang->whitelist;?></th>
          <td colspan='2'>
            <div class='input-group'>
              <?php echo html::select('whitelist[]', $users, $program->whitelist, 'class="form-control picker-select" multiple data-dropDirection="top"');?>
              <?php echo $this->fetch('my', 'buildContactLists', "dropdownName=whitelist");?>
            </div>
          </td>
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
<div id='acl' class='hidden'>
  <?php echo nl2br(html::radio('acl', $lang->program->aclList, $program->acl == 'program' ? 'private' : 'open', "onclick='setWhite(this.value);'", 'block'));?>
</div>
<div id='subAcl' class='hidden'>
  <?php echo nl2br(html::radio('acl', $lang->program->subAclList, $program->acl, "onclick='setWhite(this.value);'", 'block'));?>
</div>

<div class="modal fade" id="changeUnitTip">
  <div class="modal-dialog mw-600px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon icon-close"></i></button>
        <h4 class="modal-title"><?php echo $lang->program->changePRJUnit;?></h4>
      </div>
      <div class="modal-body">
        <form method='post' class='not-watch'>
          <table class='table table-form'>
            <tr>
              <td colspan='3'><div class='alert alert-info no-margin'><?php echo $lang->program->confirmChangePRJUint;?></div></td>
            </tr>
            <tr>
              <th><?php echo '1' . zget($budgetUnitList, $program->budgetUnit);?></th>
              <td><div class='input-group'><span class='input-group-addon'><?php echo "=";?></span><?php echo html::input('rate', '', "class='form-control' required");?> <span class='input-group-addon' id='currentUnit'></span></div></td>
              <td></td>
            </tr>
            <tr>
              <td colspan='3' class='text-center'>
                <?php echo html::commonButton($lang->confirm, "id='confirmBTN'", 'btn btn-primary btn-wide');?>
                <?php echo html::commonButton($lang->cancel, "data-dismiss='modal' id='cancelBTN'", 'btn btn-default btn-wide');?>
              </td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
