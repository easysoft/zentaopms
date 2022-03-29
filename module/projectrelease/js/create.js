$('#submit').click(function()
{
    var dateFormat = new RegExp(/^\d{4}\-\d{2}\-\d{2}$/);
    var name       = $('#name').val();
    var date       = $('#date').val();
    var build      = $('#build').val();
    if(name && build && dateFormat.test(date))
    {
        var result = confirm(confirmLink) ? true : false;
        $('#sync').val(result);
    }
});
