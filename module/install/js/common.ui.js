window.ajaxInstallEvent = function(location = '', entrance = '')
{
    $.getLib(config.webRoot + 'js/fingerprint/fingerprint.js', {root: false}, async function()
    {
        const agent     = typeof(FingerprintJS) !== 'undefined' ? await FingerprintJS.load() : '';
        let fingerprint = agent ? await agent.get() : '';
        fingerprint     = fingerprint ? fingerprint.visitorId : '';
        let eventUrl    = $.createLink('misc', 'installEvent');
        if(entrance === 'index') eventUrl = eventUrl.replace('install.php', 'index.php');
        $.ajax({url: eventUrl, type: "post", data: {fingerprint, location}, timeout: 2000});
    });
}
