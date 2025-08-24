<?php

namespace App\Wrappers;

use App\Models\User;
use Html2Text\Html2Text;
use PHPMailer\PHPMailer\PHPMailer;

class Mail
{
    private const string WELCOME_SUBJECT = "¡Bienvenid@ al CEETSII, %s!";
    private PHPMailer $client;

    public function __construct()
    {
        $mail = Env::mail();
        $this->client = new PHPMailer(true);
        $this->client->CharSet = 'UTF-8';
        $this->client->isSMTP();
        $this->client->Host = $mail['host'];
        $this->client->Port = $mail['port'];

        if (!empty($mail['secure'])) {
            $this->client->SMTPSecure = $mail['secure'];
        }

        if (!empty($mail['password'])) {
            $this->client->SMTPAuth = true;
            $this->client->Username = $mail['username'];
            $this->client->Password = $mail['password'];
        }

        $this->client->setFrom($mail['username'], $mail['from']);
    }

    /**
     * Send welcome message to user
     */
    public function sendWelcome(User $user): void
    {
        $this->client->addAddress($user->email, $user->getFullName());
        $this->client->isHTML();
        $this->client->Subject = sprintf(self::WELCOME_SUBJECT, $user->firstName);

        $body = Plates::render('views/mails/welcome', [
            'user' => $user,
        ]);

        $this->client->Body = $body;
        $this->client->AltBody = (new Html2Text($body))->getText();
        $this->client->send();

        // Cleanup
        $this->__cleanup();
    }

    private function __cleanup(): void
    {
        $this->client->clearAddresses();
    }
}
