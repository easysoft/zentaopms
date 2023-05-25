<?php if(!$this->post->parseContent):?>
<style>
.holder-value {display: none!important;}
.holder-element {border-radius: 4px; padding: 5px; position: relative; margin: 5px 2px;}
.holder-element:before {content: attr(data-holder)}
.holder-block {display: block; text-align: center}
.holder-span {display: inline-block; line-height: 1}

.holder-element.needParams {background: #f5515f; color: #fff; border: 1px solid #e62340; cursor: pointer;}
.holder-element.withoutParams {background: #eee; color: #3c4353; border: 1px solid #dcdcdc;}
</style>
<?php endif;?>

<div class="modal fade" id="paramFormModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only"><?php $lang->close;?></span></button>
        <h4 class="modal-title" id='holderModalTitle'><?php echo $lang->measurement->setParams?></h4>
      </div>
      <div id='paramFormBox' class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang->close;?></button>
        <button type="button" class="btn btn-primary" id='paramFormSaveBtn'><?php echo $lang->save;?></button>
      </div>
    </div>
  </div>
</div>

<script>
$(function()
{
    $('.holder-element').each(function()
    {
        var $that = $(this);
        var value = $that.find('.holder-value').text();
        value     = value.split('{');
        value     = value[1].split('}');
        value     = value[0];
        value     = window.btoa(value);

        $.get(createLink('measurement', 'ajaxCheckElementNeedParams', "value=" + value), function(data)
        {
            if(data == 'no')
            {
                $that.addClass('withoutParams');
            }
            else
            {
                $that.addClass('needParams');
            }
        })
    });

    $('.holder-element').click(function()
    {
        if($(this).hasClass('withoutParams')) return;

        value = $(this).find('.holder-value').text();
        value = value.split('{');
        value = value[1].split('}');
        value = value[0];
        value = window.btoa(value);

        $('#paramFormBox').load(createLink('measurement', 'ajaxBuildParamForm', "value=" + value), function()
        {
            $('#paramFormBox .form-control').each(function()
            {
                var controlID = $(this).data('control');
                var paramName = $(this).data('name');
                var ID        = paramName + '_' + controlID;

                var paramControl = $('#paramValues #' + ID);
                if(paramControl.length != 0) $(this).val(paramControl.val());
            });
            $('.report-date').datepicker();
            $('#paramFormBox .chosen').chosen();
            $('#paramFormModal').modal('show');
        });
    });

    $(document).on('click', '#paramFormSaveBtn', function()
    {
        $('#paramFormBox .form-control').each(function()
        {
            var controlID = $(this).data('control');
            var paramName = $(this).data('name');
            var ID        = paramName + '_' + controlID;

            var paramControl = $('#paramValues #' + ID);
            if(paramControl.length == 0)
            {
                $('#paramValues').append("<input type='hidden' id='" + ID + "' name='params[" + controlID + "][" + paramName + "]' value='" + $(this).val() + "'>");
            }
            else
            {
                paramControl.val($(this).val());
            }
            $('#paramFormModal').modal('hide');
        });
    });
});
</script>
