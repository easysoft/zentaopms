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
window.changeProject = function(e)
{
    let projectID = $('#form-execution-edit [name=project]').val();
    if($('#syncStories').length == 0) $('button[type=submit]').after("<input type='hidden' id='syncStories' name='syncStories' value='no' />");

    if(lastProjectID != 'undefined' && projectID == lastProjectID) return;
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
            $('#form-execution-edit [name=project]').zui('picker').$.setValue(lastProjectID);
        }
    });
};
