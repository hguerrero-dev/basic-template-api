@props([
    'pagination' => null,
    'tableClass' => 'min-w-full divide-y border-b border-gray-200 divide-gray-200',
    'theadClass' => 'bg-gray-50',
    'tbodyClass' => 'bg-white divide-y divide-gray-200',
])

<div {{ $attributes->merge(['class' => 'overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 bg-white']) }}>
    <div class="overflow-x-auto">
        <table class="{{ $tableClass }}">
            <thead class="{{ $theadClass }}">
                <tr>
                    {{ $headers ?? '' }}
                </tr>
            </thead>
            <tbody class="{{ $tbodyClass }}">
                {{ $slot }}
            </tbody>
        </table>
    </div>
    
    @if($pagination)
        <div class="px-6 py-4 border-t border-gray-200 {{ $theadClass }}">
            {{ $pagination }}
        </div>
    @endif
</div>