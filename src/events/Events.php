<?php
require 'AllEvents.php';

class MyEvents extends AllEvents
{

    /**
     * This is a list of all current events. Uncomment the ones you wish to listen to.
     * Every event that is uncommented - should then have a function below.
     * @var array
     */
    public $activeEvents = array(
//        'onClose',
//        'onCodeRegister',
//        'onCodeRegisterFailed',
//        'onCodeRequest',
//        'onCodeRequestFailed',
//        'onCodeRequestFailedTooRecent',
        'onConnect',
//        'onConnectError',
//        'onCredentialsBad',
//        'onCredentialsGood',
        'onDisconnect',
//        'onDissectPhone',
//        'onDissectPhoneFailed',
//        'onGetAudio',
//        'onGetBroadcastLists',
//        'onGetError',
//        'onGetExtendAccount',
//        'onGetGroupMessage',
//        'onGetGroupParticipants',
//        'onGetGroups',
//        'onGetGroupsInfo',
//        'onGetGroupsSubject',
//        'onGetImage',
//        'onGetLocation',
        'onGetMessage',
//        'onGetNormalizedJid',
//        'onGetPrivacyBlockedList',
//        'onGetProfilePicture',
//        'onGetReceipt',
//        'onGetRequestLastSeen',
//        'onGetServerProperties',
//        'onGetServicePricing',
//        'onGetStatus',
//        'onGetSyncResult',
//        'onGetVideo',
//        'onGetvCard',
//        'onGroupCreate',
//        'onGroupisCreated',
//        'onGroupsChatCreate',
//        'onGroupsChatEnd',
//        'onGroupsParticipantsAdd',
//        'onGroupsParticipantsRemove',
//        'onLogin',
//        'onLoginFailed',
//        'onAccountExpired',
//        'onMediaMessageSent',
//        'onMediaUploadFailed',
//        'onMessageComposing',
//        'onMessagePaused',
//        'onMessageReceivedClient',
//        'onMessageReceivedServer',
//        'onPaidAccount',
//        'onPing',
//        'onPresence',
//        'onProfilePictureChanged',
//        'onProfilePictureDeleted',
        'onSendMessage',
 //       'onSendMessageReceived',
//        'onSendPong',
//        'onSendPresence',
//        'onSendStatusUpdate',
//        'onStreamError',
//        'onUploadFile',
//        'onUploadFileFailed',
    );

    public function onConnect($mynumber, $socket)
    {
        echo "Connected.</br>";
    }

    public function onDisconnect($mynumber, $socket)
    {
        echo "Disconnected.</br>";
    }

    public function onGetMessage( $mynumber, $from, $id, $type, $time, $name, $body )
    {
		$from = chop($from,'@s.whatsapp.net');
		$body = strtoupper($body);
	
		$servername = "ec2-50-19-236-178.compute-1.amazonaws.com";
		$port = "5432";
		$username = "dsrxtxfrujicul";
		$password = "abU_kylvHNeWMFJRwzPnQajP8P";
		$dbname = "db7ctk55jodcoo";
		
		$pg_connection_string = "dbname=" . $dbname . " host=" . $servername . " port=" . $port . " user=" . $username . " password=" . $password . " sslmode=require";

		$db = pg_connect($pg_connection_string) or die("Could not connect");
	
		$sql = "SELECT * FROM messages WHERE sender = '" . $from . "'";
		$result = pg_query($db, $sql);
		$lastmessage = pg_fetch_assoc($result);

		if(!$lastmessage) {
			$sql = "INSERT INTO messages (sender, time_sent, message, new) VALUES ('" . $from . "', " . $time . ", '" . $body . "', TRUE)";
			if (pg_query($db, $sql)) {
				echo "New record created successfully<br/>";
			} else {
				echo "Error: " . $sql;
			}
		}
		else {
			$sql = "UPDATE messages SET time_sent='" . $time . "', message='" . $body . "', prev_message='" . $lastmessage['message'] . "', new=TRUE WHERE sender='" . $from . "'";		
			if (pg_query($db, $sql)) {
				echo "Record updated successfully<br/>";
			} else {
				echo "Error: " . $sql;
			}			
		}
		
		pg_close($db);
    }

    public function onSendMessage($mynumber, $target, $messageId, $node)
    {
        echo "Message $messageId sent to $target.</br>";
    }

}