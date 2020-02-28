$('#repo').change(function()
{
    var repoID = $(this).val();
    var type   = 'Git';
    if(typeof(repoTypes[repoID]) != 'undefined') type = repoTypes[repoID];
    $('.svn-fields').toggleClass('hidden', type != 'Subversion');
    $('#repoType').val(type);
    $('#triggerType option[value=tag]').html(type == 'Subversion' ? dirChange : buildTag).trigger('chosen:updated');
    $('#repo_chosen .chosen-single').attr('style', type == 'Subversion' ? 'border-right:0px' : '');
    if(type == 'Subversion')
    {
        $('#svnDirBox #svnDir').remove();
        $('#svnDirBox #svnDir_chosen').remove();
        $('#svnDirBox .input-group').append("<div class='load-indicator loading'></div>");
        var params = 'repoID=' + repoID;
        if(jobRepo == repoID) params = 'repoID=' + repoID + '&path=' + encodeSVNDir;
        $.getJSON(createLink('repo', 'ajaxGetSVNDirs', params), function(tags)
        {
            html = "<select id='svnDir' name='svnDir' class='form-control'>";
            for(path in tags)
            {
                var encodePath = tags[path];
                html += "<option value='" + path + "' data-encodePath='" + encodePath + "'>" + path + "</option>";
            }
            html += '</select>';
            $('#svnDirBox .loading').remove();
            $('#svnDirBox .input-group').append(html);
            $('#svnDirBox #svnDir').val(svnDir).chosen();
        })
    }
})

$(document).on('change', '#svnDir', function()
{
    var repoID      = $('#repo').val();
    var selectedTag = $(this).val();
    var encodePath  = $(this).find("option:selected").attr('data-encodePath');
    $('#svnDirBox #svnDir').remove();
    $('#svnDirBox #svnDir_chosen').remove();
    $('#svnDirBox .input-group').append("<div class='load-indicator loading'></div>");
    $.getJSON(createLink('repo', 'ajaxGetSVNDirs', 'repoID=' + repoID + '&path=' + encodePath), function(tags)
    {
        html = "<select id='svnDir' name='svnDir' class='form-control'>";
        for(path in tags)
        {
            var encodePath = tags[path];
            html += "<option value='" + path + "' data-encodePath='" + encodePath + "'>" + path + "</option>";
        }
        html += '</select>';
        $('#svnDirBox .loading').remove();
        $('#svnDirBox .input-group').append(html);
        $('#svnDirBox #svnDir').val(selectedTag).chosen();
    })
})

$('#triggerType').change(function()
{
    var type = $(this).val();
    $('.comment-fields').addClass('hidden');
    $('.custom-fields').addClass('hidden');
    if(type == 'commit')   $('.comment-fields').removeClass('hidden');
    if(type == 'schedule') $('.custom-fields').removeClass('hidden');
});

$('#jkHost').change(function()
{
    var jenkinsID = $(this).val();
    $('#jkJobBox #jkJob').remove();
    $('#jkJobBox #jkJob_chosen').remove();
    $('#jkJobBox .input-group').append("<div class='load-indicator loading'></div>");
    $.getJSON(createLink('jenkins', 'ajaxGetTasks', 'jenkinsID=' + jenkinsID), function(tasks)
    {
        html  = "<select id='jkJob' name='jkJob' class='form-control'>";
        for(taskKey in tasks)
        {
            var task = tasks[taskKey];
            html += "<option value='" + taskKey + "'>" + task + "</option>";
        }
        html += '</select>';
        $('#jkJobBox .loading').remove();
        $('#jkJobBox .input-group').append(html);
        $('#jkJobBox #jkJob').val(jkJob).chosen();
    })
})

$(function()
{
    $('#repo').change();
    $('#jkHost').change();
    $('#triggerType').change();
});

function execJob(id)
{
    $.ajax(
    {
        type: "POST",
        url: createLink('integration', 'exec', 'id=' + id),
        data: {},
        datatype: "json",
        success: function(data)
        {
            $('.exe-job-button').tooltip('show', sendExec);
        }
    });
}
