<?php

namespace App\Repositories;

use App\Models\Supply;
use App\Repositories\BaseRepository;

/**
 * Class RegionRepository
 * @package App\Repositories
 * @version August 2, 2021, 4:28 pm UTC
*/

class SupplyRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'quantity',
        'product_id'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Supply::class;
    }
}
