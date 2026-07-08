window.loadRepoList = function(engine = '')
{
    const link = $.createLink('job', 'ajaxGetRepoList', 'engine=' + engine);
    $.get(link, function(data)
    {
        if(data)
        {
            $('#repo').replaceWith(data)
            $('#repo_chosen').remove();
            $('#repo').chosen();
        }
    });
}

/*
 * Check sonarqube linked.
 */
window.checkSonarquebLink = function()
{
    const repoID = $('[name=repo]').val();
    const frame  = $('[name=frame]').val();
    const jobID  = typeof(job) == 'undefined' ? 0 : job.id;

    if(frame != 'sonarqube' || repoID == 0) return false;

    $.getJSON($.createLink('job', 'ajaxCheckSonarqubeLink', 'repoID=' + repoID + '&jobID=' + jobID), function(result)
    {
        if(result.result  != 'success') zui.Modal.alert(result.message);
    })
}

window.changeJenkinsServer = function(event)
{
    const jenkinsID = $(event.target).val();

    const pipelineDropmenu = zui.Dropmenu.query('#pipelineDropmenu');
    if(!jenkinsID)
    {
        pipelineDropmenu.render({fetcher: ''});
    }
    else
    {
        pipelineDropmenu.render({fetcher: $.createLink('jenkins', 'ajaxGetJenkinsTasks', 'jenkinsID=' + jenkinsID)})
    }
}

window.changeSonarqubeServer = function()
{
    const sonarqubeID = $('[name=sonarqubeServer]').val();
    $.getJSON($.createLink('sonarqube', 'ajaxGetProjectList', 'sonarqubeID=' + sonarqubeID), function(data)
    {
        $('#projectKey').zui('picker').render({items: data});

        const project = data.length ? data[0].value : '';
        $('#projectKey').zui('picker').$.setValue(project)
    })

    /* There has been a problem with handling the prompt label. */
    $('#projectKeyLabel').remove();
}

window.changeFrame = function(event)
{
    const frame = $(event.target).val();
    if(frame == 'sonarqube')
    {
        /* Check exists sonarqube data. */
        checkSonarquebLink();

        $('div.sonarqube').removeClass('hidden');
    }
    else
    {
        $('div.sonarqube').addClass('hidden');
    }
}

window.changeEngine = function(event)
{
    const engine      = $(event.target).val();
    const repos       = [];
    let   checkedRepo = '';
    for(const repoID in repoList)
    {
        const repo = repoList[repoID];
        if(engine == 'jenkins')
        {
            if(repoID == pageRepoID || !checkedRepo) checkedRepo = repoID;
            repos.push({text: `[${repo.SCM}] ${repo.name}`, value: repoID});
        }
        else if(repo.SCM.toLowerCase() == engine)
        {
            if(repoID == pageRepoID || !checkedRepo) checkedRepo = repoID;
            repos.push({text: `[${repo.SCM}] ${repo.name}`, value: repoID});
        }
    }

    const picker = $('[name=repo]').zui('picker');
    picker.render({items: repos});
    picker.$.setValue(checkedRepo);

    if(engine == 'jenkins')
    {
        $('#jenkinsServerTR').removeClass('hidden');
    }
    else
    {
        $('#jenkinsServerTR').addClass('hidden');
    }

    const items = [];
    for(frame in frameList)
    {
        if(engine == 'jenkins' || frame != 'sonarqube') items.push({'text': frameList[frame], 'value': frame});
    }
    zui.Picker.query('[name=frame]').render({items: items});
    $('[name=frame]').zui('picker').$.setValue('');
}

window.changeRepo = function()
{
    const repoID = $('input[name="repo"]').val();
    if(!repoID) return;

    let link = $.createLink('repo', 'ajaxLoadProducts', 'repoID=' + repoID);
    $.get(link, function(data)
    {
        if(data)
        {
            $productPicker = $('input[name="product"]').zui('picker');
            data = JSON.parse(data);

            $productPicker.render({items: data});
            $productPicker.$.clear();
            if(data[1]) $productPicker.$.setValue(data[1].value);
        }
    });

    /* Add new way get repo type. */
    link = $.createLink('job', 'ajaxGetRepoType', 'repoID=' + repoID);
    const $trigger = $('[name=triggerType]').zui('picker');
    $.getJSON(link, function(data)
    {
        if(data.result == 'success')
        {
            const triggerOptions = [];
            for(code in triggerList)
            {
                if(code == 'tag' && !data.triggerByTag) continue;
                triggerOptions.push({text: triggerList[code], value: code})
            }
            $trigger.render({items: triggerOptions});
            if($('[name=triggerType]').val() == 'tag') $trigger.$.setValue(triggerOptions[0].value);

            if(data.type.indexOf('git') != -1)
            {
                $('.reference').addClass('gitRepo');

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
            }

            for(i in triggerOptions)
            {
                if(triggerOptions[i].value == 'tag') triggerOptions[i].text = data.type != 'subversion' ? buildTag : dirChange;
            }
            $trigger.render({items: triggerOptions});
        }
    });

    /* Check exists sonarqube data. */
    window.checkSonarquebLink();
}
