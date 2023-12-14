@extends('layouts.master')


@section('content')
<div class="grid md:grid-cols-1 gap-4 rounded-lg">
    <div class="flex p-3 rounded-lg gap-2 space-between">
        <div>
        </div>
        <span class="text-amber-400">
            <x-icons.svg type="hourglass-split"  size="28" />
        </span>
        <h2 class="text-xl font-semibold text-black dark:text-white">tarefas pendentes</h2>


        <button
            type="button"
            class="px-4 py-2 font-semibold text-sm bg-sky-500 hover:bg-sky-400 text-white rounded-none shadow-sm w-half"
            data-open-modal="create_task"
            data-event-modal="{{ route('app.todo.item.store') }}"
        >
            Cadastrar tarefa
        </button>
    </div>

    <div class="grid gap-4 overflow-y-scroll overflow-x-hidden max-h-96 max-w-full" name="pendent">
        @forelse ($pendent as $item)
            <x-cards.item
                type="link"
                title="{{ $item->title }}"
                description="{{ $item->ShortDescription }}"
                action="mark.as.done"
                key="{{ $item->getKey() }}"
            />
        @empty
            <h4 class="text-xl font-semibold text-black dark:text-white">Sem cartões cadastrados</h4>
        @endforelse
    </div>
</div>


<div class="grid md:grid-cols-1 gap-4 rounded-lg">
    <div class="flex p-3 rounded-lg gap-2">
        <span class="text-lime-400">
            <x-icons.svg type="done"  size="28" />
        </span>
        <h2 class="text-xl font-semibold text-black dark:text-white">tarefas completas</h2>
    </div>

    <div class="grid gap-4 overflow-y-scroll overflow-x-hidden max-h-96 max-w-full" name="done">
        @forelse ($completed as $item)
            <x-cards.item
                type="block"
                title="{{ $item->title }}"
                date="{{ optional($item->completed_at)->format('d-m-Y  \à\s H:i') }}"
                description="{{ $item->ShortDescription }}"
                action="mark.as.pendent"
                key="{{ $item->getKey() }}"
            />
        @empty
            <h4 class="text-xl font-semibold text-black dark:text-white">Sem cartões cadastrados</h4>
        @endforelse
    </div>
</div>

<x-cards.modal id="mark.as.done" />
<x-cards.modal id="mark.as.pendent" />

<x-cards.form-modal
    id="create_task"
    title="Cadastrar tarefa"
/>

<div name="template_card" class="hidden">
    <x-cards.item
        type="link"
        title="$TEMPLATE_TITLE$"
        description="$TEMPLATE_DESCRIPTION$"
        action="mark.as.done"
        key="$TEMPLATE_KEY$"
    />
</div>


@push('pre_js')
<script>
function onLoad()
{
    const $markAsDoneBtns = document.querySelectorAll('[data-action="mark.as.done"]')
    const $markAsPendentBtns = document.querySelectorAll('[data-action="mark.as.pendent"]')

    function MarkAsDoneAction ()
    {
        const $prompt = document.getElementById(this.dataset.action)
        $prompt.classList.remove('hidden')
        const $key = this.dataset.key
        // TODO: replace workaround
        const _url = "{{ route('app.todo.item.mark-done', ['id' => 0]) }}".split('/0')[0] + '/' + $key

        const $action = $prompt.querySelector(`[data-event-modal-active]`)
        $action.dataset.eventModalActive = _url
        $prompt.dataset.key = $key
    }

    Array.from($markAsDoneBtns).forEach($btn => {
        $btn.removeEventListener('click', MarkAsDoneAction)
        $btn.addEventListener('click', MarkAsDoneAction)
    })


    function MarkAsPendentAction ()
    {
        const $prompt = document.getElementById(this.dataset.action)
        $prompt.classList.remove('hidden')
        const $key = this.dataset.key
        // TODO: replace workaround
        const _url = "{{ route('app.todo.item.mark-pendent', ['id' => 0]) }}".split('/0')[0] + '/' + $key

        const $action = $prompt.querySelector(`[data-event-modal-active]`)
        $action.dataset.eventModalActive = _url
        $prompt.dataset.key = $key
    }

    Array.from($markAsPendentBtns).forEach($btn => {
        $btn.removeEventListener('click', MarkAsPendentAction)
        $btn.addEventListener('click', MarkAsPendentAction)
    })
}

document.addEventListener('DOMContentLoaded', function () {
    onLoad()
})
</script>
@endpush

@endsection
