$(document).ready(function()
{
    /*
     * Get frame select.
     * param string engine eg. jenkins|gitlab
     */
    function getFrameSelect(engine)
    {
        $('#frameBox .input-group').empty();
        $('#frameBox .input-group').append("<div class='load-indicator loading'></div>");
        var html = "<select id='frame' name='frame' class='form-control chosen'>";
        for(frame in frameList)
        {
            if(engine == 'jenkins' || frame != 'sonarqube') html += "<option value='" + frame + "'>" + frameList[frame] + "</option>";
        }
        html += '</select>';

        $('#frameBox .loading').remove();
        $('#frameBox .input-group').append(html);
        $('#frameBox #frame').chosen();

        $('#frame').change();
    }
    getFrameSelect('');

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

    $(document).on('change', '#repo', function()
    {
        var repoID = $(this).val();
        if(repoID <= 0) return;

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

        /* Add new way get repo type. */
        var link = createLink('job', 'ajaxGetRepoType', 'repoID=' + repoID);
        $.getJSON(link, function(data)
        {
            if(data.result == 'success')
            {
                if(data.type.indexOf('git') != -1)
                {
                    $('.reference').show();
                    $('.svn-fields').addClass('hidden');
                    $('#reference option').remove();

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
                else
                {
                    if($('#triggerType').val() == 'tag') $('.svn-fields').removeClass('hidden');

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
                $('#triggerggerType option[value=tag]').html(data.type == 'gitlab' ? buildTag : dirChange).trigger('chosen:updated');
            }
        });

        /* Check exists sonarqube data. */
        checkSonarquebLink();
    });

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
            length++;

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
            $('#svnDir' + length + '_chosen .chosen-single').css('border-left', '0px');
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
            if(type == 'Subversion') $('.svn-fields').removeClass('hidden');
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
    })

    var scheduleOption = "<option value='schedule'>" + $('#triggerType').find('[value=schedule]').text() + "</option>";
    $('#engine').change(function()
    {
        var engine = $(this).val();
        $('.reference').hide();
        loadRepoList(engine);
        $('#jenkinsServerTR').toggle(engine == 'jenkins');
        $('#gitlabServerTR').toggle(engine == 'gitlab');

        if(engine == 'gitlab')
        {
            $('tr.gitlabRepo').show();
            $('tr.commonRepo').hide();
        }
        //else if($('#triggerType').find('[value=schedule]').size() == 0)
        else
        {
            $('tr.gitlabRepo').hide();
            $('tr.commonRepo').show();
        }

        getFrameSelect(engine);
    });

    $('#engine').change();
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
