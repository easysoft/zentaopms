$(function()
{
    $('#program').change(function()
    {
        var programID = $(this).val();
        if(programID != oldProgramID && projects)
        {
            var singleLinkProjects   = '';
            var multipleLinkProjects = '';
            for(var i in projects)
            {
                var project = projects[i];

                if(Object.getOwnPropertyNames(project.product).length == 1)
                {
                    singleLinkProjects += project.name + ',';
                }
                else
                {
                    multipleLinkProjects += project.name + ',';
                }
            }

            PGMChangeTip     = PGMChangeTip.replace("%s", singleLinkProjects);
            confirmChangePGM = confirmChangePGM.replace("%s", multipleLinkProjects);
            if(singleLinkProjects) alert(PGMChangeTip);
            if(multipleLinkProjects && confirm(confirmChangePGM))
            {
                $('#comfirmChange').val('yes');
            }
            else
            {
                $('#comfirmChange').val('no');
            }
        }
    })
});
