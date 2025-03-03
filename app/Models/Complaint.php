<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Complaint
 *
 * Representa una denuncia realizada por un usuario.
 *
 * @property int $complaint_id
 * @property string $registration_date
 * @property string $incident_date
 * @property string $description
 * @property int $motive_id
 * @property int $complaint_status_id
 * @property int $user_id
 * @property int $ticket_id
 * @property string|null $processing_notes
 * @property int|null $processed_by
 *
 * @method \Illuminate\Database\Eloquent\Collection files()
 */
class Complaint extends Model
{
    protected $table = 'complaints';
    protected $primaryKey = 'complaint_id';

    protected $fillable = [
        'registration_date',
        'incident_date',
        'description',
        'motive_id',
        'complaint_status_id',
        'user_id',
        'ticket_id',
        'processing_notes',
        'processed_by'
    ];

    /**
     * Relación: El motivo por el cual se realizó la denuncia.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function motive()
    {
        return $this->belongsTo(Motive::class, 'motive_id', 'motive_id');
    }

    /**
     * Relación: El estado actual de la denuncia.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function complaintStatus()
    {
        return $this->belongsTo(ComplaintStatus::class, 'complaint_status_id', 'complaint_status_id');
    }

    /**
     * Relación: El usuario que registró la denuncia.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Relación: El ticket (vuelo) asociado a la denuncia.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'ticket_id');
    }

    /**
     * Relación: El usuario (funcionario o administrador) que procesó la denuncia.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by', 'user_id');
    }

    /**
     * Relación: Archivos asociados a la denuncia.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany(File::class, 'complaint_id', 'complaint_id');
    }
}
