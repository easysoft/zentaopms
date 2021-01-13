$(function()
{
    $('#program').change(function()
    {
        var programID = $(this).val();
        if(programID != oldProgramID && projects)
        {
            var singleLinkProjects = '';
            var mulLinkProjects = '';
            for(var i in projects)
            {
                var project = projects[i];

                if(Object.getOwnPropertyNames(project.product).length == 1)
                {
                    singleLinkProjects += project.name + ',';
                }
                else
                {
                    mulLinkProjects += project.name + ',';
                }
            }

            PGMChangeTip     = PGMChangeTip.replace("%s", singleLinkProjects);
            confirmChangePGM = confirmChangePGM.replace("%s", mulLinkProjects);
            if(singleLinkProjects) alert(PGMChangeTip);
            if(mulLinkProjects && confirm(confirmChangePGM))
            {
                $('#comfirmChange').val('yes');
            }
        }
    })
});
