@extends('layouts.layout')

@section('content')
    <div class="container">
        <div class="profile-edit-container">
            <h1 class="profile-edit-title">Редактировать профиль</h1>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="profile-edit-form">
                @csrf
                @method('PUT')

                <!-- Имя -->
                <div class="form-group">
                    <label for="name">Имя</label>
                    <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required class="form-input">
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required class="form-input">
                </div>

                <!-- Аватар -->
                <div class="form-group">
                    <label for="avatar">Аватар</label>
                    <input type="file" id="avatar" name="avatar" accept="image/*" class="form-input">
                    <div style="position: relative; display: inline-block; margin-top: 10px;">
                        <div style="position: relative;">
                            <img id="avatar-preview" src="#" alt="Предпросмотр аватара" style="display: none; max-width: 150px;">
                            <span id="clear-avatar-icon" style="display: none; position: absolute; top: 0px; right: 0px; cursor: pointer; color: red; font-size: 1.5rem; font-weight: bold;">×</span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                <a href="{{ route('profile.password') }}" class="btn btn-secondary" style="margin-left: 1rem;">Сменить пароль</a>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const avatarUpload = document.getElementById('avatar');
    const avatarPreview = document.getElementById('avatar-preview');
    const clearAvatarIcon = document.getElementById('clear-avatar-icon');

    avatarUpload.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                avatarPreview.src = e.target.result;
                avatarPreview.style.display = 'block';
                clearAvatarIcon.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            avatarPreview.src = '#';
            avatarPreview.style.display = 'none';
            clearAvatarIcon.style.display = 'none';
        }
    });

    clearAvatarIcon.addEventListener('click', function() {
        avatarUpload.value = ''; // Clear the file input
        avatarPreview.src = '#';
        avatarPreview.style.display = 'none';
        clearAvatarIcon.style.display = 'none';
    });
</script>
@endpush

