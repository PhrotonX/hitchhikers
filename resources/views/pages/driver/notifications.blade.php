@extends('layouts.app')

@push('head')
    <style>
        /* --- Driver Sidebar Navigation --- */
        .driver-nav { display: flex; flex-direction: column; gap: 5px; margin: -10px; }
        .driver-nav-link { display: flex; align-items: center; gap: 15px; padding: 15px 20px; text-decoration: none; font-weight: 500; font-size: 1rem; color: var(--text-light); transition: all 0.2s ease-in-out; }
        .driver-nav-link i { width: 20px; }
        .driver-nav-link:hover { background-color: var(--background-hover); color: var(--primary); }
        .driver-nav-link.active { background-color: var(--primary-light); color: white; border-right: 4px solid var(--accent); }
        body.dark-mode .driver-nav-link:hover { color: var(--accent); }
        body.dark-mode .driver-nav-link.active { background-color: var(--primary); }

        /* --- Notification Page Layout --- */
        .notification-page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 2px solid var(--border);
            padding-bottom: 20px;
        }
        .notification-page-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
        }
        body.dark-mode .notification-page-header h1 {
            color: white;
        }
        .btn-secondary {
            padding: 10px 20px;
            font-size: 0.9rem;
            font-weight: 600;
            border: 1px solid var(--border);
            border-radius: 8px;
            cursor: pointer;
            background: var(--card-bg);
            color: var(--primary);
            transition: all 0.2s;
        }
        .btn-secondary:hover {
            background-color: var(--background-hover);
            border-color: var(--primary);
        }
        body.dark-mode .btn-secondary {
            background: #334155;
            color: var(--text-light);
            border-color: #4b5563;
        }
        body.dark-mode .btn-secondary:hover {
            background: #4b5563;
            color: white;
        }
        
        .notification-list-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .notification-list-container h2 {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-light);
            margin-top: 30px;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .notification-item {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            padding: 20px;
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            margin-bottom: 15px;
            transition: box-shadow 0.2s;
        }
        .notification-item:hover {
            box-shadow: var(--shadow);
        }
        .notification-item.unread {
            background: var(--light-blue-bg, #f4f8ff);
            border-left: 5px solid var(--primary);
        }
        
        body.dark-mode .notification-item {
            background: #1a243d;
            border-color: #334155;
        }
        body.dark-mode .notification-item.unread {
            background: #334155;
            border-left-color: var(--accent);
        }

        .notification-icon {
            font-size: 1.5rem;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .notification-icon.ride-alert {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .notification-icon.doc-alert {
            background-color: #feF3c7;
            color: #92400e;
        }
        .notification-icon.system-alert {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .notification-content {
            flex: 1;
        }
        .notification-content p {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 500;
            color: var(--text-dark);
            line-height: 1.5;
        }
        body.dark-mode .notification-content p {
            color: white;
        }
        .notification-content span {
            font-size: 0.9rem;
            color: var(--text-light);
            margin-top: 5px;
            display: block;
        }

    </style>
@endpush

@section('content')

    <div class="main-layout">

        <aside class="mlay-side">
            <nav class="driver-nav">
                <a href="{{ url('driver/dashboard') }}" class="driver-nav-link">
                    <i class="fa-solid fa-tachometer-alt"></i> {{ __('Dashboard') }}
                </a>
                <a href="{{ url('driver/rides') }}" class="driver-nav-link">
                    <i class="fa-solid fa-car"></i> {{ __('Ride Management') }}
                </a>
                <a href="{{ url('driver/earnings') }}" class="driver-nav-link">
                    <i class="fa-solid fa-dollar-sign"></i> {{ __('Earnings') }}
                </a>
                <a href="{{ url('driver/profile') }}" class="driver-nav-link">
                    <i class="fa-solid fa-user-gear"></i> {{ __('My Profile') }}
                </a>
                <a href="{{ url('driver/notifications') }}" class="driver-nav-link active">
                    <i class="fa-solid fa-bell"></i> {{ __('Notifications') }}
                </a>
            </nav>
        </aside>

        <main class="main-content">
            
            <div class="notification-page-header">
                <h1>{{ __('Notifications') }}</h1>
                <button class="btn-secondary" id="mark-all-read-btn">{{ __('Mark all as read') }}</button>
            </div>

            <div class="notification-list-container">
                
                @if($newNotifications->count() > 0)
                    <h2>{{ __('New') }}</h2>

                    @foreach($newNotifications as $notification)
                        <div class="notification-item unread" data-notification-id="{{ $notification->id }}">
                            <span class="notification-icon {{ $notification->type }}-alert">
                                @switch($notification->type)
                                    @case('ride')
                                        <i class="fa-solid fa-car"></i>
                                        @break
                                    @case('doc')
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        @break
                                    @case('system')
                                        <i class="fa-solid fa-bullhorn"></i>
                                        @break
                                    @default
                                        <i class="fa-solid fa-bell"></i>
                                @endswitch
                            </span>
                            <div class="notification-content">
                                <p>{{ $notification->message }}</p>
                                <span>{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                @endif

                @if($earlierNotifications->count() > 0)
                    <h2>{{ __('Earlier') }}</h2>

                    @foreach($earlierNotifications as $notification)
                        <div class="notification-item" data-notification-id="{{ $notification->id }}">
                            <span class="notification-icon {{ $notification->type }}-alert">
                                @switch($notification->type)
                                    @case('ride')
                                        <i class="fa-solid fa-car"></i>
                                        @break
                                    @case('doc')
                                        <i class="fa-solid fa-check"></i>
                                        @break
                                    @case('system')
                                        <i class="fa-solid fa-dollar-sign"></i>
                                        @break
                                    @default
                                        <i class="fa-solid fa-bell"></i>
                                @endswitch
                            </span>
                            <div class="notification-content">
                                <p>{{ $notification->message }}</p>
                                <span>{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                @endif

                @if($newNotifications->count() == 0 && $earlierNotifications->count() == 0)
                    <p style="text-align: center; padding: 40px; color: var(--text-light);">
                        {{ __('No notifications yet.') }}
                    </p>
                @endif

            </div>

        </main>
    </div>

@endsection

@push('scripts')
    <script>
        // Mark all as read
        document.getElementById('mark-all-read-btn')?.addEventListener('click', function() {
            console.log('Marking all notifications as read...');
            
            // Disable button temporarily
            this.disabled = true;
            this.textContent = '{{ __("Marking...") }}';
            
            // Make AJAX call to mark all as read
            // fetch('/driver/notifications/mark-all-read', { method: 'POST' })
            //     .then(response => response.json())
            //     .then(data => {
            //         if (data.success) {
            //             location.reload();
            //         }
            //     });
        });

        // Mark individual notification as read on click
        document.querySelectorAll('.notification-item.unread').forEach(item => {
            item.addEventListener('click', function() {
                const notificationId = this.dataset.notificationId;
                // fetch(`/driver/notifications/${notificationId}/read`, { method: 'POST' })
                //     .then(response => response.json())
                //     .then(data => {
                //         if (data.success) {
                //             this.classList.remove('unread');
                //         }
                //     });
            });
        });
    </script>
@endpush
