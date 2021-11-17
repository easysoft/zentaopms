$(document).ready(function()
{
    $(document).on('change', '#repo', function()
    {
        var _this = this;
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
                if(data.type == 'gitlab')
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
        $('#jenkinsServerTR #jkTask').remove();
        $('#jenkinsServerTR #jkTask_chosen').remove();
        $('#jenkinsServerTR .input-group').append("<div class='load-indicator loading'></div>");
        $.getJSON(createLink('jenkins', 'ajaxGetJenkinsTasks', 'jenkinsID=' + jenkinsID), function(tasks)
        {
            html = "<select id='jkTask' name='jkTask' class='form-control'>";
            for(taskKey in tasks)
            {
                var task = tasks[taskKey];
                html += "<option value='" + taskKey + "'>" + task + "</option>";
            }
            html += '</select>';
            $('#jenkinsServerTR .loading').remove();
            $('#jenkinsServerTR .input-group').append(html);

            $('#jenkinsServerTR #jkTask').chosen({drop_direction: 'auto'});
        })
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
    });

    $('#engine').change();

    $('#triggerType').change();
});
