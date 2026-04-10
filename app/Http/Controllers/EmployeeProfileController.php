<?php

namespace App\Http\Controllers;

use App\Models\EmployeeProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeProfileController extends Controller
{
    public function index(): View
    {
        $profile = auth()->user()
            ->employeeProfile()
            ->with(['educations', 'workExperiences'])
            ->first();

        return view('employee.profile', compact('profile'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateProfileRequest($request);

        if ($request->hasFile('resume')) {
            $validated['resume'] = $request->file('resume')->store('resumes', 'public');
        }

        $profile = auth()->user()->employeeProfile()->create($validated);
        $this->syncProfileCollections($profile, $validated);

        return redirect()->route('dashboard')->with('success', 'Profile created successfully.');
    }

    public function update(Request $request, EmployeeProfile $profile): RedirectResponse
    {
        $this->authorize('update', $profile);

        $validated = $this->validateProfileRequest($request);

        if ($request->hasFile('resume')) {
            $validated['resume'] = $request->file('resume')->store('resumes', 'public');
        }

        $profile->update($validated);
        $this->syncProfileCollections($profile, $validated);

        return redirect()->route('dashboard')->with('success', 'Profile updated successfully.');
    }

    private function validateProfileRequest(Request $request): array
    {
        $maxYear = now()->year + 10;

        return $request->validate([
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'bio' => 'nullable|string',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'skills' => 'nullable|string',
            'education' => 'nullable|string',
            'experience' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'educations' => 'nullable|array',
            'educations.*.program' => 'nullable|string|max:255',
            'educations.*.school_name' => 'nullable|string|max:255',
            'educations.*.passing_year' => "nullable|integer|min:1950|max:$maxYear",
            'educations.*.grade_system' => 'nullable|string|in:cgpa,gpa,grade,percentage',
            'educations.*.grade_value' => 'nullable|string|max:50',
            'educations.*.description' => 'nullable|string|max:2000',
            'work_experiences' => 'nullable|array',
            'work_experiences.*.job_title' => 'nullable|string|max:255',
            'work_experiences.*.company_name' => 'nullable|string|max:255',
            'work_experiences.*.employment_type' => 'nullable|string|in:full_time,part_time,contract,internship,freelance',
            'work_experiences.*.start_year' => "nullable|integer|min:1950|max:$maxYear",
            'work_experiences.*.end_year' => "nullable|integer|min:1950|max:$maxYear",
            'work_experiences.*.description' => 'nullable|string|max:3000',
        ]);
    }

    private function syncProfileCollections(EmployeeProfile $profile, array $validated): void
    {
        $educations = $this->sanitizeEducationRows($validated['educations'] ?? []);
        $workExperiences = $this->sanitizeWorkExperienceRows($validated['work_experiences'] ?? []);

        $profile->educations()->delete();
        if ($educations !== []) {
            $profile->educations()->createMany($educations);
        }

        $profile->workExperiences()->delete();
        if ($workExperiences !== []) {
            $profile->workExperiences()->createMany($workExperiences);
        }
    }

    private function sanitizeEducationRows(array $rows): array
    {
        $result = [];

        foreach ($rows as $row) {
            $program = trim((string) ($row['program'] ?? ''));
            $schoolName = trim((string) ($row['school_name'] ?? ''));
            $gradeSystem = trim((string) ($row['grade_system'] ?? ''));
            $gradeValue = trim((string) ($row['grade_value'] ?? ''));
            $description = trim((string) ($row['description'] ?? ''));
            $passingYear = $row['passing_year'] ?? null;
            $passingYear = ($passingYear === null || $passingYear === '') ? null : (int) $passingYear;

            if (
                $program === '' &&
                $schoolName === '' &&
                $passingYear === null &&
                $gradeSystem === '' &&
                $gradeValue === '' &&
                $description === ''
            ) {
                continue;
            }

            $result[] = [
                'program' => $program !== '' ? $program : null,
                'school_name' => $schoolName !== '' ? $schoolName : null,
                'passing_year' => $passingYear,
                'grade_system' => $gradeSystem !== '' ? $gradeSystem : null,
                'grade_value' => $gradeValue !== '' ? $gradeValue : null,
                'description' => $description !== '' ? $description : null,
            ];
        }

        return $result;
    }

    private function sanitizeWorkExperienceRows(array $rows): array
    {
        $result = [];

        foreach ($rows as $row) {
            $jobTitle = trim((string) ($row['job_title'] ?? ''));
            $companyName = trim((string) ($row['company_name'] ?? ''));
            $employmentType = trim((string) ($row['employment_type'] ?? ''));
            $description = trim((string) ($row['description'] ?? ''));
            $startYear = $row['start_year'] ?? null;
            $endYear = $row['end_year'] ?? null;
            $startYear = ($startYear === null || $startYear === '') ? null : (int) $startYear;
            $endYear = ($endYear === null || $endYear === '') ? null : (int) $endYear;

            if (
                $jobTitle === '' &&
                $companyName === '' &&
                $employmentType === '' &&
                $startYear === null &&
                $endYear === null &&
                $description === ''
            ) {
                continue;
            }

            $result[] = [
                'job_title' => $jobTitle !== '' ? $jobTitle : null,
                'company_name' => $companyName !== '' ? $companyName : null,
                'employment_type' => $employmentType !== '' ? $employmentType : null,
                'start_year' => $startYear,
                'end_year' => $endYear,
                'description' => $description !== '' ? $description : null,
            ];
        }

        return $result;
    }
}
