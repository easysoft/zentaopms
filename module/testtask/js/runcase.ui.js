$(function()
{
    loadResult();
});
function loadResult()
{
    loadCurrentPage({url: resultsLink, selector: '#casesResults', partial: true});
}
