
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campus extends Model
{
    protected $table = 'campuses';

    protected $primaryKey = 'campus_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'campus_id',
        'campus_name',
        'institution_name',
        'address',
    ];

    public function laboratories(): HasMany
    {
        return $this->hasMany(Laboratory::class, 'campus_id', 'campus_id');
    }
}
