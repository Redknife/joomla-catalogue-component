<?php
defined('_JEXEC') or die;

class CatalogueControllerReview extends JControllerForm
{

    public function save($key = NULL, $urlVar = NULL)
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $app = JFactory::getApplication();
        $id = $this->input->get('id');

        $mailfrom = $app->getCfg('mailfrom');
        $fromname = $app->getCfg('fromname');
        $params = $app->getParams();
        $manager_email = $params->get('review_manager_email');

        $data = $this->input->get('jform', array(), 'post', 'array');

        $model = $this->getModel();
        $form = $model->getForm();
        $url_refer = $_SERVER['HTTP_REFERER'];

        if ($save_id = $model->save($data)) {
            $mail = JFactory::getMailer();
            $mail->addRecipient($manager_email);
            $mail->setSender(array($mailfrom, $fromname));
            $mail->setSubject('Новый отзыв на сайте');

            $body = 'Новый отзыв на товар ' . $url_refer . "\n\n";
            $body .= "ФИО: " . $data['item_review_fio'] . "\n\n";
            $body .= "Текст отзыва:\n";
            $body .= $data['item_review_text'] . "\n\n";
            $body .= "Рейтинг: " . $data['item_review_rate'] . "\n\n";


            $body .= 'Чтобы опубликовать отзыв перейдите в редактирование товара:' . "\n";
            $body .= JURI::base() . 'administrator/index.php?option=com_catalogue&view=item&layout=edit&id=' . $id;

            $body .= "\n\n" . 'Либо перейдите по ссылке:' . "\n";
            $body .= JURI::base() . 'index.php?option=com_catalogue&task=review.publish&id=' . $save_id;

            $mail->setBody($body);
            $sent = $mail->Send();

            $menu = $app->getMenu();
            $page_redirect = $menu->getItem($params->get('review_form_redirect'))->route;
            if ($sent) $app->redirect($page_redirect, '');
        }
    }

    public function publish()
    {
        $id = $this->input->get('id');
        $model = $this->getModel();
        $row = $model->getItem($id);
        $publish_data = array('id' => $row->id, 'published' => 1);
        $model->save($publish_data);

        $this->setRedirect(JRoute::_('index.php'), 'Отзыв опубликован!');
    }


}