function onChangeType(event)
{
    const type = $(event.target).val();
}

function onChangeAppType(event)
{
    const appType = $(event.target).val();

    if(appType == 'Jenkins' || appType == 'Sonarqube')
    {
        $('div.jenkins').removeClass('hidden');
        if(appType == 'Jenkins')
        {
            $('div.token').removeClass('hidden');
            $('div.password .form-label').removeClass('required');
        }
        else
        {
            $('div.token').addClass('hidden');
            $('div.password .form-label').addClass('required');
        }
    }
    else
    {
        $('div.jenkins').addClass('hidden');
        $('div.token').removeClass('hidden');
    }

    $('#url').attr('placeholder', '');
    $('#token').attr('placeholder', '');
    $('#account').attr('placeholder', '');
    $('#password').attr('placeholder', '');
    switch(appType)
    {
        case 'Gitlab':
            $('#createAppForm').attr('action', $.createLink('gitlab', 'create'));
            $('#url').attr('placeholder', gitlabUrlTips);
            $('#token').attr('placeholder', gitlabTokenTips);
            break;
        case 'Gitea':
            $('#createAppForm').attr('action', $.createLink('gitea', 'create'));
            break;
        case 'Gogs':
            $('#createAppForm').attr('action', $.createLink('gogs', 'create'));
            break;
        case 'Jenkins':
            $('#createAppForm').attr('action', $.createLink('jenkins', 'create'));
            $('#token').attr('placeholder', jenkinsTokenTips);
            $('#password').attr('placeholder', jenkinsPasswordTips);
            break;
        case 'Sonarqube':
            $('#createAppForm').attr('action', $.createLink('sonarqube', 'create'));
            $('#url').attr('placeholder', sonarqubeUrlTips);
            $('#account').attr('placeholder', sonarqubeAccountTips);
            break;
    }
}
