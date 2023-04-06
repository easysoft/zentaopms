window.loadPage = function loadPage(url, callback)
{
    url = url || defaultUrl;
    console.log('> loadPage', url);
    const selector = $('#main').length ? '#main' : 'body';
    fetch(url, {headers: {'X-ZIN-Options': JSON.stringify({selector: selector, inner: true})}}).then((res) => res.text()).then((html) =>
    {
        $(selector).html(html);
        if(callback) callback();
    });
}

/* Transfer click event to parent */
$(document).on('click', (e) =>
{
    window.parent.$('body').trigger('click');

    const $a = $(e.target).closest('a');
    if(!$a.length || $a.data('toggle') || $a.hasClass('not-in-app') || $a.attr('target') === '_blank') return;

    const url = $a.attr('href');
    if(!url) return;
    e.preventDefault();
    loadPage(url);
});

loadPage();
