window.switchLang = function(lang)
{
    selectLang(lang);
};

$.ajax({url: $.createLink('misc', 'ajaxSendEvent', "step=start"), timeout: 2000});
