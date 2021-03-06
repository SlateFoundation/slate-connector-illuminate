<?php

namespace Slate\Connectors\Illuminate;

use ActiveRecord;
use Slate\People\Student;
use SpreadsheetWriter;

class Connector extends \Emergence\Connectors\AbstractConnector
{
    public static $exportKey;

    public static function handleRequest($action = null)
    {
        switch ($action ?: $action = static::shiftPath()) {
            case 'students.csv':
            case 'students':
                return static::handleStudentsRequest();
            default:
                return parent::handleRequest($action);
        }
    }

    public static function handleConnectorRequest(array $responseData = [])
    {
        $GLOBALS['Session']->requireAccountLevel('Administrator');

        $responseData['exportKey'] = static::$exportKey;

        return parent::handleConnectorRequest($responseData);
    }

    public static function handleStudentsRequest()
    {
        if (!static::$exportKey) {
            throw new \Exception(__CLASS__.'::$exportKey not initialized');
        }

        if ($_GET['export_key'] != static::$exportKey) {
            return static::throwAPIUnauthorizedError('export_key missing or incorrect');
        }

        // init spreadsheet writer
        $spreadsheet = new SpreadsheetWriter();

        // write header
        $spreadsheet->writeRow(['Year', 'Last Name', 'First Name', 'Student Number', 'Username', 'Email']);

        // retrieve results
        $students = Student::getAllByClass();

        // output results
        foreach ($students AS $Student) {
            // write row
            $spreadsheet->writeRow([
                $Student->GraduationYear,
                $Student->LastName,
                $Student->FirstName,
                $Student->StudentNumber,
                $Student->Username,
                $Student->PrimaryEmail->Data
            ]);
        }

        $spreadsheet->close();
    }
}
