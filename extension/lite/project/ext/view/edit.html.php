<?php
/**
 * The prjedit view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id
 * @link        http://www.zentao.net
 */
?>
<?php include $this->app->getmoduleroot() . 'common/view/header.html.php';?>
<?php include $this->app->getmoduleroot() . 'common/view/kindeditor.html.php';?>
<?php js::import($jsRoot . 'misc/date.js');?>
<?php js::set('weekend', $config->execution->weekend);?>
<?php js::set('LONG_TIME', LONG_TIME);?>
<?php js::set('errorSameProducts', $lang->project->errorSameProducts);?>
<?php js::set('errorSameBranches', $lang->project->errorSameBranches);?>
<?php js::set('errorSamePlans', $lang->project->errorSamePlans);?>
<?php js::set('oldParent', $project->parent);?>
<?php js::set('projectID', $project->id);?>
<?php js::set('longTime', $lang->project->longTime);?>
<?php js::set('unmodifiableProducts', $unmodifiableProducts)?>
<?php js::set('unmodifiableBranches', $unmodifiableBranches)?>
<?php js::set('unmodifiableMainBranches', $unmodifiableMainBranches)?>
<?php js::set('tip', $lang->project->notAllowRemoveProducts);?>
<?php js::set('linkedProjectsTip', $lang->project->linkedProjectsTip);?>
<?php js::set('multiBranchProducts', $multiBranchProducts);?>
<?php $aclList = $project->parent ? $lang->project->subAclList : $lang->project->aclList;?>
<?php $requiredFields = $config->project->edit->requiredFields;?>
<?php js::set('requiredFields', $requiredFields);?>
<?php js::set('currencySymbol', $lang->project->currencySymbol)?>
<?php js::set('beginLetterParent', $lang->project->beginLetterParent);?>
<?php js::set('endGreaterParent', $lang->project->endGreaterParent);?>
<?php js::set('ignore', $lang->project->ignore);?>
<?php js::set('allProducts', $allProducts);?>
<?php js::set('branchGroups', $branchGroups);?>
<?php js::set('unLinkProductTip', $lang->project->unLinkProductTip);?>
<?php js::set('model', $project->model);?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->project->edit;?></h2>
    </div>
    <form class='form-indicator main-form form-ajax' method='post' target='hiddenwin' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th class='w-120px'><?php echo $lang->project->name;?></th>
          <td class="col-main"><?php echo html::input('name', $project->name, "class='form-control' required");?></td>
          <td colspan='2'></td>
        </tr>
        <?php if(isset($config->setCode) and $config->setCode == 1):?>
        <tr>
          <th><?php echo $lang->project->code;?></th>
          <td><?php echo html::input('code', $project->code, "class='form-control' required");?></td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->project->PM;?></th>
          <td><?php echo html::select('PM', $PMUsers, $project->PM, "class='form-control chosen'" . (strpos($requiredFields, 'PM') !== false ? ' required' : ''));?></td>
        </tr>
        <tr>
          <th id="dateRange"><?php echo $lang->project->dateRange;?></th>
          <td>
            <div id='dateBox' class='input-group'>
              <?php echo html::input('begin', $project->begin, "class='form-control form-date' onchange='computeWorkDays();' placeholder='" . $lang->project->begin . "' required");?>
              <span class='input-group-addon'><?php echo $lang->project->to;?></span>
              <?php
                $end = $project->end == LONG_TIME ? $lang->project->longTime : $project->end;
                echo html::input('end', $end, "class='form-control form-date' onchange='computeWorkDays();' placeholder='" . $lang->project->end . "' required");
              ?>
            </div>
          </td>
          <?php
          /* Remove LONG_TIME item when no multiple project. */
          if(empty($project->multiple)) unset($lang->project->endList[999]);
          $deltaValue = $project->end == LONG_TIME ? 999 : (strtotime($project->end) - strtotime($project->begin)) / 3600 / 24 + 1;
          ?>
          <td id="endList" colspan='2'><?php echo html::radio('delta', $lang->project->endList, $deltaValue, "onclick='computeEndDate(this.value)'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->execution->team;?></th>
          <td colspan='2'><?php echo html::select('teamMembers[]', $users, array_keys($teamMembers), "class='form-control picker-select' multiple"); ?></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->desc;?></th>
          <td colspan='3'>
            <?php echo html::textarea('desc', $project->desc, "rows='6' class='form-control kindeditor' hidefocus='true'" . (strpos($requiredFields, 'desc') !== false ? ' required' : ''));?>
          </td>
        </tr>
        <?php $this->printExtendFields($project, 'table', 'columns=3');?>
        <tr>
          <th><?php echo $lang->project->acl;?></th>
          <td colspan='3' class='aclBox'><?php echo nl2br(html::radio('acl', $aclList, $project->acl, "onclick='setWhite(this.value);'", 'block'));?></td>
        </tr>
        <tr class="<?php if($project->acl == 'open') echo 'hidden';?>" id="whitelistBox">
          <th><?php echo $lang->whitelist;?></th>
          <td colspan='2'>
            <div class='input-group'>
              <?php echo html::select('whitelist[]', $users, $project->whitelist, 'class="form-control picker-select" multiple');?>
              <?php echo $this->fetch('my', 'buildContactLists', "dropdownName=whitelist");?>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan='4' class='text-center form-actions'>
            <?php
              if($disableModel == 'disabled') echo html::hidden('model', $project->model);
              echo html::submitButton();
              if(!isonlybody()) echo html::backButton();
            ?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<div id='projectAcl' class='hidden'>
  <?php echo nl2br(html::radio('acl', $lang->project->aclList, $project->acl == 'project' ? 'private' : 'open', "onclick='setWhite(this.value);'", 'block'));?>
</div>
<div id='programAcl' class='hidden'>
  <?php echo nl2br(html::radio('acl', $lang->project->subAclList, $project->acl, "onclick='setWhite(this.value);'", 'block'));?>
</div>
<?php include $this->app->getModuleRoot() . 'common/view/footer.html.php';?>
