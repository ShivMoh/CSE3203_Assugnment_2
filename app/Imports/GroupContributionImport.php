<?php
// namespace App\Imports;

// use App\Models\GroupReport;
// use App\Models\Contribution;
// use App\Models\Student; // Assuming you have a Student model
// use Maatwebsite\Excel\Concerns\ToCollection;
// use Illuminate\Support\Collection;

// class ContributionImport implements ToCollection
// {
//     public function collection(Collection $rows)
//     {
//         foreach ($rows as $row) 
//         {
//             // Assuming the columns are in the order: last name, first name, usi, award
//             $groupReport = new GroupReport($row[0], $row[1], $row[2], $row[3]);

//             // Retrieve the student based on the USI (unique student identifier)
//             $student = Student::where('usi', $groupReport->usi)->first();

//             // If student is not found, handle it as needed (e.g., skip the row, throw an error)
//             if (!$student) {
//                 continue; // Skip this iteration
//             }

//             // Create or update the Contribution model
//             Contribution::create([
//                 'percentage' => $groupReport->award, // Assuming 'award' corresponds to 'percentage'
//                 'group_id' => $student->group_id, // Use group_id from the student object
//                 'student_id' => $student->id
//             ]);
//         }
//     }
// }


namespace App\Imports;

use App\Models\GroupReport;
use App\Models\Contribution;
use App\Models\Student;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;

// class ContributionImport implements ToCollection
// {
//     public function collection(Collection $rows)
//     {
//         // Skip the first row if it contains headings
//         $headerSkipped = false;
//         foreach ($rows as $row) 
//         {
//             // Skip the header row
//             if (!$headerSkipped) {
//                 $headerSkipped = true;
//                 continue;
//             }

//             // Assuming the columns are in the order: last name, first name, usi, award
//             // Initialize GroupReport object with the given row
//             $groupReport = new GroupReport(
//                 $row[0],
//                 $row[1],
//                 $row[2],
//                 $row[3]
//             );

//             // Retrieve the student based on the USI (unique student identifier)
//             $student = Student::where('usi', $groupReport->usi)->first();

//             // If student is not found, handle it as needed (e.g., skip the row, throw an error)
//             if (!$student) {
//                 continue; // Skip this iteration if student not found
//             }

//             // Create or update the Contribution model
//             Contribution::create([
//                 'id' => Str::uuuid(),
//                 'percentage' => $groupReport->award, // Assuming 'award' corresponds to 'percentage'
//                 'group_id' => $student->group_id, // Use group_id from the student object
//                 'student_id' => $student->id
//             ]);
//         }
//     }
// }


namespace App\Imports;

use App\Models\GroupReport;
use App\Models\Contribution;
use App\Models\Student;
use App\Models\Group;
use App\Models\Grade;
use App\Models\Comment;
use App\Models\GradeSection;
use App\Models\Section;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;

class ContributionImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        // Create a new Grade
        $grade = Grade::create([
            'id' => Str::uuid(),
            'marks_attained' => 0, // Initialize to 0
            'letter_grade' => '', // Initialize as empty
            'assessment_id' => 'your_assessment_id' // Replace with your actual assessment_id logic
        ]);

        // Create a new Group associated with the newly created Grade
        $group = Group::create([
            'id' => Str::uuid(),
            'name' => 'New Group', // Assign a name to the group
            'grade_id' => $grade->id,
        ]);

        // Initialize a Comment for the Grade
        Comment::create([
            'id' => Str::uuid(),
            'comment' => '', // Initialize as empty
            'grade_id' => $grade->id
        ]);

        // Skip the first row if it contains headings
        $headerSkipped = false;
        foreach ($rows as $row) 
        {
            // Skip the header row
            if (!$headerSkipped) {
                $headerSkipped = true;
                continue;
            }

            // Assuming the columns are in the order: last name, first name, usi, award
            // Initialize GroupReport object with the given row
            $groupReport = new GroupReport(
                $row[0],
                $row[1],
                $row[2],
                $row[3]
            );

            // Retrieve the student based on the USI (unique student identifier)
            $student = Student::where('usi', $groupReport->usi)->first();

            // If student is not found, handle it as needed (e.g., skip the row, throw an error)
            if (!$student) {
                continue; // Skip this iteration if student not found
            }

            // Create a new Contribution for the student in the new Group
            Contribution::create([
                'id' => Str::uuid(),
                'percentage' => $groupReport->award, // Assuming 'award' corresponds to 'percentage'
                'group_id' => $group->id, // Use the newly created group_id
                'student_id' => $student->id
            ]);
        }

        // Initialize GradeSections for the newly created Grade (assuming sections are predefined)
        $sections = Section::all(); // Fetch all sections or define your logic to fetch relevant sections
        foreach ($sections as $section) {
            GradeSection::create([
                'id' => Str::uuid(),
                'marks_attained' => 0, // Initialize to 0
                'grade_id' => $grade->id,
                'section_id' => $section->id
            ]);
        }
    }
}
