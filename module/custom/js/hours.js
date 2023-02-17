$(function()
{
    $("#sidebar .nav [data-toggle='tab']").click(function()
    {
        $(this).parent().find('a').removeClass('active');
        $(this).addClass('active');
        var type = $(this).hasClass('hours') ? 'hours' : 'weekend';
        $('#type').val(type);
    });

      $('#restDayBox').toggleClass('hidden', $('[name=weekend]:checked').val() != 1)
      $('[name=weekend]').change(function()
      {
          $('#restDayBox').toggleClass('hidden', $(this).val() != 1)
      })
})
