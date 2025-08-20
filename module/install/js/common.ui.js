window.ajaxInstallEvent = function(location = '')
{
    $.getLib(config.webRoot + 'js/fingerprint/fingerprint.js', {root: false}, async function()
    {
        const agent     = typeof(FingerprintJS) !== 'undefined' ? await FingerprintJS.load() : '';
        let fingerprint = agent ? await agent.get() : '';
        fingerprint     = fingerprint ? fingerprint.visitorId : '';
        $.ajax({url: $.createLink('misc', 'installEvent'), type: "post", data: {fingerprint, location}, timeout: 2000});
    });
}
