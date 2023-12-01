window.saveAvatar = function(e, data)
{
    $.ajaxSubmit({url: $.createLink('user', 'cropavatar', 'imageID=' + imageID), data});
}
