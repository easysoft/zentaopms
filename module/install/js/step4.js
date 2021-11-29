/**
 * Show guide video.
 *
 * @access public
 * @return void
 */
function showVideo()
{
    $('video').removeClass('hidden');
}

$(function()
{
    $('#modeclassic').click(function()
    {
        $('#selectedModeTips').show();
    });

    $('#modenew').click(function()
    {
        $('#selectedModeTips').hide();
    });
})
