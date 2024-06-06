window.saveMode = function(obj)
{
    let $this = $(obj);
    if($this.hasClass('disabled')) return false;

    let selectedMode = $this.data('mode');
    $('[name=mode]').val(selectedMode);

    if(selectedMode != 'light' || !hasProgram)
    {
        const formData = new FormData();
        formData.append('mode', selectedMode);
        zui.Modal.confirm({message: changeModeTips, icon: 'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) => {if(res) $.ajaxSubmit({url: $.createLink('custom', 'mode'), data: formData});});
    }
};
