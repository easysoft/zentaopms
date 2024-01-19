<?php include '../../common/view/header.html.php'; ?>

<?php
js::set('currentField', $currentFields);
js::set('currentPrompt', $currentPrompt);
js::set('appID', $appID);
?>
<?php js::import($jsRoot . 'textcomplete/jquery.textcomplete.min.js'); ?>

<form action="<?= $this->createLink('ai', 'testMiniProgram', "appID=$appID"); ?>" method="post" style="width: 100%; height: 100%; display: flex; flex-direction: column; padding: 24px 32px 32px 32px; background: #fff; overflow: hidden;">
  <strong style="font-size: 16px; padding-bottom: 16px;"><?= $lang->ai->prompts->action->test; ?></strong>
  <div id="test-miniprogram">
    <div style="flex-basis: 55%; border-right: 1px solid #E6EAF1; height: 100%;">
      <div class="content-debug-area" style="height: 50%; display: flex; flex-direction: column;">
        <div class="area-title">
          <strong style="font-size: 14px;"><?php echo $lang->ai->miniPrograms->field->contentDebugging; ?></strong>
        </div>
        <div style="flex-grow: 1; overflow-y: auto; width: 100%; padding-right: 10px;">
          <table class="table table-form">
            <tbody class="field-content">
              <?php foreach($currentFields as $key => $field): ?>
                <tr data-id="<?= "field-$key"; ?>">
                  <th title="<?= $field->name; ?>">
                    <span class="field-name"><?= $field->name; ?></span>
                  </th>
                  <?php
                  $fieldOptions = array();
                  if (!empty($field->options))
                  {
                    $fieldOptions = explode(',', $field->options);
                    $fieldOptions = array_combine($fieldOptions, $fieldOptions);
                  }; ?>
                  <td data-type="<?= $field->type; ?>" <?= empty($field->required) ? '' : 'class="required"'; ?>>
                    <?php if($field->type === 'text') : ?>
                      <input type="text" class="form-control field-type" placeholder="<?= $field->placeholder; ?>">
                    <?php elseif($field->type === 'textarea') : ?>
                      <textarea class="form-control field-type" placeholder="<?= $field->placeholder; ?>"></textarea>
                    <?php elseif($field->type === 'radio') : ?>
                      <?= html::select('', $fieldOptions, '', 'class="form-control picker-select field-type"'); ?>
                    <?php else: ?>
                      <?= html::select('', $fieldOptions, '', 'class="form-control picker-select field-type" multiple'); ?>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="prompt-design-area" style="height: 50%; position: relative; padding-top: 0;">
        <div class="area-title">
          <strong style="font-size: 14px;"><?php echo $lang->ai->miniPrograms->field->prompterDesign; ?></strong>
        </div>
        <div
          class="form-control"
          id="autocomplete-textarea"
          contenteditable="true"></div>
      </div>
    </div>
    <div style="flex-basis: 45%; height: 100%;">
      <div class="prompt-preview-area" style="height: 50%; position: relative;">
        <div class="area-title" style="display: flex; justify-content: space-between;">
          <strong style="font-size: 14px;"><?php echo $lang->ai->miniPrograms->field->prompterPreview; ?></strong>
          <button id="generate-result" class="btn btn-link" type="button" style="color: #2E7FFF; position: absolute; right: 16px; top: 10px;">
            <i class="icon-publish text-primary"></i>
            <?php echo $lang->ai->miniPrograms->field->generateResult; ?>
          </button>
        </div>
        <div class="preview-container"></div>
      </div>
      <div class="prompt-result-area" style="height: 50%; position: relative; padding-top: 0;">
        <div class="area-title">
          <strong style="font-size: 14px;"><?php echo $lang->ai->miniPrograms->field->resultPreview; ?></strong>
        </div>
        <div class="preview-container" style="top: 32px;"></div>
      </div>
    </div>
  </div>
  <input type="text" class="hidden" name="prompt">
  <input type="text" class="hidden" name="toPublish">
  <div class="button-container" style="display: flex; justify-content: center; gap: 24px; padding-top: 16px;">
    <button type="submit" class="btn btn-wide btn-secondary" onclick="saveMiniProgram()"><?= $lang->save; ?></button>
    <?php if(common::hasPriv('ai', 'publishMiniProgram')): ?>
      <button type="button" class="btn btn-wide btn-primary" onclick="openPublishDialog()"><?= $lang->ai->prompts->action->publish; ?></button>
    <?php endif; ?>
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
          <button type="submit" class="btn btn-primary" onclick="publishMiniProgram()" data-dismiss="modal"><?= $lang->confirm; ?></button>
          <button type="button" class="btn" data-dismiss="modal"><?= $lang->cancel; ?></button>
        </div>
      </div>
    </div>
  </div>
</form>
<?php include '../../common/view/footer.html.php'; ?>
