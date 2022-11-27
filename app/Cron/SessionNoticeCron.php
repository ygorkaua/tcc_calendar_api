<?php

namespace App\Cron;

use App\Models\SessionsToNotice;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;

class SessionNoticeCron
{
    private const USER_ID = 'user_id';

    public function __construct(private SessionsToNotice $sessionsToNotice)
    {}

    /**
     * @return bool
     * @throws \PHPMailer\PHPMailer\Exception
     * @throws Exception
     */
    public function noticeSessions()
    {
        $sessionsToNotice = $this->sessionsToNotice->getSessionsToNotice();

        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 465;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Username = 'avisospsicologiaunip@gmail.com';
        $mail->Password = 'Unip@123';

        $mail->setFrom('avisospsicologiaunip@gmail.com', 'Aviso Psicologia');
        $mail->addAddress('ygorkaua27@gmail.com', 'Paciente');

        $mail->isHTML();
        $mail->Subject = 'Aviso de sessão';
        $mail->Charset = 'UTF-8';
        $mail->Body = "<h4> PHPMailer the awesome Package </h4>
            <b>PHPMailer is working fine for sending mail</b>
            <p> This is a tutorial to guide you on PHPMailer integration</p>";

        // Send mail
        if (!$mail->send()) {
            $mail->smtpClose();
            return 'Email not sent an error was encountered: ' . $mail->ErrorInfo;
        } else {
            $mail->smtpClose();
            return 'Message has been sent.';
        }
    }
}
