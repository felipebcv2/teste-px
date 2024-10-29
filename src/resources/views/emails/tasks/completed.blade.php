@component('mail::message')
# Tarefa Concluída

A tarefa **{{ $task->title }}** foi marcada como concluída.

**Descrição:** {{ $task->description }}

**Prioridade:** {{ $task->priority }}

@component('mail::button', ['url' => url('/tasks/' . $task->id)])
Ver Tarefa
@endcomponent

Parabéns por concluir esta tarefa!<br>
{{ config('app.name') }}
@endcomponent
