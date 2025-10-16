<?php

declare(strict_types=1);

namespace App;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @method static static|null find(int $id)
 * @method static static|null first()
 * @method static LengthAwarePaginator paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null, $total = null)
 */
abstract class Model extends BaseModel
{
    public const string TABLE = '';

    public const string ATTRIBUTE_ID = 'id';
    public const string ATTRIBUTE_CREATED_AT = 'created_at';
    public const string ATTRIBUTE_UPDATED_AT = 'updated_at';

    protected $primaryKey = self::ATTRIBUTE_ID;

    public function getTable(): string
    {
        return static::TABLE ?: parent::getTable();
    }

    public function getCreatedAtColumn(): string
    {
        return self::ATTRIBUTE_CREATED_AT;
    }

    public function getUpdatedAtColumn(): string
    {
        return self::ATTRIBUTE_UPDATED_AT;
    }
}
