<script>
$(function()
{
    $('#riskindex').attr('readonly', true);
    $('#pri').attr('disabled', true);
    computeIndex();
    function computeIndex()
    {   
        var impact      = $('#impact').val();
        var probability = $('#probability').val();
        var riskindex   = parseInt(impact * probability);
        var pri = '';
        if(0 < riskindex && riskindex <= 5)    pri = 'low';
        if(5 < riskindex && riskindex <= 12)   pri = 'middle';
        if(15 <= riskindex && riskindex <= 25) pri = 'high';

        $('#riskindex').val(riskindex);
        $('#pri').val(pri);
        $('#pri').trigger("chosen:updated")
            $('#pri').chosen();
        $('#pri').attr('disabled', true);
        $('input[name="pri"]').remove();
        $('#pri').after("<input type='hidden' name='pri' value='" + pri + "'/>");
    }   

    $('#impact, #chance').change(function(){computeIndex()});
})
</script>
