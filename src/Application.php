<?php

namespace Application;

use Mvc\AbstractController;
use ArrayObject;

class Application {

    private $controllers = array();

    public function __construct() {
        $this->controllers = new ArrayObject();
    }

    public function registerController(AbstractController $controller) {
        $this->controllers->append($controller);
    }

    public function run() {
        $iterator = $this->controllers->getIterator();
        while ($iterator->valid()) {
            $controller = $iterator->current();
            $inspector = $controller->getInspector($controller);
            $parse = $inspector->parse()->getParse();
            array_map(array($this, 'map'), (array) $parse);
            $iterator->next();
        }
    }

    private function map($prop) {
        list($class, $method) = explode(':', $prop['action']);
        $object = new $class();
        $object->validate($prop['Inspector\Controller\Route'], $method);
    }
}
