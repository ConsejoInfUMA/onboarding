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

        $csvColumns = Env::csv_columns();

        foreach ($csv as $record) {
            // Evitar entradas vacías
            if ($record[$csvColumns['lastName']] !== '') {
                $users[] = new User(
                    firstName: ucwords(mb_strtolower($record[$csvColumns['firstName']])),
                    lastName: ucwords(mb_strtolower($record[$csvColumns['lastName']])),
                    email: $record[$csvColumns['email']],
                );
            }
        }

        // Eliminar usuarios duplicados
        $usersFiltered = array_unique($users);
        return $usersFiltered;
    }
}
