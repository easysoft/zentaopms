$(function()
{
    $('#projectList .transfer').click(function()
    {   
        var programID = $(this).attr('data-id');    
        var link = createLink('program', 'ajaxGetEnterLink', "programID=" + programID);
        $.post(link, function(pgmLink)
        {   
            location.href = pgmLink;
        })  
    })
})
