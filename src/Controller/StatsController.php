<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Role;
use App\Entity\User;
use App\Repository\CourseRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class StatsController extends AbstractController
{
    #[Route('/stats', name: 'app_stats')]
    public function index(ChartBuilderInterface $chartBuilder, CourseRepository $courseRepository, UserRepository $userRepository): Response
    {
        $courses = $courseRepository->findAll();
        $users = $userRepository->findAll();
        return $this->render('stats/index.html.twig', [
            'course_rate_chart' => $this->getRateChart($chartBuilder, $courses),
            'class_average_chart' => $this->getCourseAverageChart($chartBuilder, $courses),
            'student_performance_chart' => $this->getStudentPerformanceChart($chartBuilder, $users),
        ]);
    }

    private function getRateChart(ChartBuilderInterface $chartBuilder, Array $courses): Chart
    {
        return $chartBuilder->createChart(Chart::TYPE_PIE)
            ->setData([
                'labels' => array_map(function (Course $course) {
                    return $course->getTitle();
                }, $courses),
                'datasets' => [
                    [
                        'label' => 'Course rate',
                        'backgroundColor' => array_map(function (Course $course) {
                            return '#'.dechex(rand(0x000000, 0xFFFFFF));
                        }, $courses),
                        'borderColor' => 'rgb(255, 99, 132)',
                        'data' => array_map(function (Course $course) {
                            return $course->getAverageRate();
                        }, $courses),
                    ],
                ],
            ])
            ->setOptions([
                'responsive' => true,
                'maintainAspectRatio' => false
            ]);
    }

    private function getCourseAverageChart(ChartBuilderInterface $chartBuilder, Array $courses): Chart
    {
        return $chartBuilder->createChart(Chart::TYPE_BAR)
            ->setData([
                'labels' => array_map(function (Course $course) {
                    return $course->getTitle();
                }, $courses),
                'datasets' => [
                    [
                        'label' => 'Class average',
                        'backgroundColor' => array_map(function (Course $course) {
                            return '#'.dechex(rand(0x000000, 0xFFFFFF));
                        }, $courses),
                        'borderColor' => 'rgb(255, 99, 132)',
                        'data' => array_map(function (Course $course) {
                            return $course->getClassAverage();
                        }, $courses),
                    ],
                ],
            ])
            ->setOptions([
                'responsive' => true,
                'maintainAspectRatio' => false
            ]);
    }

    private function getStudentPerformanceChart(ChartBuilderInterface $chartBuilder, Array $users) : Chart
    {
        $students = array_values(
            array_filter($users, function (User $user) {
                return $user->hasRole(Role::Student->value);
            })
        );
        return $chartBuilder->createChart(Chart::TYPE_BAR)
            ->setData([
                'labels' => array_map(function (User $student) {
                    return $student->getEmail();
                }, $students),
                'datasets' => [
                    [
                        'label' => 'Student performance',
                        'backgroundColor' => array_map(function (User $student) {
                            return '#'.dechex(rand(0x000000, 0xFFFFFF));
                        }, $students),
                        'borderColor' => 'rgb(255, 99, 132)',
                        'data' => array_map(function (User $student) {
                            return $student->getGpa();
                        }, $students),
                    ],
                ],
            ])
            ->setOptions([
                'responsive' => true,
                'maintainAspectRatio' => false
            ]);
    }
}
