$(function()
{
    $("input[name^='module']").change(function()
    {
        const name = $(this).attr('name');
        if($(this).prop('checked'))
        {
            $("input[name='" + name + "'][type=hidden]").val('1').attr('disabled', 'disabled');
        }
        else
        {
            $("input[name='" + name + "'][type=hidden]").val('0').attr('disabled', false);
        }
    });
    $('input[name=allChecker]').change(function()
    {
        $(this).closest('tr').find("input[name^='module']").prop('checked', $(this).prop('checked')).change();
    });
    $('#allChecker').change(function()
    {
        $('input[name=allChecker]').prop('checked', $(this).prop('checked')).change();
    });
})
