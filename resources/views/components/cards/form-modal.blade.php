@props([
    'id' => 'component-unique',
    'title' => 'Ação perigosa',
    'message' => 'Tem certeza que deseja realizar esta auteração?',
    'continue' => 'Continuar',
    'cancel' => 'Cancelar',
])

<div class="relative z-10 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true" id="{{ $id }}">
    <!--
      Background backdrop, show/hide based on modal state.

      Entering: "ease-out duration-300"
        From: "opacity-0"
        To: "opacity-100"
      Leaving: "ease-in duration-200"
        From: "opacity-100"
        To: "opacity-0"
    -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <!--
          Modal panel, show/hide based on modal state.

          Entering: "ease-out duration-300"
            From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            To: "opacity-100 translate-y-0 sm:scale-100"
          Leaving: "ease-in duration-200"
            From: "opacity-100 translate-y-0 sm:scale-100"
            To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        -->
            <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white dark:bg-gray-800 px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                            <h3 class="text-base font-semibold leading-6 dark:text-white text-slate-800" id="modal-title">{{ $title }}</h3>
                            <form
                                class="mt-2"
                                role="form"
                                method="POST"
                                action="{{ '#' }}"
                                enctype="multipart/form-data"
                            >

                            <label class="flex flex-col font-normal dark:text-white text-slate-800">
                                <span class="mb-2 text-sm">Tíulo</span>
                                <div class="relative">
                                    <input
                                        class="border-w-6003 placeholder:text-w-4004 w-full rounded-lg border bg-transparent text-white focus:border-white h-10 p-[10px]"
                                        type="text"
                                        required
                                        name="title"
                                    >
                                </div>
                            </label>

                            <label class="flex flex-col font-normal dark:text-white text-slate-800">
                                <span class="mb-2 text-sm">Descrição</span>
                                <div class="relative">
                                    <input
                                        class="border-w-6003 placeholder:text-w-4004 w-full rounded-lg border bg-transparent text-white focus:border-white h-10 p-[10px]"
                                        type="text"
                                        required
                                        name="description"
                                    >
                                </div>
                            </label>

                            </form>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" data-event-modal-active="" class="inline-flex w-full justify-center rounded-md bg-lime-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-lime-500 sm:ml-3 sm:w-auto">{{ $continue }}</button>
                    <button type="button" class="close-modal mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm ring-inset hover:bg-red-500 sm:mt-0 sm:w-auto">{{ $cancel }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const _id = '{{ $id }}';
        const _title = '{{ $title }}';
        const _message = '{{ $message }}';
        const $card = document.querySelector('[name="template_card"]').innerHTML
        const $button = document.querySelector(`[data-open-modal='${_id}']`);
        const $modal = document.querySelector(`#${_id}`);
        const $column = document.querySelector('[name=pendent]')


        $modal.querySelector('.close-modal').addEventListener('click', function () {
            $modal.classList.add('hidden')
        })

        if ($button) {
            $button.addEventListener('click', function () {
                $modal.classList.remove('hidden')
                const $actionBtn = $modal.querySelector('[data-event-modal-active]')

                $actionBtn.addEventListener('click', function () {
                    const $form = $modal.querySelector('form')
                    const _data = new FormData($form)
                    const $keys = Object.keys(Object.fromEntries(_data.entries()))
                    let $formIsValid = []

                    $keys.map((name) => {
                        const $el = $form.querySelector(`[name="${name}"]`)

                        if($el.required && $el.value.trim().length == 0) {
                            $formIsValid.push(name)
                            $el.classList.add('border-red-600')
                        } else {
                            $el.classList.remove('border-red-600')

                        }
                    })

                    if ($formIsValid.length)
                    {
                        return;
                    }

                    fetch($button.dataset.eventModal, {
                        method: 'POST',
                        mode: 'cors',
                        cache: 'no-cache',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            '_token': document.querySelector('meta[name="csrf-token"]').content,
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            '_method': 'POST',
                        },
                        body: JSON.stringify(Object.fromEntries(_data.entries())),
                    })
                    .then(async (response) => {
                        if (response.status === 201) {
                            const _json = await response.json()

                            $column.innerHTML = `${
                                $card
                                    .replace('$TEMPLATE_TITLE$', _json.title)
                                    .replace('$TEMPLATE_DESCRIPTION$', _json.description)
                                    .replace('$TEMPLATE_KEY$', _json.id)
                            }${$column.innerHTML}`

                            $modal.classList.add('hidden')
                        } else {
                            alert(response.message)
                        }
                    })
                })
            })
        }
    })
</script>
@endpush
