@extends('layouts.app')

@section('title', 'All Categories — TheTrove')

@section('content')

<section class="categories-page">
  <div class="container">
    <div class="categories-header">
      <h1>All Categories</h1>
      <p>Explore every treasure category on TheTrove</p>
    </div>
    
    <div class="categories-grid">
      @foreach($categories as $category)
        <div class="category-card" onclick="window.location.href='{{ route('category.products', $category->id_cat) }}'">
          <div class="category-card-icon">
            <img src="{{ asset('images/' . ($category->icon ?? 'default.png')) }}" alt="{{ $category->nom }}">
          </div>
          <div class="category-card-info">
            <h3>{{ $category->nom }}</h3>
            <span class="category-count">{{ $category->produits_count ?? 0 }} Treasures</span>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

@endsection