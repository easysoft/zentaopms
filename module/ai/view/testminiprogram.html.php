<?php include '../../common/view/header.html.php'; ?>

<?php
js::set('currentField', $currentFields);
js::set('currentPrompt', $currentPrompt);
js::set('appID', $appID);
?>
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.textcomplete/1.8.5/jquery.textcomplete.min.js"></script>
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
      <button type="submit" class="btn btn-wide btn-primary" onclick="publishMiniProgram()"><?= $lang->ai->prompts->action->publish; ?></button>
    <?php endif; ?>
  </div>
</form>
<?php include '../../common/view/footer.html.php'; ?>
