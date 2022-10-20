$(function()
{
    if(programID == 0) $("#line").closest('tr').addClass('hidden');
});

$('#program').change(function()
{
    var programID = $(this).val();
    programID > 0 ? $("#line").closest('tr').removeClass('hidden') : $("#line").closest('tr').addClass('hidden');

    if(singleLinkProjects.length !== 0 || multipleLinkProjects.length !== 0)
    {
        $('#changeProgram').modal({show: true});
    }

    $.get(createLink('product', 'ajaxGetLine', 'programID=' + programID), function(data)
    {
        $('#line_chosen').remove();
        $('#line').replaceWith(data);
        $('#line').chosen();
    })
})

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
