<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Asegúrate de agregar esta línea

/**
 * Class User
 *
 * Representa a un usuario del sistema.
 * Los usuarios pueden tener diferentes roles y relaciones con otras entidades, como tickets y denuncias.
 *
 * @property int $user_id
 * @property string $first_name
 * @property string $last_name
 * @property string $birthdate
 * @property string $password
 * @property string $document
 * @property int $document_type_id
 * @property string $email
 * @property string|null $phone
 * @property string|null $local_phone
 * @property string|null $profession
 * @property int $role_id
 * @property int $nationality_id
 * @property int $country_origin_id
 * @property string|null $domicile_address
 * @property string|null $additional_address
 *
 * @method \Illuminate\Database\Eloquent\Collection tickets()
 * @method \Illuminate\Database\Eloquent\Collection complaints()
 * @method \Illuminate\Database\Eloquent\Collection processedComplaints()
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'first_name',
        'last_name',
        'birthdate',
        'password',
        'document',
        'document_type_id',
        'email',
        'phone',
        'local_phone',
        'profession',
        'role_id',
        'nationality_id',
        'country_origin_id',
        'domicile_address',
        'additional_address'
    ];

    /**
     * Relación: Los tickets que pertenecen a este usuario.
     * Un usuario puede tener muchos tickets.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'user_id', 'user_id');
    }

    /**
     * Relación: El rol al que pertenece el usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    /**
     * Relación: El tipo de documento del usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function documentType()
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id', 'document_type_id');
    }

    /**
     * Relación: La nacionalidad del usuario (país).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nationality()
    {
        return $this->belongsTo(Country::class, 'nationality_id', 'country_id');
    }

    /**
     * Relación: El país de procedencia del usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function countryOrigin()
    {
        return $this->belongsTo(Country::class, 'country_origin_id', 'country_id');
    }

    /**
     * Relación: Denuncias registradas por el usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'user_id', 'user_id');
    }

    /**
     * Relación: Denuncias procesadas por el usuario (si actúa como funcionario o administrador).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function processedComplaints()
    {
        return $this->hasMany(Complaint::class, 'processed_by', 'user_id');
    }
}
