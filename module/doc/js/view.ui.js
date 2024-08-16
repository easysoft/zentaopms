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

window.scrollIntoDoc = function($item)
{
    const level    = $item.data('level');
    const index    = $item.data('index');
    const text     = $item.text();
    const editor   = $('#docEditor zen-editor')[0];
    const headings = editor.shadowRoot.querySelector('zen-editor-core').shadowRoot.querySelector('zen-editor-content').querySelectorAll('h1,h2,h3,h4,h5,h6');
    let heading = headings[index];
    if(!heading || heading.textContent !== text || heading.tagName !== `H${level}`)
    {
        heading = Array.from(headings).find(h => h.textContent === text && h.tagName === `H${level}`);
    }
    if(heading) heading.scrollIntoView({behavior: 'smooth', block: 'start'});
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
