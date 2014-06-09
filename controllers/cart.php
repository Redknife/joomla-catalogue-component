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
        $manager_email = $params->get('cart_manager_email');
        $manager_subject = $params->get('cart_manager_email_subject');
        $manager_intro = $params->get('cart_manager_introtext');

        $client_subject = $params->get('cart_client_email_subject');
        $client_intro = $params->get('cart_client_introtext');
        $client_mail_sign = $params->get('cart_email_sign');

        $user_name = $this->input->get('name', '', 'string');
        $user_phone = $this->input->get('phone', '', 'string');
        $user_email = $this->input->get('email', '', 'string');
        $user_msg = $this->input->get('desc', '', 'string');
        $itemId = $this->input->get('id', '', 'string');

        $app = JFactory::getApplication();
        $mail = JFactory::getMailer();
        $mail->IsHTML(true);
        $mailfrom   = $app->getCfg('mailfrom');
        $fromname   = $app->getCfg('fromname');
        $sitename   = $app->getCfg('sitename');

        // check..
        if ($user_email){
            if(!preg_match("~^([a-z0-9_\-\.])+@([a-z0-9_\-\.])+\.([a-z0-9])+$~i", $user_email)){
                    $app->redirect('/', JText::_('Некоректный Email'));
              return;
            }
        }

        if(strlen($user_name) < 3){
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
        $mail->addReplyTo(array($manager_email, $user_name));
        $mail->setSender(array($mailfrom, $fromname));
        $mail->setSubject($manager_subject);
        
        $body = strip_tags(str_replace(array('<br />','<br/>'), "\n", $manager_intro)."\n");

        $body .= '<br/><br/>';
        
        if ($user_name)
            $body .= 'ФИО отправителя: '.$user_name."<br/>";
        
        if ($user_phone)
            $body .= 'Телефон: '.$user_phone."<br/>";
        
        if ($user_email)
            $body .= 'E-mail: '.$user_email."<br/>";

        if ($user_msg)
            $body .= 'Сообщение: '.$user_msg."<br/>";
        
        $body .= strip_tags(str_replace(array('<br />','<br/>'), "\n", $manager_body));

        $body .= '<br/>'.$body_items;
        $body .= 'Страница отправки: '.$_SERVER['HTTP_REFERER'];
        $mail->setBody($body);
        $sent = $mail->Send();
        
        if ($user_email)
        {
            $mail = JFactory::getMailer();
            $mail->addRecipient($user_email);
            if (!$user_name)
                $user_name = $user_email;
            $mail->addReplyTo(array($user_email, $user_name));
            $mail->setSender(array($mailfrom, $fromname));
            $mail->setSubject($client_subject);
            
            $body = strip_tags(str_replace(array('<br />','<br/>'), "\n", $client_intro)."\n");
            
            $body .= strip_tags(str_replace(array('<br />','<br/>'), "\n", $client_mail_sign));
            
            $mail->setBody($body);
            
            $sent = $mail->Send();
        }
        $menu = $app->getMenu();
        $page_redirect = $menu->getItem($params->get('cart_form_redirect'))->route;
        if ($sent) $app->redirect($page_redirect, '');
    }
}