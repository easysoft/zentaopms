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
    echo html::select('contactListMenu', $contactLists, '', "class='form-control chosen' $attr onchange=\"setMailto('$dropdownName', this.value)\"");
}
else
{
    $width = isonlybody() ? 'data-width=100%' : '';
    echo '<span class="input-group-btn">';
    echo '<a title="' . $lang->user->contacts->manage . '" href="' . $this->createLink('my', 'managecontacts', "listID=0&mode=new", '', true) . "\" target='_blank' data-icon='cog' data-title='{$lang->user->contacts->manage}' class='btn btn-icon iframe' $width><i class='icon icon-cog'></i></a>";
    echo '</span>';
    echo '<span class="input-group-btn">';
    echo '<button type="button" title="' . $lang->refresh . '" class="btn btn-icon"' . "onclick=\"ajaxGetContacts(this, '$dropdownName')\"" . '><i class="icon icon-refresh"></i></button>';
    echo '</span>';
}
?>
<style>
#contactListMenu_chosen {width: 100px !important;}
#contactListMenu + .chosen-container {min-width: 100px;}
td > <?php echo "#" . $dropdownName;?> + .chosen-container .chosen-choices {border-radius: 2px 2px 0 0;}
td > <?php echo "#" . $dropdownName;?> + .chosen-container + #contactListMenu + .chosen-container > .chosen-single {border-radius: 0 0 2px 2px; border-top-width: 0; padding-top: 6px;}
#contactListMenu + .chosen-container.chosen-container-active > .chosen-single {border-top-width: 1px !important; padding-top: 5px !important;}
</style>
