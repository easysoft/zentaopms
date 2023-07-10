<?php
/**
 * The ai prompt finalize view file of ai module of ZenTaoPMS.
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
  .center-content {width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center;}
  #info-form {display: flex; flex-direction: column; min-width: 800px;}
  .content-row {display: flex; flex-direction: row; padding: 8px 0px;}
  .input-label {width: 120px; padding: 6px 12px; text-align: right;}
  .input {flex-grow: 1;}
  .input-tip {padding: 6px 12px;}
</style>

<?php include 'promptdesignprogressbar.html.php';?>
<div id='mainContent' class='main-content' style='height: calc(100vh - 120px);'>
  <form class='load-indicator main-form form-ajax' method='post' style='height: 100%;'>
    <div class='center-wrapper'>
      <div class='center-content'>
        <div id='info-form'>
          <div class='content-row'>
            <div class='input-label'><span><?php echo $lang->prompt->name;?></span></div>
            <div class='input mw-600px'><?php echo html::input('name', $prompt->name, "class='form-control' required");?></div>
          </div>
          <div class='content-row'>
            <div class='input-label'><span><?php echo $lang->prompt->module;?></span></div>
            <div class='input mw-200px'><?php echo html::input('module', $lang->ai->prompts->modules[$prompt->module], "class='form-control' disabled");?></div>
            <div class='input-tip text-gray'><?php echo $lang->ai->moduleDisableTip;?></div>
          </div>
          <div class='content-row'>
            <div class='input-label'><span><?php echo $lang->prompt->desc;?></span></div>
            <div class='input mw-600px'><?php echo html::textarea('desc', $prompt->desc, "rows='6' class='form-control'");?></div>
          </div>
        </div>
        <div style='display: flex; flex-grow: 1; flex-direction: column-reverse;'>
          <?php echo html::hidden('jumpToNext', "1");?>
          <div style='display: flex; justify-content: center;'><?php echo html::submitButton($lang->ai->nextStep, 'disabled');?></div>
        </div>
      </div>
    </div>
  </form>
</div>

<script>
$(function() {
    $('input[name="name"]').on('input', function()
    {
        if($(this).val().length > 0)
        {
            $('button[type="submit"]').removeAttr('disabled');
        }
        else
        {
            $('button[type="submit"]').attr('disabled', 'disabled');
        }
    });
    $('input[name="name"]').trigger('input');
});
</script>

<?php include '../../common/view/footer.html.php';?>
