<?php
if (PHP_SAPI !== 'cli' || php_sapi_name() !== 'cli') {
    die("CLI mode only");
}
require_once (__DIR__ . "/classes/CalendarMonthsTotals.php");

parse_str(implode('&', array_slice($argv, 1)), $_GET);
$action = (int) $_GET['action'] ?? 0;
$extraParams = $_GET['extraParams'] ?? [];

$main = Main::get();

$outputResult = null;

$main->unitTesting();

echo "\n" . str_pad('', 30, "-") . PHP_EOL;

exit();

class Main
{

    const DATE_FORMAT = "d/m/Y";

    private static $i = null;

    public function __construct()
    {}

    public static function get(): Main
    {
        if (self::$i === null) {
            self::$i = new static();
        }
        return self::$i;
    }

    public function run(array $params): array
    {
        if (empty($params)) {
            throw new Exception("Parameters empty");
        }
        if (! isset($params[0]["date"])) {
            throw new Exception("Parameters format invalid");
        }

        $firstDate = date_create_from_format(self::DATE_FORMAT, $params[0]["date"]);
        $currentMonth = (int) $firstDate->format('m');

        if (\count($params) !== cal_days_in_month(CAL_GREGORIAN, $currentMonth, (int) $firstDate->format('Y'))) {
            throw new Exception("Not enough entries for the month of " . $firstDate->format('F'));
        }

        $totals = new CalendarMonthsTotals();

        foreach ($params as $parameter) {
            $date = date_create_from_format(self::DATE_FORMAT, $parameter["date"]);

            if ($currentMonth !== (int) $date->format('m')) {
                throw new Exception("Date " . $date->format(self::DATE_FORMAT) . " isn't part of the month of " . $firstDate->format('F'));
            }

            if ((int) $parameter["duration"] > 8) {
                throw new Exception("Duration cannot be higher than 8 ");
            }

            $totals->addDay($date, (int) $parameter["duration"]);
        }

        // $totals->displayResults();

        return $totals->getTotals();
    }

    public function unitTesting(): void
    {
        $UNIT_TESTS = [
            'test classic month' => [
                'expectedResult' => [
                    "nbOpenDays" => 18,
                    "nbOpenHours" => 144,
                    "nbSundays" => 0,
                    "nbSundayHours" => 0,
                    "nbBankHolidays" => 0,
                    "nbBankHolidayHours" => 0,
                    "nbAbsence" => 13
                ],
                'params' => [
                    [
                        "date" => "01/05/2024",
                        "duration" => 0
                    ],
                    [
                        "date" => "02/05/2024",
                        "duration" => 0 // Pas de travail le jour de mon anniversaire :D
                    ],
                    [
                        "date" => "03/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "04/05/2024",
                        "duration" => 0
                    ],
                    [
                        "date" => "05/05/2024",
                        "duration" => 0
                    ],
                    [
                        "date" => "06/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "07/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "08/05/2024",
                        "duration" => 0
                    ],
                    [
                        "date" => "09/05/2024",
                        "duration" => 0
                    ],
                    [
                        "date" => "10/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "11/05/2024",
                        "duration" => 0
                    ],
                    [
                        "date" => "12/05/2024",
                        "duration" => 0
                    ],
                    [
                        "date" => "13/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "14/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "15/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "16/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "17/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "18/05/2024",
                        "duration" => 0
                    ],
                    [
                        "date" => "19/05/2024",
                        "duration" => 0
                    ],
                    [
                        "date" => "20/05/2024",
                        "duration" => 0
                    ],
                    [
                        "date" => "21/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "22/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "23/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "24/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "25/05/2024",
                        "duration" => 0
                    ],
                    [
                        "date" => "26/05/2024",
                        "duration" => 0
                    ],
                    [
                        "date" => "27/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "28/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "29/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "30/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "31/05/2024",
                        "duration" => 8
                    ]
                ]
            ],
            'test working very very hard' => [
                'expectedResult' => [
                    "nbOpenDays" => 22,
                    "nbOpenHours" => 176,
                    "nbSundays" => 4,
                    "nbSundayHours" => 32,
                    "nbBankHolidays" => 4,
                    "nbBankHolidayHours" => 32,
                    "nbAbsence" => 1
                ],
                'params' => [
                    [
                        "date" => "01/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "02/05/2024",
                        "duration" => 0 // Pas de travail le jour de mon anniversaire :D
                    ],
                    [
                        "date" => "03/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "04/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "05/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "06/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "07/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "08/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "09/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "10/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "11/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "12/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "13/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "14/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "15/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "16/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "17/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "18/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "19/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "20/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "21/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "22/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "23/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "24/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "25/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "26/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "27/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "28/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "29/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "30/05/2024",
                        "duration" => 8
                    ],
                    [
                        "date" => "31/05/2024",
                        "duration" => 8
                    ]
                ]
            ]
        ];

        foreach ($UNIT_TESTS as $title => $data) {
            echo $title . "\n";
            $result = $this->run($data['params']);
            $nbError = 0;
            foreach ($data['expectedResult'] as $key => $value) {
                if (! isset($result[$key])) {
                    $nbError ++;
                    echo "No value for " . $key . "\n";
                } else if ($result[$key] !== $value) {
                    $nbError ++;
                    echo "Wrong value for " . $key . " -> expected : " . $value . " found :" . $result[$key] . "\n";
                }
            }
            echo "\n";
            if ($nbError === 0) {
                echo $title . " --> All OK\n";
            } else {
                echo $title . " --> " . $nbError . " errors\n";
            }

            echo "_________________\n\n";
        }
    }
}