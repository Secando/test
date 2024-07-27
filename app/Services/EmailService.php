<?php

namespace App\Services;

use App\Config;
use App\Entity\EmailQueue;
use App\Entity\UserCode;

use App\Enums\EmailQueueStatus;
use App\Enums\EmailStatus;
use App\Enums\UserCodeStatus;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
    public function __construct(private readonly MailerInterface $mailer,readonly Config $config,private readonly EntityManager $em)
    {
    }

    public function sendVerificationLink($user){
        $verificationCode = hash("sha256",$user->getEmail().rand(0,999999));
        $verificationLink = $this->config->app_host. '/verificate/'.$verificationCode;
        $userCode = new UserCode();
        $userCode->setCode($verificationCode);
        $userCode->setUserId($user->getId());
        $userCode->setStatus((UserCodeStatus::WAITING)->value);

        $email = new EmailQueue();
        $email->setHeader('Ссылка для подтверждения FushiManga');
        $email->setEmailTo($user->getEmail());
        $email->setEmailFrom($this->config->app_email);
        $email->setBody('Перейдите по ссылке:'.$verificationLink);
        $email->setStatus((EmailQueueStatus::PENDING)->value);
        $this->em->persist($userCode);
        $this->em->persist($email);
        $this->em->flush();


  }
    public function sendQueueMail()
    {
        $query = $this->em->createQueryBuilder();
        $emails =$query->select('e')->from(EmailQueue::class,'e')->where('e.status=:status')->setParameter('status',(string)EmailQueueStatus::PENDING->value)->getQuery()->getResult();
        foreach ($emails as $email){
            $emailMessage = (new Email())
                ->from($email->getEmailFrom())
                ->to($email->getEmailTo())
                ->subject($email->getHeader())
                ->text($email->getBody());

            $this->mailer->send($emailMessage);
            $email->setStatus(EmailQueueStatus::SUCCESS->value);
            $this->em->persist($email);
            $this->em->flush();
        }

    }
}