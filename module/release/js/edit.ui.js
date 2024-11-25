window.changeStatus = function(e)
{
    const status = e.target.value;
    if(status == 'wait')
    {
        $('#releasedDate').closest('.form-row').addClass('hidden');
        $('[data-name=date] .form-label').addClass('required');
    }
    else
    {
        $('#releasedDate').closest('.form-row').removeClass('hidden');
        $('[data-name=date] .form-label').removeClass('required');
    }
}


$(function()
{
    setTimeout(function()
    {
        changeStatus({target: {value: oldStatus}});
        window.loadSystemBlock();
    }, 100);
})
