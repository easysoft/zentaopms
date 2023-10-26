let selectedIcon;
let selectedTheme;

function handleThemeButtonClick(event)
{
    const $icon = $('#preview-icon');
    const $button = $(event.target).closest('button');
    const $prev = $('.theme-checked');
    $prev.removeClass('theme-checked');
    $button.addClass('theme-checked');
    $button.html($prev.html());
    $prev.empty();
    $icon.css('background-color', $button.css('background-color'));
    $icon.css('border', $button.css('border'));
    selectedTheme = $button.index();
}

function handleIconButtonClick(event)
{
    const $icon = $('#preview-icon');
    const $svg = $(event.target).closest('svg').clone();
    $icon.empty();
    $icon.append($svg);
    selectedIcon = $svg.prop('id');
}

function handleSaveButtonClick()
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

function handleSaveMiniProgramClick()
{
    $('[name="toNext"]').prop('value', '0');
}

function handleNextStepClick()
{
    $('[name="toNext"]').prop('value', '1');
}

$(function()
{
    $('#theme-buttons button').on('click', handleThemeButtonClick);
    $('#icon-buttons svg').on('click', handleIconButtonClick);
    $('#save-icon-button').on('click', handleSaveButtonClick);
    $('#save-miniprogram').on('click', handleSaveMiniProgramClick);
    $('#next-step').on('click', handleNextStepClick);
});
