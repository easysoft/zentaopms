window.customCount = 1;
function addItem(event)
{
    const obj        = $(event.target);
    const inputGroup = obj.closest('.input-group').clone();
    const newName    = window.customCount + 'custom';
    window.customCount ++;

    $(inputGroup).find('input.custom').attr('id', newName);
    $(inputGroup).find('input.paramName').val('');
    $(inputGroup).find('input[id="' + newName + '"]').next().attr('for', newName);
    obj.closest('.form-group').append($(inputGroup));
}

function deleteItem(event)
{
    const $obj = $(event.target);
    if($('.delete-param').length > 1) $obj.closest('.input-group').remove();
}

/**
 * Show input, hidden select.
 *
 * @param  obj $obj
 * @access public
 * @return void
 */
function setValueInput(event)
{
    const obj = event.target;
    if($(obj).prop('checked'))
    {
        $(obj).closest('.input-group').find('select').attr('disabled', true);
        $(obj).closest('.input-group').find('select').addClass('hidden');
        $(obj).closest('.input-group').find("input[name^='paramValue']").removeClass('hidden');
        $(obj).closest('.input-group').find("input[name^='paramValue']").removeAttr('disabled');
    }
    else
    {
        $(obj).closest('.input-group').find("input[name^='paramValue']").attr('disabled', true);
        $(obj).closest('.input-group').find("input[name^='paramValue']").addClass('hidden');
        $(obj).closest('.input-group').find('select').removeClass('hidden');
        $(obj).closest('.input-group').find('select').removeAttr('disabled');
    }
}

function loadRepoList(engine = '')
{
    var link = $.createLink('job', 'ajaxGetRepoList', 'engine=' + engine);
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

function changeTrigger(event)
{
    let useZentao;
    if(typeof(event) == 'object')
    {
        useZentao = $(event.target).val();
    }
    else
    {
        useZentao = event;
    }

    if(useZentao)
    {
        $('.job-form #paramDiv').show();
        $('.job-form .sonarqube').show();
        $('.job-form .custom-fields').show();
        $('.job-form .comment-fields').show();
        $('.job-form #jenkinsServerTR').show();
        $('.job-form [data-name="triggerType"]').show();
    }
    else
    {
        $('.job-form #paramDiv').hide();
        $('.job-form .sonarqube').hide();
        $('.job-form .custom-fields').hide();
        $('.job-form .comment-fields').hide();
        $('.job-form #jenkinsServerTR').hide();
        $('.job-form [data-name="triggerType"]').hide();
    }
}

/*
 * Check sonarqube linked.
 */
function setPipeline()
{
    $('.gitfox-pipeline').addClass('hidden');
    const $pipeline = $('[name=gitfoxpipeline]').zui('picker');
    $pipeline.$.clear();

    const repoID = $('[name=repo]').val();
    if(repoID == 0) return false;
    if(!repoList[repoID] || repoList[repoID].SCM != 'GitFox') return;

    $.getJSON($.createLink('job', 'ajaxGetPipelines', 'repoID=' + repoID), function(result)
    {
        let pipelines = [];
        if(result.data.length > 0) pipelines = result.data;

        $pipeline.render({items: pipelines});
        $('.gitfox-pipeline').removeClass('hidden');
    })
}

/*
 * Check sonarqube linked.
 */
function checkSonarquebLink()
{
    var repoID = $('[name=repo]').val();
    var frame  = $('[name=frame]').val();

    if(frame != 'sonarqube' || repoID == 0) return false;

    $.getJSON($.createLink('job', 'ajaxCheckSonarqubeLink', 'repoID=' + repoID), function(result)
    {
        if(result.result  != 'success') zui.Modal.alert(result.message);
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
    type == 'tag' ? $('.svn-fields').removeClass('hidden') : $('.svn-fields').addClass('hidden');
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
