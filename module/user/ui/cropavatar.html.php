<?php
declare(strict_types=1);
/**
 * The cropavatar view file of user module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     user
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('imageID', $image->id);

modalHeader(set::title(''), set::entityText($lang->user->cropAvatar), set::entityID(''));
formPanel
(
    set::actions(''),
    imgCutter
    (
        set::btnText($lang->save),
        set::tipText($lang->user->cropAvatarTip),
        set::src($image->webPath),
        set::handleBtnClick(jsRaw('saveAvatar')),
        setStyle('max-width', '540px')
    )
);

render();
