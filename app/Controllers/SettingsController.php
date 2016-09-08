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
    
    public function getUserSettingsPanel(Http $http) {
        $options = unserialize( get_user_meta( get_current_user_id(), 'm12e-user-settings', true) );

        if ( $http->has('account_username') ) {
            $postback = true;

            $options_pure                 =  array();
            $options_pure["username"] = $http->get('account_username');
            $options_pure["password"] = $http->get('account_password');
            $options = $options_pure;

            update_user_meta( get_current_user_id(), 'm12e-user-settings', serialize($options) );
        } else {
            $postback = false;
        }

        return view('@MailTwelve/settings-user.twig', [
            'title'     => Helper::get('pluginName'),
            'data'      => $options,
            'saved'     => $postback
        ]);
    }
}
