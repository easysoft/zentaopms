window.startTestConnection = async () =>
{
    $('#zaiSettingForm').addClass('is-checking').addClass('is-checked').find('.form-actions .btn').attr('disabled', 'disabled');
    try
    {
        await $.getLib('md5.js', {root: config.webRoot + 'js/'});

        const formHelper = zui.formHelper('#zaiSettingForm');
        const host       = formHelper.getFieldVal('host');
        const port       = formHelper.getFieldVal('port');
        const appID      = formHelper.getFieldVal('appID');
        const appToken   = formHelper.getFieldVal('token');
        const adminToken = formHelper.getFieldVal('adminToken');
        const userID     = config.account;
        const storeConf  = {
            baseUrl: host ? ([window.location.protocol, '//', host].join('') + (port ? `:${port}` : '')) : '',
            appID: appID,
            userID: userID,
            token: appToken ? (() => {
                const expiredTime = (Math.floor(Date.now() / 1000) + 1000);
                return {
                    hash: md5(appToken + appID + userID + expiredTime),
                    userID,
                    appID,
                    expiredTime,
                };
            }) : '',
            adminToken: adminToken ? (() => {
                const expiredTime = (Math.floor(Date.now() / 1000) + 1000);
                return {
                    hash: md5(adminToken + appID + userID + expiredTime),
                    userID,
                    appID,
                    expiredTime,
                };
            }) : '',
        };
        const store  = new zui.ZAIStore(storeConf);
        const doctor = $('#zaiDoctor').zui();
        await doctor.$.run(store);
    }
    catch (error)
    {
        zui.Modal.showError({error: error});
    }
    $('#zaiSettingForm').removeClass('is-checking').find('.form-actions .btn').attr('disabled', null);
};
