<?php

namespace App\Controller;

use App\Service\ScheduleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use TelegramBot\Api\BotApi;

#[Route(path: '/zpi18')]
class Zpi18Controller extends AbstractController
{
    #[Route(path: '/pidor', name: 'app_zpi18_pidorbot')]
    public function pidorBot(): Response
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $telegramBot = new BotApi($_ENV['TELEGRAM_BOT_TOKEN_ZPI18_PIDOR']);

        $headers = ['content-Type' => 'text/json'];

        if (!empty($data['message']['text'])) {
            $chatId = $data['message']['from']['id'];
            $text = trim($data['message']['text']);

            if (str_contains($text, '/start')) {
                $pidors = [
                    'Алтухов',
                    'Романов',
                    'Леньшин',
                ];
                $rand = rand(0, count($pidors));
                $telegramBot->sendMessage($chatId, 'И пидор дня - '.$pidors[$rand]);

                return new Response('ok', headers: $headers);
            } else {
                return new Response('Unknown command', Response::HTTP_UNPROCESSABLE_ENTITY, $headers);
            }
        }

        return new Response('Empty body', Response::HTTP_UNPROCESSABLE_ENTITY, $headers);
    }

    #[Route(path: '/schedule', name: 'app_zpi18_schedule')]
    public function schedule(ScheduleService $scheduleService): Response
    {
        $headers = ['content-Type' => 'text/json'];
        $data = json_decode(file_get_contents('php://input'), true);

        if (!empty($data['message']['text'])) {
            $chatId = $data['message']['from']['id'];
            $text = trim($data['message']['text']);


            if (str_contains($text, '/rasp')) {
                $telegramBot = new BotApi($_ENV['TELEGRAM_BOT_TOKEN_ZPI18_PIDOR']);
                $schedule = $scheduleService->getSchedule(date('Y-m-d'));
                $telegramBot->sendMessage($chatId, implode('\n', $schedule));

                return new Response('ok', headers: $headers);
            } else {
                return new Response('Unknown command', Response::HTTP_UNPROCESSABLE_ENTITY, $headers);
            }
        }

        return new Response('Empty body', Response::HTTP_OK, $headers);
    }
}
