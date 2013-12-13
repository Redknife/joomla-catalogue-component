<?php
defined('_JEXEC') or die;

class CatalogueControllerOrder extends JControllerForm
{
    
    public function save()
    {
        $data = $this->input->get('jform', array(), 'post', 'array');
        $model = $this->getModel();
        $form = $model->getForm();
        
        
        if ($model->save($data))
            $this->setRedirect(JRoute::_('index.php?option=com_catalogue'), 'Ваш заказ отправлен');
        
        
    }
}