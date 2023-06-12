<?php

namespace App\Service;

readonly class ScheduleService
{
    public function __construct(private int $groupId)
    {
    }

    public function getSchedule(string $date): array
    {
        $url = 'https://stud.mgri.ru/api/Rasp?idGroup='.$this->groupId.'&sdate='.$date;
        $response = json_decode(file_get_contents($url), true);

        file_put_contents('/tmp/schedule.txt', $response);

        return array_map(fn (array $raspString): string => $this->getScheduleString($raspString), $response);
    }

    private function getScheduleString(array $rasp): string
    {
        $result = [];

        $result[] = $rasp['дисциплина'];
        $result[] = $rasp['аудитория'];
        $result[] = $rasp['преподаватель'];

        return $rasp['начало'].'-'.$rasp['конец'].'\n'.implode('\n', $result);
    }
}
