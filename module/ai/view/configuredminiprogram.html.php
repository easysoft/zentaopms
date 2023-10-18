<?php

/**
 * The create ai mini program file of ai module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php'; ?>

<?php
  js::set('pleaseInput', $lang->ai->miniPrograms->placeholder->input);
  js::set('deleteTip', $lang->ai->miniPrograms->deleteTip);
?>


<template id="text-danger-template">
  <div id="nameLabel" class="text-danger help-text"><?php echo $lang->ai->miniPrograms->field->emptyNameWarning; ?></div>
</template>

<template id="option-template">
  <div class="input-group">
    <span class="input-group-addon"><?php echo $lang->ai->miniPrograms->field->option; ?>1</span>
    <input name="option[]" type="text" class="form-control" placeholder="<?php echo $lang->ai->miniPrograms->placeholder->input; ?>" />
    <span class="input-group-btn">
      <button type="button" class="btn btn-default btn-icon" onclick="handleAddOptionClick(event)"><i class="icon icon-plus"></i></button>
      <button type="button" class="btn btn-default btn-icon" onclick="handleRemoveOptionClick(event)"><i class="icon icon-minus"></i></button>
    </span>
  </div>
</template>

<template id="field-template">
  <form>
    <table class="table table-form">
      <tbody>
        <tr>
          <th><?php echo $lang->ai->miniPrograms->field->name; ?></th>
          <td class="required"><input type="text" name="field-name" class="form-control" /></td>
        </tr>
        <tr>
          <th><?php echo $lang->ai->miniPrograms->field->type; ?></th>
          <td>
            <select name="field-type" class="form-control" onchange="handleFieldTypeChange(event)">
              <option value="text"><?php echo $lang->ai->miniPrograms->field->typeList[0]; ?></option>
              <option value="textarea"><?php echo $lang->ai->miniPrograms->field->typeList[1]; ?></option>
              <option value="radio"><?php echo $lang->ai->miniPrograms->field->typeList[2]; ?></option>
              <option value="checkbox"><?php echo $lang->ai->miniPrograms->field->typeList[3]; ?></option>
            </select>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->ai->miniPrograms->field->placeholder; ?></th>
          <td><input name="placeholder" type="text" class="form-control" placeholder="<?php echo $lang->ai->miniPrograms->placeholder->default; ?>" /></td>
        </tr>
        <tr class="field-options hidden">
          <th></th>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->ai->miniPrograms->field->required; ?></th>
          <td>
            <div class="radio" style="display: flex; align-items: center; gap: 4px;">
              <label>
                <input type="radio" name="required" value="1" checked><?php echo $lang->ai->miniPrograms->field->isRequired; ?>
              </label>
              <label>
                <input type="radio" name="required" value="0"><?php echo $lang->ai->miniPrograms->field->isNotRequired; ?>
              </label>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
</template>

<div class="modal fade" id="add-field-modal">
  <div class="modal-dialog" style="width: 600px;">
    <div class="modal-content">
      <div class="modal-header" style="border-bottom: none; padding-left: 12px;">
        <strong style="font-size: 20px;"><?php echo $lang->ai->miniPrograms->field->add; ?></strong>
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body" style="display: flex; gap: 42px; padding-right: 36px;"></div>
      <div class="modal-footer" style="display: flex; justify-content: center; border-top: none;">
        <button type="button" class="btn btn-wide btn-primary" id="save-add-field-button" onclick="handleSaveFieldClick()"><?php echo $lang->save; ?></button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="edit-field-modal">
  <div class="modal-dialog" style="width: 600px;">
    <div class="modal-content">
      <div class="modal-header" style="border-bottom: none; padding-left: 12px;">
        <strong style="font-size: 20px;"><?php echo $lang->ai->miniPrograms->field->edit; ?></strong>
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body" style="display: flex; gap: 42px; padding-right: 36px;"></div>
      <div class="modal-footer" style="display: flex; justify-content: center; border-top: none;">
        <button type="button" class="btn btn-wide btn-primary" id="save-add-field-button" onclick="handleSaveEditedFieldClick()"><?php echo $lang->save; ?></button>
      </div>
    </div>
  </div>
</div>
<div id="mainContent" class="main-content">
  <div style="flex-basis: 30%; margin-right: 16px;">
    <header>
      <strong><?php echo $lang->ai->miniPrograms->field->configuration; ?></strong>
    </header>
    <main class="field-configuration-main">
      <div>
        <a onclick="handleAddFieldClick()" style="border: 1px dashed #D8DBDE; border-radius: 2px; margin: 25px 72px; display: flex; align-items: center; flex-direction: column; padding: 12px; gap: 4px;">
          <div style="color: #2E7FFF; display: flex; align-items: center; gap: 4px;"><i class="icon icon-plus"></i><span><?php echo $lang->ai->miniPrograms->field->addTitle; ?></span></div>
          <div style="color: #9EA3B0; font-size: 12divx;"><?php echo $lang->ai->miniPrograms->field->addTip; ?></div>
        </a>
      </div>
      <table class="table table-form">
        <tbody class="field-configuration" id="sortable-list"></tbody>
      </table>
    </main>
  </div>
  <div style="flex-basis: 40%; border-right: 1px solid #E6EAF1;">
    <header>
      <strong><?php echo $lang->ai->miniPrograms->field->debug; ?></strong>
    </header>
    <main>

    </main>
  </div>
  <div style="flex-basis: 30%;">
    <header>
      <strong><?php echo $lang->ai->miniPrograms->field->preview; ?></strong>
    </header>
    <main>

    </main>
  </div>
</div>
<?php include '../../common/view/footer.html.php'; ?>
