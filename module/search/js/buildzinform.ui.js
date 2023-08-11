window.onSearchFormResult = function(formName, response)
{
    response.then(res => res.json())
        .then(json => {
            const $modal = $(formName).closest('.modal');
            console.log('> onSearchFormResult', formName, $modal.length, json, $modal);
            if($modal.length) loadModal(json.load, $modal.attr('id'));
            else              loadPage(json.load);
        }).catch(console.error);
}
