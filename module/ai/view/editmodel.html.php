<?php
/**
 * The ai editModels view file of ai module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wenrui LI <liwenrui@easycorp.ltd>
 * @package     ai
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <form id='mainForm' class='load-indicator main-form form-ajax' method='post'>
    <table class='table table-form mw-800px'>
      <tr>
        <th><?php echo $lang->ai->models->type;?></th>
        <td><div class="required required-wrapper"></div><?php echo html::select('type', $lang->ai->models->typeList, $modelConfig->type, "class='form-control chosen'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->ai->models->apiKey;?></th>
        <td><div class="required required-wrapper"></div><?php echo html::input('key', $modelConfig->key, "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->ai->models->proxyType;?></th>
        <td>
          <div class='row'>
            <div class='col-md-4'>
              <div class="required required-wrapper"></div>
              <?php echo html::select('proxyType', $lang->ai->models->proxyTypes, $modelConfig->proxyType, "class='form-control chosen' data-disable_search='true'");?>
            </div>
            <div class='col-md-8' id='proxyAddrContainer' <?php if(empty($modelConfig->proxyType)) echo 'style="display: none;"'; ?>>
              <div class='row'>
                <div class='col-md-3 text-right' style='padding-top: 6px;'><strong><?php echo $lang->ai->models->proxyAddr;?></strong></div>
                <div class='col-md-9'><div class="required required-wrapper"></div><?php echo html::input('proxyAddr', $modelConfig->proxyAddr, "class='form-control'");?></div>
              </div>
            </div>
          </div>
        </td>
      </tr>
      <tr>
        <th><?php echo $lang->ai->models->description;?></th>
        <td><?php echo html::textarea('description', $modelConfig->description, "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->statusAB;?></th>
        <td><?php echo html::radio('status', $lang->ai->models->statusList, empty($modelConfig->status) ? 'on' : $modelConfig->status);?></td>
      </tr>
      <tr>
        <td colspan='2' class='text-center'>
          <?php echo html::submitButton();?>
          <?php echo html::commonButton($lang->ai->models->testConnection, 'id="testConn"', 'btn btn-secondary btn-wide');?>
        </td>
      </tr>
    </table>
  </form>
</div>
<script>
$(function() {
    $('select[name="proxyType"]').change(function()
    {
        var proxyType = $(this).val();
        $('#proxyAddrContainer').toggle(proxyType != '');
    });
    $('#mainForm').on('submit', function()
    {
        $('#testConn').attr('disabled', 'disabled');
    });
    $('#mainForm').on('ajaxComplete', function()
    {
        var timesTried = 0;
        var buttonStateSyncInterval = setInterval(function()
        {
            if(!$('#submit').attr('disabled'))
            {
                $('#testConn').removeAttr('disabled');
                clearInterval(buttonStateSyncInterval);
            }
            if(timesTried++ > 20) clearInterval(buttonStateSyncInterval);
        }, 100);
    });
    $('#testConn').click(function()
    {
        $.disableForm('#mainForm');
        $.ajax(
        {
            type: 'POST',
            url: createLink('ai', 'testConnection'),
            data: $('.main-form').serialize(),
            dataType: 'json',
            success: function(data)
            {
                if(data.result == 'success')
                {
                    $.zui.messager.success(data.message);
                }
                else
                {
                    $.zui.messager.danger(data.message);
                }
            },
            complete: function() {$.enableForm('#mainForm');}
        });
    });
});
</script>
<?php include '../../common/view/footer.html.php';?>
