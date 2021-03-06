<?php
/**
 * Created by PhpStorm.
 * User: Dave
 * Date: 8/19/2018
 * Time: 10:42 AM
 */

namespace App\Services\Cycle;


use App\Models\Cycle;

class CycleCollection implements CycleCollectionInterface
{
    /**
     * @var array
     */
    public $meta = [];
    /**
     * @var Cycle
     */
    public $model;

    /**
     * Pagination per_page.
     *
     * @var integer
     */
    public $per_page = 30;

    /**
     * CycleCollection constructor.
     * @param Cycle $model
     */
    public function __construct(Cycle $model)
    {
        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function get()
    {
        $builder = $this->model->orderBy('name', 'ASC');

        $builder = $this->search($builder);

        $builder = $this->archive($builder);

        $limit = request()->get('per_page', $this->per_page);

        return $builder->paginate($limit);
    }

    /**
     * @param $builder
     * @return mixed
     */
    public function search($builder)
    {
        if (request()->has('q') && request()->get('q')) {
            $keyword = '%' . request()->get('q') . '%';
            $builder = $builder->where('name', 'like', $keyword);
            $this->meta['q'] = request()->get('q');
        }
        return $builder;
    }

    /**
     * @param $builder
     * @return mixed
     */
    public function archive($builder)
    {
        if (request()->has('is_archived')) {
            $builder = $builder->where('is_archived', request()->get('is_archived'));
            $this->meta['is_archived'] = request()->get('is_archived');
        }
        return $builder;
    }
}