<?php include '../../common/view/header.html.php'; ?>

<?php js::set('currentField', $currentFields); ?>
<?php js::set('currentPrompt', $currentPrompt); ?>
<div style="width: 100%; height: 100%; display: flex; flex-direction: column; padding: 24px 32px 32px 32px; background: #fff;">
  <strong style="font-size: 16px; padding-bottom: 16px;"><?= $lang->ai->prompts->action->test; ?></strong>
  <div id="test-miniprogram">
    <div style="flex-basis: 55%; border-right: 1px solid #E6EAF1;">
      <div class="content-debug-area" style="min-height: 50%;">
        <div class="area-title">
          <strong style="font-size: 14px;"><?php echo $lang->ai->miniPrograms->field->contentDebugging; ?></strong>
        </div>
        <table class="table table-form">
          <tbody class="field-content">
            <?php foreach ($currentFields as $key => $field) : ?>
              <tr data-id="<?= "field-$key"; ?>">
                <th title="<?= $field->name; ?>">
                  <span class="field-name"><?= $field->name; ?></span>
                </th>
                <?php
                $fieldOptions = array();
                if (!empty($field->options)) {
                  $fieldOptions = explode(',', $field->options);
                  $fieldOptions = array_combine($fieldOptions, $fieldOptions);
                }; ?>
                <td data-type="<?= $field->type; ?>" <?= empty($field->required) ? '' : 'class="required"'; ?>>
                  <?php if ($field->type === 'text') : ?>
                    <input type="text" class="form-control field-type" placeholder="<?= $field->placeholder; ?>">
                  <?php elseif ($field->type === 'textarea') : ?>
                    <textarea class="form-control field-type" placeholder="<?= $field->placeholder; ?>"></textarea>
                  <?php elseif ($field->type === 'radio') : ?>
                    <?= html::select('', $fieldOptions, '', 'class="form-control picker-select field-type"'); ?>
                  <?php else : ?>
                    <?= html::select('', $fieldOptions, '', 'class="form-control picker-select field-type" multiple'); ?>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="prompt-design-area" style="height: 50%; position: relative; padding-top: 0;">
        <div class="area-title">
          <strong style="font-size: 14px;"><?php echo $lang->ai->miniPrograms->field->prompterDesign; ?></strong>
        </div>
        <div class="form-control" id="autocomplete-textarea" contenteditable="true" style="overflow-y: auto; position: absolute; top: 32px; left: 16px; right: 16px; bottom: 16px; height: auto; width: auto;"></div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.textcomplete/1.8.5/jquery.textcomplete.min.js"></script>
      </div>
    </div>
    <div style="flex-basis: 45%;">
      <div class="prompt-preview-area" style="height: 50%; position: relative;">
        <div class="area-title" style="display: flex; justify-content: space-between;">
          <strong style="font-size: 14px;"><?php echo $lang->ai->miniPrograms->field->prompterPreview; ?></strong>
          <button class="btn btn-link" style="display: flex; align-items: center; gap: 4px; color: #2E7FFF;">
            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
              <g clip-path="url(#clip0_669_15822)">
                <path d="M12.5418 1.45837L8.66266 12.5417L6.446 7.55421L1.4585 5.33754L12.5418 1.45837Z" stroke="#2E7FFF" stroke-linejoin="round" />
                <path d="M12.5416 1.45837L6.4458 7.55421" stroke="#2E7FFF" stroke-linecap="round" stroke-linejoin="round" />
              </g>
              <defs>
                <clipPath id="clip0_669_15822">
                  <rect width="14" height="14" fill="white" />
                </clipPath>
              </defs>
            </svg>
            <?php echo $lang->ai->miniPrograms->field->generateResult; ?>
          </button>
        </div>
        <div class="preview-container"></div>
      </div>
      <div class="prompt-result-area" style="height: 50%; position: relative; padding-top: 0;">
        <div class="area-title">
          <strong style="font-size: 14px;"><?php echo $lang->ai->miniPrograms->field->resultPreview; ?></strong>
        </div>
        <div class="preview-container"></div>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php'; ?>
