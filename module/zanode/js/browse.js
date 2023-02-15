if(showFeature)
{
    var encodedHelpPageUrl = encodeURIComponent('https://www.zentao.net/book/zentaopms/978.html?fullScreen=zentao');
    var urlForNewTab = webRoot + '#app=help&url=' + encodedHelpPageUrl;
    window.open(urlForNewTab)
}

$('#helpTab').click(function()
{
    var encodedHelpPageUrl = encodeURIComponent('https://www.zentao.net/book/zentaopms/978.html?fullScreen=zentao');
    var urlForNewTab = webRoot + '#app=help&url=' + encodedHelpPageUrl;
    window.open(urlForNewTab)
})
