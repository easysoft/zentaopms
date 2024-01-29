let password1Encrypted = false;
let password2Encrypted = false;
let verifyEncrypted    = false;

/**
 * 密码改变时标记密码未加密。
 * Mark password unencrypted when password changes.
 *
 * @param  event  event
 * @access public
 * @return void
 */
function changePassword(event)
{
    const targetID = $(event.target).attr('id');
    if(targetID == 'password1')      password1Encrypted = false;
    if(targetID == 'password2')      password2Encrypted = false;
    if(targetID == 'verifyPassword') verifyEncrypted    = false;
}

/**
 * 加密密码并记录密码强度和长度。
 * Encrypt password and record password strength and length.
 *
 * @access public
 * @return void
 */
function encryptPassword()
{
    $('#verifyPassword').on('change', function(){verifyEncrypted = false});

    const rand = $('input[name=verifyRand]').val();

    /* 加密当前登录用户的密码。*/
    /* Encrypt password of current user. */
    if($('input#verifyPassword').length > 0)
    {
        const password = $('input#verifyPassword').val().trim();
        if(password && !verifyEncrypted)
        {
            $('input#verifyPassword').val(md5(md5(password) + rand));
            verifyEncrypted = true;
        }
    }

    if($('#password1').length == 0 || $('#password2').length == 0) return;

    /* 加密新添加用户或被修改用户的密码 1，并记录密码强度和长度。*/
    /* Encrypt password 1 of new or modified user, and record password strength and length. */
    const password1 = $('#password1').val().trim();
    if(password1 && !password1Encrypted)
    {
        $('#password1').val(md5(password1) + rand);
        $("input[name=passwordStrength]").val(computePasswordStrength(password1));
        $("input[name=passwordLength]").val(password1.length);
        password1Encrypted = true;
    }

    /* 加密新添加用户或被修改用户的密码 2。*/
    /* Encrypt password 2 of new or modified user. */
    const password2 = $('#password2').val().trim();
    if(password2 && !password2Encrypted)
    {
        $('#password2').val(md5(password2) + rand);
        password2Encrypted = true;
    }
}

/**
 * 添加或编辑一个用户时切换显示选择所属公司或添加公司的输入框。
 * Toggle display company picker or input when add or edit a user.
 *
 * @param  event  event
 * @access public
 * @return void
 */
function toggleNew(event)
{
    const checked = $(event.target).prop('checked');
    $('[name="company"]').closest('.picker-box').toggleClass('hidden', checked);
    $('#newCompany').toggleClass('hidden', !checked);
}

/**
 * 更改界面类型时更新权限组。
 * Update group when change vision.
 *
 * @param  event  event
 * @access public
 * @return void
 */
function changeVision(event)
{
    let visions = [];
    $('input[name="visions[]"]:checked').each(function()
    {
        visions.push($(this).val());
    });

    const link = $.createLink('user', 'ajaxGetGroups', 'visions=' + visions);
    $.get(link, function(response)
    {
        const data   = JSON.parse(response);
        const group  = $('[name^="group"]').val();
        const $group = $('[name^="group"]').zui('picker');
        $group.render({items: data});
        $group.$.setValue(group);
    });
}

/**
 * 计算密码强度。
 *
 * @param  string password
 * @access public
 * @return int
 */
function computePasswordStrength(password)
{
    if(password.length == 0) return 0;

    var strength = 0;
    var length   = password.length;

    var complexity  = new Array();
    for(i = 0; i < length; i++)
    {
        letter = password.charAt(i);
        var asc = letter.charCodeAt();
        if(asc >= 48 && asc <= 57)
        {
            complexity[0] = 1;
        }
        else if((asc >= 65 && asc <= 90))
        {
            complexity[1] = 2;
        }
        else if(asc >= 97 && asc <= 122)
        {
            complexity[2] = 4;
        }
        else
        {
            complexity[3] = 8;
        }
    }

    var sumComplexity = 0;
    for(i in complexity) sumComplexity += complexity[i];

    if((sumComplexity == 7 || sumComplexity == 15) && password.length >= 6) strength = 1;
    if(sumComplexity == 15 && password.length >= 10) strength = 2;

    return strength;
}

window.switchAccount = function(account)
{
    link = $.createLink('user', method, 'account=' + account);
    if(method == 'dynamic') link = $.createLink('user', method, 'account=' + account + '&period=' + pageParams.period);
    if(method == 'todo')    link = $.createLink('user', method, 'account=' + account + '&type=' + pageParams.type);
    if(method == 'story')   link = $.createLink('user', method, 'account=' + account + '&storyType=' + pageParams.storyType);

    loadPage(link);
};

window.beforePageLoad = function(options)
{
    if(options.load === 'table') options.selector += ',.dtable-sub-nav';
}
