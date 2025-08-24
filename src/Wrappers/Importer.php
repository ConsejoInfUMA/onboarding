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
            $firstName = $record[$csvColumns['firstName']];
            $lastName = $record[$csvColumns['lastName']];
            $email = $record[$csvColumns['email']];
            // Evitar entradas vacías
            if ($lastName !== '') {
                $users[] = new User(
                    firstName: self::__normalize($firstName),
                    lastName: self::__normalize($lastName),
                    email: $email,
                );
            }
        }

        // Eliminar usuarios duplicados
        $usersFiltered = array_unique($users);
        return $usersFiltered;
    }

    private static function __normalize(string $str): string
    {
        return ucwords(mb_strtolower($str));
    }
}
