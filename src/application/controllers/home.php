<?php
declare(strict_types=1);

use Shared\Controller as Controller;
use Framework\ArrayMethods as ArrayMethods;

class Home extends Controller
{
    public function index(): void
    {
        $user = $this->getUser();
        $view = $this->getActionView();

        if ($user)
        {
            $friends = Friend::all(array(
                "user = ?" => $user->id,
                "live = ?" => true,
                "deleted = ?" => false
            ), array("friend"));

            $ids = array();
            foreach($friends as $friend)
            {
                $ids[] = $friend->friend;
            }

            $messages = Message::all(array(
                "user in ?" => $ids,
                "live = ?" => true,
                "deleted = ?" => false
            ), array("*"), "created", "asc");

            $view->set("messages", $messages);
        }
    }
}
