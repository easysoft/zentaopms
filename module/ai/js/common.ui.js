window.getTestingLocation = function(id)
{
    const promise = new Promise((resolve, reject) =>
    {
        $.getJSON($.createLink('ai', 'ajaxTestPrompt', `promptID=${id}`), data =>
        {
            const info = (data && typeof data === 'object') ? data : null;
            if(info && info.data)
            {
                try
                {
                    const testing = parent.executeZentaoPrompt(info.data, true);
                    if(testing && typeof testing.then === 'function')
                    {
                        testing.then(resolve).catch(error =>
                        {
                            zui.Messager.fail(String(error));
                            reject(error);
                        });
                    }
                    else
                    {
                        resolve(testing);
                    }
                }
                catch(error)
                {
                    zui.Messager.fail(String(error));
                    reject(error);
                }
                return;
            }

            if(info && info.message) zui.Messager.fail(info.message);
            else zui.Messager.fail();
            reject(info);
        }).catch(error =>
        {
            zui.Messager.fail(String(error));
            reject(error);
        });
    });

    promise.always = callback =>
    {
        promise.then(callback).catch(callback);
        return promise;
    };

    return promise;
};

window.goPromptTesting = function(id)
{
    return getTestingLocation(id);
};
