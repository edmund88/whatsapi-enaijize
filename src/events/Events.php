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
	
		$servername = "mysql://bcdca4bcfe9366:bfc05937@us-cdbr-iron-east-01.cleardb.net/heroku_555506e4f7e7997?reconnect=true";
		$username = "bcdca4bcfe9366";
		$password = "bfc05937";
		$dbname = "heroku_555506e4f7e7997";

		$conn = new mysqli($servername, $username, $password, $dbname);

		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
	
		$sql = "SELECT * FROM messages WHERE sender = '" . $from . "'";
		$result = mysqli_query($conn, $sql);
		$lastmessage = mysqli_fetch_array($result, MYSQLI_ASSOC);

		if(!$lastmessage) {
			$sql = "INSERT INTO messages (sender, time_sent, message, new) VALUES ('" . $from . "', " . $time . ", '" . $body . "', 1)";
			if ($conn->query($sql) === TRUE) {
				echo "New record created successfully<br/>";
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
		}
		else {
			$sql = "UPDATE messages SET time_sent='" . $time . "', message='" . $body . "', prev_message='" . $lastmessage['message'] . "', new=1 WHERE sender='" . $from . "'";		
			if ($conn->query($sql) === TRUE) {
				echo "Record updated successfully<br/>";
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}			
		}
		
		$conn->close();
    }

    public function onSendMessage($mynumber, $target, $messageId, $node)
    {
        echo "Message $messageId sent to $target.</br>";
    }

}