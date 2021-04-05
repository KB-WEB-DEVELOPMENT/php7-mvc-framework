<?php
declare(strict_types=1);

use Framework\Test as Test;
use Framework\Request as Request;

$post = function($url, $data)
{
    $request = new Request();
    return $request->post("http://".$_SERVER["HTTP_HOST"]."/{$url}", $data);
};

   Test::add(
    function() use ($post)
    {
        $html = $post(
            "register.html",
            array(
                "first" => "Hello",
                "last" => "World",
                "email" => "info@example.com",
                "password" => "password",
                "register" => "register"
            )
        );
        
        return (stristr($html, "Your account has been created!"));
    },
    "Register form creates user",
    "Functions/Users"
);

  Test::add(
    function() use ($post)
    {
        $html = $post(
            "login.html",
            array(
                "email" => "info@example.com",
                "password" => "password",
                "login" => "login"
            )
        );
        
        return (stristr($html, "Location: /profile.html"));
    },
    "Login form creates user session + redirect to profile",
    "Functions/Users"
);
