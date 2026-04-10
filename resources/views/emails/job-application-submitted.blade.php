@component('mail::message')

# New Job Application Received

A new application has been submitted for your job posting.

**Job Position:** {{ $job->title }}
**Company:** {{ $company->name }}
**Applicant:** {{ $applicant->name }}
**Email:** {{ $applicant->email }}
**Applied On:** {{ $application->applied_at->format('F j, Y \a\t g:i A') }}

## Cover Letter

{{ $application->cover_letter }}

@component('mail::button', ['url' => route('job-applications.show', $application), 'color' => 'primary'])
View Full Application
@endcomponent

@if($application->resume_path)
The applicant's resume is attached to this email.
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
