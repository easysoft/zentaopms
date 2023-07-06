$(function()
{
    loadResult();
});

/**
 * Load result.
 *
 * @access public
 * @return void
 */
function loadResult()
{
    loadCurrentPage({url: resultsLink, selector: '#casesResults', partial: true});
}
