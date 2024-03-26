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

$(function()
{
    changeTrigger(job.triggerType);
    changeTriggerType(job.triggerType);

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
