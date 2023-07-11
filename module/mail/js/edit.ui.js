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
                    url:     $.createLink('mail', 'test'),
                    text:    testBtn,
                    btnType: 'primary btn-wide',
                },
                'cancel'
            ],
            message: {html: $('#hasMail').html()}
        })
    }
}
