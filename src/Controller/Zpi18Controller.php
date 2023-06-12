<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use TelegramBot\Api\BotApi;

#[Route(path: '/zpi18')]
class Zpi18Controller extends AbstractController
{
    #[Route(path: '/pidor', name: 'app_zpi18_pidorbot')]
    public function pidorBot()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $telegramBot = new BotApi($_ENV['TELEGRAM_BOT_TOKEN_ZPI18_PIDOR']);

        $headers = ['content-Type' => 'text/json'];

        if (!empty($data['message']['text'])) {
            $chatId = $data['message']['from']['id'];
            $text = trim($data['message']['text']);

            if (str_contains($text, '/start')) {
                // Получение списка участников группы
                $members = $telegramBot->getChat($chatId)->getActiveUsernames();
                // Получение информации о случайном участнике
                $telegramBot->sendMessage($chatId, 'users: '.print_r($members, true));
                //$user = $telegramBot->getChatMember($chatId, $randomIndex)->getUser();
                //$message = 'И пидор дня - ' . $user->getFirstName() . ' ' . $user->getLastName();
                //$telegramBot->sendMessage($chatId, $message);

                return new Response('ok', headers: $headers);
            } else {
                return new Response('Unknown command', Response::HTTP_UNPROCESSABLE_ENTITY, $headers);
            }
        }

        return new Response('Empty body', Response::HTTP_UNPROCESSABLE_ENTITY, $headers);
    }
}
