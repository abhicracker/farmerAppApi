<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {
	
	function __construct(){
        parent::__construct();
        $this->load->model('User');
		$this->load->model('App_Model');

	}





	public function index()
	{
		$this->load->view('header');
		$this->load->view('login');
		$this->load->view('footer');
	}
	public function home()
	{
		$data["usersCount"] = $this->db->get("user")->num_rows();
		$data["ridersCount"] = $this->db->get("rider")->num_rows();
		$data["ridesCount"] = $this->db->get("ride")->num_rows();
		$this->load->view('header');
		$this->load->view('sidebar');
		$this->parser->parse('home',$data);
		$this->load->view('footer');
	}


	public function users()
	{
		$data["users"] = $this->db->get("user")->result();
		$this->load->view('header');
		$this->load->view('sidebar');
		$this->parser->parse('users',$data);
		$this->load->view('footer');
	}

	public function riders()
	{
		$data["riders"] = $this->db->get("rider")->result();
		$this->load->view('header');
		$this->load->view('sidebar');
		$this->parser->parse('riders',$data);
		$this->load->view('footer');
	}

	public function rides()
	{
		$data["rides"] = $this->db->get("ride")->result();
		$this->load->view('header');
		$this->load->view('sidebar');
		$this->parser->parse('rides',$data);
		$this->load->view('footer');
	}
	











		// APIS START HERE ////////////////////////////////
 
	 public function loginUser(){
		$phone  = $this->input->post('phone');
	    $password =  $this->input->post('password');
		$result = $this->User->loginUser($phone,$password);
	
		 if($result != null ){
			echo json_encode($result[0]);

		 }else{
			 header('HTTP/1.1 401 Unauthorized', true, 401);
		 }
		}

		



		public function getRiderStat(){
			$id =  $this->input->post('id');
			date_default_timezone_set('Asia/kolkata');
			$date = date('Y-m-d');
			$condition =array(
				"rider_id" => $id
		
			);

			$m = $this->db->query('SELECT * FROM ride WHERE ride_date >= now() + INTERVAL 1 DAY;');
		
			$condition2 =array(
				"rider_id" => $id,
				"ride_status"=>"requested_by_user"
				
			);
			$condition3 =array(
				"rider_id" => $id,
				"ride_status"=>"cancelled_by_user"
			);
			$result = $this->db->where($condition)->get("ride")->num_rows();
			$result2 = $this->db->where($condition2)->get("ride")->num_rows();
			$result3 = $this->db->where($condition3)->get("ride")->num_rows();

			$data = array(
				'total_ride' => $result,
				'pending_ride' => $result2,
				'cancelled_ride' => $result3,
				
			);
			

			 if($result != null ){
				
				echo json_encode($data);
				
	
			 }else{
				echo json_encode($result);
				 header('HTTP/1.1 401 Unauthorized', true, 401);
			 }
			}
	


		public function signUpUser(){
		$data = array(
			'name'  => $this->input->post('name'),
			'phone'  => $this->input->post('phone'),
			'email'  => $this->input->post('email'),
			"password" =>  $this->input->post('password'),
			"lat" =>  $this->input->post('lat'),
			"lon" =>  $this->input->post('lon'),

		);

		//check email already Exists or not
		$email = $this->input->post('email');
		$checkEmail = $this->User->checkEmail($email,'user');
		$phone = $this->input->post('phone'); 
		$checkPhoneNumber = $this->User->checkPhoneNumber($phone,'user');
		
		if($checkPhoneNumber > 0){
			header('HTTP/1.1 401 Unauthorized', true, 401);
		}else{

		//data to be inserted	
		$inserted_id = $this->User->insertData("user",$data);
		
		//get user details in reponse
		$condition =array(
			"id" => $inserted_id
		);
		
		$usercount = $this->db->where($condition)->get('user')->result();
		//final response
		if($inserted_id > 0 ){
			echo json_encode($usercount[0]);

		}else{
			header('HTTP/1.1 401 Unauthorized', true, 401);
		}
		
		}
	}
		public function addItem(){

			$config['upload_path']          = './assets/images/items/';
			$config['allowed_types']        = 'gif|jpg|png';
			$config['max_size']             = 500;
			$config['max_width']            = 5000;
			$config['max_height']           = 5000;

			$this->load->library('upload', $config);
			
			if ( ! $this->upload->do_upload('image_url'))
			{
					$error = array('error' => $this->upload->display_errors());
					echo json_encode($error);
					header('HTTP/1.1 401 Unauthorized', true, 401);
				
			}
			else
			{
					$data = $this->upload->data();
					$image_path = $data['file_name'];
					//echo json_encode($image_path);
					
			}


			$data = array(
				'owner_id'  => $this->input->post('owner_id'),
				'name'  => $this->input->post('name'),
				'description'  => $this->input->post('description'),
				'image_url'  => $image_path,
				'price_hr'  => $this->input->post('price_hr'),
				'price_day'  => $this->input->post('price_day'),
				'model_no'  => $this->input->post('model_no'),
				'type_of_imp'  => $this->input->post('type_of_imp'),
				'lat'  => $this->input->post('lat'),
				'lon'  => $this->input->post('lon'),
				"type" =>  $this->input->post('type')

			);
	
			
			
		
			$inserted_id = $this->User->insertData("items",$data);
				
			$condition =array(
				"id" => $inserted_id
		
			);
			$usercount = $this->db->where($condition)->get('items')->result();
			if($inserted_id > 0 ){
				echo json_encode($usercount[0]);
	
			}else{
				header('HTTP/1.1 401 Unauthorized', true, 401);
			}
		}	
	


		public function bookItem(){

			$owner_id = $this->input->post('owner_id');
			$book_by_id = $this->input->post('book_by_id');
			$book_date = $this->input->post('book_date');
			$isForDay = $this->input->post('isForDay');
			$book_by_name = $this->input->post('book_by_name');
			$name = $this->input->post('name');
			$type = $this->input->post('type');
			$image_url = $this->input->post('image_url');
			$price = $this->input->post('price');
			$for_hour = $this->input->post('for_hour');
			$booked_from_hr = $this->input->post('booked_from_hr');
			$booked_till_hr = $this->input->post('booked_till_hr');
			$total_price = $this->input->post('total_price');
			$service_charge = $this->input->post('service_charge');
	
			$data = array(
				'owner_id' => $owner_id,
				'book_by_id' => $book_by_id,
				'book_date' => $book_date,
				'isForDay' => $isForDay,
				'book_by_name' => $book_by_name,
				'name' => $name,
				'type' => $type,
				'image_url' => $image_url,
				'price' => $price,
				'for_hour' => $for_hour,
				'booked_from_hr' => $booked_from_hr,
				'booked_till_hr' => $booked_till_hr,
				'total_price' => $total_price,
				'service_charge' => $service_charge,
				'book_lat' => $book_lat,
				'book_lon' => $book_lon


			);
	
			$inserted_id = $this->User->insertData("booking",$data);
				
			$condition =array(
				"id" => $inserted_id
		
			);
			$usercount = $this->db->where($condition)->get('booking')->result();
			if($inserted_id > 0 ){
				echo json_encode($usercount[0]);
	
			}else{
				header('HTTP/1.1 401 Unauthorized', true, 401);
			}
	
			 
		}









	//RIDES APIS STARTS HERE

	public function getAllVehicles(){ 

		$condition =array(
			"type" => 'vehicle'
	
		);
		$result = $this->db->where($condition)->get('items')->result();
		
	 echo json_encode($result);
	}

	public function getAllAppratus(){ 

		$condition =array(
			"type" => 'appratus'
	
		);
		$result = $this->db->where($condition)->get('items')->result();
		
	 echo json_encode($result);
	}

	//Item user have booked
	public function getMyBooking(){
		$id = $this->input->post('id');
		$condition = array(
			'book_by_id' => $id
		);
		$result = $this->db->where($condition)->get('booking')->result();
		echo json_encode($result);
	}


	
		//Item boooked by others to us
		public function getBooking(){
			$id = $this->input->post('id');
			$condition = array(
				'owner_id' => $id
			);
			$result = $this->db->where($condition)->get('booking')->result();
			echo json_encode($result);
		}
	

	public function getUserById(){
		$id = $this->input->post('id');
		$condition = array(
			'id' => $id
		);
		$result = $this->db->where($condition)->get('user')->result();
		echo json_encode($result);
	}


	public function getItemById(){
		$id = $this->input->post('id');
		$condition = array(
			'id' => $id
		);
		$result = $this->db->where($condition)->get('items')->result();
		echo json_encode($result[0]);
	}




	public function do_upload()
	{
			$config['upload_path']          = './assets/images/items/';
			$config['allowed_types']        = 'gif|jpg|png';
			$config['max_size']             = 500;
			$config['max_width']            = 5000;
			$config['max_height']           = 5000;

			$this->load->library('upload', $config);

			if ( ! $this->upload->do_upload('userfile'))
			{
					$error = array('error' => $this->upload->display_errors());
					echo json_encode($error);
				
			}
			else
			{
					$data = $this->upload->data();
					$path = $data['file_name'];
					echo json_encode($path);
					
			}
	}

	

	public function createRide(){
		//Default ride_status value => created_by_rider
		$data = array(
			'rider_id'  => $this->input->post('rider_id'),
			'rider_name'  => $this->input->post('rider_name'),
			'start_latitude'  => $this->input->post('start_latitude'),
			'end_latitude'  => $this->input->post('end_latitude'),
			'start_longitude'  => $this->input->post('start_longitude'),
			'end_longitude'  => $this->input->post('end_longitude'),
			'start_location'  => $this->input->post('start_location'),
			'end_location'  => $this->input->post('end_location'),
			
				
			'price'  => $this->input->post('price'),
			'ride_date'  => $this->input->post('ride_date')
		);

		//insert data into ride table
		$inserted_id = $this->User->insertData("ride",$data);
				
		//get ride details in response
		$condition =array(
			"id" => $inserted_id
	
		);

		$ride_details = $this->db->where($condition)->get('ride')->result();

		//final response
		if($inserted_id > 0 ){
			echo json_encode($ride_details[0]);

		}else{
			header('HTTP/1.1 401 Unauthorized', true, 401);
		}

	}

 	public function distance() {

		$lat1 = $this->input->post('lat1');
		$lon1 = $this->input->post('lon1');
		$lat2 = $this->input->post('lat2');
		$lon2 = $this->input->post('lon2');
		$unit = $this->input->post('unit');
		


		$theta = $lon1 - $lon2;
		$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
		$dist = acos($dist);
		$dist = rad2deg($dist);
		$miles = $dist * 60 * 1.1515;
		$unit = strtoupper($unit);
		//round(520.34345, 2)
		echo round($miles * 1.609344, 2);
		//echo json_encode(round($miles * 1.609344, 2));
		//echo json_encode($miles * 1.609344);

		// if ($unit == "K") {
		// 	return ($miles * 1.609344);
		// 	echo json_encode($miles * 1.609344);

		// } else if ($unit == "N") {
		// 	return ($miles * 0.8684);
		// } else {
			
		// 	return $miles;
		// }
		
	  }


}
