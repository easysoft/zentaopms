<?php
/**
 * The build contact lists view file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fei Chen<chenfei@cnezsoft.com>
 * @package     my
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php
if($contactLists)
{
    echo html::select('contactListMenu', array('' => '') + $contactLists, '', "class='form-control chosen' $attr onchange=\"setOldMailto('$dropdownName', this.value)\"");
}
?>
<style>
#contactListMenu_chosen {width: 100px !important;}
#contactListMenu + .chosen-container {min-width: 100px;}
td > <?php echo "#" . $dropdownName;?> + .chosen-container .chosen-choices {border-radius: 2px 2px 0 0;}
td > <?php echo "#" . $dropdownName;?> + .chosen-container + #contactListMenu + .chosen-container > .chosen-single {border-radius: 0 0 2px 2px; border-top-width: 0; padding-top: 6px;}
#contactListMenu + .chosen-container.chosen-container-active > .chosen-single {border-top-width: 1px !important; padding-top: 5px !important;}
</style>

<script>
function setOldMailto(mailto, contactListID)
{
    link = createLink('user', 'ajaxGetOldContactUsers', 'listID=' + contactListID + '&dropdownName=' + mailto);
    $.get(link, function(users)
    {
        $('#' + mailto).replaceWith(users);
        $('#' + mailto + '_chosen').remove();
        $('#' + mailto).chosen();
    });
}
</script>
