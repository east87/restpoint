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
    public function show($id = NULL)
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
        $options = [
            'max-age'  => 300,
            's-maxage' => 900,
            'etag'     => 'abcde'
        ];
        $this->response->setCache($options);
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
                    'success' => $this->request->getVar('user_name').' Saved'
                ]
            ];
             return $this->respond($response, 200);;
        }
    }

    // update Point
    public function update($id = null, $met = null )
    {
        $get = $this->model->getPointsBy($id);
       
        $pointnow= $get[0]['user_point'];
       
        $model = new PointsModel();
        $input = $this->request->getRawInput();
        $options = [
            'max-age'  => 300,
            's-maxage' => 900,
            'etag'     => 'abcde'
        ];
        $this->response->setCache($options);
        if($met =='plus' ||  $met =='min' || $met !=''){

                if($met=='plus'){
                    $pointnew=$pointnow + $input['user_point'];
                    $notif= 'bertambah';
                } 
                else if($met=='min'){
                    $pointnew=$pointnow - $input['user_point'];  
                    $notif= 'berkurang';
                    }

                    $data = [
                        'user_point' => $pointnew
                    ];
                    $model->update($id, $data);
                    $response = [
                        'status'   => 200,
                        'error'    => null,
                        'messages' => [
                            'success' => 'point '.$notif.' '.$input['user_point'].' menjadi '.$pointnew
                        ]
                    ];
                 
                    return $this->respond($response);

        } else {
            $response = [
                'status' => 500,
                'error' => true,
                'data' => "please choose 'plus' for add point or 'min'  for reduce"
            ];
            return $this->respond($response, 500);


        }


       
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