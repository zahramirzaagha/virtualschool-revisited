<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\CourseRate;
use App\Entity\Role;
use App\Repository\CommentRepository;
use App\Repository\CourseRateRepository;
use App\Repository\CourseRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/{_locale}/comment')]
class CommentController extends AbstractController
{
    #[Route('/new', name: 'app_comment_new', methods: ["POST"])]
    #[IsGranted(Role::RaterCommenter->value)]
    public function comment(Request $request, CourseRepository $courseRepository, CommentRepository $commentRepository, SerializerInterface $serializer): JsonResponse
    {
        $courseId = $request->request->get('courseId');
        $comment = new Comment();
        $comment->setUser($this->getUser());
        $course = $courseRepository->find($courseId);
        $comment->setCourse($course);
        $comment->setText($request->request->get('text'));

        $commentRepository->save($comment, true);

        $data = $serializer->serialize(array(
            'comments' => array_map(function (Comment $comment) {
                return (object) array(
                    'username' => $comment->getUser()->getName(),
                    'text' => $comment->getText()
                );
            }, $course->getComments())
        ), JsonEncoder::FORMAT);
        return new JsonResponse($data, 200);
    }
}
