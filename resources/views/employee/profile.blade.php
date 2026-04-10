<x-app-layout>
    @php
        $isEdit = !is_null($profile);
        $formAction = $isEdit ? route('profile.employee.update', $profile) : route('profile.employee.store');
        $currentYear = (int) date('Y');
        $yearOptions = range($currentYear + 1, 1970);

        $educationItems = old('educations');
        if ($educationItems === null) {
            $educationItems = $isEdit
                ? $profile->educations->map(function ($education) {
                    return [
                        'program' => $education->program,
                        'school_name' => $education->school_name,
                        'passing_year' => $education->passing_year,
                        'grade_system' => $education->grade_system,
                        'grade_value' => $education->grade_value,
                        'description' => $education->description,
                    ];
                })->values()->all()
                : [];
        }
        if ($isEdit && count($educationItems) === 0 && !empty($profile->education)) {
            $educationItems[] = [
                'description' => $profile->education,
            ];
        }
        if (count($educationItems) === 0) {
            $educationItems = [[]];
        }

        $workExperienceItems = old('work_experiences');
        if ($workExperienceItems === null) {
            $workExperienceItems = $isEdit
                ? $profile->workExperiences->map(function ($workExperience) {
                    return [
                        'job_title' => $workExperience->job_title,
                        'company_name' => $workExperience->company_name,
                        'employment_type' => $workExperience->employment_type,
                        'start_year' => $workExperience->start_year,
                        'end_year' => $workExperience->end_year,
                        'description' => $workExperience->description,
                    ];
                })->values()->all()
                : [];
        }
        if ($isEdit && count($workExperienceItems) === 0 && !empty($profile->experience)) {
            $workExperienceItems[] = [
                'description' => $profile->experience,
            ];
        }
        if (count($workExperienceItems) === 0) {
            $workExperienceItems = [[]];
        }
    @endphp

    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-indigo-600">Career Profile</p>
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">{{ $isEdit ? 'Update My Profile' : 'Create My Profile' }}</h2>
                <p class="mt-1 text-sm text-slate-600">Add professional information employers want to see in a real job portal.</p>
            </div>
            <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-semibold {{ $isEdit ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800' }}">
                {{ $isEdit ? 'Profile Active' : 'Profile Setup' }}
            </span>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-b from-sky-50 via-white to-blue-100/30 py-10 sm:py-12">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-rose-700">
                    {{ session('error') }}
                </div>
            @endif

            @if($isEdit)
                <section class="mb-6 rounded-2xl border border-blue-200/80 bg-white/85 p-6 shadow-[0_12px_28px_rgba(24,72,153,0.12)] backdrop-blur">
                    <h3 class="text-xl font-semibold text-slate-900">{{ auth()->user()->name }}</h3>
                    <p class="text-sm font-medium text-indigo-600">{{ auth()->user()->email }}</p>
                    @if($profile->bio)
                        <p class="mt-3 text-sm leading-relaxed text-slate-600">{{ $profile->bio }}</p>
                    @endif
                </section>
            @endif

            <section class="rounded-2xl border border-blue-200/80 bg-white/85 shadow-[0_12px_28px_rgba(24,72,153,0.12)] backdrop-blur">
                <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" class="divide-y divide-blue-100/80">
                    @csrf
                    @if($isEdit)
                        @method('patch')
                    @endif

                    <div class="px-6 py-6">
                        <h3 class="text-lg font-semibold text-slate-900">Personal Information</h3>
                        <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label for="phone" class="mb-1.5 block text-sm font-medium text-slate-700">Phone Number</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone', $profile->phone ?? '') }}"
                                       class="block w-full rounded-xl border border-blue-200 bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                       placeholder="+8801XXXXXXXXX">
                                @error('phone')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="date_of_birth" class="mb-1.5 block text-sm font-medium text-slate-700">Date of Birth</label>
                                <input type="date" id="date_of_birth" name="date_of_birth"
                                       value="{{ old('date_of_birth', isset($profile) ? $profile->date_of_birth?->format('Y-m-d') : '') }}"
                                       class="block w-full rounded-xl border border-blue-200 bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                @error('date_of_birth')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="gender" class="mb-1.5 block text-sm font-medium text-slate-700">Gender</label>
                                <select id="gender" name="gender"
                                        class="block w-full rounded-xl border border-blue-200 bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                    <option value="">Select</option>
                                    <option value="male" {{ old('gender', $profile->gender ?? '') === 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $profile->gender ?? '') === 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $profile->gender ?? '') === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="resume" class="mb-1.5 block text-sm font-medium text-slate-700">Resume / CV</label>
                                @if($isEdit && $profile->resume)
                                    <div class="mb-2 rounded-lg border border-blue-100 bg-blue-50/70 px-3 py-2 text-sm text-slate-700">
                                        Current: <span class="font-medium">{{ basename($profile->resume) }}</span>
                                    </div>
                                @endif
                                <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx"
                                       class="block w-full rounded-xl border border-blue-200 bg-white px-4 py-2 text-sm text-slate-600 file:mr-3 file:rounded-full file:border-0 file:bg-blue-100 file:px-4 file:py-2 file:font-semibold file:text-blue-700 hover:file:bg-blue-200">
                                <p class="mt-1 text-xs text-slate-500">Allowed: PDF, DOC, DOCX up to 5MB</p>
                                @error('resume')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-6">
                        <h3 class="text-lg font-semibold text-slate-900">Professional Summary</h3>
                        <div class="mt-4 grid grid-cols-1 gap-4">
                            <div>
                                <label for="address" class="mb-1.5 block text-sm font-medium text-slate-700">Address</label>
                                <textarea id="address" name="address" rows="2"
                                          class="block w-full rounded-xl border border-blue-200 bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                          placeholder="Your full address">{{ old('address', $profile->address ?? '') }}</textarea>
                                @error('address')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="bio" class="mb-1.5 block text-sm font-medium text-slate-700">Description / About You</label>
                                <textarea id="bio" name="bio" rows="4"
                                          class="block w-full rounded-xl border border-blue-200 bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                          placeholder="Write a short professional summary...">{{ old('bio', $profile->bio ?? '') }}</textarea>
                                @error('bio')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </div>

                            <div>
                                <label for="skills" class="mb-1.5 block text-sm font-medium text-slate-700">Skills</label>
                                <input type="text" id="skills" name="skills" value="{{ old('skills', $profile->skills ?? '') }}"
                                       class="block w-full rounded-xl border border-blue-200 bg-white px-4 py-2.5 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                       placeholder="Laravel, PHP, Vue, React, SQL, Communication">
                                @error('skills')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-6">
                        <div class="mb-4 flex items-center justify-between gap-3">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">Education</h3>
                                <p class="text-sm text-slate-500">Add one or more education entries with program, school, passing year, grade system and description.</p>
                            </div>
                            <button type="button" id="add-education"
                                    class="inline-flex items-center rounded-xl border border-blue-200 bg-blue-50 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-blue-800 transition hover:bg-blue-100">
                                Add Education
                            </button>
                        </div>

                        <div id="education-wrapper" class="space-y-4">
                            @foreach($educationItems as $index => $education)
                                <article class="education-item rounded-xl border border-blue-100 bg-blue-50/40 p-4" data-index="{{ $index }}">
                                    <div class="mb-3 flex items-center justify-between">
                                        <h4 class="text-sm font-semibold uppercase tracking-[0.1em] text-blue-700">Education #{{ $index + 1 }}</h4>
                                        <button type="button" class="js-remove-education text-xs font-semibold text-rose-600 hover:text-rose-700">Remove</button>
                                    </div>

                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                        <div>
                                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Program / Degree</label>
                                            <input type="text" name="educations[{{ $index }}][program]" value="{{ $education['program'] ?? '' }}"
                                                   class="block w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                                   placeholder="BSc in Computer Science">
                                            @error("educations.$index.program")<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                                        </div>

                                        <div>
                                            <label class="mb-1.5 block text-sm font-medium text-slate-700">School / Institution</label>
                                            <input type="text" name="educations[{{ $index }}][school_name]" value="{{ $education['school_name'] ?? '' }}"
                                                   class="block w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                                   placeholder="University of Dhaka">
                                            @error("educations.$index.school_name")<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                                        </div>

                                        <div>
                                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Passing Year</label>
                                            <select name="educations[{{ $index }}][passing_year]"
                                                    class="block w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                                <option value="">Select year</option>
                                                @foreach($yearOptions as $year)
                                                    <option value="{{ $year }}" {{ (string)($education['passing_year'] ?? '') === (string)$year ? 'selected' : '' }}>{{ $year }}</option>
                                                @endforeach
                                            </select>
                                            @error("educations.$index.passing_year")<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                                        </div>

                                        <div>
                                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Grade System</label>
                                            <select name="educations[{{ $index }}][grade_system]"
                                                    class="block w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                                <option value="">Select system</option>
                                                <option value="cgpa" {{ ($education['grade_system'] ?? '') === 'cgpa' ? 'selected' : '' }}>CGPA</option>
                                                <option value="gpa" {{ ($education['grade_system'] ?? '') === 'gpa' ? 'selected' : '' }}>GPA</option>
                                                <option value="grade" {{ ($education['grade_system'] ?? '') === 'grade' ? 'selected' : '' }}>Grade</option>
                                                <option value="percentage" {{ ($education['grade_system'] ?? '') === 'percentage' ? 'selected' : '' }}>Percentage</option>
                                            </select>
                                            @error("educations.$index.grade_system")<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                                        </div>

                                        <div class="md:col-span-2">
                                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Grade / Score</label>
                                            <input type="text" name="educations[{{ $index }}][grade_value]" value="{{ $education['grade_value'] ?? '' }}"
                                                   class="block w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                                   placeholder="e.g. 3.75 / 4.00">
                                            @error("educations.$index.grade_value")<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                                        </div>

                                        <div class="md:col-span-2">
                                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Description</label>
                                            <textarea name="educations[{{ $index }}][description]" rows="3"
                                                      class="block w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                                      placeholder="Achievements, relevant coursework, projects, scholarships...">{{ $education['description'] ?? '' }}</textarea>
                                            @error("educations.$index.description")<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>

                    <div class="px-6 py-6">
                        <div class="mb-4 flex items-center justify-between gap-3">
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900">Work Experience</h3>
                                <p class="text-sm text-slate-500">Add job title, company, employment type, duration and responsibilities.</p>
                            </div>
                            <button type="button" id="add-work-experience"
                                    class="inline-flex items-center rounded-xl border border-blue-200 bg-blue-50 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-blue-800 transition hover:bg-blue-100">
                                Add Experience
                            </button>
                        </div>

                        <div id="work-experience-wrapper" class="space-y-4">
                            @foreach($workExperienceItems as $index => $workExperience)
                                <article class="work-experience-item rounded-xl border border-blue-100 bg-blue-50/40 p-4" data-index="{{ $index }}">
                                    <div class="mb-3 flex items-center justify-between">
                                        <h4 class="text-sm font-semibold uppercase tracking-[0.1em] text-blue-700">Experience #{{ $index + 1 }}</h4>
                                        <button type="button" class="js-remove-work-experience text-xs font-semibold text-rose-600 hover:text-rose-700">Remove</button>
                                    </div>

                                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                        <div>
                                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Job Title</label>
                                            <input type="text" name="work_experiences[{{ $index }}][job_title]" value="{{ $workExperience['job_title'] ?? '' }}"
                                                   class="block w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                                   placeholder="Software Engineer">
                                            @error("work_experiences.$index.job_title")<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                                        </div>

                                        <div>
                                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Company</label>
                                            <input type="text" name="work_experiences[{{ $index }}][company_name]" value="{{ $workExperience['company_name'] ?? '' }}"
                                                   class="block w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                                   placeholder="ABC Technologies Ltd">
                                            @error("work_experiences.$index.company_name")<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                                        </div>

                                        <div>
                                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Employment Type</label>
                                            <select name="work_experiences[{{ $index }}][employment_type]"
                                                    class="block w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                                <option value="">Select type</option>
                                                <option value="full_time" {{ ($workExperience['employment_type'] ?? '') === 'full_time' ? 'selected' : '' }}>Full-time</option>
                                                <option value="part_time" {{ ($workExperience['employment_type'] ?? '') === 'part_time' ? 'selected' : '' }}>Part-time</option>
                                                <option value="contract" {{ ($workExperience['employment_type'] ?? '') === 'contract' ? 'selected' : '' }}>Contract</option>
                                                <option value="internship" {{ ($workExperience['employment_type'] ?? '') === 'internship' ? 'selected' : '' }}>Internship</option>
                                                <option value="freelance" {{ ($workExperience['employment_type'] ?? '') === 'freelance' ? 'selected' : '' }}>Freelance</option>
                                            </select>
                                            @error("work_experiences.$index.employment_type")<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                                        </div>

                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="mb-1.5 block text-sm font-medium text-slate-700">Start Year</label>
                                                <select name="work_experiences[{{ $index }}][start_year]"
                                                        class="block w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                                    <option value="">Start</option>
                                                    @foreach($yearOptions as $year)
                                                        <option value="{{ $year }}" {{ (string)($workExperience['start_year'] ?? '') === (string)$year ? 'selected' : '' }}>{{ $year }}</option>
                                                    @endforeach
                                                </select>
                                                @error("work_experiences.$index.start_year")<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                                            </div>
                                            <div>
                                                <label class="mb-1.5 block text-sm font-medium text-slate-700">End Year</label>
                                                <select name="work_experiences[{{ $index }}][end_year]"
                                                        class="block w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                                    <option value="">Present</option>
                                                    @foreach($yearOptions as $year)
                                                        <option value="{{ $year }}" {{ (string)($workExperience['end_year'] ?? '') === (string)$year ? 'selected' : '' }}>{{ $year }}</option>
                                                    @endforeach
                                                </select>
                                                @error("work_experiences.$index.end_year")<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                                            </div>
                                        </div>

                                        <div class="md:col-span-2">
                                            <label class="mb-1.5 block text-sm font-medium text-slate-700">Description</label>
                                            <textarea name="work_experiences[{{ $index }}][description]" rows="3"
                                                      class="block w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                                                      placeholder="Key responsibilities, achievements, tools you used...">{{ $workExperience['description'] ?? '' }}</textarea>
                                            @error("work_experiences.$index.description")<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end bg-blue-50/50 px-6 py-4">
                        <button type="submit"
                                class="inline-flex items-center rounded-xl bg-gradient-to-r from-blue-700 to-sky-500 px-6 py-2.5 text-sm font-semibold text-white shadow-[0_10px_20px_rgba(30,102,230,0.25)] transition hover:brightness-105">
                            {{ $isEdit ? 'Update Profile' : 'Create Profile' }}
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </div>

    <template id="education-template">
        <article class="education-item rounded-xl border border-blue-100 bg-blue-50/40 p-4" data-index="__INDEX__">
            <div class="mb-3 flex items-center justify-between">
                <h4 class="text-sm font-semibold uppercase tracking-[0.1em] text-blue-700">Education #__NUMBER__</h4>
                <button type="button" class="js-remove-education text-xs font-semibold text-rose-600 hover:text-rose-700">Remove</button>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Program / Degree</label>
                    <input type="text" name="educations[__INDEX__][program]"
                           class="block w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                           placeholder="BSc in Computer Science">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">School / Institution</label>
                    <input type="text" name="educations[__INDEX__][school_name]"
                           class="block w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                           placeholder="University of Dhaka">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Passing Year</label>
                    <select name="educations[__INDEX__][passing_year]"
                            class="block w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                        <option value="">Select year</option>
                        @foreach($yearOptions as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Grade System</label>
                    <select name="educations[__INDEX__][grade_system]"
                            class="block w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                        <option value="">Select system</option>
                        <option value="cgpa">CGPA</option>
                        <option value="gpa">GPA</option>
                        <option value="grade">Grade</option>
                        <option value="percentage">Percentage</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Grade / Score</label>
                    <input type="text" name="educations[__INDEX__][grade_value]"
                           class="block w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                           placeholder="e.g. 3.75 / 4.00">
                </div>

                <div class="md:col-span-2">
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Description</label>
                    <textarea name="educations[__INDEX__][description]" rows="3"
                              class="block w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                              placeholder="Achievements, relevant coursework, projects, scholarships..."></textarea>
                </div>
            </div>
        </article>
    </template>

    <template id="work-experience-template">
        <article class="work-experience-item rounded-xl border border-blue-100 bg-blue-50/40 p-4" data-index="__INDEX__">
            <div class="mb-3 flex items-center justify-between">
                <h4 class="text-sm font-semibold uppercase tracking-[0.1em] text-blue-700">Experience #__NUMBER__</h4>
                <button type="button" class="js-remove-work-experience text-xs font-semibold text-rose-600 hover:text-rose-700">Remove</button>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Job Title</label>
                    <input type="text" name="work_experiences[__INDEX__][job_title]"
                           class="block w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                           placeholder="Software Engineer">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Company</label>
                    <input type="text" name="work_experiences[__INDEX__][company_name]"
                           class="block w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                           placeholder="ABC Technologies Ltd">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Employment Type</label>
                    <select name="work_experiences[__INDEX__][employment_type]"
                            class="block w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                        <option value="">Select type</option>
                        <option value="full_time">Full-time</option>
                        <option value="part_time">Part-time</option>
                        <option value="contract">Contract</option>
                        <option value="internship">Internship</option>
                        <option value="freelance">Freelance</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">Start Year</label>
                        <select name="work_experiences[__INDEX__][start_year]"
                                class="block w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                            <option value="">Start</option>
                            @foreach($yearOptions as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-slate-700">End Year</label>
                        <select name="work_experiences[__INDEX__][end_year]"
                                class="block w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                            <option value="">Present</option>
                            @foreach($yearOptions as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="mb-1.5 block text-sm font-medium text-slate-700">Description</label>
                    <textarea name="work_experiences[__INDEX__][description]" rows="3"
                              class="block w-full rounded-lg border border-blue-200 bg-white px-3 py-2 text-sm text-slate-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200"
                              placeholder="Key responsibilities, achievements, tools you used..."></textarea>
                </div>
            </div>
        </article>
    </template>

    <script>
        (function () {
            var educationWrapper = document.getElementById('education-wrapper');
            var addEducationButton = document.getElementById('add-education');
            var educationTemplate = document.getElementById('education-template');
            var nextEducationIndex = educationWrapper ? educationWrapper.querySelectorAll('.education-item').length : 0;

            if (addEducationButton && educationWrapper && educationTemplate) {
                addEducationButton.addEventListener('click', function () {
                    var html = educationTemplate.innerHTML
                        .replace(/__INDEX__/g, nextEducationIndex)
                        .replace(/__NUMBER__/g, nextEducationIndex + 1);
                    educationWrapper.insertAdjacentHTML('beforeend', html);
                    nextEducationIndex += 1;
                });

                educationWrapper.addEventListener('click', function (event) {
                    if (event.target.classList.contains('js-remove-education')) {
                        var item = event.target.closest('.education-item');
                        if (item) {
                            item.remove();
                        }
                    }
                });
            }

            var workExperienceWrapper = document.getElementById('work-experience-wrapper');
            var addWorkExperienceButton = document.getElementById('add-work-experience');
            var workExperienceTemplate = document.getElementById('work-experience-template');
            var nextWorkExperienceIndex = workExperienceWrapper ? workExperienceWrapper.querySelectorAll('.work-experience-item').length : 0;

            if (addWorkExperienceButton && workExperienceWrapper && workExperienceTemplate) {
                addWorkExperienceButton.addEventListener('click', function () {
                    var html = workExperienceTemplate.innerHTML
                        .replace(/__INDEX__/g, nextWorkExperienceIndex)
                        .replace(/__NUMBER__/g, nextWorkExperienceIndex + 1);
                    workExperienceWrapper.insertAdjacentHTML('beforeend', html);
                    nextWorkExperienceIndex += 1;
                });

                workExperienceWrapper.addEventListener('click', function (event) {
                    if (event.target.classList.contains('js-remove-work-experience')) {
                        var item = event.target.closest('.work-experience-item');
                        if (item) {
                            item.remove();
                        }
                    }
                });
            }
        })();
    </script>
</x-app-layout>
