#### Join Antispam Telegram Bot

Simple bot which will remove messages about joins to the chats if user name was too long (50+ characters)

Example of such spam messages:
![http://i.imgur.com/omcxMSY.png](http://i.imgur.com/omcxMSY.png)

To add bot and make it work you need follow instruction down below:

* Add bot [@joinSpamBot](http://t.me/joinSpamBot) to your chat/group (Open menu of bot and click on the "Add to group" link, then choose a chat/group)
* Then go to your group and add this bot as administrator with "Delete Messages" permission granted
* Done!

#### For developers

If you want to host this bot by yourself then you need to copy `.env.dist` as `.env` and set such params as bot telegram token, bot name, and url to your bot script (**not setHook.php**) and then just open setHook.php in browser. Good Luck!