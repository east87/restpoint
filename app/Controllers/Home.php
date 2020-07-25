<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\PointsModel;
class Points extends ResourceController
{
   
    protected $format       = 'json';
    protected $modelName    = 'App\Models\PointsModel';
    // get all Point
    public function index()
    {
        return $this->respond($this->model->findAll(), 200);
    }

    // get single Point
    public function show($method = null, $id = null)
    {
        $get = $this->model->getPoints($id);
        if($get){
            $code = 200;
            $response = [
                'status' => $code,
                'error' => false,
                'data' => $get,
            ];
        } else {
            $code = 401;
            $msg = ['message' => 'Not Found'];
            $response = [
                'status' => $code,
                'error' => true,
                'data' => $msg,
            ];
        }
        return $this->respond($response, $code);
    }

    // create a Point
    public function create()
    {
        $validation =  \Config\Services::validation();

        $model = new PointsModel();
        $data = [
            'user_name' => $this->request->getVar('user_name'),
            'user_point' => $this->request->getVar('user_point')
        ];
        if($validation->run($data, 'point') == FALSE){
            $response = [
                'status' => 500,
                'error' => true,
                'data' => $validation->getErrors(),
            ];
            return $this->respond($response, 500);
        }else {
            $model->insert($data);
            $response = [
                'status'   => 201,
                'error'    => null,
                'messages' => [
                    'success' => 'Data Saved'
                ]
            ];
             return $this->respond($response, 200);;
        }
    }

    // update Point
    public function update($method = null, $id = null)
    {
        
        $get = $this->model->getPointsBy($id);
       
        $pointnow= $get[0]['user_point'];
      // die;
        $model = new PointsModel();
        $inputpoint  = $this->request->getRawInput('user_point');
        $inputpoint  = $this->request->getRawInput('user_point');
        if($method == 1){
            $user_point= $pointnow + $inputpoint;
        }
        else {
            $user_point= $pointnow - $inputpoint;
        }

        $data = [
            'user_point' => $user_point
        ];
        $model->update($id, $data);

        $response = [
            'status'   => 200,
            'error'    => null,
            'messages' => [
                'success' => 'Data Updated'
            ]
        ];
        return $this->respond($response);
    }

    // delete Point
    public function delete($id = null)
    {
        $model = new PointsModel();
        $data = $model->find($id);
        if($data){
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Data Deleted'
                ]
            ];
            return $this->respondDeleted($response);
        }else{
            return $this->failNotFound('No Data Found with id '.$id);
        }
        
    }

}