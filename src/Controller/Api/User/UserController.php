<?php

namespace App\Controller\Api\User;

use App\Entity\User;
use App\Exception\JsonHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Security;

/**
 * Class UserController.
 *
 * @Route("api/user")
 */
class UserController extends Controller
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(SerializerInterface $serializer, UserPasswordEncoderInterface $passwordEncoder, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->passwordEncoder = $passwordEncoder;
        $this->validator = $validator;
    }

    /**
     * @Route("/registration", methods={"POST"}, name="api_user_registration")
     */
    public function registrationUserAction(Request $request)
    {
        if (!$content = $request->getContent()) {
            throw new JsonHttpException(400, 'Bad Request');
        }
        /* @var User $user */
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');
        $user
          ->setRoles(['ROLE_READER'])
          ->setApiToken(Uuid::uuid4())
          ->setPassword($this->passwordEncoder->encodePassword($user, $user->getPlainPassword()))
        ;

        $errors = $this->validator->validate($user);
        if (count($errors)) {
            throw new JsonHttpException(400, (string) $errors->get(0)->getPropertyPath().': '.(string) $errors->get(0)->getMessage());
        }
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        return $this->json($user);
    }

    /**
     * @Route("/login", methods={"POST"}, name="api_user_login")
     */
    public function loginUserAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if (!$content = $request->getContent()) {
            throw new JsonHttpException(400, 'Bad Request');
        }
        /* @var User $user */
        $user = $this->serializer->deserialize($request->getContent(), User::class, 'json');
        $plainPassword = $user->getPlainPassword();
        if (empty($plainPassword) or empty($user->getEmail())) {
            throw new JsonHttpException(400, 'Bad Request');
        }
        $user = $this->getDoctrine()
          ->getManager()
          ->getRepository(User::class)
          ->findOneBy(['email' => $user->getEmail()]);
        if (!$user) {
            throw new JsonHttpException(400, 'Authentication error');
        }
        if ($passwordEncoder->isPasswordValid($user, $plainPassword)) {
            $user->setApiToken(Uuid::uuid4());
            $this->getDoctrine()->getManager()->flush();

            return $this->json(['user' => $user]);
        }

        throw new JsonHttpException(400, 'Incorrect password');
    }

    /**
     * @Route("/profile", methods={"GET"}, name="api_user_profile")
     */
    public function profileUserAction(Request $request)
    {
        $apiToken = $request->headers->get('x-api-key');

        /* @var User $user */
        $user = $this->getDoctrine()
          ->getManager()
          ->getRepository(User::class)
          ->findOneBy(['apiToken' => $apiToken]);
        if (!$user) {
            throw new JsonHttpException(400, 'Authentication error');
        }

        return $this->json(['user' => $user]);
    }
}
