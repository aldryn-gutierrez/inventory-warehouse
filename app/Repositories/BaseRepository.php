<?php 

namespace App\Repositories;

use App\Contracts\Repositories\RepositoryContract;
use App\Exceptions\RepositoryException;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements RepositoryContract 
{
    protected $model;

    public function __construct() {
        $this->makeModel();
    }

    /**
     * Specify Model name
     * 
     * @return string
     */
    abstract function getModelName();

    /**
     * @return Model
     * @throws RepositoryException
     */
    public function makeModel() 
    {
        $modelName = $this->getModelName();
        if (!class_exists($modelName)) {
            throw new RepositoryException("$modelName is an invalid class");
        }

        $model = new $modelName;
        if (!$model instanceof Model) {
            throw new RepositoryException(
                "Class {$this->getModelName()} must be an instance of Illuminate\\Database\\Eloquent\\Model"
            );
        }

        return $this->model = $model;
    }

    /**
     * @param  array $columns
     * @return mixed
     */
    public function all(array $columns = ['*']) 
    {
        return $this->model->get($columns);
    }
 
    /**
     * @param  array $data
     * @return mixed
     */
    public function create(array $data) 
    {    
        foreach ($data as $key => $value) {
            $this->model->$key = $value;
        }

        $this->model->save();

        return $this->model;
    }
 
    /**
     * @param  array $data
     * @param  $id
     * @param  string $attribute
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id") 
    {
        $this->model->where($attribute, '=', $id)->update($data);

        return $this->find($id);
    }
 
    /**
     * @param $id
     * @return mixed
     */
    public function delete($id) 
    {
        return $this->model->destroy($id);
    }
 
    /**
     * @param  $id
     * @param  array $columns
     * @return mixed
     */
    public function find($id, array $columns = ['*']) 
    {
        return $this->model->find($id, $columns);
    }
 
    /**
     * @param  string $field
     * @param  mixed  $value
     * @param  array  $columns
     * @return mixed
     */
    public function findByField($field, $value, array $columns = ['*']) 
    {
        if (is_array($value)) {
            return $this->model->whereIn($field, $value)->first($columns);
        } else {
            return $this->model->where($field, $value)->first($columns);
        }
    }

    /**
     * @param  string $field
     * @param  mixed  $value
     * @param  array  $columns
     * @return mixed
     */
    public function findByFields($fieldsAndValues, array $columns = ['*']) 
    {
        $model = $this->model;

        foreach ($fieldsAndValues as $field => $value) {
            if (is_array($value)) {
                $model = $model->whereIn($field, $value);
            } else {
                $model = $model->where($field, $value);
            }
        }

        return $model->first($columns);
    }
 
    /**
     * @param  string $field
     * @param  mixed  $value
     * @param  array  $columns
     * @return mixed
     */
    public function getByField($field, $value, array $columns = ['*']) 
    {
        if (is_array($value)) {
            return $this->model->whereIn($field, $value)->get($columns);
        } else {
            return $this->model->where($field, $value)->get($columns);
        }
    }

    /**
     * @param  string $field
     * @param  mixed  $value
     * @param  array  $columns
     * @return mixed
     */
    public function getByFields($fieldsAndValues, array $columns = ['*']) 
    {
        $model = $this->model;

        foreach ($fieldsAndValues as $field => $value) {
            if (is_array($value)) {
                $model = $model->whereIn($field, $value);
            } else {
                $model = $model->where($field, $value);
            }
        }

        return $model->get($columns);
    }

    public function existsByFields($fieldsAndValues, $id = null)
    {
        $model = $this->model;

        foreach ($fieldsAndValues as $field => $value) {
            if (is_array($value)) {
                $model = $model->whereIn($field, $value);
            } else {
                $model = $model->where($field, $value);
            }
        }

        if (!is_null($id)) {
            $model = $model->where('id', '!=', $id);
        }

        return $model->exists();
    }

    public function countByFields($fieldsAndValues)
    {
        $model = $this->model;

        foreach ($fieldsAndValues as $field => $value) {
            if (is_array($value)) {
                $model = $model->whereIn($field, $value);
            } else {
                $model = $model->where($field, $value);
            }
        }

        return $model->count();
    }
}