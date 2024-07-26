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

setTimeout(() => {
    changeStatus({target: {value: oldStatus}});
}, 100);
