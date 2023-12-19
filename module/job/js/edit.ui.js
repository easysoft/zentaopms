function changeEngine(engine)
{
    engine == 'jenkins' ? $('.reference').show() : $('.reference').hide();

    var repos     = engine == 'gitlab' ? gitlabRepos : repoPairs;
    var repoItems = [];
    for(i in repos)
    {
        repoItems.push({'text': repos[i], 'value': i});
    }
    const picker = $('[name=repo]').zui('picker');
    if(picker) picker.render({items: repoItems});

    if(engine == 'gitlab')
    {
        $('#gitlabServerTR').removeClass('hidden');
        $('#jenkinsServerTR').addClass('hidden');
    }
    else
    {
        $('#gitlabServerTR').addClass('hidden');
        $('#jenkinsServerTR').removeClass('hidden');
    }
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

function changeRepo(event)
{
    const repoID = $(event.target).val();
    if(repoID <= 0) return;

    var link = $.createLink('repo', 'ajaxLoadProducts', 'repoID=' + repoID);
    $.get(link, function(data)
    {
        if(data)
        {
            $productPicker = $('[name=product]').zui('picker');
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
                //if(engine == 'jenkins') $('.reference').removeClass('hidden');
                $('.reference').addClass('gitRepo');

                $('.svn-fields').addClass('hidden');
                $('#reference option').remove();

                $.getJSON($.createLink('job', 'ajaxGetRefList', "repoID=" + repoID), function(response)
                {
                    if(response.result == 'success')
                    {
                        $('[name=reference]').zui('picker').render({items: response.refList});
                    }
                });
            }
            else
            {
                $('.reference').removeClass('gitRepo');
                if($('[name=triggerType]').val() == 'tag') $('.svn-fields').removeClass('hidden');

                $('#svnDir').remove();
                $('#svnDirBox').append("<div class='load-indicator loading'></div>");
                $.getJSON($.createLink('repo', 'ajaxGetSVNDirs', 'repoID=' + repoID), function(tags)
                {
                    html = "<select id='svnDir' name='svnDir[]' class='form-control'>";
                    for(path in tags)
                    {
                        var encodePath = tags[path];
                        html += "<option value='" + path + "' data-encodePath='" + encodePath + "'>" + path + "</option>";
                    }
                    html += '</select>';
                    $('#svnDirBox .loading').remove();
                    $('#svnDirBox').append(html);
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

    /* Check exists sonarqube data. */
    checkSonarquebLink();

}


/*
 * Check sonarqube linked.
 */
function checkSonarquebLink()
{
    var repoID = $('#repo').val();
    var frame  = $('#frame').val();

    if(frame != 'sonarqube' || repoID == 0) return false;

    $.getJSON(createLink('job', 'ajaxCheckSonarqubeLink', 'repoID=' + repoID), function(result)
    {
        if(result.result  != 'success')
        {
            alert(result.message);
            $('#repo').val(0).trigger('chosen:updated');
            $('#reference').val('').trigger('chosen:updated');
            $('.reference').hide();
            return false;
        }
    })
}

function changeJenkinsServer(event)
{
    const jenkinsID = $(event.target).val();

    var pipelineDropmenu = zui.Dropmenu.query('#pipelineDropmenu');
    if(!jenkinsID)
    {
        pipelineDropmenu.render({fetcher: ''});
    }
    else
    {
        pipelineDropmenu.render({fetcher: $.createLink('jenkins', 'ajaxGetJenkinsTasks', 'jenkinsID=' + jenkinsID)})
    }
}

function changeTriggerType(event)
{
    if(typeof(event) == 'object')
    {
        var type = $(event.target).val();
    }
    else
    {
        var type = event;
    }
    dirs ? $('.svn-fields').removeClass('hidden') : $('.svn-fields').addClass('hidden');
    $('.comment-fields').addClass('hidden');
    $('.custom-fields').addClass('hidden');
    if(type == 'commit')   $('.comment-fields').removeClass('hidden');
    if(type == 'schedule') $('.custom-fields').removeClass('hidden');
}

function changeSonarqubeServer(event)
{
    var sonarqubeID = $(event.target).val();
    $.get($.createLink('sonarqube', 'ajaxGetProjectList', 'sonarqubeID=' + sonarqubeID), function(data)
    {
        data = JSON.parse(data);
        $('#projectKey').zui('picker').render({items: data});
    })

    /* There has been a problem with handling the prompt label. */
    $('#projectKeyLabel').remove();
}

$(function()
{
    changeEngine(engine);
    changeTriggerType(job.triggerType);

    $(document).on('click', '.dropmenu-list li.tree-item', function()
    {
        $('#jkTask').val($('#pipelineDropmenu button.dropmenu-btn').data('value'));
    });
    $(document).on('change', 'select.paramValue', function()
    {
        var paramValue = $(this).val();
        paramValue = paramValue.substr(1).toUpperCase();
        $(this).prevAll('input').val(paramValue);
    });
});
