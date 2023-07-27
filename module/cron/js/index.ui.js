window.refreshURL = function(obj)
{
    $this = $(obj);
    $.get($this.data('href'));
    loadPage($.createLink('cron', 'index'));
    return false;
}
