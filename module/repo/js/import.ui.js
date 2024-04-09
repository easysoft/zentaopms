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

/**
 * Hidden repo.
 *
 * @access public
 * @return void
 */
window.hiddenRepo = function(event)
{
    var repoID = $(event.target).closest('tr').find('input[data-name="serviceProject"]').val();
    $.post($.createLink('repo', 'ajaxHiddenRepo'), {'serverID': serverID, 'repoID': repoID}, function(response){
        if(response.result == 'fail') return zui.Modal.alert(response.message);
        $(event.target).closest('tr').remove();
    });
}
