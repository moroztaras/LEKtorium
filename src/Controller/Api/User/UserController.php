<?php

namespace App\Controller\Api\User;

use App\Entity\User;
use App\Exception\JsonHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController.
 *
 * @Route("api")
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

    public function __construct(SerializerInterface $serializer, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->serializer = $serializer;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/registration", name="api_registration")
     * @Method({"POST"})
     */
    public function registrationAction(Request $request)
    {
        if (!$content = $request->getContent()) {
            throw new JsonHttpException(Response::HTTP_BAD_REQUEST, 'Bad Request');
        }
        /** @var User $user */
        $user = $this->serializer->deserialize($request->getContent(), User::class, JsonEncoder::FORMAT);

        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        return $this->json($user);
    }

       /**
        * @Route("/login", name="api_login", methods={"POST"})
        */
       public function loginAction(Request $request)
    {
        if (!$content = $request->getContent()) {
            throw new JsonHttpException(Response::HTTP_BAD_REQUEST, 'Bad Request');
        }
        $data = json_decode($content, true);
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email'=>$data['email']]);

        if($user instanceof User) {
            if($this->passwordEncoder->isPasswordValid($user,$data['password'])){
                return ($this->json(['user'=>$user]));
            }}

        throw new JsonHttpException(Response::HTTP_BAD_REQUEST, 'Bad Request');
    }

}
