<x-layout>
    <div>
        <header class="py-8 md:py-12">
            <h1 class="text-3xl font-bold">Ideas</h1>
            <p class="text-muted-foreground text-sm mt-2">Capture Your Ideas</p>

            <x-card
                x-data
                @click="$dispatch('open-modal', 'create-idea')"
                is="button"
                type="button"
                data-test="create-idea-button"
                class="mt-10 cursor-pointer h-32 w-full text-left"
                >
                <p> What's your big idea? </p>
            </x-card>
        </header>

        <div>
            <a href="/ideas" class="btn {{  request()->has('status') ? 'btn-outlined' : '' }}">All</a>

            @foreach (App\IdeaStatus::cases() as $status)
                <a
                    href="/ideas?status={{ $status->value }}"
                    class="btn {{ request('status') === $status->value ? '' : 'btn-outlined' }}"
                >
                    {{ $status->label() }} <span class="text-xs pl-3">{{ $statusCounts->get($status->value) }}</span>
                </a>
            @endforeach
        </div>

        <div class="mt-10 text-muted-foreground">
            <div class="grid md:grid-cols-2 gap-6">
                {{-- looping through idea cards --}}
                @forelse ($ideas as $idea)
                    <x-card href="{{ route('idea.show', $idea) }}">

                        @if ($idea->image_path)
                            <div class="mb-4 -mx-4 -mt-4 rounded-t-lg overflow-hidden">
                                <img src="{{ asset('storage/' . $idea->image_path) }}" alt="" class="w-full h-48 object-cover">
                            </div>
                        @endif

                        <h3 class="text-foreground text-lg">{{ $idea->title }}</h3>

                        <div class="mt-1">
                            <x-idea.status-label status="{{ $idea->status }}">
                                {{ $idea->status->label() }}
                            </x-idea.status-label>
                        </div>

                        <div class="mt-5 line-clamp-3">{{ $idea->description }}</div>

                        {{-- shows how long ago the timestamp was e.g.2 mins or 2 months ago --}}
                        <div class="mt-4">{{ $idea->created_at->diffForHumans() }}</div>
                    </x-card>

                @empty
                    <x-card>
                        <p>You haven't added any ideas yet.</p>
                    </x-card>
                @endforelse
            </div>
        </div>


        {{-- creating Create Idea Modal FORM --}}
        <x-modal name="create-idea" title="New Idea">
            <form
                x-data="{
                    status: 'pending',
                    newLink: '',
                    links: [],
                    newStep: '',
                    steps: []
                }"
                method="POST"
                action="{{ route('idea.store') }}"
                @submit="if ($refs.image.files.length === 0) { $refs.image.disabled = true; $el.removeAttribute('enctype') } else { $el.setAttribute('enctype', 'multipart/form-data') }"
            >
                @csrf

                <div class="space-y-6">
                    <x-form.field
                        label="Title"
                        name="title"
                        placeholder="Enter My Big Idea"
                        autofocus
                        required
                    />

                <div class="space-y-2">
                    <label for="status" class="label">Status</label>

                    <div class="flex gap-x-3">
                        @foreach (App\IdeaStatus::cases() as $status)
                        <button
                        type="button"
                        @click="status = @js($status->value)"
                        data-test="button-status-{{ $status->value }}"
                        class="btn flex-1 h-10"
                        {{-- if current selected status is equal to one in loop, do nothing. otherwise set a button outlined class --}}
                        :class="status === @js($status->value) ? '' : 'btn-outlined'">
                            {{ $status->label() }}
                        </button>
                        @endforeach

                        <input type="hidden" name="status" :value="status" class="input"/>
                    </div>

                    <x-form.error name="status" />
                </div>


                    <x-form.field
                        label="Description"
                        name="description"
                        type="textarea"
                        placeholder="Enter a detailed description of your idea"
                    />

                    {{-- --------- START ADDING IMAGE ----------- --}}
                    <div class="space-y-2">
                        <label for="image" class="label">Featured Image</label>

                        <input
                            type="file"
                            name="image"
                            x-ref="image"
                            accept="image/*">
                            <x-form.error name="image" />

                    </div>
                    {{-- --------- END ADDING IMAGE ----------- --}}

                    {{-- --------- START STEPS ----------- --}}
                    <div>
                    <fieldset class="space-y-3">
                        <legend class="label">Actionable Steps</legend>

                        <template x-for="(step, index) in steps" :key="step">
                            <div class="flex gap-x-2 items-center">
                                <input name="steps[]" x-model="step" class="input" readonly>

                                <button
                                    type="button"
                                        {{-- should use aria label to describe button without text for accessibility - screen readers --}}
                                    aria-label="Remove step"
                                        {{-- index remove 1 step item --}}
                                    @click="steps.splice(index, 1)"
                                    class="form-muted-icon"
                                >
                                    <x-icons.close/>
                                </button>
                            </div>
                        </template>

                        <div class="flex gap-x-2 items-center">
                            <input
                                x-model="newStep"
                                id="new-step"
                                data-test="new-step"
                                placeholder="What needs to be done?"
                                class="input flex-1"
                                spellcheck="false"
                            >

                            <button
                                type="button"
                                @click="steps.push(newStep.trim()); newStep='';"
                                data-test="submit-new-step-button"
                                :disabled="newStep.trim() === 0"

                                {{-- should use aria label to describe button without text for accessibility - screen readers --}}
                                aria-label="Add new step"
                                class="form-muted-icon"
                                >

                                {{-- rotating close button to look like plus button --}}
                                <x-icons.close class="rotate-45" />
                            </button>
                        </div>

                    </fieldset>
                </div>
                {{-- --------- END STEPS ----------- --}}

                <div>
                    <fieldset class="space-y-3">
                        <legend class="label">Links</legend>

                        <template x-for="(link, index) in links" :key="link">
                            <div class="flex gap-x-2 items-center">
                                <input name="links[]" x-model="link" class="input">

                                <button
                                    type="button"
                                    {{-- should use aria label to describe button without text for accessibility - screen readers --}}
                                    aria-label="Remove link"
                                    {{-- index remove 1 link item --}}
                                    @click="links.splice(index, 1)"
                                    class="form-muted-icon"
                                >
                                    <x-icons.close/>
                                </button>
                            </div>
                        </template>

                        <div class="flex gap-x-2 items-center">
                            <input
                                x-model="newLink"
                                type="url"
                                id="new-link"
                                data-test="new-link"
                                placeholder="https://example.com"
                                autocomplete="url"
                                class="input flex-1"
                                spellcheck="false"
                            >

                            <button
                                type="button"
                                @click="links.push(newLink.trim()); newLink='';"
                                data-test="submit-new-link-button"
                                :disabled="newLink.trim() === 0"

                                {{-- should use aria label to describe button without text for accessibility - screen readers --}}
                                aria-label="Add new link"
                                class="form-muted-icon"
                                >

                                {{-- rotating close button to look like plus button --}}
                                <x-icons.close class="rotate-45" />
                            </button>
                        </div>

                    </fieldset>
                </div>


                <div class="flex justify-end gap-x-5">
                    <button type="button" @click="$dispatch('close-modal')">Cancel</button>
                    <button type="submit" class="btn">Create Idea</button>
                </div>
                </div>
            </form>
        </x-modal>
    </div>
</x-layout>
