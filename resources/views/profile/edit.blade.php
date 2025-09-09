@extends('layouts.app')
@section('title','Profile')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="p-4 bg-white shadow sm:rounded-lg">
        @include('profile.partials.update-profile-information-form')
    </div>
    <div class="p-4 bg-white shadow sm:rounded-lg">
        @include('profile.partials.update-password-form')
    </div>
    <div class="p-4 bg-white shadow sm:rounded-lg">
        @include('profile.partials.delete-user-form')
    </div>
</div>
@endsection
