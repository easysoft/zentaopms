window.mailTips = function(mailExist)
{
    if(mailExist == 0)
    {
        zui.Modal.alert({
            size   : 'sm',
            message: {html: $('#noMail').html()}
        });
    }
    else
    {
        zui.Modal.confirm({
            actions: [
                {
                    text: testBtn,
                    key:  'confirm',
                },
                'cancel'
            ],
            message: {html: $('#hasMail').html()},
            onResult: (result) =>
            {
                if(result) loadPage($.createLink('mail', 'test'));
            }
        })
    }
}
