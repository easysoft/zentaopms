$(function()
{
    checkItem();
    $('.form-batch-row-actions .form-batch-btn').each(function()
    {
        if($(this).data('type') == 'add') $(this).addClass('hidden');
    });
});

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

/**
 * Change server.
 *
 * @access public
 * @return void
 */
function selectServer(event)
{
    var server = $(event.target).val();
    if(server) loadPage($.createLink('repo', 'import', 'server=' + server));
}

function loadProductProjects(event)
{
    const $target     = $(event.target);
    const $currentRow = $target.closest('tr');
    const products    = $target.val();
    const projects    = $currentRow.find('div.picker-box[data-name="projects"]');
    const projectIds  = $(projects).val();

    $.post($.createLink('repo', 'ajaxProjectsOfProducts'), {products : products.join(','), projects: projectIds, number : 1}, function(response)
    {
        var items = JSON.parse(response);
        $(projects).zui('picker').render({items: items});
    });
}
