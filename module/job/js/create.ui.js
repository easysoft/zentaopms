function changeEngine(event)
{
    const engine = $(event.target).val();

    $('.reference').hide();

    var repos     = engine == 'gitlab' ? gitlabRepos : repoPairs;
    var repoItems = [];
    for(i in repos)
    {
        repoItems.push({'text': repos[i], 'value': i});
    }
    zui.Picker.query('#repo').render({items: repoItems});

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

    var items = [];
    for(frame in frameList)
    {
        if(engine == 'jenkins' || frame != 'sonarqube') items.push({'text': frameList[frame], 'value': frame});
    }
    zui.Picker.query('#frame').render({items: items});
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
    var repoID = $(event.taget).val();
    if(repoID <= 0) return;

    var link = $.createLink('repo', 'ajaxLoadProducts', 'repoID=' + repoID);
    $.get(link, function(data)
    {
        if(data)
        {
            $('#product').replaceWith(data);
            $('#product_chosen').remove();
            $('#product').picker();
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
                $('.reference').show();
                $('.svn-fields').addClass('hidden');
                $('#reference option').remove();

                $.getJSON($.createLink('job', 'ajaxGetRefList', "repoID=" + repoID), function(response)
                {
                    if(response.result == 'success')
                    {
                        $.each(response.refList, function(reference, name)
                        {
                            $('#reference').append("<option value='" + reference + "'>" + name + "</option>");
                        });
                    }
                    $('#reference').trigger('chosen:updated');
                });
            }
            else
            {
                if($('#triggerType').val() == 'tag') $('.svn-fields').removeClass('hidden');

                $('#svnDirBox .input-group').empty();
                $('#svnDirBox .input-group').append("<div class='load-indicator loading'></div>");
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
                    $('#svnDirBox .input-group').append(html);
                    $('#svnDirBox #svnDir').chosen();
                })
            }
            $('#triggerggerType option[value=tag]').html(data.type == 'gitlab' ? buildTag : dirChange).trigger('chosen:updated');
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
    var type = $(event.target).val();
    $('.svn-fields').addClass('hidden');
    $('.comment-fields').addClass('hidden');
    $('.custom-fields').addClass('hidden');
    if(type == 'commit')   $('.comment-fields').removeClass('hidden');
    if(type == 'schedule') $('.custom-fields').removeClass('hidden');
    if(type == 'tag')
    {
        var repoID = $('#repo').val();
        var type   = 'Git';
        if(typeof(repoTypes[repoID]) != 'undefined') type = repoTypes[repoID];
        if(type == 'Subversion') $('.svn-fields').removeClass('hidden');
    }
}

function changeSonarqubeServer()
{
    var sonarqubeID = $(this).val();
    $('#sonarProject #projectKey').remove();
    $('#sonarProject #projectKey_chosen').remove();
    $('#sonarProject .input-group').append("<div class='load-indicator loading'></div>");
    $.get(createLink('sonarqube', 'ajaxGetProjectList', 'sonarqubeID=' + sonarqubeID), function(html)
    {
        $('#sonarProject .loading').remove();
        $('#sonarProject .input-group').append(html);
        $('#sonarProject #projectKey').chosen({drop_direction: 'auto'});
    })

    /* There has been a problem with handling the prompt label. */
    $('#projectKeyLabel').remove();
}

$(document).ready(function()
{
    $('#engine').trigger('change');
    $('#triggerType').trigger('change');
});
