@props(['idea' => new App\Models\Idea()])


{{-- creating Create Idea Modal FORM --}}

{{-- exists property returns true or false and therefore it doesnt literally mean 'if' it exists it just means whether or not the result to 'exists' is true or false --}}
{{-- easy way to determine whether we should call it edit or create idea ? 'edit-idea' : 'create-idea' --}}
        <x-modal name="{{  $idea->exists ? 'edit-idea' : 'create-idea' }}" title="{{ $idea->exists ? 'Edit Idea' : 'New Idea' }}">
            <form
                x-data="{
                    status: @js(old('status', $idea->status->value)),
                    newLink: '',
                    links: @js(old('links', $idea->links ?? [])),
                    newStep: '',
                    steps: @js(old('steps', $idea->steps->map->only(['id', 'description', 'completed'])))
                }"
                method="POST"
                {{-- if idea exists, make request to update. otherwise make request to store --}}
                action="{{ $idea->exists ? route('idea.update', $idea) : route('idea.store') }}"
                enctype="multipart/form-data"
            >

                @csrf

                @if ($idea->exists)
                    @method('PATCH')
                @endif

                <div class="space-y-6">
                    <x-form.field
                        label="Title"
                        name="title"
                        placeholder="Enter My Big Idea"
                        autofocus
                        required
                        {{-- colon means "evaluate this as PHP" in Blade components. --}}
                        :value="$idea->title"
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
                        :value="$idea->description"
                    />

                    {{-- --------- START ADDING IMAGE ----------- --}}
                    <div class="space-y-2">
                        <label for="image" class="label">Featured Image</label>

                        {{-- if there is an image, display it --}}
                        @if ($idea->image_path)
                            <div class="space-y-2">
                                <img src="{{ asset('storage/' . $idea->image_path) }}" alt="{{ $idea->title }}"
                                    class="w-full h-48 object-cover rounded-lg">

                                {{-- can use form attribute inside a button tag in a form to make a form request inside a form --}}
                                <button class="btn btn-outlined h-10 mt-5 w-full" form="delete-image-form">Remove Image</button>
                            </div>
                        @endif

                        <input type="file" name="image" accept="image/*" x-ref="image">
                        <x-form.error name="image" />

                    </div>
                    {{-- --------- END ADDING IMAGE ----------- --}}

                    {{-- --------- START STEPS ----------- --}}
                    <div>
                    <fieldset class="space-y-3">
                        <legend class="label">Actionable Steps</legend>

                        <template x-for="(step, index) in steps" :key="step.id || index">
                            <div class="flex gap-x-2 items-center">
                                <input :name="`steps[${index}][description]`" x-model="step.description" class="input" readonly>
                                <input type="hidden" :name="`steps[${index}][completed]`" :value="step.completed ? '1' : '0'">

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
                                @click="
                                    steps.push({ description: newStep.trim(), completed: false });
                                    newStep='';
                                "
                                data-test="submit-new-step-button"
                                :disabled="newStep.trim().length === 0"

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
                                placeholder="http://example.com"
                                autocomplete="url"
                                class="input flex-1"
                                spellcheck="false"
                            >

                                <button
                                    type="button"
                                    @click="links.push(newLink.trim()); newLink='';"
                                    data-test="submit-new-link-button"
                                    :disabled="newLink.trim().length === 0"

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
                        <button type="submit" class="btn">{{ $idea->exists ? 'Update' : 'Create' }}</button>
                    </div>
                </div>
            </form>

            {{-- only send through image if idea has associated image --}}
            @if ($idea->image_path)
                <form
                    method="POST"
                    action="{{ route('idea.image.destroy', $idea) }}"
                    id="delete-image-form">
                    @csrf
                    @method('DELETE')
                </form>
            @endif
        </x-modal>
