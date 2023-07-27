window.switchAccount = function(account)
{
    link = $.createLink('user', method, 'account=' + account);
    if(method == 'dynamic') link = $.createLink('user', method, 'account=' + account + '&period=' + pageParams.period);
    if(method == 'todo')    link = $.createLink('user', method, 'account=' + account + '&type=' + pageParams.type);
    if(method == 'story')   link = $.createLink('user', method, 'account=' + account + '&storyType=' + pageParams.storyType);

    loadPage(link);
};

/**
 * Switch account
 *
 * @param  string $account
 * @param  string $method
 * @access public
 * @return void
 */
$(document).ready(function()
{
    var verifyEncrypted = false;
    $('#verifyPassword').on('change', function(){verifyEncrypted = false});
    $('#verifyPassword').closest('form').find('button[type="submit"]').on('click', function()
    {
        var password = $('input#verifyPassword').val().trim();
        var rand     = $('input[name=verifyRand]').val();
        if(!verifyEncrypted && password) $('input#verifyPassword').val(md5(md5(password) + rand));
        verifyEncrypted = true;
    });
});

/**
 * Update groups when visions change.
 *
 * @param  event  $event
 * @access public
 * @return void
 */
function changeVision(event)
{
    var visions = [];
    $('input[name="visions[]"]:checked').each(function()
    {
        visions.push($(this).val());
    });

    const link  = $.createLink('user', 'ajaxGetGroup', 'visions=' + visions);
    $.get(link, function(data)
    {
        let group        = $('[name^="group"]').val();
        let $groupPicker = $('[name^="group"]').zui('picker');
        if(data)
        {
            data = JSON.parse(data);
            $groupPicker.render({items: data});
            $groupPicker.$.changeState({value: group});
        }
    });
}
