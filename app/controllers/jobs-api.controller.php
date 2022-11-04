<?php

    require_once './app/models/main.model.php';
    require_once './app/models/jobs.model.php';
    require_once './app/models/sectors.model.php';
    require_once './app/views/api.view.php';

    class JobsApiController{
        
        private $jobsModel;
        private $sectorsModel;
        private $apiView;
        private $data;
        private $sortfield;
        private $sortOrder;
        private $from;
        private $quantity;

        function __construct(){
            $this->jobsModel = new JobsModel();
            $this->sectorsModel = new SectorsModel();
            $this->apiView = new ApiView();
            $this->data = file_get_contents("php://input");
            $this->fields = ['id', 'work_name', 'work_description', 'client_name', 'work_id', 'work_status', 'area', 'manager'];
            $this->sortfield = 'id';
            $this->sortOrder = "ASC";
            $this->from = 0;
            $this->quantity = $this->jobsModel->getCount()[0];
        }

        // API/JOBS GET
        function get($params = null){
            
            $this->getSort();
            $this->getSortOrder();
            $this->getOffsetLimit();

            $jobs = $this->jobsModel->get($this->sortfield, $this->sortOrder);

            if($jobs)
                $this->apiView->response(array_slice($jobs, $this->from, $this->quantity));
            else
                $this->apiView->response("Objeto no encontrado.", 404);
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

        // API/JOBS/SECTORS/:ID GET
        function getSectorJobs($params = null){

            $this->getSort();
            $this->getSortOrder();
            $this->getOffsetLimit();

            $id = $params[':ID'];
            $jobs = $this->jobsModel->getSectorJobs($id, $this->sortfield, $this->sortOrder);
            if($jobs)
                $this->apiView->response(array_slice($jobs, $this->from, $this->quantity));
            else
                $this->apiView->response("Objeto no encontrado.", 404);
        }

        // API/JOBS/:ID DELETE
        function delete($params = null){
            $id = $params[':ID'];
            $job = $this->jobsModel->getJob($id);

            if($job){
                $this->jobsModel->delete($id);
                $this->apiView->response($job);
            } else{
                $this->apiView->response("Objeto no encontrado.", 404);
            }
        }

        // API/JOBS POST
        function insert($params = null){
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
        }

        // API/JOBS/:ID PUT
        function modify($params = null){
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
        }

        //Obtiene valores de inputs
        private function getData() {
            return json_decode($this->data);
        }

        //Obtiene valor GET de sort
        private function getSort(){
            if(isset($_GET['sort']) && $this->checkField($_GET['sort']))
                $this->sortfield = $_GET['sort'];
        }

        //Obtiene valor GET de order
        private function getSortOrder(){
            if(isset($_GET['order']) && $this->checkOrder($_GET['order']))
                $this->sortOrder = $_GET['order'];
        }

        //Obtiene valores GET de offset y limit ARREGLAR ESTO
        private function getOffsetLimit(){
            if($this->checkOffset()&&$this->checkLimit()){
                $this->from = $_GET['offset'];
                $this->quantity = $_GET['limit'];
            } else {
                $this->apiView->response("Valores de paginados incorrectos", 400);
                die();
            }
        }

        //Chequea que el valor GET offset sea un numero.
        private function checkOffset(){
            if(isset($_GET['offset'])&&(is_numeric($_GET['offset'])))
                return true;
            return false;
        }

        //Chequea que el valor GET limit sea un numero.
        private function checkLimit(){
            if(isset($_GET['limit'])&&(is_numeric($_GET['limit'])))
                return true;
            return false;
        }
        
        //Chequea que el valor GET sort sea valido, sino envia un mensaje con codigo 400 bad request.
        private function checkField($fieldToCheck){
            $exists = false;
            foreach($this->fields as $field){
                if($field == $fieldToCheck){
                    $exists = true;
                }
            }
            if(!$exists){
                $this->apiView->response("El campo ingresado no es valido", 400);
                die();
            }
            return $exists;
        }

        //Chequea que el valor GET order sea valido, sino envia un mensaje con codigo 400 bad request.
        private function checkOrder($order){
            if(($order == 'ASC')||($order == 'DESC'))
                return true;
            $this->apiView->response("El campo ingresado no es valido", 400);
            die();
        }

    }