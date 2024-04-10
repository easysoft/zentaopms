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
    let elem = $(event.target);
    if(elem.find('.icon').length > 0) elem = elem.find('.icon');

    const trElem = elem.closest('tr');
    const repoID = trElem.find('input[data-name="serviceProject"]').val();

    const postData = {
        "serverID": serverID,
        "repoID":   repoID
    };
    if(trElem.hasClass('hide-repo'))
    {
        $.post($.createLink('repo', 'ajaxShowRepo'), postData, function(response)
        {
            const {result, message} = JSON.parse(response);
            if(result == 'fail') {return zui.Modal.alert(message);}

            trElem.removeClass('hide-repo');
            elem.removeClass('icon-eye').addClass('icon-eye-off');
            elem.parent().attr('title', hideLang);
        });
    }
    else
    {
        $.post($.createLink('repo', 'ajaxHiddenRepo'), postData, function(response)
        {
            const {result, message} = JSON.parse(response);
            if(result == 'fail') {return zui.Modal.alert(message);}

            trElem.addClass('hide-repo');
            if(!$('.show-all-repo').prop('checked')) trElem.addClass('hidden');

            elem.removeClass('icon-eye-off').addClass('icon-eye');
            elem.parent().attr('title', showLang);

            const productDom = $('#product_' + trElem.data('index')).zui('picker');
            if(productDom) productDom.$.setValue('');
        });
    }
}
