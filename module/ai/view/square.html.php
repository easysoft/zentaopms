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
<div id="mainMenu">
  <div class="btn-toolbar pull-left">
    <?php if(count($categoryList) <= 9): ?>
      <?php foreach($categoryList as $key => $value): ?>
        <a href="<?= $this->createLink('ai', 'square', "category=$key"); ?>" class="btn btn-link<?php if($category === $key) echo ' btn-active-text'; ?>">
        <span class="text"><?= $value; ?></span>
        <?= $category === $key ? '<span class="label label-light label-badge" style="margin-left: 4px;">' . $pager->recTotal . '</span>' : ''; ?>
        </a>
      <?php endforeach; ?>
    <?php else : ?>
      <?php foreach(array_slice($categoryList, 0, 8) as $key => $value): ?>
        <a href="<?= $this->createLink('ai', 'square', "category=$key"); ?>" class="btn btn-link<?php if($category === $key) echo ' btn-active-text'; ?>">
          <span class="text"><?= $value; ?></span>
          <?= $category === $key ? '<span class="label label-light label-badge" style="margin-left: 4px;">' . $pager->recTotal . '</span>' : ''; ?>
        </a>
      <?php endforeach; ?>
      <div class="btn-group">
        <?php $moreCategoryList = array_slice($categoryList, 8); ?>
        <a class="btn btn-link" data-toggle="dropdown"><?= array_key_exists($category, $moreCategoryList) ? ($moreCategoryList[$category] . '<span class="label label-light label-badge" style="margin-left: 4px;">' . $pager->recTotal . '</span>') : $lang->ai->miniPrograms->more; ?><span class="caret"></span></a>
        <ul class="dropdown-menu">
          <?php foreach($moreCategoryList as $key => $value): ?>
            <li <?php if($category === $key) echo 'class="active"'; ?>><a href="<?= $this->createLink('ai', 'square', "category=$key"); ?>"><?= $value; ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  <div class="miniprogram-container">
    <?php foreach($miniPrograms as $miniProgram) : ?>
      <div class="miniprogram-card">
        <div class="program-content">
          <div class="program-text">
            <header class="title">
              <?= $miniProgram->name; ?>
            </header>
            <div class="desc">
              <?= $miniProgram->desc; ?>
            </div>
          </div>
          <div class="program-avatar">
            <?php list($iconName, $iconTheme) = explode('-', $miniProgram->icon); ?>
            <button class="btn btn-icon" style="width: 46px; height: 46px; border-radius: 50%; display: flex; justify-content: center; align-items: center; border: 1px solid <?= $config->ai->miniPrograms->themeList[$iconTheme][1]; ?>; background-color: <?= $config->ai->miniPrograms->themeList[$iconTheme][0]; ?>">
              <?= $config->ai->miniPrograms->iconList[$iconName]; ?>
            </button>
          </div>
        </div>
        <div class="program-actions">
          <div class="badge"><?= $categoryList[$miniProgram->category]; ?></div>
          <?php
            $star = in_array($miniProgram->id, $collectedIDs) ? 'star' : 'star-empty';
            $delete = $star === 'star' ? 'true' : 'false';
          ?>
          <button class="btn btn-link btn-star" data-url="<?= $this->createLink('ai', 'collectMiniProgram', "appID={$miniProgram->id}&delete={$delete}"); ?>">
            <?= html::image("static/svg/{$star}.svg", "class='$star'");?>
            <?= $lang->ai->miniPrograms->collect; ?>
          </button>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <div class='table-footer'>
    <div class="table-statistic"><?= sprintf($lang->ai->miniPrograms->summary, count($miniPrograms)); ?></div>
    <?php $pager->show('right', 'pagerjs'); ?>
  </div>
</div>
<?php include '../../common/view/footer.html.php'; ?>
