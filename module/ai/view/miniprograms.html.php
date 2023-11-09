<?php

/**
 * The ai mini programs view file of ai module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php'; ?>
<div id="mainMenu" class="clearfix">
  <div id="sidebarHeader">
    <div class="title" title="<?php echo $this->lang->ai->prompts->modules[$module]; ?>">
      <?php echo $this->lang->ai->prompts->modules[$module]; ?>
      <?php if($module) echo html::a($this->createLink('ai', 'prompts'), "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted'"); ?>
    </div>
  </div>
  <div class="btn-toolbar pull-left">
    <?php
    foreach($this->lang->ai->prompts->statuses as $statusKey => $statusName)
    {
      echo html::a($this->createLink('ai', 'prompts', "module=$module&status=$statusKey"), "<span class='text'>{$this->lang->ai->prompts->statuses[$statusKey]}" . ($status == $statusKey ? '<span class="label label-light label-badge" style="margin-left: 4px;">' . $pager->recTotal . '</span>' : '') . "</span>", '', "id='status-$statusKey' class='btn btn-link" . ($status == $statusKey ? ' btn-active-text' : '') . "'");
    }
    ?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php echo html::a($this->createLink('ai', 'createMiniProgram'), "<i class='icon icon-plus'></i> " . $lang->ai->miniPrograms->create, '', "class='btn btn-primary'"); ?>
  </div>
</div>
<div class="modal fade" id="disable-miniprogram">
  <div class="modal-dialog" style="width: 480px;">
    <div class="modal-content">
      <div class="modal-header" style="border-bottom: none; padding-left: 12px; display: flex; align-items: center;">
        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="12.0159" cy="12.0163" r="12" transform="rotate(0.0777774 12.0159 12.0163)" fill="#FFA34D" />
          <path d="M12.4516 14.621C12.8867 14.6215 13.3224 14.1498 13.3231 13.6775L13.6588 7.42006C13.6595 6.94777 13.661 6.00319 12.3559 6.0016C11.1595 6.00013 11.0495 6.8265 11.0486 7.41686L11.3655 13.6751C11.5823 14.1476 12.0166 14.6204 12.4516 14.621ZM12.4499 15.8017C11.7973 15.8009 11.1439 16.3905 11.1426 17.217C11.1416 17.9254 11.6843 18.6345 12.4456 18.6354C13.2069 18.6363 13.7516 18.0467 13.7528 17.2202C13.7541 16.3936 13.1024 15.8025 12.4499 15.8017Z" fill="white" />
        </svg>
        <span style="padding-left: 16px;"><?= $lang->ai->miniPrograms->disableTip; ?></span>
      </div>
      <div class="modal-footer" style="display: flex; justify-content: center; border-top: none; padding-top: 0;">
        <button type="button" class="btn btn-primary" onclick="publishMiniProgram('0')" data-dismiss="modal"><?= $lang->confirm; ?></button>
        <button type="button" class="btn" data-dismiss="modal"><?= $lang->cancel; ?></button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="delete-miniprogram">
  <div class="modal-dialog" style="width: 480px;">
    <div class="modal-content">
      <div class="modal-header" style="border-bottom: none; padding-left: 12px; display: flex; align-items: center;">
        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="12.0159" cy="12.0163" r="12" transform="rotate(0.0777774 12.0159 12.0163)" fill="#FFA34D" />
          <path d="M12.4516 14.621C12.8867 14.6215 13.3224 14.1498 13.3231 13.6775L13.6588 7.42006C13.6595 6.94777 13.661 6.00319 12.3559 6.0016C11.1595 6.00013 11.0495 6.8265 11.0486 7.41686L11.3655 13.6751C11.5823 14.1476 12.0166 14.6204 12.4516 14.621ZM12.4499 15.8017C11.7973 15.8009 11.1439 16.3905 11.1426 17.217C11.1416 17.9254 11.6843 18.6345 12.4456 18.6354C13.2069 18.6363 13.7516 18.0467 13.7528 17.2202C13.7541 16.3936 13.1024 15.8025 12.4499 15.8017Z" fill="white" />
        </svg>
        <span style="padding-left: 16px;"><?= $lang->ai->miniPrograms->deleteTip; ?></span>
      </div>
      <div class="modal-footer" style="display: flex; justify-content: center; border-top: none; padding-top: 0;">
        <button type="button" class="btn btn-primary" onclick="deleteMiniProgram('1')" data-dismiss="modal"><?= $lang->confirm; ?></button>
        <button type="button" class="btn" data-dismiss="modal"><?= $lang->cancel; ?></button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="publish-miniprogram">
  <div class="modal-dialog" style="width: 480px;">
    <div class="modal-content">
      <div class="modal-header" style="border-bottom: none; padding-left: 12px; display: flex; align-items: center;">
        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="12.0159" cy="12.0163" r="12" transform="rotate(0.0777774 12.0159 12.0163)" fill="#FFA34D" />
          <path d="M12.4516 14.621C12.8867 14.6215 13.3224 14.1498 13.3231 13.6775L13.6588 7.42006C13.6595 6.94777 13.661 6.00319 12.3559 6.0016C11.1595 6.00013 11.0495 6.8265 11.0486 7.41686L11.3655 13.6751C11.5823 14.1476 12.0166 14.6204 12.4516 14.621ZM12.4499 15.8017C11.7973 15.8009 11.1439 16.3905 11.1426 17.217C11.1416 17.9254 11.6843 18.6345 12.4456 18.6354C13.2069 18.6363 13.7516 18.0467 13.7528 17.2202C13.7541 16.3936 13.1024 15.8025 12.4499 15.8017Z" fill="white" />
        </svg>
        <span style="padding-left: 16px;"><?= $lang->ai->miniPrograms->publishTip; ?></span>
      </div>
      <div class="modal-footer" style="display: flex; justify-content: center; border-top: none; padding-top: 0;">
        <button type="button" class="btn btn-primary" onclick="publishMiniProgram('1')" data-dismiss="modal"><?= $lang->confirm; ?></button>
        <button type="button" class="btn" data-dismiss="modal"><?= $lang->cancel; ?></button>
      </div>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  <div class="side-col" id="sidebar">
    <div class="cell">
      <ul id="modules" class="tree" data-ride="tree" data-name="tree-modules">
      </ul>
    </div>
  </div>
  <div class="main-col">
    <div class="main-table">
      <table class="main-table table has-sort-head table-fixed">
        <thead>
          <tr>
            <td class="c-id" style="width: 70px;">ID</td>
            <td class="c-name" style="width: 30%;"><?= $lang->prompt->name; ?></td>
            <td class="c-status"><?= $lang->prompt->status; ?></td>
            <td class="c-category"><?= $lang->prompt->module; ?></td>
            <td class="c-createdby"><?= $lang->prompt->createdBy; ?></td>
            <td class="c-createddate"><?= $lang->prompt->createdDate; ?></td>
            <td class="c-publisheddate"><?= $lang->ai->miniPrograms->latestPublishedDate; ?></td>
            <td class="c-actions" style="width: 160px;"><?php echo $lang->actions; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($miniPrograms as $miniProgram) : ?>
            <tr>
              <td class="c-id"><?= $miniProgram->id; ?></td>
              <td class="c-name"><?= $miniProgram->name; ?></td>
              <td class="c-status"><?= $miniProgram->publishedLabel; ?></td>
              <td class="c-category"><?= $miniProgram->categoryLabel; ?></td>
              <td class="c-createdby"><?= $miniProgram->createdByLabel; ?></td>
              <td class="c-createddate"><?= $miniProgram->createdDate; ?></td>
              <td class="c-publisheddate"><?= $miniProgram->publishedDate; ?></td>
              <td class="c-actions">
                <?php $isPublished = $miniProgram->published === '1'; ?>
                <?php
                  echo $isPublished
                    ? "<button class='btn' disabled title='{$lang->ai->prompts->action->edit}'><i class='icon-edit text-primary'></i></button>"
                    : "<a class='btn' title='{$lang->ai->prompts->action->edit}' href='{$this->createLink('ai', 'createMiniProgram', "appID=$miniProgram->id")}'><i class='icon-edit text-primary'></i></a>";
                ?>
                <button
                  class="btn iframe"
                  data-toggle="modal"
                  data-width="800"
                  data-height="600"<?= $isPublished ? ' disabled' : ''; ?>
                  title="<?= $lang->ai->prompts->action->test; ?>"
                  data-iframe="<?= $this->createLink('ai', 'testMiniProgram', "appID={$miniProgram->id}&onlybody=yes"); ?>"
                >
                  <i class="icon-menu-backend text-primary"></i>
                </button>
                <button class="btn" onclick="openPublishDialog(event)" title="<?= $lang->ai->prompts->action->publish; ?>"<?= $miniProgram->canPublish ? '' : ' disabled'; ?>>
                  <i class="icon-publish text-primary"></i>
                </button>
                <button class="btn" onclick="openDisableDialog(event)" title="<?= $lang->ai->prompts->action->disable; ?>"<?= $isPublished ? '' : ' disabled'; ?>>
                  <i class="icon-ban-circle text-primary"></i>
                </button>
                <button class="btn" onclick="openDeleteDialog(event)" title="<?= $lang->ai->prompts->action->delete; ?>"<?= $isPublished ? ' disabled' : ''; ?>>
                  <i class="icon-trash text-primary"></i>
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php'; ?>
