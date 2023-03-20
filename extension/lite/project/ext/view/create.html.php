<?php
/**
 * The create view file of project module for lite vision of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     project
 * @version     $Id: create.html.php 4769 2013-05-05 07:24:21Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include $this->app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $this->app->getModuleRoot() . 'common/view/kindeditor.html.php';?>
<?php js::import($jsRoot . 'misc/date.js');?>
<?php js::set('model', $model);?>
<?php js::set('programID', $programID);?>
<?php js::set('copyProjectID', $copyProjectID);?>
<?php js::set('weekend', $config->execution->weekend);?>
<?php js::set('errorSameProducts', $lang->project->errorSameProducts);?>
<?php js::set('errorSameBranches', $lang->project->errorSameBranches);?>
<?php js::set('longTime', $lang->project->longTime);?>
<?php js::set('multiBranchProducts', $multiBranchProducts);?>
<?php js::set('selectedProductID', $productID);?>
<?php js::set('selectedBranchID', $branchID);?>
<?php $requiredFields = $config->project->create->requiredFields;?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->project->create;?></h2>
      <?php if(!commonModel::isTutorialMode()): ?>
      <div class="pull-right btn-toolbar">
        <button type='button' class='btn btn-link' data-toggle='modal' data-target='#copyProjectModal'><?php echo html::icon($lang->icons['copy'], 'muted') . ' ' . $lang->project->copy;?></button>
      </div>
      <?php endif; ?>
    </div>
    <form class='form-indicator main-form form-ajax' method='post' target='hiddenwin' id='dataform'>
      <table class='table table-form'>
        <tr>
          <th><?php echo $lang->project->name;?></th>
          <td class="col-main"><?php echo html::input('name', $name, "class='form-control' required");?></td>
        </tr>
        <?php if(isset($config->setCode) and $config->setCode == 1):?>
        <tr>
          <th><?php echo $lang->project->code;?></th>
          <td><?php echo html::input('code', '', "class='form-control' required");?></td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo $lang->project->PM;?></th>
          <td><?php echo html::select('PM', $pmUsers, '', "class='form-control chosen'" . (strpos($requiredFields, 'PM') !== false ? ' required' : ''));?></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->dateRange;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('begin', date('Y-m-d'), "class='form-control form-date' onchange='computeWorkDays();' placeholder='" . $lang->project->begin . "' required");?>
              <span class='input-group-addon'><?php echo $lang->project->to;?></span>
              <?php echo html::input('end', '', "class='form-control form-date' onchange='computeWorkDays();' placeholder='" . $lang->project->end . "' required");?>
            </div>
          </td>
          <td colspan='2'><?php echo html::radio('delta', $lang->project->endList , '', "onclick='computeEndDate(this.value)'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->execution->team;?></th>
          <td colspan='2'><?php echo html::select('teamMembers[]', $users, '', "class='form-control picker-select' multiple"); ?></td>
        </tr>
        <tr>
          <th><?php echo $lang->project->desc;?></th>
          <td colspan='3'>
            <?php echo html::textarea('desc', '', "rows='6' class='form-control kindeditor' hidefocus='true'" . (strpos($requiredFields, 'desc') !== false ? ' required' : ''));?>
          </td>
        </tr>
        <?php $this->printExtendFields('', 'table', 'columns=3');?>
        <tr>
          <th><?php echo $lang->project->acl;?></th>
          <td colspan='3' class='aclBox'><?php echo nl2br(html::radio('acl', $lang->project->aclList, $acl, "onclick='setWhite(this.value);'", 'block'));?></td>
        </tr>
        <tr class="hidden" id="whitelistBox">
          <th><?php echo $lang->whitelist;?></th>
          <td colspan='2'>
            <div class='input-group'>
              <?php echo html::select('whitelist[]', $users, '', 'class="form-control chosen" multiple');?>
              <?php echo $this->fetch('my', 'buildContactLists', "dropdownName=whitelist");?>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan='4' class='text-center form-actions'>
            <?php
              echo html::hidden('model', $model);
              echo html::hidden('vision', 'lite');
              echo html::hidden('hasProduct', 1);
              echo html::hidden('parent', 0);
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
      <?php foreach ($copyProjects as $id => $name):?>
        <?php $active = ($copyProjectID == $id) ? ' active' : '';?>
        <div class='col-md-4 col-sm-6'><a href='javascript:;' data-id='<?php echo $id;?>' class='nobr <?php echo $active;?>'><?php echo html::icon($lang->icons['project'], 'text-muted') . ' ' . $name;?></a></div>
      <?php endforeach;?>
      </div>
      <?php endif;?>
    </div>
  </div>
</div>
<div id='projectAcl' class='hidden'>
  <?php echo nl2br(html::radio('acl', $lang->project->aclList, $acl, "onclick='setWhite(this.value);'", 'block'));?>
</div>
<?php include $this->app->getModuleRoot() . 'common/view/footer.html.php';?>
