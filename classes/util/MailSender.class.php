<?php
require_once(CLASSES_PATH."/util/NgsSmarty.class.php");

class MailSender{

	private $config;

	public function __construct($config){
		$this->config = $config;
	
	}
	
	public function send($from, $recipients, $subject, $template, $params = array(), $separate = false){

//--proccessing the message
		$smarty = new NgsSmarty($this->config["static"]);
		$smarty->assign("ns", $params);
		$message = $smarty->fetch($template);

		// To send HTML mail, the Content-type header must be set
		$headers  = "";//'MIME-Version: 1.0' . "\r\n";
		$headers  .= 'MIME-Version: 1.0' . "\n";
		//$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
		$headers .= 'Content-type: text/plain; charset=iso-8859-1' . "\n";

		// Additional headers
//			$headers .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";
			$headers .= "From: $from" . "\n";
//		$headers .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";
//		$headers .= 'From: Birthday Reminder <birthday@example.com>' . "\r\n";
//		$headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
//		$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";

		// multiple recipients
		if($separate){
			$to ="";
			foreach($recipients as $recipient){
				$to.="$recipient";
				mail($to, $subject, $message, $headers);
			}
		}
		else{
			$to ="";
			foreach($recipients as $recipient){
				$to.="$recipient".',';				
			}
			return mail($to, $subject, $message, $headers);
		}
	

	}
	
	
}
?>