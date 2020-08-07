/**
 * Set the ping url.
 *
 * @access public
 * @return void
 */
function setPing()
{
    $('#hiddenwin').attr('src', createLink('misc', 'ping'));
}

/**
 * Disable the submit button when submit form.
 *
 * @access public
 * @return void
 */
function setForm()
{
    var formClicked = false;
    $('form').submit(function()
    {
        submitObj   = $(this).find(':submit');
        if($(submitObj).size() >= 1)
        {
            var isBtn = submitObj.prop('tagName') == "BUTTON";
            submitLabel = isBtn ? $(submitObj).html() : $(submitObj).attr('value');
            $(submitObj).attr('disabled', 'disabled');
            var submitting = submitObj.attr('data-submitting') || lang.submitting;
            if(isBtn) submitObj.text(submitting);
            else $(submitObj).attr('value', submitting);
            formClicked = true;
        }
    });

    $("body").click(function()
    {
        if(formClicked)
        {
            $(submitObj).removeAttr('disabled');
            if(submitObj.prop('tagName') == "BUTTON")
            {
                submitObj.text(submitLabel);
            }
            else
            {
                $(submitObj).attr('value', submitLabel);
            }
            $(submitObj).removeClass('button-d');
        }
        formClicked = false;
    });
}

/**
 * Set form action and submit.
 *
 * @param  url    $actionLink
 * @param  string $hiddenwin 'hiddenwin'
 * @access public
 * @return void
 */
function setFormAction(actionLink, hiddenwin, obj)
{
    $form = typeof(obj) == 'undefined' ? $('form') : $(obj).closest('form');
    if(hiddenwin) $form.attr('target', hiddenwin);
    else $form.removeAttr('target');

    $form.attr('action', actionLink);

    // Check safari for bug #1000, see http://pms.zentao.net/bug-view-1000.html
    var userAgent = navigator.userAgent;
    var isSafari = userAgent.indexOf('AppleWebKit') > -1 && userAgent.indexOf('Safari') > -1 && userAgent.indexOf('Chrome') < 0;
    if(isSafari)
    {
        var idPreffix = 'checkbox-fix-' + $.zui.uuid();
        $form.find('[data-fix-checkbox]').remove();
        $form.find('input[type="checkbox"]:not(.rows-selector)').each(function()
        {
            var $checkbox = $(this);
            var checkboxId = idPreffix + $checkbox.val();
            $checkbox.clone().attr('data-fix-checkbox', checkboxId).css('display', 'none').after('<div id="' + checkboxId + '"/>').appendTo($form);
        });
    }
    $form.submit();
}

/**
 * Set the max with of image.
 *
 * @access public
 * @return void
 */
function setImageSize(image, maxWidth, maxHeight)
{
    var $image = $(image);
    if($image.parent().prop('tagName').toLowerCase() == 'a') return;

    /* If not set maxWidth, set it auto. */
    if(!maxWidth)
    {
        bodyWidth = $('body').width();
        maxWidth  = bodyWidth - 470; // The side bar's width is 336, and add some margins.
    }
    if(!maxHeight) maxHeight = $(top.window).height();

    setTimeout(function()
    {
        maxHeightStyle = $image.height() > 0 ? 'max-height:' + maxHeight + 'px' : '';
        if($image.width() > 0 && $image.width() > maxWidth) $image.attr('width', maxWidth);
        $image.wrap('<a href="' + $image.attr('src') + '" style="display:inline-block;position:relative;overflow:hidden;' + maxHeightStyle + '" target="_blank"></a>');
        if($image.height() > 0 && $image.height() > maxHeight) $image.closest('a').append("<a href='###' class='showMoreImage' onclick='showMoreImage(this)'>" + lang.expand + " <i class='icon-angle-down'></i></a>");
    }, 50);
}

/**
 * Show more image when image is too height.
 *
 * @param  obj $obj
 * @access public
 * @return void
 */
function showMoreImage(obj)
{
    $(obj).parents('a').css('max-height', 'none');
    $(obj).remove();
}

/**
 * Set mailto list from a contact list..
 *
 * @param  string $mailto
 * @param  int    $contactListID
 * @access public
 * @return void
 */
function setMailto(mailto, contactListID)
{
    link = createLink('user', 'ajaxGetContactUsers', 'listID=' + contactListID);
    $.get(link, function(users)
    {
        $('#' + mailto).replaceWith(users);
        $('#' + mailto + '_chosen').remove();
        $('.picker').remove();

        if($("[data-pickertype='remote']").length == 0 && $('.picker-select').length == 0)
        {
            $('#' + mailto).chosen();
        }
        else
        {
            $('#' + mailto + "[data-pickertype!='remote']").picker({chosenMode: true});
            $("[data-pickertype='remote']").each(function()
            {
                var pickerremote = $(this).attr('data-pickerremote');
                $(this).picker({chosenMode: true, remote: pickerremote});
            });
        }
    });
}

/**
 * Ajax get contacts.
 *
 * @param  obj $obj
 * @access public
 * @return void
 */
function ajaxGetContacts(obj)
{
    link = createLink('user', 'ajaxGetContactList');
    $.get(link, function(contacts)
    {
        if(!contacts) return false;

        $inputgroup = $(obj).closest('.input-group');
        $inputgroup.find('.input-group-btn').remove();
        $inputgroup.append(contacts);
        $inputgroup.find('select:last').chosen().fixInputGroup();
    });
}

/**
 * add one option of a select to another select.
 *
 * @param  string $SelectID
 * @param  string $TargetID
 * @access public
 * @return void
 */
function addItem(SelectID,TargetID)
{
    ItemList = document.getElementById(SelectID);
    Target   = document.getElementById(TargetID);
    for(var x = 0; x < ItemList.length; x++)
    {
        var opt = ItemList.options[x];
        if (opt.selected)
        {
            flag = true;
            for (var y=0;y<Target.length;y++)
            {
                var myopt = Target.options[y];
                if (myopt.value == opt.value)
                {
                    flag = false;
                }
            }
            if(flag)
            {
                Target.options[Target.options.length] = new Option(opt.text, opt.value, 0, 0);
            }
        }
    }
}

/**
 * Remove one selected option from a select.
 *
 * @param  string $SelectID
 * @access public
 * @return void
 */
function delItem(SelectID)
{
    ItemList = document.getElementById(SelectID);
    for(var x=ItemList.length-1;x>=0;x--)
    {
        var opt = ItemList.options[x];
        if (opt.selected)
        {
            ItemList.options[x] = null;
        }
    }
}

/**
 * move one selected option up from a select.
 *
 * @param  string $SelectID
 * @access public
 * @return void
 */
function upItem(SelectID)
{
    ItemList = document.getElementById(SelectID);
    for(var x=1;x<ItemList.length;x++)
    {
        var opt = ItemList.options[x];
        if(opt.selected)
        {
            tmpUpValue = ItemList.options[x-1].value;
            tmpUpText  = ItemList.options[x-1].text;
            ItemList.options[x-1].value = opt.value;
            ItemList.options[x-1].text  = opt.text;
            ItemList.options[x].value = tmpUpValue;
            ItemList.options[x].text  = tmpUpText;
            ItemList.options[x-1].selected = true;
            ItemList.options[x].selected = false;
            break;
        }
    }
}

/**
 * move one selected option down from a select.
 *
 * @param  string $SelectID
 * @access public
 * @return void
 */
function downItem(SelectID)
{
    ItemList = document.getElementById(SelectID);
    for(var x=0;x<ItemList.length;x++)
    {
        var opt = ItemList.options[x];
        if(opt.selected)
        {
            tmpUpValue = ItemList.options[x+1].value;
            tmpUpText  = ItemList.options[x+1].text;
            ItemList.options[x+1].value = opt.value;
            ItemList.options[x+1].text  = opt.text;
            ItemList.options[x].value = tmpUpValue;
            ItemList.options[x].text  = tmpUpText;
            ItemList.options[x+1].selected = true;
            ItemList.options[x].selected = false;
            break;
        }
    }
}

/**
 * select all items of a select.
 *
 * @param  string $SelectID
 * @access public
 * @return void
 */
function selectItem(SelectID)
{
    ItemList = document.getElementById(SelectID);
    for(var x=ItemList.length-1;x>=0;x--)
    {
        var opt = ItemList.options[x];
        opt.selected = true;
    }
}

/**
 * Delete item use ajax.
 *
 * @param  string url
 * @param  string replaceID
 * @param  string notice
 * @access public
 * @return void
 */
function ajaxDelete(url, replaceID, notice)
{
    if(confirm(notice))
    {
        $.ajax(
        {
            type:     'GET',
            url:      url,
            dataType: 'json',
            success:  function(data)
            {
                if(data.result == 'success')
                {
                    var $table = $('#' + replaceID).closest('[data-ride="table"]');
                    if($table.length)
                    {
                        var table = $table.data('zui.table');
                        if(table)
                        {
                            table.options.replaceId = replaceID;
                            return table.reload();
                        }
                    }
                    $.get(document.location.href, function(data)
                    {
                        if(!($(data).find('#' + replaceID).length)) location.reload();
                        $('#' + replaceID).html($(data).find('#' + replaceID).html());
                        if(typeof sortTable == 'function') sortTable();
                        $('#' + replaceID).find('[data-toggle=modal], a.iframe').modalTrigger();
                        if($('#' + replaceID).find('table.datatable').length) $('#' + replaceID).find('table.datatable').datatable();
                        $('.table-footer [data-ride=pager]').pager();
                    });
                }
                else if(data.result == 'fail' && typeof(data.message) == 'string')
                {
                    bootbox.alert(data.message);
                }
            }
        });
    }
}

/**
 * Judge the string is a integer number
 *
 * @access public
 * @return bool
 */
function isNum(s)
{
    if(s!=null)
    {
        var r, re;
        re = /\d*/i;
        r = s.match(re);
        return (r == s) ? true : false;
    }
    return false;
}

/**
 * Start cron.
 *
 * @access public
 * @return void
 */
function startCron(restart)
{
    if(typeof(restart) == 'undefined') restart = 0;
    $.ajax({type:"GET", timeout:100, url:createLink('cron', 'ajaxExec', 'restart=' + restart)});
}

function computePasswordStrength(password)
{
    if(password.length == 0) return 0;

    var strength = 0;
    var length   = password.length;

    var uniqueChars = '';
    var complexity  = new Array();
    for(i = 0; i < length; i++)
    {
        letter = password.charAt(i);
        var asc = letter.charCodeAt();
        if(asc >= 48 && asc <= 57)
        {
            complexity[2] = 2;
        }
        else if((asc >= 65 && asc <= 90))
        {
            complexity[1] = 2;
        }
        else if(asc >= 97 && asc <= 122)
        {
            complexity[0] = 1;
        }
        else
        {
            complexity[3] = 3;
        }
        if(uniqueChars.indexOf(letter) == -1) uniqueChars += letter;
    }

    if(uniqueChars.length > 4) strength += uniqueChars.length - 4;
    var sumComplexity = 0;
    var complexitySize = 0;
    for(i in complexity)
    {
        complexitySize += 1;
        sumComplexity += complexity[i];
    }
    strength += sumComplexity + (2 * (complexitySize - 1));
    if(length < 6 && strength >= 10) strength = 9;

    strength = strength > 29 ? 29 : strength;
    strength = Math.floor(strength / 10);

    return strength;
}

/**
 * Check onlybody page when it is not open in modal then location to on onlybody page.
 *
 * @access public
 * @return void
 */
function checkOnlybodyPage()
{
    if(self == parent)
    {
        href = location.href.replace('?onlybody=yes', '');
        location.href = href.replace('&onlybody=yes', '');
    }
}

/**
 * Fixed table head in list when scrolling.
 *
 * @param  string $tableID
 * @access public
 * @return void
 */
function fixedTheadOfList(tableID)
{
    if($(tableID).size() == 0) return false;
    if($(tableID).css('display') == 'none') return false;
    if($(tableID).find('thead').size() == 0) return false;

    fixTheadInit();
    $(window).scroll(fixThead);//Fix table head when scrolling.
    $('.side-handle').click(function(){setTimeout(fixTheadInit, 300);});//Fix table head if module tree is hidden or displayed.

    var tableWidth, theadOffset, fixedThead, $fixedThead;
    function fixThead()
    {
        theadOffset = $(tableID).find('thead').offset().top;
        $fixedThead = $(tableID).parent().find('.fixedTheadOfList');
        if($fixedThead.size() <= 0 &&theadOffset < $(window).scrollTop())
        {
            tableWidth  = $(tableID).width();
            fixedThead  = "<table class='fixedTheadOfList'><thead>" + $(tableID).find('thead').html() + '</thead></table>';
            $(tableID).before(fixedThead);
            $('.fixedTheadOfList').addClass($(tableID).attr('class')).width(tableWidth);
        }
        if($fixedThead.size() > 0 && theadOffset >= $(window).scrollTop()) $fixedThead.remove();
    }
    function fixTheadInit()
    {
        $fixedThead = $(tableID).parent().find('.fixedTheadOfList');
        if($fixedThead.size() > 0) $fixedThead.remove();
        fixThead();
    }
}

/**
 * Apply cs style to page
 * @return void
 */
function applyCssStyle(css, tag)
{
    tag = tag || 'default';
    var name = 'applyStyle-' + tag;
    var $style = $('style#' + name);
    if(!$style.length)
    {
        $style = $('<style id="' + name + '">').appendTo('body');
    }
    var styleTag = $style.get(0);
    if (styleTag.styleSheet) styleTag.styleSheet.cssText = css;
    else styleTag.innerHTML = css;
}

/**
 * Remove cookie by key
 *
 * @param  cookieKey $cookieKey
 * @access public
 * @return void
 */
function removeCookieByKey(cookieKey)
{
    $.cookie(cookieKey, '', {expires:config.cookieLife, path:config.webRoot});
    location.href = location.href;
}

/**
 * Set homepage.
 *
 * @param  string $module
 * @param  string $page
 * @access public
 * @return void
 */
function setHomepage(module, page)
{
    $.get(createLink('custom', 'ajaxSetHomepage', 'module=' + module + '&page=' + page), function(){location.reload(true)});
}

/**
 * Reload page when tutorial mode setted in this session but not load in iframe
 *
 * @access public
 * @return void
 */
function checkTutorial()
{
    if(config.currentModule != 'tutorial' && window.TUTORIAL && (!frameElement || frameElement.tagName != 'IFRAME'))
    {
        if(confirm(window.TUTORIAL.tip))
        {
            $.getJSON(createLink('tutorial', 'ajaxQuit'), function()
            {
                window.location.reload();
            }).error(function(){alert(lang.timeout)});
        }
    }
}

/* Remove 'ditto' in first row when batch create or edit. */
function removeDitto()
{
    $firstTr = $('.table-form').find('tbody tr:first');
    $firstTr.find('td select').each(function()
    {
        $(this).find("option[value='ditto']").remove();
        $(this).trigger("chosen:updated");
    });
}

/**
 * Revert module cookie.
 *
 * @access public
 * @return void
 */
function revertModuleCookie()
{
    if($('#mainmenu .nav li[data-id="project"]').hasClass('active'))
    {
        $('#modulemenu .nav li[data-id="task"] a').click(function()
        {
            $.cookie('moduleBrowseParam', 0, {expires:config.cookieLife, path:config.webRoot});
        });
    }
    if($('#mainmenu .nav li[data-id="product"]').hasClass('active'))
    {
        $('#modulemenu .nav li[data-id="story"] a').click(function()
        {
            $.cookie('storyModule', 0, {expires:config.cookieLife, path:config.webRoot});
        });
    }
    if($('#mainmenu .nav li[data-id="qa"]').hasClass('active'))
    {
        $('#modulemenu .nav li[data-id="bug"] a').click(function()
        {
            $.cookie('bugModule', 0, {expires:config.cookieLife, path:config.webRoot});
        });
        $('#modulemenu .nav li[data-id="testcase"] a').click(function()
        {
            $.cookie('caseModule', 0, {expires:config.cookieLife, path:config.webRoot});
        });
    }
}

/**
 * Focus move up or down for input.
 *
 * @param type up|down
 */
function inputFocusJump(type){
    var hasFocus = $('input').is(':focus');
    if(hasFocus)
    {
        var title     = $("input:focus").attr('name').replace(/\[\d]/g, '');
        var $input    = $(":input[name^=" + title + "]:text:not(:disabled):not([name*='%'])");
        var num       = $input.length;
        var index     = parseInt($("input:focus").attr('name').replace(/[^0-9]/g, ''));
        var nextIndex = type == 'down' ? index + 1 : index - 1;

        if(nextIndex < num && nextIndex >= 0)
        {
            $input[nextIndex].focus();
        }
    }
}

/**
 * Focus move up or down for select.
 *
 * @param type
 */
function selectFocusJump(type)
{
    var hasFocus = $('select').is(':focus');
    if(hasFocus)
    {
        var title     = $("select:focus").attr('name').replace(/\[\d]/g, '');
        var $select   = $("select[name^=" + title + "]:not([name*='%'])");
        var num       = $select.length;
        var index     = parseInt($("select:focus").attr('name').replace(/[^0-9]/g, ''));
        var nextIndex = type == 'down' ? index + 1 : index - 1;

        if(nextIndex < num && nextIndex >= 0)
        {
            $select[nextIndex].focus();
        }
    }
}

function adjustNoticePosition()
{
    var bottom = 25;
    $('#noticeBox').find('.alert').each(function()
    {
        $(this).css('bottom',  bottom + 'px');
        bottom += $(this).outerHeight(true) - 10;
    });
}

function notifyMessage(data)
{
    if(window.Notification)
    {
        var notify = null;

        message = data;
        if(typeof data.message == 'string') message = data.message;
        if(Notification.permission == "granted")
        {
            notify = new Notification("", {body:message, tag:'zentao', data:data});
        }
        else if(Notification.permission != "denied")
        {
            Notification.requestPermission(function(permission)
            {
                notify = new Notification("", {body:message, tag:'zentao', data:data});
            });
        }

        if(notify)
        {
            notify.onclick = function()
            {
                window.focus();
                if(typeof notify.data.url == 'string' && notify.data.url) window.location.href = notify.data.url;
                notify.close();
            }
            setTimeout(function()
            {
                notify.close();
            }, 3000);
        }
    }
}

/**
 * Get fingerprint.
 *
 * @access public
 * @return void
 */
function getFingerprint()
{
    if(typeof(Fingerprint) == 'function') return new Fingerprint().get();

    fingerprint = '';
    $.each(navigator, function(key, value)
    {
        if(typeof(value) == 'string') fingerprint += value.length;
    })
    return fingerprint;
}

/**
 * Alert message with bootbox.
 *
 * @param  message $message
 * @access public
 * @return bool
 */
function bootAlert(message)
{
    bootbox.alert(message);
    return false;
}

/**
 * Toggle fold or unfold for parent.
 *
 * @param  string $form
 * @param  array  $unfoldIdList
 * @param  int    $objectID
 * @param  string $objectType
 * @access public
 * @return void
 */
function toggleFold(form, unfoldIdList, objectID, objectType)
{
    $form     = $(form);
    $parentTd = $form.find('td.has-child');
    if($parentTd.length == 0) return false;

    var toggleClass = objectType == 'product' ? 'story-toggle' : 'task-toggle';
    var nameClass   = objectType == 'product' ? 'c-title'      : 'c-name';
    $form.find('th.' + nameClass).append("<button type='button' id='toggleFold' class='btn btn-mini collapsed'>" + unfoldAll + "</button>");

    var allUnfold = true;
    $parentTd.each(function()
    {
        var dataID = $(this).closest('tr').attr('data-id');
        if(typeof(unfoldIdList[dataID]) != 'undefined') return true;

        allUnfold = false;
        $form.find('tr.parent-' + dataID).hide();
        $(this).find('a.' + toggleClass).addClass('collapsed')
    })

    $form.find('th.' + nameClass + ' #toggleFold').html(allUnfold ? foldAll : unfoldAll).toggleClass('collapsed', !allUnfold);

    $(document).on('click', '#toggleFold', function()
    {
        var newUnfoldID = [];
        var url         = '';
        var collapsed   = $(this).hasClass('collapsed');
        $parentTd.each(function()
        {
            var dataID = $(this).closest('tr').attr('data-id');
            $form.find('tr.parent-' + dataID).toggle(collapsed);
            $(this).find('a.' + toggleClass).toggleClass('collapsed', !collapsed)
            newUnfoldID.push(dataID);
        })

        $(this).html(collapsed ? foldAll : unfoldAll).toggleClass('collapsed', !collapsed);
        url = createLink('misc', 'ajaxSetUnfoldID', 'objectID=' + objectID + '&objectType=' + objectType + '&action=' + (collapsed ? 'add' : 'delete'));
        $.post(url, {'newUnfoldID': JSON.stringify(newUnfoldID)});
    });

    $parentTd.find('a.' + toggleClass).click(function()
    {
        var newUnfoldID = [];
        var url         = '';
        var collapsed   = $(this).hasClass('collapsed');
        var dataID      = $(this).closest('tr').attr('data-id');

        $form.find('tr.parent-' + dataID).toggle(!collapsed);
        newUnfoldID.push(dataID);
        url = createLink('misc', 'ajaxSetUnfoldID', 'objectID=' + objectID + '&objectType=' + objectType + '&action=' + (collapsed ? 'add' : 'delete'));

        $table = $(this).closest('table');
        setTimeout(function()
        {
            hasCollapsed = $table.find('td.has-child a.' + toggleClass + '.collapsed').length != 0;
            $('#toggleFold').html(hasCollapsed ? unfoldAll : foldAll).toggleClass('collapsed', hasCollapsed);
        }, 100);

        $.post(url, {'newUnfoldID': JSON.stringify(newUnfoldID)});
    });
}

/* Ping the server every some minutes to keep the session. */
needPing = true;

/* When body's ready, execute these. */
$(document).ready(function()
{
    if(needPing) setTimeout('setPing()', 1000 * 60 * 10);  // After 10 minutes, begin ping.

    checkTutorial();
    revertModuleCookie();

    $(document).on('click', '#helpMenuItem .close-help-tab', function(){$('#helpMenuItem').prev().remove();$('#helpMenuItem').remove();});
});
