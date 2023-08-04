function onChangeType(event)
{
    const type = $(event.target).val();

    if(type == 'external')
    {
        $('.externalPanel').removeClass('hidden');
        $('.storePanel').addClass('hidden');
        $('#type_external').prop('checked', true);
    }
    else
    {
        $('.storePanel').removeClass('hidden');
        $('.externalPanel').addClass('hidden');
        $('#type_store').prop('checked', true);
    }
}

function onChangeAppType(event)
{
    const appType = $(event.target).val();

    if(appType == 'Jenkins' || appType == 'SonarQube')
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
        case 'GitLab':
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
        case 'SonarQube':
            $('#createAppForm').attr('action', $.createLink('sonarqube', 'create'));
            $('#url').attr('placeholder', sonarqubeUrlTips);
            $('#account').attr('placeholder', sonarqubeAccountTips);
            break;
    }
}

function onChangeStoreAppType(event)
{
    if(typeof(event) == 'undefined')
    {
        var storeApp = defaultApp;
    }
    else
    {
        var storeApp = $('[name=storeAppType]').val();
    }

    $('#createStoreAppForm').attr('action', $.createLink('instance', 'install', 'appID=' + storeApp));

    var externalApps = ['GitLab', 'Gitea', 'Gogs', 'Jenkins', 'SonarQube'];
    var storeAppName = apps[storeApp];

    if(externalApps.indexOf(storeAppName) !== -1)
    {
        $('#createStoreAppForm input[name=type][value=external]').prop('disabled', false);
    }
    else
    {
        $('#createStoreAppForm input[name=type][value=external]').prop('disabled', true);
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
