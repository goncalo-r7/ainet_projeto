<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CursosSeeder extends Seeder
{
    public function run()
    {
        DB::table('courses')->truncate();
        $this->loadFromJson();

        Storage::deleteDirectory('public/courses');
        Storage::makeDirectory('public/courses');

        // Preencher Image Courses
        $allFiles = collect(File::files(database_path('seeders/courses')));
        foreach ($allFiles as $f) {
            $this->saveCourseImageFile($f->getPathname(), $f->getFilename());
        }
    }

    private function loadFromJson()
    {
        $this->command->line('--- > Creating Courses');
        DB::table('courses')->truncate();

        $coursesFromJSONFile = json_decode(file_get_contents(database_path('seeders/data') . "/courses.json"), true);
        DB::table('courses')->insert($coursesFromJSONFile);
    }

    private function saveCourseImageFile($file, $newFileName)
    {
        File::copy($file, storage_path('app/public/courses') . '/' . $newFileName);
        $this->command->info("Copied Course Image: $newFileName");
    }
}
