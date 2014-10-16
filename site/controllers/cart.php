<?php
defined('_JEXEC') or die;

require_once(JPATH_COMPONENT_SITE . DS . 'helper.php');
require_once(JPATH_COMPONENT_SITE . DS . 'helpers' . DS . 'cart.php');

class CatalogueControllerCart extends JControllerForm
{

    public function add()
    {
        $app = JFactory::getApplication();

        $cart_items = $app->getUserState('com_catalogue.cart');
        $item_id = $this->input->get('id', 0, 'get', 'int');
        $item_price = $this->input->get('price', 0, 'get', 'int');
        $item_count = (int)$this->input->get('count', 0, 'get', 'int');

        $cart_items = unserialize($cart_items);

        if (!is_array($cart_items)) {
            $cart_items = array();
        }
        if ($item_id) {
            if (array_key_exists($item_id, $cart_items)) {
                if (!$item_count) {
                    $cart_items[$item_id]['count'] = $cart_items[$item_id]['count'] + $item_count;
                } else {
                    $cart_items[$item_id]['count'] = isset($cart_items[$item_id]['count']) ? $cart_items[$item_id]['count']++ : 1;
                }
            } else {
                if ($item_count) {
                    $cart_items[$item_id] = array('count' => $item_count, 'price' => $item_price);
                } else {
                    $cart_items[$item_id] = array('count' => 1, 'price' => $item_price);
                }
            }
        }

        $data = serialize($cart_items);
        $app->setUserState('com_catalogue.cart', $data);

        echo json_encode($cart_items);
        // $this->setRedirect($_SERVER['HTTP_REFERER']);
    }

    public function remove()
    {
        $app = JFactory::getApplication();
        $data = $app->getUserState('com_catalogue.cart');
        $item_id = $this->input->get('id', 0, 'get', 'int');

        if (CatalogueHelperCart::inCart($item_id)) {
            $cart_items = unserialize($data);
            unset($cart_items[$item_id]);
            $data = serialize($cart_items);
            $app->setUserState('com_catalogue.cart', $data);
            echo json_encode($cart_items);
            // $this->setRedirect($_SERVER['HTTP_REFERER']);
        } else {
            return false;
        }
    }

    public function update()
    {
        $app = JFactory::getApplication();

        $cart_items = $app->getUserState('com_catalogue.cart');
        $item_id = $this->input->get('id', 0, 'get', 'int');
        $item_count = $this->input->get('count', 0, 'get', 'int');

        $cart_items = unserialize($cart_items);

        if (!is_array($cart_items)) {
            return false;
        }
        if ($item_id) {
            if (array_key_exists($item_id, $cart_items)) {
                if (isset($item_count)) {
                    $cart_items[$item_id]['count'] = $item_count;
                }
            }
        }

        $data = serialize($cart_items);
        $app->setUserState('com_catalogue.cart', $data);

        echo json_encode($cart_items);
        // $this->setRedirect($_SERVER['HTTP_REFERER']);
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

        $mail = JFactory::getMailer();
        $mail->IsHTML(true);
        $mailfrom = $app->getCfg('mailfrom');
        $fromname = $app->getCfg('fromname');
        $sitename = $app->getCfg('sitename');

        // check..
        if ($user_email) {
            if (!preg_match("~^([a-z0-9_\-\.])+@([a-z0-9_\-\.])+\.([a-z0-9])+$~i", $user_email)) {
                $app->redirect('/', JText::_('Некоректный Email'));
                return;
            }
        }

        // if(strlen($user_name) < 3){
        //     $app->redirect('/', JText::_('Некоректное имя'));
        //   return;
        // }
        // ..check

        // get order items..
        $body_items = '<table width="550px" cellspacing="5"><tbody><tr><td>Наименование</td><td>Цена</td></tr>';

        $item = CatalogueHelper::getItemById($itemId);
        if ($item) {
            $body_items .= '<tr>';
            $body_items .= '<td>' . $item->item_name . '</td>';
            $body_items .= '<td>' . $item->price . '</td>';
            $body_items .= '</tr>';
        }
        $body_items .= '</table>';
        // ...get order items

        $mail->addRecipient($manager_email);
        $mail->addReplyTo(array($manager_email, $user_name));
        $mail->setSender(array($mailfrom, $fromname));
        $mail->setSubject($manager_subject);

        $body = strip_tags(str_replace(array('<br />', '<br/>'), "\n", $manager_intro) . "\n");

        $body .= '<br/><br/>';

        if ($user_name)
            $body .= 'ФИО отправителя: ' . $user_name . "<br/>";

        if ($user_phone)
            $body .= 'Телефон: ' . $user_phone . "<br/>";

        if ($user_email)
            $body .= 'E-mail: ' . $user_email . "<br/>";

        if ($user_msg)
            $body .= 'Сообщение: ' . $user_msg . "<br/>";

        $body .= strip_tags(str_replace(array('<br />', '<br/>'), "\n", $manager_body));

        $body .= '<br/>' . $body_items;
        $body .= 'Страница отправки: ' . $_SERVER['HTTP_REFERER'];
        $mail->setBody($body);
        $sent = $mail->Send();

        if ($user_email) {
            $mail = JFactory::getMailer();
            $mail->addRecipient($user_email);
            if (!$user_name)
                $user_name = $user_email;
            $mail->addReplyTo(array($user_email, $user_name));
            $mail->setSender(array($mailfrom, $fromname));
            $mail->setSubject($client_subject);

            $body = strip_tags(str_replace(array('<br />', '<br/>'), "\n", $client_intro) . "\n");

            $body .= strip_tags(str_replace(array('<br />', '<br/>'), "\n", $client_mail_sign));

            $mail->setBody($body);

            $sent = $mail->Send();
        }
        $menu = $app->getMenu();
        $page_redirect = $menu->getItem($params->get('cart_form_redirect'))->route;
        if ($sent) $app->redirect($page_redirect, '');
    }

    public function order()
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
        $user_address = $this->input->get('address', '', 'string');
        $user_msg = $this->input->get('desc', '', 'string');

        $cart_items = CatalogueHelperCart::getCartItems();


        $mail = JFactory::getMailer();
        $mail->IsHTML(true);
        $mailfrom = $app->getCfg('mailfrom');
        $fromname = $app->getCfg('fromname');
        $sitename = $app->getCfg('sitename');

        // check..
        if ($user_email) {
            if (!preg_match("~^([a-z0-9_\-\.])+@([a-z0-9_\-\.])+\.([a-z0-9])+$~i", $user_email)) {
                $app->redirect('/', JText::_('Некоректный Email'));
                return;
            }
        }

        // if(strlen($user_name) < 3){
        //     $app->redirect('/', JText::_('Некоректное имя'));
        //   return;
        // }
        // ..check

        // get order items..
        if (!empty($cart_items)) {
            $body_items = '<table width="550px" cellspacing="5"><tbody><tr><td>Наименование</td><td>Цена</td><td>Кол-во</td></tr>';
            foreach ($cart_items as $item) {
                $body_items .= '<tr>';
                $body_items .= '<td>' . $item->item_name . '</td>';
                $body_items .= '<td>' . $item->price . '</td>';
                $body_items .= '<td>' . $item->cart_info['count'] . '</td>';
                $body_items .= '</tr>';
            }
            $body_items .= '</table>';
        }
        // ...get order items

        $mail->addRecipient($manager_email);
        $mail->addReplyTo(array($manager_email, $user_name));
        $mail->setSender(array($mailfrom, $fromname));
        $mail->setSubject($manager_subject);

        $body = strip_tags(str_replace(array('<br />', '<br/>'), "\n", $manager_intro) . "\n");

        $body .= '<br/><br/>';

        if ($user_name)
            $body .= 'ФИО отправителя: ' . $user_name . "<br/>";

        if ($user_phone)
            $body .= 'Телефон: ' . $user_phone . "<br/>";

        if ($user_email)
            $body .= 'E-mail: ' . $user_email . "<br/>";

        if ($user_address)
            $body .= 'Адрес: ' . $user_address . "<br/>";

        if ($user_msg)
            $body .= 'Сообщение: ' . $user_msg . "<br/>";

        $body .= strip_tags(str_replace(array('<br />', '<br/>'), "\n", $manager_body));

        $body .= '<br/>' . $body_items;
        $body .= '<br/>Страница отправки: ' . $_SERVER['HTTP_REFERER'];
        $mail->setBody($body);
        $sent = $mail->Send();

        if ($user_email) {
            $mail = JFactory::getMailer();
            $mail->addRecipient($user_email);
            if (!$user_name)
                $user_name = $user_email;
            $mail->addReplyTo(array($user_email, $user_name));
            $mail->setSender(array($mailfrom, $fromname));
            $mail->setSubject($client_subject);

            $body = strip_tags(str_replace(array('<br />', '<br/>'), "\n", $client_intro) . "\n");

            $body .= strip_tags(str_replace(array('<br />', '<br/>'), "\n", $client_mail_sign));

            $mail->setBody($body);

            $sent = $mail->Send();
        }

        $app->setUserState('com_catalogue.cart', serialize(array()));

        $menu = $app->getMenu();
        $page_redirect = $menu->getItem($params->get('cart_form_redirect'))->route;
        if ($sent) $app->redirect($page_redirect, '');
    }
}