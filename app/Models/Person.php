<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Carbon\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string $last_name
 * @property Carbon $birth_date
 */
class Person extends Model
{
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birth_date' => 'datetime:Y-m-d',
        ];
    }
}
