let selectedIcon;
let selectedTheme;

function changeMiniProgramTheme(event)
{
    const $icon = $('#preview-icon');
    const $button = $(event.target).closest('button');
    const $prev = $('.theme-checked');
    $prev.removeClass('theme-checked');
    $button.addClass('theme-checked');
    $prev.empty();
    $button.append(iconCheck);
    $icon.css('background-color', $button.css('background-color'));
    $icon.css('border', $button.css('border'));
    selectedTheme = $button.index();
}

function changeMiniProgramIcon(event)
{
    const $icon = $('#preview-icon');
    const $svg = $(event.target).closest('svg').clone();
    $icon.empty();
    $icon.append($svg);
    selectedIcon = $svg.prop('id');
}

function saveMiniProgramIcon()
{
    const $editIcon = $('#ai-edit-icon');
    $editIcon.children().first('svg').remove();
    const $icon = $('#preview-icon');
    $editIcon.prepend($icon.find('svg').clone());
    $editIcon.css('background-color', $icon.css('background-color'));
    $editIcon.css('border', $icon.css('border'));
    $('input[name="iconTheme"]').prop('value', selectedTheme);
    $('input[name="iconName"]').prop('value', selectedIcon);
}

function saveMiniProgram()
{
    $('[name="toNext"]').prop('value', '0');
}

function toConfiguredMiniProgram()
{
    $('[name="toNext"]').prop('value', '1');
}

function toEditMiniProgramIcon()
{
    const $button = $('#ai-edit-icon');
    const iconName = $button.find('svg').prop('id');
    const iconTheme = $button.css('background-color');
    const $container = $('.icon-setting-container');
    $container.find(`#${iconName}`).click();
    $container.find('.btn-icon').each(function()
    {
        if($(this).css('background-color') === iconTheme) $(this).click();
    });
}

$(function()
{
    $('#theme-buttons button').on('click', changeMiniProgramTheme);
    $('#icon-buttons svg').on('click', changeMiniProgramIcon);
    $('#save-icon-button').on('click', saveMiniProgramIcon);
    $('#save-miniprogram').on('click', saveMiniProgram);
    $('#next-step').on('click', toConfiguredMiniProgram);
    $('#ai-edit-icon').on('click', toEditMiniProgramIcon);
});
