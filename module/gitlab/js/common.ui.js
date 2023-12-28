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
        $('#files').trigger('click');
    });
    $("#files").on('change',function(){
        var files = this.files;
        if(!files.length) return;

        $(".avatar img").attr("src", window.URL.createObjectURL(files[0]));
        $(".avatar").removeClass('hidden');
    });
}

function onAclChange(event)
{
    const visibility = $(event.target).val();

    if(visibility == 'public') $("#visibilitypublic").parent().append(publicTip);
    if(visibility != 'public') $('#publicTip').remove();
}

/**
 * Alert error and jump page.
 *
 * @param  string $error
 * @param  string $errorJump
 * @access public
 * @return viod
 */
function alertJump(error, errorJump)
{
    zui.Modal.alert(error).then((res) => {loadPage(errorJump)});
}
