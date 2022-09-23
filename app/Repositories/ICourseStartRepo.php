<?php

namespace App\Repositories;

use App\Models\CourseStart;
use Illuminate\Support\Collection;

interface ICourseStartRepo {
    public function getCourseStartForCourseAndUser(string $courseSlug, int $userId): CourseStart;
    public function updateCourseStart(array $updateParams, CourseStart $courseStart): CourseStart;
    public function getCourseNotes($courseId);
    public function getCourseReviewMarks($courseId): Collection;
    public function getCourseReviews($courseId): Collection;
}
