@component('mail::message')
# 📋 New Task Assignment  
You have a new task to complete  

Hello {{ $assignee->name ?? 'Team Member' }},  

You've been assigned the following task:  

**Task Title:** {{ $task->title ?? 'New Task' }}  

@if(!empty($task->description))
**Description:**  
{{ $task->description }}  
@endif

@if(!empty($task->due_date))
**Due Date:** 📅 {{ \Carbon\Carbon::parse($task->due_date)->format('F j, Y') }} ({{ \Carbon\Carbon::parse($task->due_date)->diffForHumans() }})
@endif

@if(!empty($task->priority))
**Priority:** {{ $task->priority->name ?? 'Medium' }} Priority  
@endif

@if(!empty($task->status))
**Status:** {{ ucfirst($task->status) }}  
@endif

@if(!empty($task->created_by))
**Assigned by:** {{ $task->creator->name }}  
@endif

@component('mail::button', ['url' => route('tasks.show', $task->id)])
View Task Details
@endcomponent

---  
You're receiving this email because you were assigned this task in {{ config('app.name') }}.  
Need help? Reply to this email or contact your manager.  

© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
@endcomponent