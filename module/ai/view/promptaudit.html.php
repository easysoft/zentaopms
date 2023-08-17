<?php
/**
 * The design step bar view file of AI module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jianyu Chen <chenjianyu@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
?>

<?php include '../../common/view/header.lite.html.php'; ?>
<div id='mainContent' class='main-content'>
  <form method="post" class="main-form form-ajax">
    <div class='center-block'>
      <div class="main-header">
        <p>
          <?php echo $this->lang->ai->audit->designPrompt; ?>
          <strong><?php echo $prompt->name ?></strong>
          <span class='label label-id'> <?php echo $prompt->id; ?></span>
        </p>
      </div>
      <div class="bg-gray-3" style="display: flex;">
        <div style="flex-basis: 50%; flex-grow: 1;padding: 5px 20px 5px 10px;  border-right: #E6EAF1 1px solid">
          <h4><?php echo $this->lang->ai->prompts->assignRole; ?></h4>
          <div class='content-row'>
            <div class='input-label'><span><?php echo $this->lang->ai->prompts->role; ?></span></div>
            <div
              class='input'><?php echo html::input('role', $prompt->role, "class='form-control' placeholder='{$this->lang->ai->prompts->rolePlaceholder}'"); ?></div>
          </div>
          <div class='content-row'>
            <div class='input-label'><span><?php echo $this->lang->ai->prompts->characterization; ?></span></div>
            <div
              class='input'><?php echo html::textarea('characterization', $prompt->characterization, "class='form-control' rows='4' placeholder='{$this->lang->ai->prompts->charPlaceholder}'"); ?></div>
          </div>
          <h4><?php echo $this->lang->ai->prompts->selectDataSource; ?></h4>
          <div class='content-row'>
            <div class='input-label text-gray'><span><?php echo $this->lang->ai->prompts->object; ?></span></div>
            <div class='input'
                 style="text-align: left; padding: 6px 0;"><?php echo $this->lang->ai->dataSource[$prompt->module]['common']; ?></div>
          </div>
          <div class='content-row'>
            <div class='input-label text-gray'><span><?php echo $this->lang->ai->prompts->field; ?></span></div>
            <div class='input' style="padding: 6px 0;">
              <?php
              $sources = explode(',', $prompt->source);
              $sources = array_filter($sources);
              end($sources);
              $lastKey = key($sources);
              foreach($sources as $key => $source)
              {
                $isLastElem = ($key === $lastKey);
                list($object, $field) = explode('.', $source);
                echo $this->lang->ai->dataSource[$prompt->module][$object][$field] . ($isLastElem ? '' : $this->lang->separater);
              }
              ?>
            </div>
          </div>
          <h4><?php echo $this->lang->ai->prompts->setPurpose; ?></h4>
          <div class='content-row'>
            <div class='input-label'><span><?php echo $lang->ai->prompts->purpose; ?></span></div>
            <div
              class='input'><?php echo html::textarea('purpose', $prompt->purpose, "class='form-control' rows='6' placeholder='{$lang->ai->prompts->purposeTip}' required"); ?></div>
          </div>
          <div class='content-row'>
            <div class='input-label'><span><?php echo $lang->ai->prompts->elaboration; ?></span></div>
            <div
              class='input'><?php echo html::textarea('elaboration', $prompt->elaboration, "class='form-control' rows='6' placeholder='{$lang->ai->prompts->elaborationTip}'"); ?></div>
          </div>
          <h4><?php echo $this->lang->ai->prompts->setTargetForm; ?></h4>
          <div class='content-row'>
            <div class='input-label'><span><?php echo $this->lang->ai->prompts->selectTargetForm; ?></span></div>
            <div class='input' style="padding: 6px 0;">
              <?php
              $targetForm = explode('.', $prompt->targetForm);
              echo $this->lang->ai->targetForm[$targetForm[0]][$targetForm[1]];
              ?>
            </div>
          </div>
        </div>
        <div style="flex-basis: 50%; flex-grow: 1; display: flex; flex-direction: column; word-break: break-all;">
          <div style="padding: 5px 10px; border-bottom: #E6EAF1 1px solid">
            <h4
              style="margin-bottom: 24px;"><?php echo sprintf($this->lang->ai->models->promptFor, $this->lang->ai->models->typeList['openai-gpt35']); ?></h4>
            <p class="text-gray"><?php echo $this->lang->ai->prompts->assignRole; ?></p>
            <p id="roleDisplay"><?php echo $prompt->role; ?></p>
            <p id="characterizationDisplay"><?php echo $prompt->characterization; ?></p>
          </div>
          <div style="padding: 5px 10px; border-bottom: #E6EAF1 1px solid">
            <p class="text-gray"><?php echo $this->lang->ai->prompts->selectDataSource; ?></p>
            <p><?php echo htmlspecialchars($dataPrompt); ?></p>
          </div>
          <div style="padding: 5px 10px;">
            <p class="text-gray"><?php echo $this->lang->ai->prompts->setPurpose; ?></p>
            <p id="purposeDisplay"><?php echo $prompt->purpose; ?></p>
            <p id="elaborationDisplay"><?php echo $prompt->elaboration; ?></p>
          </div>
        </div>
      </div>
      <div style="margin-top: 15px; padding-left: 10px;">
        <p style="display: inline-block"><?php echo $this->lang->ai->audit->afterSave; ?></p>
        <?php echo html::radio('backLocation', $this->lang->ai->audit->backLocationList, 1); ?>
      </div>
      <div style="display: flex; justify-content: center; margin-top: 10px;">
        <?php echo html::submitButton($this->lang->save, '', 'btn btn-primary'); ?>
      </div>
    </div>
  </form>
</div>
<script>
  (function()
  {
    const submitButton = document.getElementById('submit');
    if(!submitButton) return;
    const purpose = document.getElementsByName('purpose')[0];
    if(!purpose) return;

    if(!purpose.value)
    {
      submitButton.disabled = true;
    }
    purpose.addEventListener('input', function()
    {
      submitButton.disabled = !purpose.value;
    });
  })();

  function reloadPrompt(promptId, objectId)
  {
    parent.$.zui.closeModal();

    $('body', window.parent.document).attr('data-loading', '<?php echo $lang->ai->execute->loading;?>');
    $('body', window.parent.document).addClass('load-indicator loading');

    let link = createLink('ai', 'promptexecute', 'promptId=' + promptId + '&objectId=' + objectId);
    link = link.replace('onlybody=yes', '');
    const aTag = document.createElement('a');
    aTag.href = link;
    aTag.style.display = 'none';
    parent.document.body.appendChild(aTag);
    aTag.click();
  }
</script>
<?php include '../../common/view/footer.lite.html.php'; ?>
