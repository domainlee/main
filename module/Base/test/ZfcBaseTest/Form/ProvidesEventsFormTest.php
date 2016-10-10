<?php
namespace BaseTest\Form;

use PHPUnit_Framework_TestCase;
use Base\Form\ProvidesEventsForm;
use Zend\EventManager\EventManager;

class ProvidesEventsFormTest extends PHPUnit_Framework_TestCase
{
    public function setup()
    {
        $this->form = new ProvidesEventsForm;
    }

    public function testGetEventManagerSetsDefaultIdentifiers()
    {
        $em = $this->form->getEventManager();
        $this->assertInstanceOf('Zend\EventManager\EventManager', $em);
        $this->assertContains('Base\Form\ProvidesEventsForm', $em->getIdentifiers());
    }

    public function testSetEventManagerWorks()
    {
        $em = new EventManager();
        $this->form->setEventManager($em);
        $this->assertSame($this->form->getEventManager(), $em);
    }
}

