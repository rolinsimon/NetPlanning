<?

class CalendarMonthsTotals
{

    const BANK_HOLIDAYS = [
        2024 => [
            1 => [
                1
            ],
            2 => [],
            3 => [],
            4 => [
                1
            ],
            5 => [
                1,
                8,
                9,
                20
            ],
            6 => [],
            7 => [
                14
            ],
            8 => [
                15
            ],
            9 => [],
            10 => [],
            11 => [
                1,
                11
            ],
            12 => [
                25
            ]
        ]
    ];

    private int $nbOpenDays = 0;

    private int $nbOpenDayHours = 0;

    private int $nbSundays = 0;

    private int $nbSundayHours = 0;

    private int $nbBankHolidays = 0;

    private int $nbBankHolidayHours = 0;

    private int $nbAbsence = 0;

    public function __construct()
    {}

    public function addDay(DateTime $date, int $duration = 0): void
    {
        if ($duration === 0) {
            $this->nbAbsence ++;
            return;
        }

        $isHoliday = $this->isBankHoliday($date);

        if ($this->isSunday($date)) {

            if ($isHoliday && $duration >= 4) {
                // Sunday + bankHoliday
                $this->nbBankHolidays ++;
                $this->nbBankHolidayHours += $duration;
            } else if ($duration >= 3) {
                $this->nbSundays ++;
                $this->nbSundayHours += $duration;
            } else {
                $this->nbOpenDays ++;
                $this->nbOpenDayHours += $duration;
            }
        } else {

            if ($isHoliday && $duration >= 4) {
                // BankHoliday
                $this->nbBankHolidays ++;
                $this->nbBankHolidayHours += $duration;
            } else {
                $this->nbOpenDays ++;
                $this->nbOpenDayHours += $duration;
            }
        }
    }

    public function isSunday(DateTime $date): bool
    {
        return $date->format("D") === "Sun";
    }

    public function isBankHoliday(DateTime $date): bool
    {
        return in_array((int) $date->format("d"), self::BANK_HOLIDAYS[(int) $date->format("Y")][(int) $date->format("m")]);
    }

    public function displayResults(): void
    {
        echo "nbOpenDays => " . $this->nbOpenDays . "\n" . "nbOpenHours => " . $this->nbOpenDayHours . "\n" . "nbSundays => " . $this->nbSundays . "\n" . "nbSundayHours => " . $this->nbSundayHours . "\n" . "nbBankHolidays => " . $this->nbBankHolidays . "\n" . "nbBankHolidayHours => " . $this->nbBankHolidayHours . "\n" . "nbAbsence => " . $this->nbAbsence . "\n" . "\n";
    }

    public function getTotals(): array
    {
        return [
            "nbOpenDays" => $this->nbOpenDays,
            "nbOpenHours" => $this->nbOpenDayHours,
            "nbSundays" => $this->nbSundays,
            "nbSundayHours" => $this->nbSundayHours,
            "nbBankHolidays" => $this->nbBankHolidays,
            "nbBankHolidayHours" => $this->nbBankHolidayHours,
            "nbAbsence" => $this->nbAbsence
        ];
    }
}

?>