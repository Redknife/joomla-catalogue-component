<?php


defined('_JEXEC') or die;

require_once(dirname(__FILE__).DS.'helper.php');

class CatalogueController extends JControllerLegacy
{
	
	public function order()
	{
		$app = JFactory::getApplication();

		$cart = $app->getUserState('com_catalogue.cart');
		
		$order = JRequest::getVar('orderId', 0, 'get', 'int');
		
		
		if (!CatalogueHelper::inCart($order))
		{
			$data = unserialize($cart);
			if (!is_array($data))
			{
				$data = array();
			}
		
			array_push($data, $order);
			$data = array_unique($data);
			$order = serialize($data);
			$app->setUserState('com_catalogue.cart', $order);
			
			echo CatalogueHelper::getCart();	
		}
		
		return false;
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
	
	public function remove()
	{
		$app = JFactory::getApplication();

		$cart = $app->getUserState('com_catalogue.cart');
		
		$order = JRequest::getVar('orderId', 0, 'get', 'int');
		
		if (CatalogueHelper::inCart($order))
		{
			$data = unserialize($cart);
			if (!is_array($data))
			{
				$data = array();
			}
			
			unset($data[array_search($order,$data)]);
			$order = serialize($data);
			$app->setUserState('com_catalogue.cart', $order);
		}
		return false;
	}
	
	public function send()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		
		$data = JRequest::getVar('cart');
		$name = JRequest::getVar('name', '');
		$address = JRequest::getVar('address', '');
		$phone = JRequest::getVar('phone', '');
		
		if(empty($data))
		{
			$this->setRedirect('http://mix-74.ru/cart.html', 'Корзина пуста!', 'error');
			return;
		}
		
		if(!$name || !$address || !$phone)
		{
			$this->setRedirect('http://mix-74.ru/cart.html', 'Заполните, пожалуйста все поля!', 'error');
			return;
		}
		
		
				
		$mail = JFactory::getMailer();
		$mail->addRecipient('order@mix-74.ru');
		$mail->addReplyTo(array('admin@saity74.ru', 'Буянов Данила'));
		$mail->setSender(array('info@mix-74.ru', 'Кафе MIX'));
		$mail->setSubject('mix-74.ru'.': '.'Ваш заказ принят!');
		
		$body .= "Здравствуйте, ".$name."! \n\n";
		$body .= "Контактный телефон: ".$phone."\n";
		$body .= "Адрес доставки: ".$address."\n\n";
		$body .= "Ваш заказ:\n";
		
		$body .= "-----------------------------------------------------------\n";
		
		$ids = array_keys($data);
		$items = CatalogueHelper::getItemsByIds($ids);
		
		foreach ($items as $item)
		{
			$count = $data[$item->id]['count'];
			$rowSumm = $count*$item->price;
			$body .= "\t".$item->name."\t\t".$data[$item->id]['count']."шт.\t\t".$rowSumm."р.\n";
		}
		
		$body .= "-----------------------------------------------------------\n";
		
		
		$mail->setBody($body);
		//$sent = $mail->Send();
		
		$this->setRedirect('http://mix-74.ru', 'Спасибо за заказ! Мы Вам перезвоним в ближайшее время.');
	}
}
