<?php

namespace JoinSpamBot\Commands;

use Longman\TelegramBot\Entities\User;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Commands\SystemCommands\NewchatmembersCommand as BaseCommand;

class NewChatMembersCommand extends BaseCommand
{
    /**
     * Command execute method
     *
     * @return \Longman\TelegramBot\Entities\ServerResponse
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $message_id = $message->getMessageId();
        $members = $message->getNewChatMembers();

        if ($message->botAddedInChat()) {
            return Request::emptyResponse();
        }

        $needToRemoveMessage = false;
        foreach ($members as $member) {
            if ($this->isNameTooLong($this->getFullName($member))) {
                $needToRemoveMessage = true;
                break;
            }
        }

        if ($needToRemoveMessage === false) {
            return Request::emptyResponse();
        }

        return Request::deleteMessage([
            'chat_id' => $chat_id,
            'message_id' => $message_id,
        ]);
    }

    private function getFullName(User $user)
    {
        return $user->getFirstName() . ' ' . $user->getLastName();
    }

    private function isNameTooLong($name)
    {
        return mb_strlen($name) >= 50;
    }
}