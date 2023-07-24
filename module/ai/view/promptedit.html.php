<?php
/**
 * The ai prompt edit view file of ai module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('requiredFields', $config->ai->createprompt->requiredFields);?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
  <div class='main-header'>
      <h2><?php echo $this->lang->ai->prompts->edit . " $lang->colon " . $prompt->name;?></h2>
    </div>
    <form method="post" class="main-form form-ajax" target="hiddenwin">
      <table class="table table-form">
        <tbody>
          <tr>
            <th class="w-80px"><?php echo $this->lang->ai->prompts->name;?></th>
            <td><?php echo html::input('name', $prompt->name, "class='form-control'");?></td>
          </tr>
          <tr>
            <th class="w-80px"><?php echo $this->lang->ai->prompts->description;?></th>
            <td><?php echo html::textarea('desc', $prompt->desc, "rows='6' class='form-control'");?></td>
          </tr>
          <tr>
            <td colspan="2" class="text-center form-actions">
              <?php echo html::submitButton($lang->save, '', 'btn btn-wide btn-primary');?>
            </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<script>
$(function() {
    $('input[name="name"]').on('input', function()
    {
        if($(this).val().length > 0)
        {
            $('button[type="submit"]').removeClass('disabled').removeAttr('disabled');
        }
        else
        {
            $('button[type="submit"]').addClass('disabled').attr('disabled', 'disabled');
        }
    });
});
</script>
<?php include '../../common/view/footer.html.php';?>
