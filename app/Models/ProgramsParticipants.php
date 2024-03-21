<?php

namespace App\Models;

use App\Enum\EntityType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ProgramsParticipants extends Model
{
    use HasFactory;
    protected $fillable = [
        'program_id',
        'entity_type',
        'entity_id',
    ];

    /**
     * Define relation model Program
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function program()
    {
        return $this->belongsTo(Programs::class);
    }

    /**
     * Function to validate that the entity is validated with respect to the created enum
     *
     * @param Request $request
     * @return bool
     */
    public static function validateEntities(Request $request): bool {
        try {
            $data = $request->all();
            $class = EntityType::getParent($data['entity_type']);
            $model = $class::find($data['entity_id']);
            if ($model) {
                return true;
            }
        } catch (\Throwable $th) {}

        return false;
    }
}
