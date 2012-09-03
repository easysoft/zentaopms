$(function() {
    $("#story").chosen({no_results_text: noResultsMatch});
    $("#mailto").autocomplete(userList, { multiple: true, mustMatch: true});
})
