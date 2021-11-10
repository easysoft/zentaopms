$(function () {
  $('#repo').change(function () {
    repoID = $(this).val();
    jobUrl = createLink('mr', 'ajaxGetJobList', "repoID=" + repoID);
    $.get(jobUrl, function (response) {
      $('#job').html('').append(response);
      $('#job').chosen().trigger("chosen:updated");;
    });
  });

  $('#job').change(function () {
    jobID = $(this).val();
    compileUrl = createLink('mr', 'ajaxGetCompileList', "job=" + jobID);
    $.get(compileUrl, function (response) {
      $('#compile').html('').append(response);
      $('#compile').chosen().trigger("chosen:updated");;
    });
  });

});
