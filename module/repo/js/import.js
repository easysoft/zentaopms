/**
 * Delete the project.
 *
 * @param object $icon
 * @access public
 * @return void
 */
function delItem(icon)
{
    $(icon).closest('tr').remove();
    checkItem();
}

/**
 * Check the project.
 *
 * @access public
 * @return void
 */
function checkItem()
{
    if(!$("#repoList tbody tr").length) $("#submit").prop('disabled', true);
}

$(function()
{
    checkItem();

    $(document).on('change', '[id^=product]', function()
    {
        var i        = $(this).attr('id').replace(/[^0-9]/ig, '');
        var projects = $('#projects' + i).val();
        var products = $(this).val();
        $.post(createLink('repo', 'ajaxProjectsOfProducts'), {products, projects, 'number':i}, function(response)
        {
            $('#projectContainer' + i).html('').append(response);
            $('#projects' + i).chosen().trigger("chosen:updated");
        });
    });

});

/**
 * Change server.
 *
 * @access public
 * @return void
 */
function selectServer()
{
    var server = $('#servers').val();
    window.location.href = createLink('repo', 'import', 'server=' + server);
}
