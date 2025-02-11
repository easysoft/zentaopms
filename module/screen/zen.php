<?php
class screenZen extends screen
{
    /**
     * Common Action.
     *
     * @param  int    $dimensionID
     * @param  bool   $setMenu
     * @access protected
     * @return void
     */
    protected function commonAction($dimensionID, $setMenu = true)
    {
        return 1;
    }

    /**
     * Prepare card list.
     *
     * @param  array $screens
     * @access protected
     * @return array
     */
    protected function prepareCardList(array $screens): array
    {
        $canDesign = common::hasPriv('screen', 'design');
        $canEdit   = common::hasPriv('screen', 'edit');
        $canDelete = common::hasPriv('screen', 'delete');

        $canViewAnnualData = common::hasPriv('screen', 'annualData');

        foreach($screens as $id => $screen)
        {
            $screen->src = empty($screen->cover) || $screen->status == 'draft' ? "static/images/screen_{$screen->status}.png" : $screen->cover;

            $screen->actions = array();

            if($this->config->edition == 'open') continue;
            if($canEdit)   $screen->actions[] = array('icon' => 'edit', 'text' => $this->lang->screen->edit, 'url' => $this->createLink('screen', 'edit', "screenID={$screen->id}"), 'data-toggle' => 'modal');

            if($screen->builtin == '1') continue;
            if($canDesign) $screen->actions[] = array('icon' => 'design', 'text' => $this->lang->screen->design, 'url' => $this->createLink('screen', 'design', "screenID={$screen->id}"));
            if($canDelete) $screen->actions[] = array('icon' => 'trash', 'text' => $this->lang->screen->delete, 'url' => $this->createLink('screen', 'delete', "screenID={$screen->id}&confirm=yes"), 'data-confirm' => $this->lang->screen->confirmDelete);
        }

        return $screens;
    }
}
