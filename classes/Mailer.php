<?php

namespace MediumSans\LendManagement;

use Kirby\Exception\Exception;
use Kirby\Exception\NotFoundException;

class Mailer
{

    /**
     * Get the sender address. If not throw an exception.
     * @throws Exception
     */
    static function getSender(): string
    {
        $sender = kirby()->option('mediumsans.kirby-lend-management.email.sender');

        if ($sender === '' || $sender === null) {
            throw new Exception('Please set the sender email address in your config file.');
        }

        return $sender;
    }

    /**
     * Get the replyTo address. If not set, use the sender address.
     * @return string
     * @throws Exception
     */
    static function getReplyTo(): string
    {
        $replyTo = kirby()->option('mediumsans.kirby-lend-management.email.replyTo');
        if ($replyTo === null || $replyTo === '') {
            $replyTo = self::getSender();
        }

        return $replyTo;
    }

    /**
     * @return string
     * @throws Exception
     */
    private static function getLendHasExpiredNotificationSubject(): string
    {
        $subject = kirby()->option('mediumsans.kirby-lend-management.email.subject.expired');

        if ($subject === '' || $subject === null) {
            throw new Exception('Please set the subject of the expiration notification in your config file.');
        }

        return $subject;
    }

    /**
     * @return string
     * @throws Exception
     */
    private static function getLendHasBeenExtendedNotificationSubject(): string
    {
        $subject = kirby()->option('mediumsans.kirby-lend-management.email.subject.extended');

        if ($subject === '' || $subject === null) {
            throw new Exception('Please set the subject of the expiration notification in your config file.');
        }

        return $subject;
    }

    /**
     * Email the borrower to notify him that the lend is about to expire.
     * Using the template 'expired.txt' in the templates folder.
     * @param \stdClass $lend
     * @return bool
     * @throws Exception
     * @throws NotFoundException
     */
    static function notifyBorrowerOfExpiration(\stdClass $lend): bool
    {
        $sent = true;

        $kirby = kirby();
        $borrower = Borrower::find($lend->borrower_id);

        $from = self::getSender();
        $replyTo = self::getReplyTo();
        $subject = self::getLendHasExpiredNotificationSubject();
        $body = file_get_contents(__DIR__ . "/../templates/emails/expired.txt");

        $sent = self::sendMessage($borrower->email, $from, $replyTo, $subject, $body);

        return $sent;
    }

    /**
     * Email the borrower to notify him that the loan has been extended.
     * @param \stdClass $lend
     * @param \stdClass $extension
     * @return bool
     * @throws Exception
     * @throws NotFoundException
     */
    static function notifyBorrowerOfExtension(\stdClass $lend): bool
    {
        $kirby = kirby();
        $borrower = Borrower::find($lend->borrower_id);

        $from = self::getSender();
        $replyTo = self::getReplyTo();
        $subject = self::getLendHasBeenExtendedNotificationSubject();
        $content = file_get_contents(__DIR__ . "/../templates/emails/extended.txt");
        $return_date = LendExtension::getLendExpiryDateByLendId($lend->end_date, $lend->id);
        $body = str_replace("%new_return_date%", $return_date, $content);

        $sent = self::sendMessage($borrower->email, $from, $replyTo, $subject, $body);

        return $sent;
    }

    /**
     * @param string $to
     * @param string $from
     * @param string $replyTo
     * @param string $subject
     * @param string $body
     * @return bool
     * @throws Exception
     */
    static function sendMessage(string $to, string $from, string $replyTo, string $subject, string $body): bool
    {
        $kirby = kirby();
        $sent = true;

        try {
            $kirby->email([
                'to' => $to,
                'from' => $from,
                'body' => $body,
                'replyTo' => $replyTo,
                'subject' => $subject,
            ]);
        } catch (Exception $error) {
            $sent = false;
            throw new Exception($error);
        }

        return $sent;
    }
}
