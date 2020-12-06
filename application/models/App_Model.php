<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App_Model extends CI_Model {  


public function getAllRides()
{
    $data = $this->db->get('ride')->result();
    return $data;
}

public function getRidesByUser()
{
    $data = $this->db->get('ride')->result();
    return $data;
}

}