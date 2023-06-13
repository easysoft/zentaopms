<?php
class imMessage extends model
{
    /**
     * Get message list.
     *
     * @param  string $cgid
     * @param  array  $idList
     * @param  object $pager
     * @param  string $startDate
     * @param  string $type
     * @param  bool   $format
     * @param  bool   $masterOnly
     * @param  int    $userID
     * @access public
     * @return array
     */
    public function getList($cgid = '', $idList = array(), $pager = null, $startDate = '', $type = '', $format = true, $masterOnly = false, $userID = null)
    {
        if($masterOnly)
        {
            $tables = array((object)array('tableName' => TABLE_IM_MESSAGE, 'messages' => $idList));
        }
        else
        {
            if(!empty($idList))    $tables = $this->getTableByMessages($idList);
            if(!empty($startDate)) $tables = $this->getTablesByDateRange($startDate);
            if(empty($idList) && empty($startDate)) $tables = $this->getAllTables();
            if(empty($tables)) return array();
        }

        $queries = array();
        foreach($tables as $table)
        {
            $queries[] = $this->dao->select('*')->from($table->tableName)->where('1')
                ->beginIF(!empty($cgid))->andWhere('cgid')->eq($cgid)->fi()
                ->beginIF(!empty($idList))->andWhere('id')->in($table->messages)->fi()
                ->beginIF(!empty($startDate))->andWhere('date')->ge($startDate)->fi()
                ->beginIF(!empty($type) && strpos($type, '!') === 0)->andWhere('type')->ne(substr($type, 1))->fi()
                ->beginIF(!empty($type) && strpos($type, '!') !== 0)->andWhere('type')->eq($type)->fi()
                ->beginIF($userID != null)->andWhere('user')->eq($userID)->fi()
                ->get();
        }
        $query = join(' UNION ALL ', $queries);

        $sql = $this->dao->select('*')->from(TABLE_IM_MESSAGE);
        $sql->sqlobj->sql = 'SELECT * FROM (' . $query . ') as t';

        /* Assemble order by and pager stuff. */
        $messages = $sql
            ->orderBy('id_desc')
            ->beginIF($pager != null)->page($pager)->fi()
			->fetchAll();
        $messages = $this->decodeMessages($messages);
        return $format ? $this->format($messages) : $messages;
    }

    /**
     * Get message list by indexes.
     *
     * @param  string $cgid
     * @param  array  $indexList
     * @param  object $pager
     * @param  string $startDate
     * @param  string $type
     * @param  bool   $format
     * @param  bool   $masterOnly
     * @param  int    $userID
     * @access public
     * @return array
     */
    public function getListByIndexes($cgid = '', $indexList = array(), $pager = null, $startDate = '', $type = '', $format = true, $masterOnly = false, $userID = null)
    {
        if($cgid == '') return array();
        if($masterOnly)
        {
            $tables = array((object)array('tableName' => TABLE_IM_MESSAGE, 'messages' => $indexList));
        }
        else
        {
            if(!empty($indexList)) $tables = $this->getChatTablesByChatIndexes($cgid, $indexList);
            if(!empty($startDate)) $tables = $this->getChatTablesByDateRange($cgid, $startDate);
            if(empty($indexList) && empty($startDate)) $tables = $this->getChatAllTables($cgid);
            if(empty($tables)) return array();
        }

        $queries = array();
        foreach($tables as $table)
        {
            $queries[] = $this->dao->select('*')->from($table->tableName)->where('1')
                ->andWhere('cgid')->eq($cgid)
                ->beginIF(!empty($indexList))->andWhere('`index`')->in($table->messages)->fi()
                ->beginIF(!empty($startDate))->andWhere('date')->ge($startDate)->fi()
                ->beginIF(!empty($type) && strpos($type, '!') === 0)->andWhere('type')->ne(substr($type, 1))->fi()
                ->beginIF(!empty($type) && strpos($type, '!') !== 0)->andWhere('type')->eq($type)->fi()
                ->beginIF($userID != null)->andWhere('user')->eq($userID)->fi()
                ->get();
        }
        $query = join(' UNION ALL ', $queries);

        $sql = $this->dao->select('*')->from(TABLE_IM_MESSAGE);
        $sql->sqlobj->sql = 'SELECT * FROM (' . $query . ') as t';

        /* Assemble order by and pager stuff. */
        $messages = $sql
            ->orderBy('id_desc')
            ->beginIF($pager != null)->page($pager)->fi()
            ->fetchAll();
        $messages = $this->decodeMessages($messages);
        return $format ? $this->format($messages) : $messages;
    }

    /**
     * Get messages ranged from id for user.
     *
     * @param  string $cgid
     * @param  int    $fromID
     * @param  bool   $reverse
     * @param  int    $limit
     * @param  int    $userID
     * @param  bool   $returnID  return id list only.
     * @access public
     * @return array
     */
    public function getListAroundIDForUser($cgid, $fromID, $reverse = false, $limit = 50, $userID = 0, $returnID = false)
    {
        if($fromID == 0 && $reverse) $fromID = PHP_INT_MAX;

        $chats = empty($cgid) ? $this->loadModel('im')->chatGetGidListByUserID($userID, true) : array($cgid);
        $tables = $this->getTablesByChats($chats);

        if(!empty($tables)) $tables = $this->dao->select('tableName')->from(TABLE_IM_MESSAGE_INDEX)->where(1)
                ->beginIF($reverse)->andWhere('start')->le($fromID)->fi()
                ->beginIF(!$reverse)->andWhere('end')->ge($fromID)->fi()
                ->andWhere('tableName')->in($tables)
                ->fetchAll();

        $tables[] = (object)array('tableName' => TABLE_IM_MESSAGE);

        $queries = array();
        foreach($tables as $table)
        {
            $queries[] = $this->dao->select($returnID ? 'id' : '*')->from($table->tableName)->where(1)
                ->beginIF($reverse)->andWhere('id')->le($fromID)->fi()
                ->beginIF(!$reverse)->andWhere('id')->ge($fromID)->fi()
                ->andWhere('cgid')->in($chats)
                ->get();
        }
        $query = join(' UNION ALL ', $queries);

        $sql = $this->dao->select('*')->from(TABLE_IM_MESSAGE);
        $sql->sqlobj->sql = 'SELECT * FROM (' . $query . ') as t';

        $messages = $sql
            ->beginIF($reverse)->orderBy('id_desc')->fi()
            ->beginIF(!$reverse)->orderBy('id')->fi()
            ->limit($limit)
            ->fetchAll();
        $messages = $this->decodeMessages($messages);
        return $returnID ? array_map(function($obj){return (int)$obj->id;}, $messages) : $this->format($messages);
    }

    /**
     * Get chat messages by indexes.
     *
     * @param  string $cgid
     * @param  array  $indexList
     * @param  bool   $reverse
     * @param  bool   $returnID  return id list only.
     * @access public
     * @return array
     */
    public function getListAroundIDByIndexes($cgid, $indexList, $reverse = false, $returnID = false)
    {
        $tables  = $this->getChatTablesByChatIndexes($cgid, $indexList);
        $queries = array();
        foreach($tables as $table)
        {
            $queries[] = $this->dao->select($returnID ? 'id' : '*')->from($table->tableName)
                ->where('cgid')->eq($cgid)
                ->beginIF(!empty($indexList))->andWhere('`index`')->in($table->messages)->fi()
                ->get();
        }
        $query = join(' UNION ALL ', $queries);

        $sql = $this->dao->select('*')->from(TABLE_IM_MESSAGE);
        $sql->sqlobj->sql = 'SELECT * FROM (' . $query . ') as t';

        /* Assemble order by and pager stuff. */
        $messages = $sql
            ->beginIF($reverse)->orderBy('id_desc')->fi()
            ->beginIF(!$reverse)->orderBy('id')->fi()
            ->fetchAll();
        $messages = $this->decodeMessages($messages);
        return $returnID ? array_map(function($obj){return (int)$obj->id;}, $messages) : $this->format($messages);
    }

    /**
     * Get last messages of chats.
     *
     * @param  array  $cgids
     * @access public
     * @return array
     */
    public function getLast($cgids)
    {
        $chatMessages = $this->dao->select('gid,lastMessage')->from(TABLE_IM_CHAT)->where('gid')->in($cgids)->fetchPairs();
        return $chatMessages ? $this->getList('', array_values($chatMessages)) : array();
    }

    /**
     * Format messages.
     *
     * @param  mixed  $messages  object | array
     * @access public
     * @return object | array
     */
    public function format($messages)
    {
        $isObject = false;
        if(is_object($messages))
        {
            $isObject = true;
            $messages = array($messages);
        }

        $messageList = array();
        foreach($messages as $message)
        {
            $message->id      = (int)$message->id;
            $message->index   = (int)$message->index;
            $message->user    = (int)$message->user;
            $message->date    = strtotime($message->date);
            $message->deleted = isset($message->deleted) ? (bool)$message->deleted : false;

            /* Clear content for retracted messages */
            if($message->deleted) $message->content = '';

            $messageList[$message->gid] = $message;
        }

        if($isObject) return reset($messageList);

        return $messageList;
    }

    /**
     * Create messages.
     *
     * @param  array  $messageList
     * @param  int    $userID
     * @access public
     * @return array
     */
    public function create($messageList = array(), $userID = 0)
    {
        $idList           = array();
        $chatMessageID    = array();
        $chatMessageIndex = array();
        $now = helper::now();
        foreach($messageList as $message)
        {
            $message = (object) $message;
            $msg     = $this->dao->select('*')->from(TABLE_IM_MESSAGE)->where('gid')->eq($message->gid)->fetch();
            if($msg)
            {
                if($msg->contentType == 'image' || $msg->contentType == 'file')
                {
                    $message = $this->encodeMessage($message);
                    $this->dao->update(TABLE_IM_MESSAGE)->set('content')->eq($message->content)->where('id')->eq($msg->id)->exec();
                }
                $idList[] = $msg->id;
            }
            elseif(!$msg)
            {
                if(!(isset($message->user) && $message->user)) $message->user = $userID;
                if(!(isset($message->date) && $message->date)) $message->date = $now;

                $msgIndex = $this->dao->select('`lastMessageIndex` + 1')->from(TABLE_IM_CHAT)->where('gid')->eq($message->cgid)->fetch('`lastMessageIndex` + 1');

                if(empty($msgIndex)) $msgIndex = 1;

                $message->index = $msgIndex;
                $chatMessageIndex[$message->cgid] = $msgIndex;

                $this->dao->insert(TABLE_IM_MESSAGE)->data($this->encodeMessage($message))->exec();
                $message->id = $this->dao->lastInsertID();
                $idList[] = $message->id;
            }
            if(isset($message->id)) $chatMessageID[$message->cgid] = $message->id;
        }
        if(empty($idList)) return array();

        foreach(array_keys($chatMessageID) as $cgid)
        {
            $setData = "lastActiveTime = '$now'";
            if(isset($chatMessageIndex[$cgid]))
            {
                $setData .= ", lastMessage = $chatMessageID[$cgid]";
            }
            if(isset($chatMessageIndex[$cgid]))
            {
                $setData .= ", lastMessageIndex = $chatMessageIndex[$cgid]";
            }
            $this->dao->update(TABLE_IM_CHAT)
                ->set($setData)
                ->where('gid')->eq($cgid)
                ->exec();
        }

        return $this->getList('', $idList);
    }

    /**
     * set message with new content.
     * @param $message
     * @return void
     */
    public function setMessage($message)
    {
        $msg = $this->encodeMessage($message);
        $this->dao->update(TABLE_IM_MESSAGE)->set('content')->eq($msg->content)->where('id')->eq($message->id)->exec();
    }

    /**
     * Get message list by cgid.
     *
     * @param  string|array  $cgid
     * @param  object        $pager
     * @param  string        $startDate
     * @access public
     * @return array
     */
    public function getListByCgid($cgid, $pager = null, $startDate = '')
    {
        if(empty($startDate))  $tables = $this->getAllTables();
        if(!empty($startDate)) $tables = $this->getTablesByDateRange($startDate);

        if(empty($tables)) return array();

        $queries = array();
        foreach($tables as $table)
        {
            $queries[] = $this->dao->select('*')->from($table->tableName)->where('1')
                ->beginIF(!is_array($cgid))->andWhere('cgid')->eq($cgid)->fi()
                ->beginIF(is_array($cgid))->andWhere('cgid')->in($cgid)->fi()
                ->beginIF(!empty($startDate))->andWhere('date')->ge($startDate)->fi()
                ->get();
        }
        $query = join(' UNION ALL ', $queries);

        $sql = $this->dao->select('*')->from(TABLE_IM_MESSAGE);
        $sql->sqlobj->sql = 'SELECT * FROM (' . $query . ') as t';

        /* Assemble order by and pager stuff. */
        $messages = $sql
            ->orderBy('id_desc')
            ->beginIF($pager != null)->page($pager)->fi()
            ->fetchAll();

        return $this->format($this->decodeMessages($messages));
    }

    /**
     * Fetch chat and message information if chat's last read is greater than user's last known.
     *
     * @param  int     $lastKnownMessage
     * @param  int     $userID
     * @access public
     * @return array   of chat => lastReadMessage, empty if all last reads are not greater than last known.
     */
    public function getMissedByLastKnown($lastKnownMessage, $userID = 0)
    {
        $missedMessageChats = $this->dao->select('cgid,lastReadMessage')->from(TABLE_IM_CHATUSER)
            ->where('user')->eq($userID)
            ->andWhere('lastReadMessage')->gt($lastKnownMessage)
            ->fetchPairs();

        $messageIDs = array();
        foreach($missedMessageChats as $cgid => $lastReadID)
        {
            $chatMessageIDs = $this->getListAroundIDForUser($cgid, $lastReadID, true, 5, $userID, true);
            $messageIDs = array_merge($messageIDs, $chatMessageIDs);
        }

        $unreadIDs = $this->dao->select('message')->from(TABLE_IM_MESSAGESTATUS)
            ->where('user')->eq($userID)
            ->andWhere('status')->eq('waiting')
            ->fetchAll();

        $missedIDs = array_diff($messageIDs, array_keys($unreadIDs));
        if(empty($missedIDs)) return array();

        return $this->getList('', $missedIDs);
    }

    /**
     * Get offline messages.
     *
     * @param  bool   $full
     * @param  int    $userID
     * @access public
     * @return array
     */
    public function getOfflineList($full = false, $userID = 0)
    {
        $messageIDs = $this->dao->select('message')->from(TABLE_IM_MESSAGESTATUS)
            ->where('user')->eq($userID)
            ->andWhere('status')->eq('waiting')
            ->fetchPairs('message');
        if(empty($messageIDs)) return array();

        if(!$full)
        {
            $firstRecordID = $this->dao->select('MIN(id)')->from(TABLE_IM_MESSAGE)->fetch('MIN(id)');
            $messageIDs = array_filter(
                $messageIDs,
                function($id) use ($firstRecordID)
                {
                    return $id >= $firstRecordID;
                }
            );
        }
        if(empty($messageIDs)) return array();

        $messages = $this->getList('', $messageIDs, null, '', '!notify', false);

        $messageIDs = array();
        foreach($messages as $message) array_push($messageIDs, $message->id);

        $this->dao->delete()->from(TABLE_IM_MESSAGESTATUS)
            ->where('user')->eq($userID)
            ->andWhere('message')->in($messageIDs)
            ->exec();

        return $this->format($messages);
    }

    /**
     * Get history list.
     *
     * @param  object   $user
     * @param  string   $device
     * @access public
     * @return array
     */
    public function getHistoryList($user, $device = 'desktop')
    {
        $gids      = $this->loadModel('im')->chat->getGidListByUserID($user->id);
        $startDate = $this->loadModel('setting')->getItem("owner={$user->account}&module=common&section=lastLogin&key={$device}");
        if(!empty($startDate) && !empty($gids)) return $this->getListByCgid($gids, null, $startDate);
    }


    /**
     * Create a output of broadcast.
     *
     * @param  string $type
     * @param  object $chat
     * @param  array  $onlineUsers
     * @param  int    $userID
     * @param  array  $members
     * @param  bool   $setReminders  if true, send members and userID as property "reminders" in $message->data.
     * @access public
     * @return object
     */
    public function createBroadcast($type, $chat, $onlineUsers, $userID, $members = array(), $setReminders = false)
    {
        $adminUsers = array();

        $message              = new stdclass();
        $message->gid         = imModel::createGID();
        $message->cgid        = $chat->gid;
        $message->type        = 'broadcast';
        $message->contentType = 'text';
        $message->content     = $this->getBroadcastContent($type, $chat, $userID, $members);
        $message->date        = helper::now();
        $message->user        = $userID;

        if($setReminders)
        {
            $membersData = array_merge($members, array($userID));
            $message->data = json_encode(array('reminders' => $membersData));
        }
        /* If quit a chat, only send broadcast to the admins or the created user of chat. */
        if($type == 'leaveChat')
        {
            if($chat->admins)
            {
                if(is_array($chat->admins))
                {
                    $adminUsers = array_map(function($value){
                        return trim($value);
                    }, $chat->admins);
                }
                if(is_string($chat->admins))
                {
                   $adminUsers = explode(',', trim($chat->admins, ','));
                }
            }
            if(!$adminUsers)
            {
                $user = $this->loadModel('user')->getByAccount($chat->createdBy);
                if($user) $adminUsers = array($user->id);
            }
            $users       = $this->loadModel('im')->user->getList($status = 'online', $adminUsers);
            $onlineUsers = array_keys($users);
        }

        /* Save broadcast to im_message. */
        $messages     = $this->create(array($message), $userID);
        $offlineUsers = $this->loadModel('im')->user->getList($status = 'offline', $chat->members);
        $this->saveOfflineList($messages, array_keys($offlineUsers));

        $output = new stdclass();
        $output->method = 'messagesend';

        if(dao::isError())
        {
            $output->result  = 'fail';
            $output->message = 'Send message failed.';
        }
        else
        {
            $output->result = 'success';
            $output->users  = $onlineUsers;
            $output->data   = $messages;
        }

        return $output;
    }

	/**
     * Get content of broadcast.
     *
     * @param  string $type
     * @param  object $chat
     * @param  int    $userID
     * @param  array  $members
     * @access public
     * @return string
     */
    public function getBroadcastContent($type, $chat, $userID, $members)
    {
        $user        = $this->loadModel('im')->userGetByID($userID);
        $userName    = empty($user->realname) ? $user->account : $user->realname;
        $userMention = "[@$userName](@#$user->id)";

        if(stripos($type, 'changeChatOwnership') === 0)
        {
            $nameInMarkdown = preg_replace('/([#\\`*_{}\[\]\(\)\+\-\.!])/i', '\\\\$1', $chat->name);
            return sprintf($this->lang->im->broadcast->$type, $nameInMarkdown, $chat->gid, $userMention);
        }

        if($type == 'chatMerged')
        {
            return sprintf($this->lang->im->broadcast->$type, $chat->name, $chat->intoName);
        }

        if($type == 'mergeChat')
        {
            return sprintf($this->lang->im->broadcast->$type, $chat->name);
        }

        if($type == 'renamePrivate')
        {
            $nameInMarkdown = preg_replace('/([#\\`*_{}\[\]\(\)\+\-\.!])/i', '\\\\$1', $chat->name);
            return sprintf($this->lang->im->broadcast->$type, $nameInMarkdown, $chat->gid);
        }

        if($type == 'createChat' or $type == 'renameChat')
        {
            $nameInMarkdown = preg_replace('/([#\\`*_{}\[\]\(\)\+\-\.!])/i', '\\\\$1', $chat->name);
            return sprintf($this->lang->im->broadcast->$type, $userMention, $nameInMarkdown, $chat->gid);
        }

        if(in_array($type, array('inviteUser', 'createConferenceInvitation', 'mergeChatWithMembers')))
        {
            $memberMentions = array();
            $membersList    = $this->im->userGetList('', $members, true, true);
            foreach($membersList as $member)
            {
                $memberName       = empty($member->realname) ? $member->account : $member->realname;
                $memberMentions[] = "[@$memberName](@#$member->id)";
            }
            $memberMentions = implode($this->lang->im->connector, $memberMentions);

            if($type == 'mergeChatWithMembers') return sprintf($this->lang->im->broadcast->mergeChatWithMembers, $chat->name, $memberMentions);

            return sprintf($this->lang->im->broadcast->$type, $userMention, $memberMentions);
        }

        if($type == 'archiveChat' or $type == 'unarchiveChat')
        {
            $nameInMarkdown = preg_replace('/([#\\`*_{}\[\]\(\)\+\-\.!])/i', '\\\\$1', $chat->name);
            return sprintf($this->lang->im->broadcast->$type, $userMention, $nameInMarkdown);
        }

        return sprintf($this->lang->im->broadcast->$type, $userMention);
    }

    /**
     * Retract one message.
     *
     * @param  string  $gid
     * @param  boolean $byAdmin
     * @param  int     $deletedBy
     * @access public
     * @return array
     */
    public function retract($gid = '', $byAdmin = false, $deletedBy = 0)
    {
        $message = $this->dao->select('id, gid, cgid, `index`, user, date, data, deleted, type, contentType')->from(TABLE_IM_MESSAGE)->where('gid')->eq($gid)->fetch();

        $archiveDate = $this->dao->select('archiveDate')->from(TABLE_IM_CHAT)->where('gid')->eq($message->cgid)->fetch('archiveDate');
        if($archiveDate !== '0000-00-00 00:00:00') return array();

        $messageLife = (strtotime(helper::now()) - strtotime($message->date)) / 60;
        if($messageLife <= $this->config->im->retract->validTime && $message->user == $deletedBy)
        {
            $message->deleted = 1;
            $this->dao->update(TABLE_IM_MESSAGE)->set('deleted')->eq($message->deleted)->where('gid')->eq($gid)->exec();
        }
        else if($byAdmin)
        {
            $messageData = empty($message->data) ? new stdClass() : json_decode($message->data);
            $bySelf = $message->user == $deletedBy;
            if(!$bySelf)
            {
                $messageData->deletedBy = $deletedBy;
                $message->data = $messageData;
            }
            $message->deleted = 1;
            if($bySelf) $this->dao->update(TABLE_IM_MESSAGE)->set('deleted')->eq($message->deleted)->where('gid')->eq($gid)->exec();
            else        $this->dao->update(TABLE_IM_MESSAGE)->set('deleted')->eq($message->deleted)->set('data')->eq(json_encode($messageData))->where('gid')->eq($gid)->exec();
        }

        return $this->format(array($message));
    }

    /**
     * Save offline messages.
     *
     * @param  array  $messages
     * @param  array  $users
     * @access public
     * @return bool
     */
    public function saveOfflineList($messages = array(), $users = array())
    {
        /* Prevent deleted users from being stored in TABLE_MESSAGESTATUS. */
        $deletedUsers = $this->dao->select('id')->from(TABLE_USER)->where('deleted')->eq('1')->fetchPairs();
        $users = array_values(array_diff($users, $deletedUsers));

        foreach($messages as $message)
        {
            $this->saveStatus($users, $message->id, 'waiting');
        }
        return !dao::isError();
    }

    /**
     * Save message status.
     *
     * @param  array    $users
     * @param  int      $message
     * @param  string   $status
     * @access public
     * @return bool
     */
    public function saveStatus($users, $message, $status = 'waiting')
    {
        if(empty($users) || empty($message)) return false;

        foreach($users as $user)
        {
            $data = new stdclass();
            $data->user    = $user;
            $data->message = $user;
            $data->status  = $user;
            $this->dao->replace(TABLE_IM_MESSAGESTATUS)->data($data)->exec();
            if(dao::isError()) return false;
        }

        return true;
    }

    /**
     * Get notify.
     * @access public
     * @return array
     */
    public function getNotifyList()
    {
        $onlineUsers = $this->loadModel('im')->user->getList('online');
        if(empty($onlineUsers)) return array();
        $onlineUsers = array_keys($onlineUsers);

        $messageUserPairsData = $this->dao->select('message,user')->from(TABLE_IM_MESSAGESTATUS)
                            ->where('status')->eq('waiting')
                            ->andWhere('user')->in($onlineUsers)
                            ->fetchAll();
        if(empty($messageUserPairsData)) return array();

        $messageUserPairs = array();
        foreach($messageUserPairsData as $data)
        {
            if(isset($messageUserPairs[$data->message]))
            {
                $messageUserPairs[$data->message][] = $data->user;
                continue;
            }
            $messageUserPairs[$data->message] = array($data->user);
        }
        $notifyMessages = $this->getList('', array_keys($messageUserPairs), null, '', 'notify', false);
        if(empty($notifyMessages)) return array();

        $messageIDs = array();
        foreach($notifyMessages as $message) $messageIDs[] = $message->id;
        $messageUserPairs = array_intersect_key($messageUserPairs, array_flip($messageIDs));

        $notifications = $this->formatNotify($notifyMessages);
        $data          = array();
        $messages      = array();
        foreach($notifications as $message)
        {
            foreach($messageUserPairs[$message->id] as $userID)
            {
                $messages[$userID][] = $message->id;
                $data[$userID][]     = $message;
            }
        }

        foreach($messages as $userID => $message)
        {
            $this->dao->delete()->from(TABLE_IM_MESSAGESTATUS)
                ->where('message')->in($message)
                ->andWhere('user')->eq($userID)
                ->exec();
        }
        return $data;
    }

    /**
     * Get offline notify.
     * @param $userID
     * @return array
     */
    public function getNotifyByUserID($userID)
    {
        $messageIDs = $this->dao->select('message')->from(TABLE_IM_MESSAGESTATUS)
            ->where('user')->eq($userID)
            ->andWhere('status')->eq('waiting')
            ->fetchPairs('message');
        if(empty($messageIDs)) return array();

        $messages = $this->getList('', $messageIDs, null, '', 'notify', false);
        if(empty($messages)) return array();
        $notifications = $this->formatNotify($messages);

        $messages = array();
        foreach($notifications as $message) $messages[] = $message->id;

        $this->dao->delete()->from(TABLE_IM_MESSAGESTATUS)
            ->where('message')->in($messages)
            ->andWhere('user')->eq($userID)
            ->exec();
        return $notifications;
    }

    /**
     * Foramt messages for notify.
     * @param object $messages
     * @access public
     * @return array
     */
    public function formatNotify($messages)
    {
        $notifications = array();
        foreach($messages as $message)
        {
            $data = new stdClass();
            $messageData = json_decode($message->data);
            $data->id          = $message->id;
            $data->gid         = $message->gid;
            $data->cgid        = $message->cgid;
            $data->type        = $message->type;
            $data->content     = $message->deleted ? '' : $message->content;
            $data->date        = strtotime($message->date);
            $data->contentType = $message->contentType;
            $data->title       = $messageData->title;
            $data->subtitle    = $messageData->subtitle;
            $data->url         = $messageData->url;
            $data->actions     = $messageData->actions;
            $data->sender      = $messageData->sender;
            $data->users       = $messageData->target;

            if($data->cgid != 'notification' && !empty($message->index)) $data->index = $message->index;

            $notifications[] = $data;
        }
        return $notifications;
    }

    /**
     * create a bot welcome message.
     * @param int  $userID
     * @param bool $needUpdate
     * @return void
     */
    public function createXuanbotWelcomeNotify($userID, $needUpdate = false)
    {
        $sender = $this->loadModel('im')->bot->createDefaultBotSender();

        if($needUpdate) $this->createNotify(array($userID), $this->lang->im->bot->upgradeWelcome->title, '', $this->lang->im->bot->upgradeWelcome->content, 'text', $this->lang->im->bot->upgradeWelcome->link, array(), $sender);
        $this->createNotify(array($userID), $this->lang->im->bot->welcome->title, '', $this->lang->im->bot->welcome->content, 'text', $this->lang->im->bot->welcome->link, array(), $sender);
    }

    public function createDetachedConferenceEnableNotify()
    {
        $sender = $this->loadModel('im')->bot->createDefaultBotSender();
        $userModule = $this->im->user;
        $allUsers = $userModule->getList();
        $allUsers = array_keys($allUsers);
        $newClientUsers = array_filter($allUsers, function($user) use($userModule)
        {
            return $userModule->isDeviceVersionGe($user, '7.2.beta');
        });
        $oldClientUsers = array_diff($allUsers, $newClientUsers);

        $this->createNotify($newClientUsers, $this->lang->im->detachedConferenceUpgradeMessage->newClient->title, '', $this->lang->im->detachedConferenceUpgradeMessage->newClient->content, 'text', $this->lang->im->bot->upgradeWelcome->link, array(), $sender);

        $this->createNotify($oldClientUsers, $this->lang->im->detachedConferenceUpgradeMessage->oldClient->title, '', $this->lang->im->detachedConferenceUpgradeMessage->oldClient->content, 'text', $this->lang->im->bot->upgradeWelcome->link, array(), $sender);
    }

    /**
     * Insert message for notify.
     * @param  string $target
     * @param  string $title
     * @param  string $subtitle
     * @param  string $content
     * @param  string $contentType
     * @param  string $url
     * @param  array  $actions
     * @param  int    $sender
     * @access public
     * @return bool
     */
    public function createNotify($target = '', $title = '', $subtitle = '', $content = '', $contentType = 'text', $url = '', $actions = array(), $sender = 0)
	{
        /* Check if target is a chat gid or a list of users. */
        if(is_array($target))
        {
            $cgid = 'notification';
        }
        else
        {
            $cgid   = $target;
            $target = $this->dao->select('user')->from(TABLE_IM_CHATUSER)
                    ->where('cgid')->eq($target)
                    ->andWhere('quit')->eq('0000-00-00 00:00:00')
                    ->fetchPairs('user');
        }
        $users = $this->loadModel('im')->user->getList('', $target);

		$info = array();
		$info['title']    = $title;
		$info['subtitle'] = $subtitle;
		$info['url']	  = $url;
		$info['actions']  = $actions;
		$info['sender']	  = $sender;
		$info['target']	  = array_keys($users);

		$notify = new stdClass();
		$notify->gid		 = imModel::createGID();
		$notify->cgid		 = $cgid;
		$notify->user		 = 0;
		$notify->date		 = helper::now();
		$notify->type		 = 'notify';
		$notify->content     = $content;
		$notify->contentType = $contentType;
        $notify->data		 = json_encode($info);

        $msgIndex = $this->dao->select('`lastMessageIndex` + 1')->from(TABLE_IM_CHAT)->where('gid')->eq($cgid)->fetch('`lastMessageIndex` + 1');
        if(empty($msgIndex)) $msgIndex = 1;
        $notify->index = $msgIndex;

		$this->dao->insert(TABLE_IM_MESSAGE)->data($this->encodeMessage($notify))->exec();
        $message = $this->dao->lastInsertID();

        $this->dao->update(TABLE_IM_CHAT)
            ->set('lastActiveTime')->eq(helper::now())
            ->set('lastMessage')->eq($message)
            ->set('lastMessageIndex')->eq($msgIndex)
            ->where('gid')->eq($cgid)->exec();

		$this->saveStatus($info['target'], $message, 'waiting');
        return !dao::isError();
    }

    /**
     * Add offline messages according to the gid of messages that failed to be sent.
     * @param  array  $sendfail
     * @access public
     * @return bool
     */
    public function sendFailures($sendfail = array())
    {
        foreach($sendfail as $userID => $gid)
        {
            if(empty($gid)) continue;
            $idList   = $this->dao->select('id')->from(TABLE_IM_MESSAGE)->where('gid')->in($gid)->fetchPairs();
            $messages = $this->getList('', $idList);
            $this->saveOfflineList($messages, array($userID));
        }
        return !dao::isError();
    }

    /**
     * Get message count for block.
     *
     * @access public
     * @return object
     */
    public function getCountForBlock()
    {
        $masterTableTotal = $this->dao->select('COUNT(*)')->from(TABLE_IM_MESSAGE)->where('deleted')->eq('0')->fetch('COUNT(*)');
        $partitionsTotal  = $this->dao->select('SUM(`count`)')->from(TABLE_IM_CHAT_MESSAGE_INDEX)->fetch('SUM(`count`)');

        $dayCount   = $this->dao->select("SUM(deleted = '0' AND date > '".date('Y-m-d H:i', strtotime('-1 day'))."')")->from(TABLE_IM_MESSAGE)->fetchPairs();
        $hourCount  = $this->dao->select("SUM(deleted = '0' AND date > '".date('Y-m-d H:i', strtotime('-1 hour'))."')")->from(TABLE_IM_MESSAGE)->fetchPairs();

        $count = new stdClass();
        $count->total = $masterTableTotal + ($partitionsTotal || 0);
        $count->day   = reset($dayCount);
        $count->hour  = reset($hourCount);

        if(empty($count->total))
        {
            $count->total = 0;
            $count->day = 0;
            $count->hour = 0;
        }

        return $count;
    }

    /**
     * Get all message tables.
     *
     * @access public
     * @return array
     */
    public function getAllTables()
    {
        $tables = $this->dao->select('tableName')->from(TABLE_IM_MESSAGE_INDEX)->fetchAll();

        foreach($tables as $key => $table) $tables[$key]->messages = '';

        $master = new stdclass;
        $master->tableName = TABLE_IM_MESSAGE;
        $master->messages  = '';
        $tables[] = $master;

        return $tables;
    }

    /**
     * Get all message tables with cgid.
     *
     * @param  string $cgid
     * @access public
     * @return array
     */
    public function getChatAllTables($cgid = '')
    {
        if($cgid == '') return array();
        $tables = $this->dao->select('DISTINCT tableName')->from(TABLE_IM_CHAT_MESSAGE_INDEX)->fetchAll();

        foreach($tables as $key => $table) $tables[$key]->messages = '';

        $master = new stdclass;
        $master->tableName = TABLE_IM_MESSAGE;
        $master->messages  = '';
        $tables[] = $master;

        return $tables;
    }

    /**
     * Get message table names by message IDs.
     *
     * @param  array  $messageIDs
     * @access public
     * @return array
     */
    public function getTableByMessages($messageIDs)
    {
        $tables = array();
        $indices = $this->dao->select('tableName,start,end')->from(TABLE_IM_MESSAGE_INDEX)->fetchAll('tableName');

        $processedIDs = array();
        foreach($indices as $index)
        {
            $min = $index->start;
            $max = $index->end;
            $ids = array_filter(
                $messageIDs,
                function($id) use ($min, $max)
                {
                    return $id >= $min && $id <= $max;
                }
            );
            if(!empty($ids))
            {
                $result = new stdclass();
                $result->tableName = $index->tableName;
                $result->messages  = $ids;
                $tables[$index->tableName] = $result;

                $processedIDs = array_merge($processedIDs, $ids);
            }
        }

        $unindexed = array_diff($messageIDs, $processedIDs);
        if(!empty($unindexed))
        {
            $result = new stdclass();
            $result->tableName = TABLE_IM_MESSAGE;
            $result->messages  = $unindexed;
            $tables[TABLE_IM_MESSAGE] = $result;
        }

        return $tables;
    }

    /**
     * Get chat message table names by message indexes.
     *
     * @param  string $cgid
     * @param  array  $indexes
     * @access public
     * @return array
     */
    public function getChatTablesByChatIndexes($cgid, $indexes)
    {
        if($cgid == '') return array();
        $indexIds = $indexes;
        $tables   = array();
        $indices  = $this->dao->select('tableName,startIndex,endIndex')->from(TABLE_IM_CHAT_MESSAGE_INDEX)->where('gid')->eq($cgid)->fetchAll('tableName');

        $processedIDs = array();
        foreach($indices as $index)
        {
            $min = $index->startIndex;
            $max = $index->endIndex;
            $ids = array_filter(
                $indexes,
                function($id) use ($min, $max)
                {
                    return $id >= $min && $id <= $max;
                }
            );
            $indexes = array_diff($indexes, $ids);
            if(!empty($ids))
            {
                $result = new stdclass();
                $result->tableName = $index->tableName;
                $result->messages  = $ids;
                $tables[$index->tableName] = $result;

                $processedIDs = array_merge($processedIDs, $ids);
            }
        }

        $unindexed = array_diff($indexIds, $processedIDs);
        if(!empty($unindexed))
        {
            $result = new stdclass();
            $result->tableName = TABLE_IM_MESSAGE;
            $result->messages  = $unindexed;
            $tables[TABLE_IM_MESSAGE] = $result;
        }

        return $tables;
    }

    /**
     * Get tables by start (and / or) end date).
     *
     * @param  string $startDate
     * @param  string $endDate
     * @access public
     * @return array
     */
    public function getTablesByDateRange($startDate = '', $endDate = '')
    {
        $tables = $this->dao->select('tableName,startDate,endDate')->from(TABLE_IM_MESSAGE_INDEX)
            ->where('1')
            ->beginIF(!empty($startDate))->andWhere('endDate')->ge($startDate)->fi()
            ->beginIF(!empty($endDate))->andWhere('startDate')->le($endDate)->fi()
            ->fetchAll('tableName');

        if(empty($tables)) $appendMaster = true;

        /* If endDate is even later than the max endDate we have in the index, append the master table. */
        elseif(!empty($endDate))
        {
            $maxEndDate = max(array_map(
                function($t)
                {
                    return $t->endDate;
                },
                $tables
            ));
            if($maxEndDate < $endDate) $appendMaster = true;
        }

        if(isset($appendMaster))
        {
            $master = new stdclass();
            $master->tableName = TABLE_IM_MESSAGE;
            $master->startDate = isset($maxEndDate) ? $maxEndDate : '0000-00-00 00:00:00';
            $master->endDate   = '9999-12-31 23:59:59';
            $tables[] = $master;
        }

        return $tables;
    }

    /**
     * Get chat tables by start (and / or) end date).
     *
     * @param  string $cgid
     * @param  string $startDate
     * @param  string $endDate
     * @access public
     * @return array
     */
    public function getChatTablesByDateRange($cgid = '', $startDate = '', $endDate = '')
    {
        if($cgid == '') return array();
        $tables = $this->dao->select('tableName,startDate,endDate')->from(TABLE_IM_CHAT_MESSAGE_INDEX)
            ->where('gid')->eq($cgid)
            ->beginIF(!empty($startDate))->andWhere('endDate')->ge($startDate)->fi()
            ->beginIF(!empty($endDate))->andWhere('startDate')->le($endDate)->fi()
            ->fetchAll('tableName');

        if(empty($tables)) $appendMaster = true;

        /* If endDate is even later than the max endDate we have in the index, append the master table. */
        elseif(!empty($endDate))
        {
            $maxEndDate = max(array_map(
                function($t)
                {
                    return $t->endDate;
                },
                $tables
            ));
            if($maxEndDate < $endDate) $appendMaster = true;
        }

        if(isset($appendMaster))
        {
            $master = new stdclass();
            $master->tableName = TABLE_IM_MESSAGE;
            $master->startDate = isset($maxEndDate) ? $maxEndDate : '0000-00-00 00:00:00';
            $master->endDate   = '9999-12-31 23:59:59';
            $tables[] = $master;
        }

        return $tables;
    }

    /**
     * Get tables by gids of chats.
     *
     * @param  array|string $cgids
     * @access public
     * @return array
     */
    public function getTablesByChats($cgids)
    {
        return $this->dao->select('DISTINCT tableName')->from(TABLE_IM_CHAT_MESSAGE_INDEX)
            ->where('gid')->in($cgids)
            ->fetchPairs();
    }

    /**
     * Mark an ongoing partition opreation.
     *
     * @param  bool   $ongoing
     * @access public
     * @return bool   true if successfully marked, otherwise there already is another ongoing opreation.
     */
    public function markOngoingPartition($ongoing)
    {
        $this->loadModel('setting');
        $lastStatus = $this->setting->getItem("owner=system&module=common&section=partition&key=ongoing");
        if($ongoing != $lastStatus)
        {
            $this->setting->setItem('system.common.partition.ongoing', $ongoing);
            return true;
        }
        return false;
    }

    /**
     * Check if message count in the master table exceeds twice the partition size.
     *
     * @access public
     * @return bool   true if partition is needed.
     */
    public function needPartition()
    {
        $currentID = $this->dao->select('id')->from(TABLE_IM_MESSAGE)->orderBy('id_desc')->limit(1)->fetch('id');
        $lastIndexID = $this->dao->select('end')->from(TABLE_IM_MESSAGE_INDEX)->orderBy('end_desc')->limit(1)->fetch('end');

        return $currentID > ($lastIndexID + 2 * $this->config->im->partition->messagePerTable);
    }

    /**
     * Backup master message table.
     *
     * @param  int    $fromID
     * @param  int    $toID
     * @access public
     * @return int    count of message affected.
     */
    public function backupMasterTable($fromID, $toID = 0)
    {
        $insertStmt = $this->dao->insert(TABLE_IM_MESSAGE_BACKUP)->get();
        $selectStmt = $this->dao->select('*')->from(TABLE_IM_MESSAGE)
            ->where('id')->ge($fromID)
            ->beginIF(!empty($toID))->andWhere('id')->le($toID)->fi()
            ->get();
        $stmt = substr($insertStmt, 0, -4) . $selectStmt;
        return $this->dao->exec($stmt);
    }

    /**
     * Delete a large amount of rows from im_message table
     * by selecting the rest into a new table and replace current table with the new one.
     *
     * @param  int    $end
     * @param  int    $start
     * @access public
     * @return bool
     */
    public function deleteFromMasterTable($end, $start = 0)
    {
        $tmpTable = str_replace(array('`', '-'), '', sprintf("%s_%s", TABLE_IM_MESSAGE, 'tmp_' . helper::today()));
        $oldTable = str_replace(array('`', '-'), '', sprintf("%s_%s", TABLE_IM_MESSAGE, 'old_' . helper::today()));

        $zdb = $this->app->loadClass('zdb');
        $fields = $zdb->getTableFields(TABLE_IM_MESSAGE);
        $zdb->createTable($tmpTable, $fields);

        $insertStmt = $this->dao->insert($tmpTable)->get();
        $selectStmt = $this->dao->select('*')->from(TABLE_IM_MESSAGE)
            ->where('id')->gt($end)
            ->beginIF($start)->orWhere('id')->lt($start)->fi()
            ->get();
        $stmt = substr($insertStmt, 0, -4) . $selectStmt;
        $this->dao->exec($stmt);

        $renameQuery  = 'RENAME TABLE ' . TABLE_IM_MESSAGE . ' TO ' . $oldTable . '; ';
        $renameQuery .= 'RENAME TABLE ' . $tmpTable . ' TO ' . TABLE_IM_MESSAGE . '; ';
        $this->dao->exec($renameQuery);

        $dropQuery = "DROP TABLE $oldTable;";
        $this->dao->exec($dropQuery);

        return !dao::isError();
    }

    /**
     * Partition master message table into smaller tables.
     *
     * @access public
     * @return bool
     */
    public function partitionTable()
    {
        /* Check if creation of a new partition is needed. */
        if(!$this->needPartition()) return false;

        /* Set memory limit to avoid OOMs. */
        ini_set('memory_limit', '256M');

        /* Start a transaction. */
        $this->dao->begin();

        /* Create a new message table. */
        $zdb = $this->app->loadClass('zdb');
        $fields = $zdb->getTableFields(TABLE_IM_MESSAGE);
        $fields['id']->extra = ''; // disable auto_increment.
        $currentTable = $this->dao->select('id,end')->from(TABLE_IM_MESSAGE_INDEX)->orderBy('id_desc')->limit(1)->fetch();
        $newTable = str_replace('`', '', sprintf("%s_%s", TABLE_IM_MESSAGE, ++$currentTable->id));
        $zdb->createTable($newTable, $fields);

        /* Select messages and insert into the new table. */
        $insertStmt = $this->dao->insert($newTable)->get();
        $selectStmt = $this->dao->select('*')->from(TABLE_IM_MESSAGE)
            ->where('id')->gt($currentTable->end)
            ->andWhere('id')->le($currentTable->end + $this->config->im->partition->messagePerTable)
            ->get();
        $stmt = substr($insertStmt, 0, -4) . $selectStmt;
        $this->dao->exec($stmt);

        /* Index the new table. */
        $this->reindex($newTable);

        /* Backup the master table. */
        $this->backupMasterTable($currentTable->end + 1, $currentTable->end + $this->config->im->partition->messagePerTable);

        /* Delete messages from master table on success. */
        $this->deleteFromMasterTable($currentTable->end + $this->config->im->partition->messagePerTable);

        /* End the transaction. */
        $this->dao->commit();

        return !dao::isError();
    }

    /**
     * Index partitions.
     *
     * @param  string $table  table to index
     * @access public
     * @return void
     */
    public function reindex($table = '')
    {
        $MAXID    = 'MAX(id)';
        $MINID    = 'MIN(id)';
        $MAXINDEX = 'MAX(`index`)';
        $MININDEX = 'MIN(`index`)';
        $IDDATE   = 'id,date';

        $messageMeta = new stdclass();
        $firstRecord = $this->dao->select($IDDATE)->from($table)->orderBy('id')->limit(1)->fetch();
        $lastRecord  = $this->dao->select($IDDATE)->from($table)->orderBy('id_desc')->limit(1)->fetch();
        $chats       = $this->dao->select('DISTINCT(cgid)')->from($table)->fetchPairs();
        $messageMeta->tableName = $table;
        $messageMeta->start     = $firstRecord->id;
        $messageMeta->end       = $lastRecord->id;
        $messageMeta->startDate = $firstRecord->date;
        $messageMeta->endDate   = $lastRecord->date;
        $messageMeta->chats     = ',' . join(',', $chats) . ',';
        $this->dao->insert(TABLE_IM_MESSAGE_INDEX)->data($messageMeta)->exec();

        $chatsInfo = $this->dao->select('cgid,MAX(id),MIN(id),MAX(`index`),MIN(`index`),count(*)')->from($table)->groupBy('cgid')->fetchAll();
        $messages = array();
        foreach($chatsInfo as $info)
        {
            $messages[] = $info->{$MAXID};
            $messages[] = $info->{$MINID};
        }
        $messages = array_unique($messages);
        $messageDates = $this->dao->select($IDDATE)->from($table)->where('id')->in($messages)->fetchPairs('id');

        $values = array();
        foreach($chatsInfo as $info)
        {
            $meta = array();
            $meta[] = $table;
            $meta[] = $info->cgid;
            $meta[] = $info->{$MINID};
            $meta[] = $info->{$MAXID};
            $meta[] = $info->{$MININDEX};
            $meta[] = $info->{$MAXINDEX};
            $meta[] = $messageDates[$info->{$MINID}];
            $meta[] = $messageDates[$info->{$MAXID}];
            $meta[] = $info->{'count(*)'};

            $data = "('" . join("','", $meta) . "')";
            $values[] = $data;
        }
        $insertStmt = $this->dao->insert(TABLE_IM_CHAT_MESSAGE_INDEX)->get();
        $insertStmt = substr($insertStmt, 0, -4) . '(`tableName`,`gid`,`start`,`end`,`startIndex`,`endIndex`,`startDate`,`endDate`,`count`) VALUES ' . join(',', $values);
        $this->dao->exec($insertStmt);
    }

    /**
     * codec string with rot47.
     *
     * @access public
     * @param  string $str  string to be rot47
     * @return string
     */
    public function codecWithRot47($str)
    {
        return strtr($str, '!"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~', 'PQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~!"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNO');
    }

    /**
     * decode text with rot47 & base64.
     *
     * @access public
     * @param  string $text  text to be rot47
     * @return string
     */
    public function decodeText($text)
    {
		$rot47Encoded = $this->codecWithRot47($text);
		return base64_decode($rot47Encoded);
    }

    /**
     * decode chat messages.
     *
     * @access public
     * @param  array $messages  messages to be decode
     * @return array
     */
    public function decodeMessages($messages)
    {
        if(isset($this->config->xuanxuan->messageEncrypt) && ($this->config->xuanxuan->messageEncrypt == 'on') && commonModel::isLicensedMethod('im', 'messageEncrypt'))
        {
            $config = $this->config;
            return array_map(function($msg) use($config) {
				if(isset($msg->content) && isset($config->xuanxuan->lastUnEncryptMessageId) && intval($config->xuanxuan->lastUnEncryptMessageId) <= $msg->id)
				{
					$msg->content = $this->decodeText($msg->content);
				}
				return $msg;
            }, $messages);
        }
        return $messages;
    }

    /**
     * decode one chat message.
     *
     * @access public
     * @param  object $message  message to be decode
     * @return string
     */
    public function decodeMessage($message)
    {
        if(isset($this->config->xuanxuan->messageEncrypt) && ($this->config->xuanxuan->messageEncrypt == 'on') && commonModel::isLicensedMethod('im', 'messageEncrypt') && isset($this->config->xuanxuan->lastUnEncryptMessageId) && intval($this->config->xuanxuan->lastUnEncryptMessageId) <= $message->id)
        {
			$message->content = $this->decodeText($message->content);
        }
        return $message;
    }

    /**
	 * encode one chat message.
	 *
     * @access public
     * @param  object $message  message to be encode
     * @return object
     */
    public function encodeMessage($message)
    {
        if(isset($this->config->xuanxuan->messageEncrypt) && ($this->config->xuanxuan->messageEncrypt == 'on') && commonModel::isLicensedMethod('im', 'messageEncrypt'))
        {
            $message->content = $this->codecWithRot47(base64_encode($message->content));
        }
        return $message;
	}

    /**
	 * get last message id
	 *
     * @access public
     * @return int
     */
    public function getLastMessageId()
    {
        $lastMessageId = $this->dao->select('id')
            ->from(TABLE_IM_MESSAGE)
            ->orderBy('id desc')
            ->limit(1)
            ->fetch('id');
        if(!$lastMessageId)
        {
            $messagesCount = $this->dao->select('COUNT(*)')->from(TABLE_IM_MESSAGE)->fetch('COUNT(*)');
            if($messagesCount == 0)
            {
                $messagesIdxCount = $this->dao->select('COUNT(*)')->from(TABLE_IM_MESSAGE_INDEX)->fetch('COUNT(*)');
                if($messagesIdxCount > 0)
                {
                    $lastMessageId = $this->dao->select('MAX(end)')->from(TABLE_IM_MESSAGE_INDEX)->fetch('MAX(end)');
                }
                else
                {
                    $lastMessageId = 0;
                }
            }
        }
        return $lastMessageId;
    }
}
