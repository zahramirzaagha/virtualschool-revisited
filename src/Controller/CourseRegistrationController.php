<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\CourseRegistration;
use App\Entity\Role;
use App\Form\CourseRegistrationEditType;
use App\Form\CourseRegistrationType;
use App\Repository\CourseRegistrationRepository;
use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/{_locale}/course-registration')]
class CourseRegistrationController extends AbstractController
{
    #[Route('/student/{studentId}', name: 'app_course_registration_by_student')]
    #[IsGranted(Role::Parent->value)]
    public function listByStudent(int $studentId, CourseRegistrationRepository $courseRegistrationRepository): Response
    {
        return $this->render('course_registration/index_wrapped.html.twig', [
            'course_registrations' => $courseRegistrationRepository->findByStudentId($studentId),
        ]);
    }

    #[Route('/new/{courseId}', name: 'app_course_registration_new', methods: ['GET', 'POST'])]
    #[IsGranted(Role::Student->value)]
    public function new(Request $request, int $courseId, CourseRepository $courseRepository, CourseRegistrationRepository $courseRegistrationRepository): Response
    {
        $courseRegistration = new CourseRegistration();
        $courseRegistration->setCourse($courseRepository->find($courseId));
        $courseRegistration->setStudent($this->getUser());

        $form = $this->createForm(CourseRegistrationType::class, $courseRegistration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $courseRegistrationRepository->save($courseRegistration, true);

            return $this->redirectToRoute('app_course_show', [
                'id' => $courseRegistration->getCourse()->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('course_registration/new.html.twig', [
            'course_registration' => $courseRegistration,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_course_registration_show', methods: ['GET'])]
    public function show(CourseRegistration $courseRegistration): Response
    {
        return $this->render('course_registration/show.html.twig', [
            'course_registration' => $courseRegistration,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_course_registration_edit', methods: ['GET', 'POST'])]
    #[IsGranted(Role::Teacher->value)]
    public function edit(Request $request, CourseRegistration $courseRegistration, CourseRegistrationRepository $courseRegistrationRepository): Response
    {
        $form = $this->createForm(CourseRegistrationEditType::class, $courseRegistration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $courseRegistrationRepository->save($courseRegistration, true);

            return $this->redirectToRoute('app_course_show', [
                'id' => $courseRegistration->getCourse()->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('course_registration/edit.html.twig', [
            'course_registration' => $courseRegistration,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_course_registration_delete', methods: ['POST'])]
    #[IsGranted(Role::Student->value)]
    public function delete(Request $request, CourseRegistration $courseRegistration, CourseRegistrationRepository $courseRegistrationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$courseRegistration->getId(), $request->request->get('_token'))) {
            $courseRegistrationRepository->remove($courseRegistration, true);
        }

        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }
}
