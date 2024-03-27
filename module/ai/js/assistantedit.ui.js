let selectedTheme = iconTheme ?? 7;
let selectedIcon = iconName ?? 'coding';

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

$(function()
{
    $('#theme-buttons button').on('click', changeMiniProgramTheme);
    $('#icon-buttons svg').on('click', changeMiniProgramIcon);
    $('#save-icon-button').on('click', saveMiniProgramIcon);
});
