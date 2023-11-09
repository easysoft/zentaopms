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
js::set('deleteTip', $lang->ai->miniPrograms->deleteFieldTip);
js::set('emptyWarning', $lang->ai->miniPrograms->field->emptyNameWarning);
js::set('duplicatedWarning', $lang->ai->miniPrograms->field->duplicatedNameWarning);
js::set('emptyOptionWarning', $lang->ai->miniPrograms->field->emptyOptionWarning);
js::set('appID', $appID);
js::set('publishConfirm', $lang->ai->miniPrograms->publishConfirm);
js::set('emptyPrompterTip', $lang->ai->miniPrograms->emptyPrompterTip);
js::set('currentFields', $currentFields);
js::set('currentPrompt', $currentPrompt);
js::set('defaultFields', $lang->ai->miniPrograms->field->default);
?>

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
        <button type="button" class="btn btn-wide btn-primary" onclick="handleSaveFieldClick()"><?php echo $lang->save; ?></button>
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
        <button type="button" class="btn btn-wide btn-primary" onclick="handleSaveEditedFieldClick()"><?php echo $lang->save; ?></button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="back-to-list-modal">
  <div class="modal-dialog" style="width: 480px;">
    <div class="modal-content">
      <div class="modal-header" style="border-bottom: none; padding-left: 12px; display: flex; align-items: center; gap: 16px; margin: 0;">
        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="12.0159" cy="12.0163" r="12" transform="rotate(0.0777774 12.0159 12.0163)" fill="#FFA34D" />
          <path d="M12.4516 14.621C12.8867 14.6215 13.3224 14.1498 13.3231 13.6775L13.6588 7.42006C13.6595 6.94777 13.661 6.00319 12.3559 6.0016C11.1595 6.00013 11.0495 6.8265 11.0486 7.41686L11.3655 13.6751C11.5823 14.1476 12.0166 14.6204 12.4516 14.621ZM12.4499 15.8017C11.7973 15.8009 11.1439 16.3905 11.1426 17.217C11.1416 17.9254 11.6843 18.6345 12.4456 18.6354C13.2069 18.6363 13.7516 18.0467 13.7528 17.2202C13.7541 16.3936 13.1024 15.8025 12.4499 15.8017Z" fill="white" />
        </svg>
        <span style="font-size: 16px;"><?php echo $lang->ai->miniPrograms->backToListPageTip; ?></span>
      </div>
      <div class="modal-footer" style="display: flex; padding-top: 0; justify-content: center; border-top: none; gap: 10px;">
        <button class="btn btn-wide btn-primary" onclick="backWithSave()"><?= $lang->save; ?></button>
        <button class="btn btn-wide" data-dismiss="modal"><?php echo $lang->cancel; ?></button>
        <button class="btn btn-wide btn-link text-primary" onclick="backWithoutSave()"><?= $lang->ai->prompts->roleTemplateSaveList['discard']; ?></button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="publish-confirm-modal">
  <div class="modal-dialog" style="width: 480px;">
    <div class="modal-content">
      <div class="modal-header" style="border-bottom: none; padding-left: 12px; display: flex; align-items: center; gap: 16px; margin: 0;">
        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
          <circle cx="12.0159" cy="12.0163" r="12" transform="rotate(0.0777774 12.0159 12.0163)" fill="#FFA34D" />
          <path d="M12.4516 14.621C12.8867 14.6215 13.3224 14.1498 13.3231 13.6775L13.6588 7.42006C13.6595 6.94777 13.661 6.00319 12.3559 6.0016C11.1595 6.00013 11.0495 6.8265 11.0486 7.41686L11.3655 13.6751C11.5823 14.1476 12.0166 14.6204 12.4516 14.621ZM12.4499 15.8017C11.7973 15.8009 11.1439 16.3905 11.1426 17.217C11.1416 17.9254 11.6843 18.6345 12.4456 18.6354C13.2069 18.6363 13.7516 18.0467 13.7528 17.2202C13.7541 16.3936 13.1024 15.8025 12.4499 15.8017Z" fill="white" />
        </svg>
        <strong style="font-size: 16px;"><?php echo $lang->ai->miniPrograms->publishConfirm[0]; ?></strong>
      </div>
      <div class="modal-body" style="padding-left: 70px;"><?php echo $lang->ai->miniPrograms->publishConfirm[1]; ?></div>
      <div class="modal-footer" style="display: flex; justify-content: center; border-top: none; gap: 10px;">
        <a class="btn btn-wide btn-primary"><?php echo $lang->confirm; ?></a>
        <a class="btn btn-wide" data-dismiss="modal"><?php echo $lang->cancel; ?></a>
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
        <a onclick="handleAddFieldClick()" style="border: 1px dashed #D8DBDE; border-radius: 2px; display: flex; align-items: center; flex-direction: column; padding: 12px; gap: 4px;">
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
      <div class="content-debug-area" style="min-height: 50%;">
        <div class="area-title">
          <strong><?php echo $lang->ai->miniPrograms->field->contentDebugging; ?></strong>
          <i title="<?= $lang->help; ?>" class="icon icon-help text-warning"></i>
          <span class="text-muted"><?php echo $lang->ai->miniPrograms->field->contentDebuggingTip; ?></span>
        </div>
        <table class="table table-form">
          <tbody class="field-content"></tbody>
        </table>
      </div>
      <div class="prompt-design-area" style="height: 50%; position: relative; padding-top: 0;">
        <div class="area-title">
          <strong><?php echo $lang->ai->miniPrograms->field->prompterDesign; ?></strong>
          <i title="<?= $lang->help; ?>" class="icon icon-help text-warning"></i>
          <span class="text-muted"><?php echo $lang->ai->miniPrograms->field->prompterDesignTip; ?></span>
        </div>
        <div class="form-control" id="autocomplete-textarea" contenteditable="true" style="overflow-y: auto; position: absolute; top: 32px; left: 24px; right: 24px; bottom: 24px; height: auto; width: auto;"></div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.textcomplete/1.8.5/jquery.textcomplete.min.js"></script>
      </div>
    </main>
  </div>
  <div style="flex-basis: 30%;">
    <header>
      <strong><?php echo $lang->ai->miniPrograms->field->preview; ?></strong>
    </header>
    <main>
      <div class="prompt-preview-area" style="height: 50%; position: relative;">
        <div class="area-title" style="display: flex; justify-content: space-between;">
          <strong><?php echo $lang->ai->miniPrograms->field->prompterPreview; ?></strong>
          <button class="btn btn-link" style="color: #2E7FFF; position: absolute; right: 16px;">
            <i class="icon-publish text-primary"></i>
            <?php echo $lang->ai->miniPrograms->field->generateResult; ?>
          </button>
        </div>
        <div class="preview-container"></div>
      </div>
      <div class="prompt-result-area" style="height: 50%; position: relative; padding-top: 0;">
        <div class="area-title">
          <strong><?php echo $lang->ai->miniPrograms->field->resultPreview; ?></strong>
        </div>
        <div class="preview-container"></div>
      </div>
    </main>
  </div>
</div>
<footer style="display: flex; justify-content: center; align-items: center; height: 56px; background: #fff; border-top: 1px solid #eff1f7; position: fixed; bottom: 0; left: 20px; right: 20px; gap: 24px;">
  <a onclick="backToList()" class="btn btn-wide"><?php echo $lang->ai->miniPrograms->backToListPage; ?></a>
  <a href="<?php echo $this->createLink('ai', 'createMiniProgram', "appID=$appID"); ?>" class="btn btn-wide"><?php echo $lang->ai->miniPrograms->lastStep; ?></a>
  <a class="btn btn-wide btn-secondary" onclick="saveMiniProgram('0')"><?php echo $lang->save; ?></a>
  <a class="btn btn-wide btn-primary" onclick="saveMiniProgram('1')"><?php echo $lang->ai->prompts->action->publish; ?></a>
</footer>
<?php include '../../common/view/footer.html.php'; ?>
