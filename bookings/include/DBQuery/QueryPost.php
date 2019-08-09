<?php

require_once '../../include/DBOps/DBConnection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require 'fpdf181/fpdf.php';

class DBQuery
{
	private $conn;
	private $db;

	function __construct()
	{
		$this->conn = new DBConnection();
		$this->db = $this->conn->mConnect();
	}

	public function getCustomers(){
		
		$sql = "SELECT * FROM `customers`";
		$result = mysqli_query($this->db,$sql);
		$row=mysqli_fetch_all($result,MYSQLI_ASSOC);
		// print_r($result);
		return $row;
	}

	public function getAgents(){
		
		$sql = "SELECT * FROM `agents`";
		$result = mysqli_query($this->db,$sql);
		$row=mysqli_fetch_all($result,MYSQLI_ASSOC);
		// print_r($result);
		return $row;
	}


	public function getCustomersByPage($params){
		$page = $params['page'];
		$limit = 15;
		$offset = ($page - 1)  * $limit;
		$start = $offset + 1;

		$sql_count = "SELECT * FROM `customers`";
		$result_count = mysqli_query($this->db,$sql_count);
	  	$total=mysqli_num_rows($result_count);
		$row["total_page"] = ceil($total / $limit);
		

		$sql = "SELECT * FROM `customers` LIMIT 15 OFFSET {$offset}";
		$result = mysqli_query($this->db,$sql);
		$row['customers']=mysqli_fetch_all($result,MYSQLI_ASSOC);
		$row['page'] = $page;
		// print_r($result);
		return $row;
	}

	public function getAgentsByPage($params){
		$page = $params['page'];
		$limit = 15;
		$offset = ($page - 1)  * $limit;
		$start = $offset + 1;

		$sql_count = "SELECT * FROM `agents`";
		$result_count = mysqli_query($this->db,$sql_count);
	  	$total=mysqli_num_rows($result_count);
		$row["total_page"] = ceil($total / $limit);
		

		$sql = "SELECT * FROM `agents` LIMIT 15 OFFSET {$offset}";
		$result = mysqli_query($this->db,$sql);
		$row['agents']=mysqli_fetch_all($result,MYSQLI_ASSOC);
		$row['page'] = $page;
		// print_r($result);
		return $row;
	}



	public function logInCustomer($body){
		
		$password = md5($body["password"]);

		$sql = "SELECT * FROM `customers` where `email` = '{$body["email"]}' 
		and `password` = '{$password}'";
		
		$result = mysqli_query($this->db,$sql);
		$row=mysqli_fetch_all($result,MYSQLI_ASSOC);
		return $row;
	}

	public function addCustomer($body){
		$password = md5($body["password"]);
		$sql = "INSERT INTO `customers` (`customer_id`, `fullname`, `business_name`, `email`, `phone`, `created_at`, `address`, `username`, `password`, `address_2`, `city`, `state`, `zip`) VALUES (NULL, '{$body["fullname"]}', '{$body["business_name"]}', '{$body["email"]}', '{$body["phone"]}', CURRENT_TIMESTAMP, '{$body["address"]}', '{$body["username"]}', '{$password}', '{$body["address_2"]}', '{$body["city"]}', '{$body["state"]}', '{$body["zip"]}')";
		$result = mysqli_query($this->db,$sql);
		return $result;
	}

	public function updateCustomer($id , $body){

		
		$sql = "UPDATE `customers` SET `customer_fname` = '{$body["customer_fname"]}', `customer_lname` = '{$body["customer_lname"]}', `email` = '{$body["email"]}', `phone` = '{$body["phone"]}', `phone2` = '{$body["phone2"]}', `birthdate` = '{$body["birthdate"]}' WHERE `customers`.`customer_id` = {$id}";
		$result = mysqli_query($this->db,$sql);

		return $result;
	}

	public function updateAgent($id , $body){
		
		$sql = "UPDATE `agents` SET `agent_fname` = '{$body["agent_fname"]}', `agent_lname` = '{$body["agent_lname"]}', `agent_email` = '{$body["agent_email"]}', `agent_phone` = '{$body["agent_phone"]}', `agent_phone2` = '{$body["agent_phone2"]}', `store_assigned` = '{$body["store_assigned"]}',`agent_pin` = '{$body["agent_pin"]}',`agent_password` = '{$body["agent_password"]}' WHERE `agents`.`agent_id` = {$id}";
		$result = mysqli_query($this->db,$sql);

		return $result;
	}

	// public function updateCustomer($id , $body){

		
	// 	$sql = "UPDATE `customers` SET `customer_fname` = '{$body["customer_fname"]}', `customer_lname` = '{$body["customer_lname"]}', `email` = '{$body["email"]}', `phone` = '{$body["phone"]}', `phone2` = '{$body["phone2"]}',`address` = '{$body["address"]}', `username` = '{$body["username"]}', `password` = '{$body["password"]}', `address_2` = '{$body["address_2"]}', `city` = '{$body["city"]}', `state` = '{$body["state"]}', `zip` = '{$body["zip"]}' WHERE `customers`.`customer_id` = {$id}";
	// 	$result = mysqli_query($this->db,$sql);

	// 	return $result;
	// }

	public function updateBooking($id , $body){
		$sql = "UPDATE `dev_owner` INNER JOIN `bookings` ON `bookings`.`owner_id` = `dev_owner`.`owner_id` SET `owner_name` = '{$body["fullname"]}', `email` = '{$body["email"]}', `phone` = '{$body["phone"]}', `phone2` = '{$body["phone2"]}', `birthdate` = '{$body["birthdate"]}' WHERE `bookings`.`id` = {$id}";
		$result = mysqli_query($this->db,$sql);
		return $result;
	}



	public function deleteCustomer($id){
		$sql = "DELETE FROM `customers` WHERE `customers`.`customer_id` = {$id}";
		$result = mysqli_query($this->db,$sql);
		return $result;
	}

	public function deleteAgent($id){
		$sql = "DELETE FROM `customers` WHERE `customers`.`customer_id` = {$id}";
		$result = mysqli_query($this->db,$sql);
		return $result;
	}

	public function deleteBooking($id){
		$sql = "DELETE FROM `bookings` WHERE `bookings`.`id` = {$id}";
		$result = mysqli_query($this->db,$sql);
		return $result;
	}
	
	

	public function getBookings(){
		$sql = "SELECT `bookings`.`bookings_id`,`customers`.`customer_fname`,`agents`.`agent_lname`,`agents`.`agent_fname`,`agents`.`agent_lname`,`booking_timestamps`.`created_at`,
				`repair_statuses`.`rep_status` FROM 
				`bookings` 
							LEFT JOIN `agents` ON `bookings`.`agent_id` = `agents`.`agent_id`
							LEFT JOIN `booking_timestamps` ON `bookings`.`timestamp_no` = `booking_timestamps`.`timestamp_no` 
							LEFT JOIN `customers` ON `bookings`.`customer_id` = `customers`.`customer_id` 
							LEFT JOIN `repair_statuses` ON `bookings`.`repstatus_no` = `repair_statuses`.`repstatus_no` ";
		$result = mysqli_query($this->db,$sql);
		$row=mysqli_fetch_all($result,MYSQLI_ASSOC);
		return $row;
	}

	public function getOwner($id){
		$sql = "SELECT `dev_owner`.`owner_id`,`dev_owner`.`owner_name`, `dev_owner`.`email`, `dev_owner`.`birthdate`, `dev_owner`.`phone`, `dev_owner`.`phone2`  FROM `bookings` INNER JOIN `dev_owner` ON `bookings`.`owner_id` = `dev_owner`.`owner_id` WHERE `bookings`.`bookings_id` = {$id} ";
		$result = mysqli_query($this->db,$sql);
		$row=mysqli_fetch_assoc($result);
		return $row;
	}

	public function getRepair($id){
		$sql = "SELECT `device_type`.`type`,`phone_brands`.`phone_brand`, `phone_models`.`pmodel_name`, `phone_models`.`pmodel_number`, `carriers`.`carrier_name`, `color`.`color_name`,`selected_repairs`.`screenrep_selected`,`selected_repairs`.`headrep_selected`,`selected_repairs`.`earrep_selected`,`selected_repairs`.`powerrep_selected`,`selected_repairs`.`rearcamrep_selected`,`selected_repairs`.`frontcamrep_selected`,`selected_repairs`.`homerep_selected`,`selected_repairs`.`microphone_selected`,`selected_repairs`.`chargeport_selected`,`selected_repairs`.`volumerep_selected`,`selected_repairs`.`battrep_selected`,`selected_repairs`.`signalrep_selected`,`selected_repairs`.`backglassrep_selected`
			FROM `bookings` INNER JOIN `device_type` ON `bookings`.`devtype_id` = `device_type`.`devtype_id` 
							INNER JOIN `phone_brands` ON `bookings`.`phonebrand_id` = `phone_brands`.`phonebrand_id` 
							INNER JOIN `phone_models` ON `bookings`.`phonemodel_id` = `phone_models`.`phonemodel_id` 
							INNER JOIN `carriers` ON `bookings`.`carrier_no` = `carriers`.`carrier_no` 
							INNER JOIN `color` ON `bookings`.`color_no` = `color`.`color_no` 
							INNER JOIN `selected_repairs` ON `bookings`.`selectedrepair_no` = `selected_repairs`.`selectedrepair_no` 

			WHERE `bookings`.`bookings_id` = {$id} ";
		$result = mysqli_query($this->db,$sql);
		$row=mysqli_fetch_assoc($result);
		return $row;
	}


	public function getBookingsByPage($params){
		$page = $params['page'];
		$limit = 15;
		$offset = ($page - 1)  * $limit;
		$start = $offset + 1;

		$sql_count = "SELECT `bookings`.`bookings_id`,`customers`.`customer_fname`,`agents`.`agent_lname`,`agents`.`agent_fname`,`agents`.`agent_lname`,`booking_timestamps`.`created_at`,
				`repair_statuses`.`rep_status` FROM 
				`bookings` 
							LEFT JOIN `agents` ON `bookings`.`agent_id` = `agents`.`agent_id`
							LEFT JOIN `booking_timestamps` ON `bookings`.`timestamp_no` = `booking_timestamps`.`timestamp_no` 
							LEFT JOIN `customers` ON `bookings`.`customer_id` = `customers`.`customer_id` 
							LEFT JOIN `repair_statuses` ON `bookings`.`repstatus_no` = `repair_statuses`.`repstatus_no`";
		$result_count = mysqli_query($this->db,$sql_count);
	  	$total=mysqli_num_rows($result_count);
		$row["total_page"] = ceil($total / $limit);
		

		$sql = "SELECT `bookings`.`bookings_id`,`customers`.`customer_fname`,`customers`.`customer_lname`,`agents`.`agent_fname`,`agents`.`agent_lname`,`booking_timestamps`.`created_at`,
				`repair_statuses`.`rep_status` FROM 
				`bookings` 
							LEFT JOIN `agents` ON `bookings`.`agent_id` = `agents`.`agent_id`
							LEFT JOIN `booking_timestamps` ON `bookings`.`timestamp_no` = `booking_timestamps`.`timestamp_no` 
							LEFT JOIN `customers` ON `bookings`.`customer_id` = `customers`.`customer_id` 
							LEFT JOIN `repair_statuses` ON `bookings`.`repstatus_no` = `repair_statuses`.`repstatus_no`
							LIMIT 15 OFFSET {$offset}";

		$result = mysqli_query($this->db,$sql);
		$row['bookings']=mysqli_fetch_all($result,MYSQLI_ASSOC);
		$row['page'] = $page;
		// print_r($result);
		return $row;
	}

	public function getInvoices(){
		$sql = "SELECT `invoices`.`invoice_no`,`invoices`.`total_price`,`invoices`.`payment_Timestamp`,
							 `dev_owner`.`owner_name`, `payment_statuses`.`pstatus_name` 
					  FROM `bookings`  
							LEFT JOIN `invoices` ON `bookings`.`invoice_no` = `invoices`.`invoice_no`
							LEFT JOIN `dev_owner` ON `bookings`.`owner_id` = `dev_owner`.`owner_id` 
							LEFT JOIN `payment_statuses` ON `bookings`.`paystatus_no` = `payment_statuses`.`paystatus_no` ";
		$result = mysqli_query($this->db,$sql);
		$row=mysqli_fetch_all($result,MYSQLI_ASSOC);
		return $row;
	}


	public function getInvoicesByPage($params){
		$page = $params['page'];
		$limit = 15;
		$offset = ($page - 1)  * $limit;
		$start = $offset + 1;

		$sql_count = "SELECT `invoices`.`invoice_no`,`invoices`.`total_price`,`invoices`.`payment_Timestamp`, `customers`.`customer_fname`,`customers`.`customer_lname`, `payment_statuses`.`pstatus_name` FROM `bookings` LEFT JOIN `invoices` ON `bookings`.`invoice_no` = `invoices`.`invoice_no` LEFT JOIN `customers` ON `bookings`.`customer_id` = `customers`.`customer_id` LEFT JOIN `payment_statuses` ON `bookings`.`paystatus_no` = `payment_statuses`.`paystatus_no` ";
		$result_count = mysqli_query($this->db,$sql_count);
	  	$total=mysqli_num_rows($result_count);
		$row["total_page"] = ceil($total / $limit);
		

		$sql = "SELECT `invoices`.`invoice_no`,`invoices`.`total_price`,`invoices`.`payment_Timestamp`, `customers`.`customer_fname`,`customers`.`customer_lname`, `payment_statuses`.`pstatus_name` FROM `bookings` LEFT JOIN `invoices` ON `bookings`.`invoice_no` = `invoices`.`invoice_no` LEFT JOIN `customers` ON `bookings`.`customer_id` = `customers`.`customer_id` LEFT JOIN `payment_statuses` ON `bookings`.`paystatus_no` = `payment_statuses`.`paystatus_no` LIMIT 15 OFFSET {$offset}";
							
		$result = mysqli_query($this->db,$sql);
		$row['invoices']=mysqli_fetch_all($result,MYSQLI_ASSOC);
		$row['page'] = $page;
		// print_r($result);
		return $row;
	}

	public function getTickets(){
		$sql = "SELECT `tickets`.`ticket_no`,`dev_owner`.`owner_name`,`dev_owner`.`email`,
							 `dev_owner`.`phone`,`dev_owner`.`phone2` 
					  FROM `bookings`  
							LEFT JOIN `tickets` ON `bookings`.`ticket_no` = `tickets`.`ticket_no`
							LEFT JOIN `dev_owner` ON `bookings`.`owner_id` = `dev_owner`.`owner_id` ";
		$result = mysqli_query($this->db,$sql);
		$row=mysqli_fetch_all($result,MYSQLI_ASSOC);
		return $row;
	}


	public function getTicketsByPage($params){
		$page = $params['page'];
		$limit = 15;
		$offset = ($page - 1)  * $limit;
		$start = $offset + 1;

		$sql_count = "SELECT `tickets`.`ticket_no`,`customers`.`customer_fname`,`customers`.`email`,`customers`.`customer_lname`, `customers`.`phone`,`customers`.`phone2` FROM `bookings` LEFT JOIN `tickets` ON `bookings`.`ticket_no` = `tickets`.`ticket_no` LEFT JOIN `customers` ON `bookings`.`customer_id` = `customers`.`customer_id` ";
		$result_count = mysqli_query($this->db,$sql_count);
	  	$total=mysqli_num_rows($result_count);
		$row["total_page"] = ceil($total / $limit);
		

		$sql = "SELECT `tickets`.`ticket_no`,`customers`.`customer_fname`,`customers`.`email`,`customers`.`customer_lname`, `customers`.`phone`,`customers`.`phone2` FROM `bookings` LEFT JOIN `tickets` ON `bookings`.`ticket_no` = `tickets`.`ticket_no` LEFT JOIN `customers` ON `bookings`.`customer_id` = `customers`.`customer_id` LIMIT 15 OFFSET {$offset}";
							
		$result = mysqli_query($this->db,$sql);
		$row['tickets']=mysqli_fetch_all($result,MYSQLI_ASSOC);
		$row['page'] = $page;
		// print_r($result);
		return $row;
	}

	public function getInventoryByPage($params){
		$page = $params['page'];
		$limit = 15;
		$offset = ($page - 1)  * $limit;
		$start = $offset + 1;

		$sql_count = "SELECT `inventory`.`item_no`,`inventory`.`quantity`,`inventory`.`last_restocked`,`repair_parts`.`part_name`,
							 `phone_models`.`pmodel_name`,`phone_brands`.`phone_brand` 
					  FROM `inventory`  
							LEFT JOIN `repair_parts` ON `inventory`.`part_no` = `repair_parts`.`part_no`
							LEFT JOIN `phone_models` ON `inventory`.`phonemodel_id` = `phone_models`.`phonemodel_id`
							LEFT JOIN `phone_brands` ON `inventory`.`phonebrand_id` = `phone_brands`.`phonebrand_id` ";
		$result_count = mysqli_query($this->db,$sql_count);
	  	$total=mysqli_num_rows($result_count);
		$row["total_page"] = ceil($total / $limit);
		

		$sql = "SELECT `inventory`.`item_no`,`inventory`.`quantity`,`inventory`.`last_restocked`,`repair_parts`.`part_name`,
							 `phone_models`.`pmodel_name`,`phone_brands`.`phone_brand` 
					  FROM `inventory`  
							LEFT JOIN `repair_parts` ON `inventory`.`part_no` = `repair_parts`.`part_no`
							LEFT JOIN `phone_models` ON `inventory`.`phonemodel_id` = `phone_models`.`phonemodel_id`
							LEFT JOIN `phone_brands` ON `inventory`.`phonebrand_id` = `phone_brands`.`phonebrand_id` LIMIT 15 OFFSET {$offset}";
							
		$result = mysqli_query($this->db,$sql);
		$row['inventory']=mysqli_fetch_all($result,MYSQLI_ASSOC);
		$row['page'] = $page;
		// print_r($result);
		return $row;
	}

	public function  updatePaymentStatus($id){

		// $res  = mysqli_query($this->db,"SELECT `bookings`.`paystatus_no`,`invoices`. FROM bookings WHERE id='$id'");
		// $row = mysqli_fetch_assoc($res);
		// $status = $row['payment_status'];

   		$sql = "UPDATE `bookings` INNER JOIN `invoices` ON `bookings`.`invoice_no` = `invoices`.`invoice_no` SET `bookings`.`paystatus_no` = '2' , `invoices`.`payment_Timestamp` = CURRENT_TIMESTAMP WHERE `invoices`.`invoice_no` = {$id}";
		$result = mysqli_query($this->db,$sql);
		return $result;
	}

	public function  editOwner($id,$body){

		// $res  = mysqli_query($this->db,"SELECT `bookings`.`paystatus_no`,`invoices`. FROM bookings WHERE id='$id'");
		// $row = mysqli_fetch_assoc($res);
		// $status = $row['payment_status'];

   		$sql = "UPDATE `dev_owner` SET `owner_name` = '{$body['owner_name']}', `birthdate` = '{$body['birthdate']}',`phone` = '{$body['phone']}',`email` = '{$body['email']}', `phone` = '{$body['phone2']}' WHERE `dev_owner`.`owner_id` = {$id}";
		$result = mysqli_query($this->db,$sql);
		return $result;
	}


	public function  updateBookingStatus($id){

		$res  = mysqli_query($this->db,"SELECT `repstatus_no` FROM `bookings` WHERE `bookings`.`bookings_id` = '$id'");
		$row = mysqli_fetch_assoc($res);
		$status = $row['repstatus_no'];

		if ($status == 1) {
    		$sql = "UPDATE `bookings` INNER JOIN `booking_timestamps` ON `bookings`.`timestamp_no` = `booking_timestamps`.`timestamp_no` SET `bookings`.`repstatus_no` = '2', `booking_timestamps`.`ongoing_Timestamp` = CURRENT_TIMESTAMP WHERE `bookings`.`bookings_id` = {$id}";
			$result = mysqli_query($this->db,$sql);
		} elseif ($status == 2) {
			$sql = "UPDATE `bookings` INNER JOIN `booking_timestamps` ON `bookings`.`timestamp_no` = `booking_timestamps`.`timestamp_no` SET `bookings`.`repstatus_no` = '3', `booking_timestamps`.`resolved_Timestamp` = CURRENT_TIMESTAMP WHERE `bookings`.`bookings_id` = {$id}";
			$result = mysqli_query($this->db,$sql);
		}

		$res  = mysqli_query($this->db,"SELECT `dev_owner`.`email` FROM `bookings` INNER JOIN `dev_owner` ON `bookings`.`owner_id` = `dev_owner`.`owner_id` WHERE `bookings`.`bookings_id`='$id'");
		$row = mysqli_fetch_assoc($res);
		$userEmail = $row['email'];
//$userEmail = 'paulcampbell.iphixx@gmail.com';

		$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    //Server settings
    $mail->SMTPDebug = 0;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'admin.iphixx.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'iphixxmail@admin.iphixx.com';                 // SMTP username
    $mail->Password = '{Ae,E$R};!M)';                           // SMTP password
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port =  465;                                    // TCP port to connect to




    //Recipients
    $mail->setFrom('iphixxmail@admin.iphixx.com',"Mailer");
    $mail->addAddress($userEmail);     // Add a recipient
    // $mail->addAddress('ellen@example.com');               // Name is optional
    //$mail->addReplyTo('iphixxmail@admin.iphixx.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    // //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    $mail->DKIM_domain = "admin.iphixx.com";
    $mail->DKIM_private = "admin.iphixx.com.private"; //path to file on the disk.
    $mail->DKIM_selector = "phpmailer";// change this to whatever you set during step 2
    $mail->DKIM_passphrase = "";
    $mail->DKIM_identifier = $mail->From;
    $mail->DKIM_extraHeaders = ['List-Unsubscribe', 'List-Help'];


    //Content
    $mail->isHTML(true);                                  // Set email format to HTML

    if ($status == 1) {
    		$mail->Subject = 'In Progress';
    			$mail->Body    = 'Your repair is now in progress';
    			$mail->AltBody = 'Your repair is now in progress';
		} elseif ($status == 2) {
    		$mail->Subject = 'Completed';
    			$mail->Body    = 'Your repair has been completed';
    			$mail->AltBody = 'Your repair has been completed';
		}
   
    $mail->send();

		return true;	
	}


	public function  cancelBooking($id){

		$res  = mysqli_query($this->db,"SELECT `repstatus_no` FROM `bookings` WHERE `bookings`.`bookings_id` = '$id'");
		$row = mysqli_fetch_assoc($res);
		$status = $row['repstatus_no'];

		
    		$sql = "UPDATE `bookings` INNER JOIN `booking_timestamps` ON `bookings`.`timestamp_no` = `booking_timestamps`.`timestamp_no` SET `bookings`.`repstatus_no` = '4', `booking_timestamps`.`cancelled_Timestamp` = CURRENT_TIMESTAMP WHERE `bookings`.`bookings_id` = {$id}";
			$result1 = mysqli_query($this->db,$sql);
		

		$res  = mysqli_query($this->db,"SELECT `dev_owner`.`email` FROM `bookings` INNER JOIN `dev_owner` ON `bookings`.`owner_id` = `dev_owner`.`owner_id` WHERE `bookings`.`bookings_id`='$id'");
		$row = mysqli_fetch_assoc($res);
		$userEmail = $row['email'];
//$userEmail = 'paulcampbell.iphixx@gmail.com';

		$mail = new PHPMailer(true);                              // Passing `true` enables exceptions

    //Server settings
    $mail->SMTPDebug = 0;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'admin.iphixx.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'iphixxmail@admin.iphixx.com';                 // SMTP username
    $mail->Password = '{Ae,E$R};!M)';                           // SMTP password
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port =  465;                                    // TCP port to connect to




    //Recipients
    $mail->setFrom('iphixxmail@admin.iphixx.com',"Mailer");
    $mail->addAddress($userEmail);     // Add a recipient
    // $mail->addAddress('ellen@example.com');               // Name is optional
    //$mail->addReplyTo('iphixxmail@admin.iphixx.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    // //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    $mail->DKIM_domain = "admin.iphixx.com";
    $mail->DKIM_private = "admin.iphixx.com.private"; //path to file on the disk.
    $mail->DKIM_selector = "phpmailer";// change this to whatever you set during step 2
    $mail->DKIM_passphrase = "";
    $mail->DKIM_identifier = $mail->From;
    $mail->DKIM_extraHeaders = ['List-Unsubscribe', 'List-Help'];


    //Content
    $mail->isHTML(true);                                  // Set email format to HTML

   
    		$mail->Subject = 'Repair Cancelled';
    			$mail->Body    = 'Your repair has been cancelled';
    			$mail->AltBody = 'Your repair has been cancelled';
		
   
    $mail->send();
  
		return $result;	
	}


	public function addBooking($body){
		
		$sql = "INSERT INTO `bookings_old` (`id`, `fullname`, `birthdate`, `email`, `phone`, `phone2`, `created_at`, `status`, `device_pin`, `brand`, `model`, `color`, `network`, `repair`, `device`, `customer_id`, `upgrade_1`, `upgrade_2`, `phone_offer`, `total_price`) VALUES (NULL, '{$body["fullname"]}', '{$body["birthdate"]}', '{$body["email"]}', '{$body["phone"]}', '{$body["phone2"]}', CURRENT_TIMESTAMP, '{$body["status"]}', '{$body["device_pin"]}', '{$body["brand"]}', '{$body["model"]}', '{$body["color"]}', '{$body["network"]}', '{$body["repair"]}', '{$body["device"]}', '{$body["customer_id"]}', '{$body["upgrade_1"]}', '{$body["upgrade_2"]}', '{$body["phone_offer"]}', '{$body["total"]}')";
		$result = mysqli_query($this->db,$sql);

		$sql = "INSERT INTO `dev_owner` (`owner_id`, `owner_name`, `birthdate`, `email`, `phone`, `phone2`) VALUES (NULL, '{$body["fullname"]}', '{$body["birthdate"]}', '{$body["email"]}', '{$body["phone"]}', '{$body["phone2"]}')";
		$result = mysqli_query($this->db,$sql);


		$sql = "INSERT INTO `invoices`(`total_price`) VALUES ({$body["total"]})";
		$result = mysqli_query($this->db,$sql);

		$sql = "INSERT INTO `tickets`(`ticket_no`) VALUES ('10002')";
		$result = mysqli_query($this->db,$sql);

		$sql = "INSERT INTO `bookings` (`owner_id`, `customer_id`, `created_at`, `repstatus_no`, `invoice_no`,`paystatus_no`,`ticket_no`) VALUES ('3', '42', CURRENT_TIMESTAMP, '1','10568', '1','10003')";
		$result = mysqli_query($this->db,$sql);

		$pdf = new FPDF();

		$pdf->AddPage();
		$pdf->SetFont('Arial','B',16);

		$w = array(120,50);

		$pdf->Cell(40,10,'Invoice');
		$pdf->Ln();
		$pdf->Cell(40,10,'Iphixx');
		$pdf->Ln();
		$pdf->Cell(40,10,'Name of Store: Walmart');
		$pdf->Ln();
		$pdf->Cell(40,10,'Name of Agent: Ryan Margolin');
		$pdf->Ln();
		$pdf->Cell(40,10,'Repair Number Confirmation: 0123456789');
		$pdf->Ln();
		$pdf->Cell(40,10,'Customer Details');
		$pdf->Ln();
		$pdf->Cell(40,10,'Full Name: '. $body["fullname"]);
		$pdf->Ln();
		$pdf->Cell(40,10,'Email: '. $body["email"]);
		$pdf->Ln();
		$pdf->Cell(40,10,'Device Details');
		$pdf->Ln();
		$pdf->Cell(40,10,'Device Model: '. $body["brand"]. " ". $body["model"]);
		$pdf->Ln();
		$pdf->Cell(40,10,'Color: '. $body["color"]);
		$pdf->Ln();
		$pdf->Cell(40,10,'Carrier: '. $body["network"]);
		$pdf->Ln();

		define('EURO',chr(128));

		$selectedrepairs=array();
		$repairstring=str_replace(array( '[', ']','"'), '', $body["repair"]);
		for($i=0;$i<$body["repairlength"];$i++)
			$selectedrepairs = explode(',',$repairstring);

		$prices=array();
		$pricestring=str_replace(array( '[', ']','"'), '', $body["prices"]);
		for($i=0;$i<$body["repairlength"];$i++)
			$prices = explode(',',$pricestring);

		$header = array('Selected Repairs', 'Cost');
		echo "this". $selectedrepairs[0];
		echo "this". $selectedrepairs[1];
		echo "this". $prices[0];
		echo "this". $prices[1];
		// echo "this2". $selectedrepairs[1];
    	// Header
    	for($i=0;$i<count($header);$i++)
        	$pdf->Cell($w[$i],7,$header[$i],1,0,'C');
    	$pdf->Ln();

    	for($i=0;$i<count($selectedrepairs);$i++){
        	$pdf->Cell($w[0],7,$selectedrepairs[$i],1,0,'C');
        	$pdf->Cell($w[1],7,$prices[$i]. " ".EURO,1,0,'C');
    		$pdf->Ln();
    }
    $pdf->Ln();
    	// Data
    	// foreach($selectedrepairs as $row)
    	// {
     //    	$pdf->Cell($w[0],6,$row[0],'LR');
     //    	$pdf->Cell($w[1],6,$row[1],'LR');
     //    	$pdf->Ln();
    	// }
    	// Closing line

    	$pdf->Cell($w[0],7,"Total Cost: ",'','','C');
        $pdf->Cell($w[1],7,$body["total"]. ".00 ".EURO,'','','C');
		
		$pdf->Ln();
		$pdf->Cell(40,10,"Thank you for your business with iPhixx!");
		
		$pdfdoc = $pdf->Output('', 'S');

		$mail = new PHPMailer(true);                              // Passing `true` enables exceptions

    //Server settings
    $mail->SMTPDebug = 2;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'admin.iphixx.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'iphixxmail@admin.iphixx.com';                 // SMTP username
    $mail->Password = '{Ae,E$R};!M)';                           // SMTP password
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port =  465;                                    // TCP port to connect to




    //Recipients
    $mail->setFrom('iphixxmail@admin.iphixx.com',"Mailer");
    $mail->addAddress($body["email"]);     // Add a recipient
    $mail->addAddress('ian08bulatao@gmail.com');               // Name is optional
    $mail->addReplyTo('ian08bulatao@gmail.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    // //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    $mail->addStringAttachment($pdfdoc, 'my-doc.pdf');

    $mail->DKIM_domain = "admin.iphixx.com";
    $mail->DKIM_private = "admin.iphixx.com.private"; //path to file on the disk.
    $mail->DKIM_selector = "phpmailer";// change this to whatever you set during step 2
    $mail->DKIM_passphrase = "";
    $mail->DKIM_identifier = $mail->From;
    $mail->DKIM_extraHeaders = ['List-Unsubscribe', 'List-Help'];


    //Content
    $mail->isHTML(true);                                  // Set email format to HTML

  
    		$mail->Subject = 'Invoice';
    			$mail->Body    = 'Here is your receipt';
    			$mail->AltBody = 'Here is your receipt';

   
    $mail->send();

		return $result;
	}
}
	