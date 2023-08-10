window.onSubmit = function(response)
{
    response.then(res => res.json())
        .then(json => {loadPage(json.load)})
        .catch(console.log);
}
