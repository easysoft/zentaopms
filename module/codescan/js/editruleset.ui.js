window.checkLanguage = function()
{
    const lang = $('[name^=lang]').zui('picker').$.value;
    if(!lang) return;

    const langList    = lang.split(',');
    const oldLangList = ruleset.langID.toString().split(',');

    const diff = oldLangList.filter(item => !langList.includes(item));
    if(diff.length < 1) return;

    const clearLang   = diff.map((item) => langPairs[item === '0' ? 'Custom' : item]);
    const confirmLang = codeScanLang.notice.editLanguage.replace('%s', clearLang.join(', '));

    zui.Modal.confirm({message: confirmLang, icon:'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
    {
        if(res) $.ajaxSubmit({url: $('#editRulesetForm').attr('action'), data: new FormData($("#editRulesetForm")[0])})
    });
    return false;
};
