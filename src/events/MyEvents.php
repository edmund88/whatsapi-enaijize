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
	
		$servername = "localhost";
		$username = "njabang_whatsapp";
		$password = "enaijize14";
		$dbname = "njabang_whatsapp";

		$conn = new mysqli($servername, $username, $password, $dbname);

		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} 
	
		$sql = "SELECT * FROM messages WHERE sender = '" . $from . "'";
		$result = mysqli_query($conn, $sql);
		$lastmessage = mysqli_fetch_array($result, MYSQLI_ASSOC);
	
        echo "Message received from $name ($from) at $time:<p>$body</p>";
		
		switch($body) {
			case "YES":
				if($lastmessage["message"] == "YES" || !$lastmessage)
					$reply = "Nothing to confirm. Please enter a valid command.";
				else	
					$reply = "Previous message: '" . $lastmessage["message"] . "' has been confirmed.";
				break;
			default:
				$reply = "You entered: '" . $body . "'. Please reply with 'YES' to confirm.";
		}
			
		
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'http://enaijize.com/whatsapp/send.php',
			CURLOPT_USERAGENT => 'Enaijize Event',
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => array(
				to => $from,
				msg => $reply
			)
		));

		$resp = curl_exec($curl);

		curl_close($curl);
		

		if(!$lastmessage) {
			$sql = "INSERT INTO messages (sender, time_sent, message) VALUES ('" . $from . "', " . $time . ", '" . $body . "')";
			if ($conn->query($sql) === TRUE) {
				echo "New record created successfully";
			} else {
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
		}
		else {
			$sql = "UPDATE messages SET time_sent='" . $time . "', message='" . $body . "' WHERE sender='" . $from . "'";		
			if ($conn->query($sql) === TRUE) {
				echo "Record updated successfully";
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