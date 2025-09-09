<div>
    <!-- User Profile Component -->
    <h2>User Profile</h2>
    <div>Name: {{ $user->name }}</div>
    <div>Email: {{ $user->email }}</div>
    <button wire:click="editProfile" class="btn btn-primary mt-2">Edit Profile</button>
</div>
