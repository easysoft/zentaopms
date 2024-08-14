window.collectDoc = function(id)
{
    $.ajaxSubmit(
    {
        url: $.createLink('doc', 'collect', `objectID=${id}`),
        onSuccess: function(result)
        {
            const isStar = result.status === 'yes';
            $('#docPanel .doc-collect-btn > img').each(function()
            {
                this.src = this.src.replace(`/${isStar ? 'star-empty' : 'star'}.svg`, `/${isStar ? 'star' : 'star-empty'}.svg`);
            });
            return false;
        }
    });
};

$(function()
{
    if($.cookie.get('hiddenOutline') == 'true') $("#docPanel").addClass("show-outline")
});

$("#docContent").on('enterFullscreen', () => {
    $('.right-icon').attr('id', 'right-icon').removeClass('right-icon');
});

$("#docContent").on('exitFullscreen', () => {
    $('#right-icon').addClass('right-icon');
});
