$(function()
{
    $('#program').change(function()
    {
        var programID = $(this).val();
        if(programID != oldProgramID && (!canChangePGM || singleLinkProjects.length !== 0 || multipleLinkProjects.length !== 0))
        {
            $('#changeProgram').modal({show: true});
            if(!canChangePGM)
            {
                $('#program').val(oldProgramID);
                $('#program').trigger("chosen:updated");
            }
        }
    })
});

/**
 * Set projects of change program.
 *
 * @access public
 * @return void
 */
function setChangeProjects()
{
    var projects = ',';
    $("input[name^='projects']:checked").each(function()
    {
        projects += $(this).val() + ',';
        $('#changeProjects').val(projects);
    });

    $('#changeProgram').modal('hide');
}
