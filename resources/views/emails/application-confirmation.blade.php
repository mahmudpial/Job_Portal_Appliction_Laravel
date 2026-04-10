@component('mail::message')

# Application Submitted Successfully!

Thank you for applying to the {{ $job->title }} position at {{ $company->name }}.

## Application Details

**Position:** {{ $job->title }}
**Company:** {{ $company->name }}
**Location:** {{ $job->location ?? 'Remote' }}
**Applied On:** {{ $application->applied_at->format('F j, Y \a\t g:i A') }}

## What's Next?

- Your application is now under review by the hiring team
- You will be notified of any updates to your application status
- You can track your application status in your dashboard

@component('mail::button', ['url' => route('job-applications.index'), 'color' => 'primary'])
View My Applications
@endcomponent

If you have any questions about your application, feel free to contact the company directly.

Best regards,<br>
{{ config('app.name') }} Team
@endcomponent