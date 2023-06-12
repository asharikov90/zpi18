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

        return array_map(fn (array $raspString): string => $this->getScheduleString($raspString), $response['data']['rasp']);
    }

    private function getScheduleString(array $rasp): string
    {
        $result = [];

        $result[] = $rasp['дисциплина'];
        $result[] = $rasp['аудитория'];
        $result[] = $rasp['преподаватель'];

        return $rasp['начало'].'-'.$rasp['конец'].'<br>'.implode('<br>', $result);
    }
}
