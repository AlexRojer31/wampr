<?php
namespace src\php;

/**
 * @Route("/")
 */
class Main {

    public function GET() : void
    {
        require_once("public/index.html");
    }

}

?>