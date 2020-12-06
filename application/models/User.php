<?php

class User extends CI_Model {



public function loginUser($phone,$password){
    $condition =array(
        "phone" => $phone,
        "password" => $password

    );
    $usercount = $this->db->where($condition)->get('user')->result();

    return $usercount;

}

public function loginRider($phone,$password){
    $condition =array(
        "phone" => $phone,
        "password" => $password

    );
    $usercount = $this->db->where($condition)->get('rider')->result();

    return $usercount;

}


public function checkEmail($email,$tblName){
    $condition =array(
         "email" => $email
         );

    $usercount = $this->db->where($condition)->get($tblName)->num_rows();
    return $usercount;

}

public function checkPhoneNumber($phone,$tblName){
    $condition =array(
         "phone" => $phone
         );

    $usercount = $this->db->where($condition)->get($tblName)->num_rows();
    return $usercount;

}

public function getCount($tblName){
   $count = $this->db->get($tblName)->num_rows();
  return $count;

}

public function getAll($tblName){  
     $result = $this->db->get($tblName)->result();
       return $result;

}

public function getDataById($tblName,$id){  
    $condition = array(
        'id' => $id
    );
    $result = $this->db->where($condition)->get($tblName)->result();
      return $result;

}

public function insertData($tblName,$data){
   $data = $this->db->insert($tblName,$data);
   $rows = $this->db->insert_id();

    return $rows;
}


public function insert_img($data_insert){
    $data =  $this->db->insert('items',$data_insert);

    return $data;
    }


}