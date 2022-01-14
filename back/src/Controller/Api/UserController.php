<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\User;
use App\Form\UserType;
use Nelmio\ApiDocBundle\Annotation as ApiDoc;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class UserController extends AbstractController
{
    /**
     * Get an user.
     *
     * @ApiDoc\Areas({"default"})
     *
     * @SWG\Tag(name="Users")
     *
     * @SWG\Parameter(
     *     name="userId",
     *     in="path",
     *     description="User ID",
     *     type="integer"
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returned when user was found",
     *     @SWG\Schema(ref="#/definitions/User")
     * )
     * @SWG\Response(
     *     response=403,
     *     description="Returned when user is not authorized to get an user"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returned when user was not found"
     * )
     */
    #[Route(path: '/{userId}', name: 'read', methods: ['GET'], requirements: ['userId' => '\d+'])]
    public function readUser(int $userId): JsonResponse
    {
        $user = $this->getDoctrine()->getRepository(User::class)
            ->find($userId)
        ;
        if (!$user instanceof User) {
            throw $this->createNotFoundException('User not found');
        }

        return $this->json($user, Response::HTTP_OK, [], ['groups' => ['public']]);
    }

    /**
     * Get current user.
     *
     * @ApiDoc\Areas({"default"})
     *
     * @SWG\Tag(name="Users")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returned when user was found",
     *     @SWG\Schema(ref="#/definitions/CurrentUser")
     * )
     * @SWG\Response(
     *     response=403,
     *     description="Returned when user is not authorized to get an user"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returned when user was not found"
     * )
     */
    #[Route(path: '/current', name: 'current', methods: ['GET'])]
    public function currentUser(): JsonResponse
    {
        $user = $this->getUser();

        return $this->json($user, Response::HTTP_OK, [], ['groups' => ['public', 'private']]);
    }

    /**
     * Update an user account data.
     *
     * @SWG\Tag(name="Users")
     * @ApiDoc\Operation(
     *     security={{"OAuth2Password": {"superadmin"}}},
     *     consumes={"application/x-www-form-urlencoded"}
     * )
     *
     * @SWG\Parameter(
     *     name="userId",
     *     in="path",
     *     description="User ID",
     *     type="integer"
     * )
     *
     * @SWG\Parameter(
     *     name="email",
     *     in="formData",
     *     description="User email address",
     *     type="string"
     * )
     * @SWG\Parameter(
     *     name="nickname",
     *     in="formData",
     *     description="User nickname",
     *     type="string"
     * )
     * @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     description="User password",
     *     type="string"
     * )
     *
     * @SWG\Response(
     *     response=200,
     *     description="Returned when user was updated",
     *     @SWG\Schema(ref="#/definitions/CurrentUser")
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Returned when parameters are invalid"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returned when user was not found"
     * )
     */
    #[Route(path: '/{userId}', name: 'update', methods: ['PUT'])]
    public function updateUser(Request $request, int $userId): JsonResponse
    {
        $user = $this->getDoctrine()->getRepository(User::class)
            ->find($userId)
        ;
        if (!$user instanceof User) {
            throw $this->createNotFoundException('User not found');
        }

        $form = $this->createForm(UserType::class, $user);
        $form->submit($request->request->all(), false);
        if (!$form->isValid()) {
            return $this->json($form, Response::HTTP_BAD_REQUEST);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->json($user);
    }

    /**
     * Delete an user account.
     *
     * @SWG\Tag(name="Users")
     *
     * @SWG\Parameter(
     *     name="userId",
     *     in="path",
     *     description="User ID",
     *     type="integer"
     * )
     *
     * @SWG\Response(
     *     response=204,
     *     description="Returned when user was found and deleted"
     * )
     * @SWG\Response(
     *     response=403,
     *     description="Returned when user is not authorized to delete this user"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Returned when user was not found"
     * )
     */
    #[Route(path: '/{userId}', name: 'delete', methods: ['DELETE'])]
    public function deleteUser(int $userId): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)
            ->find($userId)
        ;
        if (!$user instanceof User) {
            throw $this->createNotFoundException('User not found');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
