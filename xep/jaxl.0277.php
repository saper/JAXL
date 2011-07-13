<?php

class JAXL0277 {

    public static $ns = 'http://jabber.org/protocol/pubsub';
    
    public static function init($jaxl) {
        JAXLXml::addTag('iq','pubsubItemsEntryAuthor',  '//iq/pubsub/items/item/entry/source/author/name');
        JAXLXml::addTag('iq','pubsubItemsEntryContent',  '//iq/pubsub/items/item/entry/content');
    }
    
    public static function getItems($jaxl, $to) {
        $payload = '';
        $payload .= '<pubsub xmlns="'.self::$ns.'">';
        $payload .= '<items node="urn:xmpp:microblog:0" max_items="20"></items>';
        $payload .= '</pubsub>';
        return XMPPSend::iq($jaxl, 'get', $payload, $to);
    }

    public static function getComments($jaxl, $to, $id) {
        $payload = '';
        $payload .= '<pubsub xmlns="'.self::$ns.'">';
        $payload .= '<items node="urn:xmpp:microblog:0:comments/'.$id.'"></items>';
        $payload .= '</pubsub>';
        return XMPPSend::iq($jaxl, 'get', $payload, $to);
    }
}
