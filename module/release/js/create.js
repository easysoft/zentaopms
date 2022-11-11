$('#submit').click(function()
{
    var dateFormat    = new RegExp(/^\d{4}\-\d{2}\-\d{2}$/);
    var name          = $('#name').val();
    var date          = $('#date').val();
    var build         = $('#build').val();
    var notEmptyBuild = false;

    $.each(build, function(index, value)
    {
        if(typeof(notEmptyBuilds[value]) != 'undefined')
        {
            notEmptyBuild = true;
            return false;
        }
    })

    if(name && build && notEmptyBuild && dateFormat.test(date))
    {
        var result = confirm(confirmLink) ? true : false;
        $('#sync').val(result);
    }
});

$('[data-toggle="popover"]').popover();
