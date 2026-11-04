/**
 * GitFox 版空间应用列表的搜索：与应用模块列表的搜索交互保持一致。
 * The search of the application list of a space in the GitFox vision.
 *
 * @copyright   Copyright 2009-2026 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @package     space
 * @link        https://www.zentao.net
 */
window.searchSpaceApp = function()
{
    const keyword   = $('#spaceAppKeyword').val().trim();
    const searchUrl = spaceAppSearchLink.replace('__KEYWORD__', encodeURIComponent(keyword));

    if(typeof loadPage === 'function') loadPage(searchUrl);
    else location.href = searchUrl;
};

$(document).on('click', '#spaceAppSearchBtn', window.searchSpaceApp);
$(document).on('keydown', '#spaceAppKeyword', function(e)
{
    if(e.keyCode == 13)
    {
        e.preventDefault();
        window.searchSpaceApp();
    }
});
