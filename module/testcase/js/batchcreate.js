$(document).ready(function()
{
    for(i = 0; i < testcaseBatchCreateNum; i++) $("#story" + i).chosen({no_results_text: noResultsMatch});
});
