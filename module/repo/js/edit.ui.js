$(function()
{
    onScmChange(true);
});

function onProductChange(event)
{
    var projects = $('#projects').val();
    var products = $('#product').val();
    $.post($.createLink('repo', 'ajaxProjectsOfProducts'), {products, projects}, function(response)
    {
        $('#projects').replaceWith(response);
    });
}

function onHostChange()
{
    var host = $('#serviceHost').val();
    var url  = $.createLink('repo', 'ajaxGetProjects', "host=" + host);
    if(host == '') return false;

    $.get(url, function(response)
    {
        $('#serviceProject').html(response);
    });
}

function onProjectChange()
{
    $option = $('#serviceProject option').eq($('#serviceProject').prop('selectedIndex'));
    $('#name').val($option.data('name'));
}

/**
 * Changed SCM.
 *
 * @param  bool   $isFirstRequest
 * @access public
 * @return void
 */
function onScmChange(isFirstRequest = false)
{
    const scm = $('#SCM').val();

    (scm == 'Git') ? $('.tips-git').removeClass('hidden') : $('.tips-git').addClass('hidden');
    if(scm == 'Git' || scm == 'Gitea' || scm == 'Gogs')
    {
        $('.account-fields').hide();
        $('#path').attr('placeholder', pathGitTip);
        $('#client').attr('placeholder', clientGitTip);
    }
    else
    {
        $('.account-fields').show();
        $('#path').attr('placeholder', pathSvnTip);
        $('#client').attr('placeholder', clientSvnTip);
    }

    if(scm == 'Git' || scm == 'Subversion')
    {
        $('.service').toggle(false);
        $('.hide-service').toggle(true);
    }
    else
    {
        $('.service').toggle(true);
        if(scm == 'Gitea' || scm == 'Gogs')
        {
            $('.hide-service').each(function()
            {
                if(!$(this).hasClass('hide-git')) $(this).toggle(true);
            });
            $('.hide-git').toggle(false);
        }
        else
        {
            $('.hide-service').toggle(false);
        }

        if(!isFirstRequset)
        {
            var url = $.createLink('repo', 'ajaxGetHosts', "scm=" + scm);
            $.get(url, function(response)
            {
                $('#serviceHost').html(response);
                onHostChange();
            });
        }
    }
}
