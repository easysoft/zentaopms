<?php
/**
 * The ai prompt role assign view file of ai module of ZenTaoPMS.
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
  .center-content {min-width: 800px; height: 100%; display: flex; flex-direction: column;}
  .content-row {display: flex; flex-direction: row; padding: 8px 0px;}
  .input-label {width: 120px; padding: 6px 12px; text-align: right;}
  .input {flex-grow: 1;}
  .v-top > * {vertical-align: top; display: inline-block;}
</style>
<?php include 'promptdesignprogressbar.html.php';?>
<div id='mainContent' class='main-content' style='height: calc(100vh - 120px); padding: 0;'>
  <form id="mainForm" onsubmit="return validateForm();" class='load-indicator main-form form-ajax' method='post' style='height: 100%;'>
    <div class='center-wrapper'>
      <div class='center-content' style="width: 100%; display: flex; flex-direction: row;">
        <div style="flex-grow: 1; padding: 20px;">
          <div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
              <h4><?php echo $lang->ai->prompts->assignRole;?></h4>
              <?php echo html::commonButton("<span>{$lang->ai->prompts->roleTemplate}</span> " . "<icon class='icon icon-first-page'></icon> ", 'id="expandRoleTemplatePanel"', 'btn btn-info');?>
            </div>
            <div class='content-row'>
              <div class='input-label'><span><?php echo $lang->ai->prompts->model;?></span></div>
              <div class='input mw-400px'>
                <?php echo html::select('model', $lang->ai->models->typeList, current(array_keys($lang->ai->models->typeList)), "class='form-control chosen' required");?>
              </div>
            </div>
            <div class='content-row'>
              <div class='input-label'><span><?php echo $lang->ai->prompts->role;?></span></div>
              <div class='input mw-400px'><?php echo html::input('role', $prompt->role, "class='form-control' placeholder='{$lang->ai->prompts->rolePlaceholder}'");?></div>
            </div>
            <div class='content-row'>
              <div class='input-label'><span><?php echo $lang->ai->prompts->characterization;?></span></div>
              <div class='input'><?php echo html::textarea('characterization', $prompt->characterization, "class='form-control' rows='4' placeholder='{$lang->ai->prompts->charPlaceholder}'");?></div>
            </div>
          </div>
          <div style='display: flex; flex-grow: 1; flex-direction: column-reverse;'>
            <div style='display: flex; justify-content: center;'><?php echo html::submitButton($lang->ai->nextStep, 'disabled name="jumpToNext" value="1"');?></div>
          </div>
        </div>
        <div id="roleTemplate" style="display: none; flex-basis: 370px; flex-grow: 0; padding: 20px 24px; border-left: 1px solid #E6EAF1; background-color: #FCFDFE; border-top-right-radius: 4px; border-bottom-right-radius: 4px;">
          <h4 class="v-top"">
            <?php echo "<span style='padding-right: 4px;'>{$lang->ai->prompts->roleTemplate}</span>" . " <i class='icon icon-help' data-toggle='tooltip' data-placement='top' title='{$lang->ai->prompts->roleTemplateTip}'></i>";?>
          </h4>
        </div>
      </div>
    </div>
  </form>
</div>
<script>
function validateForm()
{
  let pass = true;
  const model = document.getElementById('model')?.value;
  if(!model)
  {
    $.zui.messager.danger('<?php echo sprintf($lang->ai->validate->noEmpty, $lang->ai->prompts->model);?>');
    pass = false;
  }
  return pass;
}

$(function () {
  $('[data-toggle="tooltip"]').tooltip();

  $('select[name="model"]').change(function () {
    var model = $(this).val();
    if (model) $('button[type="submit"]').removeAttr('disabled');
    if (!model) $('button[type="submit"]').attr('disabled', 'disabled');
  });
  $('select[name="model"]').trigger('change');
});

(function()
{
  const expandRoleTemplatePanel = document.getElementById('expandRoleTemplatePanel');
  if(expandRoleTemplatePanel)
  {
    expandRoleTemplatePanel.addEventListener('click', function(e)
    {
      const roleTemplate = document.getElementById('roleTemplate');
      const isPanelExpanded = roleTemplate.style.display === 'block';
      expandRoleTemplatePanel.querySelector('icon').className = isPanelExpanded ? 'icon icon-first-page' : 'icon icon-last-page';
      if(roleTemplate)
      {
        roleTemplate.style.display = isPanelExpanded ? 'none' : 'block';
      }
    });
  }
})();

</script>
<?php include '../../common/view/footer.html.php';?>
