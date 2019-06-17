<?php

namespace Odin\utils\services;

final class JWT 
{
    protected static $key;

    public static function key(string $key)
    {
        static::$key = $key;
    }

    public static function token(array $payload, int $expiration_date)
    {
        if(!empty(static::$key)) {
            if(is_array($payload) && !empty($payload)) {
                $header = [
                    'typ' => "JWT",
                    'alg' => "HS256",
                    'expiration' => $expiration_date
                ];
                
                $header = json_encode($header);
                $header = base64_encode($header);
                
                $payload = json_encode($payload);
                $payload = base64_encode($payload);
                
                $signature = hash_hmac('sha256', "{$header}.{$payload}", static::$key, true);
                $signature = base64_encode($signature);
                
                $token = "{$header}.{$payload}.{$signature}";
                
                return $token;
            } else {
                throw new \Exception("Invalid Payload");
            }
        } else {
            throw new \Exception("Invalid Key");
        }
    }

    public static function validate(string $token)
    {
        $payload = json_decode(base64_decode(explode(".", $token)[1]), true);
        $expiration = json_decode(base64_decode(explode(".", $token)[0]), true)["expiration"];
        $expiration_date = date('Y-m-d H:i:s', $expiration);
        $current = date('Y-m-d H:i:s');

        if ($expiration_date >= $current) {
            return ($token === static::token($payload, $expiration));
        } else {
            throw new \Exception("Token Expired");
        }
    }
}
