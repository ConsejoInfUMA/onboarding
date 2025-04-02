<?php

namespace App\Wrappers;

use App\Models\User;
use League\Csv\Reader;

class Importer
{
    /**
     * Get all users from CSV
     *
     * @param resource $resource
     * @return User[]
     */
    public static function users($resource): array
    {
        $users = [];

        $csv = Reader::createFromStream($resource)
            ->setHeaderOffset(0)
            ->setEscape('');

        foreach ($csv as $record) {
            // Evitar entradas vacías
            if ($record['Apellido(s)'] !== '') {
                $users[] = new User(
                    firstName: ucwords(mb_strtolower($record['Nombre'])),
                    lastName: ucwords(mb_strtolower($record['Apellido(s)'])),
                    email: $record['Correo principal de contacto'],
                );
            }
        }

        // Eliminar usuarios duplicados
        $usersFiltered = array_unique($users);
        return $usersFiltered;
    }
}
