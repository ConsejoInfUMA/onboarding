<?php

namespace App\Wrappers;

use App\Models\User;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Input
{
    private const string INPUT_TYPE = IOFactory::READER_XLSX;
    private const string INPUT_PATH = __DIR__ . '/../../input.xlsx';

    /**
     * Find a user in the CSV by their email address.
     * @param string $email
     * @return ?User
     */
    public static function findUserByEmail(string $email): ?User
    {
        $input = Env::input();

        $reader = IOFactory::createReader(self::INPUT_TYPE);
        $spreadsheet = $reader->load(self::INPUT_PATH);
        $sheet = $spreadsheet->getSheet(0);

        $rowGenerator = $sheet->rangeToArrayYieldRows(
            range: $input['start'] . ':' . $sheet->getHighestDataColumn() . $sheet->getHighestDataRow(),
            calculateFormulas: false,
            oldCalculatedValue: true,
        );

        $firstNameIndex = $input['firstName'];
        $lastNameIndex = $input['lastName'];
        $emailIndex = $input['email'];

        $user = null;
        foreach ($rowGenerator as $row) {
            if ($row[$emailIndex] === $email) {
                $user = new User(
                    firstName: self::__normalize($row[$firstNameIndex]),
                    lastName: self::__normalize($row[$lastNameIndex]),
                    email: $row[$emailIndex],
                );
                break;
            }
        }

        return $user;
    }

    private static function __normalize(string $str): string
    {
        return ucwords(mb_strtolower($str));
    }
}
