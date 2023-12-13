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

    if(appType == 'jenkins' || appType == 'sonarqube' || appType == 'nexus')
    {
        $('div.jenkins').removeClass('hidden');
        if(appType == 'jenkins')
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
        case 'gitlab':
            $('#createAppForm').attr('action', $.createLink('gitlab', 'create'));
            $('#url').attr('placeholder', gitlabUrlTips);
            $('#token').attr('placeholder', gitlabTokenTips);
            break;
        case 'gitea':
            $('#createAppForm').attr('action', $.createLink('gitea', 'create'));
            break;
        case 'gogs':
            $('#createAppForm').attr('action', $.createLink('gogs', 'create'));
            break;
        case 'jenkins':
            $('#createAppForm').attr('action', $.createLink('jenkins', 'create'));
            $('#token').attr('placeholder', jenkinsTokenTips);
            $('#password').attr('placeholder', jenkinsPasswordTips);
            break;
        case 'sonarqube':
            $('#createAppForm').attr('action', $.createLink('sonarqube', 'create'));
            $('#url').attr('placeholder', sonarqubeUrlTips);
            $('#account').attr('placeholder', sonarqubeAccountTips);
            break;
        case 'nexus':
            $('#createAppForm').attr('action', $.createLink('instance', 'createExternalApp', 'type=nexus'));
            break;
    }
}

function onChangeStoreAppType(event)
{
    var storeApp = appID;
    if(!storeApp)
    {
        if(typeof(event) == 'undefined')
        {
            storeApp = defaultApp;
        }
        else
        {
            storeApp = $('[name=storeAppType]').val();
        }
    }

    $('#createStoreAppForm').data('appid', storeApp);
    $('#createStoreAppForm').attr('action', $.createLink('instance', 'install', 'appID=' + storeApp));

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
    if(storeApp)
    {
        $.get($.createLink('space', 'getStoreAppInfo', 'appID=' + storeApp), function(response)
        {
            var app = JSON.parse(response);

            $('#app_version').val(app.app_version);
            if(showVersion === true)
            {
                $('#version').picker({items: app.versionList, name: 'version', required: true});
                setTimeout(() =>
                {
                    $('#version').picker('setValue', app.versionList[0].value);
                }, 300);
            }
            else
            {
                $('#version').val(app.version);
            }
            if((app.dependencies.mysql && mysqlList) || (app.dependencies.postgresql && pgList && pgList.length > 0))
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

window.alertResource = function()
{
    zui.Modal.confirm({'message': resourceAlert}).then((res) =>
    {
        if(res)
        {
            var appID = $('#createStoreAppForm').data('appid');
            $('#createStoreAppForm').attr('action', $.createLink('instance', 'install', 'appID=' + appID + '&checkResource=false'));
            $('#createStoreAppForm .form-row .toolbar button[type=submit]').trigger('click');
        }
    });
}

$(function()
{
    onChangeStoreAppType();
    $('div.dbService .form-label').removeClass('required');
});
