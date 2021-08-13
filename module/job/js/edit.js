$(document).ready(function()
{
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

            $('#jenkinsServerTR #jkTask').val(jkTask).chosen();
        })
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

    $('#engine').change();
    $('#jkServer').change();

    $('#triggerType').change();
});
