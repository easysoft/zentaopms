$(function()
{
    if($('#typeHover').length) new zui.Tooltip('#typeHover', {title: typeTip, trigger: 'hover', placement: 'right', type: 'white', 'className': 'text-gray border border-light'});

    if(isWaterfall) hidePlanBox(executionAttr);
});

/**
 * Change project interaction.
 *
 * @access public
 * @return void
 */
function changeProject(e)
{
    let projectID = $(e.target).val();
    if($('#syncStories').length == 0) $('button[type=submit]').after("<input type='hidden' id='syncStories' name='syncStories' value='no' />");

    zui.Modal.confirm(confirmSync).then((res) =>
    {
        if(res)
        {
            $("#syncStories").val('yes');
            lastProjectID = projectID;
        }
        else
        {
            $("#syncStories").val('no');
            $('#project').val(lastProjectID);
        }
    });
};
