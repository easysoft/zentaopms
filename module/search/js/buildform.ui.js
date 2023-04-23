window.onDeleteQuery = function(event, queryID)
{
    event.stopPropagation();

    var deleteQueryURL = onDeleteQueryURL;
    fetch(deleteQueryURL.replace('myQueryID', queryID), {method:'POST'})
        .then((response) => {
            if (!response.ok) throw new Error('HTTP error! Status: ' + response.status);
            return response.text();
        })
        .then((text) => {
            if(text === 'success') event.target.closest('div').remove();
            else throw new Error('Failed: ' + text);
        });
}
