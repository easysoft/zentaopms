$(function ()
  {
    $('#repoID').change(function ()
      {
        repoID = $(this).val();
        jobUrl = createLink('mr', 'ajaxGetJobList', "repoID=" + repoID);
        $.get(jobUrl, function (response)
          {
            $('#jobID').html('').append(response);
            $('#jobID').chosen().trigger("chosen:updated");;
          });
      });

    $('#jobID').change(function ()
      {
        jobID = $(this).val();
        compileUrl = createLink('mr', 'ajaxGetCompileList', "job=" + jobID);
        $.get(compileUrl, function (response)
          {
            $('#compile').html('').append(response);
            $('#compile').chosen().trigger("chosen:updated");;
          });
      });

  });
