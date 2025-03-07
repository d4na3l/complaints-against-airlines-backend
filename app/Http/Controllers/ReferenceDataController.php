<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Airline;
use App\Models\Airport;
use App\Models\ComplaintStatus;
use App\Models\Country;
use App\Models\DocumentType;
use App\Models\FlightType;
use App\Models\Motive;
use App\Models\Role;
use Illuminate\Support\Facades\Cache;

class ReferenceDataController extends Controller
{
    /**
     * Tiempo de caché para los datos de referencia (en minutos)
     *
     * @var int
     */
    protected $cacheTime = 60;

    /**
     * Obtener todas las tablas de referencia necesarias
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllReferences()
    {
        return response()->json([
            'airlines' => $this->getAirlines(),
            'airports' => $this->getAirports(),
            'complaintStatus' => $this->getComplaintStatus(),
            'countries' => $this->getCountries(),
            'documentTypes' => $this->getDocumentTypes(),
            'flightTypes' => $this->getFlightTypes(),
            'motives' => $this->getMotives(),
            'roles' => $this->getRoles(),
        ]);
    }

    /**
     * Obtener aerolíneas
     *
     * @return array
     */
    public function getAirlines()
    {
        return Cache::remember('airlines', $this->cacheTime, function () {
            return Airline::select('airline_id', 'airline_name')
                ->orderBy('airline_name')
                ->get();
        });
    }

    /**
     * Obtener aeropuertos
     *
     * @return array
     */
    public function getAirports()
    {
        return Cache::remember('airports', $this->cacheTime, function () {
            return Airport::select('airport_id', 'airport_name')
                ->orderBy('airport_name')
                ->get();
        });
    }

    /**
     * Obtener estados de denuncias
     *
     * @return array
     */
    public function getComplaintStatus()
    {
        return Cache::remember('complaint_status', $this->cacheTime, function () {
            return ComplaintStatus::select('complaint_status_id', 'status_name')
                ->orderBy('complaint_status_id')
                ->get();
        });
    }

    /**
     * Obtener países
     *
     * @return array
     */
    public function getCountries()
    {
        return Cache::remember('countries', $this->cacheTime, function () {
            return Country::select('country_id', 'country_name')
                ->orderBy('country_name')
                ->get();
        });
    }

    /**
     * Obtener tipos de documentos
     *
     * @return array
     */
    public function getDocumentTypes()
    {
        return Cache::remember('document_types', $this->cacheTime, function () {
            return DocumentType::select('document_type_id', 'document_type_name', 'keyword')
                ->orderBy('document_type_name')
                ->get();
        });
    }

    /**
     * Obtener tipos de vuelo
     *
     * @return array
     */
    public function getFlightTypes()
    {
        return Cache::remember('flight_types', $this->cacheTime, function () {
            return FlightType::select('flight_type_id', 'flight_type')
                ->orderBy('flight_type')
                ->get();
        });
    }

    /**
     * Obtener motivos de denuncias
     *
     * @return array
     */
    public function getMotives()
    {
        return Cache::remember('motives', $this->cacheTime, function () {
            return Motive::select('motive_id', 'motive')
                ->orderBy('motive')
                ->get();
        });
    }

    /**
     * Obtener roles
     *
     * @return array
     */
    public function getRoles()
    {
        return Cache::remember('roles', $this->cacheTime, function () {
            return Role::select('role_id', 'role_name')
                ->orderBy('role_name')
                ->get();
        });
    }

    /**
     * Obtener aeropuertos asociados a una aerolínea específica
     *
     * @param int $airlineId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAirportsByAirline($airlineId)
    {
        $cacheKey = "airline_airports_{$airlineId}";

        $airports = Cache::remember($cacheKey, $this->cacheTime, function () use ($airlineId) {
            return Airport::join('airline_airports', 'airports.airport_id', '=', 'airline_airports.airport_id')
                ->where('airline_airports.airline_id', $airlineId)
                ->select('airports.airport_id', 'airports.airport_name')
                ->orderBy('airports.airport_name')
                ->get();
        });

        return response()->json($airports);
    }

    /**
     * Limpiar la caché de datos referenciales
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearReferenceCache(Request $request)
    {
        // Verificar si el usuario tiene permisos (debería ser solo admin)
        if ($request->user()->role->role_name !== 'administrador') {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        // Lista de claves de caché a limpiar
        $cacheKeys = [
            'airlines',
            'airports',
            'complaint_status',
            'countries',
            'document_types',
            'flight_types',
            'motives',
            'roles',
        ];

        // Limpiar caché de aeropuertos por aerolínea
        $airlineIds = Airline::pluck('airline_id')->toArray();
        foreach ($airlineIds as $airlineId) {
            Cache::forget("airline_airports_{$airlineId}");
        }

        // Limpiar el resto de cachés
        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        return response()->json(['message' => 'Caché de datos referenciales limpiada exitosamente']);
    }
}
