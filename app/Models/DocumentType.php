<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class DocumentType
 *
 * Define los tipos de documento que pueden tener los usuarios.
 * Ejemplos: cédula identidad venezolano (civ), cédula identidad extranjero (cie), pasaporte (pas).
 *
 * @property int $document_type_id
 * @property string $document_type_name
 * @property string $keyword
 *
 * @method \Illuminate\Database\Eloquent\Collection users()
 */
class DocumentType extends Model
{
    protected $table = 'document_types';
    protected $primaryKey = 'document_type_id';

    protected $fillable = ['document_type_name', 'keyword'];

    /**
     * Relación: Un tipo de documento puede pertenecer a muchos usuarios.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class, 'document_type_id', 'document_type_id');
    }
}
