<?php

namespace Myw\ModelRepository\Http\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class BaseRepository
{
    const OPERATOR_AND = 'AND';
    const OPERATOR_OR = 'OR';
    const OPERATOR_LIKE = 'LIKE';

    const OPERATOR_WHERE = 'where';
    const OPERATOR_OR_WHERE = 'orWhere';

    public $model;

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Store item
     *
     * @param array $parameter
     * @return mixed
     */
    public function store(array $parameter)
    {
        try {
            $model = $this->model;

            return $model->create($parameter);

        } catch(\Exception $e) {

            Log::error(__('messages.errors.store', ['description' => json_encode( $e->getMessage() ) ]));

            return false;
        }
    }

    /**
     * Insert items in bulk
     *
     * @param $items
     * @return mixed
     */
    public function bulkInsert($items)
    {
        try {

            return $this->model->insert($items);

        } catch (\Exception $e) {

            Log::error(__('messages.errors.bulk-store', [ 'description' => json_encode($e->getMessage()) ]));

            return false;

        }
    }

    /**
     * Update an item
     *
     * @param $id
     * @param $parameters
     * @return mixed
     */
    public function update($id, $parameters)
    {
        try {

            $item = $this->find($id);

            return $item->update($parameters);

        } catch (\Exception $e) {

            Log::error(__('messages.errors.update', [ 'description' => json_encode($e->getMessage()) ]));

            return false;
        }
    }

    /**
     * Delete an item
     *
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        try {

            $item = $this->find($id);

            return $item->destroy();

        } catch (\Exception $e) {

            Log::error(__('messages.errors.destroy', [ 'description' => json_encode($e->getMessage()) ]));

            return false;
        }
    }

    /**
     * Return all items
     *
     * @param array $select
     * @param bool $array
     * @return mixed
     */
    public function all($select = [], $array = false)
    {
        $model = $this->model;

        if(!empty($select)) {
            $model->select($select);
        }

        return $array ?
            $model->get()->toArray()
            : $model->get();
    }

    /**
     * @param array $filter
     * @param array $data
     * @return mixed
     */
    public function updateOrCreate(array $filter, array $data)
    {
        return $this->model->updateOrCreate(
            $filter,
            $data
        );
    }


    /**
     * @param array $relationship
     * @return mixed
     */
    public function withRelation($relationship = [])
    {
        return $this->model->with($relationship);
    }

    /**
     * @param $parameter
     * @param array $values
     * @return mixed
     */
    public function whereIn($parameter, array $values)
    {
        return $this->model->whereIn($parameter, $values)
            ->get();
    }

    /**
     * Find item
     *
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Queries model.
     *
     * $where = [
     *   'field_name' => [
     *      'value' => value
     *      'operator' => operator
     *      'where_type' => OperatorConstants::OPERATOR_WHERE or OperatorConstants::OPERATOR_OR_WHERE
     *   ],
     *   ... can add more ...
     * ]
     *
     * Notes:
     *   if 'operator' is LIKE, the value that would be passed
     *   should have '%' depending on what the application needs.
     *
     * Additional info:
     *   operator : optional
     *   where_type : optional
     *
     * @param array $where
     * @return Model model
     */
    public function findBy(array $where)
    {
        $model = $this->model;

        $model = $model->where(function ($query) use ($where)
        {
            foreach($where as $field => $value)
            {
                // get where type : orWhere() or where()
                $where_type = isset($value['where_type']) ? $value['where_type'] : self::OPERATOR_WHERE;

                // get the operator type
                $operator = isset($value['operator']) ? $value['operator'] : self::OPERATOR_AND;

                // get the value
                $val = isset($value['value']) ? $value['value'] : $value;

                // build query
                switch($operator)
                {
                    case self::OPERATOR_AND:
                    case self::OPERATOR_OR:
                        $query->$where_type($field, $val);
                        break;

                    case self::OPERATOR_LIKE:
                        $query->$where_type($field, 'LIKE', $val);
                        break;
                }
            }
        });

        return $model;
    }

}
