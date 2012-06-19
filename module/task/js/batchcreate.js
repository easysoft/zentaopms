$(function() {
    for(i=0; i<batchCreateNum; i++) $("#story" + i).chosen({no_results_text: noResultsMatch});
})
