<?php

interface IBaseRepository {
    public function GetAll() : array;
    public function GetById(int $id);
    public function Add($entity) : void;
    public function Update($entity) : void;
    public function Delete(int $id) : void;
}