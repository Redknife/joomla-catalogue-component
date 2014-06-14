<?php


defined('_JEXEC') or die;

require_once(dirname(__FILE__).DS.'helper.php');

class CatalogueController extends JControllerLegacy
{
	public function search(){
 		$search = $this->input->get('search', '', 'string');

		parent::display();
    }
 
	public function addToFavorite()
	{
		$app = JFactory::getApplication();

		$favorite = $app->getUserState('com_catalogue.favorite');
		
		$order = JRequest::getVar('id', 0, 'get', 'int');
		
		if (!CatalogueHelper::isFavorite($order))
		{
			$data = unserialize($favorite);
			if (!is_array($data))
			{
				$data = array();
			}
		
			array_push($data, $order);
			$data = array_unique($data);
			$order = serialize($data);
			$app->setUserState('com_catalogue.favorite', $order);
			
			echo '1';
		}
		
		return false;
	}
	
	public function send()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

    $manager_email = 'info@saity74.ru';
    $manager_greeting = 'hello manager';
    $manager_subject = 'Заказ товаров';

    $name = $this->input->get('name', '', 'string');
    $phone = $this->input->get('phone', '', 'string');
    $email = $this->input->get('email', '', 'string');
    $desc = $this->input->get('desc', '', 'string');
    $client_subject = 'client subject';
    $client_body = 'Наш менеджер свяжется с вами';

    $client_greeting = 'Здравствуйте'.' '.$name;

    $app = JFactory::getApplication();
    $mail = JFactory::getMailer();

    $mail->IsHTML(true);

    $mailfrom	= $app->getCfg('mailfrom');
    $fromname	= $app->getCfg('fromname');
    $sitename	= $app->getCfg('sitename');

		// check..
    if(!preg_match("~^([a-z0-9_\-\.])+@([a-z0-9_\-\.])+\.([a-z0-9])+$~i", $email)){
			$app->redirect('/', JText::_('Некоректный Email'));
      return;
    }

    if(strlen($name) < 3){
    	$app->redirect('/', JText::_('Некоректное имя'));
      return;
    }
		// ..check

    // get order items..
    $body_items = '<table width="550px" cellspacing="5"><tbody><tr><td>Наименование</td><td>Обозначение</td><td>Количество</td></tr>';

    $items = CatalogueHelper::getCartItems();
    if($items){
      foreach ($items as $item){
        if( $item != end($items)){
				$body_items .= '<tr>';
				$item_name = $item->item_name;
				$item_art = $item->item_art;
				$count = $items[count($items)-1][$item->id];
				$body_items .= '<td>'.$item_name.'</td>'.'<td>'.$item_art.'</td>'.'<td>'.$count.'</td>';
				$body_items .= '</tr>';
        }
			}
		}

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
    if ($sent) $app->redirect('/', JText::_('Сообщение отправлено!'));
	}
}
