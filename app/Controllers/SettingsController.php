<?php namespace MailTwelve\Controllers;

/** @var Herbert\Framework\Http $http */

use Herbert\Framework\Models\Post;
use Herbert\Framework\Http;
use MailTwelve\Helper;

class SettingsController {
    public function getSettingsPanel(Http $http) {
        $options = unserialize( get_option('m12e-settings') );

        if ( $http->has('mail_protocol') ) {
            $postback = true;

            $options_pure                 =  array();
            $options_pure["protocol"]     = $http->get('mail_protocol');
            $options_pure["incomingUrl"]  = $http->get('incoming_mailserver_url');
            $options_pure["incomingPort"] = $http->get('incoming_mailserver_port');
            $options_pure["outgoingUrl"]  = $http->get('outgoing_mailserver_url');
            $options_pure["outgoingPort"] = $http->get('outgoing_mailserver_port');
            $options = $options_pure;

            update_option( 'm12e-settings', serialize($options) );
        } else {
            $postback = false;
        }
        
        $protocols = ['IMAP', 'POP'];

        return view('@MailTwelve/settings.twig', [
            'title'     => Helper::get('pluginName'),
            'protocols' => $protocols, 
            'data'      => $options,
            'saved'     => $postback
        ]);
    }
}
