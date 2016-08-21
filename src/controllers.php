<?php

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Email;

$app->match('/', function (Request $request) use ($app) {


    /** @var \Symfony\Component\Form\Form $form */
    $form = $app['form.factory']->createBuilder(FormType::class, ['email' => ''])
        ->add('email', TextType::class, array(
            'constraints' => new Email(),
            'attr' => array('class' => 'form-control', 'placeholder' => 'your@email.com')
        ))
        ->getForm();
    if ($form->isValid()) {
        $data = $form->getData();
        var_dump($data);
    }
    $form->handleRequest($request);
    return $app['twig']->render('index.html.twig', ['form' => $form->createView(), 'slack' => $app['slack']]);
})->bind('homepage');

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/' . $code . '.html.twig',
        'errors/' . substr($code, 0, 2) . 'x.html.twig',
        'errors/' . substr($code, 0, 1) . 'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
