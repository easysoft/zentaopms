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
    var host = $('#serviceHost').zui('picker').$.state.value;
    if(host == '')
    {
      $project.render({items: []});
      $project.$.clear();
      return false;
    }

    var $groups = $('#namespace').zui('picker');
    toggleLoading('#namespace', true);
    $.get($.createLink('repo', 'ajaxGetGroups', "host=" + host), function(resp)
    {
        resp = JSON.parse(resp);
        $groups.render({items: resp.options});
        $groups.$.clear();
        toggleLoading('#namespace', false);
        $('.hide-service').toggle(resp.server.type == 'gitea');
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
