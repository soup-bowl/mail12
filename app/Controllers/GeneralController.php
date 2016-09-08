<?php namespace MailTwelve\Controllers;

use Herbert\Framework\Models\Post;

class GeneralController {

    public function info($id) {
        return view('@MailTwelve/test.twig', [
            'title'   => 'Test',
            'content' => 'Test successful.'
        ]);
    }
}