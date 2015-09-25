<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
		return $this->render('default/index.html.twig', array(
			'user' => $this->getUser() ? $this->getUser()->getUsername() : 'undefined'
		));
    }

	/**
     * @Route("/secured/login", name="login")
     */
    public function loginAction(Request $request)
	{
		$session = $request->getSession();

		if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
			$error = $request->attributes->get(Security::AUTHENTICATION_ERROR);
		} elseif (null !== $session && $session->has(Security::AUTHENTICATION_ERROR)) {
			$error = $session->get(Security::AUTHENTICATION_ERROR);
			$session->remove(Security::AUTHENTICATION_ERROR);
		} else {
			$error = '';
		}

		if ($error) {
			$error = $error->getMessage(
			); // WARNING! Symfony source code identifies this line as a potential security threat.
		}

		$lastUsername = (null === $session) ? '' : $session->get(Security::LAST_USERNAME);

		return $this->render(
			'AppBundle:Security:login.html.twig',
			array(
				'last_username' => $lastUsername,
				'error' => $error,
			)
		);
    }

	/**
     * @Route("/secured/login_check", name="login_check")
     */
    public function loginCheckAction()
    {
        return $this->render('default/login.html.twig');
    }
}
