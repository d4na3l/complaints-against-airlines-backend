<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ComplaintStatus
 *
 * Define los estados de una denuncia.
 * Valores típicos: procesada, desestimada, en espera.
 *
 * @property int $complaint_status_id
 * @property string $status_name
 *
 * @method \Illuminate\Database\Eloquent\Collection complaints()
 */
class ComplaintStatus extends Model
{
    protected $table = 'complaint_status';
    protected $primaryKey = 'complaint_status_id';

    protected $fillable = ['status_name'];

    /**
     * Relación: Un estado de denuncia puede aplicarse a muchas denuncias.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'complaint_status_id', 'complaint_status_id');
    }
}
