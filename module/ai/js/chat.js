$(function()
{
  function markBusy()
  {
    $('input[type=submit]').attr('readonly', true).addClass('busy');
    $('textarea[name=message]').attr('readonly', true).addClass('busy');
    $('#retry').addClass('busy');
    $('#reset').addClass('busy');
  }

  function reset(e)
  {
    if($(e.target).hasClass('busy') || $(e.target).hasClass('disabled')) return;
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

  function retry(e)
  {
    if($(e.target).hasClass('busy')) return;
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
        if($(this).hasClass('busy')) return;

        if(e.ctrlKey || e.metaKey)
        {
          $('textarea[name=message]').val($('textarea[name=message]').val() + '\n');
          $('textarea[name=message]').trigger('input');
        }
        else
        {
          if($('textarea[name=message]').val().trim().length == 0)
          {
            e.preventDefault();
            return;
          }
          $('form').submit();
        }
      }
    });
    $('form').on('submit', function(e)
    {
      /* Disable form and add message. */
      markBusy();
      const message = $('textarea[name=message]').val().trim();
      $('.messages').prepend("<div class='message-container-user'><div class='message-content'>" + message + '</div></div>');

      /* Send message to server and re-render the chat. */
      e.preventDefault();
      $.ajax({
        url: createLink('ai', 'chat'),
        type: 'POST',
        data: {message, history: $('input[name=history]').val()},
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
    $('textarea[name=message]').on('input', function()
    {
      /* Toggle send button. */
      const hasMessage = $(this).val().trim().length > 0;
      $('input[type=submit]').attr('disabled', !hasMessage).toggleClass('disabled', !hasMessage);

      /* Resize textarea to fit content. */
      const clientHeight = +$(this).prop('clientHeight');
      const scrollHeight = +$(this).prop('scrollHeight');
      if(clientHeight < scrollHeight)
      {
        if(clientHeight < 150)
        {
          $(this).css('height', (scrollHeight + 2) + 'px');
        }
        else
        {
          $(this).addClass('has-scrollbar');
        }
      }
    });
  }
  init();
});
