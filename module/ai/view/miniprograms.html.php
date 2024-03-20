<?php
include '../../common/view/header.html.php';
$finalList = array_merge($categoryList, $lang->ai->miniPrograms->allCategories);
$isNotOpen = $config->edition != 'open';
?>
<div id="mainMenu" class="clearfix">
  <div id="sidebarHeader">
    <div class="title" title="<?= $finalList[$category]; ?>">
      <?= $finalList[$category]; ?>
      <?php if($category) echo html::a($this->createLink('ai', 'miniPrograms'), "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted'"); ?>
    </div>
  </div>
  <div class="btn-toolbar pull-left">
    <?php
    foreach($this->lang->ai->miniPrograms->statuses as $statusKey => $statusName)
    {
      echo html::a($this->createLink('ai', 'miniPrograms', "category=$category&status=$statusKey"), "<span class='text'>{$this->lang->ai->miniPrograms->statuses[$statusKey]}" . ($status == $statusKey ? '<span class="label label-light label-badge" style="margin-left: 4px;">' . $pager->recTotal . '</span>' : '') . "</span>", '', "id='status-$statusKey' class='btn btn-link" . ($status == $statusKey ? ' btn-active-text' : '') . "'");
    }
    ?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('ai', 'importMiniProgram')): ?>
    <a style="display: flex; align-items: center; gap: 2px;" class="btn btn-link" data-toggle="tooltip" data-placement="left" title="<?= $lang->ai->toZentaoStoreAIPage; ?>" href="https://www.zentao.net/extension-browse-1625.html" target="_blank">
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M1.75962 11.375V9.49025C1.75787 9.47362 1.75087 9.45963 1.75087 9.44213L1.75 7.45412C1.75 7.20125 1.94687 7 2.1875 7C2.429 6.99825 2.6075 7.30275 2.6075 7.55212V9.541C2.6075 9.57163 2.61363 9.49988 2.6075 9.52875V10.5079H10.5V8.463C10.5009 8.46475 10.4991 8.46387 10.5 8.46475V7.45412C10.5 7.20125 10.7004 7 10.941 7C11.1825 6.99825 11.375 7.203 11.375 7.45325V9.44213C11.375 9.44825 11.3759 9.4535 11.375 9.45963V11.375H1.75962ZM3.6155 4.592C3.6155 5.43813 4.27175 6.125 5.08113 6.125C5.88875 6.125 6.545 5.43813 6.545 4.592C6.545 5.43813 7.20125 6.125 8.00975 6.125C8.81913 6.125 9.47538 5.43813 9.47538 4.592C9.47538 5.43813 9.97588 6.125 10.7844 6.125C11.5938 6.125 12.25 5.43813 12.25 4.592L10.7844 0.875H2.33975L0.875 4.592C0.875 5.43813 1.34225 6.125 2.15075 6.125C2.95925 6.125 3.6155 5.43813 3.6155 4.592ZM12.25 12.6875C12.25 12.446 12.054 12.25 11.8125 12.25H1.3125C1.071 12.25 0.875 12.446 0.875 12.6875C0.875 12.929 1.071 13.125 1.3125 13.125H11.8125C12.054 13.125 12.25 12.929 12.25 12.6875Z" fill="#838A9D"/>
        </svg>
        <?= $lang->ai->store; ?>
      </a>
      <a class="btn btn-primary" data-toggle="modal" data-target="#import-miniprogram">
        <i class="icon icon-import"></i>
        <?= $lang->ai->import; ?>
      </a>
    <?php endif; ?>
    <?php if($isNotOpen && common::hasPriv('ai', 'createMiniProgram')): ?>
      <?= html::a($this->createLink('ai', 'createMiniProgram'), "<i class='icon icon-plus'></i> " . $lang->ai->miniPrograms->create, '', "class='btn btn-primary'"); ?>
    <?php endif; ?>
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
<div class="modal fade" id="import-miniprogram">
  <div class="modal-dialog" style="width: 600px;">
    <div class="modal-content">
      <div class="modal-header">
        <strong style="font-size: 20px; color: #0B0F18;"><?= $lang->ai->import; ?></strong>
        <a data-dismiss="modal" class="close-import-dialog">
          <i class="icon-close"></i>
        </a>
      </div>
      <div class="modal-body">
        <form class="form-ajax" method="post" action="<?= $this->createLink('ai', 'importMiniProgram'); ?>" id="import-miniprogram-form">
          <table class="table table-form">
            <tr>
              <th><?= $lang->ai->installPackage; ?></th>
              <td>
                <?= html::file('file', "accept='.zip' class='form-control'"); ?>
              </td>
            </tr>
            <tr>
              <th><?= $lang->ai->miniPrograms->category; ?></th>
              <td>
                <?= html::select('category', array_merge($lang->ai->miniPrograms->categoryList, $categoryList), $category, "class='form-control chosen'"); ?>
              </td>
            </tr>
            <tr>
              <th><?= $lang->ai->toPublish; ?></th>
              <td>
                <div class="radio" style="display: flex; align-items: center; gap: 4px;">
                  <label>
                    <input type="radio" name="published" value="1"><?= $lang->ai->miniPrograms->field->requiredOptions[1]; ?>
                  </label>
                  <label>
                    <input type="radio" name="published" value="0" checked><?= $lang->ai->miniPrograms->field->requiredOptions[0]; ?>
                  </label>
                </div>
              </td>
            </tr>
          </table>
          <div style="display: flex; justify-content: center; border-top: none; padding-top: 10px;">
            <button type="submit" class="btn btn-primary btn-wide"><?= $lang->save; ?></button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  <div class="side-col" id="sidebar">
    <div class="cell">
      <ul id="modules" class="tree" data-ride="tree" data-name="tree-modules">
        <?php foreach($categoryList as $key => $value): ?>
          <li <?php if($category == $key) echo 'class="active"'; ?>>
            <a href="<?= $this->createLink('ai', 'miniPrograms', "category=$key"); ?>"><?= $value; ?></a>
          </li>
        <?php endforeach; ?>
      </ul>
      <?php if(common::hasPriv('ai', 'editMiniProgramCategory')): ?>
        <div class="text-center">
          <a class="btn btn-info btn-wide" href="<?= $this->createLink('ai', 'editMiniProgramCategory'); ?>"><?= $lang->ai->maintenanceGroup; ?></a>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <div class="main-col">
    <?php if(empty($miniPrograms)): ?>
      <div class="table-empty-tip">
        <p>
          <span class="text-muted"><?php echo $lang->ai->miniPrograms->emptyList;?></span>
          <?php if($isNotOpen && common::hasPriv('ai', 'createMiniProgram')) echo html::a($this->createLink('ai', 'createMiniProgram'), "<i class='icon icon-plus'></i> " . $lang->ai->miniPrograms->create, '', "class='btn btn-info'");?>
        </p>
      </div>
    <?php else: ?>
      <div class="main-table">
        <table class="main-table table has-sort-head table-fixed">
          <thead>
            <tr>
              <?php $vars = "category=$category&status=$status&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
              <th class="c-id" style="width: 70px;"><?php common::printOrderLink('id', $orderBy, $vars, 'ID'); ?></th>
              <th class="c-name" style="width: 30%;"><?= $lang->prompt->name; ?></th>
              <th class="c-status"><?= $lang->prompt->status; ?></th>
              <th class="c-category"><?= $lang->prompt->module; ?></th>
              <th class="c-createdby"><?= $lang->prompt->createdBy; ?></th>
              <th class="c-createddate w-130px"><?php common::printOrderLink('createdDate', $orderBy, $vars, $lang->prompt->createdDate); ?></th>
              <th class="c-publisheddate w-130px"><?php common::printOrderLink('publishedDate', $orderBy, $vars, $lang->ai->miniPrograms->latestPublishedDate); ?></th>
              <th class="c-actions" style="width: 160px;"><?= $lang->actions; ?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($miniPrograms as $miniProgram) : ?>
              <tr>
                <td class="c-id"><?= $miniProgram->id; ?></td>
                <?php $link = $isNotOpen && common::hasPriv('ai', 'miniProgramView') ? $this->createLink('ai', 'miniProgramView', "id={$miniProgram->id}") : null; ?>
                <td class="c-name" title="<?= $miniProgram->name; ?>"><?= is_null($link) ? $miniProgram->name : "<a href='$link'>{$miniProgram->name}</a>" ?></td>
                <td class="c-status"><?= $miniProgram->publishedLabel; ?></td>
                <td class="c-category"><?= $miniProgram->categoryLabel; ?></td>
                <td class="c-createdby"><?= $miniProgram->createdByLabel; ?></td>
                <td class="c-createddate"><?= $miniProgram->createdDate; ?></td>
                <td class="c-publisheddate"><?= $miniProgram->publishedDate; ?></td>
                <td class="c-actions">
                  <?php
                    $isPublished = $miniProgram->published === '1';
                    $isBuiltIn = $miniProgram->builtIn === '1';
                  ?>
                  <?php
                  if($isNotOpen && common::hasPriv('ai', 'editMiniProgram'))
                  {
                    echo $isPublished || $isBuiltIn
                      ? "<button class='btn' disabled title='{$lang->ai->prompts->action->edit}'><i class='icon icon-edit text-primary'></i></button>"
                      : "<a class='btn' title='{$lang->ai->prompts->action->edit}' href='{$this->createLink('ai', 'editMiniProgram', "appID=$miniProgram->id")}'><i class='icon icon-edit text-primary'></i></a>";
                  }
                  ?>
                  <?php if($isNotOpen && common::hasPriv('ai', 'testMiniProgram')): ?>
                    <button
                      class="btn iframe"
                      data-toggle="modal"
                      data-width="800"
                      data-height="500"<?= $isPublished || $isBuiltIn ? ' disabled' : ''; ?>
                      title="<?= $lang->ai->prompts->action->test; ?>"
                      data-iframe="<?= $this->createLink('ai', 'testMiniProgram', "appID={$miniProgram->id}&onlybody=yes"); ?>"
                    >
                      <i class="icon icon-menu-backend text-primary"></i>
                    </button>
                  <?php endif; ?>
                  <?php if(common::hasPriv('ai', 'publishMiniProgram')): ?>
                    <button class="btn" onclick="openPublishDialog(event)" title="<?= $lang->ai->prompts->action->publish; ?>"<?= $miniProgram->canPublish ? '' : ' disabled'; ?>>
                      <i class="icon icon-publish text-primary"></i>
                    </button>
                  <?php endif; ?>
                  <?php if(common::hasPriv('ai', 'unpublishMiniProgram')): ?>
                    <button class="btn" onclick="openDisableDialog(event)" title="<?= $lang->ai->prompts->action->unpublish; ?>"<?= $isPublished ? '' : ' disabled'; ?>>
                      <i class="icon icon-ban-circle text-primary"></i>
                    </button>
                  <?php endif; ?>
                  <?php if($isNotOpen && common::hasPriv('ai', 'exportMiniProgram')): ?>
                    <button class="btn" onclick="exportMiniProgram(event)" title="<?= $lang->ai->export; ?>"<?= $isPublished && !$isBuiltIn ? '' : ' disabled'; ?>>
                      <i class="icon icon-export text-primary"></i>
                    </button>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <div class='table-footer'>
          <div class="table-statistic"><?= sprintf($lang->ai->miniPrograms->summary, count($miniPrograms)); ?></div>
          <?php $pager->show('right', 'pagerjs'); ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>
<?php include '../../common/view/footer.html.php'; ?>
