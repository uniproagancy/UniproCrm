<div>
    @if (session()->has('message'))
        <div style="color: green; margin-bottom: 10px;">
            {{ session('message') }}
        </div>
    @endif
    <form wire:submit="login">
        <input type="text" wire:model="email">
        <input type="password" wire:model="password">
        <button type="submit">Save</button>
    </form>
</div>
