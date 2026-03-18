window.setBranchRule = function()
{
    const pickerDom = $(event.target).closest('.input-group').find('.picker-box');
    const select    = $(event.target).closest('.radio-primary').find('input').val();
    if(select == 1)
    {
        pickerDom.removeClass('hidden');
    }
    else
    {
        pickerDom.addClass('hidden');
        pickerDom.zui('picker').$.clear();
    }
}
