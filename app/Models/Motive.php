<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Motive
 *
 * Representa los motivos por los cuales se realiza una denuncia.
 *
 * @property int $motive_id
 * @property string $motive
 *
 * @method \Illuminate\Database\Eloquent\Collection complaints()
 */
class Motive extends Model
{
    protected $table = 'motives';
    protected $primaryKey = 'motive_id';

    protected $fillable = ['motive'];

    /**
     * Relación: Un motivo puede asociarse a muchas denuncias.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'motive_id', 'motive_id');
    }
}
