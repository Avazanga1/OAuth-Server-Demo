<?php
/**
 * Created by PhpStorm.
 * User: Karol
 * Date: 2015-04-07
 * Time: 17:13
 */

namespace Ava\Bundle\SecurityBundle\Controller;

use OAuth2\OAuth2;
use OAuth2\Tests\Fixtures\OAuth2StorageStub;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class SecurityController extends Controller
{
	public function meAction(Request $request)
	{
		$user = $this->container->get('security.context')->getToken()->getUser();
		// client
		/*$tokenManager = $this->container->get('fos_oauth_server.access_token_manager.default');
		$accessToken = $tokenManager->findTokenByToken(
			$this->container->get('security.context')->getToken()->getToken()
		);
		$client = $accessToken->getClient();
		$client->getName();*/

		$array = array();
		$array['id'] = $user->getId();
		$array['username'] = $user->getUsername();
		$array['email'] = $user->getEmail();


		$response = new Response(
			json_encode($array),
			Response::HTTP_OK,
			array('content-type' => 'application/json')
		);
		$response->setCharset('UTF-8');

		$response->prepare($request);
		$response->send();
	}

	public function usrAction(Request $request)
	{
		$array = array();
		$array['id'] = 123213;
		$array['username'] = 'ty';
		$array['email'] = 'glupol@glupszy.pl';
		$array['headers'] = $request->headers->all();

		$response = new Response(
			json_encode($array),
			Response::HTTP_OK,
			array('content-type' => 'application/json')
		);
		$response->setCharset('UTF-8');

		$response->prepare($request);
		$response->send();
		return;
	}

	public function meOptionsAction(Request $request)
	{
		$response = new Response(
			'Go Ahead!',
			Response::HTTP_OK
		);
		$response->setCharset('UTF-8');

		$response->prepare($request);
		$response->send();
		return;
	}

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

		// Add the following lines
		if ($session->has('_security.target_path')) {
			if (false !== strpos($session->get('_security.target_path'), $this->generateUrl('fos_oauth_server_authorize'))) {
				$session->set('_fos_oauth_server.ensure_logout', true);
			}
		}

		if ($error) {
			$error = $error->getMessage(); // WARNING! Symfony source code identifies this line as a potential security threat.
		}

		$lastUsername = (null === $session) ? '' : $session->get(Security::LAST_USERNAME);

		return $this->render(
			'AvaSecurityBundle:Security:login.html.twig',
			array(
				'last_username' => $lastUsername,
				'error' => $error,
			)
		);
	}

	public function loginCheckAction()
	{

	}
}