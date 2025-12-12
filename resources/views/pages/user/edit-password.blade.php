<div class="card" style="margin-top: 20px;">
    <div class="card-header">
        <h2 class="card-title"><i class="fas fa-lock"></i> {{ __('passwords.update_password') }}</h2>
        <p style="margin: 5px 0 0 0; font-size: 14px; color: #666;">{{ __('passwords.update_password_msg') }}</p>
    </div>
    <div class="card-body">
        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom: 20px;">
                <label><i class="fas fa-key"></i> {{ __('passwords.current_password') }}</label>
                <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;" placeholder="Enter current password">
                @error('current_password')
                    <span style="color: #dc3545; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label><i class="fas fa-lock"></i> {{ __('passwords.new_password') }}</label>
                <input id="update_password_password" name="password" type="password" autocomplete="new-password" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;" placeholder="Enter new password">
                @error('password')
                    <span style="color: #dc3545; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-bottom: 20px;">
                <label><i class="fas fa-lock"></i> {{ __('passwords.password_confirmation') }}</label>
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;" placeholder="Confirm new password">
                @error('password_confirmation')
                    <span style="color: #dc3545; font-size: 14px; display: block; margin-top: 5px;">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ __('string.save') }}
                </button>

                @if (session('status') === 'password-updated')
                    <span style="color: #28a745; margin-left: 15px;">
                        <i class="fas fa-check-circle"></i> {{ __('Saved.') }}
                    </span>
                @endif
            </div>
        </form>
    </div>
</div>