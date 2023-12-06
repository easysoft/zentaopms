<?php
/**
 * The pgmcreate view of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: pgmcreate.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
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
<?php helper::end();?>
<?php endif;?>

<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::import($jsRoot . 'misc/date.js');?>
<?php js::set('weekend', $config->execution->weekend);?>
<?php js::set('holders', $lang->execution->placeholder);?>
<?php js::set('errorSameProducts', $lang->project->errorSameProducts);?>
<?php js::set('longTime', $lang->program->longTime);?>
<?php js::set('LONG_TIME', LONG_TIME);?>
<?php js::set('currencySymbol', $lang->project->currencySymbol);?>
<?php js::set('PGMParentBudget', $lang->program->parentBudget);?>
<?php js::set('future', $lang->project->future);?>
<?php js::set('programList', $programList);?>
<?php js::set('budgetOverrun', $lang->project->budgetOverrun);?>
<?php js::set('currencySymbol', $lang->project->currencySymbol)?>
<?php js::set('parentBudget', $lang->program->parentBudget);?>
<?php js::set('beginLessThanParent', $lang->program->beginLessThanParent);?>
<?php js::set('endGreatThanParent', $lang->program->endGreatThanParent);?>
<?php js::set('ignore', $lang->program->ignore);?>
<?php js::set('page', $this->app->getMethodName());?>
<?php $aclList = $parentProgram ? $lang->program->subAclList : $lang->program->aclList;?>
<?php $requiredFields = $config->program->create->requiredFields;?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo isset($parentProgram->id) ? $lang->program->children : $lang->program->create;?></h2>
    </div>
    <form class='form-indicator main-form form-ajax' method='post' target='hiddenwin' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th><?php echo $lang->program->parent;?></th>
          <td>
            <?php $disabled = isset($parentProgram->id) ? 'disabled' : '';?>
            <?php echo html::select('parent', $parents, isset($parentProgram->id) ? $parentProgram->id : 0, "class='form-control chosen' onchange=setBudgetTipsAndAclList(this.value) $disabled");?>
            <?php if($disabled) echo html::hidden('parent', $parentProgram->id);?>
          </td>
          <td></td>
        </tr>
        <tr>
          <th class='w-120px'><?php echo $lang->program->name;?></th>
          <td class="col-main"><?php echo html::input('name', '', "class='form-control' required");?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->PM;?></th>
          <td><?php echo html::select('PM', $pmUsers, '', "class='form-control chosen'" . (strpos($requiredFields, 'PM') !== false ? ' required' : ''));?></td>
        </tr>
        <tr>
          <th><?php echo $lang->program->budget;?></th>
          <td>
            <div id='budgetBox' class='input-group'>
              <?php $placeholder = $parentProgram ? 'placeholder="' . $lang->program->parentBudget . zget($lang->project->currencySymbol, $parentProgram->budgetUnit) . $budgetLeft . '"' : '';?>
              <?php echo html::input('budget', '', "class='form-control' onchange='budgetOverrunTips()' maxlength='10' " . (strpos($requiredFields, 'budget') !== false ? 'required ' : '') . $placeholder);?>
              <?php if($parentProgram):?>
              <span class='input-group-addon fix-border'><?php echo zget($budgetUnitList, $parentProgram->budgetUnit);?></span>
              <?php else:?>
              <span class='input-group-addon fix-border'></span>
              <?php echo html::select('budgetUnit', $budgetUnitList, $config->project->defaultCurrency, "class='form-control'");?>
              <?php endif;?>
            </div>
          </td>
          <td class='futureBox'>
            <div class='checkbox-primary future w-70px'>
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
              <?php echo html::input('end', '', "class='form-control form-date' onchange='outOfDateTip();' placeholder='" . $lang->project->end . "' required");?>
            </div>
          </td>
          <td id="endList" colspan='2'><?php echo html::radio('delta', $lang->program->endList, '', "onclick='computeEndDate(this.value)'");?></td>
        </tr>
        <tr class='hide'>
          <th><?php echo $lang->project->status;?></th>
          <td><?php echo html::hidden('status', 'wait');?></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
        <?php $this->printExtendFields('', 'table');?>
        <tr>
          <th><?php echo $lang->program->desc;?></th>
          <td colspan='3'>
            <?php echo html::textarea('desc', '', "rows='6' class='form-control kindeditor' hidefocus='true'" . (strpos($requiredFields, 'desc') !== false ? ' required' : ''));?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->project->acl;?></th>
          <td colspan='3' class='aclBox'><?php echo nl2br(html::radio('acl', $aclList, 'private', "onclick='setWhite(this.value);'", 'block'));?></td>
        </tr>
        <tr id="whitelistBox">
          <th><?php echo $lang->whitelist;?></th>
          <td colspan='2'>
            <div class='input-group'>
              <?php echo html::select('whitelist[]', $users, '', 'class="form-control picker-select" multiple');?>
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
<div id='PGMAcl' class='hidden'>
  <?php echo nl2br(html::radio('acl', $lang->program->aclList, 'private', "onclick='setWhite(this.value);'", 'block'));?>
</div>
<div id='subPGMAcl' class='hidden'>
  <?php echo nl2br(html::radio('acl', $lang->program->subAclList, 'private', "onclick='setWhite(this.value);'", 'block'));?>
</div>
<?php js::set('parentProgramID', isset($parentProgram->id) ? $parentProgram->id : 0);?>
<?php include '../../common/view/footer.html.php';?>
