<nav class="breadcrumb" aria-label="Breadcrumb">

    @foreach ($items as $item)

        @if (!$loop->last)

            <a
                href="{{ $item['url'] }}"
                class="breadcrumb__link"
            >
                {{ $item['label'] }}
            </a>

            <span
                class="breadcrumb__separator"
                aria-hidden="true"
            >
                /
            </span>

        @else

            <span
                class="breadcrumb__current"
                aria-current="page"
            >
                {{ $item['label'] }}
            </span>

        @endif

    @endforeach

</nav>