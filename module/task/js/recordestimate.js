$(function()
{
    var parentLink = parent.location.href;
    if(parentLink.indexOf('view') > 0)
    {
        $.get(parentLink, function(data)
        {
            $data = $(data);
            parent.$('#actionbox ol.histories-list').html($data.find('#actionbox ol.histories-list').html());
            parent.$('.side-col').html($data.find('.side-col').html());

            if(parent.$('#actionbox ol.histories-list #lastComment').length > 0) $(initKindeditor);
        });
    }

    $('.form-date').datetimepicker('setEndDate', today);

    $("#recordForm #submit").click(function(e, confirmed)
    {
        if(confirmed) return true;

        var $this = $(this);
        $('#recordForm .left').each(function()
        {
            if($(this).val() !== '' && !$(this).prop('readonly')) left = $(this).val();
        });

        if(typeof(left) != 'undefined' && left == '0')
        {
            e.preventDefault();
            bootbox.confirm(confirmRecord, function(result)
            {
                if(!result) $('#submit').attr("disabled", false);
                if(result) $this.trigger('click', true);
            });
        }
    });

    $('#recordForm .showinonlybody').each(function()
    {
        $(this).click(function()
        {
            var hasRecord = false;
            $('#recordForm').find('input[name^="consumed"], input[name^="left"], textarea[name^="work"]').each(function()
            {
                if($(this).val() !== '')
                {
                    hasRecord = true;
                    return false;
                }
            });
            if(hasRecord)
            {
                alert(noticeSaveRecord);
                return false;
            }
        });
    });
})
