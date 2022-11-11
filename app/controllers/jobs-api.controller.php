<?php

    require_once './app/models/main.model.php';
    require_once './app/models/jobs.model.php';
    require_once './app/helpers/api.helper.php';
    require_once './app/models/sectors.model.php';
    require_once './app/views/api.view.php';

    class JobsApiController{
        
        private $jobsModel;
        private $sectorsModel;
        private $apiView;
        private $apiHelper;
        private $data;
        private $sortfield;
        private $sortOrder;
        private $from;
        private $quantity;
        private $field;
        private $fieldValue;

        function __construct(){
            $this->jobsModel = new JobsModel();
            $this->apiView = new ApiView();
            $this->apiHelper = new ApiHelper();
            $this->data = file_get_contents("php://input");
            $this->fields = ['id', 'work_name', 'work_description', 'client_name', 'work_id', 'work_status', 'area', 'manager'];
        }

        // API/JOBS GET
        function get($params = null){
            
            $this->getSort();
            $this->getSortOrder();
            $this->getOffsetLimit();
            $this->checkPaginationValues();
            $this->checkSortValues();
            $this->getFieldValues();

            if($this->field == null){
                $jobs = $this->jobsModel->get($this->sortfield, $this->sortOrder);
                if($jobs)
                    $this->apiView->response(array_slice($jobs, $this->from, $this->quantity));
                else
                    $this->apiView->response("Objeto no encontrado.", 404);
            } else{
                $jobs = $this->jobsModel->getSectorJobs($this->sortfield, $this->sortOrder, $this->field, $this->fieldValue);
                if($jobs)
                    $this->apiView->response(array_slice($jobs, $this->from, $this->quantity));
                else
                    $this->apiView->response("Objeto no encontrado.", 404);
            }
        }

        // API/JOBS/:ID GET
        function getJob($params = null){
            $id = $params[':ID'];
            $job = $this->jobsModel->getJob($id);
            
            if($job)
                $this->apiView->response($job);
            else
                $this->apiView->response("Objeto no encontrado.", 404);
        }

        // API/JOBS/:ID DELETE
        function delete($params = null){

            if($this->apiHelper->isLoggedIn()){

                $id = $params[':ID'];
                $job = $this->jobsModel->getJob($id);

                if($job){
                    $this->jobsModel->delete($id);
                    $this->apiView->response("Eliminacion exitosa, id: ".$id);
                } else{
                    $this->apiView->response("Objeto no encontrado.", 404);
                }
            } else {
                $this->apiView->response('No autorizado', 401);
            }
        }

        // API/JOBS POST
        function insert($params = null){

            if($this->apiHelper->isLoggedIn()){

                $data = $this->getData();
                if(empty($data->name)||empty($data->description)||empty($data->client_name)||empty($data->job_id)||empty($data->status)||empty($data->fk_id)){
                    $this->apiView->response("Ingrese los campos", 400);
                } else{
                    $job = $this->jobsModel->getJobByJobId($data->job_id);
                    $sector = $this->sectorsModel->getSector($data->fk_id);
                    if($job)
                        $this->apiView->response("El codigo de trabajo ingresado ya existe.", 400);
                    else if(!$sector)
                        $this->apiView->response("El sector de trabajo introducido no existe", 400);
                    else{
                        $id = $this->jobsModel->add($data->name, $data->description, $data->client_name, $data->job_id, $data->status, $data->fk_id);
                        $job = $this->jobsModel->getJob($id);
                        $this->apiView->response($job, 201);
                    }
                }
            } else {
                $this->apiView->response('No autorizado', 401);
            }
        }

        // API/JOBS/:ID PUT
        function modify($params = null){

            if($this->apiHelper->isLoggedIn()){
            
                $id = $params[':ID'];
                $job = $this->jobsModel->getJob($id);

                if($job){
                    $data = $this->getData();
                    if(empty($data->name)||empty($data->description)||empty($data->client_name)||empty($data->status)||empty($data->fk_id)){
                        $this->apiView->response("Ingrese los campos", 400);
                    } else{
                        $sector = $this->sectorsModel->getSector($data->fk_id);
                        if (!$sector)
                            $this->apiView->response("El sector de trabajo introducido no existe", 400);
                        else{
                            $this->jobsModel->update($id, $data->name, $data->description, $data->client_name, $data->status, $data->fk_id);
                            $job = $this->jobsModel->getJob($id);
                            $this->apiView->response($job, 201);
                        }
                    }
                } else {
                    $this->apiView->response("Objeto no encontrado.", 404);
                }
            } else {
                $this->apiView->response('No autorizado', 401);
            }
        }

        //Obtiene valores de inputs
        private function getData() {
            return json_decode($this->data);
        }

        //Obtiene valor GET de sort
        private function getSort(){
            if(isset($_GET['sort']))
                $this->sortfield = $_GET['sort'];
            else
                $this->sortfield = 'id';
        }

        //Obtiene valor GET de order
        private function getSortOrder(){
            if(isset($_GET['order']))
                $this->sortOrder = $_GET['order'];
            else
                $this->sortOrder = "ASC";
            }

        //Chequea que el valor GET 
        private function checkSortValues(){
            if(!($this->checkField()&&$this->checkOrder())){
                $this->apiView->response("Los campos de busqueda no son validos.", 400);
                die();
            }
        }

        //Chequea que el valor GET sort sea valido, sino envia un mensaje con codigo 400 bad request.
        private function checkField(){
            foreach($this->fields as $field){
                if($field == $this->sortfield){
                    return true;
                }
            }
            return false;
        }

        //Chequea que el valor GET order sea valido, sino envia un mensaje con codigo 400 bad request.
        private function checkOrder(){
            if(($this->sortOrder == 'ASC')||($this->sortOrder == 'DESC'))
                return true;
            else
                return false;
        }

        //Obtiene valores GET de offset y limit ARREGLAR ESTO
        private function getOffsetLimit(){
            if(isset($_GET['offset'])&&isset($_GET['limit'])){
                $this->from = $_GET['offset'];
                $this->quantity = $_GET['limit'];
            } else {
                $this->from = 0;
                $this->quantity = $this->jobsModel->getCount()[0];
            }
        }

        //Chequea que los valores de paginacion sean correctos.
        private function checkPaginationValues(){
            if(!($this->checkOffset($this->from)&&$this->checkLimit($this->quantity))){
                $this->apiView->response("Los campos de paginacion no son validos.", 400);
                die();
            }
        }

        //Chequea que el valor GET offset sea un numero.
        private function checkOffset($offset){
            if(is_numeric($offset) && $offset >= 0)
                return true;
            else
                return false;
        }

        //Chequea que el valor GET limit sea un numero.
        private function checkLimit($limit){
            if(is_numeric($limit) && $limit >= 0)
                return true;
            else
                return false;
        }

        //Obtiene campo y valor para filtrado.
        private function getFieldValues(){
            foreach($this->fields as $field){
                if(isset($_GET[$field])){
                    $this->field = $field;
                    $this->fieldValue = $_GET[$field];
                }
            }
        }
    }