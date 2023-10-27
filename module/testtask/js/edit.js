$(function()
{
    adjustPriBoxWidth();
    if(config.onlybody) $('#ownerAndPriBox .picker-selection').css('width', '123px');
})

$('#mainContent').on('change', '#build', function()
{
    var buildID = $(this).val();
    link = createLink('testtask', 'ajaxGetExecutionByBuild', 'buildID=' + buildID);
    $.get(link, function(data)
    {
        $('#execution').val(data);
        $("#execution").trigger("chosen:updated");
    });
})
