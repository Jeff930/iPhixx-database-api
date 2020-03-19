<?php

require_once '../../include/DBOps/DBConnection.php';
require __DIR__ . '/twilio-php-master/src/Twilio/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Twilio\Rest\Client;

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

	public function getTax() {
		$sql = "SELECT * FROM `tax`";
		$result = mysqli_query($this->db,$sql);
		$row=mysqli_fetch_all($result,MYSQLI_ASSOC);
		// print_r($result);
		return $row;
	}

	public function getOneTax($id) {
		$sql = "SELECT * FROM `tax` WHERE `tax`.`tax_id` = {$id} ";
		$result = mysqli_query($this->db,$sql);
		$row=mysqli_fetch_all($result,MYSQLI_ASSOC);
		// print_r($result);
		return $row;
	}

	public function addTax($body){
		$sql = "INSERT INTO `tax` (`tax_id`, `tax_name`, `tax_value`) VALUES (NULL, '{$body["tax_name"]}', '{$body["tax_value"]}')";
		$result = mysqli_query($this->db,$sql);
		return $result;
	}

	public function updateTax($id , $body){
		$sql = "UPDATE `tax` SET `tax_name` = '{$body["tax_name"]}', `tax_value` = '{$body["tax_value"]}' WHERE `tax`.`tax_id` = {$id}";
		$result = mysqli_query($this->db,$sql);

		return $result;
	}

	public function deleteTax($id){
		$sql = "DELETE FROM `tax` WHERE `tax`.`tax_id` = {$id}";
		$result = mysqli_query($this->db,$sql);
		return $result;
	}
	//End of Tax

	public function getTabletModels() {
		$sql = "SELECT `tablet_models`.`tmodel_name` FROM `tablet_models`";
		$result = mysqli_query($this->db,$sql);
		$row=mysqli_fetch_all($result,MYSQLI_ASSOC);
		// print_r($result);
		return $row;
	}
	// getReminders() {
	// 	$sql = "SELECT * FROM `tax`";
	// 	$result = mysqli_query($this->db,$sql);
	// 	$row=mysqli_fetch_all($result,MYSQLI_ASSOC);
	// 	return $row;
	// }

	// public function addTax($body){
	// 	$sql = "INSERT INTO `tax` (`tax_id`, `tax_name`, `tax_value`) VALUES (NULL, '{$body["tax_name"]}', '{$body["tax_value"]}')";
	// 	$result = mysqli_query($this->db,$sql);
	// 	return $result;
	// }

	// public function updateTax($id , $body){
	// 	$sql = "UPDATE `tax` SET `tax_name` = '{$body["tax_name"]}', `tax_value` = '{$body["tax_value"]}' WHERE `tax`.`tax_id` = {$id}";
	// 	$result = mysqli_query($this->db,$sql);

	// 	return $result;
	// }

	// public function deleteTax($id){
	// 	$sql = "DELETE FROM `tax` WHERE `tax`.`tax_id` = {$id}";
	// 	$result = mysqli_query($this->db,$sql);
	// 	return $result;
	// }
	//End of Tax

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

	public function getCustomersCount(){
		
		$sql = "SELECT COUNT(*) FROM `customers`";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_row($result);
		$items_number = $row[0];
		// print_r($result);
		return $items_number;
	}

	
	public function getInvoicesCount(){
		
		$sql = "SELECT COUNT(*) FROM `invoices`";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_row($result);
		$items_number = $row[0];
		// print_r($result);
		return $items_number;
	}
	
	public function getTicketsCount(){
		
		$sql = "SELECT COUNT(*) FROM `invoices`";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_row($result);
		$items_number = $row[0];
		// print_r($result);
		return $items_number;
	}

	public function getInventoryCount(){
		
		$sql = "SELECT COUNT(*) FROM `inventory`";
		$result = mysqli_query($this->db,$sql);
		$row = mysqli_fetch_row($result);
		$items_number = $row[0];
		// print_r($result);
		return $items_number;
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

		$sql_count = "SELECT `agent_id`,`agent_fname`,`agent_lname`,`agent_username`,`agent_email`  FROM `agents`";
		$result_count = mysqli_query($this->db,$sql_count);
	  	$total=mysqli_num_rows($result_count);
		$row["total_page"] = ceil($total / $limit);
		

		$sql = "SELECT `agent_id`,`agent_fname`,`agent_lname`,`agent_username`,`agent_email`  FROM `agents` LIMIT 15 OFFSET {$offset}";
		$result = mysqli_query($this->db,$sql);
		$row['agents']=mysqli_fetch_all($result,MYSQLI_ASSOC);
		$row['page'] = $page;
		// print_r($result);
		return $row;
	}



	public function logInAgent($body){
		
		$password = md5($body["password"]);

		$sql = "SELECT `agent_id`,`agent_username`,`location_id` FROM `agents` where `agent_email` = '{$body["email"]}' 
		and `agent_password` = '{$password}'";
		
		$result = mysqli_query($this->db,$sql);
		$row['agent']=mysqli_fetch_all($result,MYSQLI_ASSOC);
		return $row;
	}

	// public function addCustomer($body){
	// 	$password = md5($body["password"]);
	// 	$sql = "INSERT INTO `customers` (`customer_id`, `customer_fname`, `customer_lname` , `email`, `phone`, `birthdate`,  `address`,  `location`) VALUES (NULL, '{$body["customer_fname"]}', '{$body["customer_lname"]}' ,  '{$body["email"]}', '{$body["phone"]}', '{$body["birthdate"]}', '{$body["address"]}', '{$body["location"]}')";
	// 	$result = mysqli_query($this->db,$sql);
	// 	return $result;
	// }

	public function addCustomer($body){
		$sql = "INSERT INTO `customers` (`customer_id`, `customer_fname`, `customer_lname` , `email`, `phone`,`altPhone`, `birthdate`,`address`,`location_id`,`smsOption`) VALUES (NULL, '{$body["firstName"]}', '{$body["lastName"]}' ,  '{$body["email"]}', '{$body["mobile"]}','{$body["phone"]}', '{$body["birthdate"]}','{$body["address"]}','{$body["location_id"]}','{$body["smsOption"]}')";
		$result = mysqli_query($this->db, $sql);
		if ($result) {
    		$row["id"] = mysqli_insert_id($this->db);
			} else {
   				 echo "Error: " . $sql . "<br>" . mysqli_error($this->db);
			}
		return $row;
	}


	public function updateCustomer($id , $body){
		$sql = "UPDATE `customers` SET `customer_fname` = '{$body["customer_fname"]}', `customer_lname` = '{$body["customer_lname"]}', `email` = '{$body["email"]}', `phone` = '{$body["phone"]}', `birthdate` = '{$body["birthdate"]}', `location_id` = '{$body["location_id"]}', `smsOption` = '{$body["smsOption"]}' WHERE `customers`.`customer_id` = {$id}";
		$result = mysqli_query($this->db,$sql);
		return $result;
	}

	public function updateAgent($id , $body){
		$sql = "UPDATE `agents` SET `agent_fname` = '{$body["agent_fname"]}', `agent_lname` = '{$body["agent_lname"]}', `agent_email` = '{$body["agent_email"]}', `agent_phone` = '{$body["agent_phone"]}', `agent_location` = '{$body["agent_location"]}', `store_assigned` = '{$body["store_assigned"]}',`agent_pin` = '{$body["agent_pin"]}',`agent_password` = '{$body["agent_password"]}' WHERE `agents`.`agent_id` = {$id}";
		$result = mysqli_query($this->db,$sql);

		return $result;
	}

	// public function updateCustomer($id , $body){

		
	// 	$sql = "UPDATE `customers` SET `customer_fname` = '{$body["customer_fname"]}', `customer_lname` = '{$body["customer_lname"]}', `email` = '{$body["email"]}', `phone` = '{$body["phone"]}', `location` = '{$body["location"]}',`address` = '{$body["address"]}', `username` = '{$body["username"]}', `password` = '{$body["password"]}', `address_2` = '{$body["address_2"]}', `city` = '{$body["city"]}', `state` = '{$body["state"]}', `zip` = '{$body["zip"]}' WHERE `customers`.`customer_id` = {$id}";
	// 	$result = mysqli_query($this->db,$sql);

	// 	return $result;
	// }

	public function updateBooking($id , $body){
		$sql = "UPDATE `dev_owner` INNER JOIN `bookings` ON `bookings`.`owner_id` = `dev_owner`.`owner_id` SET `owner_name` = '{$body["fullname"]}', `email` = '{$body["email"]}', `phone` = '{$body["phone"]}', `location` = '{$body["location"]}', `birthdate` = '{$body["birthdate"]}',`location_id` = '{$body["location_id"]}' WHERE `bookings`.`id` = {$id}";
		$result = mysqli_query($this->db,$sql);
		return $result;
	}



	public function deleteCustomer($id){
		$sql = "DELETE FROM `bookings` WHERE `bookings`.`customer_id` = {$id}";
		$result = mysqli_query($this->db,$sql);

		$sql1 = "DELETE FROM `customers` WHERE `customer_id` = {$id}";
		$result1 = mysqli_query($this->db,$sql1);
		return $result1;
	}

	public function deleteAgent($id){
		$sql = "DELETE FROM `customers` WHERE `customers`.`customer_id` = {$id}";
		$result = mysqli_query($this->db,$sql);
		return $result;
	}

	public function deleteBooking($id){
		$sql = "DELETE FROM `bookings` WHERE `bookings`.`bookings_id` = {$id}";
		$result = mysqli_query($this->db,$sql);
		return $result;
	}
	
	

	public function getBookings() {
		$sql = "SELECT `bookings`.`bookings_id`,`customers`.`customer_fname`, `customers`.`customer_lname`,`customers`.`location`, `agents`.`agent_lname`,`agents`.`agent_fname`,`agents`.`agent_lname`,`booking_timestamps`.`created_at`, `phone_brands`.`phone_brand` , `device_models`.`model_name`,`lead_statuses`.`lead_status` FROM 
				`bookings` 
							LEFT JOIN `agents` ON `bookings`.`agent_id` = `agents`.`agent_id`
							LEFT JOIN `booking_timestamps` ON `bookings`.`timestamp_no` = `booking_timestamps`.`timestamp_no` 
							LEFT JOIN `customers` ON `bookings`.`customer_id` = `customers`.`customer_id` 
							LEFT JOIN  `phone_brands` ON `bookings`.`phonebrand_id` = `phone_brands`.`phonebrand_id` 
							LEFT JOIN  `device_models` ON `bookings`.`devicemodel_id` = `device_models`.`devicemodel_id`
							LEFT JOIN  `lead_statuses` ON `bookings`.`leadstatus_no` = `lead_statuses`.`leadstatus_no`";
		$result = mysqli_query($this->db,$sql);
		$row=mysqli_fetch_all($result,MYSQLI_ASSOC);
		return $row;
	}

	public function getDevice($id) {
		$sql = "SELECT * FROM `device_models` WHERE `device_models`.`devicemodel_id` = '{$id}'";
		$result = mysqli_query($this->db,$sql);
		$row=mysqli_fetch_assoc($result);
		return $row;
	}

	public function getOwner($id) {
		$sql = "SELECT `customers`.`customer_id`,`customers`.`customer_fname`, `customers`.`customer_lname`,  `customers`.`location`,  `customers`.`email`, `customers`.`location_id`, `customers`.`phone`,  `customers`.`birthdate`, `customers`.`address`,`customers`.`smsOption` FROM `bookings` INNER JOIN `customers` 
		ON `bookings`.`customer_id` = `customers`.`customer_id` 
		WHERE `bookings`.`bookings_id` = {$id}";
		$result = mysqli_query($this->db,$sql);
		$row=mysqli_fetch_assoc($result);
		return $row;
	}

	public function getInvoice($id) {
		$sql = "SELECT `bookings`.`bookings_id`,`bookings`.`total_price`,`customers`.`customer_fname`, `customers`.`customer_lname`,`customers`.`location_id`, `device_brands`.`device_brand` , `device_models`.`model_name`,`customers`.`email`,`customers`.`customer_id`,`customers`.`phone` FROM `bookings` 
							LEFT JOIN `booking_timestamps` ON `bookings`.`timestamp_no` = `booking_timestamps`.`timestamp_no` 
							LEFT JOIN `customers` ON `bookings`.`customer_id` = `customers`.`customer_id` 
							LEFT JOIN  `device_brands` ON `bookings`.`devicebrand_id` = `device_brands`.`devicebrand_id` 
							LEFT JOIN  `device_models` ON `bookings`.`devicemodel_id` = `device_models`.`devicemodel_id`
                            LEFT JOIN  `invoices` ON `bookings`.`invoice_no` = `invoices`.`invoice_no`
                            WHERE `invoices`.`invoice_no` = {$id}";
		$result = mysqli_query($this->db,$sql);
		$row=mysqli_fetch_assoc($result);
		return $row;
	}

	public function getAgent($id) {
		$sql = "SELECT `agent_id`,`agent_fname`,`agent_lname`,`agent_username`,`agent_email`,`agent_address`, `store_assigned`,`agent_pin`,`agent_phone`,`agent_created_at`, `location_id`, `agent_status` FROM `agents`
                            WHERE `invoices`.`invoice_no` = {$id}";
		$result = mysqli_query($this->db,$sql);
		$row=mysqli_fetch_assoc($result);
		return $row;
	}

	public function getLogistic($id) {
		$sql = "SELECT `bookings`.`bookings_id`,`bookings`.`leadstatus_no`,`bookings`.`ticketstatus_no`,`customers`.`customer_fname`, `customers`.`customer_lname`,`customers`.`location_id`, `device_brands`.`device_brand` , `device_models`.`model_name`,`customers`.`customer_id`,`booking_timestamps`.`created_at`,`booking_timestamps`.`transfer_timestamp`,`booking_timestamps`.`cancelled_timestamp` AS lost_timestamp,`tickets`.`ticket_no`, `tickets`.`created_at` AS ticket_create, `tickets`.`outbound_timestamp`, `tickets`.`ongoing_timestamp`, `tickets`.`inbound_timestamp`, `tickets`.`resolved_timestamp`, `tickets`.`cancelled_timestamp` FROM `bookings` 
							LEFT JOIN `booking_timestamps` ON `bookings`.`timestamp_no` = `booking_timestamps`.`timestamp_no` 
							LEFT JOIN `customers` ON `bookings`.`customer_id` = `customers`.`customer_id` 
							LEFT JOIN  `device_brands` ON `bookings`.`devicebrand_id` = `device_brands`.`devicebrand_id` 
							LEFT JOIN  `device_models` ON `bookings`.`devicemodel_id` = `device_models`.`devicemodel_id`
                            LEFT JOIN  `tickets` ON `bookings`.`ticket_no` = `tickets`.`ticket_no`
                            WHERE `bookings`.`bookings_id` = {$id}";
		$result = mysqli_query($this->db,$sql);
		$row=mysqli_fetch_assoc($result);
		return $row;
	}

	public function getTicket($id) {
		$sql = "SELECT `bookings`.`bookings_id`,`bookings`.`total_price`,`customers`.`customer_id`,`customers`.`customer_fname`, `customers`.`customer_lname`,`customers`.`location_id`, `device_brands`.`device_brand` , `device_models`.`model_name`,`customers`.`email`,`customers`.`customer_id`,`customers`.`phone`,`tickets`.`ticket_no`,`ticket_statuses`.`ticket_status`,`tickets`.`created_at`,`tickets`.`outbound_timestamp`,`tickets`.`ongoing_timestamp`,`tickets`.`inbound_timestamp`,`tickets`.`resolved_timestamp` FROM `bookings` 
							LEFT JOIN `tickets` ON `bookings`.`ticket_no` = `tickets`.`ticket_no` 
							LEFT JOIN `ticket_statuses` ON `bookings`.`ticketstatus_no` = `ticket_statuses`.`ticketstatus_no` 
							LEFT JOIN `customers` ON `bookings`.`customer_id` = `customers`.`customer_id` 
							LEFT JOIN  `device_brands` ON `bookings`.`devicebrand_id` = `device_brands`.`devicebrand_id` 
							LEFT JOIN  `device_models` ON `bookings`.`devicemodel_id` = `device_models`.`devicemodel_id`
                            WHERE `tickets`.`ticket_no` = {$id}";
		$result = mysqli_query($this->db,$sql);
		$row=mysqli_fetch_assoc($result);
		return $row;
	}

	public function getStock($id) {
		$sql = "SELECT `quantity`,`item_no` FROM `inventory` WHERE `item_no` = {$id}";
		$result = mysqli_query($this->db,$sql);
		$row=mysqli_fetch_assoc($result);
		return $row;
	}

	public function getRepair($id){
		$sql = "SELECT `device_type`.`type`,`bookings`.`devtype_id`,`device_brands`.`device_brand`, `device_models`.`model_name`, `device_models`.`model_number`, `carriers`.`carrier_name`, `color`.`color_name`,`selected_repairs`.`screenrep_selected`,`selected_repairs`.`headrep_selected`,`selected_repairs`.`earrep_selected`,`selected_repairs`.`powerrep_selected`,`selected_repairs`.`rearcamrep_selected`,`selected_repairs`.`frontcamrep_selected`,`selected_repairs`.`homerep_selected`,`selected_repairs`.`microphone_selected`,`selected_repairs`.`chargeport_selected`,`selected_repairs`.`volumerep_selected`,`selected_repairs`.`battrep_selected`,`selected_repairs`.`signalrep_selected`,`selected_repairs`.`backglassrep_selected`,`selected_repairs`.`protector_selected`,`selected_repairs`.`tempPhone_selected`,`selected_repairs`.`keyboardrep_selected`,`selected_repairs`.`fanrep_selected`,`selected_repairs`.`laptopcamrep_selected`,`selected_repairs`.`laptopscreenrep_selected`,`selected_repairs`.`laptopspeakerrep_selected`,`selected_repairs`.`datarecovery`,`selected_repairs`.`virusremoval`,`selected_repairs`.`virusremoval_withsoftware`,`selected_repairs`.`HDDHalfTeraWithDataTransfer`,`selected_repairs`.`HDDTeraWithDataTransfer`,`selected_repairs`.`HDDHalfTera`,`selected_repairs`.`HDDTera`,`selected_repairs`.`SSDHalfTeraWithDataTransfer`,`selected_repairs`.`SSDTeraWithDataTransfer`,`selected_repairs`.`SSDHalfTera`,`selected_repairs`.`SSDTera`,`selected_repairs`.`hdmirep_selected`,`selected_repairs`.`harddrive_selected`

			FROM `bookings` INNER JOIN `device_type` ON `bookings`.`devtype_id` = `device_type`.`devtype_id` 
							INNER JOIN `device_brands` ON `bookings`.`devicebrand_id` = `device_brands`.`devicebrand_id` 
							INNER JOIN `device_models` ON `bookings`.`devicemodel_id` = `device_models`.`devicemodel_id` 
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

		$sql_count = "SELECT `bookings`.`bookings_id`,`customers`.`customer_fname`, `customers`.`customer_lname`,`customers`.`location`, `agents`.`agent_lname`,`agents`.`agent_fname`,`agents`.`agent_lname`,`booking_timestamps`.`created_at`, `device_brands`.`device_brand` , `device_models`.`model_name`,`lead_statuses`.`lead_status` FROM 
				`bookings` 
							LEFT JOIN `agents` ON `bookings`.`agent_id` = `agents`.`agent_id`
							LEFT JOIN `booking_timestamps` ON `bookings`.`timestamp_no` = `booking_timestamps`.`timestamp_no` 
							LEFT JOIN `customers` ON `bookings`.`customer_id` = `customers`.`customer_id` 
							LEFT JOIN  `device_brands` ON `bookings`.`devicebrand_id` = `device_brands`.`devicebrand_id` 
							LEFT JOIN  `device_models` ON `bookings`.`devicemodel_id` = `device_models`.`devicemodel_id`
							LEFT JOIN  `lead_statuses` ON `bookings`.`leadstatus_no` = `lead_statuses`.`leadstatus_no`
							LIMIT 15 OFFSET {$offset}";
		$result_count = mysqli_query($this->db,$sql_count);
	  	$total=mysqli_num_rows($result_count);
		$row["total_page"] = ceil($total / $limit);
		

		$sql = "SELECT `bookings`.`bookings_id`,`customers`.`customer_fname`, `customers`.`customer_lname`,`customers`.`location`, `agents`.`agent_lname`,`agents`.`agent_fname`,`agents`.`agent_lname`,`booking_timestamps`.`created_at`, `device_brands`.`device_brand` , `device_models`.`model_name`,`lead_statuses`.`lead_status` FROM 
				`bookings` 
							LEFT JOIN `agents` ON `bookings`.`agent_id` = `agents`.`agent_id`
							LEFT JOIN `booking_timestamps` ON `bookings`.`timestamp_no` = `booking_timestamps`.`timestamp_no` 
							LEFT JOIN `customers` ON `bookings`.`customer_id` = `customers`.`customer_id` 
							LEFT JOIN  `device_brands` ON `bookings`.`devicebrand_id` = `device_brands`.`devicebrand_id` 
							LEFT JOIN  `device_models` ON `bookings`.`devicemodel_id` = `device_models`.`devicemodel_id`
							LEFT JOIN  `lead_statuses` ON `bookings`.`leadstatus_no` = `lead_statuses`.`leadstatus_no`
							LIMIT 15 OFFSET {$offset}";

		$result = mysqli_query($this->db,$sql);
		$row['bookings']=mysqli_fetch_all($result,MYSQLI_ASSOC);
		$row['page'] = $page;
		// print_r($result);
		return $row;
	}

	public function getInvoices(){
		$sql = "SELECT `invoices`.`invoice_no`,`invoices`.`settled_Timestamp`,`invoices`.`unsettled_Timestamp`, `customers`.`customer_fname`,`customers`.`customer_lname`, `invoice_statuses`.`invoice_status`,`bookings`.`total_price` FROM `bookings` LEFT JOIN `invoices` ON `bookings`.`invoice_no` = `invoices`.`invoice_no` LEFT JOIN `customers` ON `bookings`.`customer_id` = `customers`.`customer_id` LEFT JOIN `invoice_statuses` ON `bookings`.`invoicestatus_no` = `invoice_statuses`.`invoicestatus_no` ";
		$result = mysqli_query($this->db,$sql);
		$row=mysqli_fetch_all($result,MYSQLI_ASSOC);
		return $row;
	}

	public function getAgents(){
		$sql = "SELECT `agent_id`,`agent_fname`,`agent_lname`,`agent_username`,`agent_email`  FROM `agents`";
		$result = mysqli_query($this->db,$sql);
		$row=mysqli_fetch_all($result,MYSQLI_ASSOC);
		return $row;
	}

	public function getInvoicesByPage($params){
		$page = $params['page'];
		$limit = 15;
		$offset = ($page - 1)  * $limit;
		$start = $offset + 1;

		$sql_count = "SELECT `invoices`.`invoice_no`,`invoices`.`settled_Timestamp`,`invoices`.`unsettled_Timestamp`, `customers`.`customer_fname`,`customers`.`customer_lname`, `invoice_statuses`.`invoice_status`,`bookings`.`total_price` FROM `bookings` LEFT JOIN `invoices` ON `bookings`.`invoice_no` = `invoices`.`invoice_no` LEFT JOIN `customers` ON `bookings`.`customer_id` = `customers`.`customer_id` LEFT JOIN `invoice_statuses` ON `bookings`.`invoicestatus_no` = `invoice_statuses`.`invoicestatus_no` LIMIT 15 OFFSET {$offset}";
		$result_count = mysqli_query($this->db,$sql_count);
	  	$total=mysqli_num_rows($result_count);
		$row["total_page"] = ceil($total / $limit);
		

		$sql = "SELECT `invoices`.`invoice_no`,`invoices`.`settled_Timestamp`,`invoices`.`unsettled_Timestamp`, `customers`.`customer_fname`,`customers`.`customer_lname`, `invoice_statuses`.`invoice_status`,`bookings`.`total_price` FROM `bookings` LEFT JOIN `invoices` ON `bookings`.`invoice_no` = `invoices`.`invoice_no` LEFT JOIN `customers` ON `bookings`.`customer_id` = `customers`.`customer_id` LEFT JOIN `invoice_statuses` ON `bookings`.`invoicestatus_no` = `invoice_statuses`.`invoicestatus_no` LIMIT 15 OFFSET {$offset}";
							
		$result = mysqli_query($this->db,$sql);
		$row['invoices']=mysqli_fetch_all($result,MYSQLI_ASSOC);
		$row['page'] = $page;
		// print_r($result);
		return $row;
	}

	public function getLogistics(){
		$sql = "SELECT `bookings`.`bookings_id`,`tickets`.`ticket_no`,`bookings`.`leadstatus_no`, `bookings`.`ticketstatus_no`,`locations`.`location_name` FROM `bookings` LEFT JOIN `tickets` ON `bookings`.`ticket_no` = `tickets`.`ticket_no` LEFT JOIN `locations` ON `bookings`.`location_id` = `locations`.`location_id`";
		$result = mysqli_query($this->db,$sql);
		$row=mysqli_fetch_all($result,MYSQLI_ASSOC);
		return $row;
	}


	public function getLogisticsByPage($params){
		$page = $params['page'];
		$limit = 15;
		$offset = ($page - 1)  * $limit;
		$start = $offset + 1;

		$sql_count = "SELECT `bookings`.`bookings_id`,`tickets`.`ticket_no`,`bookings`.`leadstatus_no`, `bookings`.`ticketstatus_no`,`locations`.`location_name` FROM `bookings` LEFT JOIN `tickets` ON `bookings`.`ticket_no` = `tickets`.`ticket_no` LEFT JOIN `locations` ON `bookings`.`location_id` = `locations`.`location_id` LIMIT 15 OFFSET {$offset}";
		$result_count = mysqli_query($this->db,$sql_count);
	  	$total=mysqli_num_rows($result_count);
		$row["total_page"] = ceil($total / $limit);
		

		$sql = "SELECT `bookings`.`bookings_id`,`tickets`.`ticket_no`,`bookings`.`leadstatus_no`, `bookings`.`ticketstatus_no`,`locations`.`location_name` FROM `bookings` LEFT JOIN `tickets` ON `bookings`.`ticket_no` = `tickets`.`ticket_no` LEFT JOIN `locations` ON `bookings`.`location_id` = `locations`.`location_id` LIMIT 15 OFFSET {$offset}";
							
		$result = mysqli_query($this->db,$sql);
		$row['logistics']=mysqli_fetch_all($result,MYSQLI_ASSOC);
		$row['page'] = $page;
		// print_r($result);
		return $row;
	}

	public function getTickets(){
		$sql = "SELECT `tickets`.`ticket_no`,`ticket_statuses`.`ticket_status`,`tickets`.`created_at`,`customers`.`customer_fname`,`customers`.`customer_lname`
					  FROM `bookings`  
					  		LEFT JOIN `customers` ON `bookings`.`customer_id` = `customers`.`customer_id`
							LEFT JOIN `tickets` ON `bookings`.`ticket_no` = `tickets`.`ticket_no`
							LEFT JOIN `ticket_statuses` ON `bookings`.`ticketstatus_no` = `ticket_statuses`.`ticketstatus_no` ";
		$result = mysqli_query($this->db,$sql);
		$row=mysqli_fetch_all($result,MYSQLI_ASSOC);
		return $row;
	}

	public function checkLeadStatus($id){
		$sql = "SELECT `bookings`.`leadstatus_no` FROM `bookings` WHERE `bookings`.`bookings_id` = {$id} ";
		$result = mysqli_query($this->db,$sql);
		$row=mysqli_fetch_assoc($result);
		return $row;
	}

	public function checkRepairStatus($id){
		$sql = "SELECT `bookings`.`repairstatus_no` FROM `bookings` WHERE `bookings`.`bookings_id` = {$id} ";
		$result = mysqli_query($this->db,$sql);
		$row=mysqli_fetch_assoc($result);
		return $row;
	}

	public function checkInvoiceStatus($id){
		$sql = "SELECT `bookings`.`invoicestatus_no` FROM `bookings` WHERE `bookings`.`bookings_id` = {$id} ";
		$result = mysqli_query($this->db,$sql);
		$row=mysqli_fetch_assoc($result);
		return $row;
	}

	public function checkTicketStatus($id){
		$sql = "SELECT `bookings`.`ticketstatus_no` FROM `bookings` WHERE `bookings`.`bookings_id` = {$id} ";
		$result = mysqli_query($this->db,$sql);
		$row=mysqli_fetch_assoc($result);
		return $row;
	}

	// public function getModels($body){
	// 	$sql = "SELECT * FROM `device_models` WHERE `devtype_id` = '{$body['email']}' AND `phonebrand_id` = '{$body['password']}' ";
	// 	$result = mysqli_query($this->db,$sql);
	// 	$row=mysqli_fetch_all($result,MYSQLI_ASSOC);
	// 	return $row;
	// }


	public function getTicketsByPage($params){
		$page = $params['page'];
		$limit = 15;
		$offset = ($page - 1)  * $limit;
		$start = $offset + 1;

		$sql_count = "SELECT `bookings`.`bookings_id`,`tickets`.`ticket_no`,`ticket_statuses`.`ticket_status`,`tickets`.`created_at`,`customers`.`customer_fname`,`customers`.`customer_lname`, `device_brands`.`device_brand`,`device_models`.`model_name`
					  FROM `bookings`  
					  		INNER JOIN `customers` ON `bookings`.`customer_id` = `customers`.`customer_id`
					  		INNER JOIN `device_brands` ON `bookings`.`devicebrand_id` = `device_brands`.`devicebrand_id`
					  		INNER JOIN `device_models` ON `bookings`.`devicemodel_id` = `device_models`.`devicemodel_id`
							INNER JOIN `tickets` ON `bookings`.`ticket_no` = `tickets`.`ticket_no`
							INNER JOIN `ticket_statuses` ON `bookings`.`ticketstatus_no` = `ticket_statuses`.`ticketstatus_no` LIMIT 15 OFFSET {$offset}";
		$result_count = mysqli_query($this->db,$sql_count);
	  	$total=mysqli_num_rows($result_count);
		$row["total_page"] = ceil($total / $limit);
		

		$sql = "SELECT `bookings`.`bookings_id`,`tickets`.`ticket_no`,`ticket_statuses`.`ticket_status`,`tickets`.`created_at`,`customers`.`customer_fname`,`customers`.`customer_lname`, `device_brands`.`device_brand`,`device_models`.`model_name`
					  FROM `bookings`  
					  		INNER JOIN `customers` ON `bookings`.`customer_id` = `customers`.`customer_id`
					  		INNER JOIN `device_brands` ON `bookings`.`devicebrand_id` = `device_brands`.`devicebrand_id`
					  		INNER JOIN `device_models` ON `bookings`.`devicemodel_id` = `device_models`.`devicemodel_id`
							INNER JOIN `tickets` ON `bookings`.`ticket_no` = `tickets`.`ticket_no`
							INNER JOIN `ticket_statuses` ON `bookings`.`ticketstatus_no` = `ticket_statuses`.`ticketstatus_no` LIMIT 15 OFFSET {$offset} ";
							
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

		$sql_count = "SELECT `inventory`.`item_no`,`inventory`.`quantity`,`inventory`.`last_restocked`, `inventory`.`cost`,`repair_parts`.`part_name`,
							 `device_models`.`model_name`,`device_brands`.`device_brand` 
					  FROM `inventory`  
							LEFT JOIN `repair_parts` ON `inventory`.`part_no` = `repair_parts`.`part_no`
							LEFT JOIN `device_models` ON `inventory`.`devicemodel_id` = `device_models`.`devicemodel_id`
							LEFT JOIN `device_brands` ON `inventory`.`devicebrand_id` = `device_brands`.`devicebrand_id` LIMIT 15 OFFSET {$offset}";
		$result_count = mysqli_query($this->db,$sql_count);
	  	$total=mysqli_num_rows($result_count);
		$row["total_page"] = ceil($total / $limit);
		

		$sql = "SELECT `inventory`.`item_no`,`inventory`.`quantity`,`inventory`.`last_restocked`, `inventory`.`cost`,`repair_parts`.`part_name`,
							 `device_models`.`model_name`,`device_brands`.`device_brand` 
					  FROM `inventory`  
							LEFT JOIN `repair_parts` ON `inventory`.`part_no` = `repair_parts`.`part_no`
							LEFT JOIN `device_models` ON `inventory`.`devicemodel_id` = `device_models`.`devicemodel_id`
							LEFT JOIN `device_brands` ON `inventory`.`devicebrand_id` = `device_brands`.`devicebrand_id` LIMIT 15 OFFSET {$offset}";
							
		$result = mysqli_query($this->db,$sql);
		$row['inventory']=mysqli_fetch_all($result,MYSQLI_ASSOC);
		$row['page'] = $page;
		// print_r($result);
		return $row;
	}

	public function  updatePaymentStatus($id){

		$sql = "UPDATE `bookings` INNER JOIN `invoices` ON `bookings`.`invoice_no` = `invoices`.`invoice_no` SET `bookings`.`invoicestatus_no` = 2 , `invoices`.`settled_Timestamp` = CURRENT_TIMESTAMP WHERE `invoices`.`invoice_no` = {$id}";
		$result = mysqli_query($this->db,$sql);
		return $result;
	}

	public function getLaptopPrices(){
		$sql = "SELECT * FROM `laptop_price` WHERE 1";		
		$result = mysqli_query($this->db,$sql);
		$row['result']=mysqli_fetch_all($result,MYSQLI_ASSOC);
		// print_r($result);
		return $row;
	}

	public function getModelsByPage($params){
		$model = $params['model'];
		$limit = 15;
		$offset = ($page - 1)  * $limit;
		$start = $offset + 1;

		$sql_count = "SELECT `devicemodel_id`, `model_name`, `model_number`, `screenrep_price`, `headrep_price`, `earrep_price`, `powerrep_price`, `rearcamrep_price`, `frontcamrep_price`, `homerep_price`, `microphone_price`, `chargeport_price`, `volumerep_price`, `battrep_price`, `signalrep_price`, `backglass_price`, `devtype_id`, `devicebrand_id` FROM `device_models` WHERE `devtype_id` = '{$params['device_id']}' AND `devicebrand_id`  = '{$params['brand_id']}' LIMIT 15 OFFSET {$offset}";
		$result_count = mysqli_query($this->db,$sql_count);
	  	$total=mysqli_num_rows($result_count);
		$row["total_page"] = ceil($total / $limit);
		

		$sql = "SELECT `devicemodel_id`, `model_name`, `model_number`, `screenrep_price`, `headrep_price`, `earrep_price`, `powerrep_price`, `rearcamrep_price`, `frontcamrep_price`, `homerep_price`, `microphone_price`, `chargeport_price`, `volumerep_price`, `battrep_price`, `signalrep_price`, `backglass_price`, `devtype_id`, `devicebrand_id` FROM `device_models` WHERE `devtype_id` = '{$params['device_id']}' AND `devicebrand_id`  = '{$params['brand_id']}'LIMIT 15 OFFSET {$offset}";
							
		$result = mysqli_query($this->db,$sql);
		$row['result']=mysqli_fetch_all($result,MYSQLI_ASSOC);
		// print_r($result);
		return $row;
	}

	public function getModels($params){
		$sql = "SELECT `devicemodel_id`, `model_name`, `model_number`, `screenrep_price`, `headrep_price`, `earrep_price`, `powerrep_price`, `rearcamrep_price`, `frontcamrep_price`, `homerep_price`, `microphone_price`, `chargeport_price`, `volumerep_price`, `battrep_price`, `signalrep_price`, `backglass_price`, `devtype_id`, `devicebrand_id` FROM `device_models` WHERE `devtype_id` = '{$params['devtype_id']}' AND `devicebrand_id`  = '{$params['phonebrand_id']}'";
							
		$result = mysqli_query($this->db,$sql);
		$row['result']=mysqli_fetch_all($result,MYSQLI_ASSOC);
		// print_r($result);
		return $row;
	}

	public function getConsoles($params){
		$sql = "SELECT * FROM `device_models` WHERE `devtype_id` = '{$params['devtype_id']}'";
							
		$result = mysqli_query($this->db,$sql);
		$row['result']=mysqli_fetch_all($result,MYSQLI_ASSOC);
		// print_r($result);
		return $row;
	}

	public function getAllModelsByPage($params){
		$page = $params['page'];
		$limit = 15;
		$offset = ($page - 1)  * $limit;
		$start = $offset + 1;

		$sql_count = "SELECT `devicemodel_id`, `model_name`, `model_number`, `screenrep_price`, `headrep_price`, `earrep_price`, `powerrep_price`, `rearcamrep_price`, `frontcamrep_price`, `homerep_price`, `microphone_price`, `chargeport_price`, `volumerep_price`, `battrep_price`, `signalrep_price`, `backglass_price`, `devtype_id`, `devicebrand_id` FROM `device_models` ";
		$result_count = mysqli_query($this->db,$sql_count);
	  	$total=mysqli_num_rows($result_count);
		$row["total_page"] = ceil($total / $limit);
		

		$sql = "SELECT `devicemodel_id`, `model_name`, `model_number`, `screenrep_price`, `headrep_price`, `earrep_price`, `powerrep_price`, `rearcamrep_price`, `frontcamrep_price`, `homerep_price`, `microphone_price`, `chargeport_price`, `volumerep_price`, `battrep_price`, `signalrep_price`, `backglass_price`, `devtype_id`, `devicebrand_id` FROM `device_models` LIMIT 15 OFFSET {$offset}";
							
		$result = mysqli_query($this->db,$sql);
		$row['devices']=mysqli_fetch_all($result,MYSQLI_ASSOC);
		$row['page'] = $page;
		// print_r($result);
		return $row;
	}

	public function getAllModels(){
		$sql = "SELECT `devicemodel_id`, `model_name`, `model_number`, `screenrep_price`, `headrep_price`, `earrep_price`, `powerrep_price`, `rearcamrep_price`, `frontcamrep_price`, `homerep_price`, `microphone_price`, `chargeport_price`, `volumerep_price`, `battrep_price`, `signalrep_price`, `backglass_price`, `devtype_id`, `devicebrand_id` FROM `device_models`";
							
		$result = mysqli_query($this->db,$sql);
		$row['devices']=mysqli_fetch_all($result,MYSQLI_ASSOC);
		$row['page'] = $page;
		// print_r($result);
		return $row;
	}

	public function updatePrice($id , $body){

		$sql = "UPDATE `device_models` SET "; 
		if ($body['screenrep_price'] != 'null'){
			$sql = $sql."`screenrep_price` = '{$body['screenrep_price']}',";
		}
		else{
			$sql = $sql."`screenrep_price` = NULL,";
		}

		if ($body['headrep_price'] != 'null'){
			$sql = $sql."`headrep_price` = '{$body['headrep_price']}',";
		}
		else{
			$sql = $sql."`headrep_price` = NULL,";
		}

		if ($body['earrep_price'] != 'null'){
			$sql = $sql."`earrep_price` = '{$body['earrep_price']}',";
		}
		else{
			$sql = $sql."`earrep_price` = NULL,";
		}


		if ($body['powerrep_price'] != 'null'){
			$sql = $sql."`powerrep_price` = '{$body['powerrep_price']}',";
		}
		else{
			$sql = $sql."`powerrep_price` = NULL,";
		}

		if ($body['rearcamrep_price'] != 'null'){
			$sql = $sql."`rearcamrep_price` = '{$body['rearcamrep_price']}',";
		}
		else{
			$sql = $sql."`rearcamrep_price` = NULL,";
		}

		if ($body['frontcamrep_price'] != 'null'){
			$sql = $sql."`frontcamrep_price` = '{$body['frontcamrep_price']}',";
		}
		else{
			$sql = $sql."`frontcamrep_price` = NULL,";
		}

		if ($body['homerep_price'] != 'null'){
			$sql = $sql."`homerep_price` = '{$body['homerep_price']}',";
		}
		else{
			$sql = $sql."`homerep_price` = NULL,";
		}

		if ($body['microphone_price'] != 'null'){
			$sql = $sql."`microphone_price` = '{$body['microphone_price']}',";
		}
		else{
			$sql = $sql."`microphone_price` = NULL,";
		}

		if ($body['chargeport_price'] != 'null'){
			$sql = $sql."`chargeport_price` = '{$body['chargeport_price']}',";
		}
		else{
			$sql = $sql."`chargeport_price` = NULL,";
		}

		if ($body['volumerep_price'] != 'null'){
			$sql = $sql."`volumerep_price` = '{$body['volumerep_price']}',";
		}
		else{
			$sql = $sql."`volumerep_price` = NULL,";
		}

		if ($body['battrep_price'] != 'null'){
			$sql = $sql."`battrep_price` = '{$body['battrep_price']}',";
		}
		else{
			$sql = $sql."`battrep_price` = NULL,";
		}

		if ($body['signalrep_price'] != 'null'){
			$sql = $sql."`signalrep_price` = '{$body['signalrep_price']}',";
		}
		else{
			$sql = $sql."`signalrep_price` = NULL,";
		}

		if ($body['backglass_price'] != 'null'){
			$sql = $sql."`backglass_price` = '{$body['backglass_price']}',";
		}
		else{
			$sql = $sql."`backglass_price` = NULL,";
		}

		if ($body['trackpad_price'] != 'null'){
			$sql = $sql."`trackpad_price` = '{$body['trackpad_price']}',";
		}
		else{
			$sql = $sql."`trackpad_price` = NULL,";
		}

		if ($body['hdmirep_price'] != 'null'){
			$sql = $sql."`hdmirep_price` = '{$body['hdmirep_price']}',";
		}
		else{
			$sql = $sql."`hdmirep_price` = NULL,";
		}

		if ($body['harddrive_rep'] != 'null'){
			$sql = $sql."`harddrive_rep` = '{$body['harddrive_rep']}',";
		}
		else{
			$sql = $sql."`harddrive_rep` = NULL ";
		}

		$sql = $sql." WHERE `device_models`.`devicemodel_id` = {$id}";
		
		$result = mysqli_query($this->db,$sql);
		return $result;
	}

	public function updateLaptopPrice($body){
		$sql = "UPDATE `laptop_price` SET `laptopscreenrep_price` = '{$body['laptopscreenrep_price']}', `laptopchargerep_price` = '{$body['laptopchargerep_price']}' , `keyboardrep_price` = '{$body['keyboardrep_price']}', `fanrep_price` = '{$body['fanrep_price']}', `laptopcamrep_price` = '{$body['laptopcamrep_price']}', `laptopspeakerrep_price` = '{$body['laptopspeakerrep_price']}', `laptopbatteryrep_price` = '{$body['laptopbatteryrep_price']}', `datarecovery` = '{$body['datarecovery']}', `virusremoval_withsoftware` = '{$body['virusremoval_withsoftware']}', `HDDHalfTeraWithDataTransfer` = '{$body['HDDHalfTeraWithDataTransfer']}', `HDDTeraWithDataTransfer` = '{$body['HDDTeraWithDataTransfer']}', `HDDHalfTera` = '{$body['HDDHalfTera']}', `HDDTera` = '{$body['HDDTera']}', `SSDHalfTeraWithDataTransfer` = '{$body['SSDHalfTeraWithDataTransfer']}', `SSDTeraWithDataTransfer` = '{$body['SSDTeraWithDataTransfer']}', `SSDHalfTera` = '{$body['SSDHalfTera']}', `SSDTera` = '{$body['SSDTera']}' WHERE `laptop_price`.`LaptopPriceNo` = 1";

		$result = mysqli_query($this->db,$sql);
		return $result;
	}

	public function updateStock($id,$body){
   		$sql = "UPDATE `inventory` SET `quantity` = '{$body['quantity']}' WHERE `item_no` = {$id}";
		$result = mysqli_query($this->db,$sql);
		return $result;
	}

	public function editOwner($id,$body){

		// $res  = mysqli_query($this->db,"SELECT `bookings`.`paystatus_no`,`invoices`. FROM bookings WHERE id='$id'");
		// $row = mysqli_fetch_assoc($res);
		// $status = $row['payment_status'];

   		$sql = "UPDATE `dev_owner` SET `owner_name` = '{$body['owner_name']}', `birthdate` = '{$body['birthdate']}',`phone` = '{$body['phone']}',`email` = '{$body['email']}', `phone` = '{$body['location']}' WHERE `dev_owner`.`owner_id` = {$id}";
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

	public function  updateTicketStatus($id){
		$res  = mysqli_query($this->db,"SELECT `ticketstatus_no` FROM `bookings` WHERE `bookings`.`bookings_id` = '$id'");
		$row = mysqli_fetch_assoc($res);
		$status = $row['ticketstatus_no'];

		if ($status == 1) {
    		$sql = "UPDATE `bookings` INNER JOIN `tickets` ON `bookings`.`ticket_no` = `tickets`.`ticket_no` SET `bookings`.`ticketstatus_no` = '2', `tickets`.`outbound_Timestamp` = CURRENT_TIMESTAMP WHERE `bookings`.`bookings_id` = {$id}";
			$result = mysqli_query($this->db,$sql);
		} elseif ($status == 2) {
			$sql = "UPDATE `bookings` INNER JOIN `tickets` ON `bookings`.`ticket_no` = `tickets`.`ticket_no` SET `bookings`.`ticketstatus_no` = '3', `tickets`.`ongoing_Timestamp` = CURRENT_TIMESTAMP WHERE `bookings`.`bookings_id` = {$id}";
			$result = mysqli_query($this->db,$sql);
		} elseif ($status == 3) {
			$sql = "UPDATE `bookings` INNER JOIN `tickets` ON `bookings`.`ticket_no` = `tickets`.`ticket_no` SET `bookings`.`ticketstatus_no` = '4', `tickets`.`inbound_Timestamp` = CURRENT_TIMESTAMP WHERE `bookings`.`bookings_id` = {$id}";
			$result = mysqli_query($this->db,$sql);
		} elseif ($status == 4) {
			$sql = "UPDATE `bookings` INNER JOIN `tickets` ON `bookings`.`ticket_no` = `tickets`.`ticket_no` SET `bookings`.`ticketstatus_no` = '5', `tickets`.`resolved_Timestamp` = CURRENT_TIMESTAMP WHERE `bookings`.`bookings_id` = {$id}";
			$result = mysqli_query($this->db,$sql);
		}

		$res  = mysqli_query($this->db,"SELECT `customers`.`email` FROM `bookings` INNER JOIN `customers` ON `bookings`.`customer_id` = `customers`.`customer_id` WHERE `bookings`.`bookings_id`='$id'");
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
    $mail->setFrom('iphixxmail@admin.iphixx.com',"iPhixx Phone Repair Services");
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
    		$mail->Subject = 'Ticket Creation';
    			$mail->Body    = 'The ticket for your repair has been created.';
    			$mail->AltBody = 'The ticket for your repair has been created.';
		} elseif ($status == 2) {
    		$mail->Subject = 'Repair Outbound';
    			$mail->Body    = 'Your device for repair is now being delivered to the repair hub.';
    			$mail->AltBody = 'Your device for repair is now being delivered to the repair hub.';
		} elseif ($status == 3) {
    		$mail->Subject = 'Repair Ongoing';
    			$mail->Body    = 'Your device for repair has arrived to the repair center and is now for repair.';
    			$mail->AltBody = 'Your device for repair has arrived to the repair center and is now for repair.';
		} elseif ($status == 4) {
    		$mail->Subject = 'Repair Outbound';
    			$mail->Body    = 'The repair for your device is finished and is now being delivered back to the repair center.';
    			$mail->AltBody = 'The repair for your device is finished and is now being delivered back to the repair center.';
		} elseif ($status == 5) {
    		$mail->Subject = 'Repair Resolved';
    			$mail->Body    = 'Your repaired device is now available for collection. Thank you for your business with iPhixx.';
    			$mail->AltBody = 'Your repaired device is now available for collection. Thank you for your business with iPhixx.';
		}
   
    $mail->send();

	return true;	
	
	}

	public function transferLead($id){
		
    	$sql = "UPDATE `bookings` INNER JOIN `booking_timestamps` ON `bookings`.`timestamp_no` = `booking_timestamps`.`timestamp_no` SET `bookings`.`leadstatus_no` = '2', `booking_timestamps`.`transfer_Timestamp` = CURRENT_TIMESTAMP WHERE `bookings`.`bookings_id` = {$id}";
		$result = mysqli_query($this->db,$sql);

		$sql2 = "INSERT INTO `tickets`(`ticket_no`, `created_at`, `outbound_timestamp`,`ongoing_timestamp`,`inbound_timestamp`,`resolved_timestamp`,`cancelled_timestamp`) VALUES (NULL,CURRENT_TIMESTAMP,NULL,NULL,NULL,NULL,NULL)";

		$tickets = mysqli_query($this->db,$sql2);

		$ticket_id = mysqli_insert_id($this->db);

		$sql3 = "INSERT INTO `invoices`(`invoice_no`, `tax_id`, `unsettled_timestamp`,`settled_timestamp`) VALUES (NULL,NULL,CURRENT_TIMESTAMP,NULL)";

		$invoices = mysqli_query($this->db,$sql3);

		$invoice_id = mysqli_insert_id($this->db);

		$sql = "UPDATE `bookings` SET `bookings`.`ticket_no` = '{$ticket_id}',`bookings`.`ticketstatus_no` = '1', `bookings`.`invoice_no` = '{$invoice_id}',`bookings`.`invoicestatus_no` = '1' WHERE `bookings`.`bookings_id` = {$id}";

		$result = mysqli_query($this->db,$sql);

		return $result;
	}

	public function leadLost($id){

		$res  = mysqli_query($this->db,"SELECT `leadstatus_no` FROM `bookings` WHERE `bookings`.`bookings_id` = '$id'");
		$row = mysqli_fetch_assoc($res);
		$status = $row['leadstatus_no'];

		
    		$sql = "UPDATE `bookings` INNER JOIN `booking_timestamps` ON `bookings`.`timestamp_no` = `booking_timestamps`.`timestamp_no` SET `bookings`.`leadstatus_no` = '3', `booking_timestamps`.`cancelled_Timestamp` = CURRENT_TIMESTAMP WHERE `bookings`.`bookings_id` = {$id}";
			$result1 = mysqli_query($this->db,$sql);
		

		$res  = mysqli_query($this->db,"SELECT `customers`.`email`,`customers`.`smsOption` FROM `bookings` INNER JOIN `customers` ON `bookings`.`customer_id` = `customers`.`customer_id` WHERE `bookings`.`bookings_id` = {$id}");
		$row = mysqli_fetch_assoc($res);
		$userEmail = $row['email'];
		$smsOption = $row['smsOption'];
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
    $mail->setFrom('iphixxmail@admin.iphixx.com',"iPhixx Phone Repair Services");
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

	// public function updateCustomer($id , $body){
	
	// 	return $client;
	// }


	public function addBooking($body){
		$res  = mysqli_query($this->db,"SELECT * FROM `customers` WHERE `customers`.`customer_id` = '{$body['customer_id']}'");
		$row = mysqli_fetch_assoc($res);
		$userFirstName = $row['customer_fname'];
		$userLastName = $row['customer_lname'];
		$userEmail = $row['email'];
		$phoneNumber = $row['phone'];
		$smsOption = $row['smsOption'];


		$res  = mysqli_query($this->db,"SELECT * FROM `agents` WHERE `agents`.`agent_id` = '{$body['agent_id']}'");
		$row = mysqli_fetch_assoc($res);
		$agentFirstName = $row['agent_fname'];
		$agentLastName = $row['agent_lname'];
		$agentStoreName = $row['store_assigned'];

		$res  = mysqli_query($this->db,"SELECT * FROM `device_brands` WHERE `devicebrand_id` = '{$body['brand']}'");
		$row = mysqli_fetch_assoc($res);
		$brand = $row['device_brand'];

		$res  = mysqli_query($this->db,"SELECT * FROM `device_models` WHERE `devicemodel_id` = '{$body['model']}'");
		$row = mysqli_fetch_assoc($res);
		$model = $row['model_name'];

		$res  = mysqli_query($this->db,"SELECT * FROM `color` WHERE `color_no` = '{$body['color']}'");
		$row = mysqli_fetch_assoc($res);
		$color = $row['color_name'];

		$res  = mysqli_query($this->db,"SELECT * FROM `carriers` WHERE `carrier_no` = '{$body['network']}'");
		$row = mysqli_fetch_assoc($res);
		$carrier = $row['carrier_name'];

		$sql1 = "INSERT INTO `selected_repairs`
			(`selectedrepair_no`, `screenrep_selected`, `headrep_selected`,
			 `earrep_selected`, `powerrep_selected`, `rearcamrep_selected`, 
			 `frontcamrep_selected`, `homerep_selected`, `microphone_selected`,
			 `chargeport_selected`, `volumerep_selected`, `battrep_selected`, 
			 `signalrep_selected`, `backglassrep_selected`, `protector_selected`, 
			 `tempPhone_selected`, `keyboardrep_selected`, `fanrep_selected`, 
			 `laptopcamrep_selected`,`laptopscreenrep_selected`,`laptopspeakerrep_selected`,`datarecovery`,
			 `virusremoval`,`virusremoval_withsoftware`,`HDDHalfTeraWithDataTransfer`,
			 `HDDTeraWithDataTransfer`,`HDDHalfTera`,`HDDTera`,
			 `SSDHalfTeraWithDataTransfer`,`SSDTeraWithDataTransfer`,`SSDHalfTera`,
			 `SSDTera`,`hdmirep_selected`, `harddrive_selected`)
			 VALUES (NULL,'{$body['screenrep_selected']}','{$body['headrep_selected']}','{$body['earrep_selected']}','{$body['powerrep_selected']}','{$body['rearcamrep_selected']}','{$body['frontcamrep_selected']}','{$body['homerep_selected']}','{$body['microphone_selected']}','{$body['chargeport_selected']}','{$body['volumerep_selected']}','{$body['battrep_selected']}','{$body['signalrep_selected']}','{$body['backglassrep_selected']}','{$body['screenOffer']}','{$body['phoneOffer']}','{$body['keyboardrep_selected']}','{$body['fanrep_selected']}','{$body['laptopcamrep_selected']}','{$body['laptopscreenrep_selected']}','{$body['laptopspeakerrep_selected']}','{$body['datarecovery']}','{$body['virusremoval']}','{$body['virusremoval_withsoftware']}','{$body['HDDHalfTeraWithDataTransfer']}','{$body['HDDTeraWithDataTransfer']}','{$body['HDDHalfTera']}','{$body['HDDTera']}','{$body['SSDHalfTeraWithDataTransfer']}','{$body['SSDTeraWithDataTransfer']}','{$body['SSDHalfTera']}','{$body['SSDTera']}','{$body['hdmirep_selected']}','{$body['harddriverep_selected']}')";

		$selectedrepairs = mysqli_query($this->db,$sql1);

		$repairs_id =  mysqli_insert_id($this->db);

		$sql2 = "INSERT INTO `booking_timestamps`(`timestamp_no`, `created_at`, `transfer_timestamp`, `cancelled_timestamp`) VALUES (NULL,CURRENT_TIMESTAMP,NULL,NULL)";

		$timestamp = mysqli_query($this->db,$sql2);

		$timestamp_id = mysqli_insert_id($this->db);

		$sql3 = "INSERT INTO `invoices`(`invoice_no`, `tax_id`, `unsettled_timestamp`, `settled_timestamp`) VALUES (NULL,NULL,CURRENT_TIMESTAMP,NULL)";

		$invoice = mysqli_query($this->db,$sql3);

		$invoice_id = mysqli_insert_id($this->db);

		$res  = mysqli_query($this->db,"SELECT * FROM `booking_timestamps` WHERE `timestamp_no` = '$timestamp_id'");
		$row = mysqli_fetch_assoc($res);
		$timestamp = $row['created_at'];

		if ($body["device"] == '1' || $body["device"] == '2'){
    		$sql = "INSERT INTO `bookings`(`bookings_id`, `carrier_no`, `other_carrier`, `color_no`, `other_color`, `customer_id`, `devtype_id`, `invoice_no`, `invoicestatus_no`, `devicebrand_id`, `devicemodel_id`, `other_phonebrand`, `other_phonemodel`, `tabletbrand_id`, `tabletmodel_id`, `other_tabletbrand`, `other_tabletmodel`, `ticket_no`, `leadstatus_no`, `timestamp_no`, `selectedrepair_no`, `consume_no`, `agent_id`, `total_price`,`repairstatus_no`,`ticketstatus_no`,`location_id`) VALUES (NULL,'{$body['network']}',NULL,'{$body['color']}',NULL,'{$body['customer_id']}','{$body['device']}','{$invoice_id}','1','{$body['brand']}','{$body['model']}',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1', '{$timestamp_id}','{$repairs_id}',NULL,'{$body['agent_id']}','{$body['total']}',NULL,NULL,'{$body['location_id']}')";
    	}else{
    		 if ($body["device"] == '3'){
    			$sql = "INSERT INTO `bookings`(`bookings_id`, `carrier_no`, `other_carrier`, `color_no`, `other_color`, `customer_id`, `devtype_id`, `invoice_no`, `invoicestatus_no`, `devicebrand_id`, `devicemodel_id`, `other_phonebrand`, `other_phonemodel`, `tabletbrand_id`, `tabletmodel_id`, `other_tabletbrand`, `other_tabletmodel`, `ticket_no`, `leadstatus_no`, `timestamp_no`, `selectedrepair_no`, `consume_no`, `agent_id`, `total_price`,`repairstatus_no`,`ticketstatus_no`,`location_id`) VALUES (NULL,NULL,NULL,NULL,NULL,'{$body['customer_id']}','{$body['device']}','{$invoice_id}','1','{$body['brand']}',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1', '{$timestamp_id}','{$repairs_id}',NULL,'{$body['agent_id']}','{$body['total']}',NULL,NULL,'{$body['location_id']}')";
    			}else{
    				$sql = "INSERT INTO `bookings`(`bookings_id`, `carrier_no`, `other_carrier`, `color_no`, `other_color`, `customer_id`, `devtype_id`, `invoice_no`, `invoicestatus_no`, `devicebrand_id`, `devicemodel_id`, `other_phonebrand`, `other_phonemodel`, `tabletbrand_id`, `tabletmodel_id`, `other_tabletbrand`, `other_tabletmodel`, `ticket_no`, `leadstatus_no`, `timestamp_no`, `selectedrepair_no`, `consume_no`, `agent_id`, `total_price`,`repairstatus_no`,`ticketstatus_no`,`location_id`) VALUES (NULL,NULL,NULL,NULL,NULL,'{$body['customer_id']}','{$body['device']}','{$invoice_id}','1','{$body['brand']}','{$body['model']}',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1', '{$timestamp_id}','{$repairs_id}',NULL,'{$body['agent_id']}','{$body['total']}',NULL,NULL,'{$body['location_id']}')";
    			}
    	}
		

		$result = mysqli_query($this->db,$sql);

		if ($result) {
    		$row["id"] = mysqli_insert_id($this->db);
			} else {
   				 return "Error: " . $sql . "<br>" . mysqli_error($this->db);
			}
		//return $row;

		
		
		

		$pdf = new FPDF();

		$pdf->AddPage();
		$pdf->SetFont('Arial','B',25);

		$w = array(125,65);

		$pdf->Image('https://booking.iphixx.com/assets/imgs/invoice.jpg',10,10,60,0,'JPG');
		
		$pdf->Cell(60,15,'');
		$pdf->Ln();
		$pdf->Ln();

		$pdf->SetTextColor(50,50,50);
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(55,15,'Booking Number: ');
		$pdf->SetFont('Arial','',14);
		$pdf->Cell(60,15,'123456789');
		$pdf->Ln();

		$pdf->SetTextColor(164, 171, 176);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(90,8,'Booked By: ');
		$pdf->Cell(90,8,'Customer Details: ');
		$pdf->Ln();

		$pdf->SetTextColor(50,50,50);
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(90,8,$agentStoreName);
		$pdf->Cell(90,8,$userFirstName. " ". $userLastName);
		$pdf->Ln();

		$pdf->SetTextColor(50,50,50);
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(90,8,$agentFirstName. " ". $agentLastName);
		$pdf->Cell(90,8,$userEmail);
		$pdf->Ln();

		$pdf->SetTextColor(50,50,50);
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(90,8,$timestamp);
		$pdf->Cell(90,8,$phoneNumber);
		$pdf->Ln();
		$pdf->Ln();

		$pdf->SetTextColor(50,50,50);
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(180,10,$brand. " ". $model. " ". $color. " ".$carrier);
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

		$header = array('SELECTED REPAIRS', 'AMOUNT');
		
    	// Header
    	for($i=0;$i<count($header);$i++){
    		$pdf->SetDrawColor(63,139,197);
    		$pdf->SetFillColor(63,139,197);
    		$pdf->SetTextColor(255,255,255);
        	$pdf->Cell($w[$i],20,$header[$i],1,0,'C',1);
    	}	
    	$pdf->Ln();

    	$pdf->SetDrawColor(220,220,220);
    	$pdf->SetFillColor(0);
    	$pdf->SetTextColor(50,50,50);
    	$pdf->SetFont('Arial','',12);

    	if ($body["screenrep_selected"] == '1'){
    		$pdf->Cell($w[0],10,'Screen Replacement',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["screenrep_price"],1,0,'C');
    		$pdf->Ln();
    	}

    	if ($body["headrep_selected"] == '1'){
    		$pdf->Cell($w[0],10,'Headphone Repair',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["headrep_price"],1,0,'C');
    		$pdf->Ln();
    	}

    	if ($body["earrep_selected"] == '1'){
    		$pdf->Cell($w[0],10,'Earpiece Repair',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["earrep_price"],1,0,'C');
    		$pdf->Ln();
    	}

    	if ($body["powerrep_selected"] == '1'){
    		$pdf->Cell($w[0],10,'Power Button Repair',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["powerrep_price"],1,0,'C');
    		$pdf->Ln();
    	}

    	if ($body["rearcamrep_selected"] == '1'){
    		$pdf->Cell($w[0],10,'Rear Camera Repair',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["rearcamrep_price"],1,0,'C');
    		$pdf->Ln();
    	}

    	if ($body["frontcamrep_selected"] == '1'){
    		$pdf->Cell($w[0],10,'Front Camera Repair',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["frontcamrep_price"],1,0,'C');
    		$pdf->Ln();
    	}

    	if ($body["homerep_selected"] == '1'){
    		$pdf->Cell($w[0],10,'Home Button Repair',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["homerep_price"],1,0,'C');
    		$pdf->Ln();
    	}

    	if ($body["microphone_selected"] == '1'){
    		$pdf->Cell($w[0],10,'Microphone Repair',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["microphone_price"],1,0,'C');
    		$pdf->Ln();
    	}

    	if ($body["chargeport_selected"] == '1'){
    		$pdf->Cell($w[0],10,'Charging Port Repair',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["chargeport_price"],1,0,'C');
    		$pdf->Ln();
    	}

    	if ($body["volumerep_selected"] == '1'){
    		$pdf->Cell($w[0],10,'Volume Button Repair',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["homerep_price"],1,0,'C');
    		$pdf->Ln();
    	}

    	if ($body["battrep_selected"] == '1'){
    		$pdf->Cell($w[0],10,'Battery Repair',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["battrep_price"],1,0,'C');
    		$pdf->Ln();
    	}

    	if ($body["signalrep_selected"] == '1'){
    		$pdf->Cell($w[0],10,'Cellular Signal Repair',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["signalrep_price"],1,0,'C');
    		$pdf->Ln();
    	}

    	if ($body["backglassrep_selected"] == '1'){
    		$pdf->Cell($w[0],10,'Back Glass Repair',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["backglassrep_price"],1,0,'C');
    		$pdf->Ln();
    	}

    	if ($body["screenOffer"] == '1'){
    		$pdf->Cell($w[0],10,'Screen Protector',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["screenOffer_price"],1,0,'C');
    		$pdf->Ln();
    	}


    	if ($body["phoneOffer"] == '1'){
    		$pdf->Cell($w[0],10,'Temporary Phone',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["phoneOffer_price"],1,0,'C');
    		$pdf->Ln();
    	}

    	if ($body["harddriverep_selected"] == '1'){
    		$pdf->Cell($w[0],10,'Hard Drive Repair',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["harddrive_rep"],1,0,'C');
    		$pdf->Ln();
    	}

    	if ($body["hdmirep_selected"] == '1'){
    		$pdf->Cell($w[0],10,'HDMI Port Replacement',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["hdmirep_price"],1,0,'C');
    		$pdf->Ln();
    	}

    	if ($body["laptopscreenrep_selected"] == '1'){
    		$pdf->Cell($w[0],10,'Laptop Screen Replacement',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["laptopscreenrep_price"],1,0,'C');
    		$pdf->Ln();
    	}

    	if ($body["laptopcamrep_selected"] == '1'){
    		$pdf->Cell($w[0],10,'Laptop Camera Repair',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["laptopcamrep_price"],1,0,'C');
    		$pdf->Ln();
    	}

    	if ($body["keyboardrep_selected"] == '1'){
    		$pdf->Cell($w[0],10,'Keyboard Replacement',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["keyboardrep_price"],1,0,'C');
    		$pdf->Ln();
    	}

    	if ($body["fanrep_selected"] == '1'){
    		$pdf->Cell($w[0],10,'Fan Replacement',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["fanrep_price"],1,0,'C');
    		$pdf->Ln();
    	}

    	if ($body["laptopspeakerrep_selected"] == '1'){
    		$pdf->Cell($w[0],10,'Laptop Speaker Repair',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["laptopspeakerrep_price"],1,0,'C');
    		$pdf->Ln();
    	}

    	if ($body["laptopchargeport_selectedd"] == '1'){
    		$pdf->Cell($w[0],10,'Laptop Charging Port Repair',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["laptopchargerep_price"],1,0,'C');
    		$pdf->Ln();
    	}

    	if ($body["laptopbattrep_selected"] == '1'){
    		$pdf->Cell($w[0],10,'Laptop Battery Replacement',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["laptopbatteryrep_price"],1,0,'C');
    		$pdf->Ln();
    	}

    	if ($body["datarecovery"] == '1'){
    		$pdf->Cell($w[0],10,'Data Recovery',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["datarecovery_price"],1,0,'C');
    		$pdf->Ln();
    	}

    	if ($body["virusremoval_withsoftware"] == '1'){
    		$pdf->Cell($w[0],10,'Virus Removal w/ free 1yr Anti-Virus',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["virusremoval_price"],1,0,'C');
    		$pdf->Ln();
    	}

    	if ($body["HDDHalfTeraWithDataTransfer"] == '1'){
    		$pdf->Cell($w[0],10,'HDD Replacement - 500GB (OS & Data Transfer)',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["HDDHalfTeraWithDataTransfer_price"],1,0,'C');
    		$pdf->Ln();
    	}

    	if ($body["HDDTeraWithDataTransfer"] == '1'){
    		$pdf->Cell($w[0],10,'HDD Replacement - 1TB (OS & Data Transfer)',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["HDDTeraWithDataTransfer_price"],1,0,'C');
    		$pdf->Ln();
    	}

    	if ($body["HDDHalfTera"] == '1'){
    		$pdf->Cell($w[0],10,'HDD Replacement - 500GB (OS Reinstall)',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["HDDHalfTera_price"],1,0,'C');
    		$pdf->Ln();
    	}

    	if ($body["HDDTera"] == '1'){
    		$pdf->Cell($w[0],10,'HDD Replacement - 1TB (OS Reinstall)',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["HDDTera_price"],1,0,'C');
    		$pdf->Ln();
    	}

    	if ($body["SSDHalfTeraWithDataTransfer"] == '1'){
    		$pdf->Cell($w[0],10,'SSD Replacement - 500GB (OS & Data Transfer)',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["SSDHalfTeraWithDataTransfer_price"],1,0,'C');
    		$pdf->Ln();
    	}
    	if ($body["SSDTeraWithDataTransfer"] == '1'){
    		$pdf->Cell($w[0],10,'SSD Replacement - 1TB (OS & Data Transfer)',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["SSDTeraWithDataTransfer_price"],1,0,'C');
    		$pdf->Ln();
    	}

    	if ($body["SSDHalfTera"] == '1'){
    		$pdf->Cell($w[0],10,'SSD Replacement - 500GB (OS Reinstall)',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["SSDHalfTera_price"],1,0,'C');
    		$pdf->Ln();
    	}

    	if ($body["SSDTera"] == '1'){
    		$pdf->Cell($w[0],10,'SSD Replacement - 1TB (OS Reinstall)',1,0,'C');
        	$pdf->Cell($w[1],10,EURO. " ".$body["SSDTera_price"],1,0,'C');
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

    	$pdf->SetTextColor(63,139,197);
    	$pdf->SetFont('Arial','B',20);

        $pdf->Cell(70,12,"",'','','C');
    	$pdf->Cell(60,12,"TOTAL AMOUNT: ",'','','C');

    	$pdf->SetTextColor(0);
    	$pdf->SetFont('Arial','B',20);
        $pdf->Cell(60,12,EURO. " ".$body["total"]. ".00 ",'','','C');
		
		$pdf->Ln();
		
		$pdfdoc = $pdf->Output('', 'S');

		$mail = new PHPMailer(true);                              // Passing `true` enables exceptions

		try{

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
    $mail->setFrom('iphixxmail@admin.iphixx.com',"iPhixx Phone Repair Services");
    $mail->addBCC($userEmail);      // Add a recipient
    $mail->addBCC('ryan@iphixx.com');
    $mail->addBCC('shealy@iphixx.com');
    $mail->addBCC('sales@iphixx.com');
    // $mail->addAddress('ellen@example.com');               // Name is optional
    //$mail->addReplyTo('iphixxmail@admin.iphixx.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    // //Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    $mail->addStringAttachment($pdfdoc, $userFirstName. " ". $userLastName." ".$brand. " ". $model. " ". $color. " ".$carrier.'.pdf');

    $mail->DKIM_domain = "admin.iphixx.com";
    $mail->DKIM_private = "admin.iphixx.com.private"; //path to file on the disk.
    $mail->DKIM_selector = "phpmailer";// change this to whatever you set during step 2
    $mail->DKIM_passphrase = "";
    $mail->DKIM_identifier = $mail->From;
    $mail->DKIM_extraHeaders = ['List-Unsubscribe', 'List-Help'];


    //Content
    $mail->isHTML(true);                                  // Set email format to HTML

  
    		$mail->Subject = 'IPhixx Repair Invoice';
    			$mail->Body    = "Hi ".$userFirstName."! Thank you for your business with iPhixx. The attached file shall serve as your invoice.";
    			$mail->AltBody = "Hi ".$userFirstName."! Thank you for your business with iPhixx. The attached file shall serve as your invoice.";

   
    //$mail->send();

    $mail->send();
    return $mail;
} catch (Exception $e) {
    return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

		
	}
}
	