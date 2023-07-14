function redirectSetting(event)
{
    const $target = $(event.target);
    if($target.hasClass('icon-help')) return false;

    const $box = $target.closest('.setting-box');
    if($box.length == 0) return false;

    openUrl($box.data('url'));
}
