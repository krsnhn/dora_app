<div class="flash-container">
    @foreach(['success', 'error', 'warning', 'info'] as $type)
        @if(session($type))
            <div class="flash flash-{{ $type }}">
                {{ session($type) }}
                <button class="flash-close" type="button" onclick="this.parentElement.remove()">&times;</button>
            </div>
        @endif
    @endforeach
</div>
