<?php

namespace Odin\utils;

/**
 * Gerencia o envio de e-mails
 * @author Edney Mesquita
 */

class Mail
{

    /**
     * @var string $origin Email ao qual envia a mensagem
     * @var string $destiny Email destinatário
     * @var string $subject Título do email
     * @var string $headers Cabeçalhos de configurações do email
     * @var string $message Corpo da mensagem
     */
    private static $origin;
    private static $destiny;
    private static $subject;
    private static $headers;
    private static $message;

    /**
     * Define o remetente do email
     * @param string $from
     * @return void
     */
    public static function from(string $from)
    {
        self::$origin = $from;
    }

    /**
     * Configura o destinatário e o título do email
     * @param string $to Destinatário (Contato <contato@email.com>)
     * @param string $subject Título da mensagem
     */
    public static function write($to, $subject)
    {
        self::$destiny = $to;
        self::$subject = $subject;
    }

    /**
     * Configura os cabeçalhos do email
     * @param array $configs Configurações a serem adicionadas no email
     * @return void
     */
    public static function headers($configs = null)
    {
        self::$headers = "MIME-Version: 1.1\n";
        self::$headers .= "Content-type: text/html; charset=iso-8859-1\n";
        self::$headers .= "From: <{self::$origin}>\n";
        self::$headers .= "Return-Path: <{self::$origin}>\n";
        self::$headers .= "Reply-to: <{self::$origin}>\n";
        if ($configs) {
            foreach ($configs as $config)
            {
                self::$headers .= $config . "\n";
            }
        }
    }

    /**
     * Configura o corpo do email
     * @param string $msgBody Mensagem do email
     * @return void
     */
    public static function message($msgBody)
    {
        self::$message = $msgBody;
    }

    /**
     * Envia o email
     * @throws Exception
     * @reurn void
     */
    public static function send()
     {
        return mail(self::$destiny, self::$subject, self::$message, self::$headers, "-r" . self::$origin);
    }

}
