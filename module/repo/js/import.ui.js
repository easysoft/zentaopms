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
 * Render row action.
 *
 * @access public
 * @return void
 */
window.onRenderRowCol = function(row, cel)
{
    if(cel.name == 'actions')
    {
        const repoID = $(row).closest('tr').find('input[data-name="serviceProject"]').val();
        if(hiddenRepos.includes(repoID))
        {
            $(row).closest('tr').addClass('hidden hide-repo');
            $(row).find('button').attr('title', showLang);
            $(row).find('button').html('<i class="icon icon-eye">');
        }
        else
        {
            $(row).find('button').attr('title', hideLang);
            $(row).find('button').html('<i class="icon icon-eye-off">');
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
    if($(event.target).prop('checked'))
    {
        $('.hide-repo').removeClass('hidden');
    }
    else
    {
        $('.hide-repo').addClass('hidden');
    }
}

/**
 * Set a repo state.
 *
 * @access public
 * @return void
 */
window.setRepoState = function(event)
{
    const elem   = $(event.target);
    const trElem = elem.closest('tr');
    const repoID = trElem.find('input[data-name="serviceProject"]').val();

    if(trElem.hasClass('hide-repo'))
    {
        elem.find('.icon').removeClass('icon-eye').addClass('icon-eye-off');
        elem.find('button').attr('title', hideLang);
        $.post($.createLink('repo', 'ajaxShowRepo'), {'serverID': serverID, 'repoID': repoID}, function(response){
            if(response.result == 'fail') return zui.Modal.alert(response.message);
            trElem.removeClass('hide-repo');
        });
    }
    else
    {
        if(!$('.show-all-repo').prop('checked')) trElem.addClass('hidden');
        elem.find('.icon').removeClass('icon-eye-off').addClass('icon-eye');
        elem.find('button').attr('title', showLang);
        $.post($.createLink('repo', 'ajaxHiddenRepo'), {'serverID': serverID, 'repoID': repoID}, function(response){
            if(response.result == 'fail') return zui.Modal.alert(response.message);
            trElem.addClass('hide-repo');
        });
    }
}
