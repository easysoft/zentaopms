$(function()
{
    onScmChange();
    onHostChange();
});

function onProductChange(event)
{
    var projects = $('#projects').val();
    var products = $('#product').val();
    $.post($.createLink('repo', 'ajaxProjectsOfProducts'), {products, projects}, function(response)
    {
        $('#projectContainer').html('').append(response);
        $('#projects').chosen().trigger("chosen:updated");
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
 * @param  string $scm
 * @access public
 * @return void
 */
function onScmChange()
{
    const scm = $('#SCM').val();

    if(scm == 'Git' || scm == 'Gitea' || scm == 'Gogs')
    {
        $('.account-fields').hide();
        $('.tips-git').removeClass('hidden');
        $('.tips-svn').addClass('hidden');
    }
    else
    {
        $('.account-fields').show();
        $('.tips-git').addClass('hidden');
        $('.tips-svn').removeClass('hidden');
    }

    if(scm == 'Git' || scm == 'Subversion')
    {
        $('.service').toggle(false);
        $('.hide-service').toggle(true);
    }
    else
    {
        $('.tips').addClass('hidden');
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

        var url = $.createLink('repo', 'ajaxGetHosts', "scm=" + scm);
        $.get(url, function(response)
        {
            $('#serviceHost').html(response);
            onHostChange();
        });
    }
}
