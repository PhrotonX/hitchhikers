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

        /* --- Page Layout --- */
        .earnings-layout {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            align-items: flex-start;
        }
        
        @media (max-width: 1024px) {
            .earnings-layout { grid-template-columns: 1fr; }
        }

        /* --- Generic Card Style --- */
        .card {
            background-color: var(--card-bg);
            border-radius: 12px;
            padding: 24px;
            box-shadow: var(--shadow);
            margin-bottom: 30px;
        }
        body.dark-mode .card {
            background-color: #1a243d;
            border: 1px solid #334155;
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border);
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .card-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0;
        }
        body.dark-mode .card-title { color: white; }

        /* --- Button Styles --- */
        .btn {
            padding: 12px 15px;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
        }
        .btn-primary { background-color: var(--primary); color: white; }
        .btn-primary:hover { background-color: var(--primary-light); }
        .btn-secondary { background-color: var(--border); color: var(--text-dark); }
        .btn-secondary:hover { background-color: #d1d5db; }

        /* --- Earnings Summary Cards --- */
        .earnings-summary-card {
            text-align: center;
        }
        .earnings-label {
            font-size: 1.1rem;
            font-weight: 500;
            color: var(--text-light);
            margin-bottom: 10px;
        }
        .earnings-amount {
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary);
            margin: 0;
        }
        body.dark-mode .earnings-amount {
            color: var(--accent);
        }
        .payout-info {
            font-size: 0.9rem;
            color: var(--text-light);
            margin-top: 15px;
        }

        /* --- Earnings Filter Tabs --- */
        .earnings-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            border-bottom: 2px solid var(--border);
        }
        .tab-button {
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            background: none;
            cursor: pointer;
            color: var(--text-light);
            border-bottom: 3px solid transparent;
        }
        .tab-button.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }
        body.dark-mode .tab-button.active {
            color: var(--accent);
            border-bottom-color: var(--accent);
        }

        /* --- Transaction List --- */
        .transaction-list {
            list-style: none;
            padding: 0;
        }
        .transaction-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 10px;
            border-bottom: 1px solid var(--border);
        }
        .transaction-item:last-child {
            border-bottom: none;
        }
        .transaction-details {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .transaction-icon {
            font-size: 1.5rem;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .transaction-icon.ride {
            background-color: #d1fae5;
            color: #065f46;
        }
        .transaction-icon.payout {
            background-color: #dbeafe;
            color: #1e40af;
        }
        .transaction-info p {
            margin: 0;
            font-weight: 600;
            color: var(--text-dark);
        }
        body.dark-mode .transaction-info p { color: white; }
        .transaction-info span {
            font-size: 0.9rem;
            color: var(--text-light);
        }
        .transaction-amount {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--secondary);
        }
        .transaction-amount.negative {
            color: var(--error);
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
                <a href="{{ url('driver/earnings') }}" class="driver-nav-link active">
                    <i class="fa-solid fa-dollar-sign"></i> {{ __('Earnings') }}
                </a>
                <a href="{{ url('driver/profile') }}" class="driver-nav-link">
                    <i class="fa-solid fa-user-gear"></i> {{ __('My Profile') }}
                </a>
                <a href="{{ url('driver/notifications') }}" class="driver-nav-link">
                    <i class="fa-solid fa-bell"></i> {{ __('Notifications') }}
                </a>
            </nav>
        </aside>

        <main class="main-content">
            
            <div class="earnings-layout">

                <div class="earnings-col-left">
                    
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title">{{ __('Earnings History') }}</h2>
                        </div>
                        <div class="card-body">
                            <div class="earnings-tabs">
                                <button class="tab-button active" id="tab-day" data-period="day">{{ __('Today') }}</button>
                                <button class="tab-button" id="tab-week" data-period="week">{{ __('This Week') }}</button>
                                <button class="tab-button" id="tab-month" data-period="month">{{ __('This Month') }}</button>
                            </div>
                            
                            <div class="tab-content" id="content-day">
                                <h3 class="earnings-label" style="text-align: left;">
                                    {{ __('Today\'s Total:') }} 
                                    <span class="earnings-amount" style="font-size: 2rem;" id="period-total">
                                        ₱{{ number_format($todayEarnings, 2) }}
                                    </span>
                                </h3>
                                
                                <ul class="transaction-list" id="transaction-list">
                                    @forelse($todayTransactions as $transaction)
                                        <li class="transaction-item">
                                            <div class="transaction-details">
                                                <span class="transaction-icon ride"><i class="fa-solid fa-car"></i></span>
                                                <div class="transaction-info">
                                                    <p>{{ __('Ride Fare') }}</p>
                                                    <span>{{ __('Passenger:') }} {{ $transaction->passenger->name ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            <span class="transaction-amount">+ ₱{{ number_format($transaction->amount, 2) }}</span>
                                        </li>
                                    @empty
                                        <li style="text-align: center; padding: 20px;">
                                            <p>{{ __('No earnings yet for this period.') }}</p>
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="earnings-col-right">

                    <div class="card earnings-summary-card">
                        <h3 class="earnings-label">{{ __('Total Earnings (All Time)') }}</h3>
                        <p class="earnings-amount" id="total-earnings">₱{{ number_format($totalEarnings, 2) }}</p>
                    </div>

                    <div class="card earnings-summary-card">
                        <h3 class="earnings-label">{{ __('Pending Payout') }}</h3>
                        <p class="earnings-amount" id="pending-payout" style="color: var(--secondary);">₱{{ number_format($pendingPayout, 2) }}</p>
                        <p class="payout-info">{{ __('Next payout scheduled for') }} {{ $nextPayoutDate->format('M d, Y') }}</p>
                        <button class="btn btn-primary" style="width: 100%; margin-top: 10px;" id="cashout-btn">{{ __('Cash Out Now') }}</button>
                    </div>

                </div>

            </div>

        </main>
    </div>
@endsection

@push('scripts')
    <script>
        // Handle tab switching
        const tabs = document.querySelectorAll('.tab-button');
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const period = this.dataset.period;
                
                // Remove 'active' from all tabs
                tabs.forEach(t => t.classList.remove('active'));
                // Add 'active' to the clicked tab
                this.classList.add('active');
                
                // In a real app, fetch data for the selected period
                console.log('Loading earnings for period: ' + period);
                
                // fetch(`/driver/earnings/${period}`)
                //     .then(response => response.json())
                //     .then(data => {
                //         document.getElementById('period-total').textContent = '₱' + data.total.toFixed(2);
                //         // Update transaction list...
                //     });
            });
        });

        // Handle cash out button
        document.getElementById('cashout-btn')?.addEventListener('click', function() {
            console.log('Initiating cash out...');
            // Implement cash out logic
            // fetch('/driver/earnings/cashout', { method: 'POST' })
        });
    </script>
@endpush
