$(function()
{
    $('.extfiles').css('height', window.innerHeight - (result == 'success' ? 300 : 180));
});

/**
 * Click all checkbox.
 *
 * @param  event  $event
 * @access public
 * @return void
 */
function checkAllClick(event)
{
    var checked = $(event.target).prop('checked');
    $("[name^='files']").prop('checked', checked);
}

/**
 * Click file checkbox.
 *
 * @param  event  $event
 * @access public
 * @return void
 */
function checkFileClick(event)
{
    if($(event.target).prop('checked'))
    {
        $('#checkAll').prop('checked', $("[name^='files']:checked").length == $("[name^='files']").length);
    }
    else
    {
        $('#checkAll').prop('checked', false);
    }
}

window.submit = function()
{
    const formUrl  = $('#moveExtFileForm').attr('action');
    const formData = new FormData($("#moveExtFileForm")[0]);
    postAndLoadPage(formUrl, formData);
}
