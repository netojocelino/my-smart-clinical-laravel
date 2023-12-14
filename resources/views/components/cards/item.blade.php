@props([
    'action' => null,
    'date' => null,
    'description' => '...',
    'key' => '',
    'link' => null,
    'title',
    'type' => 'block',
])


@if (!empty($link))
<a data-item="{{ $key }}" href="{{ $link }}" class="scale-100 py-6 px-4 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
@else
<div data-item="{{ $key }}" class="scale-100 py-6 px-4 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-red-500">
@endif

    <div>

        <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">{{ $title }}</h2>

        @if (!empty($date))
            <small class="text-gray-400 font-semibold">Completada em: <time datetime="{{ $date }}">{{ $date }}</time></small>
        @endif

        <div>
            <p class="mt-4 text-gray-500 dark:text-gray-400 text-sm leading-relaxed" data-max-text="">
                {{ $description }}
            </p>
            <button
                type="button"
                class="bg-sky-600 hover:bg-sky-500 text-white px-4 py-2 rounded-md hidden my-2"
                data-wrap=""
            >Mais</button>
            <button
                type="button"
                class="bg-sky-600 hover:bg-sky-500 text-white px-4 py-2 rounded-md my-2"
                data-history="{{ $key }}"
            >Histórico</button>
        </div>

        @if ($action === 'mark.as.done')
        <button type="button" class="px-4 py-2 font-semibold text-sm text-white rounded-none shadow-sm bg-lime-600 hover:bg-lime-500 flex gap-2" data-key="{{ $key ?? '' }}" data-action="{{$action}}">
            <x-icons.svg type="done" />
            Concluir
        </button>
        @elseif($action === 'mark.as.pendent')
        <button type="button" class="px-4 py-2 font-semibold text-sm text-white drop-shadow-md shadow-red rounded-none shadow-sm bg-amber-400 hover:bg-amber-300 flex gap-2" data-key="{{ $key ?? '' }}" data-action="{{$action}}">
            <x-icons.svg type="wait" />
            Pendente
        </button>
        @endif

    </div>

@if (!empty($link))
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="self-center shrink-0 stroke-red-500 w-6 h-6 mx-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" />
    </svg>
</a>
@else
</div>
@endif
