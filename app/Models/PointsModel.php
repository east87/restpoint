<?php namespace App\Models;

use CodeIgniter\Model;

class PointsModel extends Model
{
    protected $table = 'tbl_user';
    protected $primaryKey = 'user_id';
    protected $allowedFields = ['user_name','user_point'];


    public function getPoints($id = false)
    {
        if($id === false){
            return $this->findAll();
        } else {
            return $this->getWhere(['user_id' => $id])->getRowArray();
        }  
    }
    public function getPointsBy($id = false)
    {
        $db = \Config\Database::connect();
        $query = $db->query("select user_point from tbl_user where user_id=$id");
        return $query->getResult('array');
    }
}
