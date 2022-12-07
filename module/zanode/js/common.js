$(function()
{
    if(config.currentMethod == 'create') $('#parent').change();

    $('#parent').change(function()
    {
        if(config.currentMethod == 'create'){
            var hostID = $('#parent').val();
            var link   = createLink('zanode', 'ajaxGetImages', 'hostID=' + hostID);
            $.get(link, function(data)
            {
                $('#template').html('').append(data);
                $('#image').chosen().trigger("chosen:updated");
                $('#image').change();
            });
        }
    });

    $(document).on("change", '#image', function()
    {
        var image = $('#image').val();
        var link  = createLink('zanode', 'ajaxGetImage', 'image=' + image);
        $.get(link, function(data)
        {
            data = JSON.parse(data);
            console.log(data);
            $('#osName').val(data.osName);
            if(data.memory != 0){
                $('#memory').val(data.memory);
            }
            if(data.memory != 0){
                $('#diskSize').val(data.disk);
            }
        });
    });

    if(typeof image == 'undefined' || !image) $('#os').change();
})

function showModal(url = '')
{
    if(url) $("#modalLink").attr('href', url);
    $('#successModal').modal('show', 'center');
}
