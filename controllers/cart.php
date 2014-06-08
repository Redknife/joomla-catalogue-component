<?php
defined('_JEXEC') or die;

require_once(JPATH_COMPONENT_SITE.DS.'helper.php');

class CatalogueControllerCart extends JControllerForm
{
    public function add_to_cart(){
        $app = JFactory::getApplication();    

        $cart_items = $app->getUserState('com_catalogue.cart');        
        $item_id = $this->input->get('id', 0, 'get', 'int');
        
        $cart_items = unserialize($cart_items);
        
        if (!is_array($cart_items))
        {
            $cart_items = array();
        }
        if($item_id && !in_array($item_id, $cart_items)){
            $cart_items[] = $item_id;
        }
    
        $cart_items = serialize($cart_items);
        $app->setUserState('com_catalogue.cart', $cart_items);
        
        $this->setRedirect($_SERVER['HTTP_REFERER']);
    }

    public function remove_from_cart(){
        $app = JFactory::getApplication();
        $data = $app->getUserState('com_catalogue.cart');
        $item_id = JRequest::getVar('id', 0, 'get', 'int');
        
        if (CatalogueHelper::inCart($item_id))
        {
            $cart_items = unserialize($data);
            if(is_array($cart_items) && in_array($item_id, $cart_items)){
                $key = array_search($item_id, $cart_items);
                unset($cart_items[$key]);
                $data = serialize($cart_items);
                $app->setUserState('com_catalogue.cart', $data);
            }
            
            $this->setRedirect($_SERVER['HTTP_REFERER']);
        }
        return false;
    }

    public function send()
    {
       JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $app = JFactory::getApplication();
        $params = $app->getParams();
        $manager_email = $params->toArray()['cartemail'];
        $manager_greeting = $params->toArray()['greetingmanager'];
        $manager_subject = $params->toArray()['cartemailsubject'];

        $name = $this->input->get('name', '', 'string');
        $phone = $this->input->get('phone', '', 'string');
        $email = $this->input->get('email', '', 'string');
        $desc = $this->input->get('desc', '', 'string');
        $itemId = $this->input->get('id', '', 'string');
        $client_subject = $params->toArray()['cartemailclientsubject'];
        $client_body = 'Наш менеджер свяжется с вами';

        $client_greeting = 'Здравствуйте, '.$name;

        $app = JFactory::getApplication();
        $mail = JFactory::getMailer();

        $mail->IsHTML(true);

        $mailfrom   = $app->getCfg('mailfrom');
        $fromname   = $app->getCfg('fromname');
        $sitename   = $app->getCfg('sitename');

            // check..
        if ($email){
            if(!preg_match("~^([a-z0-9_\-\.])+@([a-z0-9_\-\.])+\.([a-z0-9])+$~i", $email)){
                    $app->redirect('/', JText::_('Некоректный Email'));
              return;
            }
        }

        if(strlen($name) < 3){
            $app->redirect('/', JText::_('Некоректное имя'));
          return;
        }
            // ..check

        // get order items..
        $body_items = '<table width="550px" cellspacing="5"><tbody><tr><td>Наименование</td><td>Цена</td></tr>';

        $item = CatalogueHelper::getItemById($itemId);
        if($item){
            $body_items .= '<tr>';
            $body_items .= '<td>'.$item->item_name.'</td>';
            $body_items .= '<td>'.$item->price.'</td>';
            $body_items .= '</tr>';
        }
        $body_items .= '</table>';  
            // ...get order items

        $mail->addRecipient($manager_email);
        $mail->addReplyTo(array($manager_email, $name));
        $mail->setSender(array($mailfrom, $fromname));
        $mail->setSubject($manager_subject);
        
        $body = strip_tags(str_replace(array('<br />','<br/>'), "\n", $manager_greeting)."\n");

        $body .= '<br/><br/>';
        
        if ($name)
            $body .= 'ФИО отправителя: '.$name."<br/>";
        
        if ($phone)
            $body .= 'Телефон: '.$phone."<br/>";
        
        if ($email)
            $body .= 'E-mail: '.$email."<br/>";

        if ($desc)
            $body .= 'Описание вопроса: '.$desc."<br/>";
        
        $body .= strip_tags(str_replace(array('<br />','<br/>'), "\n", $manager_body));

        $body .= '<br/>'.$body_items;
        
        $mail->setBody($body);
        print_r($body);
        
        $sent = $mail->Send();
        
        if ($email)
        {
            $mail = JFactory::getMailer();
            $mail->addRecipient($email);
            if (!$name)
                $name = $email;
            $mail->addReplyTo(array($email, $name));
            $mail->setSender(array($mailfrom, $fromname));
            $mail->setSubject($client_subject);
            
            $body = strip_tags(str_replace(array('<br />','<br/>'), "\n", $client_greeting)."\n");
            
            $body .= strip_tags(str_replace(array('<br />','<br/>'), "\n", $client_body));
            
            $mail->setBody($body);
            
            $sent = $mail->Send();
        }
        if ($sent) $app->redirect('/thanks.html', '');
        }
}