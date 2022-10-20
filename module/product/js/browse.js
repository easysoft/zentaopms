$(function()
{
    if(typeof(rawModule) == 'undefined') rawModule = 'product';
    if(rawModule != 'projectstory')
    {
        $('#navbar .nav li').removeClass('active');
        $("#navbar .nav li[data-id=" + storyType + ']').addClass('active');
    }

    $(document).ready(function(){
        var $title = $('#storyList thead th.c-title');
        var headerWidth = $('#storyList thead th.c-title a').innerWidth();
        var buttonWidth = $('#storyList thead th.c-title button').innerWidth();
        if($title.width() < headerWidth + buttonWidth + 16) $title.width(headerWidth + buttonWidth + 30);
    });

    $('#storyList td.has-child .story-toggle').each(function()
    {
        var $td = $(this).closest('td');
        var labelWidth = 0;
        if($td.find('.label').length > 0) labelWidth = $td.find('.label').width();
        $td.find('a').eq(0).css('max-width', $td.width() - labelWidth - 60);
    });

    $('#toTaskButton').on('click', function()
    {
        var planID = $('#plan').val();
        if(planID)
        {
            parent.location.href = createLink('projectstory', 'importPlanStories', 'projectID=' + projectID + '&planID=' + planID + '&productID=' + productID);
        }
    })

    $(document).on('click', '.story-toggle', function(e)
    {
        var $toggle = $(this);
        var id = $(this).data('id');
        var isCollapsed = $toggle.toggleClass('collapsed').hasClass('collapsed');
        $toggle.closest('[data-ride="table"]').find('tr.parent-' + id).toggle(!isCollapsed);

        e.stopPropagation();
        e.preventDefault();
    });

    // Fix state dropdown menu position
    $('.c-stage > .dropdown').each(function()
    {
        var $this = $(this);
        var menuHeight = $(this).find('.dropdown-menu').outerHeight();
        var $tr = $this.closest('tr');
        var height = 0;
        while(height < menuHeight)
        {
            var $next = $tr.next('tr');
            if(!$next.length) break;
            height += $next.outerHeight;
        }
        if(height < menuHeight)
        {
            $this.addClass('dropup');
        }
    });

    toggleFold('#productStoryForm', unfoldStories, productID, 'product');

    adjustTableFooter();
    $('body').on('click', '#toggleFold', adjustTableFooter);
    $('body').on('click', '.icon.icon-angle-double-right', adjustTableFooter);

    /* Get checked stories. */
    $('#importToLib').on('click', function()
    {
        var storyIdList = '';
        $("input[name^='storyIdList']:checked").each(function()
        {
            storyIdList += $(this).val() + ',';
            $('#storyIdList').val(storyIdList);
        });
    });

    $('#batchUnlinkStory').click(function()
    {
        var storyIdList = '';
        $("input[name^='storyIdList']:checked").each(function()
        {
            storyIdList += $(this).val() + ',';
        });

        $.get(createLink('projectstory', 'batchUnlinkStory', 'projectID=' + projectID + '&storyIdList=' + storyIdList), function(data)
        {
            if(data)
            {
                $('#batchUnlinkStoryTip tbody').html(data);
                $('#batchUnlinkStoryTip').modal({show: true});
            }
            else
            {
                window.location.reload();
            }
        });
    });

    $('#batchUnlinkStoryTip .close, #confirmBtn').click(function()
    {
        window.location.reload();
    })

    /* The display of the adjusting sidebarHeader is synchronized with the sidebar. */
    $(".sidebar-toggle").click(function()
    {
        $("#sidebarHeader").toggle("fast");
    });
    if($("main").is(".hide-sidebar")) $("#sidebarHeader").hide();

    /* Shift key selection. */
    var isClickStoryToggle = false;
    var lastStorySelected  = '';
    $(".story-toggle").click(function()
    {
        isClickStoryToggle = true;
    });
    $("#storyList tbody").on("click","tr",function(e)
    {
      var nowCheckbox = $(this)['context'].cells[0].childNodes[0].childNodes[0];
      if(e.shiftKey)
      {
          nowStorySelected = nowCheckbox.value;
          if(lastStorySelected != '' && nowStorySelected != '' && lastStorySelected != nowStorySelected)
          {
              var isStartStorySelected = false;
              var isEndStorySelected   = false;
              $("input[name^='storyIdList']").each(function()
              {
                  isEndStorySelected   = ((nowStorySelected == $(this).val() || lastStorySelected == $(this).val()) && isStartStorySelected ) || isEndStorySelected ? true : false;

                  $(this)['context'].checked = $(this)['context'].checked || (isStartStorySelected && !isEndStorySelected) ? true : false;

                  isStartStorySelected = nowStorySelected == $(this).val() || lastStorySelected == $(this).val() || isStartStorySelected ? true : false;

                  if(isEndStorySelected) return;
              });
          }
      }
      else if(!isClickStoryToggle)
      {
          lastStorySelected = nowCheckbox.value;
      }
      isClickStoryToggle = false;
  });
});

/**
 * Adjust the table footer style.
 *
 * @access public
 * @return void
 */
function adjustTableFooter()
{
    if($('.main-col').height() < $(window).height())
    {
        $('.table.with-footer-fixed').css('margin-bottom', '0');
        $('.table-footer').removeClass('fixed-footer');
        $('.table-footer').css({"left":"0", "bottom":"0", "width":"unset"});
    }
}
