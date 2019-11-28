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

	// public function getModels() {
	// 	$sql = "SELECT `device_models`.`model_name`, `phone_brands`.`phone_brand`, `device_type`.`type`
	// 	FROM `device_models`
	// 				INNER JOIN `phone_brands` ON `device_models`.`phonebrand_id` = `phone_brands`.`phonebrand_id`
	// 				INNER JOIN `device_type` ON `device_models`.`devtype_id` = `device_type`.`devtype_id`";
	// 	$result = mysqli_query($this->db,$sql);
	// 	$row=mysqli_fetch_all($result,MYSQLI_ASSOC);
	// 	// print_r($result);
	// 	return $row;
	// }

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



	public function logInAgent($body){
		
		$password = md5($body["password"]);

		$sql = "SELECT `agent_id`,`agent_username` FROM `agents` where `agent_email` = '{$body["email"]}' 
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
		$sql = "INSERT INTO `customers` (`customer_id`, `customer_fname`, `customer_lname` , `email`, `phone`,`altPhone`, `birthdate`) VALUES (NULL, '{$body["firstName"]}', '{$body["lastName"]}' ,  '{$body["email"]}', '{$body["mobile"]}','{$body["phone"]}', '{$body["birthdate"]}')";
		$result = mysqli_query($this->db, $sql);
		if ($result) {
    		$row["id"] = mysqli_insert_id($this->db);
			} else {
   				 echo "Error: " . $sql . "<br>" . mysqli_error($this->db);
			}
		return $row;
	}

	public function updateCustomer($id , $body){
		$sql = "UPDATE `customers` SET `customer_fname` = '{$body["customer_fname"]}', `customer_lname` = '{$body["customer_lname"]}', `email` = '{$body["email"]}', `phone` = '{$body["phone"]}', `location` = '{$body["location"]}', `birthdate` = '{$body["birthdate"]}' WHERE `customers`.`customer_id` = {$id}";
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
		$sql = "UPDATE `dev_owner` INNER JOIN `bookings` ON `bookings`.`owner_id` = `dev_owner`.`owner_id` SET `owner_name` = '{$body["fullname"]}', `email` = '{$body["email"]}', `phone` = '{$body["phone"]}', `location` = '{$body["location"]}', `birthdate` = '{$body["birthdate"]}' WHERE `bookings`.`id` = {$id}";
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

	public function getOwner($id) {
		$sql = "SELECT `customers`.`customer_id`,`customers`.`customer_fname`, `customers`.`customer_lname`,  `customers`.`location`,  `customers`.`email`, `customers`.`location`, `customers`.`phone`,  `customers`.`birthdate`, `customers`.`address` FROM `bookings` INNER JOIN `customers` 
		ON `bookings`.`customer_id` = `customers`.`customer_id` 
		WHERE `bookings`.`bookings_id` = {$id}";
		$result = mysqli_query($this->db,$sql);
		$row=mysqli_fetch_assoc($result);
		return $row;
	}

	public function getRepair($id){
		$sql = "SELECT `device_type`.`type`,`phone_brands`.`phone_brand`, `device_models`.`model_name`, `device_models`.`model_number`, `carriers`.`carrier_name`, `color`.`color_name`,`selected_repairs`.`screenrep_selected`,`selected_repairs`.`headrep_selected`,`selected_repairs`.`earrep_selected`,`selected_repairs`.`powerrep_selected`,`selected_repairs`.`rearcamrep_selected`,`selected_repairs`.`frontcamrep_selected`,`selected_repairs`.`homerep_selected`,`selected_repairs`.`microphone_selected`,`selected_repairs`.`chargeport_selected`,`selected_repairs`.`volumerep_selected`,`selected_repairs`.`battrep_selected`,`selected_repairs`.`signalrep_selected`,`selected_repairs`.`backglassrep_selected`
			FROM `bookings` INNER JOIN `device_type` ON `bookings`.`devtype_id` = `device_type`.`devtype_id` 
							INNER JOIN `phone_brands` ON `bookings`.`phonebrand_id` = `phone_brands`.`phonebrand_id` 
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


	public function getInvoicesByPage($params){
		$page = $params['page'];
		$limit = 15;
		$offset = ($page - 1)  * $limit;
		$start = $offset + 1;

		$sql_count = "SELECT `invoices`.`invoice_no`,`invoices`.`settled_Timestamp`,`invoices`.`unsettled_Timestamp`, `customers`.`customer_fname`,`customers`.`customer_lname`, `invoice_statuses`.`invoice_status`,`bookings`.`total_price` FROM `bookings` LEFT JOIN `invoices` ON `bookings`.`invoice_no` = `invoices`.`invoice_no` LEFT JOIN `customers` ON `bookings`.`customer_id` = `customers`.`customer_id` LEFT JOIN `invoice_statuses` ON `bookings`.`invoicestatus_no` = `invoice_statuses`.`invoicestatus_no` ";
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

		$sql_count = "SELECT `tickets`.`ticket_no`,`ticket_statuses`.`ticket_status`,`tickets`.`created_at`,`customers`.`customer_fname`,`customers`.`customer_lname`
					  FROM `bookings`  
					  		LEFT JOIN `customers` ON `bookings`.`customer_id` = `customers`.`customer_id`
							LEFT JOIN `tickets` ON `bookings`.`ticket_no` = `tickets`.`ticket_no`
							LEFT JOIN `ticket_statuses` ON `bookings`.`ticketstatus_no` = `ticket_statuses`.`ticketstatus_no`";
		$result_count = mysqli_query($this->db,$sql_count);
	  	$total=mysqli_num_rows($result_count);
		$row["total_page"] = ceil($total / $limit);
		

		$sql = "SELECT `tickets`.`ticket_no`,`ticket_statuses`.`ticket_status`,`tickets`.`created_at`,`customers`.`customer_fname`,`customers`.`customer_lname`
					  FROM `bookings`  
					  		LEFT JOIN `customers` ON `bookings`.`customer_id` = `customers`.`customer_id`
							LEFT JOIN `tickets` ON `bookings`.`ticket_no` = `tickets`.`ticket_no`
							LEFT JOIN `ticket_statuses` ON `bookings`.`ticketstatus_no` = `ticket_statuses`.`ticketstatus_no` LIMIT 15 OFFSET {$offset} ";
							
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
							LEFT JOIN `device_brands` ON `inventory`.`devicebrand_id` = `device_brands`.`devicebrand_id` ";
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

		$sql_count = "SELECT `devicemodel_id`, `model_name`, `model_number`, `screenrep_price`, `headrep_price`, `earrep_price`, `powerrep_price`, `rearcamrep_price`, `frontcamrep_price`, `homerep_price`, `microphone_price`, `chargeport_price`, `volumerep_price`, `battrep_price`, `signalrep_price`, `backglass_price`, `devtype_id`, `devicebrand_id` FROM `device_models` WHERE `devtype_id` = '{$params['device_id']}' AND `devicebrand_id`  = '{$params['brand_id']}' ";
		$result_count = mysqli_query($this->db,$sql_count);
	  	$total=mysqli_num_rows($result_count);
		$row["total"] = ceil($total / $limit);
		

		$sql = "SELECT `devicemodel_id`, `model_name`, `model_number`, `screenrep_price`, `headrep_price`, `earrep_price`, `powerrep_price`, `rearcamrep_price`, `frontcamrep_price`, `homerep_price`, `microphone_price`, `chargeport_price`, `volumerep_price`, `battrep_price`, `signalrep_price`, `backglass_price`, `devtype_id`, `devicebrand_id` FROM `device_models` WHERE `devtype_id` = '{$params['device_id']}' AND `devicebrand_id`  = '{$params['brand_id']}'";
							
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

	public function getAllModelsByPage($params){
		$model = $params['model'];
		$limit = 15;
		$offset = ($page - 1)  * $limit;
		$start = $offset + 1;

		$sql_count = "SELECT `devicemodel_id`, `model_name`, `model_number`, `screenrep_price`, `headrep_price`, `earrep_price`, `powerrep_price`, `rearcamrep_price`, `frontcamrep_price`, `homerep_price`, `microphone_price`, `chargeport_price`, `volumerep_price`, `battrep_price`, `signalrep_price`, `backglass_price`, `devtype_id`, `devicebrand_id` FROM `device_models`";
		$result_count = mysqli_query($this->db,$sql_count);
	  	$total=mysqli_num_rows($result_count);
		$row["total"] = ceil($total / $limit);
		

		$sql = "SELECT `devicemodel_id`, `model_name`, `model_number`, `screenrep_price`, `headrep_price`, `earrep_price`, `powerrep_price`, `rearcamrep_price`, `frontcamrep_price`, `homerep_price`, `microphone_price`, `chargeport_price`, `volumerep_price`, `battrep_price`, `signalrep_price`, `backglass_price`, `devtype_id`, `devicebrand_id` FROM `device_models`";
							
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
		$sql = "UPDATE `device_models` SET `screenrep_price` = '{$body['screenrep_price']}', `headrep_price` = '{$body['headrep_price']}' , `earrep_price` = '{$body['earrep_price']}', `powerrep_price` = '{$body['powerrep_price']}', `rearcamrep_price` = '{$body['rearcamrep_price']}', `frontcamrep_price` = '{$body['frontcamrep_price']}', `homerep_price` = '{$body['homerep_price']}', `microphone_price` = '{$body['microphone_price']}', `chargeport_price` = '{$body['chargeport_price']}', `volumerep_price` = '{$body['volumerep_price']}', `battrep_price` = '{$body['battrep_price']}', `signalrep_price` = '{$body['signalrep_price']}', `backglass_price` = '{$body['backglass_price']}' WHERE `device_models`.`devicemodel_id` = {$id}";
		$result = mysqli_query($this->db,$sql);
		return $result;
	}

	public function updateLaptopPrice($id , $body){
		$sql = "UPDATE `device_models` SET `screenrep_price` = '{$body['screenrep_price']}', `headrep_price` = '{$body['headrep_price']}' , `earrep_price` = '{$body['earrep_price']}', `powerrep_price` = '{$body['powerrep_price']}', `rearcamrep_price` = '{$body['rearcamrep_price']}', `frontcamrep_price` = '{$body['frontcamrep_price']}', `homerep_price` = '{$body['homerep_price']}', `microphone_price` = '{$body['microphone_price']}', `chargeport_price` = '{$body['chargeport_price']}', `volumerep_price` = '{$body['volumerep_price']}', `battrep_price` = '{$body['battrep_price']}', `signalrep_price` = '{$body['signalrep_price']}', `backglass_price` = '{$body['backglass_price']}' WHERE `device_models`.`devicemodel_id` = {$id}";
		$result = mysqli_query($this->db,$sql);
		return $result;
	}

	public function  editOwner($id,$body){

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
		

		$res  = mysqli_query($this->db,"SELECT `customers`.`email` FROM `bookings` INNER JOIN `customers` ON `bookings`.`customer_id` = `customers`.`customer_id` WHERE `bookings`.`bookings_id` = {$id}");
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

		$sql1 = "INSERT INTO `selected_repairs`(`selectedrepair_no`, `screenrep_selected`, `headrep_selected`, `earrep_selected`, `powerrep_selected`, `rearcamrep_selected`, `frontcamrep_selected`, `homerep_selected`, `microphone_selected`, `chargeport_selected`, `volumerep_selected`, `battrep_selected`, `signalrep_selected`, `backglassrep_selected`, `protector_selected`, `tempPhone_selected`) VALUES (NULL,'{$body['screenrep_selected']}','{$body['headrep_selected']}','{$body['earrep_selected']}','{$body['powerrep_selected']}','{$body['rearcamrep_selected']}','{$body['frontcamrep_selected']}','{$body['homerep_selected']}','{$body['microphone_selected']}','{$body['chargeport_selected']}','{$body['volumerep_selected']}','{$body['battrep_selected']}','{$body['signalrep_selected']}','{$body['backglassrep_selected']}','{$body['screenOffer']}','{$body['phoneOffer']}')";

		$selectedrepairs = mysqli_query($this->db,$sql1);

		$repairs_id =  mysqli_insert_id($this->db);

		$sql2 = "INSERT INTO `booking_timestamps`(`timestamp_no`, `created_at`, `transfer_timestamp`, `cancelled_timestamp`) VALUES (NULL,CURRENT_TIMESTAMP,NULL,NULL)";

		$timestamp = mysqli_query($this->db,$sql2);

		$timestamp_id = mysqli_insert_id($this->db);

		$sql = "INSERT INTO `bookings`(`bookings_id`, `carrier_no`, `other_carrier`, `color_no`, `other_color`, `customer_id`, `devtype_id`, `invoice_no`, `invoicestatus_no`, `devicebrand_id`, `devicemodel_id`, `other_phonebrand`, `other_phonemodel`, `tabletbrand_id`, `tabletmodel_id`, `other_tabletbrand`, `other_tabletmodel`, `ticket_no`, `leadstatus_no`, `timestamp_no`, `selectedrepair_no`, `consume_no`, `agent_id`, `total_price`,`repairstatus_no`,`ticketstatus_no`) VALUES (NULL,'{$body['network']}',NULL,'{$body['color']}',NULL,'{$body['customer_id']}','{$body['device']}',NULL,NULL,'{$body['brand']}','{$body['model']}',NULL,NULL,NULL,NULL,NULL,NULL,NULL,'1', '{$timestamp_id}','{$repairs_id}',NULL,'{$body['agent_id']}','{$body['total']}',NULL,NULL)";

		$result = mysqli_query($this->db,$sql);

		if ($result) {
    		$row["id"] = mysqli_insert_id($this->db);
			} else {
   				 echo "Error: " . $sql . "<br>" . mysqli_error($this->db);
			}
		return $row;

		// $sql = "INSERT INTO `invoices`(`total_price`) VALUES ({$body["total"]})";
		// $result = mysqli_query($this->db,$sql);

		// $sql = "INSERT INTO `tickets`(`ticket_no`) VALUES ('10002')";
		// $result = mysqli_query($this->db,$sql);

		// $sql = "INSERT INTO `bookings` (`owner_id`, `customer_id`, `created_at`, `repstatus_no`, `invoice_no`,`paystatus_no`,`ticket_no`) VALUES ('3', '42', CURRENT_TIMESTAMP, '1','10568', '1','10003')";
		// $result = mysqli_query($this->db,$sql);

		// $pdf = new FPDF();

		// $pdf->AddPage();
		// $pdf->SetFont('Arial','B',16);

		// $w = array(120,50);

		// $pdf->Cell(40,10,'Invoice');
		// $pdf->Ln();
		// $pdf->Cell(40,10,'Iphixx');
		// $pdf->Ln();
		// $pdf->Cell(40,10,'Name of Store: Walmart');
		// $pdf->Ln();
		// $pdf->Cell(40,10,'Name of Agent: Ryan Margolin');
		// $pdf->Ln();
		// $pdf->Cell(40,10,'Repair Number Confirmation: 0123456789');
		// $pdf->Ln();
		// $pdf->Cell(40,10,'Customer Details');
		// $pdf->Ln();
		// $pdf->Cell(40,10,'Full Name: '. $body["fullname"]);
		// $pdf->Ln();
		// $pdf->Cell(40,10,'Email: '. $body["email"]);
		// $pdf->Ln();
		// $pdf->Cell(40,10,'Device Details');
		// $pdf->Ln();
		// $pdf->Cell(40,10,'Device Model: '. $body["brand"]. " ". $body["model"]);
		// $pdf->Ln();
		// $pdf->Cell(40,10,'Color: '. $body["color"]);
		// $pdf->Ln();
		// $pdf->Cell(40,10,'Carrier: '. $body["network"]);
		// $pdf->Ln();

		// define('EURO',chr(128));

		// $selectedrepairs=array();
		// $repairstring=str_replace(array( '[', ']','"'), '', $body["repair"]);
		// for($i=0;$i<$body["repairlength"];$i++)
		// 	$selectedrepairs = explode(',',$repairstring);

		// $prices=array();
		// $pricestring=str_replace(array( '[', ']','"'), '', $body["prices"]);
		// for($i=0;$i<$body["repairlength"];$i++)
		// 	$prices = explode(',',$pricestring);

		// $header = array('Selected Repairs', 'Cost');
		// echo "this". $selectedrepairs[0];
		// echo "this". $selectedrepairs[1];
		// echo "this". $prices[0];
		// echo "this". $prices[1];
		// // echo "this2". $selectedrepairs[1];
  //   	// Header
  //   	for($i=0;$i<count($header);$i++)
  //       	$pdf->Cell($w[$i],7,$header[$i],1,0,'C');
  //   	$pdf->Ln();

  //   	for($i=0;$i<count($selectedrepairs);$i++){
  //       	$pdf->Cell($w[0],7,$selectedrepairs[$i],1,0,'C');
  //       	$pdf->Cell($w[1],7,$prices[$i]. " ".EURO,1,0,'C');
  //   		$pdf->Ln();
  //   }
  //   $pdf->Ln();
  //   	// Data
  //   	// foreach($selectedrepairs as $row)
  //   	// {
  //    //    	$pdf->Cell($w[0],6,$row[0],'LR');
  //    //    	$pdf->Cell($w[1],6,$row[1],'LR');
  //    //    	$pdf->Ln();
  //   	// }
  //   	// Closing line

  //   	$pdf->Cell($w[0],7,"Total Cost: ",'','','C');
  //       $pdf->Cell($w[1],7,$body["total"]. ".00 ".EURO,'','','C');
		
		// $pdf->Ln();
		// $pdf->Cell(40,10,"Thank you for your business with iPhixx!");
		
		// $pdfdoc = $pdf->Output('', 'S');

		// $mail = new PHPMailer(true);                              // Passing `true` enables exceptions

  //   //Server settings
  //   $mail->SMTPDebug = 2;                                 // Enable verbose debug output
  //   $mail->isSMTP();                                      // Set mailer to use SMTP
  //   $mail->Host = 'admin.iphixx.com';  // Specify main and backup SMTP servers
  //   $mail->SMTPAuth = true;                               // Enable SMTP authentication
  //   $mail->Username = 'iphixxmail@admin.iphixx.com';                 // SMTP username
  //   $mail->Password = '{Ae,E$R};!M)';                           // SMTP password
  //   $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
  //   $mail->Port =  465;                                    // TCP port to connect to




  //   //Recipients
  //   $mail->setFrom('iphixxmail@admin.iphixx.com',"Mailer");
  //   $mail->addAddress($body["email"]);     // Add a recipient
  //   // $mail->addAddress('ellen@example.com');               // Name is optional
  //   //$mail->addReplyTo('iphixxmail@admin.iphixx.com', 'Information');
  //   // $mail->addCC('cc@example.com');
  //   // $mail->addBCC('bcc@example.com');

  //   // //Attachments
  //   // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
  //   // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
  //   $mail->addStringAttachment($pdfdoc, 'my-doc.pdf');

  //   $mail->DKIM_domain = "admin.iphixx.com";
  //   $mail->DKIM_private = "admin.iphixx.com.private"; //path to file on the disk.
  //   $mail->DKIM_selector = "phpmailer";// change this to whatever you set during step 2
  //   $mail->DKIM_passphrase = "";
  //   $mail->DKIM_identifier = $mail->From;
  //   $mail->DKIM_extraHeaders = ['List-Unsubscribe', 'List-Help'];


  //   //Content
  //   $mail->isHTML(true);                                  // Set email format to HTML

  
  //   		$mail->Subject = 'Invoice';
  //   			$mail->Body    = 'Here is your receipt';
  //   			$mail->AltBody = 'Here is your receipt';

   
  //   $mail->send();

	//	return $result;
	}
}
	