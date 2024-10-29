@component('mail::message')
# Tarefa Atualizada

A tarefa **{{ $task->title }}** foi atualizada.

**Descrição:** {{ $task->description }}

**Prioridade:** {{ $task->priority }}

**Status:** {{ $task->status }}

**Prazo:** {{ $task->deadline }}

@component('mail::button', ['url' => url('/tasks/' . $task->id)])
Ver Tarefa
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
