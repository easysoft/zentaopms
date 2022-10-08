$(function()
{
    /* Set default tab. */
    if($.cookie('recordEstimateType') == 'all')
    {
        $('#recordForm').addClass('hidden');
        $('.my-effort, #legendMyEffort').removeClass('active');
        $('.all-effort, #legendAllEffort').addClass('active');
    }
    else
    {
        $('.my-effort, #legendMyEffort').addClass('active');
        $('#recordForm').removeClass('hidden');
    }
    $.cookie('recordEstimateType', null);

    $('.order-btn').on('click', function()
    {
        $.cookie('recordEstimateType', 'all');
    });

    /* Hide creation logs when displaying team logs. */
    $('#linearefforts .tabs ul > li').click(function()
    {
        var tab = $(this).find('a').attr('href');
        $('#recordForm').toggleClass('hidden', tab == '#legendAllEffort');
    });

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

    $('#recordForm .date-group .input-group-addon').on('click', function()
    {
        $(this).prev().datetimepicker('show');
    });
})
