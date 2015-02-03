<?php
require_once(CLASSES_PATH."/lib/phpmailer/class.phpmailer.php");
require_once(CLASSES_PATH."/util/NgsSmarty.class.php");

class SMTPMailSender extends PHPMailer{

	private $config;

	public function __construct($config){

		$this->config = $config;
		  
		$this->IsSMTP(); // telling the class to use SMTP
		$this->Host       = $config["SMTP_Host"]; // SMTP server
		
		$this->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
		                                           // 1 = errors and messages
		                                           // 2 = messages only
		$this->CharSet	= 'iso-8859-1' ;
		$this->SMTPAuth   = true;                  // enable SMTP authentication
		$this->lang_type	= 'fr' ;
		$this->Port       = 26;                    // set the SMTP port for the GMAIL server
		$this->Username   = $config["SMTP_user"]; // SMTP account username
		$this->Password   = $config["SMTP_pass"];        // SMTP account password

		
	}
	
	public function send($from, $recipients, $subject, $template, $params = array(), $separate = false){

//--proccessing the message
		$smarty = new NgsSmarty($this->config["static"]);
		$smarty->assign("ns", $params);
		$message = $smarty->fetch($template);


		$this->SetFrom($from, "");

		$this->AddReplyTo($from, "");

		$this->Subject    = $subject;

		//$this->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

		$this->MsgHTML($message);






		// multiple recipients
		if($separate){
			$to ="";
			foreach($recipients as $recipient){
				$this->AddAddress($recipient, '');
				$this->Send();
			}
		}
		else{
			$to ="";
			foreach($recipients as $recipient){
				$this->AddAddress($recipient, '');
			}
			return parent::Send();
		}
	
		//$mail->AddAttachment("images/phpmailer.gif");      // attachment
		//$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment
		


	}
	
	
}
?>