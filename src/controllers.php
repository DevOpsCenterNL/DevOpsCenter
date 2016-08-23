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

    $avatars = [];


    foreach ($app['slack']->getInfo()['users'] as $user) {
        if ($user['is_bot'] == true) continue;
        if ($user['id'] == 'USLACKBOT') continue;
        $avatars[$user['name']] = $user['profile']['image_24'];
    }


    return $app['twig']->render('index.html.twig', ['form' => $form->createView(), 'slack' => $app['slack'], 'avatars' => $avatars]);
})->bind('homepage');

$app->get('/slack_invite', function (Request $request) use ($app) {
    $slack = $app['slack'];

    if (!$request->request->has('email')) {
        return new Response("No email address", 400);
    }
    $result = $slack->invite($request->request->get('email'));
    if ($result['ok'] != "true") {
        return new Response($result['error'], 400);
    } else {
        return new Response("ok", 200);
    }
})->bind('slack_invite')->method('POST');

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
