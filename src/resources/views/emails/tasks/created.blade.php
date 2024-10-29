@component('mail::message')
# Nova Tarefa Criada

A nova tarefa **{{ $task->title }}** foi criada.

**Descrição:** {{ $task->description }}

**Prioridade:** {{ $task->priority }}

**Prazo:** {{ $task->deadline }}

@component('mail::button', ['url' => url('/tasks/' . $task->id)])
Ver Tarefa
@endcomponent

Obrigado,<br>
{{ config('app.name') }}
@endcomponent
