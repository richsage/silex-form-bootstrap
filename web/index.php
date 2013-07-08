<?php

use Silex\Provider\FormServiceProvider;
use Silex\Provider\TwigServiceProvider;

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application(array("debug" => true));
$app->register(new FormServiceProvider());
$app->register(new TwigServiceProvider(), array(
    'twig.path' => array(
        __DIR__.'/../core-views',
        __DIR__.'/../custom-views',
    )
));
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.messages' => array(),
));

// Routing
$app->get('/', function() use($app) {

    $data = array();

    $form = $app["form.factory"]->createBuilder("form", $data)
        ->add("text_field", "text", array("required" => false))
        ->add("number_field", "number", array("required" => false))
        ->add("select_field", "choice", array("choices" => array(1 => "Foo", 2 => "Bar"), "expanded" => false, "multiple" => false))
        ->add("radio_field", "choice", array("choices" => array(1 => "Foo", 2 => "Bar"), "expanded" => true, "multiple" => false))
        ->add("multi_select_field", "choice", array("choices" => array(1 => "Foo", 2 => "Bar"), "expanded" => false, "multiple" => true))
        ->add("checkbox_field", "choice", array("choices" => array(1 => "Foo", 2 => "Bar"), "expanded" => true, "multiple" => true))
        ->getForm()
    ;

    return $app["twig"]->render("index.html.twig", array("form" => $form->createView()));
});

$app->run();
