<?php
declare(strict_types=1);
/**
 * The tao file of mail module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@easycorp.ltd>
 * @package     mail
 * @link        https://www.zentao.net
 */
class mailTao extends mailModel
{
    /**
     * Exclude me from toList and ccList.
     *
     * @param  array     $toList
     * @param  array     $ccList
     * @access protected
     * @return array
     */
    protected function excludeMe(array $toList, array $ccList): array
    {
        $account = isset($this->app->user->account) ? $this->app->user->account : '';

        $toList = array_unique(array_filter(array_map(function($to) use($account){$to = trim($to); return $to == $account ? '' : $to;}, $toList)));
        $ccList = array_unique(array_filter(array_map(function($cc) use($account){$cc = trim($cc); return $cc == $account ? '' : $cc;}, $ccList)));

        return array($toList, $ccList);
    }

    /**
     * Process toList and ccList. Exclude me and remove deleted users.
     *
     * @param  string    $toList
     * @param  string    $ccList
     * @param  bool      $includeMe
     * @access protected
     * @return array
     */
    protected function processToAndCC(string $toList, string $ccList, bool $includeMe = false): array
    {
        $toList  = $toList ? explode(',', str_replace(' ', '', $toList)) : array();
        $ccList  = $ccList ? explode(',', str_replace(' ', '', $ccList)) : array();

        /* Process toList and ccList, remove current user from them. If toList is empty, use the first cc as to. */
        if(!$includeMe) list($toList, $ccList) = $this->excludeMe($toList, $ccList);

        /* Remove deleted users. */
        $this->app->loadConfig('message');
        $users      = $this->loadModel('user')->getPairs('nodeleted|all');
        $blockUsers = isset($this->config->message->blockUser) ? explode(',', $this->config->message->blockUser) : array();
        $toList = array_unique(array_filter(array_map(function($to) use($users, $blockUsers) {$to = trim($to); return (isset($users[$to]) && !in_array($to, $blockUsers)) ? $to : '';}, $toList)));
        $ccList = array_unique(array_filter(array_map(function($cc) use($users, $blockUsers) {$cc = trim($cc); return (isset($users[$cc]) && !in_array($cc, $blockUsers)) ? $cc : '';}, $ccList)));

        if(empty($toList) and $ccList) $toList = array(array_shift($ccList));

        $toList = implode(',', $toList);
        $ccList = implode(',', $ccList);

        return array($toList, $ccList);
    }

    /**
     * Replace image URL for mail content.
     *
     * @param  string    $body
     * @access protected
     * @return string
     */
    protected function replaceImageURL(string $body): string
    {
        /* Replace full webPath image for mail. */
        $sysURL      = zget($this->config->mail, 'domain', common::getSysURL());
        $readLinkReg = str_replace(array('%fileID%', '/', '.', '?'), array('[0-9]+', '\/', '\.', '\?'), helper::createLink('file', 'read', 'fileID=(%fileID%)', '\w+'));

        $body = preg_replace('/ src="(' . $readLinkReg . ')" /', ' src="' . $sysURL . '$1" ', $body);
        $body = preg_replace('/ src="{([0-9]+)(\.(\w+))?}" /', ' src="' . $sysURL . helper::createLink('file', 'read', "fileID=$1", "$3") . '" ', $body);
        $body = preg_replace('/<img (.*)src="\/?data\/upload/', '<img $1 src="' . $sysURL . $this->config->webRoot . 'data/upload', $body);

        return $body;
    }
}

