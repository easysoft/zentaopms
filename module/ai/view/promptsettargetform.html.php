<?php
/**
 * The ai prompt target form select view file of ai module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>

<style>
  .center-wrapper {display: flex; justify-content: center; height: 100%;}
  .center-content {width: 100%; height: 100%; display: flex; flex-direction: column;}
  #select-form {display: flex; flex-direction: row; max-height: calc(100% - 32px);}
  #select-form > div {flex-grow: 1; flex-basis: 0; padding: 0px 12px;}
  .content-row {display: flex; flex-direction: row; padding: 8px 0px;}
  .input-label {width: 120px; padding: 6px 12px; text-align: right;}
  .input {flex-grow: 1;}
  #prompt-preview-wrapper {margin: 16px 0; height: calc(100% - 48px);}
  #prompt-preview {padding: 8px; border: 1px solid #ccc; border-radius: 4px; background-color: #f7f8f9; min-height: 100px; height: 100%; overflow-y: auto; cursor: default; user-select: none;}
  #prompt-preview .active {background-color: #d6e5fe;}
  #prompt-preview .prompt-data, #prompt-preview .prompt-role, #prompt-preview .prompt-text {border-bottom: 1px solid #ccc; padding: 16px 0;}
  #prompt-preview .prompt-data {padding-top: 0;}
  #prompt-preview .prompt-text {border-bottom: unset; padding-bottom: 0;}
  #prompt-preview .block-header {padding-bottom: 8px;}
  #prompt-preview .block-content > div + div {margin-top: 4px;}
  #prompt-preview .prompt-text-part + .prompt-text-part {margin-top: 4px;}
  #prompt-previewer {font-weight: bold;}
  #form-selector .header > * {display: inline-block;}
  #form-selector .content {margin: 6px 0; max-height: calc(100% - 47px); overflow-y: auto; border: 1px solid #ccc; border-left: none;}
  .target-form-group {display: grid; grid-template-columns: 120px 1fr; grid-gap: 8px; border: 1px solid #ccc;}
  .target-form-group:first-of-type {border-top: none;}
  .target-form-group:last-of-type {border-bottom: none;}
  .target-form-group + .target-form-group {border-top: unset;}
  .target-form-group .header {display: flex; align-items: center; padding: 0 12px; background-color: #f8f8f8;}
  .target-form-group .options {display: grid; padding: 12px 16px; grid-template-columns: repeat(4, 1fr);}
  .target-form-group .option {padding: 4px 0;}
  .target-form-group .option label {cursor: pointer;}
  .target-form-group .option input {cursor: pointer;}
  #go-test-btn {margin-left: 16px;}

  @media (max-width: 1366px)
  {
    .target-form-group .options {grid-template-columns: repeat(3, 1fr);}
  }
</style>

<?php include 'promptdesignprogressbar.html.php';?>
<div id='mainContent' class='main-content' style='height: calc(100vh - 120px);'>
  <form id="mainForm" onsubmit="return validateForm();" class='load-indicator main-form form-ajax' method='post' style='height: 100%;'>
    <div class='center-wrapper'>
      <div class='center-content'>
        <div id='select-form'>
          <div id='form-selector'>
            <div class='header'>
              <h4><?php echo $lang->ai->prompts->selectTargetForm;?></h4>
              <small class='text-gray'><?php echo $lang->ai->prompts->selectTargetFormTip;?></small>
            </div>
            <div class='content'>
              <?php foreach ($config->ai->targetForm as $name => $forms):?>
                <div class='target-form-group'>
                  <div class='header text-gray'>
                    <div><?php echo $lang->ai->targetForm[$name]['common'];?></div>
                  </div>
                  <div class='options'>
                    <?php foreach(array_keys($forms) as $form):?>
                      <div class='option'>
                        <input type='radio' name='targetForm' value='<?php echo "$name.$form";?>' <?php echo "$name.$form" == $prompt->targetForm ? 'checked' : '';?>>
                        <label><?php echo $lang->ai->targetForm[$name][$form];?></label>
                      </div>
                    <?php endforeach;?>
                  </div>
                </div>
              <?php endforeach;?>
            </div>
          </div>
          <div>
            <h4><?php echo $lang->ai->prompts->inputPreview;?></h4>
            <div id='prompt-preview-wrapper'>
              <div id='prompt-preview'>
                <div class='prompt-data'>
                  <div class='block-header text-gray'><?php echo $lang->ai->prompts->dataPreview;?></div>
                  <div class='block-content code' style='white-space: pre-wrap; word-break: break-word;'><?php echo $dataPreview;?></div>
                </div>
                <div class='prompt-role'>
                  <div class='block-header text-gray'><?php echo $lang->ai->prompts->rolePreview;?></div>
                  <div class='block-content'>
                    <div><?php echo $prompt->role;?></div>
                    <div><?php echo $prompt->characterization;?></div>
                  </div>
                </div>
                <div class='prompt-text'>
                  <div class='block-header text-gray'><?php echo $lang->ai->prompts->promptPreview;?></div>
                  <div class='block-content'>
                    <div><?php echo $prompt->purpose;?></div>
                    <div><?php echo $prompt->elaboration;?></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div style='display: flex; flex-grow: 1; flex-direction: column-reverse;'>
          <div style='display: flex; justify-content: center;'>
            <?php echo html::submitButton($lang->ai->nextStep, 'disabled name="jumpToNext" value="1"');?>
            <button type='submit' name='goTesting' value='1' id='go-test-btn' disabled class='btn btn-wide btn-secondary'><?php echo $lang->ai->goTesting;?></button>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

<script>
function validateForm()
{
  let pass = false;
  const radios = document.getElementsByName('targetForm');
  for(let radio of radios)
  {
    if(radio.checked)
    {
      pass = true;
      break;
    }
  }
  if(!pass)
  {
    $.zui.messager.danger('<?php echo sprintf($lang->ai->validate->noEmpty, $lang->ai->prompts->selectTargetForm);?>');
  }
  return pass;
}
$(function()
{
  $('.target-form-group .option label').click(function()
  {
    $(this).parent().find('input').click();
  });

  $('.target-form-group .option input').change(function()
  {
    var $selected = $('.target-form-group').find('.option input:checked');
    if($selected.length > 0)
    {
      $('#submit').removeAttr('disabled');
      $('#go-test-btn').removeAttr('disabled');
    }
  });
  $('.target-form-group .option input:checked').trigger('change');

  /* Enable buttons after going testing. */
  $('#mainForm').ajaxForm({
    success: function(response, status)
    {
      var message  = response.msg;
      var location = response.locate;
      var target   = response.target ? response.target : '#submit';
      if(message)
      {
        var $targetSubmitBtn = $(target);
        if($targetSubmitBtn.length)
        {
          $targetSubmitBtn.popover({
            container: 'body',
            trigger:   'manual',
            content:   message,
            tipClass:  'popover-in-modal popover-success popover-form-result',
            placement: 'right',
          }).popover('show');
          setTimeout(function() {$targetSubmitBtn.popover('destroy');}, 2000);
        }
      }
    },
    finish: function(response, _, $form)
    {
      if(response.locate) setTimeout(function()
      {
        var $a = $('<a href="' + response.locate + '" target="_self"></a>');
        $a.appendTo('body');
        if($a.length) $a[0].click();
        $a.remove();
      }, 1200);
      $form.enableForm();
    }
  });
});
</script>

<?php include '../../common/view/footer.html.php';?>
