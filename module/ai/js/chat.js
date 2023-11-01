$(function()
{
  function markBusy()
  {
    $('input[type=submit]').attr('readonly', true).addClass('busy');
    $('textarea[name=message]').attr('readonly', true).addClass('busy');
    $('#retry').addClass('busy');
  }

  function reset()
  {
    markBusy();
    $.ajax({
      url: createLink('ai', 'chat'),
      dataType: 'html',
      success: function(response)
      {
        $('.chat').html($($.parseHTML(response)).filter('.chat').html());
        init();
      }
    });
  }

  function retry()
  {
    markBusy();
    $.ajax({
      url: createLink('ai', 'chat'),
      type: 'POST',
      dataType: 'html',
      data: {retry: 'true', history: $('input[name=history]').val()},
      success: function(response)
      {
        $('.chat').html($($.parseHTML(response)).filter('.chat').html());
        init();
      }
    });
  }

  function init()
  {
    $('textarea[name=message]').keydown(function(e)
    {
      /* Send message on enter, allowing linebreak inputs. */
      if((e.keyCode == 13 || e.keyCode == 10))
      {
        if(e.ctrlKey || e.metaKey)
        {
          $('textarea[name=message]').val($('textarea[name=message]').val() + '\n');
        }
        else
        {
          $('form').submit();
        }
      }
    });
    $('form').on('submit', function(e)
    {
      /* Disable form and add message. */
      markBusy();
      $('.messages').prepend("<div class='message-container-user'><div class='message-content'>" + $('textarea[name=message]').val() + '</div></div>');

      /* Send message to server and re-render the chat. */
      e.preventDefault();
      $.ajax({
        url: createLink('ai', 'chat'),
        type: 'POST',
        data: {message: $('textarea[name=message]').val(), history: $('input[name=history]').val()},
        dataType: 'html',
        success: function(response)
        {
          $('.chat').html($($.parseHTML(response)).filter('.chat').html());
          init();
        }
      });
    });
    $('#reset').click(reset);
    $('#retry').click(retry);
    $('textarea[name=message]').focus();
  }
  init();
});
