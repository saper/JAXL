<?php

class JAXL0277 {

    public static $ns = 'http://jabber.org/protocol/pubsub';
    public static $ns2 = 'urn:xmpp:microblog:0';
    public static $ns3 = 'urn:xmpp:microblog:0+notify';
    
    public static function init($jaxl) {
        $jaxl->features[] = self::$ns2;
        $jaxl->features[] = self::$ns3;
        JAXLXml::addTag('iq','pubsubItemsEntryAuthor',  '//iq/pubsub/items/item/entry/source/author/name');
        JAXLXml::addTag('iq','pubsubItemsEntryContent',  '//iq/pubsub/items/item/entry/content');
        JAXLXml::addTag('iq','pubsubItemsEntryPublished',  '//iq/pubsub/items/item/entry/published');
        //JAXLXml::addTag('iq','pubsubItemsEntryLink',  '//iq/pubsub/items/item/entry/link/@href');
        JAXLXml::addTag('iq','pubsubItemsEntryType',  '//iq/pubsub/items/item/entry/link/@type');
        JAXLXml::addTag('iq','pubsubItemsEntryRel',  '//iq/pubsub/items/item/entry/link/@rel');
        JAXLXml::addTag('iq','pubsubItemsEntryLinkRel',  '//iq/pubsub/items/item/entry/link/link/@rel');
    }
    
    public static function createNode($jaxl, $to) {
        $payload = '';
        $payload .= '<pubsub xmlns="'.self::$ns.'">';
        $payload .= '<create node="urn:xmpp:microblog:0"/>';
        $payload .= "
                    <configure>
                        <x xmlns='jabber:x:data' type='submit'>
                            <field var='FORM_TYPE' type='hidden'>
                                <value>http://jabber.org/protocol/pubsub#node_config</value>
                            </field>

                            <field var='pubsub#title'>
                                <value>Gnagna</value>
                            </field>

                            <field var='pubsub#deliver_notifications'>
                                <value>1</value>
                            </field>

                            <field var='pubsub#deliver_payloads'>
                                <value>1</value>
                            </field>

                            <field var='pubsub#persist_items'>
                                <value>1</value>
                            </field>

                            <field var='pubsub#max_items'>
                                <value>100</value>
                            </field>

                            <field var='pubsub#item_expire'>
                                <value>604800</value>
                            </field>

                            <field var='pubsub#access_model'>
                                <value>open</value>
                            </field>

                            <field var='pubsub#publish_model'>
                                <value>publishers</value>
                            </field>

                            <field var='pubsub#purge_offline'>
                                <value>0</value>
                            </field>

                            <field var='pubsub#notify_config'>
                            <value>0</value>
                            </field>

                            <field var='pubsub#notify_delete'>
                            <value>0</value>
                            </field>

                            <field var='pubsub#notify_retract'>
                            <value>0</value>
                            </field>
                            
                            
                            <field var='pubsub#subscribe' type='boolean'
                            label='Whether to allow subscriptions'>
                            <value>1</value>
                            </field>

                            <field var='pubsub#send_last_published_item' type='list-single'
                            label='When to send the last published item'>
                                <option label='Never'><value>never</value></option>
                                <option label='When a new subscription is processed'><value>on_sub</value></option>
                                <option label='When a new subscription is processed and whenever a subscriber comes online'>
                                <value>on_sub_and_presence</value>
                                </option>
                            <value>on_sub_and_presence</value>
                            </field>


                            <field var='pubsub#notify_sub'>
                            <value>1</value>
                            </field>

                            <field var='pubsub#type'>
                            <value>http://www.w3.org/2005/Atom</value>
                            </field>

                            <field var='pubsub#body_xslt'>
                            <value>http://jabxslt.jabberstudio.org/atom_body.xslt</value>

                            </field>

                        </x>

                    </configure>
        ";
        $payload .= '</pubsub>';
        return XMPPSend::iq($jaxl, 'set', $payload, $to);
    }
    
    public static function getItems($jaxl, $to) {
        $payload = '';
        $payload .= '<pubsub xmlns="'.self::$ns.'">';
        $payload .= '<items node="urn:xmpp:microblog:0" max_items="20"/>';
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
    
    public static function subscribeNode($jaxl, $to, $contact) {
        $payload = '';
        $payload .= '<pubsub xmlns="'.self::$ns.'">';
        $payload .= '<subscribe
                        node="urn:xmpp:microblog:0"
                        jid="'.$to.'"/>';
        $payload .= '</pubsub>';
        return XMPPSend::iq($jaxl, 'set', $payload, $contact);
    }

    public static function publishItem($jaxl, $to, $content, $from, $callback) {
        $payload ='
        <pubsub xmlns="'.self::$ns.'">
            <publish node="urn:xmpp:microblog:0">
            <item>
                <entry xmlns="http://www.w3.org/2005/Atom">
                    <source>
                        <author>
                            <name></name>
                            <uri>xmpp:'.$to.'</uri>
                        </author>
                    </source>
                    <content type="text">'.$content.'</content>
                    <published>'.date(DATE_ISO8601).'</published>
                    <updated>'.date(DATE_ISO8601).'</updated>
                </entry>
            </item>
            </publish>
        </pubsub>';
        return XMPPSend::iq($jaxl, 'set', $payload, $to);
    }

}
