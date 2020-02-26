$('#repo').change(function()
{
    var repoID = $(this).val();
    var type   = 'Git';
    if(typeof(repoTypes[repoID]) != 'undefined') type = repoTypes[repoID];
    $('.svn-fields').toggleClass('hidden', type != 'Subversion');
    $('#repoType').val(type);
    if(type == 'Subversion')
    {
        $('#svnFolderBox').html("<div class='load-indicator loading'></div>");
        var params = 'repoID=' + repoID;
        if(jobRepo == repoID) params = 'repoID=' + repoID + '&path=' + encodeSVNFolder;
        $.getJSON(createLink('repo', 'ajaxGetSVNTags', params), function(svnTags)
        {
            var tags    = svnTags['tags'];
            var parents = svnTags['parent'];

            html = "<select id='svnFolder' name='svnFolder' class='form-control'>";
            for(tag in parents)
            {
                var info = parents[tag];
                html += "<option value='" + info['path'] + "' data-encodePath='" + info['encodePath'] + "'>" + info['path'] + "</option>";
            }

            for(tag in tags)
            {
                var info = tags[tag];
                html += "<option value='" + info['path'] + "' data-encodePath='" + info['encodePath'] + "'>" + info['path'] + "</option>";
            }
            html += '</select>';
            $('#svnFolderBox').html(html);
            $('#svnFolderBox #svnFolder').val(svnFolder).chosen();
        })
    }
})

$(document).on('change', '#svnFolder', function()
{
    var repoID      = $('#repo').val();
    var selectedTag = $(this).val();
    var encodePath  = $(this).find("option:selected").attr('data-encodePath');
    $('#svnFolderBox').html("<div class='load-indicator loading'></div>");
    $.getJSON(createLink('repo', 'ajaxGetSVNTags', 'repoID=' + repoID + '&path=' + encodePath), function(svnTags)
    {
        var tags    = svnTags['tags'];
        var parents = svnTags['parent'];

        html = "<select id='svnFolder' name='svnFolder' class='form-control'>";
        for(tag in parents)
        {
            var info = parents[tag];
            html += "<option value='" + info['path'] + "' data-encodePath='" + info['encodePath'] + "'>" + info['path'] + "</option>";
        }

        for(tag in tags)
        {
            var info = tags[tag];
            html += "<option value='" + info['path'] + "' data-encodePath='" + info['encodePath'] + "'>" + info['path'] + "</option>";
        }
        html += '</select>';
        $('#svnFolderBox').html(html);
        $('#svnFolderBox #svnFolder').val(selectedTag).chosen();
    })
})

$('#triggerType').change(function()
{
    var type = $(this).val();
    if(type == 'tag')
    {
        $('.comment-fields').addClass('hidden');
        $('.custom-fields').addClass('hidden');
    }
    else if(type == 'commit')
    {
        $('.comment-fields').removeClass('hidden');
        $('.custom-fields').addClass('hidden');
    }
    else if(type == 'schedule')
    {
        $('.comment-fields').addClass('hidden');
        $('.custom-fields').removeClass('hidden');
    }
});

$('#jenkins').change(function()
{
    var jenkinsID = $(this).val();
    $('#jenkinsJobBox').html("<div class='load-indicator loading'></div>");
    $.getJSON(createLink('jenkins', 'ajaxGetTasks', 'jenkinsID=' + jenkinsID), function(tasks)
    {
        html  = "<select id='jenkinsJob' name='jenkinsJob' class='form-control'>";
        for(taskKey in tasks)
        {
            var task = tasks[taskKey];
            html += "<option value='" + taskKey + "'>" + task + "</option>";
        }
        html += '</select>';
        $('#jenkinsJobBox').html(html);
        $('#jenkinsJobBox #jenkinsJob').chosen();
    })
})

$(function()
{
    $('#repo').change();
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
