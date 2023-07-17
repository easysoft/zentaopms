<?php
/**
 * The docmycollectionblock view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<style>

</style>

<script>
$(function()
{
    $('.doc-box .btn').on('click', function()
    {
        if($(this).hasClass('no-priv')) return;

        location.href = $(this).data('link');
    });
});
</script>
