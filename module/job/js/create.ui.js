function changeEngine(event)
{
    const engine = $(event.target).val();
    const repos = [];
    for(const repoID in repoList)
    {
        const repo = repoList[repoID];
        if(engine == 'jenkins')
        {
            repos.push({text: `[${repo.SCM}] ${repo.name}`, value: repoID});
            continue;
        }

        if(repo.SCM.toLowerCase() == engine) repos.push({text: `[${repo.SCM}] ${repo.name}`, value: repoID});
    }

    const picker = $('[name=repo]').zui('picker');
    picker.render({items: repos});
    picker.$.setValue(repos.length > 0 ? repos[0].value : '');

    if(engine == 'jenkins')
    {
        $('#jenkinsServerTR').removeClass('hidden');
    }
    else
    {
        $('#jenkinsServerTR').addClass('hidden');
    }

    var items = [];
    for(frame in frameList)
    {
        if(engine == 'jenkins' || frame != 'sonarqube') items.push({'text': frameList[frame], 'value': frame});
    }
    zui.Picker.query('[name=frame]').render({items: items});

    changeRepo();
}

function changeFrame(event)
{
    const frame = $(event.target).val();
    if(frame == 'sonarqube')
    {
        $('div.sonarqube').removeClass('hidden');

        /* Check exists sonarqube data. */
        checkSonarquebLink();
    }
    else
    {
        $('div.sonarqube').addClass('hidden');
    }
}

function changeRepo()
{
    const repoID = $('input[name="repo"]').val();
    if(repoID <= 0) return;

    var link = $.createLink('repo', 'ajaxLoadProducts', 'repoID=' + repoID);
    $.get(link, function(data)
    {
        if(data)
        {
            $productPicker = $('#product').zui('picker');
            data = JSON.parse(data);

            $productPicker.render({items: data});
            $productPicker.$.clear();
            if(data[1]) $productPicker.$.setValue(data[1].value);
        }
    });

    /* Add new way get repo type. */
    var link = $.createLink('job', 'ajaxGetRepoType', 'repoID=' + repoID);
    $.getJSON(link, function(data)
    {
        if(data.result == 'success')
        {
            if(data.type.indexOf('git') != -1)
            {
                $('.reference').addClass('gitRepo');

                $('.svn-fields').addClass('hidden');
                $('#reference option').remove();

                $.getJSON($.createLink('job', 'ajaxGetRefList', "repoID=" + repoID), function(response)
                {
                    if(response.result == 'success')
                    {
                        const $reference = $('#reference').zui('picker');
                        $reference.render({items: response.refList});
                        $reference.$.setValue(response.refList.length > 0 ? response.refList[0].value : '');
                    }
                });
            }
            else
            {
                $('.reference').removeClass('gitRepo');
                if($('[name=triggerType]').val() == 'tag') $('.svn-fields').removeClass('hidden');

                $.getJSON($.createLink('repo', 'ajaxGetSVNDirs', 'repoID=' + repoID), function(tags)
                {
                    const $svnDom = $('#svnDir').zui('picker');
                    const options = [];
                    for(path in tags) options.push({text: path, value: path});
                    $svnDom.render({items: options});
                })
            }

            var triggerOptions = $('#triggerType').zui('picker').options.items;
            for(i in triggerOptions)
            {
                if(triggerOptions[i].value == 'tag') triggerOptions[i].text = data.type != 'subversion' ? buildTag : dirChange;
            }
            $('#triggerType').zui('picker').render({items: triggerOptions});
        }
    });

    setPipeline();

    /* Check exists sonarqube data. */
    checkSonarquebLink();
}

$(document).ready(function()
{
    $('[name=engine]').trigger('change');
    $('[name=triggerType]').trigger('change');

    $(document).on('click', '.dropmenu-list li.tree-item', function()
    {
        $('[name=jkTask]').val($('#pipelineDropmenu button.dropmenu-btn').data('value'));
    });
    $(document).on('change', 'select.paramValue', function()
    {
        var paramValue = $(this).val();
        paramValue = paramValue.substr(1).toUpperCase();
        $(this).prevAll('input').val(paramValue);
    });
});
