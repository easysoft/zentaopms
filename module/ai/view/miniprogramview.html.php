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
  <div class="btn-toolbar pull-right">
    <?= html::a($this->createLink('ai', 'createMiniProgram'), "<i class='icon icon-plus'></i> " . $lang->ai->miniPrograms->create, '', "class='btn btn-primary'"); ?>
  </div>
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
        <div class="detail-content article-content"><?= $miniProgram->prompt; ?></div>
      </div>
    </div>
    <div class="cell"><?php include '../../common/view/action.html.php'; ?></div>
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
                  <button class="btn btn-icon" style="width: 46px; height: 46px; border-radius: 50%; display: flex; justify-content: center; align-items: center; border: 1px solid <?= $config->ai->miniPrograms->themeList[$iconTheme][1]; ?>; background-color: <?= $config->ai->miniPrograms->themeList[$iconTheme][0]; ?>">
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
