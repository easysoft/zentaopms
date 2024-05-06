$(document).ready(function(){
    $.get($.createLink("mr", "ajaxSyncMRs", "repoID=" + repoID));
});
