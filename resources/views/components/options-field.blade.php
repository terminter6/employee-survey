@props([
    'options' => [],
    'name' => 'options_temp',
])

<div class="space-y-2" id="options-rows-container">
    @foreach($options as $index => $option)
        <div class="option-row flex gap-2 items-center">
            <input
                type="text"
                name="{{ $name }}[]"
                value="{{ $option }}"
                placeholder="Вариант ответа"
                class="form-control flex-1"
            />
            <button type="button" onclick="this.parentElement.remove(); updateOptionsHidden();" class="btn btn-error btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
    @endforeach
</div>

<button type="button" id="add-option-btn" class="btn btn-primary btn-sm mt-2" onclick="addOptionRow();">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
    Добавить вариант
</button>

<input type="hidden" name="options" id="options-hidden-field" value="{{ json_encode($options) }}" />

<script>
function addOptionRow(value = '') {
    const container = document.getElementById('options-rows-container');
    const row = document.createElement('div');
    row.className = 'option-row flex gap-2 items-center';
    row.innerHTML = `
        <input
            type="text"
            name="{{ $name }}[]"
            value=""
            placeholder="Вариант ответа"
            class="form-control flex-1"
        />
        <button type="button" onclick="this.parentElement.remove(); updateOptionsHidden();" class="btn btn-error btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
        </button>
    `;
    container.appendChild(row);
    updateOptionsHidden();
}

function updateOptionsHidden() {
    const inputs = document.querySelectorAll('#options-rows-container input');
    const values = Array.from(inputs)
        .map(input => input.value.trim())
        .filter(value => value.length > 0);
    document.getElementById('options-hidden-field').value = JSON.stringify(values);
}

document.addEventListener('input', function(e) {
    if (e.target.closest('#options-rows-container')) {
        updateOptionsHidden();
    }
});
</script>
