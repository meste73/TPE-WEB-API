<?php

    class JobsModel extends MainModel{

        function getCount(){
            $query = $this->db->prepare('SELECT COUNT(id) FROM works');
            $query->execute();
            return $query->fetch(PDO::FETCH_NUM);
        }

        function get($sortField, $sortOrder){
            $query = $this->db->prepare('SELECT works.id, works.work_name, works.work_description, works.client_name, works.work_id, works.work_status, garage.area, garage.manager FROM works JOIN garage ON works.fk_id = garage.id ORDER BY '.$sortField.' '.$sortOrder);
            $query->execute([]);
            return $query->fetchAll(PDO::FETCH_OBJ);
        }

        function getSectorJobs($fk_id, $sortField, $sortOrder){
            $query = $this->db->prepare('SELECT works.id, works.work_name, works.work_description, works.client_name, works.work_id, works.work_status, garage.area, garage.manager FROM works JOIN garage ON works.fk_id = garage.id WHERE fk_id = ? ORDER BY works.'.$sortField.' '.$sortOrder);
            $query->execute([$fk_id]);
            return $query->fetchAll(PDO::FETCH_OBJ);
        }

        function getJobByJobId($jobId){
            $query = $this->db->prepare('SELECT id, work_name, work_description, client_name, work_id, work_status, fk_id  FROM works WHERE work_id = ?');
            $query->execute([$jobId]);
            return $query->fetch(PDO::FETCH_OBJ);
        }

        function getJob($id){
            $query = $this->db->prepare('SELECT works.work_name, works.work_description, works.client_name, works.work_id, works.work_status, garage.area, garage.manager FROM works JOIN garage ON works.fk_id = garage.id WHERE works.id = ?');
            $query->execute([$id]);
            return $query->fetch(PDO::FETCH_OBJ);
        }

        function add($workName, $workDescription, $clientName, $workId, $workStatus, $area){
            $query = $this->db->prepare('INSERT INTO works ( work_name, work_description, client_name, work_id, work_status, fk_id ) VALUES ( ?, ?, ?, ?, ?, ?)');
            $query->execute([$workName, $workDescription, $clientName, $workId, $workStatus, $area]);
            return $this->db->lastInsertId();
        }

        function delete($id){
            $query = $this->db->prepare('DELETE FROM works WHERE id = ?');
            $query->execute([$id]);
        }

        function update($id, $workName, $workDescription, $clientName, $workStatus, $area){
            $query = $this->db->prepare('UPDATE works SET work_name = ? , work_description = ? , client_name = ? , work_status = ? , fk_id = ? WHERE id = ?');
            $query->execute([$workName, $workDescription, $clientName, $workStatus, $area, $id]);
        }

    }