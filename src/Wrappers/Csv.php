<?php

namespace App\Wrappers;

use App\Models\User;
use League\Csv\Reader;
use League\Csv\Statement;

class Csv
{
    private const CSV_PATH = __DIR__ . '/../../input.csv';

    /**
     * Find a user in the CSV by their email address.
     * @param string $email
     * @return ?User
     */
    public static function findUserByEmail(string $email): ?User
    {
        $csv = Reader::from(self::CSV_PATH)
            ->setHeaderOffset(0)
            ->setEscape('');

        $csvColumns = Env::csv_columns();
        $emailColumn = $csvColumns['email'];

        $stmt = (new Statement())
            ->where(fn(array $record) => trim($record[$emailColumn] ?? '') === trim($email))
            ->limit(1);

        $result = $stmt->process($csv);
        $record = $result->nth(0);

        if (empty($record)) {
            return null;
        }

        return new User(
            firstName: self::__normalize($record[$csvColumns['firstName']]),
            lastName: self::__normalize($record[$csvColumns['lastName']]),
            email: $record[$emailColumn],
        );
    }

    private static function __normalize(string $str): string
    {
        return ucwords(mb_strtolower($str));
    }
}
