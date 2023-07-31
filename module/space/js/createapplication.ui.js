function onChangeType(event)
{
    const type = $(event.target).val();

    if(type == 'external')
    {
        $('.externalPanel').removeClass('hidden');
        $('.storePanel').addClass('hidden');
        $('#typeexternal').prop('checked', true);
    }
    else
    {
        $('.storePanel').removeClass('hidden');
        $('.externalPanel').addClass('hidden');
        $('#storetypestore').prop('checked', true);
    }
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

function onChangeStoreAppType()
{
    var storeApp = $('[name=storeAppType]').val();
    if(!storeApp)
    {
        for(id in apps)
        {
            storeApp = id;
            break;
        }
    }
    $('#createStoreAppForm').attr('action', $.createLink('instance', 'install', 'appID=' + storeApp));

    var externalApps = ['GitLab', 'Gitea', 'Gogs', 'Jenkins', 'SonarQube'];
    var storeAppName = apps[storeApp];
    if(externalApps.indexOf(storeAppName) !== -1)
    {
        $('#storetypeexternal').prop('disabled', false);
    }
    else
    {
        $('#storetypeexternal').prop('disabled', true);
    }

    toggleLoading('#app_version', true);
    toggleLoading('#dbService', true);
    $.get($.createLink('space', 'getStoreAppInfo', 'appID=' + storeApp), function(response)
    {
        var app = JSON.parse(response);

        $('#app_version').val(app.app_version);
        $('#version').val(app.version);
        $('#customName').val(app.alias);
        if((app.dependencies.mysql && mysqlList) || (app.dependencies.postgresql && pgList))
        {
            $('div.dbType').removeClass('hidden');
            $('[name=dbService]').prop('disabled', false);

            var dbServiceItems = [];
            var dbService = (app.dependencies.mysql && mysqlList) ? mysqlList : pgList;
            for(i in dbService)
            {
                dbServiceItems.push({'text': dbService[i].alias, 'value': dbService[i].name});
            }
            $('#dbService').zui('picker').render({items: dbServiceItems});
        }
        else
        {
            $('div.dbType').addClass('hidden');
            $('[name=dbService]').prop('disabled', true);
        }

        toggleLoading('#app_version', false);
        toggleLoading('#dbService', false);
    });
}

function onChangeDbType(event)
{
    const dbType = $(event.target).val();
    if(dbType == 'sharedDB')
    {
        $('div.dbService').removeClass('hidden');
        $('[name=dbService]').prop('disabled', false);
    }
    else
    {
        $('div.dbService').addClass('hidden');
        $('[name=dbService]').prop('disabled', true);
    }
}

$(function()
{
    onChangeStoreAppType();
    $('div.dbService .form-label').removeClass('required');
});
