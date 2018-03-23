<?php

namespace App\Contracts\Repositories;

interface RepositoryContract 
{ 
    public function all(array $columns = ['*']);
 
    public function create(array $data);
 
    public function update(array $data, $id);
 
    public function delete($id);
 
    public function find($id, array $columns = ['*']);
 
    public function findByField($field, $value, array $columns = ['*']);

    public function findByFields($fieldsAndValues, array $columns = ['*']);
 
    public function getByField($field, $value, array $columns = ['*']);

    public function getByFields($fieldsAndValues, array $columns = ['*']);

    public function existsByFields($fieldsAndValues, $id = null);

    public function countByFields($fieldsAndValues);
}