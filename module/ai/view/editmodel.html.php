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
<?php
$currentVendor = empty($modelConfig->vendor) ? key($lang->ai->models->vendorList->{empty($modelConfig->type) ? key($lang->ai->models->typeList) : $modelConfig->type}) : $modelConfig->vendor;
$requiredFields = $config->ai->vendorList[$currentVendor]['requiredFields'];
if(empty($requiredFields)) $requiredFields = array();
js::set('vendorList', $config->ai->vendorList);
js::set('vendorListLang', $lang->ai->models->vendorList);
js::set('vendorTipsLang', $lang->ai->models->vendorTips);
?>
<style>
  .required:after {right: -12px;}
</style>
<div id='mainContent' class='main-content'>
  <form id='mainForm' class='load-indicator main-form form-ajax' method='post'>
    <table class='table table-form'>
      <tr>
        <th><?php echo $lang->ai->models->type;?></th>
        <td><?php echo html::select('type', $lang->ai->models->typeList, $modelConfig->type, "class='form-control chosen' required");?></td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->ai->models->vendor;?></th>
        <td><?php echo html::select('vendor', $lang->ai->models->vendorList->{empty($modelConfig->type) ? key($lang->ai->models->typeList) : $modelConfig->type}, $currentVendor, "class='form-control chosen' required");?></td>
        <td id='vendor-tips' class='text-gray' style='display: none;'></td>
      </tr>
      <tr class="vendor-row <?php echo in_array('key', $requiredFields) ? '' : ' hidden'; ?>" data-vendor-field="key">
        <th><?php echo $lang->ai->models->key;?></th>
        <td><?php echo html::input('key', $modelConfig->key, "class='form-control' required");?></td>
        <td></td>
      </tr>
      <tr class="vendor-row <?php echo in_array('secret', $requiredFields) ? '' : ' hidden'; ?>" data-vendor-field="secret">
        <th><?php echo $lang->ai->models->secret;?></th>
        <td><?php echo html::input('secret', $modelConfig->secret, "class='form-control' required");?></td>
        <td></td>
      </tr>
      <tr class="vendor-row <?php echo in_array('resource', $requiredFields) ? '' : ' hidden'; ?>" data-vendor-field="resource">
        <th><?php echo $lang->ai->models->resource;?></th>
        <td><?php echo html::input('resource', empty($modelConfig->resource) ? '' : $modelConfig->resource, "class='form-control' required");?></td>
        <td></td>
      </tr>
      <tr class="vendor-row <?php echo in_array('deployment', $requiredFields) ? '' : ' hidden'; ?>" data-vendor-field="deployment">
        <th><?php echo $lang->ai->models->deployment;?></th>
        <td><?php echo html::input('deployment', empty($modelConfig->deployment) ? '' : $modelConfig->deployment, "class='form-control' required");?></td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->ai->models->proxyType;?></th>
        <td>
          <div class='row'>
            <div class='col-md-4'>
              <?php echo html::select('proxyType', $lang->ai->models->proxyTypes, $modelConfig->proxyType, "class='form-control chosen' data-disable_search='true' required");?>
            </div>
            <div class='col-md-8' id='proxyAddrContainer' <?php if(empty($modelConfig->proxyType)) echo 'style="display: none;"'; ?>>
              <div class='row'>
                <div class='col-md-3 text-right' style='padding-top: 6px;'><strong><?php echo $lang->ai->models->proxyAddr;?></strong></div>
                <div class='col-md-9'><div class="required required-wrapper"></div><?php echo html::input('proxyAddr', $modelConfig->proxyAddr, "class='form-control'");?></div>
              </div>
            </div>
          </div>
        </td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->ai->models->description;?></th>
        <td><?php echo html::textarea('description', $modelConfig->description, "class='form-control'");?></td>
        <td></td>
      </tr>
      <tr>
        <th><?php echo $lang->statusAB;?></th>
        <td><?php echo html::radio('status', $lang->ai->models->statusList, empty($modelConfig->status) ? 'on' : $modelConfig->status);?></td>
        <td></td>
      </tr>
      <tr>
        <td colspan='2' class='text-center'>
          <?php echo html::submitButton();?>
          <?php echo html::commonButton($lang->ai->models->testConnection, 'id="testConn"', 'btn btn-secondary btn-wide');?>
          <?php echo html::a(inlink('models', ""), $lang->goback, '', 'class="btn btn-wide"');?>
        </td>
        <td></td>
      </tr>
    </table>
  </form>
</div>
<script>
$(function() {
    $('select[name="type"]').change(function()
    {
        var type = $(this).val();
        var vendorList = vendorListLang[type];
        $('select[name="vendor"]').html('');
        for(var vendor in vendorList) $('select[name="vendor"]').append('<option value="' + vendor + '">' + vendorList[vendor] + '</option>');
        $('select[name="vendor"]').trigger('chosen:updated');
        $('select[name="vendor"]').trigger('change');
    });
    $('select[name="vendor"]').change(function()
    {
        var vendor = $(this).val();
        var requiredFields = vendorList[vendor]['requiredFields'];
        const vendorTip = vendorTipsLang[vendor];
        $('#vendor-tips').html(vendorTip ? vendorTip : '').toggle(!!vendorTip);
        $('.vendor-row').each(function()
        {
            var name = $(this).data('vendor-field');
            $(this).toggleClass('hidden', !requiredFields.includes(name));
        });
    });
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
        $('#testConn').attr('disabled', 'disabled');
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
            complete: function()
            {
              $.enableForm('#mainForm');
              $('#testConn').removeAttr('disabled');
            }
        });
    });
});
</script>
<?php include '../../common/view/footer.html.php';?>
