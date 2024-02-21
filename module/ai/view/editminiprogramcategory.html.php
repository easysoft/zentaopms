<?php include '../../common/view/header.html.php'; ?>
<template id="custom-item">
  <div class="category-item">
    <div class="category-input"><input type="text" name="custom[]" value="" class="form-control"></div>
    <div class="category-actions">
      <button type="button" class="btn btn-link btn-icon btn-add" onclick="addItem(event)"><i class="icon icon-plus"></i></button>
      <button type="button" class="btn btn-link btn-icon btn-delete" onclick="deleteItem(event)"><i class="icon icon-close"></i></button>
    </div>
  </div>
</template>
<div id="mainMenu">
  <div class="btn-toolbar" style="padding-bottom: 2px;">
    <a href="<?= $this->createLink('ai', 'miniPrograms'); ?>" class="btn btn-secondary">
      <i class="icon icon-back icon-sm"></i> <?php echo $lang->goback; ?>
    </a>
    <div class="divider"></div>
    <div class="page-title">
      <span class='text'><?= $lang->ai->miniPrograms->maintenanceGroup; ?></span>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="main-col">
    <form class="main-form form-ajax" method='post'>
      <div class='panel'>
        <div class='panel-body'>
          <div class="category-container" style="width: 600px;">
            <?php foreach ($lang->ai->miniPrograms->categoryList as $key => $value) : ?>
              <div class="category-item">
                <div class="category-input"><input disabled type="text" name="<?= $key; ?>" value="<?= $value; ?>" class="form-control"></div>
                <div class="category-actions">
                  <button type="button" class="btn btn-link btn-icon btn-add" onclick="addItem(event)"><i class="icon icon-plus"></i></button>
                </div>
              </div>
            <?php endforeach; ?>
            <?php foreach ($categoryList as $key => $value) : ?>
              <div class="category-item">
                <div class="category-input"><input type="text" name="<?= $key; ?>" value="<?= $value; ?>" class="form-control"></div>
                <div class="category-actions">
                  <button type="button" class="btn btn-link btn-icon btn-add" onclick="addItem(event)"><i class="icon icon-plus"></i></button>
                  <button type="button" class="btn btn-link btn-icon btn-delete" onclick="deleteItem(event)" <?= in_array($key, $usedCustomCategories) ? 'disabled' :  ''; ?>><i class="icon icon-close"></i></button>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          <div class="form-actions">
            <button type="submit" class="btn btn-wide btn-primary"><?= $lang->save; ?></button>
            <a type="button" class="btn btn-wide" href="<?= $this->createLink('ai', 'miniPrograms'); ?>"><?= $lang->goback; ?></a>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php'; ?>
