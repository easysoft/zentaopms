function updateAction(date)
{
    if(date.indexOf('-') != -1)
    {
        var dateArray = date.split('-');
        date = '';
        for(i = 0; i < dateArray.length; i++)
        {
            date = date + dateArray[i];
        }
    }
    location.href = createLink('todo', 'batchCreate', 'date=' + date);
}

$(function()
{
    setBeginsAndEnds();
    $('.main-header #date').change(function()
    {
        $('#todoBatchAddForm #date').val($(this).val());
    });
    $('.main-header #switchDate').change(function()
    {
        var value = $(this).prop('checked') ? 'on' : '';
        $('#todoBatchAddForm #switchDate').val(value);
    });
    $('.main-header #date').change();
    parent.$('#triggerModal .modal-content .modal-header .close').hide();
    
    $("#select-all").on('click', function()
    {
        var isChecked = $("#select-all").hasClass('checked');
        $("select[name^=begins]").attr("disabled", isChecked ? false : true).trigger('chosen:updated');
        $("select[name^=ends]").attr("disabled", isChecked ? false : true).trigger('chosen:updated');
    });
});
