<?php
/**
 * The ai prompt set purpose view file of ai module of ZenTaoPMS.
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
  #purpose-setter {display: flex; flex-direction: row; max-height: calc(100% - 32px);}
  #purpose-setter > div {flex-grow: 1; flex-basis: 0; padding: 0px 12px;}
  .content-wrapper {padding: 0 12px; overflow-x: hidden; overflow-y: auto; scrollbar-gutter: stable;}
  .content-row {display: flex; flex-direction: row; padding: 8px 0px;}
  .input-label {width: 120px; padding: 6px 12px; text-align: right;}
  .input {flex-grow: 1;}
  #prompt-preview-wrapper {margin: 16px 0; height: calc(100% - 48px);}
  #prompt-preview {padding: 8px; border: 1px solid #E6EAF1; border-radius: 4px; background-color: #f8f8f8; min-height: 100px; height: 100%; overflow-y: auto;}
  #prompt-preview {cursor: default; user-select: none;}
  #prompt-preview .active {background-color: #d6e5fe;}
  #prompt-preview .prompt-data, #prompt-preview .prompt-role, #prompt-preview .prompt-text {border-bottom: 1px solid #E6EAF1; padding: 16px 0;}
  #prompt-preview .prompt-data {padding-top: 0;}
  #prompt-preview .prompt-text {border-bottom: unset; padding-bottom: 0;}
  #prompt-preview .block-header {padding-bottom: 8px;}
  #prompt-preview .block-content > div + div {margin-top: 4px;}
  #prompt-preview .prompt-text-part + .prompt-text-part {margin-top: 4px;}
  #prompt-previewer {font-weight: bold;}
</style>

<script>
class PromptPreviewer extends HTMLDivElement
{
  connectedCallback()
  {
    if(this.isConnected)
    {
      ['purpose', 'elaboration'].forEach(id =>
      {
        ['input', 'focus', 'blur'].forEach(event => document.getElementById(id).addEventListener(event, this.updatePromptView));
      });
    }
    this.render();
  }

  render()
  {
    this.innerHTML = '';

    /* Create textarea input preview. */
    ['purpose', 'elaboration'].forEach(id =>
    {
      const contentView = document.createElement('div');
      contentView.id = `${id}-preview`;
      contentView.classList.add('prompt-text-part');
      contentView.innerHTML = document.getElementById(id).value;
      if(id === document.activeElement.id) contentView.classList.add('active');
      this.appendChild(contentView);
    });
  }

  updatePromptView()
  {
    /* Sync textarea input preview. */
    ['purpose', 'elaboration'].forEach(id =>
    {
      const contentView = document.getElementById(`${id}-preview`);
      contentView.innerHTML = document.getElementById(id).value;
      if(id === document.activeElement.id)
      {
        contentView.classList.add('active');
      }
      else
      {
        contentView.classList.remove('active');
      }
    });
  }
}
customElements.define('prompt-previewer', PromptPreviewer, {extends: 'div'});
</script>

<?php include 'promptdesignprogressbar.html.php';?>
<div id='mainContent' class='main-content' style='height: calc(100vh - 120px);'>
  <form id="mainForm" onsubmit="return validateForm();" class='load-indicator main-form form-ajax' method='post' style='height: 100%;'>
    <div class='center-wrapper'>
      <div class='center-content'>
        <div id='purpose-setter'>
          <div id='purpose-input'>
            <h4><?php echo $lang->ai->prompts->purpose;?></h4>
            <div class='content-wrapper'>
              <div class='content-row'>
                <div class='input-label'><span><?php echo $lang->ai->prompts->purpose;?></span></div>
                <div class='input'><?php echo html::textarea('purpose', $prompt->purpose, "class='form-control' rows='6' placeholder='{$lang->ai->prompts->purposeTip}' required");?></div>
              </div>
              <div class='content-row'>
                <div class='input-label'><span><?php echo $lang->ai->prompts->elaboration;?></span></div>
                <div class='input'><?php echo html::textarea('elaboration', $prompt->elaboration, "class='form-control' rows='6' placeholder='{$lang->ai->prompts->elaborationTip}'");?></div>
              </div>
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
                  <div id='prompt-previewer' is='prompt-previewer'></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div style='display: flex; flex-grow: 1; flex-direction: column-reverse;'>
          <div style='display: flex; justify-content: center;'><?php echo html::submitButton($lang->ai->nextStep, 'disabled name="jumpToNext" value="1"');?></div>
        </div>
      </div>
    </div>
  </form>
</div>

<script>
function validateForm()
{
  let pass = true;
  const purpose = document.getElementById('purpose')?.value;
  if(!purpose)
  {
    pass = false;
    $.zui.messager.danger('<?php echo sprintf($lang->ai->validate->noEmpty, $lang->ai->prompts->purpose);?>');
  }
  return pass;
}
$(function()
{
  $('#purpose').on('input', function()
  {
    const val = $(this).val();
    if(val.length > 0)
    {
      $('#submit').removeAttr('disabled');
    }
    else
    {
      $('#submit').attr('disabled', 'disabled');
    }
  });
  $('#purpose').trigger('input');
});
</script>
<?php include '../../common/view/footer.html.php';?>
