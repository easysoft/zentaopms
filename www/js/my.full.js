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


// /**
//  * Set the help links of forum's items.
//  * 
//  * @access public
//  * @return void
//  */
// function setHelpLink()
// {
//     if(!$.cookie('help')) $.cookie('help', 'off', {expires:config.cookieLife, path:config.webRoot});
//     className = $.cookie('help') == 'off' ? 'hidden' : '';

//     $('form input[id], form select[id], form textarea[id]').each(function()
//     {
//         if($(this).attr('type') == 'hidden' || $(this).attr('type') == 'file') return;
//         currentFieldName = $(this).attr('name') ? $(this).attr('name') : $(this).attr('id');
//         if(currentFieldName == 'submit' || currentFieldName == 'reset') return;
//         if(currentFieldName.indexOf('[') > 0) currentFieldName = currentFieldName.substr(0, currentFieldName.indexOf('['));
//         currentFieldName = currentFieldName.toLowerCase();
//         helpLink = createLink('help', 'field', 'module=' + config.currentModule + '&method=' + config.currentMethod + '&field=' + currentFieldName);
//         $(this).after(' <a class="helplink ' + className + '" href=' + helpLink + ' target="_blank">?</a> ');
//     });

//     $("a.helplink").modalTrigger({width:600, type:'iframe'});
// }

// /**
//  * Toggle the help links.
//  * 
//  * @access public
//  * @return void
//  */
// function toggleHelpLink()
// {
//     $('.helplink').toggle();
//     if($.cookie('help') == 'off') return $.cookie('help', 'on',  {expires:config.cookieLife, path:config.webRoot});
//     if($.cookie('help') == 'on')  return $.cookie('help', 'off', {expires:config.cookieLife, path:config.webRoot});
// }

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
function setImageSize(image, maxWidth)
{
    /* If not set maxWidth, set it auto. */
    if(!maxWidth)
    {
        bodyWidth = $('body').width();
        maxWidth  = bodyWidth - 470; // The side bar's width is 336, and add some margins.
    }

    if($(image).width() > maxWidth) $(image).attr('width', maxWidth);
    $(image).wrap('<a href="' + $(image).attr('src') + '" target="_blank"></a>');
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
        $('#' + mailto).chosen();
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
                    $.get(document.location.href, function(data)
                    {
                        if(!($(data).find('#' + replaceID).length))location.reload();
                        $('#' + replaceID).html($(data).find('#' + replaceID).html());
                        if(typeof sortTable == 'function') sortTable(); 
                        $('#' + replaceID).find('[data-toggle=modal], a.iframe').modalTrigger();
                        if($('#' + replaceID).find('table.datatable').length) $('#' + replaceID).find('table.datatable').datatable();
                    });
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
 * Fixed table head in div box.
 * 
 * @param  string $boxObj 
 * @access public      
 * @return void
 */
//function fixedTableHead(boxObj)
//{
//    $(boxObj).scroll(function()
//    {
//        var hasFixed  = $(this).find('.fixedHead').size() > 0;
//        if(!hasFixed)
//        {
//            $(this).css('position', 'relative');
//            if($(this).find('table').size() == 1)
//            {
//                var fixed = "<table class='fixedHead' style='position:absolute;top:0px'><thead>" + $(this).find('table thead').html() + '</thead></table>';
//                $(this).prepend(fixed);
//                var $fixTable = $(this).find('table.fixedHead');
//                $fixTable.addClass($(this).find('table:last').attr('class'));
//                var $dataTable = $(this).find('table:last thead th');
//                $fixTable.find('thead th').each(function(i){$fixTable.find('thead th').eq(i).width($dataTable.eq(i).width());})
//            }
//        }
//        $(this).find('table.fixedHead').css('top',$(this).scrollTop());
//    });
//}

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
 * Show browser notice 
 * 
 * @access public
 * @return void
 */
function showBrowserNotice()
{
    userAgent = navigator.userAgent.toLowerCase();
    $browser  = new Object();
    $browser.msie   = /msie/.test(userAgent);
    $browser.chrome = /chrome/.test(userAgent);

    //if($browser.msie)
    //{
    //    match = /(msie) ([\w.]+)/.exec(userAgent);
    //    $browser.version = match[2] || '0';
    //}

    var show = false;

    /* IE 6,7. */
    //if($browser.msie && $browser.version <= 7) show = true;

    /* Souhu */
    if(navigator.userAgent.indexOf('MetaSr') >= 0)
    {
        show = true;
    }
    else if(navigator.userAgent.indexOf('LBBROWSER') >= 0)
    {
        show = true;
    }
    else if(navigator.userAgent.indexOf('QQBrowser') >= 0)
    {
        show = true;
    }
    else if(navigator.userAgent.indexOf('TheWorld') >= 0)
    {
        show = true;
    }
    else if(navigator.userAgent.indexOf('BIDUBrowser') >= 0)
    {
        show = true;
    }
    else if(navigator.userAgent.indexOf('Maxthon') >= 0)
    {
        show = true;
    }
    /* 360. */
    //else if($browser.chrome && !(window.clientInformation && window.clientInformation.mediaDevices))
    //{
    //    show = true;
    //}

    if(show) $('body').prepend('<div class="alert alert-info alert-dismissable" style="margin:0px;"><button type=button" onclick="ajaxIgnoreBrowser()" class="close" data-dismiss="alert" aria-hidden="true"><i class="icon-remove"></i></button><p>' + browserNotice + '</p></div>');
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
        if(Notification.permission == "granted")
        {
            new Notification("", {body:data});
        }
        else if(Notification.permission != "denied")
        {
            Notification.requestPermission(function(permission)
            {
                new Notification("", {body:data});
            });
        }
    }
}

/* Ping the server every some minutes to keep the session. */
needPing = true;

/* When body's ready, execute these. */
$(document).ready(function() 
{
    if(needPing) setTimeout('setPing()', 1000 * 60 * 10);  // After 10 minutes, begin ping.

    checkTutorial();
    revertModuleCookie();
});
