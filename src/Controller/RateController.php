<?php

namespace App\Controller;

use App\Entity\CourseRate;
use App\Entity\Role;
use App\Repository\CourseRateRepository;
use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/{_locale}/rate')]
class RateController extends AbstractController
{
    #[Route('/new', name: 'app_rate_new', methods: ["POST"])]
    #[IsGranted(Role::RaterCommenter->value)]
    public function rate(Request $request, CourseRepository $courseRepository, CourseRateRepository $courseRateRepository): JsonResponse
    {
        $courseId = $request->request->get('courseId');
        $courseRate = $courseRateRepository->findByCourseRater($courseId, $this->getUser()->getId());
        if ($courseRate == null)
            $courseRate = new CourseRate();
        $courseRate->setRater($this->getUser());
        $courseRate->setCourse($courseRepository->find($courseId));
        $courseRate->setRate($request->request->get('rate'));

        $courseRateRepository->save($courseRate, true);

        return new JsonResponse($courseRate->getId(), 200);
    }
}
