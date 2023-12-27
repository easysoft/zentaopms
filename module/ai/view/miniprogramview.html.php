<?php

/**
 * The ai prompt details view file of ai module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php'; ?>
<?php js::set('miniProgramID', $miniProgram->id); ?>
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
        <button type="button" class="btn btn-primary" onclick="unpublishMiniProgram()" data-dismiss="modal"><?= $lang->confirm; ?></button>
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
        <button type="button" class="btn btn-primary" onclick="publishMiniProgram()" data-dismiss="modal"><?= $lang->confirm; ?></button>
        <button type="button" class="btn" data-dismiss="modal"><?= $lang->cancel; ?></button>
      </div>
    </div>
  </div>
</div>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?= html::a(helper::createLink('ai', 'miniPrograms'), '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'"); ?>
    <div class="divider"></div>
    <div class="page-title">
      <span class="label label-id"><?= $miniProgram->id; ?></span>
      <span class="text" title='<?= $miniProgram->name; ?>'><?= $miniProgram->name; ?></span>
      <?php if ($miniProgram->deleted) echo "<span class='label label-danger'>{$lang->ai->prompts->deleted}</span>"; ?>
    </div>
  </div>
  <?php if(common::hasPriv('ai', 'createMiniProgram')): ?>
    <div class="btn-toolbar pull-right">
      <?= html::a($this->createLink('ai', 'createMiniProgram'), "<i class='icon icon-plus'></i> " . $lang->ai->miniPrograms->create, '', "class='btn btn-primary'"); ?>
    </div>
  <?php endif; ?>
</div>

<div id="mainContent" class="main-row">
  <div class="main-col col-8">
    <div class="cell prompt-details">
      <div class="detail">
        <div class="detail-title"><?= $lang->ai->miniPrograms->fieldConfiguration; ?></div>
        <table class="main-table table has-sort-head table-fixed">
          <thead>
            <tr>
              <th><?= $lang->ai->miniPrograms->field->name; ?></th>
              <th><?= $lang->ai->miniPrograms->field->type; ?></th>
              <th><?= $lang->ai->miniPrograms->optionName; ?></th>
              <th><?= $lang->ai->miniPrograms->field->placeholder; ?></th>
              <th><?= $lang->ai->miniPrograms->field->required; ?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($fields as $field) : ?>
              <tr>
                <td><?= $field->name; ?></td>
                <td><?= $lang->ai->miniPrograms->field->typeList[$field->type]; ?></td>
                <td><?= $field->options ?: '-'; ?></td>
                <td><?= $field->placeholder ?: '-'; ?></td>
                <td><?= $lang->ai->miniPrograms->field->requiredOptions[$field->required]; ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="cell prompt-details">
      <div class="detail">
        <div class="detail-title"><?= $lang->ai->miniPrograms->promptTemplate; ?></div>
        <?php
          $formatedPrompt = str_replace('&lt;', '<', $miniProgram->prompt);
          $formatedPrompt = str_replace('&gt;', '>', $formatedPrompt);
          $formatedPrompt = preg_replace('/<([^>]+)>/', '<strong><$1></strong>', $formatedPrompt);
        ?>
        <div class="detail-content article-content"><?= $formatedPrompt ?></div>
      </div>
    </div>
    <div class="cell"><?php include '../../common/view/action.html.php'; ?></div>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?= html::a($this->createLink('ai', 'miniPrograms'), '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn'"); ?>
        <?php if($miniProgram->deleted !== '1'): ?>
          <?= "<div class='divider'></div>"; ?>
        <?php if($miniProgram->published === '1' && common::hasPriv('ai', 'unpublishMiniProgram')): ?>
          <button class="btn" onclick="openDisableDialog(event)" title="<?= $lang->ai->prompts->action->disable; ?>">
            <i class="icon-ban-circle icon-sm"></i> <?= $lang->ai->prompts->action->disable; ?>
          </button>
        <?php else: ?>
          <?php if(common::hasPriv('ai', 'editMiniProgram')): ?>
            <a class='btn' title='<?= $lang->ai->prompts->action->edit; ?>' href='<?= $this->createLink('ai', 'editMiniProgram', "appID=$miniProgram->id"); ?>'>
              <i class='icon-edit icon-sm'></i> <?= $lang->ai->prompts->action->edit; ?>
            </a>
          <?php endif; ?>
          <?php if(common::hasPriv('ai', 'testMiniProgram')): ?>
            <button
              class="btn iframe"
              data-toggle="modal"
              data-width="800"
              data-height="600"
              title="<?= $lang->ai->prompts->action->test; ?>"
              data-iframe="<?= $this->createLink('ai', 'testMiniProgram', "appID={$miniProgram->id}&onlybody=yes"); ?>"
            >
              <i class="icon-menu-backend icon-sm"></i> <?= $lang->ai->prompts->action->test; ?>
            </button>
          <?php endif; ?>
          <?php if(common::hasPriv('ai', 'publishMiniProgram')): ?>
            <button class="btn" onclick="openPublishDialog(event)" title="<?= $lang->ai->prompts->action->publish; ?>">
              <i class="icon-publish icon-sm"></i> <?= $lang->ai->prompts->action->publish; ?>
            </button>
          <?php endif; ?>
          <?php if(common::hasPriv('ai', 'deleteMiniProgram')): ?>
            <button class="btn" onclick="openDeleteDialog(event)" title="<?= $lang->ai->prompts->action->delete; ?>">
              <i class="icon-trash icon-sm"></i> <?= $lang->ai->prompts->action->delete; ?>
            </button>
          <?php endif; ?>
        <?php endif; ?>
      <?php endif; ?>
      </div>
    </div>
  </div>
  <div class="side-col col-4">
    <div class="cell">
      <div class='tabs'>
        <ul class='nav nav-tabs'>
          <li class='active'><a href='#promptBasicInfo' data-toggle='tab'><?= $lang->ai->prompts->basicInfo; ?></a></li>
        </ul>
      </div>
      <div class='tab-content'>
        <div class='tab-pane active' id='promptBasicInfo'>
          <table class="table table-data">
            <tbody>
              <tr>
                <th class="w-90px"><?= $lang->ai->miniPrograms->category; ?></th>
                <td><?= $categoryList[$miniProgram->category]; ?></td>
              </tr>
              <tr>
                <th class="w-90px"><?= $lang->prompt->name; ?></th>
                <td><?= $miniProgram->name; ?></td>
              </tr>
              <tr>
                <th class="w-90px"><?= $lang->ai->miniPrograms->desc; ?></th>
                <td><?= $miniProgram->desc; ?></td>
              </tr>
              <tr>
                <th class="w-90px"><?= $lang->prompt->model; ?></th>
                <td><?= $lang->ai->miniPrograms->modelList[$miniProgram->model]; ?></td>
              </tr>
              <tr>
                <th class="w-90px"><?= $lang->prompt->status; ?></th>
                <td><?= $lang->ai->miniPrograms->publishedOptions[$miniProgram->published]; ?></td>
              </tr>
              <tr>
                <th class="w-90px"><?= $lang->ai->miniPrograms->icon; ?></th>
                <td>
                  <?php list($iconName, $iconTheme) = explode('-', $miniProgram->icon); ?>
                  <button class="btn btn-icon" style="width: 46px; height: 46px; display: flex; justify-content: center; align-items: center; border: 1px solid <?= $config->ai->miniPrograms->themeList[$iconTheme][1]; ?>; background-color: <?= $config->ai->miniPrograms->themeList[$iconTheme][0]; ?>">
                    <?= $config->ai->miniPrograms->iconList[$iconName]; ?>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div id="mainActions" class='main-actions'>
    <?php common::printPreAndNext($preAndNext, helper::createLink('ai', 'miniProgramView', 'id=%d')); ?>
  </div>
</div>
<?php include '../../common/view/footer.html.php'; ?>
