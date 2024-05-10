$(function()
{
    onScmChange();
    $('div.service-project .form-label').addClass('required');

    if(appTab != 'devops' && !hasProduct) zui.Modal.alert(noProductTip);
});

function onHostChange()
{
    var host     = $('[name=serviceHost]').val();
    var url      = $.createLink('repo', 'ajaxGetProjects', "host=" + host);

    const repo = zui.Dropmenu.query('#repoDropMenu');

    if(host == '')
    {
      repo.render({fetcher: ''});
      return false;
    }

    toggleLoading('#repoDropMenu', true);
    repo.render({fetcher: url})
    toggleLoading('#repoDropMenu', false);
}

function onProjectChange()
{
    var serviceProject = $('#repoDropMenu').find('span').first().text();
    if(!serviceProject)
    {
        $('#name').val('');
        return;
    }
    else
    {
        $('#name').val(serviceProject);
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

    if(scm != 'Subversion')
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
        $.getJSON(url, function(data)
        {
            const $hostPicker = $('#serviceHost').zui('picker');
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

window.importJob = function(repoID)
{
    var url = $.createLink('job', 'ajaxImportJobs', "repoID=" + repoID);
    $.getJSON(url);
}
