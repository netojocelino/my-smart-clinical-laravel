@extends('layouts.master')


@section('content')
<div class="grid md:grid-cols-1 gap-4 rounded-lg">
    <div class="flex p-3 rounded-lg gap-2 space-between">
        <div>
        </div>
        <span class="text-amber-400">
            <x-icons.svg type="hourglass-split"  size="28" />
        </span>
        <h2 class="text-xl font-semibold text-black dark:text-white">tarefas pendentes ({{$pendent->count()}})</h2>


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
                description="{{ $item->description }}"
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
        <h2 class="text-xl font-semibold text-black dark:text-white">tarefas completas ({{$completed->count()}})</h2>
    </div>

    <div class="grid gap-4 overflow-y-scroll overflow-x-hidden max-h-96 max-w-full" name="done">
        @forelse ($completed as $item)
            <x-cards.item
                type="block"
                title="{{ $item->title }}"
                date="{{ optional($item->completed_at)->format('d-m-Y  \à\s H:i') }}"
                description="{{ $item->description }}"
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

<x-cards.history-modal
    id="history_task"
    title="Histórico da tarefa"
    cencel="Fechar"
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
    const $maxText = document.querySelectorAll('[data-max-text]')
    const $historyBtn = document.querySelectorAll('[data-history]:not([data-history="$TEMPLATE_KEY$"])')

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

    function wrapTextFn () {
        const $p = this.parentNode.querySelector('p')
        $p.classList.toggle('wrap-text')
        const inverseText = this.innerText === 'Mais' ? 'Menos' : 'Mais'
        this.innerText = inverseText
    }

    Array.from($maxText).forEach($item => {
        const size = $item.innerText.length
        if (size > 60)
        {
            $item.classList.add('wrap-text')
            const $wrap = $item.parentNode.querySelector('[data-wrap]')
            $wrap.classList.remove('hidden')
            $wrap.removeEventListener('click', wrapTextFn)
            $wrap.addEventListener('click', wrapTextFn)
        }
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

    function ShowHistory ()
    {
        const $prompt = document.getElementById('history_task')
        const $div = $prompt.querySelector('[name=history]')
        $prompt.classList.remove('hidden')
        const $key = this.dataset.history

        // TODO: replace workaround
        const _url = "{{ route('app.todo.item.history', ['id' => 0]) }}".split('/0')[0] + '/' + $key
        $div.innerHTML = `<div><p><strong>Carrengando</strong></p></div>`

        fetch(_url).then(async (response) => {
            if (response.status != 200) {
                alert('Ocorreu um erro')
                return;
            }
            const _json = await response.json()


            if (_json.length == 0) {
                $div.innerHTML = `<div>
                    <p>
                        <strong>Nenhum histórico disponível</strong>
                    </p>
                </div>`
                return;
            }

            _json.forEach(item => {
                let _list = '';

                if (Object.keys(item.changes).length > 0)
                {
                    _list = '<h6 class="dark:text-white text-slate-800">Itens Modificados</h6>';

                    _list += '<ul class="list-disc">';
                        Object.keys(item.changes).forEach(change => {
                            _list += `<li class="text-lime-600">${change}: ${item.changes[change]}</li>`;

                        })
                    _list += '</ul>';

                }

                $div.innerHTML = `
                <div class="border-b-2 border-slate-500">
                    <p>
                        <strong>${item.taskName}</strong>
                        ${ item.event }
                        em
                        ${ item.created_at }
                    </p>
                    ${_list}
                </div>${$div.innerHTML}`
            })
        })
    }

    Array.from($historyBtn).forEach($btn => {
        $btn.removeEventListener('click', ShowHistory)
        $btn.addEventListener('click', ShowHistory)
    })
}

document.addEventListener('DOMContentLoaded', function () {
    onLoad()
})
</script>
@endpush

@endsection
