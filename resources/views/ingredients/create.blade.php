@extends('layouts.layout')

@section('title', 'Добавить ингредиент')

@section('content')
    <div class="container category-create-container">
        <div class="category-create-card">
            <h1 class="category-create-title">Добавить ингредиент</h1>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <form action="{{ route('ingredients.store') }}" method="POST" class="category-create-form">
                @csrf
                <div class="form-group">
                    <label for="name" class="form-label">Название ингредиента</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Например: Молоко">
                </div>
                <button type="submit" class="btn btn-success category-create-btn">Добавить ингредиент</button>
            </form>
        </div>
    </div>
@endsection

@push('styles')
<style>
.category-create-container {
    max-width: 520px;
    margin: 40px auto 0 auto;
    padding: 0 10px;
}
.category-create-card {
    background: var(--card-bg) !important;
    color: var(--text) !important;
    border: 1px solid var(--border) !important;
    border-radius: 18px;
    box-shadow: 0 4px 32px rgba(58,90,143,0.10);
    padding: 2.5rem 2.5rem 2rem 2.5rem;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.category-create-title {
    font-size: 2.2rem;
    font-weight: 700;
    color: var(--text) !important;
    margin-bottom: 2rem;
    text-align: center;
}
.category-create-form {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}
.category-create-form .form-group {
    width: 100%;
}
.category-create-form .form-label {
    font-weight: 600;
    color: var(--primary) !important;
    margin-bottom: 0.5rem;
    display: block;
    font-size: 1.08rem;
}
.category-create-form .form-control {
    border-radius: 8px;
    background: var(--card-bg) !important;
    color: var(--text) !important;
    border: 1.5px solid var(--border) !important;
    font-size: 1.1rem;
    padding: 0.7rem 1.1rem;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.category-create-form .form-control:focus {
    border-color: var(--primary) !important;
    box-shadow: 0 0 0 2px rgba(74,144,226,0.10);
    outline: none;
}
.category-create-btn {
    width: 100%;
    font-size: 1.15rem;
    font-weight: 600;
    padding: 0.9rem 0;
    border-radius: 8px;
    background: #28a745 !important;
    color: #fff !important;
    border: none;
    box-shadow: 0 2px 8px rgba(40,167,69,0.08);
    transition: background 0.18s, box-shadow 0.18s;
}
.category-create-btn:hover {
    background: #218838 !important;
    color: #fff !important;
    box-shadow: 0 4px 16px rgba(40,167,69,0.13);
}
</style>
@endpush 