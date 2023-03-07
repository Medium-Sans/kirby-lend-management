<?php

namespace MediumSans\LendManagement;

use Kirby\Exception\Exception;

class Mailer
{
    static function notifyLenderOfExpiration(\stdClass $lend): bool
    {
        $borrower = Borrower::find($lend->borrower_id);
        $kirby = kirby();
        $sent = true;

        try {
            $kirby->email([
                'to' => $borrower->email,
                'from' => 'notifications@head-pool-image-son.ch',
                'body' => 'L\'emprunt effectuer au pool image-son est arrivé à échéance, merci de ramener le matériel le mardi suivant de 9h à 12h',
                'replyTo' => 'samuel.cardoso@hesge.ch',
                'subject' => 'Emprunt - Pool Image-Son',
            ]);
        } catch (Exception $error) {
            echo $error;
            $sent = false;
        }

        return $sent;
    }

    static function sendMessage(string $to, string $subject, string $body): bool
    {
        $kirby = kirby();
        $sent = true;

        try {
            $kirby->email([
                'to' => $to,
                'from' => 'notifications@head-pool-image-son.ch',
                'body' => $body,
            ]);
        } catch (Exception $error) {
            echo $error;
            $sent = false;
        }

        return $sent;
    }
}
