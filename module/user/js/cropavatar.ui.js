window.saveAvatar = function(e, data)
{
    $.ajaxSubmit({url: $.createLink('user', 'cropavatar', 'image=' + imageID), data});
}
