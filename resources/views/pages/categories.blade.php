<link rel="stylesheet" href="{{ asset('css/categories.css') }}">

<!-- Explore Categories Section -->
<section class="section categories">

    <div class="container">

        <h2 class="section__title">
            Explore Categories
        </h2>

        <div class="row">

            @foreach ($categories as $category)

                <div class="col-xs-6 col-sm-4 col-lg-2">

                    <div class="categories__item">

                        <a href="{{ route('events.index', ['category' => $category->id]) }}">

                            <img src="{{ $category->image ? asset('storage/' . $category->image) : asset('image/category1.webp') }}"
                                class="categories__img"
                                alt="{{ $category->name }}">

                            <p class="categories__name">
                                {{ $category->name }}
                            </p>

                        </a>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</section>
