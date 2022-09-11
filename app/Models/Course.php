<?php

namespace App\Models;

use App\Traits\CourseAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Translatable\HasTranslations;

class Course extends Model
{

    use HasTranslations, CourseAttributes;
    public $translatable = ['course_name', 'course_description'];

    protected $table      = 'courses';
    protected $primaryKey = 'course_id';
    protected $guarded    = [];
    protected $appends    = ['course_average_mark', 'course_percentage_completed'];

    public function courses_started() {
        return $this->hasMany(CourseStart::class, "course_id");
    }

    public function sections() {
        return $this->hasMany(Section::class, 'section_course_id');
    }

    public function getCourseAverageMarkAttribute()
    {
        return $this->getCourseAverageMark();
    }

    public function getCoursePercentageCompletedAttribute() {
        return $this->course_percentage_completed();
    }

    public function getCourseAverageMark() {
        $average = 0;
        $courses_started = CourseStart::where("course_id", $this->course_id)
            ->whereNotNull("user_course_started_review_mark")
            ->get();

        if($courses_started == null || count($courses_started) == 0) {
            return $average;
        }

        foreach ($courses_started as $course_started) {
            $average = $average + $course_started->user_course_started_review_mark;
        }

        return $average/count($courses_started);
    }

    public function course_percentage_completed() {

        if(Auth::check()) {

            $lessons_count = 0;

            // find all lessons for this course
            $sections = Section::where("section_course_id", $this->course_id)->get();
            foreach($sections as $section) {
                $lessons_count = $lessons_count + count($section->lessons);
            }

            // find all completed lessons
            $course_start = CourseStart::where("user_id", Auth::id())
                ->where("course_id", $this->course_id)->first();

            if($course_start == null || $sections == null) {
                return false;
            }

            $lessons_completed = LessonCompleted::where("course_started_id", $course_start->user_course_started_id)->get();

            $lessons_completed_count = count($lessons_completed);

            return array(
                "lessons_count" => $lessons_count,
                "lessons_completed_count" => $lessons_completed_count);

        } else {
            return false;
        }

    }

}
