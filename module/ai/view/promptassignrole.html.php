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
</style>
<?php include 'promptdesignprogressbar.html.php';?>
<div id='mainContent' class='main-content' style='height: calc(100vh - 120px);'>
  <form id="mainForm" class='load-indicator main-form form-ajax' method='post' style='height: 100%;'>
    <div class='center-wrapper'>
      <div class='center-content'>
        <div>
          <h4><?php echo $lang->ai->prompts->assignModel;?></h4>
          <div class='content-row'>
            <div class='input-label'><span><?php echo $lang->ai->prompts->model;?></span></div>
            <div class='input mw-400px'>
              <?php echo html::select('model', $lang->ai->models->typeList, current(array_keys($lang->ai->models->typeList)), "class='form-control chosen' required disabled");?>
              <?php echo html::hidden('model', current(array_keys($lang->ai->models->typeList))); // TODO: use actual model list and value. ?>
            </div>
          </div>
        </div>
        <div>
          <h4><?php echo $lang->ai->prompts->assignRole;?></h4>
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
    </div>
  </form>
</div>
<script>
$(function()
{
    $('select[name="model"]').change(function()
    {
        var model = $(this).val();
        if(model)  $('button[type="submit"]').removeAttr('disabled');
        if(!model) $('button[type="submit"]').attr('disabled', 'disabled');
    });
    $('select[name="model"]').trigger('change');
});
</script>
<?php include '../../common/view/footer.html.php';?>
