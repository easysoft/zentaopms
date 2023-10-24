$(document).ready(function()
{
    /*
     * Get frame select.
     * param string engine eg. jenkins|gitlab
     */
    function getFrameSelect()
    {
        $('#frameBox .input-group').empty();
        $('#frameBox .input-group').append("<div class='load-indicator loading'></div>");
        var html = "<select id='frame' name='frame' class='form-control chosen'>";
        for(frame in frameList)
        {
            if(job.engine == 'jenkins' || frame != 'sonarqube')
            {
                html += "<option value='" + frame + "'";
                if(frame == job.frame) html += " selected";
                html += ">" + frameList[frame] + "</option>";
            }
        }
        html += '</select>';

        $('#frameBox .loading').remove();
        $('#frameBox .input-group').append(html);
        $('#frameBox #frame').chosen();
    }
    getFrameSelect();

    /* Check sonarqube linked. */
    function checkSonarquebLink()
    {
        var repoID = $('#repo').val();
        var frame  = $('#frame').val();

        if(frame != 'sonarqube' || repoID == 0) return false;

        $.getJSON(createLink('job', 'ajaxCheckSonarqubeLink', 'repoID=' + repoID + '&jobID=' + job.id), function(result)
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

    $('#gitlabRepo').change(function()
    {
        $('#repo').val($(this).val()).change();
    })

    $('#repo').change(function()
    {
        var repoID = $(this).val();
        var link = createLink('repo', 'ajaxLoadProducts', 'repoID=' + repoID);
        $.get(link, function(data)
        {
            if(data)
            {
                $('#product').replaceWith(data);
                $('#product_chosen').remove();
                $('#product').chosen();
            }
        });

        var type = 'Git';
        if(typeof(repoTypes[repoID]) != 'undefined') type = repoTypes[repoID];

        $('.svn-fields').addClass('hidden');
        if(type == 'Subversion' && $('#triggerType').val() == 'tag') $('.svn-fields').removeClass('hidden');

        $('#repoType').val(type);
        $('#triggerType option[value=tag]').html(type == 'Subversion' ? dirChange : buildTag).trigger('chosen:updated');
        if(type == 'Subversion')
        {
            $('#svnDirBox .input-group').empty();
            $('#svnDirBox .input-group').append("<div class='load-indicator loading'></div>");
            $.getJSON(createLink('repo', 'ajaxGetSVNDirs', 'repoID=' + repoID), function(tags)
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

        /* Check exists sonarqube data. */
        checkSonarquebLink();
    })

    $(document).on('change', '[name^=svnDir]', function()
    {
        var repoID      = $('#repo').val();
        var selectedTag = $(this).val();
        var encodePath  = $(this).find("option:selected").attr('data-encodePath');
        $(this).next('[id$=_chosen]').nextAll('[id^=svnDir]').remove();
        $(this).next('[id$=_chosen]').nextAll('[id$=_chosen]').remove();
        if(selectedTag == '/') return true;

        $('#svnDirBox .input-group').append("<div class='load-indicator loading'></div>");
        $.getJSON(createLink('repo', 'ajaxGetSVNDirs', 'repoID=' + repoID + '&path=' + encodePath), function(tags)
        {
            html    = '';
            length  = $('#svnDirBox .input-group [name^=svnDir]').length;
            length += 1;
            if(tags.length != 0)
            {
                html = "<select id='svnDir" + length + "' name='svnDir[]' class='form-control'>";
                for(path in tags)
                {
                    var encodePath = tags[path];

                    var idx = path.lastIndexOf('/')
                    var basename = idx < 0 ? path : path.substring(idx);

                    html += "<option value='" + path + "' data-encodePath='" + encodePath + "'>" + basename + "</option>";
                }
                html += '</select>';
            }
            $('#svnDirBox .loading').remove();
            $('#svnDirBox .input-group').append(html);
            $('#svnDirBox #svnDir' + length).chosen();
            $('#svnDirBox #svnDir' + length + '_chosen .chosen-single').css('border-left', '0px');
        })
    })

    $('#triggerType').change(function()
    {
        var type = $(this).val();
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
            if(type == 'Subversion')
            {
                $('.svn-fields').removeClass('hidden');
                if($('.svn-fields td .input-group select').length == 0) $('#repo').change();
            }
        }
    });

    $('#jkServer').change(function()
    {
        var jenkinsID = $(this).val();
        $('#jenkinsServerTR .dropdown,#jenkinsServerTR .input-group-addon').hide();
        $('#jenkinsServerTR .input-group').append("<div class='load-indicator loading'></div>");
        setJenkinsJob('', '');

        $.get(createLink('jenkins', 'ajaxGetJenkinsTasks', 'jenkinsID=' + jenkinsID), function(tasks)
        {
            $('#jenkinsServerTR .loading').remove();
            $('#dropMenuTasks').html(tasks);
            $('#jenkinsServerTR .dropdown,.input-group-addon').show();

            if(jenkinsPipeline)
            {
                if(jenkinsPipeline.substr(0, 5) != '/job/') jenkinsPipeline = '/job/' + jenkinsPipeline + '/';
                document.getElementById(jenkinsPipeline).click();
                jenkinsPipeline = '';
            }
        });

        /* There has been a problem with handling the prompt label. */
        $('#jkTaskLabel').remove();
        $('.jktask-label').addClass('text-right');
    })

    $(document).on('change', '#frame', function()
    {
        var frame = $(this).val();
        if(frame == 'sonarqube')
        {
            $('tr.sonarqube').removeClass('hide');

            /* Check exists sonarqube data. */
            checkSonarquebLink();
        }
        else
        {
            $('tr.sonarqube').addClass('hide');
        }
    })

    $(document).on('change', '#sonarqubeServer', function()
    {
        var sonarqubeID = $(this).val();
        var projectKey  = sonarqubeID == job.sonarqubeServer ? job.projectKey.replace(/-/g, '*') : '';
        $('#sonarProject #projectKey').remove();
        $('#sonarProject #projectKey_chosen').remove();
        $('#sonarProject .input-group').append("<div class='load-indicator loading'></div>");
        $.get(createLink('sonarqube', 'ajaxGetProjectList', 'sonarqubeID=' + sonarqubeID + '&projectKey=' + projectKey), function(html)
        {
            $('#sonarProject .loading').remove();
            $('#sonarProject .input-group').append(html);
            $('#sonarProject #projectKey').chosen({drop_direction: 'auto'});
        })

        /* There has been a problem with handling the prompt label. */
        $('#projectKeyLabel').remove();
    })

    var scheduleOption = "<option value='schedule'>" + $('#triggerType').find('[value=schedule]').text() + "</option>";
    $('#engine').change(function()
    {
        $('#jenkinsServerTR').toggle($('#engine').val() == 'jenkins');
        $('#gitlabServerTR').toggle($('#engine').val() == 'gitlab');

        if($(this).val() == 'gitlab')
        {
            $('#triggerType').find('[value=schedule]').remove();
            $('tr.gitlabRepo').show();
            $('tr.commonRepo').hide();
        }
        else if($('#triggerType').find('[value=schedule]').size() == 0 )
        {
            $('#triggerType').append(scheduleOption);
            $('tr.gitlabRepo').hide();
            $('tr.commonRepo').show();
        }
    });

    $('#gitlabRepo').change(function()
    {
        $('#reference option').remove();

        var repoID  = $(this).val();
        if(repoID > 0)
        {
            $.getJSON(createLink('job', 'ajaxGetRefList', "repoID=" + repoID), function(response)
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
    });

    $('#engine').change();
    $('#jkServer').change();
    $('#frame').change();
    $('#sonarqubeServer').change();
    $('#triggerType').change();
});

/**
 * Set jenkins job.
 *
 * @param string $name
 * @param string $task
 * @access public
 * @return void
 */
function setJenkinsJob(name, task)
{
    if(name) $('.jktask-label').removeClass('text-right');
    $('#jkTask').val(task);
    $('.jktask-label .text').html(name);
}
