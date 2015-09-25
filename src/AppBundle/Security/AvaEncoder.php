<?php
/**
 * Created by PhpStorm.
 * User: Karol
 * Date: 2015-04-09
 * Time: 17:06
 */

namespace AppBundle\Security;


use Symfony\Component\Security\Core\Encoder\BasePasswordEncoder;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class AvaEncoder extends BasePasswordEncoder
{
	private $algorithm;
	private $encodeHashAsBase64;
	private $iterations;

	/**
	 * Constructor.
	 *
	 * @param string $algorithm          The digest algorithm to use
	 * @param bool   $encodeHashAsBase64 Whether to base64 encode the password hash
	 * @param int    $iterations         The number of iterations to use to stretch the password hash
	 */
	public function __construct($algorithm = 'sha512', $encodeHashAsBase64 = true, $iterations = 1)
	{
		$this->algorithm = $algorithm;
		$this->encodeHashAsBase64 = $encodeHashAsBase64;
		$this->iterations = $iterations;
	}

	/**
	 * {@inheritdoc}
	 */
	public function encodePassword($raw, $salt)
	{
		if ($this->isPasswordTooLong($raw)) {
			throw new BadCredentialsException('Invalid password.');
		}

		if (!in_array($this->algorithm, hash_algos(), true)) {
			throw new \LogicException(sprintf('The algorithm "%s" is not supported.', $this->algorithm));
		}

		$salted = $this->mergePasswordAndSalt($raw, $salt);
		$digest = hash($this->algorithm, $salted);

		// "stretch" hash
		for ($i = 1; $i < $this->iterations; $i++) {
			$digest = hash($this->algorithm, $digest.$salted);
		}

		return $this->encodeHashAsBase64 ? base64_encode($digest) : bin2hex($digest);
	}

	/**
	 * {@inheritdoc}
	 */
	public function isPasswordValid($encoded, $raw, $salt)
	{
		return !$this->isPasswordTooLong($raw) && $this->comparePasswords($encoded, $this->encodePassword($raw, $salt));
	}
}