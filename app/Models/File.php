<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class File
 *
 * Representa un archivo probatorio asociado a una denuncia.
 *
 * @property int $file_id
 * @property string $filename
 * @property string $path
 * @property int $size
 * @property string $file_type
 * @property int $complaint_id
 *
 * @method \Illuminate\Database\Eloquent\Relations\BelongsTo complaint()
 */
class File extends Model
{
    protected $table = 'files';
    protected $primaryKey = 'file_id';

    protected $fillable = [
        'filename',
        'path',
        'size',
        'file_type',
        'complaint_id'
    ];

    /**
     * Relación: La denuncia a la que pertenece este archivo.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function complaint()
    {
        return $this->belongsTo(Complaint::class, 'complaint_id', 'complaint_id');
    }
}
