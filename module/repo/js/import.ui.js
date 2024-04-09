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

window.onRenderRowCol = function(row, cel)
{
    if(cel.name == 'actions')
    {
       const repoID = $(row).closest('tr').find('input[data-name="serviceProject"]').val();
       if(hiddenRepos.includes(repoID))
        {
            $(row).find('button').attr('title', showLang);
            $(row).find('button').removeClass('hideRepo').addClass('showRepo');
            $(row).find('.icon').removeClass('icon-eye-off').addClass('icon-eye');
        }

    }

}

/**
 * Show hidden repos.
 *
 * @access public
 * @return void
 */
window.toggleShowRepo = function(event)
{
    const showHidden = $(event.target).prop('checked') ? 1 : 0;
    loadPage($.createLink('repo', 'import', 'server=' + serverID + '&showHiddenRepo=' + showHidden), '#repoList');
}

/**
 * Hide a repo.
 *
 * @access public
 * @return void
 */
window.hideRepo = function(event)
{
    const repoID = $(event.target).closest('tr').find('input[data-name="serviceProject"]').val();
    $.post($.createLink('repo', 'ajaxHiddenRepo'), {'serverID': serverID, 'repoID': repoID}, function(response){
        if(response.result == 'fail') return zui.Modal.alert(response.message);
        $(event.target).closest('tr').remove();
    });
}

window.showRepo = function(event)
{
    const repoID = $(event.target).closest('tr').find('input[data-name="serviceProject"]').val();
    $.post($.createLink('repo', 'ajaxShowRepo'), {'serverID': serverID, 'repoID': repoID}, function(response){
        if(response.result == 'fail') return zui.Modal.alert(response.message);
        $(event.target).find('button').attr('title', hideLang);
        $(event.target).find('button').removeClass('showRepo').addClass('hideRepo');
        $(event.target).find('.icon').removeClass('icon-eye').addClass('icon-eye-off');
    });
}
