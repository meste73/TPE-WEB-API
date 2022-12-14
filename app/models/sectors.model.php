<?php

    class SectorsModel extends MainModel{

        function get(){
            $query = $this->db->prepare('SELECT * FROM garage');
            $query->execute();
            return $query->fetchAll(PDO::FETCH_OBJ);
        }

        function getSector($id){
            $query = $this->db->prepare('SELECT * FROM garage WHERE id = ?');
            $query->execute([$id]);
            return $query->fetch(PDO::FETCH_OBJ);
        }

        function getSectorByArea($area){
            $query = $this->db->prepare('SELECT * FROM garage WHERE area = ?');
            $query->execute([$area]);
            return $query->fetch(PDO::FETCH_OBJ);
        }

        function add($area, $manager){
            $query = $this->db->prepare('INSERT INTO garage ( area , manager ) VALUES ( ? , ? )');
            $query->execute([$area, $manager]);
        }

        function delete($id){
            $query = $this->db->prepare('DELETE FROM garage WHERE id = ?');
            $query->execute([$id]);
        }

        function update($id, $area, $manager){
            $query = $this->db->prepare('UPDATE garage SET area = ?, manager = ? WHERE id = ?');
            $query->execute([$area, $manager, $id]);
        }

    }