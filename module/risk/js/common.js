$(function()
{
    $('#rate').attr('readonly', true);
    $('#pri').attr('disabled', true);
    computeIndex();
    function computeIndex()
    {
        var impact      = $('#impact').val();
        var probability = $('#probability').val();
        var rate        = parseInt(impact * probability);
        var pri = ''; 
        if(0 < rate && rate <= 5)    pri = 'low';
        if(5 < rate && rate <= 12)   pri = 'middle';
        if(15 <= rate && rate <= 25) pri = 'high';

        $('#rate').val(rate);
        $('#pri').val(pri);
        $('#pri').trigger("chosen:updated")
        $('#pri').chosen();
        $('#pri').attr('disabled', true);
        $('input[name="pri"]').remove();
        $('#pri').after("<input type='hidden' name='pri' value='" + pri + "'/>");
    }

    $('#impact, #probability').change(function(){computeIndex()});
})
