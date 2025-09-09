<div>
    <!-- Login/Register Component -->
    <h2>Login or Register</h2>
    <form wire:submit.prevent="login">
        <input type="email" wire:model.defer="email" placeholder="Email" class="input input-bordered" />
        <input type="password" wire:model.defer="password" placeholder="Password" class="input input-bordered mt-2" />
        <button type="submit" class="btn btn-primary mt-2">Login</button>
    </form>
    <button wire:click="showRegister" class="btn btn-link mt-2">Register</button>
</div>
