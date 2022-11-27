<?php

namespace App\Cron;

use App\Models\SessionsToNotice;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;

class SessionNoticeCron
{
    public function __construct(private SessionsToNotice $sessionsToNotice)
    {}

    /**
     * @throws Exception
     */
    public function noticeSessions()
    {
        try {
            $sessionsToNotice = $this->sessionsToNotice->getSessionsToNotice();

            foreach ($sessionsToNotice as $session) {
                $mail = new PHPMailer(true);

                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 465;
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = 'ssl';
                $mail->Username = 'avisospsicologiaunip@gmail.com';
                $mail->Password = 'hzsriopoxqooekkd';

                $mail->setFrom('avisospsicologiaunip@gmail.com', 'Aviso Psicologia');
                $mail->addAddress($session->user_id, 'Paciente');

                $mail->isHTML();
                $mail->Subject = 'Aviso de sessão';
                $mail->Charset = 'UTF-8';
                $mail->Body = "<h2> Aviso de agendamento de sessão </h2>
                    <b>Você tem uma sessão agendada para " . $session->session_date . "</b>
                    <p> Para acessar, entre na página de identificação no horário informado com o número da sala " . $session->meet_id . "</p>";

                if (!$mail->send()) {
                    $mail->smtpClose();
                    return 'Erro ao enviar o aviso: ' . $mail->ErrorInfo;
                } else {
                    $mail->smtpClose();
                }
            }

            return 'Aviso enviado';
        } catch (Exception $exception) {
            return $exception->getMessage();
        }
    }
}
