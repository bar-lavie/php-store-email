<?php
        $name = $_POST['full_name'];
        $name = trim(filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_STRING));

        $phone = trim($_POST['tel']);
        $phone = trim(filter_input(INPUT_POST, 'tel', FILTER_SANITIZE_STRING));
		
		$phoneNumber = preg_replace('/[^0-9]/', '', $phone);
		
		if (strlen($phoneNumber) == 11) {
        if (preg_replace("/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/", "$1", $phoneNumber) == 1) {
            $phone = preg_replace("/([0-9]{1})([0-9]{3})([0-9]{3})([0-9]{4})/", "$2-$3-$4", $phoneNumber);
        }
		} else if (strlen($phoneNumber) == 10) {
        $phone = preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $phoneNumber);
		}

		if (isset($_POST['email'])) {
            $email = trim($_POST['email']);
            $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
        } else {
            $email = '';
        }

		if ($_POST['loc'] !== 'LOCATION') {
            $location = trim($_POST['loc']);
            $location = trim(filter_input(INPUT_POST, 'loc', FILTER_SANITIZE_STRING));
        } else {
            $location = 'Not Relevant';
        }






	$dbconf = 'mysql:host=localhost;dbname=hackerus_landing_pages_leads;charset=utf8';
        $db = new PDO($dbconf, 'hackerus_arielus', 'FP-xqS~%CLit');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        $sql = "INSERT INTO landing_leads VALUES('',?,?,?,'','Web Site','hackerusa.com','',?,'','','',1,'',NOW(),NOW(),NOW())";
        $query = $db->prepare($sql);
        $data = [$name, $phone, $email ,$location];
       	$query->execute($data);
       	
       	$id = $db->lastInsertId();
       	
		
		
		
		
		
       	if(isset($_POST['msg']) && $_POST['msg'] ){
       	
		$msg = trim($_POST['msg']);
        $msg = trim(filter_input(INPUT_POST, 'msg', FILTER_SANITIZE_STRING));
		
		$dbconf = 'mysql:host=localhost;dbname=hackerus_landing_pages_leads;charset=utf8';
	        $db = new PDO($dbconf, 'hackerus_arielus', 'FP-xqS~%CLit');
	        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	        $sql = "INSERT INTO summaries VALUES('',?,'',?,NOW(),NOW())";
	        $query = $db->prepare($sql);
	        $data = [$id, $msg];
	       	$query->execute($data);

       	}
       	


		require_once('class.smtp.php');
        require_once('class.phpmailer.php');
        date_default_timezone_set('Asia/Tel_Aviv');
        $hour = date("G:i");
        $time = date("d/m/y");

            $mail_to_client = new PHPMailer();
            $mail_to_client->CharSet = 'UTF-8';
            $mail_to_client_body = "
<div style='padding:0;margin:0 auto;font-weight:200;width:100%!important; direction: ltr; padding:50px 0px;'>
<table align='center' border='0' cellspacing='0' cellpadding='0' style='table-layout:fixed;font-weight:200;font-family:Helvetica,Arial,sans-serif' width='100%'>
<tbody>
<tr>
<td align='center'>
<center style='width:100%'>
<table bgcolor='#FFFFFF' border='0' cellspacing='0' cellpadding='0' style='background-color:#eee;margin:0 auto;max-width:512px;font-weight:200;width:inherit;font-family:Helvetica,Arial,sans-serif' width='512'>
<img class='logo' src='https://www.hackerusa.com/leads/testing/img/general-logo.png' border='0' style='margin:0 auto;max-width:200px;margin:20px auto;text-align: center;'/>
<tbody>
<tr>
<td align='left'>
<table border='0' cellspacing='0' cellpadding='0' style='font-weight:200;font-family:Helvetica,Arial,sans-serif' width='100%'>
<tbody>
<tr>
<td width='100%'>
<table border='0' cellspacing='0' cellpadding='0' style='font-weight:200;font-family:Helvetica,Arial,sans-serif' width='100%'>
<tbody>
<tr>
<td align='center' bgcolor='#1D4187' style='padding:20px 48px;color:#ffffff' class='banner-color'>
<table border='0' cellspacing='0' cellpadding='0' style='font-weight:200;font-family:Helvetica,Arial,sans-serif' width='100%'>
<tbody>
<tr>
<td align='center' width='100%'>
<h1 style='padding:0;margin:0;color:#ffffff;font-weight:500;font-size:20px;line-height:24px'>Hi {$name}!</h1>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td align='center' style='padding:20px 10px'>
<table border='0' cellspacing='0' cellpadding='0' style='font-weight:200;font-family:Helvetica,Arial,sans-serif' width='100%'>
<tbody>
<tr>
<td align='center' width='100%' style='padding: 0 15px;text-align: center;color: rgb(76, 76, 76);font-size: 20px;line-height: 25px;'>
<p>
We were pleased to receive your inquiry regarding our cyber and IT courses.
<br>
We want to provide you with the best service possible. Please let us know what time is most convenient for you to talk, so we can get back to you for more details.
<br><br>
<a href='mailto:arielc@hackeru.co.il'>arielc@hackeru.co.il</a><br>
Best regards,
The HackerUSA Team
</p>

</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
</td>
</tr>

</tbody>
</table>
</center>
</td>
</tr>
</tbody>
</table>
</div>
";
            $mail_to_client_subject = !empty($_GET['subject']) ? $_GET['subject'] : 'HackerUsa- Landing page leads';
			$mail_to_client->AddReplyTo("no-reply@hackerusa.com", "HackerUsa");
			$mail_to_client->From = "info@hackerusa.com";
            $mail_to_client->AddAddress($email);
            $mail_to_client->Subject = $mail_to_client_subject;
            $mail_to_client->AltBody = "To view the message, please use an HTML compatible email viewer!";
            $mail_to_client->MsgHTML($mail_to_client_body);
            $mail_to_client->Send();
		?>
