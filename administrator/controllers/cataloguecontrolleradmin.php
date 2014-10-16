<?php

defined('_JEXEC') or die;

class CatalogueControllerAdmin extends JControllerAdmin
{

    public function __construct($config = array())
    {
        parent::__construct($config);

        $this->registerTask('unfeatured', 'featured');
    }


    function featured()
    {
        // Check for request forgeries
        JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));

        $session = JFactory::getSession();
        $registry = $session->get('registry');

        // Get items to publish from the request.
        $cid = $this->input->getVar('cid', array(), 'post', 'array');
        $data = array('featured' => 1, 'unfeatured' => 0);
        $task = $this->getTask();
        $value = JArrayHelper::getValue($data, $task, 0, 'int');

        if (empty($cid)) {
            JError::raiseWarning(500, JText::_($this->text_prefix . '_NO_ITEM_SELECTED'));
        } else {
            // Get the model.
            $model = $this->getModel();

            // Make sure the item ids are integers
            JArrayHelper::toInteger($cid);

            // Publish the items.
            if (!$model->featured($cid, $value)) {
                JError::raiseWarning(500, $model->getError());
            } else {
                if ($value == 1) {
                    $ntext = $this->text_prefix . '_N_ITEMS_FEATURED';
                } else if ($value == 0) {
                    $ntext = $this->text_prefix . '_N_ITEMS_UNFEATURED';
                }
                $this->setMessage(JText::plural($ntext, count($cid)));
            }
        }
        $extension = JRequest::getCmd('extension');
        $extensionURL = ($extension) ? '&extension=' . JRequest::getCmd('extension') : '';

        $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list . $extensionURL, false));
    }

}
