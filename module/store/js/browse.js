$(function()
{
    $('#appSearchForm #submit').attr('id', '');

    $("input[name='categories[]']").on('change', function(){$('#appSearchForm').submit();});
});
