$(document).ready(function()
{
    processCheckbox();

    $.get(createLink('repo', 'ajaxSyncLatestCommit', "repoID=" + repoID), function(data)
    {
        if(data == 'finished')
        {
            $('#logList').load(location.href + ' #logList', function()
            {
                $('#logList #logList thead').unwrap();

                if($("input:checkbox[name='revision[]']:checked").length < 2)
                {
                    $("input:checkbox[name='revision[]']:lt(2)").attr('checked', 'checked');
                }
                $("input:checkbox[name='revision[]']").each(function(){ if(!$(this).is(':checked')) $(this).attr("disabled","disabled")});
                $("input:checkbox[name='revision[]']").click(function(){
                    var checkNum = $("input:checkbox[name='revision[]']:checked").length;
                    if (checkNum >= 2) 
                    {
                        $("input:checkbox[name='revision[]']").each(function(){ if(!$(this).is(':checked')) $(this).attr("disabled","disabled")});
                    }
                    else
                    {
                        $("input:checkbox[name='revision[]']").each(function(){$(this).attr("disabled", false)});
                    }
                });

                processCheckbox();
            });
        }
    });
});

function processCheckbox()
{
    $("input:checkbox[name='revision[]']").each(function()
    {
        $(this).click(function()
        {
            var checkNum = $("input:checkbox[name='revision[]']:checked").length;
            if (checkNum >= 2)
            {
                $("input:checkbox[name='revision[]']").each(function(){if($(this).attr('checked') == false) $(this).attr("disabled","disabled")});
            } 
            else
            {
                $("input:checkbox[name='revision[]']").each(function(){if($(this).attr('checked') == false) $(this).attr("enabled","enabled")});
            }
        });
    });
}
