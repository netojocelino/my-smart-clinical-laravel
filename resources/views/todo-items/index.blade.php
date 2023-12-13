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


        <button type="button" class="px-4 py-2 font-semibold text-sm bg-sky-500 text-white rounded-none shadow-sm w-half">
            Cadastrar tarefa
        </button>
    </div>

    <div class="grid gap-4 overflow-y-scroll overflow-x-hidden max-h-96 max-w-full">
        @forelse ($pendent as $item)
            <x-cards.item
                type="link"
                title="{{ $item->title }}"
                description="{{ $item->ShortDescription }}"
                action="mark.as.done"
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

    <div class="grid gap-4 overflow-y-scroll overflow-x-hidden max-h-96 max-w-full">
        @forelse ($completed as $item)
            <x-cards.item
                type="block"
                title="{{ $item->title }}"
                date="{{ optional($item->completed_at)->format('d-m-Y  \à\s H:i') }}"
                description="{{ $item->ShortDescription }}"
            />
        @empty
            <h4 class="text-xl font-semibold text-black dark:text-white">Sem cartões cadastrados</h4>
        @endforelse
    </div>
</div>

@endsection
