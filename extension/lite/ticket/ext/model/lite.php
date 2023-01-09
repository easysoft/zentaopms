<?php
/**
 * Build ticket browse menu.
 *
 * @param  object $ticketID
 * @access public
 * @return string
 */
public function buildOperateBrowseMenu($ticketID)
{
    $ticket      = $this->getByID($ticketID);
    $menu        = '';
    $params      = "ticket=$ticketID";
    $disabled = '';

    if($ticket->status == 'done')
    {
        $menu .= $this->buildMenu('ticket', 'close', $params, $ticket, 'browse', 'off', 'hiddenwin', '', '', '', $this->lang->ticket->close);
    }
    else
    {
        $menu .= $this->buildMenu('ticket', 'close', $params, $ticket, 'browse', 'off', '', 'iframe', true, '', $this->lang->ticket->close);
    }

    $menu .= $this->buildMenu('ticket', 'edit', $params, $ticket, 'browse', 'edit', '', '', '', '', $this->lang->ticket->edit);

    return $menu;
}

/**
 * Build ticket view menu.
 *
 * @param  int    $ticketID
 * @access public
 * @return string
 */
public function buildOperateViewMenu($ticketID)
{
    $ticket = $this->getByID($ticketID);
    if($ticket->deleted) return '';

    $menu   = '';
    $params = "ticket=$ticket->id";

    if($ticket->status != 'closed') $menu .= $this->loadModel('effort')->createAppendLink('ticket', $ticketID);

    if($ticket->status == 'done')
    {
        $menu .= $this->buildMenu('ticket', 'close', $params, $ticket, 'view', 'off', 'hiddenwin', '', '', '', $this->lang->ticket->close);
    }
    else
    {
        $menu .= $this->buildMenu('ticket', 'close', $params, $ticket, 'view', 'off', '', 'iframe', true, '', $this->lang->ticket->close);
    }
    
    $menu .= $this->buildMenu('ticket', 'activate', $params, $ticket, 'view', 'magic', '', "iframe", true, '', $this->lang->ticket->activate);
    $menu .= $this->buildMenu('ticket', 'edit', $params, $ticket, 'view', 'edit', '', '', '', '', $this->lang->ticket->edit);
    $menu .= $this->buildMenu('ticket', 'delete', $params, $ticket, 'view', 'trash', 'hiddenwin');

    return $menu;
}