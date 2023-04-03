$(function()
{
    $('#docListForm .table .c-name').each(function()
    {
        var $this = $(this);
        if($this.find('.draft').length > 0)
        {
            $this.find('.doc-title').css('max-width', parseInt($this.width()) - parseInt($this.find('.draft').width()) - parseInt($this.find('.ajaxCollect').width()) - parseInt($this.find('.file-icon').width()) - 24);
        }
    });
});
