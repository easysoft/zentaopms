if(typeof window.onSearchFormResult !== 'function') window.onSearchFormResult = function(formName, response)
{
    response.then(res => res.json())
        .then(json => {
            const $modal = $(formName).closest('.modal');
            if($modal.length) loadModal(json.load, $modal.attr('id'));
            else              loadPage(json.load);
        }).catch(console.error);
}
