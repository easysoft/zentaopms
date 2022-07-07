/**
 * set avatar event
 *
 * @access public
 * @return void
 */
function setAvatar()
{
    $('#avatarUploadBtn').on('click', function()
    {
        $('#files').click();
    });
    $("#files").change(function(){
        var files = this.files;
        if(!files.length) return;

        $(".avatar img").attr("src", window.URL.createObjectURL(files[0]));
        $(".avatar").removeClass('hidden');
    });
}

$(document).ready(function()
{
    $('#gitlabSearch').click(function()
    {
        triggerSearch();
    });
    $('#keyword').keypress(function(event)
    {
        if(event.which == 13) triggerSearch();
    })

});

function triggerSearch()
{
    $("#gitlabForm").submit();
}
