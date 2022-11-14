$(function()
{
    if(config.currentMethod == 'create') $('#hostID').change();

    $('#hostID').change(function()
    {
        var hostID = $('#hostID').val();
        var link   = createLink('executionnode', 'ajaxGetImages', 'hostID=' + hostID);
        $.get(link, function(data)
        {
            $('#template').html('').append(data);
            $('#imageID').chosen().trigger("chosen:updated");
            $('#imageID').change();
        });
    });

    $(document).on("change", '#imageID', function()
    {
        var imageID = $('#imageID').val();
        var link       = createLink('executionnode', 'ajaxGetImage', 'imageID=' + imageID);
        $.get(link, function(data)
        {
            data = JSON.parse(data);
            console.log(data);
            $('#os').val(data.os);
        });
    });

    if(typeof imageID == 'undefined' || !imageID) $('#os').change();
})
