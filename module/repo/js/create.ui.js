$(function()
{
    onScmChange();
    $('div.service-project .form-label').addClass('required');
});

function onProductChange(event)
{
    var projects = $('[name="projects[]"]').val();
    var products = $('[name="product[]"]').val();

    $.post($.createLink('repo', 'ajaxProjectsOfProducts'), {'products': products.join(','), 'projects': projects.join(',')}, function(response)
    {
        var data      = JSON.parse(response);
        var $projects = $('#projects').zui('picker');
        $projects.render({items: data});
        $projects.$.clear();
    });
}

function onHostChange()
{
    var host     = $('[name=serviceHost]').val();
    var url      = $.createLink('repo', 'ajaxGetProjects', "host=" + host);
    var $project = $('#serviceProject').zui('picker');
    if(host == '')
    {
      $project.render({items: []});
      $project.$.clear();
      return false;
    }

    toggleLoading('#serviceProject', true);
    $.get(url, function(response)
    {
        var data = JSON.parse(response);
        $project.render({items: data});
        $project.$.clear();
        toggleLoading('#serviceProject', false);
    });
}

function onProjectChange()
{
    var serviceProject = $('#serviceProject').zui('picker').$.state.value;
    var items          = $('#serviceProject').zui('picker').$.state.items;
    if(!serviceProject)
    {
        $('#name').val('');
        return;
    }

    for(i in items)
    {
        if(items[i].value == serviceProject)
        {
            var projectName = items[i].text.split('/');
            $('#name').val(projectName[1].trim());
            break;
        }
    }
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
    var scm = $('[name=SCM]').val();
    if(!scm)
    {
        for(i in scmList)
        {
            scm = i;
            break;
        }
    }

    (scm == 'Git') ? $('.tips-git').removeClass('hidden') : $('.tips-git').addClass('hidden');

    if(scm == 'Git' || scm == 'Gitea' || scm == 'Gogs')
    {
        $('.account-fields').addClass('hidden');
        $('#path').attr('placeholder', pathGitTip);
        $('#client').attr('placeholder', clientGitTip);
        $('#client').val('/usr/bin/git');
    }
    else
    {
        $('.account-fields').removeClass('hidden');
        $('#path').attr('placeholder', pathSvnTip);
        $('#client').attr('placeholder', clientSvnTip);
        $('#client').val('/usr/bin/svn');
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

        var url = $.createLink('repo', 'ajaxGetHosts', "scm=" + scm);
        $.get(url, function(response)
        {
            var data = JSON.parse(response);
            var $hostPicker = $('#serviceHost').zui('picker');
            $hostPicker.render({items: data});
            $hostPicker.$.clear();
        });
    }
}

/**
 * On acl change event.
 *
 * @param  event $event
 * @access public
 * @return void
 */
function onAclChange(event)
{
    const acl = $(event.target).val();
    if(acl == 'private' || acl == 'custom')
    {
        $('#whitelist').removeClass('hidden');
    }
    else
    {
        $('#whitelist').addClass('hidden');
    }
}
